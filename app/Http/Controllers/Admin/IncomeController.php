<?php

namespace App\Http\Controllers\Admin;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Services\FinanceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class IncomeController extends AdminController
{
    public function index(Request $request, FinanceService $finance)
    {
        $query = $this->filtered($request);
        [$sort, $dir] = $this->sortColumn($request, ['date', 'amount', 'created_at'], 'date');

        $summary = (clone $query)->selectRaw('COUNT(*) as n, COALESCE(SUM(amount),0) as total, COALESCE(MAX(amount),0) as max')->first();

        $looks = IncomeCategory::all()->keyBy('name');

        $incomes = $query
            ->orderBy($sort, $dir)->orderBy('id', $dir)
            ->paginate($this->perPage($request))
            ->withQueryString()
            ->through(fn (Income $i) => [
                'id' => $i->id,
                'date' => $i->date?->toDateString(),
                'amount' => (float) $i->amount,
                'income_source' => $i->income_source,
                'remarks' => $i->remarks,
                'icon' => $looks[$i->income_source]->icon ?? 'circle-dot',
                'color' => $looks[$i->income_source]->color ?? 'zinc',
            ]);

        $year = $this->chartYear($request);
        $monthly = $finance->sumByMonth(Income::query(), $year);

        return Inertia::render('Incomes/Index', [
            'incomes' => $incomes,
            'filters' => $request->only(['search', 'source', 'year', 'from', 'to', 'sort', 'dir', 'per_page']),
            'summary' => ['count' => (int) $summary->n, 'total' => (float) $summary->total, 'max' => (float) $summary->max],
            'chartYear' => $year,
            'monthly' => [
                'labels' => FinanceService::MONTHS,
                'income' => array_map(fn ($m) => $monthly[$m] ?? 0, range(1, 12)),
            ],
            'sources' => array_values(array_filter(
                $finance->incomeBySource("{$year}-01-01", "{$year}-12-31"),
                fn ($s) => $s['total'] > 0
            )),
        ]);
    }

    public function store(Request $request)
    {
        Income::create($this->validated($request));

        return back()->with('success', 'Income added.');
    }

    public function update(Request $request, Income $income)
    {
        $income->update($this->validated($request));

        return back()->with('success', 'Income updated.');
    }

    public function destroy(Income $income)
    {
        $income->delete();

        return back()->with('success', 'Income deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $n = Income::whereIn('id', $this->bulkIds($request))->delete();

        return back()->with('success', "{$n} income record(s) deleted.");
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->orderBy('date')->orderBy('id')->cursor()
            ->map(fn (Income $i) => [
                $i->date?->toDateString(),
                $i->income_source,
                number_format($i->amount, 2, '.', ''),
                $i->remarks,
            ]);

        return $this->csv('income-' . now()->format('Y-m-d') . '.csv', ['Date', 'Source', 'Amount', 'Note'], $rows);
    }

    private function filtered(Request $request): Builder
    {
        $query = $this->applyPeriod(Income::query(), $request);

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('remarks', 'like', "%{$search}%");
                if (is_numeric($search)) {
                    $q->orWhere('amount', $search);
                }
            });
        }

        if ($request->filled('source')) {
            $query->where('income_source', $request->input('source'));
        }

        return $query;
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'date' => ['required', 'date'],
            'income_source' => ['required', 'string', Rule::exists('income_categories', 'name')],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ], [], ['income_source' => 'source', 'remarks' => 'note']);
    }
}
