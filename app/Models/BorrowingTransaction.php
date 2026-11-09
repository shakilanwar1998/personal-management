<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'lender_name',
        'amount',
        'returned_amount',
        'due_date',
        'is_returned',
        'notes',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'due_date' => 'date:Y-m-d',
        'amount' => 'float',
        'returned_amount' => 'float',
        'is_returned' => 'boolean',
    ];

    protected $appends = ['outstanding', 'status', 'is_overdue'];

    /** Amount still owed to the lender. */
    public function getOutstandingAttribute(): float
    {
        return $this->is_returned ? 0.0 : max(0, (float) $this->amount - (float) $this->returned_amount);
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_returned) {
            return 'Repaid';
        }
        if ((float) $this->returned_amount > 0) {
            return 'Partially Repaid';
        }

        return 'Active';
    }

    public function getIsOverdueAttribute(): bool
    {
        return ! $this->is_returned && $this->due_date && $this->due_date->isPast() && ! $this->due_date->isToday();
    }
}
