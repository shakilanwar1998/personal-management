<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category_id',
        'amount',
        'remarks'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'id');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => number_format($value, 2),
        );
    }
}
