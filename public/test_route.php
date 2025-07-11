<?php
// Test routing and AJAX detection
require_once '../vendor/autoload.php';
$app = require_once '../app/Config/Boot/app.php';

// Initialize the application
$app->initialize();

// Get services
$request = \Config\Services::request();
$routes = \Config\Services::routes();

// Test request detection
echo "Testing Request Details:\n";
echo "=======================\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "Path Info: " . ($_SERVER['PATH_INFO'] ?? 'Not set') . "\n";
echo "Is AJAX: " . ($request->isAJAX() ? 'YES' : 'NO') . "\n";
echo "Method: " . $request->getMethod() . "\n";
echo "\nHeaders:\n";
foreach (getallheaders() as $name => $value) {
    echo "$name: $value\n";
}

echo "\n\nTesting Routes:\n";
echo "================\n";

// Test if route exists
$uri = 'settings/updateUser';
echo "Testing route: $uri\n";

try {
    $routeInfo = $routes->getRoutes('post');
    echo "\nAll POST routes:\n";
    foreach ($routeInfo as $from => $to) {
        if (strpos($from, 'settings') !== false) {
            echo "  $from => " . (is_array($to) ? json_encode($to) : $to) . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test base_url helper
helper('url');
echo "\n\nURL Testing:\n";
echo "=============\n";
echo "base_url(): " . base_url() . "\n";
echo "base_url('settings/updateUser'): " . base_url('settings/updateUser') . "\n";
echo "site_url('settings/updateUser'): " . site_url('settings/updateUser') . "\n";
