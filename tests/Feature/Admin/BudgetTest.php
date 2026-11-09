<?php

namespace Tests\Feature\Admin;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_budget_spent_includes_child_categories_for_chosen_month(): void
    {
        $living = ExpenseCategory::create(['name' => 'Living', 'parent' => 0]);
        $food = ExpenseCategory::create(['name' => 'Food', 'parent' => $living->id]);
        $transport = ExpenseCategory::create(['name' => 'Transport', 'parent' => 0]);

        Expense::create(['date' => '2026-03-02', 'category_id' => $food->id, 'amount' => 700]);
        Expense::create(['date' => '2026-03-31', 'category_id' => $living->id, 'amount' => 300]);
        Expense::create(['date' => '2026-04-01', 'category_id' => $food->id, 'amount' => 5000]); // other month
        Expense::create(['date' => '2026-03-10', 'category_id' => $transport->id, 'amount' => 50]);

        Budget::create(['expense_category_id' => $living->id, 'amount' => 1000]);
        Budget::create(['expense_category_id' => null, 'amount' => 2000]);

        $this->actingAs(User::factory()->create())
            ->get('/admin/budgets?month=2026-03')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Budgets/Index')
                ->where('month.value', '2026-03')
                ->where('budgets', function ($rows) {
                    $rows = collect($rows);
                    $living = $rows->firstWhere('name', 'Living');
                    $overall = $rows->firstWhere('overall', true);

                    return $living['spent'] == 1000 && $living['status'] === 'over'
                        && $overall['spent'] == 1050 && $overall['projected'] === null;
                })
                ->where('unbudgeted.0.name', 'Transport')
                ->where('totals.budgeted', 1000)
            );
    }

    public function test_category_and_overall_budgets_are_unique(): void
    {
        $cat = ExpenseCategory::create(['name' => 'Food', 'parent' => 0]);
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/budgets', ['expense_category_id' => $cat->id, 'amount' => 500])->assertSessionHasNoErrors();
        $this->actingAs($user)->post('/admin/budgets', ['expense_category_id' => $cat->id, 'amount' => 600])->assertSessionHasErrors('expense_category_id');

        $this->actingAs($user)->post('/admin/budgets', ['expense_category_id' => null, 'amount' => 900])->assertSessionHasNoErrors();
        $this->actingAs($user)->post('/admin/budgets', ['expense_category_id' => null, 'amount' => 950])->assertSessionHasErrors('expense_category_id');

        $overall = Budget::whereNull('expense_category_id')->first();
        $this->actingAs($user)->put("/admin/budgets/{$overall->id}", ['expense_category_id' => null, 'amount' => 1200, 'is_active' => false])->assertSessionHasNoErrors();
        $this->assertSame(1200.0, $overall->fresh()->amount);
        $this->assertFalse($overall->fresh()->is_active);

        $this->actingAs($user)->post('/admin/budgets', ['expense_category_id' => $cat->id, 'amount' => 0])->assertSessionHasErrors('amount');

        $this->actingAs($user)->delete("/admin/budgets/{$overall->id}");
        $this->assertDatabaseCount('budgets', 1);
    }
}
