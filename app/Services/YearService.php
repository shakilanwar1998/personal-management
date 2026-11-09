<?php

namespace App\Services;

use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * The app works in calendar years: 1 January – 31 December.
 * The year picked in the top bar is stored in user_settings ("selected_year").
 */
class YearService
{
    private static ?int $selected = null;

    public static function selected(): int
    {
        if (self::$selected !== null) {
            return self::$selected;
        }

        $year = (int) Settings::get('selected_year', now()->year);

        return self::$selected = ($year >= 1970 && $year <= 2100) ? $year : now()->year;
    }

    public static function select(int $year): void
    {
        Settings::set('selected_year', (string) $year);
        self::$selected = $year;
    }

    /** @return array{start: Carbon, end: Carbon} */
    public static function range(?int $year = null): array
    {
        $year ??= self::selected();

        return [
            'start' => Carbon::create($year, 1, 1)->startOfDay(),
            'end' => Carbon::create($year, 12, 31)->endOfDay(),
        ];
    }

    /** Every year that has data, plus the current year, newest first. */
    public static function available(): array
    {
        $min = collect([
            DB::table('expenses')->min('date'),
            DB::table('incomes')->min('date'),
            DB::table('investments')->min('date'),
            DB::table('lending_transactions')->min('date'),
            DB::table('borrowing_transactions')->min('date'),
            DB::table('invoices')->min('invoice_date'),
        ])->filter()->min();

        $max = collect([
            DB::table('expenses')->max('date'),
            DB::table('incomes')->max('date'),
        ])->filter()->max();

        $first = $min ? (int) substr($min, 0, 4) : now()->year;
        $last = max(now()->year, $max ? (int) substr($max, 0, 4) : now()->year, self::selected());

        return range($last, min($first, self::selected()));
    }
}
