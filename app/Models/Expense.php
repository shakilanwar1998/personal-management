<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category_id',
        'recurring_bill_id',
        'amount',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'amount' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'id');
    }

    public function recurringBill()
    {
        return $this->belongsTo(RecurringBill::class);
    }

    public function scopeInYear(Builder $query, ?int $year): Builder
    {
        return $year ? $query->whereYear('date', $year) : $query;
    }

    /** Rows added to fill a recording gap are tagged "[ESTIMATED]" in remarks. */
    public function getIsEstimatedAttribute(): bool
    {
        return str_starts_with((string) $this->remarks, '[ESTIMATED]');
    }
}
