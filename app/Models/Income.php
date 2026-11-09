<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'income_source',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'amount' => 'float',
    ];

    public function scopeInYear(Builder $query, ?int $year): Builder
    {
        return $year ? $query->whereYear('date', $year) : $query;
    }
}
