<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Investment;
use App\Services\FinanceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends AdminController
{
    public function index(Request $request, FinanceService $finance)
    {
        $query = $this->filtered($request);
        [$sort, $dir] = $this->sortColumn($request, ['date', 'amount', 'created_at'], 'date');

        $summary = (clone $query)->selectRaw('COUNT(*) as n, COALESCE(SUM(amount),0) as total, COALESCE(MAX(amount),0) as max')->first();

        $expenses = $query->with('category.parent_category')
            ->orderBy($sort, $dir)->orderBy('id', $dir)
            ->paginate($this->perPage($request))
            ->withQueryString()
            ->through(fn (Expense $e) => $this->row($e));

        $year = $this->chartYear($request);
        $monthly = $finance->sumByMonth(Expense::query(), $year);
        $lifetime = $finance->sumByMonth(Investment::where('is_lifetime', true), $year);

        // Categories flagged "show in stats" — yearly totals including subcategories.
        $pinned = ExpenseCategory::where('is_stats', true)->orderBy('name')->get()->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->parent ? optional($c->parent_category)->name . ' › ' . $c->name : $c->name,
            'icon' => $c->icon ?: optional($c->parent_category)->icon,
            'color' => $c->color ?: optional($c->parent_category)->color,
            'total' => (float) Expense::whereYear('date', $year)->whereIn('category_id', $c->idsWithChildren())->sum('amount'),
        ])->sortByDesc('total')->values();

        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'filters' => $request->only(['search', 'category', 'year', 'from', 'to', 'sort', 'dir', 'estimated', 'per_page', 'bill']),
            'bill' => $request->filled('bill') ? \App\Models\RecurringBill::find($request->input('bill'))?->only('id', 'name') : null,
            'summary' => ['count' => (int) $summary->n, 'total' => (float) $summary->total, 'max' => (float) $summary->max],
            'chartYear' => $year,
            'monthly' => [
                'labels' => FinanceService::MONTHS,
                'expense' => array_map(fn ($m) => $monthly[$m] ?? 0, range(1, 12)),
                'lifetime' => array_map(fn ($m) => $lifetime[$m] ?? 0, range(1, 12)),
            ],
            'pinned' => $pinned,
        ]);
    }

    public function store(Request $request)
    {
        Expense::create($this->validated($request));

        return back()->with('success', 'Expense added.');
    }

    public function update(Request $request, Expense $expense)
    {
        $expense->update($this->validated($request));

        return back()->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $n = Expense::whereIn('id', $this->bulkIds($request))->delete();

        return back()->with('success', "{$n} expense(s) deleted.");
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->with('category.parent_category')->orderBy('date')->orderBy('id')->cursor()
            ->map(fn (Expense $e) => [
                $e->date?->toDateString(),
                $e->category?->parent_category?->name ?? $e->category?->name,
                $e->category?->parent_category ? $e->category->name : '',
                number_format($e->amount, 2, '.', ''),
                $e->remarks,
            ]);

        return $this->csv('expenses-' . now()->format('Y-m-d') . '.csv', ['Date', 'Category', 'Subcategory', 'Amount', 'Note'], $rows);
    }

    private function filtered(Request $request): Builder
    {
        $query = $this->applyPeriod(Expense::query(), $request);

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('remarks', 'like', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orWhere('amount', $search);
                }
            });
        }

        if ($request->filled('category') && $cat = ExpenseCategory::find($request->input('category'))) {
            $query->whereIn('category_id', $cat->idsWithChildren());
        }

        if ($request->filled('bill')) {
            $query->where('recurring_bill_id', (int) $request->input('bill'));
        }

        if ($request->input('estimated') === 'hide') {
            $query->where(fn ($q) => $q->whereNull('remarks')->orWhere('remarks', 'not like', '[ESTIMATED]%'));
        } elseif ($request->input('estimated') === 'only') {
            $query->where('remarks', 'like', '[ESTIMATED]%');
        }

        return $query;
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'date' => ['required', 'date'],
            'category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ], [], ['category_id' => 'category', 'remarks' => 'note']);
    }

    private function row(Expense $e): array
    {
        $cat = $e->category;
        $parent = $cat?->parent_category;

        return [
            'id' => $e->id,
            'date' => $e->date?->toDateString(),
            'amount' => (float) $e->amount,
            'remarks' => $e->remarks,
            'category_id' => $e->category_id,
            'category' => $cat ? [
                'name' => $cat->name,
                'parent' => $parent?->name,
                'icon' => $cat->icon ?: $parent?->icon,
                'color' => $cat->color ?: $parent?->color,
            ] : null,
            'is_estimated' => $e->is_estimated,
            'recurring_bill_id' => $e->recurring_bill_id,
        ];
    }
}
