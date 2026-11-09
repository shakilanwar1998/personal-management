<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The app now works in calendar years (Jan–Dec) instead of Jul–Jun financial years.
 * Replace the stored "selected_financial_year" preference with "selected_year".
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('user_settings')->where('key', 'selected_financial_year')->delete();

        DB::table('user_settings')->updateOrInsert(
            ['key' => 'selected_year'],
            ['value' => (string) now()->year, 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('user_settings')->updateOrInsert(
            ['key' => 'currency_symbol'],
            ['value' => '৳', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        DB::table('user_settings')->where('key', 'selected_year')->delete();
        $y = now()->month >= 7 ? now()->year : now()->year - 1;
        DB::table('user_settings')->updateOrInsert(
            ['key' => 'selected_financial_year'],
            ['value' => $y . '-' . ($y + 1), 'created_at' => now(), 'updated_at' => now()]
        );
    }
};
