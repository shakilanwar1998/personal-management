<?php

namespace Tests\Feature\Admin;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvestmentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    private function invest(float $amount, array $extra = []): Investment
    {
        return Investment::create(array_merge(['company_name' => 'Acme', 'date' => '2026-01-01', 'amount' => $amount], $extra));
    }

    public function test_index_lists_all_time_by_default(): void
    {
        $this->invest(1000, ['date' => '2020-05-01']);
        $this->invest(500, ['date' => '2026-02-01', 'is_lifetime' => true]);

        $this->actingAs($this->user)->get('/admin/investments')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Investments/Index')
                ->where('investments.total', 2)
                ->where('summary.invested', 1500)
                ->where('summary.lifetime', 500)
            );

        $this->actingAs($this->user)->get('/admin/investments?year=2026')
            ->assertInertia(fn ($page) => $page->where('investments.total', 1));
    }

    public function test_partial_return_keeps_investment_open(): void
    {
        $inv = $this->invest(1000);

        $this->actingAs($this->user)->post("/admin/investments/{$inv->id}/return", ['amount' => 400])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success', 'Partial return recorded');

        $inv->refresh();
        $this->assertSame(400.0, $inv->returned_amount);
        $this->assertFalse($inv->is_returned);
        $this->assertSame('Partially Returned', $inv->status);
        $this->assertDatabaseCount('incomes', 0);
        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_profit_is_booked_once_across_two_returns(): void
    {
        $inv = $this->invest(1000);

        $this->actingAs($this->user)->post("/admin/investments/{$inv->id}/return", ['amount' => 600]);
        $this->assertDatabaseCount('incomes', 0);

        $this->actingAs($this->user)->post("/admin/investments/{$inv->id}/return", ['amount' => 700])
            ->assertSessionHas('success', 'Investment closed');

        $this->assertDatabaseCount('incomes', 1);
        $income = Income::first();
        $this->assertSame(300.0, $income->amount);
        $this->assertSame('Investment Profit', $income->income_source);
        $this->assertSame('Profit from Acme', $income->remarks);

        $inv->refresh();
        $this->assertTrue($inv->is_returned);
        $this->assertSame(1300.0, $inv->returned_amount);

        // Closed investments can't take more returns.
        $this->actingAs($this->user)->post("/admin/investments/{$inv->id}/return", ['amount' => 50])
            ->assertSessionHasErrors('amount');
        $this->assertDatabaseCount('incomes', 1);
    }

    public function test_final_settlement_books_the_loss(): void
    {
        $inv = $this->invest(1000);

        $this->actingAs($this->user)->post("/admin/investments/{$inv->id}/return", ['amount' => 250, 'final' => true])
            ->assertSessionHas('success', 'Investment closed');

        $this->assertTrue($inv->fresh()->is_returned);
        $loss = Expense::with('category')->first();
        $this->assertSame(750.0, $loss->amount);
        $this->assertSame('Investment Loss', $loss->category->name);
        $this->assertSame(0, $loss->category->parent);
        $this->assertSame('Loss from Investment of Acme', $loss->remarks);
        $this->assertDatabaseCount('incomes', 0);
    }

    public function test_lifetime_investments_cannot_take_returns(): void
    {
        $inv = $this->invest(1000, ['is_lifetime' => true]);

        $this->actingAs($this->user)->post("/admin/investments/{$inv->id}/return", ['amount' => 100])
            ->assertSessionHasErrors('amount');
        $this->assertSame(0.0, $inv->fresh()->returned_amount);
    }

    public function test_create_edit_and_bulk_delete(): void
    {
        $this->actingAs($this->user)->post('/admin/investments', [
            'company_name' => 'Okkhor', 'date' => '2026-03-01', 'amount' => 5000, 'is_lifetime' => true, 'purpose' => 'Stake',
        ])->assertSessionHasNoErrors();

        $inv = Investment::first();
        $this->assertTrue($inv->is_lifetime);

        $this->actingAs($this->user)->put("/admin/investments/{$inv->id}", [
            'company_name' => 'Okkhor', 'date' => '2026-03-01', 'amount' => 6000, 'is_lifetime' => false, 'is_returned' => true,
        ])->assertSessionHasNoErrors();
        $inv->refresh();
        $this->assertSame(6000.0, $inv->amount);
        $this->assertTrue($inv->is_returned);

        $this->actingAs($this->user)->post('/admin/investments/bulk-delete', ['ids' => [$inv->id]]);
        $this->assertDatabaseCount('investments', 0);
    }
}
