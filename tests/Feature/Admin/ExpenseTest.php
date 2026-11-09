<?php

namespace Tests\Feature\Admin;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/expenses')->assertRedirect('/admin/login');
    }

    public function test_list_filters_by_calendar_year_and_parent_category(): void
    {
        $parent = ExpenseCategory::create(['name' => 'Living', 'parent' => 0]);
        $child = ExpenseCategory::create(['name' => 'Food', 'parent' => $parent->id]);
        $other = ExpenseCategory::create(['name' => 'Transport', 'parent' => 0]);

        Expense::create(['date' => '2025-01-01', 'category_id' => $child->id, 'amount' => 100]);
        Expense::create(['date' => '2025-12-31', 'category_id' => $parent->id, 'amount' => 50]);
        Expense::create(['date' => '2024-12-31', 'category_id' => $child->id, 'amount' => 999]); // previous year
        Expense::create(['date' => '2025-06-01', 'category_id' => $other->id, 'amount' => 7]);

        $this->actingAs($this->user())
            ->get("/admin/expenses?year=2025&category={$parent->id}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Expenses/Index')
                ->where('summary.count', 2)
                ->where('summary.total', 150)
            );
    }

    public function test_create_update_and_bulk_delete(): void
    {
        $cat = ExpenseCategory::create(['name' => 'Food', 'parent' => 0]);
        $user = $this->user();

        $this->actingAs($user)->post('/admin/expenses', [
            'date' => '2026-03-04', 'category_id' => $cat->id, 'amount' => '1250.50', 'remarks' => 'Groceries',
        ])->assertSessionHasNoErrors();

        $e = Expense::first();
        $this->assertSame(1250.5, $e->amount);

        $this->actingAs($user)->put("/admin/expenses/{$e->id}", [
            'date' => '2026-03-05', 'category_id' => $cat->id, 'amount' => 99, 'remarks' => 'x',
        ])->assertSessionHasNoErrors();
        $this->assertSame(99.0, $e->fresh()->amount);

        $this->actingAs($user)->post('/admin/expenses', ['date' => '', 'amount' => 0])
            ->assertSessionHasErrors(['date', 'category_id', 'amount']);

        $this->actingAs($user)->post('/admin/expenses/bulk-delete', ['ids' => [$e->id]]);
        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_large_amounts_are_stored_exactly(): void
    {
        $cat = ExpenseCategory::create(['name' => 'House', 'parent' => 0]);
        $this->actingAs($this->user())->post('/admin/expenses', [
            'date' => '2026-01-01', 'category_id' => $cat->id, 'amount' => '12345678.91',
        ])->assertSessionHasNoErrors();

        $this->assertSame(12345678.91, Expense::first()->amount);
    }
}
