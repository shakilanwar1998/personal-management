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
        'due_date',
        'is_returned'
    ];
}
