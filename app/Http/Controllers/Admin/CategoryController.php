<?php

namespace App\Http\Controllers\Admin;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\RecurringBill;
use App\Services\YearService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CategoryController extends AdminController
{
    public function index(Request $request)
    {
        $year = YearService::selected();

        $yearSums = Expense::whereYear('date', $year)
            ->selectRaw('category_id, SUM(amount) as total, COUNT(*) as n')
            ->groupBy('category_id')->get()->keyBy('category_id');
        $allCounts = Expense::selectRaw('category_id, COUNT(*) as n')
            ->groupBy('category_id')->pluck('n', 'category_id');
        $budgets = Budget::pluck('amount', 'expense_category_id');

        $cats = ExpenseCategory::orderBy('sort_order')->orderBy('name')->get();

        $node = function (ExpenseCategory $c) use ($yearSums, $allCounts, $budgets) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'parent' => (int) $c->parent,
                'icon' => $c->icon,
                'color' => $c->color,
                'is_stats' => (bool) $c->is_stats,
                'description' => $c->description,
                'year_total' => (float) ($yearSums[$c->id]->total ?? 0),
                'year_count' => (int) ($yearSums[$c->id]->n ?? 0),
                'all_count' => (int) ($allCounts[$c->id] ?? 0),
                'budget' => isset($budgets[$c->id]) ? (float) $budgets[$c->id] : null,
            ];
        };

        $tree = $cats->where('parent', 0)->map(function ($p) use ($cats, $node) {
            $row = $node($p);
            $children = $cats->where('parent', $p->id)->map(fn ($c) => array_merge($node($c), [
                'icon_inherited' => ! $c->icon,
                'icon' => $c->icon ?: $p->icon,
                'color' => $c->color ?: $p->color,
            ]))->values();
            $row['children'] = $children;
            $row['tree_total'] = $row['year_total'] + $children->sum('year_total');
            $row['tree_count'] = $row['all_count'] + $children->sum('all_count');

            return $row;
        })->values();

        $incomeSums = Income::whereYear('date', $year)
            ->selectRaw('income_source, SUM(amount) as total')->groupBy('income_source')->pluck('total', 'income_source');
        $incomeCounts = Income::selectRaw('income_source, COUNT(*) as n')->groupBy('income_source')->pluck('n', 'income_source');

        $income = IncomeCategory::ordered()->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'icon' => $c->icon,
            'color' => $c->color,
            'sort_order' => $c->sort_order,
            'year_total' => (float) ($incomeSums[$c->name] ?? 0),
            'all_count' => (int) ($incomeCounts[$c->name] ?? 0),
        ])->values();

        return Inertia::render('Categories/Index', [
            'tab' => $request->input('tab') === 'income' ? 'income' : 'expense',
            'tree' => $tree,
            'income' => $income,
            'chartYear' => $year,
            'stats' => [
                'expense_parents' => $cats->where('parent', 0)->count(),
                'expense_children' => $cats->where('parent', '!=', 0)->count(),
                'income' => $income->count(),
            ],
        ]);
    }

    // ---------- Expense categories ----------

    public function storeExpense(Request $request)
    {
        $data = $this->validatedExpense($request);
        $c = ExpenseCategory::create($data);

        return back()->with('success', "Category “{$c->name}” created.");
    }

    public function updateExpense(Request $request, ExpenseCategory $category)
    {
        $data = $this->validatedExpense($request, $category);
        $category->update($data);

        return back()->with('success', "Category “{$category->name}” updated.");
    }

    public function destroyExpense(ExpenseCategory $category)
    {
        $reasons = [];
        if ($n = Expense::where('category_id', $category->id)->count()) {
            $reasons[] = "{$n} expense(s)";
        }
        if ($n = ExpenseCategory::where('parent', $category->id)->count()) {
            $reasons[] = "{$n} subcategory(ies)";
        }
        if (Budget::where('expense_category_id', $category->id)->exists()) {
            $reasons[] = 'a budget';
        }
        if ($n = RecurringBill::where('expense_category_id', $category->id)->count()) {
            $reasons[] = "{$n} recurring bill(s)";
        }

        if ($reasons) {
            return back()->with('error', "“{$category->name}” is used by " . implode(', ', $reasons) . '. Merge it into another category instead.');
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "Category “{$name}” deleted.");
    }

    public function mergeExpense(Request $request, ExpenseCategory $category)
    {
        $data = $request->validate([
            'target_id' => ['required', 'integer', 'exists:expense_categories,id'],
        ], [], ['target_id' => 'target category']);

        $target = ExpenseCategory::findOrFail($data['target_id']);

        if ($target->id === $category->id) {
            throw ValidationException::withMessages(['target_id' => 'A category cannot be merged into itself.']);
        }
        if ((int) $target->parent === $category->id) {
            throw ValidationException::withMessages(['target_id' => 'A category cannot be merged into one of its own subcategories.']);
        }

        $hasChildren = ExpenseCategory::where('parent', $category->id)->exists();
        if ($hasChildren && (int) $target->parent !== 0) {
            throw ValidationException::withMessages(['target_id' => 'This category has subcategories, so it can only be merged into a top-level category.']);
        }

        $moved = DB::transaction(function () use ($category, $target) {
            $moved = Expense::where('category_id', $category->id)->update(['category_id' => $target->id]);

            $sourceBudget = Budget::where('expense_category_id', $category->id)->first();
            if ($sourceBudget) {
                if (Budget::where('expense_category_id', $target->id)->exists()) {
                    $sourceBudget->delete();
                } else {
                    $sourceBudget->update(['expense_category_id' => $target->id]);
                }
            }

            RecurringBill::where('expense_category_id', $category->id)->update(['expense_category_id' => $target->id]);
            ExpenseCategory::where('parent', $category->id)->update(['parent' => $target->id]);

            $category->delete();

            return $moved;
        });

        return back()->with('success', "Merged “{$category->name}” into “{$target->name}” · {$moved} expense(s) moved.");
    }

    public function toggleStats(ExpenseCategory $category)
    {
        $category->update(['is_stats' => ! $category->is_stats]);

        return back()->with('success', $category->is_stats ? "“{$category->name}” pinned to stats." : "“{$category->name}” removed from stats.");
    }

    private function validatedExpense(Request $request, ?ExpenseCategory $category = null): array
    {
        $parent = (int) $request->input('parent', 0);

        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('expense_categories', 'name')
                    ->where(fn ($q) => $q->where('parent', $parent))
                    ->ignore($category?->id),
            ],
            'parent' => ['nullable', 'integer'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
            'is_stats' => ['boolean'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.unique' => 'A category with this name already exists here.',
        ]);

        $data['parent'] = $parent;
        $data['is_stats'] = (bool) ($data['is_stats'] ?? false);

        if ($parent !== 0) {
            $p = ExpenseCategory::find($parent);
            if (! $p || (int) $p->parent !== 0) {
                throw ValidationException::withMessages(['parent' => 'Choose a top-level category as the parent.']);
            }
            if ($category && $p->id === $category->id) {
                throw ValidationException::withMessages(['parent' => 'A category cannot be its own parent.']);
            }
            if ($category && ExpenseCategory::where('parent', $category->id)->exists()) {
                throw ValidationException::withMessages(['parent' => 'This category has subcategories, so it must stay top-level.']);
            }
        }

        return $data;
    }

    // ---------- Income categories ----------

    public function storeIncome(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:income_categories,name'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
        ], ['name.unique' => 'An income category with this name already exists.']);

        $data['sort_order'] = (int) IncomeCategory::max('sort_order') + 1;
        $c = IncomeCategory::create($data);

        return back()->with('success', "Income category “{$c->name}” created.");
    }

    public function updateIncome(Request $request, IncomeCategory $incomeCategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('income_categories', 'name')->ignore($incomeCategory->id)],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
        ], ['name.unique' => 'An income category with this name already exists.']);

        $old = $incomeCategory->name;
        $moved = DB::transaction(function () use ($incomeCategory, $data, $old) {
            $incomeCategory->update($data);

            return $old !== $data['name']
                ? Income::where('income_source', $old)->update(['income_source' => $data['name']])
                : 0;
        });

        $msg = "Income category “{$incomeCategory->name}” updated.";
        if ($moved) {
            $msg .= " {$moved} income record(s) renamed.";
        }

        return back()->with('success', $msg);
    }

    public function destroyIncome(Request $request, IncomeCategory $incomeCategory)
    {
        $used = Income::where('income_source', $incomeCategory->name)->count();

        if ($used > 0) {
            $data = $request->validate([
                'reassign_to' => [
                    'required', 'string',
                    Rule::exists('income_categories', 'name'),
                    Rule::notIn([$incomeCategory->name]),
                ],
            ], [
                'reassign_to.required' => "{$used} income record(s) use this category. Choose where to move them.",
                'reassign_to.not_in' => 'Choose a different category.',
            ], ['reassign_to' => 'category']);

            DB::transaction(function () use ($incomeCategory, $data) {
                Income::where('income_source', $incomeCategory->name)->update(['income_source' => $data['reassign_to']]);
                $incomeCategory->delete();
            });

            return back()->with('success', "Deleted “{$incomeCategory->name}” · {$used} record(s) moved to “{$data['reassign_to']}”.");
        }

        $name = $incomeCategory->name;
        $incomeCategory->delete();

        return back()->with('success', "Income category “{$name}” deleted.");
    }
}
