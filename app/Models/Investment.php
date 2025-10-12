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
        'is_lifetime',
        'return_date',
        'is_returned'
    ];

    protected $casts = [
        'date' => 'date',
        'return_date' => 'date',
        'amount' => 'decimal:2',
        'is_lifetime' => 'boolean',
        'is_returned' => 'boolean',
    ];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value, 2),
        );
    }

    public function getStatusAttribute()
    {
        if ($this->is_returned) {
            return 'Returned';
        }
        if ($this->is_lifetime) {
            return 'Lifetime';
        }
        return 'Active';
    }
}
