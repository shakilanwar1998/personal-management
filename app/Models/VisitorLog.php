<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip_address',
        'account_number',
        'country',
        'country_code',
        'region',
        'region_name',
        'city',
        'zip',
        'latitude',
        'longitude',
        'timezone',
        'isp',
        'org',
        'as',
        'location_data',
        'user_agent',
        'browser_name',
        'browser_version',
        'browser_engine',
        'os_name',
        'os_version',
        'device_type',
        'device_vendor',
        'device_model',
        'screen_width',
        'screen_height',
        'window_width',
        'window_height',
        'color_depth',
        'pixel_ratio',
        'orientation',
        'cpu_cores',
        'hardware_concurrency',
        'device_memory',
        'platform',
        'language',
        'languages',
        'locale',
        'connection_type',
        'effective_type',
        'downlink',
        'rtt',
        'canvas_fingerprint',
        'webgl_fingerprint',
        'audio_fingerprint',
        'fonts_list',
        'plugins_list',
        'mime_types',
        'cookies_enabled',
        'local_storage',
        'session_storage',
        'referrer',
        'origin',
        'headers',
        'fingerprint_data',
        'fingerprint_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'location_data' => 'array',
        'languages' => 'array',
        'fonts_list' => 'array',
        'plugins_list' => 'array',
        'mime_types' => 'array',
        'headers' => 'array',
        'fingerprint_data' => 'array',
        'screen_width' => 'integer',
        'screen_height' => 'integer',
        'window_width' => 'integer',
        'window_height' => 'integer',
        'color_depth' => 'integer',
        'pixel_ratio' => 'integer',
        'cpu_cores' => 'integer',
    ];
}
