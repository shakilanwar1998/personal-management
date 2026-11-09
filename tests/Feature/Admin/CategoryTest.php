<?php

namespace Tests\Feature\Admin;

use App\Models\Budget;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\RecurringBill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        // Start from an empty expense-category table (the migration seeds a few defaults).
        ExpenseCategory::query()->delete();
    }

    public function test_index_returns_tree_with_totals(): void
    {
        $p = ExpenseCategory::create(['name' => 'Living', 'parent' => 0]);
        $c = ExpenseCategory::create(['name' => 'Food', 'parent' => $p->id]);
        $year = now()->year;
        Expense::create(['date' => "{$year}-02-01", 'category_id' => $c->id, 'amount' => 40]);
        Expense::create(['date' => "{$year}-03-01", 'category_id' => $p->id, 'amount' => 10]);
        Expense::create(['date' => ($year - 1) . '-03-01', 'category_id' => $c->id, 'amount' => 999]);

        $this->actingAs($this->user)->get('/admin/categories')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Categories/Index')
                ->where('tree.0.name', 'Living')
                ->where('tree.0.tree_total', 50)
                ->where('tree.0.tree_count', 3)
                ->where('tree.0.children.0.year_total', 40)
                ->where('tree.0.children.0.all_count', 2)
            );
    }

    public function test_create_validates_parent_and_unique_name_within_parent(): void
    {
        $p = ExpenseCategory::create(['name' => 'Living', 'parent' => 0]);
        $c = ExpenseCategory::create(['name' => 'Food', 'parent' => $p->id]);

        // Same name under a different parent is fine.
        $this->actingAs($this->user)->post('/admin/categories/expense', ['name' => 'Food', 'parent' => 0])
            ->assertSessionHasNoErrors();
        // Duplicate under the same parent is not.
        $this->actingAs($this->user)->post('/admin/categories/expense', ['name' => 'Food', 'parent' => $p->id])
            ->assertSessionHasErrors('name');
        // A subcategory cannot be a parent.
        $this->actingAs($this->user)->post('/admin/categories/expense', ['name' => 'Snacks', 'parent' => $c->id])
            ->assertSessionHasErrors('parent');
        // A category with children cannot become a child.
        $other = ExpenseCategory::create(['name' => 'Other', 'parent' => 0]);
        $this->actingAs($this->user)->put("/admin/categories/expense/{$p->id}", ['name' => 'Living', 'parent' => $other->id])
            ->assertSessionHasErrors('parent');
    }

    public function test_delete_is_blocked_when_category_is_in_use(): void
    {
        $used = ExpenseCategory::create(['name' => 'Used', 'parent' => 0]);
        Expense::create(['date' => '2026-01-01', 'category_id' => $used->id, 'amount' => 5]);
        $withBill = ExpenseCategory::create(['name' => 'Billed', 'parent' => 0]);
        RecurringBill::create(['name' => 'Net', 'amount' => 10, 'expense_category_id' => $withBill->id, 'frequency' => 'monthly', 'next_due_date' => '2026-01-01']);
        $withChild = ExpenseCategory::create(['name' => 'Parent', 'parent' => 0]);
        ExpenseCategory::create(['name' => 'Kid', 'parent' => $withChild->id]);
        $free = ExpenseCategory::create(['name' => 'Free', 'parent' => 0]);

        foreach ([$used, $withBill, $withChild] as $c) {
            $this->actingAs($this->user)->delete("/admin/categories/expense/{$c->id}")->assertSessionHas('error');
            $this->assertNotNull($c->fresh());
        }

        $this->actingAs($this->user)->delete("/admin/categories/expense/{$free->id}")->assertSessionHas('success');
        $this->assertNull($free->fresh());
    }

    public function test_merge_moves_expenses_children_budgets_and_bills(): void
    {
        $src = ExpenseCategory::create(['name' => 'Fooding', 'parent' => 0]);
        $kid = ExpenseCategory::create(['name' => 'Snacks', 'parent' => $src->id]);
        $target = ExpenseCategory::create(['name' => 'Food', 'parent' => 0]);
        Expense::create(['date' => '2026-01-01', 'category_id' => $src->id, 'amount' => 5]);
        Expense::create(['date' => '2026-01-02', 'category_id' => $src->id, 'amount' => 6]);
        Expense::create(['date' => '2026-01-03', 'category_id' => $kid->id, 'amount' => 7]);
        Budget::create(['expense_category_id' => $src->id, 'amount' => 100]);
        Budget::create(['expense_category_id' => $target->id, 'amount' => 300]);
        $bill = RecurringBill::create(['name' => 'Meal plan', 'amount' => 10, 'expense_category_id' => $src->id, 'frequency' => 'monthly', 'next_due_date' => '2026-01-01']);

        $this->actingAs($this->user)->post("/admin/categories/expense/{$src->id}/merge", ['target_id' => $target->id])
            ->assertSessionHasNoErrors()->assertSessionHas('success');

        $this->assertNull($src->fresh());
        $this->assertSame(2, Expense::where('category_id', $target->id)->count());
        $this->assertSame($target->id, (int) $kid->fresh()->parent);
        $this->assertSame(1, Expense::where('category_id', $kid->id)->count());
        $this->assertSame(1, Budget::count());
        $this->assertSame(300.0, Budget::first()->amount);
        $this->assertSame($target->id, (int) $bill->fresh()->expense_category_id);
        $this->assertSame(18.0, (float) Expense::sum('amount'));
    }

    public function test_merge_rejects_self_and_own_child(): void
    {
        $p = ExpenseCategory::create(['name' => 'Living', 'parent' => 0]);
        $c = ExpenseCategory::create(['name' => 'Food', 'parent' => $p->id]);

        $this->actingAs($this->user)->post("/admin/categories/expense/{$p->id}/merge", ['target_id' => $p->id])
            ->assertSessionHasErrors('target_id');
        $this->actingAs($this->user)->post("/admin/categories/expense/{$p->id}/merge", ['target_id' => $c->id])
            ->assertSessionHasErrors('target_id');
        $this->assertNotNull($p->fresh());
    }

    public function test_toggle_stats(): void
    {
        $c = ExpenseCategory::create(['name' => 'Food', 'parent' => 0, 'is_stats' => false]);
        $this->actingAs($this->user)->post("/admin/categories/expense/{$c->id}/toggle-stats");
        $this->assertTrue($c->fresh()->is_stats);
    }

    public function test_income_category_rename_cascades_to_incomes(): void
    {
        $cat = IncomeCategory::where('name', 'Business')->firstOrFail();
        Income::create(['date' => '2026-01-01', 'amount' => 10, 'income_source' => 'Business']);
        Income::create(['date' => '2026-01-02', 'amount' => 20, 'income_source' => 'Business']);
        Income::create(['date' => '2026-01-03', 'amount' => 30, 'income_source' => 'Salary']);

        $this->actingAs($this->user)->put("/admin/categories/income/{$cat->id}", ['name' => 'Shop', 'icon' => 'store', 'color' => 'amber'])
            ->assertSessionHasNoErrors();

        $this->assertSame(2, Income::where('income_source', 'Shop')->count());
        $this->assertSame(0, Income::where('income_source', 'Business')->count());
        $this->assertSame(1, Income::where('income_source', 'Salary')->count());

        $this->actingAs($this->user)->put("/admin/categories/income/{$cat->id}", ['name' => 'Salary'])
            ->assertSessionHasErrors('name');
    }

    public function test_income_category_delete_requires_reassign_when_used(): void
    {
        $gifts = IncomeCategory::where('name', 'Gifts')->firstOrFail();
        $other = IncomeCategory::where('name', 'Other')->firstOrFail();
        Income::create(['date' => '2026-01-01', 'amount' => 10, 'income_source' => 'Gifts']);

        $this->actingAs($this->user)->delete("/admin/categories/income/{$gifts->id}")
            ->assertSessionHasErrors('reassign_to');
        $this->assertNotNull($gifts->fresh());

        $this->actingAs($this->user)->delete("/admin/categories/income/{$gifts->id}", ['reassign_to' => 'Gifts'])
            ->assertSessionHasErrors('reassign_to');

        $this->actingAs($this->user)->delete("/admin/categories/income/{$gifts->id}", ['reassign_to' => 'Other'])
            ->assertSessionHasNoErrors();
        $this->assertNull($gifts->fresh());
        $this->assertSame(1, Income::where('income_source', 'Other')->count());

        // Unused category deletes directly.
        $this->actingAs($this->user)->delete("/admin/categories/income/{$other->id}", ['reassign_to' => 'Salary'])
            ->assertSessionHasNoErrors();
        $this->actingAs($this->user)->post('/admin/categories/income', ['name' => 'Rental', 'icon' => 'home', 'color' => 'teal'])
            ->assertSessionHasNoErrors();
        $this->assertTrue(IncomeCategory::where('name', 'Rental')->exists());
    }
}
