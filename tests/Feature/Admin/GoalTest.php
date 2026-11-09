<?php

namespace Tests\Feature\Admin;

use App\Models\Income;
use App\Models\SavingsGoal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_contributions_withdrawals_and_achieved_toggling(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/goals', ['name' => 'Laptop', 'target_amount' => 1000, 'icon' => 'laptop', 'color' => 'indigo'])
            ->assertSessionHasNoErrors();
        $goal = SavingsGoal::first();

        $this->actingAs($user)->post("/admin/goals/{$goal->id}/contributions", ['date' => '2026-01-01', 'amount' => 600]);
        $this->assertNull($goal->fresh()->achieved_at);

        $this->actingAs($user)->post("/admin/goals/{$goal->id}/contributions", ['date' => '2026-02-01', 'amount' => 400]);
        $this->assertNotNull($goal->fresh()->achieved_at);

        // Withdrawing drops it below target again.
        $this->actingAs($user)->post("/admin/goals/{$goal->id}/contributions", ['date' => '2026-03-01', 'amount' => -100])
            ->assertSessionHasNoErrors();
        $this->assertNull($goal->fresh()->achieved_at);
        $this->assertSame(900.0, (float) $goal->contributions()->sum('amount'));

        // Cannot withdraw more than saved, and zero is rejected.
        $this->actingAs($user)->post("/admin/goals/{$goal->id}/contributions", ['date' => '2026-03-01', 'amount' => -901])
            ->assertSessionHasErrors('amount');
        $this->actingAs($user)->post("/admin/goals/{$goal->id}/contributions", ['date' => '2026-03-01', 'amount' => 0])
            ->assertSessionHasErrors('amount');

        // Removing the withdrawal reaches the target again.
        $withdrawal = $goal->contributions()->where('amount', '<', 0)->first();
        $this->actingAs($user)->delete("/admin/goals/contributions/{$withdrawal->id}");
        $this->assertNotNull($goal->fresh()->achieved_at);

        // Lowering the target via edit also re-evaluates.
        $this->actingAs($user)->put("/admin/goals/{$goal->id}", ['name' => 'Laptop', 'target_amount' => 5000]);
        $this->assertNull($goal->fresh()->achieved_at);
    }

    public function test_index_shows_free_balance_and_goals_do_not_change_balance(): void
    {
        Income::create(['date' => '2026-01-01', 'amount' => 10000, 'income_source' => 'Salary']);
        $goal = SavingsGoal::create(['name' => 'Fund', 'target_amount' => 5000, 'target_date' => now()->addMonths(5)->toDateString()]);
        $goal->contributions()->create(['date' => '2026-01-02', 'amount' => 2000]);

        $this->actingAs(User::factory()->create())->get('/admin/goals')->assertOk()->assertInertia(fn ($page) => $page
            ->component('Goals/Index')
            ->where('summary.balance', 10000)
            ->where('summary.saved', 2000)
            ->where('summary.free', 8000)
            ->where('goals.0.remaining', 3000)
            ->where('goals.0.months_left', fn ($m) => $m >= 5 && $m <= 6)
        );
    }
}
