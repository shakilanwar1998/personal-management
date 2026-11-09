<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalContribution extends Model
{
    protected $fillable = ['savings_goal_id', 'date', 'amount', 'note'];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'amount' => 'float',
    ];

    public function goal()
    {
        return $this->belongsTo(SavingsGoal::class, 'savings_goal_id');
    }
}
