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
        'is_lifetime',
        'return_date',
        'is_returned'
    ];
}
