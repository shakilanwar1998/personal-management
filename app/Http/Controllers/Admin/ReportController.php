<?php

namespace App\Http\Controllers\Admin;

use App\Models\BorrowingTransaction;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Investment;
use App\Models\LendingTransaction;
use App\Services\FinanceService;
use App\Services\YearService;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Calendar-year report (1 January – 31 December).
 */
class ReportController extends AdminController
{
    public function index(Request $request, FinanceService $finance)
    {
        [$year, $compare] = $this->years($request);

        $summary = $this->summary($finance, $year);
        $compareSummary = $this->summary($finance, $compare);
        $matrix = $this->categoryMatrix($year);

        return Inertia::render('Reports/Index', [
            'year' => $year,
            'compare' => $compare,
            'years' => YearService::available(),
            'summary' => $summary,
            'compareSummary' => $compareSummary,
            'months' => $this->months($finance, $year),
            'matrix' => $matrix,
            'yoy' => $this->yearOverYear($matrix, $compare),
            'sources' => $this->sourceMatrix($year),
            'top' => $this->topExpenses($year),
            'activity' => $this->activity($year),
        ]);
    }

    public function export(Request $request, FinanceService $finance)
    {
        [$year] = $this->years($request);
        $months = FinanceService::MONTHS;
        $fmt = fn ($v) => number_format((float) $v, 2, '.', '');
        $rows = [];

        $rows[] = ["Report {$year} (January – December)"];
        $rows[] = [];
        $rows[] = ['Monthly summary'];
        $rows[] = ['Month', 'Income', 'Expenses', 'Savings', 'Savings rate %', 'Cumulative savings'];
        $series = $this->months($finance, $year);
        foreach ($series as $m) {
            $rows[] = [$m['label'], $fmt($m['income']), $fmt($m['expense']), $fmt($m['savings']), $m['savings_rate'] ?? '', $fmt($m['cumulative'])];
        }
        $ti = array_sum(array_column($series, 'income'));
        $te = array_sum(array_column($series, 'expense'));
        $rows[] = ['Total', $fmt($ti), $fmt($te), $fmt($ti - $te), $ti > 0 ? round(($ti - $te) / $ti * 100, 1) : '', ''];

        $rows[] = [];
        $rows[] = ['Spending by category'];
        $rows[] = array_merge(['Category', 'Subcategory'], $months, ['Total', 'Share %']);
        $matrix = $this->categoryMatrix($year);
        foreach ($matrix['rows'] as $r) {
            $rows[] = array_merge([$r['name'], ''], array_map($fmt, $r['months']), [$fmt($r['total']), $r['share']]);
            foreach ($r['children'] as $c) {
                $rows[] = array_merge([$r['name'], $c['name']], array_map($fmt, $c['months']), [$fmt($c['total']), '']);
            }
        }
        $rows[] = array_merge(['Total', ''], array_map($fmt, $matrix['monthTotals']), [$fmt($matrix['total']), '100']);

        $rows[] = [];
        $rows[] = ['Income by source'];
        $rows[] = array_merge(['Source'], $months, ['Total']);
        $sources = $this->sourceMatrix($year);
        foreach ($sources['rows'] as $r) {
            $rows[] = array_merge([$r['name']], array_map($fmt, $r['months']), [$fmt($r['total'])]);
        }
        $rows[] = array_merge(['Total'], array_map($fmt, $sources['monthTotals']), [$fmt($sources['total'])]);

        $header = array_shift($rows);

        return $this->csv("report-{$year}.csv", $header, $rows);
    }

    /** @return array{0:int,1:int} */
    private function years(Request $request): array
    {
        $year = ctype_digit((string) $request->input('year')) ? (int) $request->input('year') : YearService::selected();
        $compare = ctype_digit((string) $request->input('compare')) ? (int) $request->input('compare') : $year - 1;

        return [$year, $compare];
    }

    private function summary(FinanceService $finance, int $year): array
    {
        $s = $finance->yearSummary($year);
        // Average over the months that have passed (whole year for past years).
        $monthsElapsed = $year < now()->year ? 12 : ($year === now()->year ? now()->month : 0);
        $s['avg_monthly_expense'] = $monthsElapsed > 0 ? $s['expense'] / $monthsElapsed : 0;
        $s['transactions'] = Expense::whereYear('date', $year)->count() + Income::whereYear('date', $year)->count();

        return $s;
    }

