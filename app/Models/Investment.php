<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'is_returned'
    ];

    protected $casts = [
        'date' => 'date',
        'return_date' => 'date',
        'amount' => 'decimal:2',
        'returned_amount' => 'decimal:2',
        'is_lifetime' => 'boolean',
        'is_returned' => 'boolean',
    ];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value, 2),
        );
    }

    /**
     * Principal still tied up in the investment (never negative).
     * Reads raw attributes so the formatted `amount` accessor doesn't interfere.
     */
    public function getOutstandingAttribute(): float
    {
        return max(0, $this->rawAmount() - $this->rawReturnedAmount());
    }

    public function getStatusAttribute()
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

    /**
     * Invested principal as a plain number (bypasses the number_format accessor).
     */
    public function rawAmount(): float
    {
        return (float) ($this->attributes['amount'] ?? 0);
    }

    public function rawReturnedAmount(): float
    {
        return (float) ($this->attributes['returned_amount'] ?? 0);
    }
}
