<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get the selected financial year
     */
    public static function getSelectedFinancialYear(): string
    {
        return self::get('selected_financial_year', self::getCurrentFinancialYear());
    }

    /**
     * Set the selected financial year
     */
    public static function setSelectedFinancialYear(string $financialYear): void
    {
        self::set('selected_financial_year', $financialYear);
    }

    /**
     * Get the current financial year (fallback)
     */
    public static function getCurrentFinancialYear(): string
    {
        $now = now();
        $year = $now->year;
        
        if ($now->month >= 7) {
            return $year . '-' . ($year + 1);
        }
        
        return ($year - 1) . '-' . $year;
    }
}
