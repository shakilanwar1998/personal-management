<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LendingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'borrower_name',
        'amount',
        'returned_amount',
        'due_date',
        'is_returned'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'is_returned' => 'boolean',
    ];

    /**
     * Amount still owed by the borrower.
     */
    public function getOutstandingAttribute(): float
    {
        return max(0, (float) $this->amount - (float) $this->returned_amount);
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_returned) {
            return 'Returned';
        }
        if ((float) $this->returned_amount > 0) {
            return 'Partially Returned';
        }
        return 'Active';
    }
}
