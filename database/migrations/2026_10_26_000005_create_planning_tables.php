<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Monthly spending limit per expense category (null category = overall limit).
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_category_id')->nullable()->unique();
            $table->decimal('amount', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('savings_goals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('target_amount', 15, 2);
            $table->date('target_date')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('achieved_at')->nullable();
            $table->timestamps();
        });

        // Positive = money set aside for the goal, negative = taken back out.
        Schema::create('goal_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_goal_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('recurring_bills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('expense_category_id')->nullable();
            $table->enum('frequency', ['weekly', 'monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->date('next_due_date');
            $table->boolean('is_active')->default(true);
            $table->date('last_paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('recurring_bill_id')->nullable()->after('category_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->date('paid_at')->nullable()->after('status');
            $table->unsignedBigInteger('income_id')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['paid_at', 'income_id']);
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('recurring_bill_id');
        });
        Schema::dropIfExists('recurring_bills');
        Schema::dropIfExists('goal_contributions');
        Schema::dropIfExists('savings_goals');
        Schema::dropIfExists('budgets');
    }
};
