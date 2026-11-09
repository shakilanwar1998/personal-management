<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\RecurringBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RecurringBillController extends AdminController
{
    public function index()
    {
        $today = now()->startOfDay();
        $weekEnd = now()->addDays(7)->endOfDay();

        $bills = RecurringBill::with('category.parent_category')
            ->withCount('expenses')
            ->withSum('expenses', 'amount')
            ->orderBy('next_due_date')
            ->orderBy('name')
            ->get();

        $rows = $bills->map(function (RecurringBill $b) use ($today, $weekEnd) {
            $cat = $b->category;
            $parent = $cat?->parent_category;
            $due = $b->next_due_date;

            $group = ! $b->is_active ? 'paused'
                : ($due->lt($today) ? 'overdue' : ($due->lte($weekEnd) ? 'week' : 'upcoming'));

            return [
                'id' => $b->id,
                'name' => $b->name,
                'amount' => $b->amount,
                'expense_category_id' => $b->expense_category_id,
                'category' => $cat ? [
                    'name' => $cat->name,
                    'parent' => $parent?->name,
                    'icon' => $cat->icon ?: $parent?->icon,
                    'color' => $cat->color ?: $parent?->color,
                ] : null,
                'frequency' => $b->frequency,
                'next_due_date' => $due->toDateString(),
                'is_active' => $b->is_active,
                'last_paid_at' => $b->last_paid_at?->toDateString(),
                'notes' => $b->notes,
                'payments_count' => (int) $b->expenses_count,
                'payments_total' => (float) $b->expenses_sum_amount,
                'monthly' => round($b->monthlyEquivalent(), 2),
                'group' => $group,
            ];
        });

        $active = $bills->where('is_active', true);
        $monthly = $active->sum(fn ($b) => $b->monthlyEquivalent());

        return Inertia::render('Bills/Index', [
            'bills' => $rows->values(),
            'summary' => [
                'monthly' => round($monthly, 2),
                'yearly' => round($monthly * 12, 2),
                'active' => $active->count(),
                'due_week' => $rows->whereIn('group', ['overdue', 'week'])->count(),
                'due_week_amount' => (float) $rows->whereIn('group', ['overdue', 'week'])->sum('amount'),
                'overdue' => $rows->where('group', 'overdue')->count(),
            ],
            'frequencies' => RecurringBill::FREQUENCIES,
        ]);
    }

    public function store(Request $request)
    {
        RecurringBill::create($this->validated($request));

        return back()->with('success', 'Bill added.');
    }

    public function update(Request $request, RecurringBill $bill)
    {
        $bill->update($this->validated($request));

        return back()->with('success', 'Bill updated.');
    }

    public function destroy(RecurringBill $bill)
    {
        // Past payments stay as normal expenses; only the link to the bill is removed.
        DB::transaction(function () use ($bill) {
            Expense::where('recurring_bill_id', $bill->id)->update(['recurring_bill_id' => null]);
            $bill->delete();
        });

        return back()->with('success', 'Bill deleted. Past payments were kept as expenses.');
    }

    /** Record a payment: creates an expense and moves the due date forward one period. */
    public function pay(Request $request, RecurringBill $bill)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if (! $bill->expense_category_id) {
            return back()->with('error', 'Give this bill a category before recording a payment.');
        }

        DB::transaction(function () use ($bill, $data) {
            Expense::create([
                'date' => $data['date'],
                'amount' => $data['amount'],
                'category_id' => $bill->expense_category_id,
                'recurring_bill_id' => $bill->id,
                'remarks' => ($data['note'] ?? null) ?: $bill->name,
            ]);

            $bill->last_paid_at = $data['date'];
            $bill->next_due_date = $bill->nextDateAfter($bill->next_due_date);
            $bill->save();
        });

        return back()->with('success', "{$bill->name} paid. Next due " . $bill->next_due_date->format('j M Y') . '.');
    }

    /** Skip this period without paying. */
    public function skip(RecurringBill $bill)
    {
        $bill->next_due_date = $bill->nextDateAfter($bill->next_due_date);
        $bill->save();

        return back()->with('success', "Skipped. {$bill->name} is next due " . $bill->next_due_date->format('j M Y') . '.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999'],
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'frequency' => ['required', Rule::in(RecurringBill::FREQUENCIES)],
            'next_due_date' => ['required', 'date'],
            'is_active' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [], ['expense_category_id' => 'category', 'next_due_date' => 'next due date']);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
