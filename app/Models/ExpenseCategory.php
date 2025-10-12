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

    protected $casts = [
        'is_stats' => 'boolean',
    ];

    public function parent_category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent', 'id');
    }

    public function subcategories()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent', 'id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id', 'id');
    }

    public function getFullNameAttribute()
    {
        if ($this->parent_category) {
            return $this->parent_category->name . ' > ' . $this->name;
        }
        return $this->name;
    }
}
