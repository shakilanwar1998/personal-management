<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Icon (lucide name) and colour for the existing top-level categories. */
    private array $looks = [
        'Medical' => ['heart-pulse', 'rose'],
        'Transport' => ['bus', 'sky'],
        'Living' => ['home', 'emerald'],
        'Fashion' => ['shirt', 'pink'],
        'IT' => ['laptop', 'indigo'],
        'Sports' => ['dumbbell', 'lime'],
        'Family' => ['users', 'amber'],
        'Extras' => ['sparkles', 'violet'],
        'Sabilillah' => ['hand-heart', 'teal'],
        'Accessories' => ['package', 'orange'],
        'Gardening' => ['sprout', 'green'],
        'Bussiness' => ['briefcase', 'slate'],
        'Business' => ['briefcase', 'slate'],
        'Lucky' => ['user', 'cyan'],
        'Gifts' => ['gift', 'fuchsia'],
        'Travelling' => ['plane', 'blue'],
        'Education' => ['graduation-cap', 'purple'],
        'Uncategorized' => ['circle-help', 'zinc'],
        'Family Programs' => ['party-popper', 'yellow'],
        'Shifa' => ['baby', 'pink'],
        'Investment Loss' => ['trending-down', 'red'],
    ];

    /** New top-level categories commonly needed for personal finance. */
    private array $new = [
        ['Bills & Subscriptions', 'receipt', 'blue'],
        ['Insurance', 'shield-check', 'cyan'],
        ['Taxes & Fees', 'landmark', 'stone'],
        ['Personal Care', 'smile', 'pink'],
        ['Entertainment', 'clapperboard', 'violet'],
    ];

    public function up(): void
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->string('icon', 50)->nullable()->after('name');
            $table->string('color', 30)->nullable()->after('icon');
            $table->unsignedInteger('sort_order')->default(0)->after('color');
            $table->string('description')->nullable()->after('sort_order');
        });

        // Fix the spelling of the existing category; its id (and its expenses) stay the same.
        if (! DB::table('expense_categories')->where('name', 'Business')->where('parent', 0)->exists()) {
            DB::table('expense_categories')->where('name', 'Bussiness')->where('parent', 0)->update(['name' => 'Business']);
        }

        foreach ($this->looks as $name => [$icon, $color]) {
            DB::table('expense_categories')->where('name', $name)->where('parent', 0)
                ->update(['icon' => $icon, 'color' => $color]);
        }

        foreach ($this->new as [$name, $icon, $color]) {
            $exists = DB::table('expense_categories')->where('name', $name)->where('parent', 0)->exists();
            if (! $exists) {
                DB::table('expense_categories')->insert([
                    'name' => $name,
                    'parent' => 0,
                    'is_stats' => false,
                    'icon' => $icon,
                    'color' => $color,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        Schema::table('expenses', function (Blueprint $table) {
            $table->index('date');
            $table->index('category_id');
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->index('date');
            $table->index('income_source');
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['category_id']);
        });
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['income_source']);
        });
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color', 'sort_order', 'description']);
        });
    }
};
