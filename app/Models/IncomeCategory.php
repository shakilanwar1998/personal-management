<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    protected $fillable = ['name', 'icon', 'color', 'sort_order'];

    /** Incomes reference the category by name (incomes.income_source). */
    public function incomes()
    {
        return $this->hasMany(Income::class, 'income_source', 'name');
    }

    public static function ordered()
    {
        return static::orderBy('sort_order')->orderBy('name')->get();
    }
}
