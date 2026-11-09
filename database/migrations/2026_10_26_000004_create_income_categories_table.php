<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Income sources used to be a hard-coded list. They now live in a table so they
 * can be added and renamed. incomes.income_source keeps storing the name, so no
 * existing income row changes.
 */
return new class extends Migration
{
    private array $defaults = [
        ['Salary', 'briefcase', 'emerald'],
        ['Remittance', 'send', 'sky'],
        ['Business', 'store', 'amber'],
        ['Gifts', 'gift', 'fuchsia'],
        ['Freelance', 'laptop', 'indigo'],
        ['Investment Profit', 'trending-up', 'teal'],
        ['Invoice Payment', 'file-text', 'violet'],
        ['Other', 'circle-dot', 'zinc'],
    ];

    public function up(): void
    {
        Schema::create('income_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $order = 0;
        foreach ($this->defaults as [$name, $icon, $color]) {
            DB::table('income_categories')->insert([
                'name' => $name, 'icon' => $icon, 'color' => $color, 'sort_order' => $order++,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // Keep any source already used in the data, even if it is not in the defaults.
        $used = DB::table('incomes')->whereNotNull('income_source')->distinct()->pluck('income_source');
        foreach ($used as $name) {
            if (! DB::table('income_categories')->where('name', $name)->exists()) {
                DB::table('income_categories')->insert([
                    'name' => $name, 'icon' => 'circle-dot', 'color' => 'zinc', 'sort_order' => $order++,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('income_categories');
    }
};
