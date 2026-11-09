<?php

namespace App\Http\Controllers\Admin;

use App\Models\BorrowingTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class BorrowingController extends AdminController
{
    public function index(Request $request)
    {
        $query = $this->filtered($request);
        [$sort, $dir] = $this->sortColumn($request, ['date', 'amount', 'lender_name', 'due_date'], 'date');

        $loans = $query
            ->orderBy($sort, $dir)->orderBy('id', $dir)
            ->paginate($this->perPage($request))
            ->withQueryString()
            ->through(fn (BorrowingTransaction $l) => $this->row($l));

        $today = now()->toDateString();
        $open = BorrowingTransaction::where('is_returned', false);
        $overdue = (clone $open)->whereNotNull('due_date')->whereDate('due_date', '<', $today);

        return Inertia::render('Borrowing/Index', [
            'loans' => $loans,
            'filters' => $request->only(['search', 'status', 'year', 'from', 'to', 'sort', 'dir', 'per_page']),
            'summary' => [
                'borrowed' => (float) BorrowingTransaction::sum('amount'),
                'outstanding' => (float) (clone $open)->sum(DB::raw('amount - returned_amount')),
                'returned' => (float) BorrowingTransaction::sum('returned_amount'),
                'overdue_count' => (clone $overdue)->count(),
                'overdue_amount' => (float) (clone $overdue)->sum(DB::raw('amount - returned_amount')),
                'open_count' => (clone $open)->count(),
            ],
            'people' => BorrowingTransaction::where('is_returned', false)
                ->selectRaw('lender_name as name, SUM(amount - returned_amount) as owed, COUNT(*) as n, MIN(date) as since')
                ->groupBy('lender_name')
                ->havingRaw('SUM(amount - returned_amount) > 0')
                ->orderByDesc('owed')
                ->limit(8)
                ->get()
                ->map(fn ($p) => ['name' => $p->name, 'outstanding' => (float) $p->owed, 'count' => (int) $p->n, 'since' => $p->since]),
        ]);
    }

    public function store(Request $request)
    {
        BorrowingTransaction::create($this->validated($request));

        return back()->with('success', 'Borrowing added.');
    }

    public function update(Request $request, BorrowingTransaction $borrowing)
    {
        $borrowing->update($this->validated($request, true));

        return back()->with('success', 'Borrowing updated.');
    }

    public function destroy(BorrowingTransaction $borrowing)
    {
        $borrowing->delete();

        return back()->with('success', 'Borrowing deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $n = BorrowingTransaction::whereIn('id', $this->bulkIds($request))->delete();

        return back()->with('success', "{$n} borrowing(s) deleted.");
    }

    /** A repayment you made to the lender (partial or full). */
    public function repay(Request $request, BorrowingTransaction $borrowing)
    {
        if ($borrowing->is_returned) {
            throw ValidationException::withMessages(['amount' => 'This debt is already fully repaid.']);
        }

        $outstanding = round($borrowing->outstanding, 2);
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $outstanding],
        ], ['amount.max' => 'The amount cannot be more than the outstanding ' . number_format($outstanding, 2) . '.']);

        $borrowing->returned_amount = (float) $borrowing->returned_amount + (float) $data['amount'];
        if ((float) $borrowing->returned_amount >= (float) $borrowing->amount) {
            $borrowing->is_returned = true;
        }
        $borrowing->save();

        return back()->with('success', $borrowing->is_returned ? 'Debt fully repaid' : 'Partial repayment recorded');
    }

    private function filtered(Request $request): Builder
    {
        $query = BorrowingTransaction::query();

        if ($request->filled('year')) {
            $this->applyPeriod($query, $request);
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(fn ($q) => $q->where('lender_name', 'like', "%{$search}%")->orWhere('notes', 'like', "%{$search}%"));
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
            'lender_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
        if ($editing) {
            $rules['is_returned'] = ['boolean'];
        }

        return $request->validate($rules, [], ['lender_name' => 'lender']);
    }

    private function row(BorrowingTransaction $l): array
    {
        return [
            'id' => $l->id,
            'date' => $l->date?->toDateString(),
            'lender_name' => $l->lender_name,
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
