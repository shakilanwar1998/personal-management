<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Borrowings had no repayment tracking at all; mirror what lending has.
        Schema::table('borrowing_transactions', function (Blueprint $table) {
            $table->decimal('returned_amount', 15, 2)->default(0)->after('amount');
            $table->boolean('is_returned')->default(false)->after('due_date');
            $table->text('notes')->nullable()->after('is_returned');
        });

        Schema::table('lending_transactions', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('is_returned');
        });

        Schema::table('investments', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('is_returned');
        });
    }

    public function down(): void
    {
        Schema::table('borrowing_transactions', function (Blueprint $table) {
            $table->dropColumn(['returned_amount', 'is_returned', 'notes']);
        });
        Schema::table('lending_transactions', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
