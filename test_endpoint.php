<?php
// Minimal test endpoint to debug updateUser issue
// This bypasses CodeIgniter's routing and controller logic

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON content type
header('Content-Type: application/json');

// Log the request details
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$postData = $_POST;
$headers = getallheaders();

// Create response
$response = [
    'success' => true,
    'message' => 'Test endpoint reached successfully',
    'debug' => [
        'method' => $requestMethod,
        'uri' => $requestUri,
        'post_data' => $postData,
        'headers' => $headers,
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
    ]
];

// Output JSON response
echo json_encode($response, JSON_PRETTY_PRINT);
