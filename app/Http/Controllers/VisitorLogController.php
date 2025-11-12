<?php

namespace App\Http\Controllers;

use App\Models\VisitorLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisitorLogController extends Controller
{
    /**
     * Store visitor IP address, location details, and browser fingerprinting data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Get the client's IP address
            $ipAddress = $this->getClientIpAddress($request);
            
            if (!$ipAddress) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to determine IP address',
                ], 400);
            }

            // Get fingerprinting data from request
            $fingerprintData = $request->input('fingerprint_data', []);
            
            // Generate fingerprint hash for tracking
            $fingerprintHash = null;
            if (!empty($fingerprintData)) {
                $fingerprintHash = hash('sha256', json_encode($fingerprintData));
            }

            // Fetch location details from IP
            $locationData = $this->fetchLocationFromIp($ipAddress);

            // Prepare all data for storage
            $data = [
                'ip_address' => $ipAddress,
                'account_number' => $request->input('account_number'),
                
                // Location data
                'country' => $locationData['country'] ?? null,
                'country_code' => $locationData['countryCode'] ?? null,
                'region' => $locationData['region'] ?? null,
                'region_name' => $locationData['regionName'] ?? null,
                'city' => $locationData['city'] ?? null,
                'zip' => $locationData['zip'] ?? null,
                'latitude' => $locationData['lat'] ?? null,
                'longitude' => $locationData['lon'] ?? null,
                'timezone' => $locationData['timezone'] ?? null,
                'isp' => $locationData['isp'] ?? null,
                'org' => $locationData['org'] ?? null,
                'as' => $locationData['as'] ?? null,
                'location_data' => $locationData,
                
                // Browser information from fingerprint
                'user_agent' => $request->userAgent(),
                'browser_name' => $fingerprintData['browser'] ?? $request->input('browser_name'),
                'browser_version' => $fingerprintData['browserVersion'] ?? $request->input('browser_version'),
                'browser_engine' => $fingerprintData['browserEngine'] ?? $request->input('browser_engine'),
                'os_name' => $fingerprintData['os'] ?? $request->input('os_name'),
                'os_version' => $fingerprintData['osVersion'] ?? $request->input('os_version'),
                'device_type' => $fingerprintData['deviceType'] ?? $request->input('device_type'),
                'device_vendor' => $fingerprintData['deviceVendor'] ?? $request->input('device_vendor'),
                'device_model' => $fingerprintData['deviceModel'] ?? $request->input('device_model'),
                
                // Screen and display
                'screen_width' => $fingerprintData['screenWidth'] ?? $request->input('screen_width'),
                'screen_height' => $fingerprintData['screenHeight'] ?? $request->input('screen_height'),
                'window_width' => $fingerprintData['windowWidth'] ?? $request->input('window_width'),
                'window_height' => $fingerprintData['windowHeight'] ?? $request->input('window_height'),
                'color_depth' => $fingerprintData['colorDepth'] ?? $request->input('color_depth'),
                'pixel_ratio' => $fingerprintData['pixelRatio'] ?? $request->input('pixel_ratio'),
                'orientation' => $fingerprintData['orientation'] ?? $request->input('orientation'),
                
                // Hardware information
                'cpu_cores' => $fingerprintData['cpuCores'] ?? $request->input('cpu_cores'),
                'hardware_concurrency' => $fingerprintData['hardwareConcurrency'] ?? $request->input('hardware_concurrency'),
                'device_memory' => $fingerprintData['deviceMemory'] ?? $request->input('device_memory'),
                'platform' => $fingerprintData['platform'] ?? $request->input('platform'),
                
                // Language and locale
                'language' => $fingerprintData['language'] ?? $request->input('language'),
                'languages' => $fingerprintData['languages'] ?? $request->input('languages'),
                'locale' => $fingerprintData['locale'] ?? $request->input('locale'),
                
                // Network
                'connection_type' => $fingerprintData['connectionType'] ?? $request->input('connection_type'),
                'effective_type' => $fingerprintData['effectiveType'] ?? $request->input('effective_type'),
                'downlink' => $fingerprintData['downlink'] ?? $request->input('downlink'),
                'rtt' => $fingerprintData['rtt'] ?? $request->input('rtt'),
                
                // Fingerprinting
                'canvas_fingerprint' => $fingerprintData['canvasFingerprint'] ?? $request->input('canvas_fingerprint'),
                'webgl_fingerprint' => $fingerprintData['webglFingerprint'] ?? $request->input('webgl_fingerprint'),
                'audio_fingerprint' => $fingerprintData['audioFingerprint'] ?? $request->input('audio_fingerprint'),
                'fonts_list' => $fingerprintData['fonts'] ?? $request->input('fonts_list'),
                'plugins_list' => $fingerprintData['plugins'] ?? $request->input('plugins_list'),
                'mime_types' => $fingerprintData['mimeTypes'] ?? $request->input('mime_types'),
                
                // Additional browser data
                'cookies_enabled' => $fingerprintData['cookiesEnabled'] ?? $request->input('cookies_enabled'),
                'local_storage' => $fingerprintData['localStorage'] ?? $request->input('local_storage'),
                'session_storage' => $fingerprintData['sessionStorage'] ?? $request->input('session_storage'),
                'referrer' => $request->header('referer') ?? $fingerprintData['referrer'] ?? $request->input('referrer'),
                'origin' => $fingerprintData['origin'] ?? $request->input('origin'),
                'headers' => $request->headers->all(),
                
                // Complete fingerprint data
                'fingerprint_data' => $fingerprintData,
                'fingerprint_hash' => $fingerprintHash,
            ];

            // Store in database
            $visitorLog = VisitorLog::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Visitor log stored successfully',
                'data' => $visitorLog,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error storing visitor log: ' . $e->getMessage(), [
                'exception' => $e,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to store visitor log',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Get client IP address from request
     *
     * @param Request $request
     * @return string|null
     */
    private function getClientIpAddress(Request $request): ?string
    {
        // Try various methods to get the real IP address
        $ipAddress = $request->header('X-Forwarded-For');
        
        if ($ipAddress) {
            // X-Forwarded-For can contain multiple IPs, get the first one
            $ips = explode(',', $ipAddress);
            $ipAddress = trim($ips[0]);
        }

        if (!$ipAddress) {
            $ipAddress = $request->header('X-Real-IP');
        }

        if (!$ipAddress) {
            $ipAddress = $request->ip();
        }

        // Filter out local/private IPs if needed
        if ($ipAddress && filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ipAddress;
        }

        // Return the IP even if it's private (for local development)
        return $ipAddress ?: null;
    }

    /**
     * Fetch location details from IP address using ip-api.com
     *
     * @param string $ipAddress
     * @return array
     */
    private function fetchLocationFromIp(string $ipAddress): array
    {
        try {
            // Using ip-api.com free service (no API key required)
            // Rate limit: 45 requests per minute
            $response = Http::timeout(5)->get("http://ip-api.com/json/{$ipAddress}", [
                'fields' => 'status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'success') {
                    return $data;
                }
            }

            // Fallback: return empty array if API fails
            return [];
        } catch (\Exception $e) {
            Log::warning('Failed to fetch location from IP: ' . $e->getMessage(), [
                'ip' => $ipAddress,
            ]);
            
            return [];
        }
    }
}
