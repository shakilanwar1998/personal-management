<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'income_source',
        'remarks'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

}
