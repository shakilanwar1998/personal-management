<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Money columns were DOUBLE(8,2), which caps any single amount at 999,999.99
 * and stores binary floating point. DECIMAL(15,2) keeps every existing value
 * exactly (they already have at most 2 decimals) and removes the cap.
 */
return new class extends Migration
{
    private array $columns = [
        'expenses' => ['amount' => 'NOT NULL DEFAULT 0'],
        'incomes' => ['amount' => 'NOT NULL DEFAULT 0'],
        'investments' => ['amount' => 'NOT NULL DEFAULT 0', 'returned_amount' => 'NOT NULL DEFAULT 0'],
        'lending_transactions' => ['amount' => 'NOT NULL', 'returned_amount' => 'NOT NULL DEFAULT 0'],
        'borrowing_transactions' => ['amount' => 'NOT NULL'],
    ];

    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columns as $table => $cols) {
            foreach ($cols as $col => $rest) {
                if (Schema::hasColumn($table, $col)) {
                    DB::statement("ALTER TABLE `{$table}` MODIFY `{$col}` DECIMAL(15,2) {$rest}");
                }
            }
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        foreach ($this->columns as $table => $cols) {
            foreach ($cols as $col => $rest) {
                DB::statement("ALTER TABLE `{$table}` MODIFY `{$col}` DOUBLE(8,2) {$rest}");
            }
        }
    }
};
