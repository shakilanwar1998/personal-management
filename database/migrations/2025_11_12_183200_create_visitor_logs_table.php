<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->string('account_number')->nullable();
            
            // Location data
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('region')->nullable();
            $table->string('region_name')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone')->nullable();
            $table->string('isp')->nullable();
            $table->string('org')->nullable();
            $table->string('as')->nullable();
            $table->text('location_data')->nullable();
            
            // Browser information
            $table->string('user_agent')->nullable();
            $table->string('browser_name')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('browser_engine')->nullable();
            $table->string('os_name')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_vendor')->nullable();
            $table->string('device_model')->nullable();
            
            // Screen and display
            $table->integer('screen_width')->nullable();
            $table->integer('screen_height')->nullable();
            $table->integer('window_width')->nullable();
            $table->integer('window_height')->nullable();
            $table->integer('color_depth')->nullable();
            $table->integer('pixel_ratio')->nullable();
            $table->string('orientation')->nullable();
            
            // Hardware information
            $table->integer('cpu_cores')->nullable();
            $table->string('hardware_concurrency')->nullable();
            $table->string('device_memory')->nullable();
            $table->string('platform')->nullable();
            
            // Language and locale
            $table->string('language')->nullable();
            $table->text('languages')->nullable(); // JSON array
            $table->string('locale')->nullable();
            
            // Network
            $table->string('connection_type')->nullable();
            $table->string('effective_type')->nullable();
            $table->string('downlink')->nullable();
            $table->string('rtt')->nullable();
            
            // Fingerprinting
            $table->text('canvas_fingerprint')->nullable();
            $table->text('webgl_fingerprint')->nullable();
            $table->text('audio_fingerprint')->nullable();
            $table->text('fonts_list')->nullable(); // JSON array
            $table->text('plugins_list')->nullable(); // JSON array
            $table->text('mime_types')->nullable(); // JSON array
            
            // Additional browser data
            $table->text('cookies_enabled')->nullable();
            $table->text('local_storage')->nullable();
            $table->text('session_storage')->nullable();
            $table->string('referrer')->nullable();
            $table->string('origin')->nullable();
            $table->text('headers')->nullable(); // JSON object
            
            // Complete fingerprint data
            $table->text('fingerprint_data')->nullable(); // JSON object with all collected data
            $table->string('fingerprint_hash')->nullable()->index(); // Hash of fingerprint for tracking
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