    private function months(FinanceService $finance, int $year): array
    {
        $running = 0.0;

        return array_map(function ($m) use (&$running) {
            $running += $m['savings'];

            return $m + ['cumulative' => $running];
        }, $finance->monthlySeries($year));
    }

    /** Top-level categories × 12 months (children folded into their parent, also listed separately). */
    private function categoryMatrix(int $year): array
    {
        $cells = Expense::whereYear('date', $year)
            ->selectRaw('category_id, MONTH(date) as m, SUM(amount) as total')
            ->groupBy('category_id', 'm')
            ->get();

        $byCat = [];
        foreach ($cells as $c) {
            $byCat[(int) $c->category_id][(int) $c->m] = (float) $c->total;
        }

        $cats = ExpenseCategory::orderBy('sort_order')->orderBy('name')->get();
        $known = $cats->pluck('id')->all();
        $vec = fn (array $m) => array_map(fn ($i) => $m[$i] ?? 0.0, range(1, 12));
        $add = fn (array $a, array $b) => array_map(fn ($x, $y) => $x + $y, $a, $b);

        $rows = [];
        foreach ($cats->where('parent', 0) as $p) {
            $own = $vec($byCat[$p->id] ?? []);
            $months = $own;
            $children = [];
            foreach ($cats->where('parent', $p->id) as $c) {
                $cm = $vec($byCat[$c->id] ?? []);
                $months = $add($months, $cm);
                if (array_sum($cm) > 0) {
                    $children[] = ['id' => $c->id, 'name' => $c->name, 'months' => $cm, 'total' => array_sum($cm)];
                }
            }
            if ($children && array_sum($own) > 0) {
                $children[] = ['id' => $p->id, 'name' => $p->name . ' (general)', 'months' => $own, 'total' => array_sum($own)];
            }
            usort($children, fn ($a, $b) => $b['total'] <=> $a['total']);

            if (array_sum($months) > 0) {
                $rows[] = [
                    'id' => $p->id, 'name' => $p->name, 'icon' => $p->icon, 'color' => $p->color,
                    'months' => $months, 'total' => array_sum($months), 'children' => $children,
                ];
            }
        }

        // Expenses whose category was deleted.
        $orphan = array_fill(0, 12, 0.0);
        foreach ($byCat as $id => $m) {
            if (! in_array($id, $known)) {
                $orphan = $add($orphan, $vec($m));
            }
        }
        if (array_sum($orphan) > 0) {
            $rows[] = ['id' => null, 'name' => 'No category', 'icon' => 'circle-help', 'color' => 'zinc', 'months' => $orphan, 'total' => array_sum($orphan), 'children' => []];
        }

        usort($rows, fn ($a, $b) => $b['total'] <=> $a['total']);

        $total = array_sum(array_column($rows, 'total'));
        $monthTotals = array_fill(0, 12, 0.0);
        $max = 0.0;
        foreach ($rows as &$r) {
            $r['share'] = $total > 0 ? round($r['total'] / $total * 100, 1) : 0;
            $monthTotals = $add($monthTotals, $r['months']);
            $max = max($max, ...$r['months']);
        }
        unset($r);

        return ['rows' => $rows, 'monthTotals' => $monthTotals, 'total' => $total, 'max' => $max];
    }

    private function yearOverYear(array $matrix, int $compare): array
    {
        $prev = Expense::whereYear('date', $compare)
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $cats = ExpenseCategory::all(['id', 'parent', 'name', 'icon', 'color']);
        $parentOf = $cats->mapWithKeys(fn ($c) => [$c->id => $c->parent ?: $c->id]);
        $prevByParent = [];
        foreach ($prev as $catId => $sum) {
            $pid = $parentOf[$catId] ?? null;
            $prevByParent[$pid ?? 0] = ($prevByParent[$pid ?? 0] ?? 0) + (float) $sum;
        }

        $out = [];
        $seen = [];
        foreach ($matrix['rows'] as $r) {
            $key = $r['id'] ?? 0;
            $seen[$key] = true;
            $out[] = $this->yoyRow($r['id'], $r['name'], $r['icon'], $r['color'], $r['total'], $prevByParent[$key] ?? 0.0);
        }
        // Categories with spending only in the comparison year.
        foreach ($prevByParent as $pid => $sum) {
            if (isset($seen[$pid]) || $sum <= 0) {
                continue;
            }
            $c = $cats->firstWhere('id', $pid);
            $out[] = $this->yoyRow($c?->id, $c?->name ?? 'No category', $c?->icon ?? 'circle-help', $c?->color ?? 'zinc', 0.0, $sum);
        }

        usort($out, fn ($a, $b) => abs($b['change']) <=> abs($a['change']));

        return $out;
    }

