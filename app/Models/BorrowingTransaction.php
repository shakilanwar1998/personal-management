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
        'due_date'
    ];
}
