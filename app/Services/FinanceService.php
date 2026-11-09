<?php

namespace App\Services;

use App\Models\BorrowingTransaction;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Investment;
use App\Models\LendingTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    public const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    /**
     * Cash on hand. Same formula the old dashboard used:
     * all income − (all expenses + money still lent out + money still invested).
     */
    public function currentBalance(): float
    {
        $income = (float) Income::sum('amount');
        $expense = (float) Expense::sum('amount');

        return $income - ($expense + $this->lentOutstanding() + $this->investedOutstanding());
    }

    public function lentOutstanding(): float
    {
        return (float) LendingTransaction::where('is_returned', false)->sum(DB::raw('amount - returned_amount'));
    }

    public function investedOutstanding(): float
    {
        return (float) Investment::where('is_returned', false)->sum(DB::raw('amount - returned_amount'));
    }

    public function borrowedOutstanding(): float
    {
        return (float) BorrowingTransaction::where('is_returned', false)->sum(DB::raw('amount - returned_amount'));
    }

    /** What you own (cash + money owed to you + investments) minus what you owe. */
    public function netWorth(): array
    {
        $cash = $this->currentBalance();
        $lent = $this->lentOutstanding();
        $invested = $this->investedOutstanding();
        $borrowed = $this->borrowedOutstanding();
        $assets = $cash + $lent + $invested;

        return [
            'cash' => $cash,
            'lent' => $lent,
            'invested' => $invested,
            'assets' => $assets,
            'liabilities' => $borrowed,
            'net_worth' => $assets - $borrowed,
        ];
    }

    public function monthTotal(string $model, Carbon $month): float
    {
        return (float) $model::whereBetween('date', [
            $month->copy()->startOfMonth()->toDateString(),
            $month->copy()->endOfMonth()->toDateString(),
        ])->sum('amount');
    }

    /** Totals for one calendar year. Savings also subtracts lifetime investments made that year (as before). */
    public function yearSummary(int $year): array
    {
        $income = (float) Income::whereYear('date', $year)->sum('amount');
        $expense = (float) Expense::whereYear('date', $year)->sum('amount');
        $lifetimeInvested = (float) Investment::whereYear('date', $year)->where('is_lifetime', true)->sum('amount');
        $savings = $income - ($expense + $lifetimeInvested);

        return [
            'year' => $year,
            'income' => $income,
            'expense' => $expense,
            'lifetime_invested' => $lifetimeInvested,
            'savings' => $savings,
            'savings_rate' => $income > 0 ? round($savings / $income * 100, 1) : null,
        ];
    }

    /** Income, expense and savings for each month of the year (Jan–Dec). */
    public function monthlySeries(int $year): array
    {
        $income = $this->sumByMonth(Income::query(), $year);
        $expense = $this->sumByMonth(Expense::query(), $year);
        $rows = [];

        for ($m = 1; $m <= 12; $m++) {
            $i = $income[$m] ?? 0.0;
            $e = $expense[$m] ?? 0.0;
            $rows[] = [
                'month' => $m,
                'label' => self::MONTHS[$m - 1],
                'income' => $i,
                'expense' => $e,
                'savings' => $i - $e,
                'savings_rate' => $i > 0 ? round(($i - $e) / $i * 100, 1) : null,
            ];
        }

        return $rows;
    }

    /** @return array<int, float> month number => total */
    public function sumByMonth($query, int $year): array
    {
        return $query->whereYear('date', $year)
            ->selectRaw('MONTH(date) as m, SUM(amount) as total')
            ->groupBy('m')
            ->pluck('total', 'm')
            ->map(fn ($v) => (float) $v)
            ->all();
    }

    /**
     * Spending per top-level category for a period, with each parent's children nested.
     * Children's spending is included in the parent's total.
     */
    public function categoryBreakdown(string $from, string $to): array
    {
        $sums = Expense::whereBetween('date', [$from, $to])
            ->selectRaw('category_id, SUM(amount) as total, COUNT(*) as n')
            ->groupBy('category_id')
            ->get()
            ->keyBy('category_id');

        $cats = ExpenseCategory::orderBy('sort_order')->orderBy('name')->get();
        $out = [];

        foreach ($cats->where('parent', 0) as $p) {
            $children = [];
            $own = (float) ($sums[$p->id]->total ?? 0);
            $count = (int) ($sums[$p->id]->n ?? 0);
            $total = $own;

            foreach ($cats->where('parent', $p->id) as $c) {
                $t = (float) ($sums[$c->id]->total ?? 0);
                $n = (int) ($sums[$c->id]->n ?? 0);
                $total += $t;
                $count += $n;
                if ($t > 0) {
                    $children[] = ['id' => $c->id, 'name' => $c->name, 'total' => $t, 'count' => $n];
                }
            }

            if ($own > 0 && $children) {
                array_unshift($children, ['id' => $p->id, 'name' => $p->name . ' (general)', 'total' => $own, 'count' => (int) ($sums[$p->id]->n ?? 0)]);
            }

            if ($total > 0) {
                usort($children, fn ($a, $b) => $b['total'] <=> $a['total']);
                $out[] = [
                    'id' => $p->id,
                    'name' => $p->name,
                    'icon' => $p->icon,
                    'color' => $p->color,
                    'is_stats' => $p->is_stats,
                    'total' => $total,
                    'count' => $count,
                    'children' => $children,
                ];
            }
        }

        // Expenses whose category no longer exists.
        $known = $cats->pluck('id')->all();
        $orphan = $sums->filter(fn ($row, $id) => ! in_array($id, $known))->sum('total');
        if ($orphan > 0) {
            $out[] = ['id' => null, 'name' => 'No category', 'icon' => 'circle-help', 'color' => 'zinc', 'is_stats' => false, 'total' => (float) $orphan, 'count' => 0, 'children' => []];
        }

        usort($out, fn ($a, $b) => $b['total'] <=> $a['total']);

        return $out;
    }

    /** Income per source for a period, including sources with no category row. */
    public function incomeBySource(string $from, string $to): array
    {
        $sums = Income::whereBetween('date', [$from, $to])
            ->selectRaw('income_source, SUM(amount) as total, COUNT(*) as n')
            ->groupBy('income_source')
            ->get()
            ->keyBy('income_source');

        $out = [];
        foreach (IncomeCategory::ordered() as $c) {
            $out[] = [
                'name' => $c->name,
                'icon' => $c->icon,
                'color' => $c->color,
                'total' => (float) ($sums[$c->name]->total ?? 0),
                'count' => (int) ($sums[$c->name]->n ?? 0),
            ];
            unset($sums[$c->name]);
        }
        foreach ($sums as $name => $row) {
            $out[] = ['name' => $name ?: 'Unspecified', 'icon' => 'circle-dot', 'color' => 'zinc', 'total' => (float) $row->total, 'count' => (int) $row->n];
        }

        usort($out, fn ($a, $b) => $b['total'] <=> $a['total']);

        return $out;
    }

    /** Spending per category id for one month (used by budgets). */
    public function spendByCategory(Carbon $month): array
    {
        return Expense::whereBetween('date', [
            $month->copy()->startOfMonth()->toDateString(),
            $month->copy()->endOfMonth()->toDateString(),
        ])->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id')
            ->map(fn ($v) => (float) $v)
            ->all();
    }

    public static function growth(float $current, float $previous): ?float
    {
        if ($previous == 0.0) {
            return null;
        }

        return round(($current - $previous) / abs($previous) * 100, 1);
    }
}
