<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoalContribution;
use App\Models\SavingsGoal;
use App\Services\FinanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Savings goals are earmarks: money set aside inside your current balance.
 * Contributions never create income or expenses, so the balance is unaffected.
 */
class SavingsGoalController extends AdminController
{
    public function index(FinanceService $finance)
    {
        $goals = SavingsGoal::withSum('contributions', 'amount')
            ->with('contributions')
            ->orderByRaw('achieved_at IS NOT NULL')
            ->orderByRaw('target_date IS NULL')
            ->orderBy('target_date')
            ->orderBy('name')
            ->get()
            ->map(fn (SavingsGoal $g) => $this->row($g));

        $totalSaved = (float) $goals->sum('saved');
        $balance = $finance->currentBalance();

        return Inertia::render('Goals/Index', [
            'goals' => $goals->values(),
            'summary' => [
                'saved' => $totalSaved,
                'target' => (float) $goals->whereNull('achieved_at')->sum('target'),
                'active' => $goals->whereNull('achieved_at')->count(),
                'achieved' => $goals->whereNotNull('achieved_at')->count(),
                'balance' => $balance,
                'free' => $balance - $totalSaved,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $goal = SavingsGoal::create($this->validated($request));
        $this->syncAchieved($goal);

        return back()->with('success', 'Goal created.');
    }

    public function update(Request $request, SavingsGoal $goal)
    {
        $goal->update($this->validated($request));
        $this->syncAchieved($goal);

        return back()->with('success', 'Goal updated.');
    }

    public function destroy(SavingsGoal $goal)
    {
        $goal->delete();

        return back()->with('success', 'Goal deleted.');
    }

    public function contribute(Request $request, SavingsGoal $goal)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'not_in:0', 'min:-9999999999999', 'max:9999999999999'],
            'note' => ['nullable', 'string', 'max:255'],
        ], ['amount.not_in' => 'Enter an amount other than zero.']);

        $saved = (float) $goal->contributions()->sum('amount');
        if ($data['amount'] < 0 && $saved + $data['amount'] < -0.001) {
            return back()->withErrors(['amount' => 'You can withdraw at most ' . number_format($saved, 2) . ' from this goal.']);
        }

        DB::transaction(function () use ($goal, $data) {
            $goal->contributions()->create($data);
            $this->syncAchieved($goal);
        });

        $reached = $goal->fresh()->achieved_at && $data['amount'] > 0;

        return back()->with('success', $reached
            ? "Goal reached: {$goal->name}! 🎉"
            : ($data['amount'] > 0 ? 'Money added to the goal.' : 'Money withdrawn from the goal.'));
    }

    public function destroyContribution(GoalContribution $contribution)
    {
        $goal = $contribution->goal;
        $contribution->delete();
        if ($goal) {
            $this->syncAchieved($goal);
        }

        return back()->with('success', 'Entry removed.');
    }

    /** Mark the goal achieved when saved ≥ target; clear the mark if it drops below again. */
    private function syncAchieved(SavingsGoal $goal): void
    {
        $saved = (float) $goal->contributions()->sum('amount');
        $reached = $goal->target_amount > 0 && $saved >= $goal->target_amount;

        if ($reached && ! $goal->achieved_at) {
            $goal->achieved_at = now();
            $goal->save();
        } elseif (! $reached && $goal->achieved_at) {
            $goal->achieved_at = null;
            $goal->save();
        }
    }

    private function row(SavingsGoal $g): array
    {
        $saved = (float) $g->contributions_sum_amount;
        $remaining = max(0, $g->target_amount - $saved);
        $monthsLeft = null;
        $perMonth = null;

        if ($g->target_date && ! $g->achieved_at) {
            $today = now()->startOfDay();
            $target = Carbon::parse($g->target_date)->startOfDay();
            // Months left, counting the current month; at least one so the figure stays meaningful.
            $monthsLeft = $target->lt($today) ? 0 : max(1, (int) ceil($today->floatDiffInMonths($target)));
            $perMonth = $monthsLeft > 0 ? $remaining / $monthsLeft : null;
        }

        return [
            'id' => $g->id,
            'name' => $g->name,
            'target' => $g->target_amount,
            'target_date' => $g->target_date?->toDateString(),
            'icon' => $g->icon,
            'color' => $g->color,
            'notes' => $g->notes,
            'achieved_at' => $g->achieved_at?->toDateString(),
            'saved' => $saved,
            'remaining' => $remaining,
            'percent' => $g->target_amount > 0 ? min(100, round($saved / $g->target_amount * 100, 1)) : 0,
            'months_left' => $monthsLeft,
            'per_month' => $perMonth,
            'overdue' => $g->target_date && ! $g->achieved_at && $g->target_date->lt(now()->startOfDay()),
            'contributions' => $g->contributions->map(fn ($c) => [
                'id' => $c->id,
                'date' => $c->date?->toDateString(),
                'amount' => $c->amount,
                'note' => $c->note,
            ])->values(),
        ];
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'min:1', 'max:9999999999999'],
            'target_date' => ['nullable', 'date'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [], ['target_amount' => 'target amount', 'target_date' => 'target date']);
    }
}
