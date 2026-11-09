<?php

namespace Tests\Feature\Admin;

use App\Models\LendingTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LendingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_partial_then_full_return(): void
    {
        $loan = LendingTransaction::create(['date' => '2026-01-01', 'borrower_name' => 'Rahim', 'amount' => 1000]);

        $this->actingAs($this->user)->post("/admin/lending/{$loan->id}/return", ['amount' => 400])
            ->assertSessionHas('success', 'Partial return recorded');
        $this->assertSame(600.0, $loan->fresh()->outstanding);
        $this->assertFalse($loan->fresh()->is_returned);

        $this->actingAs($this->user)->post("/admin/lending/{$loan->id}/return", ['amount' => 600])
            ->assertSessionHas('success', 'Loan fully returned');
        $this->assertTrue($loan->fresh()->is_returned);
        $this->assertSame('Returned', $loan->fresh()->status);
    }

    public function test_return_cannot_exceed_outstanding(): void
    {
        $loan = LendingTransaction::create(['date' => '2026-01-01', 'borrower_name' => 'Rahim', 'amount' => 1000, 'returned_amount' => 800]);

        $this->actingAs($this->user)->post("/admin/lending/{$loan->id}/return", ['amount' => 201])
            ->assertSessionHasErrors('amount');
        $this->assertSame(800.0, $loan->fresh()->returned_amount);
    }

    public function test_index_summary_and_people(): void
    {
        LendingTransaction::create(['date' => '2024-01-01', 'borrower_name' => 'Rahim', 'amount' => 1000, 'returned_amount' => 200, 'due_date' => '2024-02-01']);
        LendingTransaction::create(['date' => '2026-01-01', 'borrower_name' => 'Rahim', 'amount' => 500]);
        LendingTransaction::create(['date' => '2026-01-01', 'borrower_name' => 'Karim', 'amount' => 300, 'returned_amount' => 300, 'is_returned' => true]);

        $this->actingAs($this->user)->get('/admin/lending')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Lending/Index')
                ->where('loans.total', 3)
                ->where('summary.outstanding', 1300)
                ->where('summary.overdue_count', 1)
                ->where('summary.overdue_amount', 800)
                ->has('people', 1)
                ->where('people.0.name', 'Rahim')
                ->where('people.0.outstanding', 1300)
            );

        $this->actingAs($this->user)->get('/admin/lending?status=overdue')
            ->assertInertia(fn ($page) => $page->where('loans.total', 1));
    }

    public function test_create_and_edit_marks_returned(): void
    {
        $this->actingAs($this->user)->post('/admin/lending', [
            'date' => '2026-05-01', 'borrower_name' => 'Morshed', 'amount' => 2644, 'due_date' => null, 'notes' => 'Phone',
        ])->assertSessionHasNoErrors();

        $loan = LendingTransaction::first();
        $this->actingAs($this->user)->put("/admin/lending/{$loan->id}", [
            'date' => '2026-05-01', 'borrower_name' => 'Morshed', 'amount' => 2644, 'is_returned' => true,
        ])->assertSessionHasNoErrors();
        $this->assertTrue($loan->fresh()->is_returned);
        $this->assertSame(0.0, $loan->fresh()->outstanding);
    }
}
