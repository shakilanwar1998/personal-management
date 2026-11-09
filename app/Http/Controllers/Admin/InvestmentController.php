<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\Investment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class InvestmentController extends AdminController
{
    public function index(Request $request)
    {
        $query = $this->filtered($request);
        [$sort, $dir] = $this->sortColumn($request, ['date', 'amount', 'company_name'], 'date');

        $investments = $query
            ->orderBy($sort, $dir)->orderBy('id', $dir)
            ->paginate($this->perPage($request))
            ->withQueryString()
            ->through(fn (Investment $i) => $this->row($i));

        $all = Investment::query();

        return Inertia::render('Investments/Index', [
            'investments' => $investments,
            'filters' => $request->only(['search', 'status', 'year', 'from', 'to', 'sort', 'dir', 'per_page']),
            'summary' => [
                'invested' => (float) (clone $all)->sum('amount'),
                'outstanding' => (float) Investment::where('is_returned', false)->sum(DB::raw('amount - returned_amount')),
                'returned' => (float) Investment::sum('returned_amount'),
                'lifetime' => (float) Investment::where('is_lifetime', true)->sum('amount'),
                'profit' => (float) Investment::sum(DB::raw('GREATEST(returned_amount - amount, 0)')),
                'active_count' => Investment::where('is_returned', false)->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        Investment::create($this->validated($request));

        return back()->with('success', 'Investment added.');
    }

    public function update(Request $request, Investment $investment)
    {
        $investment->update($this->validated($request, true));

        return back()->with('success', 'Investment updated.');
    }

    public function destroy(Investment $investment)
    {
        $investment->delete();

        return back()->with('success', 'Investment deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $n = Investment::whereIn('id', $this->bulkIds($request))->delete();

        return back()->with('success', "{$n} investment(s) deleted.");
    }

    /**
     * Record money coming back from an investment (same rules as the old panel):
     * - anything received above the principal is booked once as "Investment Profit" income;
     * - fully recovered → closed;
     * - "final settlement" with less than the principal back → the shortfall is booked as an
     *   "Investment Loss" expense and the investment is closed.
     */
    public function recordReturn(Request $request, Investment $investment)
    {
        if ($investment->is_returned || $investment->is_lifetime) {
            throw ValidationException::withMessages(['amount' => 'This investment cannot receive returns.']);
        }

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'final' => ['boolean'],
            'date' => ['nullable', 'date'],
        ]);
        $date = $data['date'] ?? now()->toDateString();

        DB::transaction(function () use ($investment, $data, $date) {
            $invested = $investment->rawAmount();
            $prevReturned = $investment->rawReturnedAmount();
            $newReturned = $prevReturned + (float) $data['amount'];

            $profitDelta = max(0, $newReturned - $invested) - max(0, $prevReturned - $invested);
            if ($profitDelta > 0) {
                Income::create([
                    'date' => $date,
                    'amount' => round($profitDelta, 2),
                    'income_source' => 'Investment Profit',
                    'remarks' => 'Profit from ' . $investment->company_name,
                ]);
            }

            $investment->returned_amount = $newReturned;

            if ($newReturned >= $invested) {
                $investment->is_returned = true;
            } elseif (! empty($data['final'])) {
                $loss = $invested - $newReturned;
                if ($loss > 0) {
                    $category = ExpenseCategory::firstOrCreate(
                        ['name' => 'Investment Loss', 'parent' => 0],
                        ['is_stats' => true, 'icon' => 'trending-down', 'color' => 'red']
                    );
                    Expense::create([
                        'date' => $date,
                        'amount' => round($loss, 2),
                        'remarks' => 'Loss from Investment of ' . $investment->company_name,
                        'category_id' => $category->id,
                    ]);
                }
                $investment->is_returned = true;
            }

            $investment->save();
        });

        return back()->with('success', $investment->is_returned ? 'Investment closed' : 'Partial return recorded');
    }

    private function filtered(Request $request): Builder
    {
        $query = Investment::query();

        // These pages default to all time; a year or custom range can still be picked.
        if ($request->filled('year')) {
            $this->applyPeriod($query, $request);
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(fn ($q) => $q->where('company_name', 'like', "%{$search}%")
                ->orWhere('purpose', 'like', "%{$search}%")
                ->orWhere('notes', 'like', "%{$search}%"));
        }

        match ($request->input('status')) {
            'active' => $query->where('is_returned', false)->where('is_lifetime', false)->where('returned_amount', '<=', 0),
            'lifetime' => $query->where('is_returned', false)->where('is_lifetime', true),
            'partial' => $query->where('is_returned', false)->where('is_lifetime', false)->where('returned_amount', '>', 0),
            'returned' => $query->where('is_returned', true),
            default => null,
        };

        return $query;
    }

    private function validated(Request $request, bool $editing = false): array
    {
        $rules = [
            'company_name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'is_lifetime' => ['boolean'],
            'return_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
        if ($editing) {
            $rules['is_returned'] = ['boolean'];
        }

        $data = $request->validate($rules, [], ['company_name' => 'company / person']);
        $data['is_lifetime'] = (bool) ($data['is_lifetime'] ?? false);

        return $data;
    }

    private function row(Investment $i): array
    {
        return [
            'id' => $i->id,
            'company_name' => $i->company_name,
            'date' => $i->date?->toDateString(),
            'purpose' => $i->purpose,
            'amount' => $i->rawAmount(),
            'returned_amount' => $i->rawReturnedAmount(),
            'outstanding' => $i->is_returned ? 0.0 : $i->outstanding,
            'profit' => $i->profit,
            'is_lifetime' => $i->is_lifetime,
            'is_returned' => $i->is_returned,
            'return_date' => $i->return_date?->toDateString(),
            'notes' => $i->notes,
            'status' => $i->status,
        ];
    }
}
