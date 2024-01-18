<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent',
        'is_stats'
    ];

    public function parent_category()
    {
        return $this->hasOne(ExpenseCategory::class,'id','parent');
    }
}
