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
// Dashboard Routes
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('dashboard/overview', 'DashboardController::overview', ['filter' => 'auth']);
$routes->get('dashboard/request-management', 'DashboardController::requestManagement', ['filter' => 'auth']);
$routes->get('dashboard/service-appointment-management', 'DashboardController::serviceAppointmentManagement', ['filter' => 'auth']);
$routes->get('dashboard/technician-view', 'DashboardController::technicianView', ['filter' => 'auth']);

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
    
    // Account Registry routes
    $routes->get('account-registry', 'Settings::accountRegistry', ['filter' => 'auth']);
    
    // Client management routes
    $routes->post('clients/add', 'Settings::addClient');
    $routes->get('clients/get/(:num)', 'Settings::getClient/$1');
    $routes->post('clients/update/(:num)', 'Settings::updateClient/$1');
    $routes->post('clients/delete/(:num)', 'Settings::deleteClient/$1');
    $routes->get('clients/dropdown', 'Settings::getClientsForDropdown');
    
    // Service Registry routes
    $routes->post('services/add', 'Settings::addService');
    $routes->get('services/get/(:num)', 'Settings::getService/$1');
    $routes->post('services/update/(:num)', 'Settings::updateService/$1');
    $routes->post('services/delete/(:num)', 'Settings::deleteService/$1');
    
    // Sequence management routes
    $routes->get('sequences/get/(:num)', 'Settings::getSequence/$1');
    $routes->post('sequences/update/(:num)', 'Settings::updateSequence/$1');
    
    // Currency routes
    $routes->post('currency/store', 'Settings::storeCurrency');
    $routes->post('currency/update/(:num)', 'Settings::updateCurrency/$1');
    $routes->get('currency/get/(:num)', 'Settings::getCurrency/$1');
    
    // Categories routes
    $routes->get('categories', 'Settings::categories', ['filter' => 'auth']);
    $routes->post('categories/add', 'Settings::addCategory');
    $routes->get('categories/get/(:num)', 'Settings::getCategory/$1', ['filter' => 'auth']);
    $routes->get('categories/test', 'Settings::testCategory'); // Test route
    $routes->post('categories/update/(:num)', 'Settings::updateCategory/$1', ['filter' => 'auth']);
    $routes->post('categories/delete/(:num)', 'Settings::deleteCategory/$1');
    $routes->get('categories/options', 'Settings::getCategoryOptions');
});

// Currency Routes
$routes->group('currency', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'CurrencyController::index');
    $routes->post('store', 'CurrencyController::store');
    $routes->post('update/(:num)', 'CurrencyController::update/$1');
    $routes->get('get/(:num)', 'CurrencyController::getCurrency/$1');
});

// Customer Routes
$routes->group('customers', ['filter' => 'auth'], function($routes) {
    // Companies routes
    $routes->get('companies', 'CompaniesController::index');
    $routes->post('companies/create', 'CompaniesController::create');
    $routes->get('companies/get/(:num)', 'CompaniesController::get/$1');
    $routes->post('companies/update/(:num)', 'CompaniesController::update/$1');
    $routes->post('companies/delete/(:num)', 'CompaniesController::delete/$1');
    $routes->get('companies/search', 'CompaniesController::search');
    
    // Contacts routes
    $routes->get('contacts', 'ContactsController::index');
    $routes->get('contacts/create', 'ContactsController::create');
    $routes->post('contacts/create', 'ContactsController::create');
    $routes->get('contacts/get/(:num)', 'ContactsController::get/$1');
    $routes->post('contacts/update/(:num)', 'ContactsController::update/$1');
    $routes->post('contacts/delete/(:num)', 'ContactsController::delete/$1');
    $routes->get('contacts/search', 'ContactsController::search');
    $routes->post('contacts/setPrimary/(:num)', 'ContactsController::setPrimary/$1');
    $routes->get('contacts/company/(:num)', 'ContactsController::getByCompany/$1');
    
    // Assets routes
    $routes->get('assets', 'AssetsController::index');
    $routes->post('assets/create', 'AssetsController::create');
    $routes->get('assets/get/(:num)', 'AssetsController::get/$1');
    $routes->post('assets/update/(:num)', 'AssetsController::update/$1');
    $routes->post('assets/delete/(:num)', 'AssetsController::delete/$1');
    $routes->get('assets/search', 'AssetsController::search');
    $routes->get('assets/contacts/company/(:num)', 'AssetsController::getContactsByCompany/$1');
});

