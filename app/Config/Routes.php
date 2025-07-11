<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function() {
    return redirect()->to('/login');
});

// Web Routes
$routes->get('login', 'AuthController::login');
$routes->post('set-session', 'AuthController::setSession');
$routes->get('logout', 'AuthController::logout');
$routes->get('dashboard', 'AuthController::dashboard', ['filter' => 'auth']);

// Settings Routes
$routes->group('settings', function($routes) {
    $routes->get('/', 'Settings::index', ['filter' => 'auth']);
    $routes->get('organization', 'Settings::organization', ['filter' => 'auth']);
    $routes->post('organization/update', 'Settings::updateOrganization');
    $routes->get('general', 'Settings::general', ['filter' => 'auth']);
    $routes->get('currency', 'Settings::currency', ['filter' => 'auth']);
    $routes->get('users', 'Settings::users', ['filter' => 'auth']);
    $routes->get('territories', 'Settings::territories', ['filter' => 'auth']);
    $routes->get('skills', 'Settings::skills', ['filter' => 'auth']);
    $routes->get('holiday', 'Settings::holiday', ['filter' => 'auth']);
    $routes->get('billing-setup', 'Settings::billing', ['filter' => 'auth']);
    $routes->get('tax-settings', 'Settings::taxSettings', ['filter' => 'auth']);
    $routes->get('fiscal-year', 'Settings::fiscalYear', ['filter' => 'auth']);
    $routes->post('fiscal-year/update', 'Settings::updateFiscalYear');
});

// API Routes
$routes->group('api', function($routes) {
    // Auth routes (no auth required)
    $routes->post('auth/login', 'Api\AuthController::login');
    
    // Protected routes
    $routes->group('', ['filter' => 'auth'], function($routes) {
        // Auth
        $routes->post('auth/logout', 'Api\AuthController::logout');
        $routes->get('auth/me', 'Api\AuthController::me');
        $routes->post('auth/register', 'Api\AuthController::register');
        
        // Customers
        $routes->get('customers', 'Api\CustomerController::index');
        $routes->get('customers/nearby', 'Api\CustomerController::nearby');
        $routes->post('customers/sync', 'Api\CustomerController::syncFromCanvassGlobal');
        $routes->get('customers/(:num)', 'Api\CustomerController::show/$1');
        $routes->post('customers', 'Api\CustomerController::create');
        $routes->put('customers/(:num)', 'Api\CustomerController::update/$1');
        $routes->delete('customers/(:num)', 'Api\CustomerController::delete/$1');
    });
});
