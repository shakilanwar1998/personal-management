<?php

namespace Tests\Feature\Admin;

use App\Models\BorrowingTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_repay_partially_then_fully(): void
    {
        $debt = BorrowingTransaction::create(['date' => '2026-03-10', 'lender_name' => 'Sabbir', 'amount' => 7700]);

        $this->actingAs($this->user)->post("/admin/borrowing/{$debt->id}/repay", ['amount' => 2700])
            ->assertSessionHas('success', 'Partial repayment recorded');
        $debt->refresh();
        $this->assertSame(5000.0, $debt->outstanding);
        $this->assertSame('Partially Repaid', $debt->status);

        $this->actingAs($this->user)->post("/admin/borrowing/{$debt->id}/repay", ['amount' => 5000])
            ->assertSessionHas('success', 'Debt fully repaid');
        $debt->refresh();
        $this->assertTrue($debt->is_returned);
        $this->assertSame('Repaid', $debt->status);

        $this->actingAs($this->user)->post("/admin/borrowing/{$debt->id}/repay", ['amount' => 1])
            ->assertSessionHasErrors('amount');
    }

    public function test_repay_cannot_exceed_outstanding(): void
    {
        $debt = BorrowingTransaction::create(['date' => '2026-03-10', 'lender_name' => 'Sabbir', 'amount' => 100]);

        $this->actingAs($this->user)->post("/admin/borrowing/{$debt->id}/repay", ['amount' => 150])
            ->assertSessionHasErrors('amount');
        $this->assertSame(0.0, $debt->fresh()->returned_amount);
    }

    public function test_index_and_crud(): void
    {
        $this->actingAs($this->user)->post('/admin/borrowing', [
            'date' => '2026-03-10', 'lender_name' => 'Sabbir', 'amount' => 7700, 'due_date' => '2026-04-10',
        ])->assertSessionHasNoErrors();
        $debt = BorrowingTransaction::first();

        $this->actingAs($this->user)->get('/admin/borrowing')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Borrowing/Index')
                ->where('loans.total', 1)
                ->where('summary.borrowed', 7700)
                ->where('summary.outstanding', 7700)
                ->where('people.0.name', 'Sabbir')
            );

        $this->actingAs($this->user)->post('/admin/borrowing', ['date' => '', 'amount' => 0])
            ->assertSessionHasErrors(['date', 'lender_name', 'amount']);

        $this->actingAs($this->user)->delete("/admin/borrowing/{$debt->id}");
        $this->assertDatabaseCount('borrowing_transactions', 0);
    }
}
