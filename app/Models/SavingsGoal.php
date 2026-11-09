<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsGoal extends Model
{
    protected $fillable = ['name', 'target_amount', 'target_date', 'icon', 'color', 'notes', 'achieved_at'];

    protected $casts = [
        'target_amount' => 'float',
        'target_date' => 'date:Y-m-d',
        'achieved_at' => 'datetime',
    ];

    public function contributions()
    {
        return $this->hasMany(GoalContribution::class)->orderByDesc('date')->orderByDesc('id');
    }

    public function savedAmount(): float
    {
        return (float) ($this->contributions_sum_amount ?? $this->contributions()->sum('amount'));
    }
}