    private function yoyRow($id, $name, $icon, $color, float $current, float $previous): array
    {
        return [
            'id' => $id, 'name' => $name, 'icon' => $icon, 'color' => $color,
            'current' => $current, 'previous' => $previous,
            'change' => $current - $previous,
            'change_pct' => $previous > 0 ? round(($current - $previous) / $previous * 100, 1) : null,
        ];
    }

    private function sourceMatrix(int $year): array
    {
        $cells = Income::whereYear('date', $year)
            ->selectRaw('income_source, MONTH(date) as m, SUM(amount) as total')
            ->groupBy('income_source', 'm')
            ->get();

        $bySource = [];
        foreach ($cells as $c) {
            $bySource[$c->income_source ?: 'Unspecified'][(int) $c->m] = (float) $c->total;
        }

        $meta = IncomeCategory::ordered()->keyBy('name');
        $rows = [];
        foreach ($bySource as $name => $m) {
            $months = array_map(fn ($i) => $m[$i] ?? 0.0, range(1, 12));
            $rows[] = [
                'name' => $name,
                'icon' => $meta[$name]->icon ?? 'circle-dot',
                'color' => $meta[$name]->color ?? 'zinc',
                'months' => $months,
                'total' => array_sum($months),
            ];
        }
        usort($rows, fn ($a, $b) => $b['total'] <=> $a['total']);

        $monthTotals = array_fill(0, 12, 0.0);
        foreach ($rows as $r) {
            $monthTotals = array_map(fn ($x, $y) => $x + $y, $monthTotals, $r['months']);
        }

        return ['rows' => $rows, 'monthTotals' => $monthTotals, 'total' => array_sum($monthTotals)];
    }

    private function topExpenses(int $year): array
    {
        return Expense::with('category.parent_category')
            ->whereYear('date', $year)
            ->orderByDesc('amount')->orderByDesc('date')
            ->limit(15)->get()
            ->map(fn (Expense $e) => [
                'id' => $e->id,
                'date' => $e->date?->toDateString(),
                'amount' => (float) $e->amount,
                'remarks' => $e->remarks,
                'category' => $e->category?->name,
                'parent' => $e->category?->parent_category?->name,
                'icon' => $e->category?->icon ?: $e->category?->parent_category?->icon,
                'color' => $e->category?->color ?: $e->category?->parent_category?->color,
                'is_estimated' => $e->is_estimated,
            ])->all();
    }

    /**
     * Loans and investments started during the year. Repayments are not dated individually,
     * so "returned" is what has come back so far on the items started that year.
     */
    private function activity(int $year): array
    {
        $agg = fn ($model) => $model::whereYear('date', $year)
            ->selectRaw('COUNT(*) as n, COALESCE(SUM(amount),0) as amount, COALESCE(SUM(returned_amount),0) as returned')
            ->first();

        $lent = $agg(LendingTransaction::class);
        $borrowed = $agg(BorrowingTransaction::class);
        $invested = $agg(Investment::class);
        $lifetime = (float) Investment::whereYear('date', $year)->where('is_lifetime', true)->sum('amount');

        return [
            'lent' => ['count' => (int) $lent->n, 'amount' => (float) $lent->amount, 'returned' => (float) $lent->returned],
            'borrowed' => ['count' => (int) $borrowed->n, 'amount' => (float) $borrowed->amount, 'returned' => (float) $borrowed->returned],
            'invested' => ['count' => (int) $invested->n, 'amount' => (float) $invested->amount, 'returned' => (float) $invested->returned, 'lifetime' => $lifetime],
        ];
    }
}
