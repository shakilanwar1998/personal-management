<?php

namespace Tests\Feature\Admin;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\RecurringBill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillTest extends TestCase
{
    use RefreshDatabase;

    private function bill(array $attrs = []): RecurringBill
    {
        $cat = ExpenseCategory::create(['name' => 'Utility Bills', 'parent' => 0]);

        return RecurringBill::create(array_merge([
            'name' => 'Internet', 'amount' => 1500, 'expense_category_id' => $cat->id,
            'frequency' => 'monthly', 'next_due_date' => '2026-01-31', 'is_active' => true,
        ], $attrs));
    }

    public function test_paying_creates_expense_and_advances_from_due_date_without_overflow(): void
    {
        $bill = $this->bill();

        $this->actingAs(User::factory()->create())
            ->post("/admin/bills/{$bill->id}/pay", ['date' => '2026-02-05', 'amount' => 1450, 'note' => ''])
            ->assertSessionHasNoErrors();

        $expense = Expense::first();
        $this->assertSame(1450.0, $expense->amount);
        $this->assertSame('2026-02-05', $expense->date->toDateString());
        $this->assertSame($bill->expense_category_id, $expense->category_id);
        $this->assertSame($bill->id, $expense->recurring_bill_id);
        $this->assertSame('Internet', $expense->remarks);

        $bill->refresh();
        $this->assertSame('2026-02-28', $bill->next_due_date->toDateString()); // Jan 31 → Feb 28, from the due date
        $this->assertSame('2026-02-05', $bill->last_paid_at->toDateString());
    }

    public function test_skip_advances_without_expense_and_frequencies_step_correctly(): void
    {
        $user = User::factory()->create();
        $bill = $this->bill(['frequency' => 'quarterly', 'next_due_date' => '2026-11-30']);

        $this->actingAs($user)->post("/admin/bills/{$bill->id}/skip")->assertRedirect();
        $this->assertSame('2027-02-28', $bill->fresh()->next_due_date->toDateString());
        $this->assertDatabaseCount('expenses', 0);

        $bill->update(['frequency' => 'weekly', 'next_due_date' => '2026-12-29']);
        $this->actingAs($user)->post("/admin/bills/{$bill->id}/skip");
        $this->assertSame('2027-01-05', $bill->fresh()->next_due_date->toDateString());

        $bill->update(['frequency' => 'yearly', 'next_due_date' => '2028-02-29']);
        $this->actingAs($user)->post("/admin/bills/{$bill->id}/skip");
        $this->assertSame('2029-02-28', $bill->fresh()->next_due_date->toDateString());
    }

    public function test_index_groups_and_delete_keeps_payments(): void
    {
        $user = User::factory()->create();
        $overdue = $this->bill(['next_due_date' => now()->subDays(3)->toDateString()]);
        RecurringBill::create(['name' => 'Rent', 'amount' => 12000, 'expense_category_id' => $overdue->expense_category_id, 'frequency' => 'yearly', 'next_due_date' => now()->addDays(40)->toDateString()]);

        $this->actingAs($user)->get('/admin/bills')->assertOk()->assertInertia(fn ($page) => $page
            ->component('Bills/Index')
            ->where('summary.overdue', 1)
            ->where('summary.monthly', 2500) // 1500 + 12000/12
            ->where('bills.0.group', 'overdue')
            ->where('bills.1.group', 'upcoming')
        );

        $this->actingAs($user)->post("/admin/bills/{$overdue->id}/pay", ["date" => now()->toDateString(), "amount" => 1500])->assertSessionHasNoErrors()->assertSessionHas("success");
        $this->actingAs($user)->delete("/admin/bills/{$overdue->id}");

        $this->assertDatabaseMissing('recurring_bills', ['id' => $overdue->id]);
        $this->assertSame(1, Expense::count());
        $this->assertNull(Expense::first()->recurring_bill_id);
    }

    public function test_validation(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/bills', ['name' => '', 'amount' => 0, 'frequency' => 'daily'])
            ->assertSessionHasErrors(['name', 'amount', 'frequency', 'expense_category_id', 'next_due_date']);
    }
}
