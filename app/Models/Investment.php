<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'date',
        'purpose',
        'amount',
        'returned_amount',
        'is_lifetime',
        'return_date',
        'is_returned',
        'notes',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'return_date' => 'date:Y-m-d',
        'amount' => 'float',
        'returned_amount' => 'float',
        'is_lifetime' => 'boolean',
        'is_returned' => 'boolean',
    ];

    protected $appends = ['outstanding', 'status', 'profit'];

    /** Principal still tied up in the investment (never negative). */
    public function getOutstandingAttribute(): float
    {
        return max(0, $this->rawAmount() - $this->rawReturnedAmount());
    }

    /** Amount received above the invested principal. */
    public function getProfitAttribute(): float
    {
        return max(0, $this->rawReturnedAmount() - $this->rawAmount());
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_returned) {
            return 'Returned';
        }
        if ($this->is_lifetime) {
            return 'Lifetime';
        }
        if ($this->rawReturnedAmount() > 0) {
            return 'Partially Returned';
        }

        return 'Active';
    }

    public function rawAmount(): float
    {
        return (float) ($this->attributes['amount'] ?? 0);
    }

    public function rawReturnedAmount(): float
    {
        return (float) ($this->attributes['returned_amount'] ?? 0);
    }
}