// Work Order Management Routes
$routes->group('work-order-management', ['filter' => 'auth'], function($routes) {
    // Request routes
    $routes->get('request', 'RequestsController::index');
    $routes->post('request/create', 'RequestsController::create');
    $routes->get('request/get/(:num)', 'RequestsController::get/$1');
    $routes->post('request/update/(:num)', 'RequestsController::update/$1');
    $routes->post('request/delete/(:num)', 'RequestsController::delete/$1');
    $routes->get('request/search', 'RequestsController::search');
    $routes->post('request/status/(:num)', 'RequestsController::updateStatus/$1');
    $routes->get('request/company/(:num)', 'RequestsController::getByCompany/$1');
    
    // Estimates routes
    $routes->get('estimates', 'EstimatesController::index');
    $routes->post('estimates/create', 'EstimatesController::create');
    $routes->get('estimates/get/(:num)', 'EstimatesController::get/$1');
    $routes->post('estimates/update/(:num)', 'EstimatesController::update/$1');
    $routes->post('estimates/delete/(:num)', 'EstimatesController::delete/$1');
    $routes->get('estimates/search', 'EstimatesController::search');
    $routes->post('estimates/status/(:num)', 'EstimatesController::updateStatus/$1');
    $routes->get('estimates/company/(:num)', 'EstimatesController::getByCompany/$1');
    
    // Work Orders routes
    $routes->get('work-orders', 'WorkOrdersController::index');
    $routes->post('work-orders/create', 'WorkOrdersController::create');
    $routes->get('work-orders/get/(:num)', 'WorkOrdersController::get/$1');
    $routes->post('work-orders/update/(:num)', 'WorkOrdersController::update/$1');
    $routes->post('work-orders/delete/(:num)', 'WorkOrdersController::delete/$1');
    $routes->get('work-orders/search', 'WorkOrdersController::search');
    $routes->post('work-orders/status/(:num)', 'WorkOrdersController::updateStatus/$1');
    $routes->get('work-orders/company/(:num)', 'WorkOrdersController::getByCompany/$1');
    
    // Service Appointments routes
    $routes->get('service-appointments', 'ServiceAppointmentsController::index');
    $routes->post('service-appointments/create', 'ServiceAppointmentsController::create');
    $routes->get('service-appointments/get/(:num)', 'ServiceAppointmentsController::get/$1');
    $routes->post('service-appointments/update/(:num)', 'ServiceAppointmentsController::update/$1');
    $routes->post('service-appointments/delete/(:num)', 'ServiceAppointmentsController::delete/$1');
    $routes->get('service-appointments/search', 'ServiceAppointmentsController::search');
    $routes->post('service-appointments/status/(:num)', 'ServiceAppointmentsController::updateStatus/$1');
    $routes->get('service-appointments/work-order/(:num)', 'ServiceAppointmentsController::getByWorkOrder/$1');
    $routes->get('service-appointments/technician/(:num)', 'ServiceAppointmentsController::getByTechnician/$1');

    // Service Reports routes
    $routes->get('service-reports', 'ServiceReportsController::index');
    $routes->post('service-reports/create', 'ServiceReportsController::create');
    $routes->get('service-reports/get/(:num)', 'ServiceReportsController::get/$1');
    $routes->post('service-reports/update/(:num)', 'ServiceReportsController::update/$1');
    $routes->post('service-reports/delete/(:num)', 'ServiceReportsController::delete/$1');
    $routes->get('service-reports/search', 'ServiceReportsController::search');
    $routes->post('service-reports/status/(:num)', 'ServiceReportsController::updateStatus/$1');

    // Placeholder routes for other work order management modules
    $routes->get('scheduled-maintenances', 'ScheduledMaintenancesController::index');
});

