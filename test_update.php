<?php
// Test script to verify the update user endpoint
// Run this in your browser: http://localhost/fsm/test_update.php

// Include CodeIgniter's index.php
require_once 'index.php';

// Create a mock request to test the endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/fsm/settings/updateUser");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'id' => '1',
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test@example.com',
    'role' => 'admin',
    'status' => 'active'
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Requested-With: XMLHttpRequest',
    'Accept: application/json',
    'Cookie: ' . $_SERVER['HTTP_COOKIE'] ?? ''
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h2>HTTP Status Code: $httpCode</h2>";
echo "<h2>Response:</h2>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

// Try to decode as JSON
$json = json_decode($response, true);
if ($json !== null) {
    echo "<h2>Decoded JSON:</h2>";
    echo "<pre>" . print_r($json, true) . "</pre>";
} else {
    echo "<h2>Response is not valid JSON</h2>";
}
?>
