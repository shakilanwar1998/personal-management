<?php

namespace App\Http\Controllers\Admin;

use App\Models\Budget;
use App\Models\ExpenseCategory;
use App\Services\FinanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BudgetController extends AdminController
{
    public function index(Request $request, FinanceService $finance)
    {
        $month = $this->month($request);
        $isCurrent = $month->isSameMonth(now());
        $daysInMonth = $month->daysInMonth;
        $daysElapsed = $isCurrent ? now()->day : $daysInMonth;

        $spend = $finance->spendByCategory($month);
        $totalSpend = array_sum($spend);

        $categories = ExpenseCategory::orderBy('name')->get();
        $budgets = Budget::with('category.parent_category')->get();

        $rows = $budgets->map(function (Budget $b) use ($spend, $totalSpend, $isCurrent, $daysElapsed, $daysInMonth) {
            if ($b->expense_category_id && ! $b->category) {
                return null;
            }

            $spent = $b->expense_category_id
                ? array_sum(array_intersect_key($spend, array_flip($b->category->idsWithChildren())))
                : $totalSpend;
            $ratio = $b->amount > 0 ? $spent / $b->amount : 0;
            $cat = $b->category;

            return [
                'id' => $b->id,
                'expense_category_id' => $b->expense_category_id,
                'overall' => ! $b->expense_category_id,
                'name' => $cat ? $cat->name : 'Overall spending',
                'parent' => $cat?->parent_category?->name,
                'icon' => $cat ? ($cat->icon ?: $cat->parent_category?->icon) : 'wallet',
                'color' => $cat ? ($cat->color ?: $cat->parent_category?->color) : 'indigo',
                'amount' => $b->amount,
                'is_active' => $b->is_active,
                'notes' => $b->notes,
                'spent' => $spent,
                'remaining' => $b->amount - $spent,
                'percent' => round($ratio * 100, 1),
                'status' => $ratio >= 1 ? 'over' : ($ratio >= 0.85 ? 'near' : 'ok'),
                'projected' => $isCurrent && $daysElapsed > 0 ? $spent / $daysElapsed * $daysInMonth : null,
            ];
        })->filter()->sortBy([['overall', 'desc'], ['is_active', 'desc'], ['percent', 'desc']])->values();

        // Top-level categories with spending this month but no budget of their own (or on a child).
        $budgetedIds = $budgets->pluck('expense_category_id')->filter()->all();
        $unbudgeted = $categories->where('parent', 0)->map(function ($p) use ($spend, $budgetedIds, $categories) {
            $ids = array_merge([$p->id], $categories->where('parent', $p->id)->pluck('id')->all());
            if (array_intersect($ids, $budgetedIds)) {
                return null;
            }
            $spent = array_sum(array_intersect_key($spend, array_flip($ids)));

            return $spent > 0 ? ['id' => $p->id, 'name' => $p->name, 'icon' => $p->icon, 'color' => $p->color, 'spent' => $spent] : null;
        })->filter()->sortByDesc('spent')->values();

        $active = $rows->where('is_active', true);
        $categoryBudgets = $active->where('overall', false);
        $overall = $active->firstWhere('overall', true);

        return Inertia::render('Budgets/Index', [
            'month' => [
                'value' => $month->format('Y-m'),
                'label' => $month->format('F Y'),
                'prev' => $month->copy()->subMonthNoOverflow()->format('Y-m'),
                'next' => $month->copy()->addMonthNoOverflow()->format('Y-m'),
                'is_current' => $isCurrent,
                'days_elapsed' => $daysElapsed,
                'days_in_month' => $daysInMonth,
            ],
            'budgets' => $rows,
            'totals' => [
                'budgeted' => (float) $categoryBudgets->sum('amount'),
                'spent_budgeted' => (float) $categoryBudgets->sum('spent'),
                'overall_limit' => $overall['amount'] ?? null,
                'month_spend' => $totalSpend,
                'over_count' => $active->where('status', 'over')->count(),
                'near_count' => $active->where('status', 'near')->count(),
            ],
            'unbudgeted' => $unbudgeted,
            'history' => $this->history($finance, $month, $budgets),
        ]);
    }

    public function store(Request $request)
    {
        Budget::create($this->validated($request));

        return back()->with('success', 'Budget created.');
    }

    public function update(Request $request, Budget $budget)
    {
        $budget->update($this->validated($request, $budget));

        return back()->with('success', 'Budget updated.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return back()->with('success', 'Budget deleted.');
    }

    private function month(Request $request): Carbon
    {
        $value = (string) $request->input('month', '');
        if (preg_match('/^(\d{4})-(\d{2})$/', $value, $m) && (int) $m[2] >= 1 && (int) $m[2] <= 12) {
            return Carbon::create((int) $m[1], (int) $m[2], 1)->startOfMonth();
        }

        return now()->startOfMonth();
    }

    /** Budgeted total (current active budgets) vs actual spending, for the 6 months ending at $month. */
    private function history(FinanceService $finance, Carbon $month, $budgets): array
    {
        $active = $budgets->where('is_active', true);
        $overall = $active->firstWhere('expense_category_id', null);
        $categoryBudgets = $active->filter(fn ($b) => $b->expense_category_id && $b->category);
        $limit = $overall ? $overall->amount : (float) $categoryBudgets->sum('amount');

        $out = ['labels' => [], 'budget' => [], 'spent' => []];
        for ($i = 5; $i >= 0; $i--) {
            $m = $month->copy()->subMonthsNoOverflow($i);
            $spend = $finance->spendByCategory($m);
            if ($overall) {
                $spent = array_sum($spend);
            } else {
                $ids = $categoryBudgets->flatMap(fn ($b) => $b->category->idsWithChildren())->unique()->all();
                $spent = array_sum(array_intersect_key($spend, array_flip($ids)));
            }
            $out['labels'][] = $m->format('M');
            $out['budget'][] = $limit;
            $out['spent'][] = $spent;
        }

        return $out;
    }

    private function validated(Request $request, ?Budget $budget = null): array
    {
        $data = $request->validate([
            'expense_category_id' => [
                'nullable', 'integer', 'exists:expense_categories,id',
                Rule::unique('budgets', 'expense_category_id')->ignore($budget?->id),
            ],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'expense_category_id.unique' => 'This category already has a budget.',
        ], ['expense_category_id' => 'category']);

        // Only one overall (no-category) budget is allowed.
        if (empty($data['expense_category_id'])) {
            $exists = Budget::whereNull('expense_category_id')->when($budget, fn ($q) => $q->where('id', '!=', $budget->id))->exists();
            if ($exists) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'expense_category_id' => 'An overall budget already exists. Edit it instead.',
                ]);
            }
            $data['expense_category_id'] = null;
        }

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
