<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 2)->default('US');
            $table->string('tax_id')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('due_date')->constrained()->nullOnDelete();
        });

        // One profile per existing client name, filled from their latest invoice.
        $invoices = DB::table('invoices')->whereNotNull('client_name')->where('client_name', '!=', '')->orderByDesc('id')->get();

        foreach ($invoices->groupBy(fn ($i) => mb_strtolower(trim($i->client_name))) as $group) {
            $latest = $group->first();
            $id = DB::table('clients')->insertGetId([
                'name' => trim($latest->client_name),
                'email' => $latest->client_email,
                'address' => $latest->client_address,
                'city' => $latest->client_city,
                'state' => $latest->client_state,
                'postal_code' => $latest->client_postal_code,
                'country' => $latest->client_country ?: 'US',
                'tax_id' => $latest->client_tax_id,
                'currency' => $latest->currency ?: 'USD',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('invoices')->whereIn('id', $group->pluck('id'))->update(['client_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
        });
        Schema::dropIfExists('clients');
    }
};
