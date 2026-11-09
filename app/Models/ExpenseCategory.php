<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent',
        'is_stats',
        'icon',
        'color',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'is_stats' => 'boolean',
        'parent' => 'integer',
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

    public function budget()
    {
        return $this->hasOne(Budget::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->parent_category) {
            return $this->parent_category->name . ' › ' . $this->name;
        }

        return $this->name;
    }

    /** This category id plus all of its direct children (the tree is two levels deep). */
    public function idsWithChildren(): array
    {
        return array_merge([$this->id], static::where('parent', $this->id)->pluck('id')->all());
    }

    /**
     * Flat list for pickers: each parent followed by its children, with labels
     * like "Living › Food". Children inherit the parent's icon and colour.
     */
    public static function options(): Collection
    {
        $all = static::orderBy('sort_order')->orderBy('name')->get();
        $parents = $all->where('parent', 0);
        $out = collect();

        foreach ($parents as $p) {
            $out->push([
                'id' => $p->id,
                'name' => $p->name,
                'label' => $p->name,
                'parent_id' => null,
                'icon' => $p->icon,
                'color' => $p->color,
            ]);
            foreach ($all->where('parent', $p->id) as $c) {
                $out->push([
                    'id' => $c->id,
                    'name' => $c->name,
                    'label' => $p->name . ' › ' . $c->name,
                    'parent_id' => $p->id,
                    'icon' => $c->icon ?: $p->icon,
                    'color' => $c->color ?: $p->color,
                ]);
            }
        }

        return $out->values();
    }
}
