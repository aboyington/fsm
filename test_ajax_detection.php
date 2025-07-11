<?php
// Test AJAX detection in CodeIgniter
require_once 'vendor/autoload.php';
$app = require_once 'app/Config/Boot/app.php';

// Create a mock request with AJAX headers
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/settings/updateUser';

// Create request instance
$request = \Config\Services::request();

// Test AJAX detection
echo "Testing AJAX Detection:\n";
echo "Is AJAX request? " . ($request->isAJAX() ? 'YES' : 'NO') . "\n";
echo "Headers:\n";
var_dump($request->getHeaders());
