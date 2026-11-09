<?php

namespace App\Http\Controllers\Admin;

use App\Models\Budget;
use App\Models\BorrowingTransaction;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\LendingTransaction;
use App\Models\RecurringBill;
use App\Models\SavingsGoal;
use App\Services\FinanceService;
use App\Services\YearService;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends AdminController
{
    public function __invoke(FinanceService $finance)
    {
        $year = YearService::selected();
        $now = now();
        $last = now()->subMonthNoOverflow();

        $monthIncome = $finance->monthTotal(Income::class, $now);
        $monthExpense = $finance->monthTotal(Expense::class, $now);
        $lastIncome = $finance->monthTotal(Income::class, $last);
        $lastExpense = $finance->monthTotal(Expense::class, $last);

        $summary = $finance->yearSummary($year);
        $prevSummary = $finance->yearSummary($year - 1);
        $range = YearService::range($year);

        return Inertia::render('Dashboard', [
            'year' => $year,
            'month' => [
                'label' => $now->format('F Y'),
                'short' => $now->format('M'),
                'income' => $monthIncome,
                'expense' => $monthExpense,
                'savings' => $monthIncome - $monthExpense,
                'income_growth' => FinanceService::growth($monthIncome, $lastIncome),
                'expense_growth' => FinanceService::growth($monthExpense, $lastExpense),
                'savings_growth' => FinanceService::growth($monthIncome - $monthExpense, $lastIncome - $lastExpense),
                'last_label' => $last->format('F'),
            ],
            'worth' => $finance->netWorth(),
            'summary' => $summary,
            'prevSummary' => $prevSummary,
            'series' => $finance->monthlySeries($year),
            'categories' => $finance->categoryBreakdown($range['start']->toDateString(), $range['end']->toDateString()),
            'sources' => array_values(array_filter($finance->incomeBySource($range['start']->toDateString(), $range['end']->toDateString()), fn ($s) => $s['total'] > 0)),
            'budgets' => $this->budgets($finance),
            'bills' => RecurringBill::with('category')->where('is_active', true)
                ->whereDate('next_due_date', '<=', $now->copy()->addDays(14)->toDateString())
                ->orderBy('next_due_date')->limit(6)->get()
                ->map(fn ($b) => ['id' => $b->id, 'name' => $b->name, 'amount' => $b->amount, 'next_due_date' => $b->next_due_date->toDateString(), 'icon' => $b->category?->icon, 'color' => $b->category?->color]),
            'alerts' => $this->alerts(),
            'recent' => $this->recent(),
            'goals' => SavingsGoal::withSum('contributions', 'amount')->whereNull('achieved_at')->orderBy('target_date')->limit(4)->get()
                ->map(fn ($g) => ['id' => $g->id, 'name' => $g->name, 'icon' => $g->icon, 'color' => $g->color, 'target' => $g->target_amount, 'saved' => (float) $g->contributions_sum_amount, 'target_date' => $g->target_date?->toDateString()]),
        ]);
    }

    private function budgets(FinanceService $finance): array
    {
        $spend = $finance->spendByCategory(now());
        $totalSpend = array_sum($spend);

        return Budget::with('category')->where('is_active', true)->get()->map(function (Budget $b) use ($spend, $totalSpend) {
            if (! $b->expense_category_id) {
                return ['id' => $b->id, 'name' => 'Overall spending', 'icon' => 'wallet', 'color' => 'indigo', 'amount' => $b->amount, 'spent' => $totalSpend, 'overall' => true];
            }
            if (! $b->category) {
                return null;
            }
            $spent = array_sum(array_intersect_key($spend, array_flip($b->category->idsWithChildren())));

            return ['id' => $b->id, 'name' => $b->category->name, 'icon' => $b->category->icon, 'color' => $b->category->color, 'amount' => $b->amount, 'spent' => $spent, 'overall' => false];
        })->filter()->sortByDesc(fn ($b) => $b['amount'] > 0 ? $b['spent'] / $b['amount'] : 0)->values()->take(6)->all();
    }

    private function alerts(): array
    {
        $today = now()->toDateString();
        $soon = now()->addDays(7)->toDateString();
        $alerts = [];

        foreach (LendingTransaction::where('is_returned', false)->whereNotNull('due_date')->whereDate('due_date', '<', $today)->orderBy('due_date')->get() as $l) {
            $alerts[] = ['type' => 'lent', 'title' => "{$l->borrower_name} owes you", 'amount' => $l->outstanding, 'date' => $l->due_date->toDateString(), 'href' => '/admin/lending'];
        }
        foreach (BorrowingTransaction::where('is_returned', false)->whereNotNull('due_date')->whereDate('due_date', '<=', $soon)->orderBy('due_date')->get() as $b) {
            $alerts[] = ['type' => 'borrowed', 'title' => "Repay {$b->lender_name}", 'amount' => $b->outstanding, 'date' => $b->due_date->toDateString(), 'href' => '/admin/borrowing'];
        }
        foreach (Invoice::whereNotIn('status', ['paid', 'cancelled', 'draft'])->whereNotNull('due_date')->whereDate('due_date', '<', $today)->orderBy('due_date')->get() as $i) {
            $alerts[] = ['type' => 'invoice', 'title' => "Invoice {$i->invoice_number} · {$i->client_name}", 'amount' => (float) $i->total, 'currency' => $i->currency, 'date' => $i->due_date->toDateString(), 'href' => "/admin/invoices/{$i->id}/edit"];
        }

        return $alerts;
    }

    private function recent(): array
    {
        $expenses = DB::table('expenses')
            ->leftJoin('expense_categories as c', 'c.id', '=', 'expenses.category_id')
            ->leftJoin('expense_categories as p', 'p.id', '=', 'c.parent')
            ->select('expenses.id', 'expenses.date', 'expenses.amount', 'expenses.remarks', 'c.name as label',
                DB::raw('COALESCE(c.icon, p.icon) as icon'), DB::raw('COALESCE(c.color, p.color) as color'), DB::raw("'expense' as type"))
            ->orderByDesc('expenses.date')->orderByDesc('expenses.id')->limit(8);

        $incomes = DB::table('incomes')
            ->leftJoin('income_categories as ic', 'ic.name', '=', 'incomes.income_source')
            ->select('incomes.id', 'incomes.date', 'incomes.amount', 'incomes.remarks', 'incomes.income_source as label',
                'ic.icon', 'ic.color', DB::raw("'income' as type"))
            ->orderByDesc('incomes.date')->orderByDesc('incomes.id')->limit(8);

        return $expenses->get()->concat($incomes->get())
            ->sortBy([['date', 'desc'], ['id', 'desc']])
            ->take(8)
            ->map(fn ($r) => array_merge((array) $r, ['amount' => (float) $r->amount]))
            ->values()->all();
    }
}