// Workforce Management Routes
$routes->group('workforce', ['filter' => 'auth'], function($routes) {
    // Users routes
    $routes->get('users', 'WorkforceController::users');
    $routes->get('users/profile/(:num)', 'WorkforceController::userProfile/$1');
    $routes->get('users/search', 'WorkforceController::searchUsers');
    $routes->post('users/create', 'WorkforceController::createUser');
    $routes->get('users/get/(:num)', 'WorkforceController::getUser/$1');
    $routes->post('users/update/(:num)', 'WorkforceController::updateUser/$1');
    $routes->post('users/delete/(:num)', 'WorkforceController::deleteUser/$1');
    
    // Crew routes
    $routes->get('crew', 'WorkforceController::crew');
    $routes->post('crew/create', 'WorkforceController::createCrew');
    $routes->get('crew/get/(:num)', 'WorkforceController::getCrew/$1');
    $routes->post('crew/update/(:num)', 'WorkforceController::updateCrew/$1');
    $routes->post('crew/delete/(:num)', 'WorkforceController::deleteCrew/$1');
    
    // Equipment routes
    $routes->get('equipments', 'WorkforceController::equipments');
    $routes->post('equipments/create', 'WorkforceController::createEquipment');
    $routes->get('equipments/get/(:num)', 'WorkforceController::getEquipment/$1');
    $routes->post('equipments/update/(:num)', 'WorkforceController::updateEquipment/$1');
    $routes->post('equipments/delete/(:num)', 'WorkforceController::deleteEquipment/$1');
    
    // Trips routes
    $routes->get('trips', 'WorkforceController::trips');
    $routes->post('trips/create', 'WorkforceController::createTrip');
    $routes->get('trips/get/(:num)', 'WorkforceController::getTrip/$1');
    $routes->post('trips/update/(:num)', 'WorkforceController::updateTrip/$1');
    $routes->post('trips/delete/(:num)', 'WorkforceController::deleteTrip/$1');
    
    // Auto Log routes
    $routes->get('auto-log', 'WorkforceController::autoLog');
    $routes->post('auto-log/create', 'WorkforceController::createAutoLog');
    $routes->get('auto-log/get/(:num)', 'WorkforceController::getAutoLog/$1');
    $routes->post('auto-log/update/(:num)', 'WorkforceController::updateAutoLog/$1');
    $routes->post('auto-log/delete/(:num)', 'WorkforceController::deleteAutoLog/$1');
    
    // Time Off routes
    $routes->get('time-off', 'WorkforceController::timeOff');
    $routes->post('time-off/create', 'WorkforceController::createTimeOff');
    $routes->get('time-off/get/(:num)', 'WorkforceController::getTimeOff/$1');
    $routes->post('time-off/update/(:num)', 'WorkforceController::updateTimeOff/$1');
    $routes->post('time-off/delete/(:num)', 'WorkforceController::deleteTimeOff/$1');
});

// Parts & Services Routes
$routes->group('parts-services', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PartsServices::index');
    $routes->get('data', 'PartsServices::getData');
    $routes->post('create', 'PartsServices::create');
    $routes->get('show/(:num)', 'PartsServices::show/$1');
    $routes->post('update/(:num)', 'PartsServices::update/$1');
    $routes->delete('delete/(:num)', 'PartsServices::delete/$1');
    $routes->get('stats', 'PartsServices::getStats');
    $routes->get('insights', 'PartsServices::getPopularityInsights');
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
