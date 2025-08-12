<?php

/**
 * Simple test script for the Email API
 * Run this script to test the email API endpoints
 */

// Configuration
$baseUrl = 'http://localhost:8000'; // Change this to your Laravel app URL
$testEmail = 'test@example.com'; // Change this to a real email for testing

// Test data
$emailData = [
    'to' => $testEmail,
    'subject' => 'Test Email from API',
    'body' => 'This is a test email sent from the Personal Management System API.',
    'from_name' => 'Test System'
];

$htmlEmailData = [
    'to' => $testEmail,
    'subject' => 'Test HTML Email from API',
    'body' => '<h1>Hello!</h1><p>This is a <strong>test HTML email</strong> sent from the Personal Management System API.</p><p>Current time: ' . date('Y-m-d H:i:s') . '</p>',
    'from_name' => 'Test System'
];

/**
 * Make HTTP request
 */
function makeRequest($url, $method = 'GET', $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

/**
 * Test functions
 */
function testSmtpConfig($baseUrl) {
    echo "Testing SMTP Configuration...\n";
    $result = makeRequest($baseUrl . '/api/email/test-config');
    
    if ($result['error']) {
        echo "❌ Error: " . $result['error'] . "\n";
        return false;
    }
    
    $data = json_decode($result['response'], true);
    echo "HTTP Code: " . $result['http_code'] . "\n";
    echo "Response: " . $result['response'] . "\n\n";
    
    return $result['http_code'] === 200 && $data['success'] === true;
}

function testSendEmail($baseUrl, $emailData) {
    echo "Testing Send Plain Text Email...\n";
    $result = makeRequest($baseUrl . '/api/email/send', 'POST', $emailData);
    
    if ($result['error']) {
        echo "❌ Error: " . $result['error'] . "\n";
        return false;
    }
    
    $data = json_decode($result['response'], true);
    echo "HTTP Code: " . $result['http_code'] . "\n";
    echo "Response: " . $result['response'] . "\n\n";
    
    return $result['http_code'] === 200 && $data['success'] === true;
}

function testSendHtmlEmail($baseUrl, $htmlEmailData) {
    echo "Testing Send HTML Email...\n";
    $result = makeRequest($baseUrl . '/api/email/send-html', 'POST', $htmlEmailData);
    
    if ($result['error']) {
        echo "❌ Error: " . $result['error'] . "\n";
        return false;
    }
    
    $data = json_decode($result['response'], true);
    echo "HTTP Code: " . $result['http_code'] . "\n";
    echo "Response: " . $result['response'] . "\n\n";
    
    return $result['http_code'] === 200 && $data['success'] === true;
}

function testSendAutoEmail($baseUrl, $emailData) {
    echo "Testing Send Auto Format Email (Plain Text)...\n";
    $emailData['is_html'] = false;
    $result = makeRequest($baseUrl . '/api/email/send-auto', 'POST', $emailData);
    
    if ($result['error']) {
        echo "❌ Error: " . $result['error'] . "\n";
        return false;
    }
    
    $data = json_decode($result['response'], true);
    echo "HTTP Code: " . $result['http_code'] . "\n";
    echo "Response: " . $result['response'] . "\n\n";
    
    return $result['http_code'] === 200 && $data['success'] === true;
}

// Run tests
echo "=== Email API Test Script ===\n";
echo "Base URL: $baseUrl\n";
echo "Test Email: $testEmail\n\n";

$tests = [
    'SMTP Config' => testSmtpConfig($baseUrl),
    'Plain Text Email' => testSendEmail($baseUrl, $emailData),
    'HTML Email' => testSendHtmlEmail($baseUrl, $htmlEmailData),
    'Auto Format Email' => testSendAutoEmail($baseUrl, $emailData),
];

echo "=== Test Results ===\n";
foreach ($tests as $testName => $result) {
    echo $testName . ": " . ($result ? "✅ PASS" : "❌ FAIL") . "\n";
}

echo "\n=== Test Complete ===\n";
echo "Note: Check your email inbox for the test emails.\n";
echo "If tests fail, make sure:\n";
echo "1. Laravel application is running\n";
echo "2. SMTP configuration is correct in .env file\n";
echo "3. Test email address is valid\n";
