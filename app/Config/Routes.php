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
    $routes->post('business-hours/update', 'Settings::updateBusinessHours');
    $routes->get('general', 'Settings::general', ['filter' => 'auth']);
    $routes->get('currency', 'Settings::currency', ['filter' => 'auth']);
    $routes->get('users', 'Settings::users', ['filter' => 'auth']);
    $routes->post('addUser', 'Settings::addUser');
    $routes->get('getUser/(:num)', 'Settings::getUser/$1');
    $routes->post('updateUser', 'Settings::updateUser');
    $routes->get('updateUser', 'Settings::updateUser');  // Add GET route to handle any method issues
    $routes->match(['get', 'post'], 'update-user', 'Settings::updateUser');  // Alternative route
    $routes->post('deleteUser/(:num)', 'Settings::deleteUser/$1');
    $routes->get('getUserTimeline/(:num)', 'Settings::getUserTimeline/$1');
    $routes->get('territories', 'Settings::territories', ['filter' => 'auth']);
    $routes->post('territories/add', 'Settings::addTerritory');
    $routes->get('territories/get/(:num)', 'Settings::getTerritory/$1');
    $routes->post('territories/update/(:num)', 'Settings::updateTerritory/$1');
    $routes->post('territories/delete/(:num)', 'Settings::deleteTerritory/$1');
    $routes->get('skills', 'Settings::skills', ['filter' => 'auth']);
    $routes->post('skills/add', 'Settings::addSkill');
    $routes->get('skills/get/(:num)', 'Settings::getSkill/$1');
    $routes->post('skills/update/(:num)', 'Settings::updateSkill/$1');
    $routes->post('skills/delete/(:num)', 'Settings::deleteSkill/$1');
    $routes->get('users/(:num)/skills', 'Settings::getUserSkills/$1');
    $routes->post('users/skills/assign', 'Settings::assignUserSkill');
    $routes->post('users/skills/update/(:num)', 'Settings::updateUserSkill/$1');
    $routes->post('users/skills/remove', 'Settings::removeUserSkill');
    $routes->get('holiday', 'Settings::holiday', ['filter' => 'auth']);
    $routes->get('profiles', 'Settings::profiles', ['filter' => 'auth']);
    $routes->post('profiles/add', 'Settings::addProfile');
    $routes->get('profiles/get/(:num)', 'Settings::getProfile/$1');
    $routes->post('profiles/update/(:num)', 'Settings::updateProfile/$1');
    $routes->post('profiles/delete/(:num)', 'Settings::deleteProfile/$1');
    $routes->get('audit-log', 'Settings::auditLog', ['filter' => 'auth']);
    $routes->get('pii-fields', 'Settings::piiFields', ['filter' => 'auth']);
    $routes->get('billing-setup', 'Settings::billing', ['filter' => 'auth']);
    $routes->get('tax-settings', 'Settings::taxSettings', ['filter' => 'auth']);
    $routes->get('fiscal-year', 'Settings::fiscalYear', ['filter' => 'auth']);
    $routes->post('fiscal-year/update', 'Settings::updateFiscalYear');
    
    // Transaction Settings routes
    $routes->get('transaction-settings', 'Settings::transactionSettings', ['filter' => 'auth']);
    $routes->post('transaction-settings/update', 'Settings::updateTransactionSettings');
    
    // Record Templates routes
    $routes->get('record-templates', 'Settings::recordTemplates', ['filter' => 'auth']);
    $routes->post('record-templates/create', 'Settings::createRecordTemplate');
    $routes->get('record-templates/get/(:num)', 'Settings::getRecordTemplate/$1');
    $routes->post('record-templates/update/(:num)', 'Settings::updateRecordTemplate/$1');
    $routes->post('record-templates/delete/(:num)', 'Settings::deleteRecordTemplate/$1');
    $routes->post('record-templates/duplicate/(:num)', 'Settings::duplicateRecordTemplate/$1');
    
    // Currency routes
    $routes->post('currency/store', 'Settings::storeCurrency');
    $routes->post('currency/update/(:num)', 'Settings::updateCurrency/$1');
    $routes->get('currency/get/(:num)', 'Settings::getCurrency/$1');
});

// Currency Routes
$routes->group('currency', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'CurrencyController::index');
    $routes->post('store', 'CurrencyController::store');
    $routes->post('update/(:num)', 'CurrencyController::update/$1');
    $routes->get('get/(:num)', 'CurrencyController::getCurrency/$1');
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
