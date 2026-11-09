<?php

namespace App\Http\Controllers\Admin;

use App\Models\LendingTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LendingController extends AdminController
{
    public function index(Request $request)
    {
        $query = $this->filtered($request);
        [$sort, $dir] = $this->sortColumn($request, ['date', 'amount', 'borrower_name', 'due_date'], 'date');

        $loans = $query
            ->orderBy($sort, $dir)->orderBy('id', $dir)
            ->paginate($this->perPage($request))
            ->withQueryString()
            ->through(fn (LendingTransaction $l) => $this->row($l));

        $today = now()->toDateString();
        $open = LendingTransaction::where('is_returned', false);
        $overdue = (clone $open)->whereNotNull('due_date')->whereDate('due_date', '<', $today);

        return Inertia::render('Lending/Index', [
            'loans' => $loans,
            'filters' => $request->only(['search', 'status', 'year', 'from', 'to', 'sort', 'dir', 'per_page']),
            'summary' => [
                'lent' => (float) LendingTransaction::sum('amount'),
                'outstanding' => (float) (clone $open)->sum(DB::raw('amount - returned_amount')),
                'returned' => (float) LendingTransaction::sum('returned_amount'),
                'overdue_count' => (clone $overdue)->count(),
                'overdue_amount' => (float) (clone $overdue)->sum(DB::raw('amount - returned_amount')),
                'open_count' => (clone $open)->count(),
            ],
            'people' => LendingTransaction::where('is_returned', false)
                ->selectRaw('borrower_name as name, SUM(amount - returned_amount) as owed, COUNT(*) as n, MIN(date) as since')
                ->groupBy('borrower_name')
                ->havingRaw('SUM(amount - returned_amount) > 0')
                ->orderByDesc('owed')
                ->limit(8)
                ->get()
                ->map(fn ($p) => ['name' => $p->name, 'outstanding' => (float) $p->owed, 'count' => (int) $p->n, 'since' => $p->since]),
        ]);
    }

    public function store(Request $request)
    {
        LendingTransaction::create($this->validated($request));

        return back()->with('success', 'Loan added.');
    }

    public function update(Request $request, LendingTransaction $lending)
    {
        $lending->update($this->validated($request, true));

        return back()->with('success', 'Loan updated.');
    }

    public function destroy(LendingTransaction $lending)
    {
        $lending->delete();

        return back()->with('success', 'Loan deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $n = LendingTransaction::whereIn('id', $this->bulkIds($request))->delete();

        return back()->with('success', "{$n} loan(s) deleted.");
    }

    /** Money coming back from the borrower (partial or full). */
    public function recordReturn(Request $request, LendingTransaction $lending)
    {
        if ($lending->is_returned) {
            throw ValidationException::withMessages(['amount' => 'This loan is already fully returned.']);
        }

        $outstanding = round($lending->outstanding, 2);
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $outstanding],
        ], ['amount.max' => 'The amount cannot be more than the outstanding ' . number_format($outstanding, 2) . '.']);

        $lending->returned_amount = (float) $lending->returned_amount + (float) $data['amount'];
        if ((float) $lending->returned_amount >= (float) $lending->amount) {
            $lending->is_returned = true;
        }
        $lending->save();

        return back()->with('success', $lending->is_returned ? 'Loan fully returned' : 'Partial return recorded');
    }

    private function filtered(Request $request): Builder
    {
        $query = LendingTransaction::query();

        if ($request->filled('year')) {
            $this->applyPeriod($query, $request);
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(fn ($q) => $q->where('borrower_name', 'like', "%{$search}%")->orWhere('notes', 'like', "%{$search}%"));
        }

        match ($request->input('status')) {
            'outstanding' => $query->where('is_returned', false),
            'overdue' => $query->where('is_returned', false)->whereNotNull('due_date')->whereDate('due_date', '<', now()->toDateString()),
            'returned' => $query->where('is_returned', true),
            default => null,
        };

        return $query;
    }

    private function validated(Request $request, bool $editing = false): array
    {
        $rules = [
            'date' => ['required', 'date'],
            'borrower_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
        if ($editing) {
            $rules['is_returned'] = ['boolean'];
        }

        return $request->validate($rules, [], ['borrower_name' => 'borrower']);
    }

    private function row(LendingTransaction $l): array
    {
        return [
            'id' => $l->id,
            'date' => $l->date?->toDateString(),
            'borrower_name' => $l->borrower_name,
            'amount' => (float) $l->amount,
            'returned_amount' => (float) $l->returned_amount,
            'outstanding' => $l->outstanding,
            'due_date' => $l->due_date?->toDateString(),
            'is_returned' => $l->is_returned,
            'is_overdue' => $l->is_overdue,
            'notes' => $l->notes,
            'status' => $l->status,
        ];
    }
}
