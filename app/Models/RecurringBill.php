<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RecurringBill extends Model
{
    public const FREQUENCIES = ['weekly', 'monthly', 'quarterly', 'yearly'];

    protected $fillable = [
        'name', 'amount', 'expense_category_id', 'frequency', 'next_due_date', 'is_active', 'last_paid_at', 'notes',
    ];

    protected $casts = [
        'amount' => 'float',
        'next_due_date' => 'date:Y-m-d',
        'last_paid_at' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /** The due date that follows the given one for this bill's frequency. */
    public function nextDateAfter(Carbon $date): Carbon
    {
        return match ($this->frequency) {
            'weekly' => $date->copy()->addWeek(),
            'quarterly' => $date->copy()->addMonthsNoOverflow(3),
            'yearly' => $date->copy()->addYearNoOverflow(),
            default => $date->copy()->addMonthNoOverflow(),
        };
    }

    /** Approximate monthly cost, used to show total commitments. */
    public function monthlyEquivalent(): float
    {
        return match ($this->frequency) {
            'weekly' => $this->amount * 52 / 12,
            'quarterly' => $this->amount / 3,
            'yearly' => $this->amount / 12,
            default => $this->amount,
        };
    }
}
