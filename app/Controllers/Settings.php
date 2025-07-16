<?php

namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\FiscalYearModel;
use App\Models\BusinessHoursModel;
use App\Models\UserModel;
use App\Models\AuditLogModel;
use App\Models\TerritoryModel;
use App\Models\SkillModel;
use App\Models\UserSkillModel;
use App\Models\HolidayModel;
use App\Models\RecordTemplateModel;
use App\Models\ClientModel;
use App\Models\ServiceRegistryModel;
use App\Models\AccountSequenceModel;
use App\Models\CategoryModel;

class Settings extends BaseController
{
    protected $organizationModel;
    protected $fiscalYearModel;
    protected $businessHoursModel;
    protected $userModel;
    protected $auditLogModel;
    protected $territoryModel;
    protected $skillModel;
    protected $userSkillModel;
    protected $holidayModel;
    protected $recordTemplateModel;
    protected $clientModel;
    protected $serviceRegistryModel;
    protected $accountSequenceModel;
    protected $categoryModel;
    
    public function __construct()
    {
        $this->organizationModel = new OrganizationModel();
        $this->fiscalYearModel = new FiscalYearModel();
        $this->businessHoursModel = new BusinessHoursModel();
        $this->userModel = new UserModel();
        $this->auditLogModel = new AuditLogModel();
        $this->territoryModel = new TerritoryModel();
        $this->skillModel = new SkillModel();
        $this->userSkillModel = new UserSkillModel();
        $this->holidayModel = new HolidayModel();
        $this->recordTemplateModel = new RecordTemplateModel();
        $this->clientModel = new ClientModel();
        $this->serviceRegistryModel = new ServiceRegistryModel();
        $this->accountSequenceModel = new AccountSequenceModel();
        $this->categoryModel = new CategoryModel();
    }
    
    public function index()
    {
        return redirect()->to('/settings/organization');
    }

    public function organization()
    {
        $organization = $this->organizationModel->getOrganization();
        $businessHours = $this->businessHoursModel->getByOrganizationId(1);
        
        $data = [
            'title' => 'Organization Profile',
            'activeTab' => 'organization',
            'organization' => $organization,
            'businessHours' => $businessHours,
            'businessHoursFormatted' => $this->businessHoursModel->getFormattedHours($businessHours),
            'industryOptions' => $this->organizationModel->getIndustryOptions(),
            'timezoneOptions' => $this->organizationModel->getTimezoneOptions(),
            'weekdays' => $this->businessHoursModel->getWeekdays(),
            'timeOptions' => $this->businessHoursModel->getTimeOptions()
        ];
        
        return view('settings/organization', $data);
    }

    public function updateOrganization()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/settings/organization');
        }
        
        // Get POST data
        $data = $this->request->getPost();
        
        // Remove CSRF token from data
        unset($data['csrf_test_name']);
        
        // Validate and update
        if ($this->organizationModel->updateOrganization(1, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Organization details updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update organization details',
                'errors' => $this->organizationModel->errors()
            ]);
        }
    }

    public function general()
    {
        $data = [
            'title' => 'General Settings',
            'activeTab' => 'general'
        ];
        
        return view('settings/general', $data);
    }

    public function currency()
    {
        $currencyModel = new \App\Models\CurrencyModel();
        
        $data = [
            'title' => 'Currency Settings',
            'activeTab' => 'currency',
            'currencies' => $currencyModel->findAll()
        ];
        
        return view('settings/currency', $data);
    }

public function users()
{
    $status = $this->request->getVar('status') ?? 'active';
    $search = $this->request->getVar('search') ?? '';
    
    // Start with a fresh query builder
    $builder = $this->userModel->builder();
    
    // Apply status filter
    if ($status !== 'all') {
        $builder->where('status', $status);
    }
    
    // Apply search filter
    if (!empty($search)) {
        $builder->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->groupEnd();
    }
    
    // Get all users (no pagination for now)
    $users = $builder->get()->getResultArray();
    
    $data = [
        'title' => 'Users',
        'activeTab' => 'users',
        'users' => $users,
        'status' => $status,
        'search' => $search
    ];
    
    return view('settings/users', $data);
}

public function transactionSettings()
{
    $transactionSettingsModel = new \App\Models\TransactionSettingsModel();
    
    // Initialize if not already done
    $transactionSettingsModel->initializeDefaults();

    $settings = $transactionSettingsModel->getSettings();

    $data = [
        'title' => 'Transaction Settings',
        'activeTab' => 'transaction-settings',
        'settings' => $settings
    ];

    return view('settings/transaction_settings', $data);
}

public function updateTransactionSettings()
{
    header('Content-Type: application/json');
    
    // Check if user is logged in
    if (!session()->get('auth_token')) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Unauthorized: Please login to continue'
        ])->setStatusCode(401);
    }
    
    $transactionSettingsModel = new \App\Models\TransactionSettingsModel();
    $data = $this->request->getPost();
    
    // Remove CSRF token
    unset($data['csrf_test_name']);
    unset($data['csrf_token']);
    unset($data['csrf_ghash']);
    
    // Convert form data to settings format
    $settings = [
        'allow_roundoff_transactions' => [
            'value' => isset($data['allow_roundoff_transactions']) ? true : false,
            'type' => 'boolean',
            'description' => 'Allow roundoff for transactions'
        ],
        'password_protect_exported_files' => [
            'value' => isset($data['password_protect_exported_files']) ? true : false,
            'type' => 'boolean',
            'description' => 'Password protect exported files'
        ],
        'mobile_checkin_preference' => [
            'value' => isset($data['mobile_checkin_preference']) ? true : false,
            'type' => 'boolean',
            'description' => 'Mobile App Check-In Preference'
        ],
        'allow_pricing_field_agent' => [
            'value' => isset($data['allow_pricing_field_agent']) ? true : false,
            'type' => 'boolean',
            'description' => 'Allow Field Agent to see pricing'
        ],
        'allow_technicians_raise_invoices' => [
            'value' => isset($data['allow_technicians_raise_invoices']) ? true : false,
            'type' => 'boolean',
            'description' => 'Allow technicians to raise invoices'
        ],
        'field_agent_appointment_confirmation' => [
            'value' => isset($data['field_agent_appointment_confirmation']) ? true : false,
            'type' => 'boolean',
            'description' => 'Field Agent Appointment Confirmation'
        ],
        'minimum_interval_next_appointment' => [
            'value' => (int)($data['minimum_interval_next_appointment'] ?? 1),
            'type' => 'integer',
            'description' => 'Minimum interval for next appointment (hours)'
        ],
        'allow_overlapping_appointments' => [
            'value' => isset($data['allow_overlapping_appointments']) ? true : false,
            'type' => 'boolean',
            'description' => 'Allow Overlapping Appointments'
        ],
        'auto_complete_work_order' => [
            'value' => isset($data['auto_complete_work_order']) ? true : false,
            'type' => 'boolean',
            'description' => 'Automatically complete a work order'
        ],
        'prompt_complete_work_order' => [
            'value' => isset($data['prompt_complete_work_order']) ? true : false,
            'type' => 'boolean',
            'description' => 'Prompt to complete work order'
        ],
        'service_report_required_sa_completion' => [
            'value' => isset($data['service_report_required_sa_completion']) ? true : false,
            'type' => 'boolean',
            'description' => 'Service Report required for SA completion'
        ],
        'jobsheets_completion_required_sa' => [
            'value' => isset($data['jobsheets_completion_required_sa']) ? true : false,
            'type' => 'boolean',
            'description' => 'Jobsheets completion required for SA completion'
        ],
        'auto_pause_timesheet' => [
            'value' => isset($data['auto_pause_timesheet']) ? true : false,
            'type' => 'boolean',
            'description' => 'Auto Pause timesheet'
        ],
        'auto_pause_time' => [
            'value' => $data['auto_pause_time'] ?? '17:59',
            'type' => 'string',
            'description' => 'Auto Pause Time'
        ],
        'allow_overlapping_timesheet_entries' => [
            'value' => isset($data['allow_overlapping_timesheet_entries']) ? true : false,
            'type' => 'boolean',
            'description' => 'Allow Overlapping or Concurrent Timesheet Entries'
        ],
        'hide_attachments_service_reports' => [
            'value' => isset($data['hide_attachments_service_reports']) ? true : false,
            'type' => 'boolean',
            'description' => 'Hide attachments from service reports'
        ],
        'remove_customer_signature' => [
            'value' => isset($data['remove_customer_signature']) ? true : false,
            'type' => 'boolean',
            'description' => 'Remove customer signature on editing service reports'
        ],
        'estimate_email_approval' => [
            'value' => isset($data['estimate_email_approval']) ? true : false,
            'type' => 'boolean',
            'description' => 'Estimate - Email Approval'
        ],
        'email_approval_expiry_days' => [
            'value' => (int)($data['email_approval_expiry_days'] ?? 7),
            'type' => 'integer',
            'description' => 'Expiry Time for Email Approval Link (Days)'
        ],
        'terms_conditions_estimate' => [
            'value' => $data['terms_conditions_estimate'] ?? '',
            'type' => 'string',
            'description' => 'Terms & Conditions for estimate template'
        ],
        'customer_notes_estimate' => [
            'value' => $data['customer_notes_estimate'] ?? '',
            'type' => 'string',
            'description' => 'Customer Notes for estimate template'
        ]
    ];
    
    if ($transactionSettingsModel->updateSettings($settings)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaction settings updated successfully'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update transaction settings',
            'errors' => $transactionSettingsModel->errors()
        ]);
    }
}

public function addUser()
{
    // Force JSON response to prevent debug toolbar injection
    header('Content-Type: application/json');
    
    // Add detailed logging
    log_message('debug', 'addUser method called');
    log_message('debug', 'Request method: ' . $this->request->getMethod());
    log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
    
    // Check if user is logged in
    if (!session()->get('auth_token')) {
        log_message('error', 'addUser: User not authenticated');
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Unauthorized: Please login to continue'
        ])->setStatusCode(401);
    }
    
    // Check multiple ways to detect POST request
    $method = $this->request->getMethod();
    $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    
    // Log the request method for debugging
    log_message('debug', 'addUser called - Method from getMethod(): ' . $method);
    log_message('debug', 'addUser called - Method from $_SERVER: ' . $serverMethod);
    log_message('debug', 'addUser called - POST data exists: ' . (!empty($this->request->getPost()) ? 'YES' : 'NO'));
    
    // For now, accept any method if POST data exists
    // This is a temporary workaround for the routing issue
    if (empty($this->request->getPost()) && empty($_POST)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
        ])->setStatusCode(400);
    }
        $userData = $this->request->getPost();
        
        // Log raw data
        log_message('debug', 'Raw user data: ' . json_encode($userData));
        
        // Remove CSRF and confirm password fields
        unset($userData['csrf_test_name']);
        unset($userData['csrf_token']);
        unset($userData['confirm_password']);
        
        // Check for required fields
        if (empty($userData['email'])) {
            log_message('error', 'addUser: Email is required');
            return $this->response->setJSON(['success' => false, 'message' => 'Email is required']);
        }
        
        if (empty($userData['password'])) {
            log_message('error', 'addUser: Password is required');
            return $this->response->setJSON(['success' => false, 'message' => 'Password is required']);
        }
        
        // Check if email already exists
        $existingUser = $this->userModel->where('email', $userData['email'])->first();
        if ($existingUser) {
            log_message('error', 'addUser: Email already exists: ' . $userData['email']);
            return $this->response->setJSON(['success' => false, 'message' => 'This email address is already registered.']);
        }
        
        // Generate username from email if not provided
        if (!isset($userData['username']) || empty($userData['username'])) {
            $userData['username'] = explode('@', $userData['email'])[0];
        }
        
        // Check if username already exists
        $existingUsername = $this->userModel->where('username', $userData['username'])->first();
        if ($existingUsername) {
            log_message('error', 'addUser: Username already exists: ' . $userData['username']);
            return $this->response->setJSON(['success' => false, 'message' => 'This username is already taken.']);
        }
        
        // Set created_by from session
        $currentUser = session()->get('user');
        if ($currentUser) {
            $userData['created_by'] = $currentUser['first_name'] . ' ' . $currentUser['last_name'];
        }
        
        // Set default values for optional fields if not provided
        if (!isset($userData['language'])) {
            $userData['language'] = 'en-US';
        }
        if (!isset($userData['enable_rtl'])) {
            $userData['enable_rtl'] = 0;
        }
        
        // Log final data before save
        log_message('debug', 'Final user data before save: ' . json_encode($userData));
        
        try {
            if ($this->userModel->save($userData)) {
                log_message('info', 'User added successfully: ' . $userData['email']);
                return $this->response->setJSON(['success' => true, 'message' => 'User added successfully.']);
            } else {
                $errors = $this->userModel->errors();
                log_message('error', 'UserModel validation errors: ' . json_encode($errors));
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to add user', 'errors' => $errors]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in addUser: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

public function getUser($id)
{
    // Check if user is logged in
    if (!session()->get('auth_token')) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Unauthorized: Please login to continue'
        ])->setStatusCode(401);
    }
    
    $user = $this->userModel->find($id);
    
    if ($user) {
        unset($user['password']); // Don't send password
        return $this->response->setJSON([
            'success' => true,
            'user' => $user
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'User not found'
        ])->setStatusCode(404);
    }
}

    public function updateUser()
    {
        // Force JSON response even if errors occur
        header('Content-Type: application/json');
        
        // Set JSON content type header immediately
        $this->response->setContentType('application/json');
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // Log the request method for debugging
        log_message('debug', 'updateUser called - Method from getMethod(): ' . $method);
        log_message('debug', 'updateUser called - Method from $_SERVER: ' . $serverMethod);
        log_message('debug', 'updateUser called - POST data exists: ' . (!empty($this->request->getPost()) ? 'YES' : 'NO'));
        log_message('debug', 'updateUser called - Raw input: ' . file_get_contents('php://input'));
        
        // For now, accept any method if POST data exists
        // This is a temporary workaround for the routing issue
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        try {
            // Log request details for debugging
            log_message('debug', 'updateUser called - Method: ' . $this->request->getMethod());
            log_message('debug', 'Is AJAX: ' . ($this->request->isAJAX() ? 'YES' : 'NO'));
            log_message('debug', 'Headers: ' . json_encode($this->request->getHeaders()));
            
            $userData = $this->request->getPost();
            log_message('debug', 'Raw POST data: ' . json_encode($userData));
            
            // Validate required fields
            if (!isset($userData['id']) || empty($userData['id'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User ID is required'
                ]);
            }
            
            $userId = $userData['id'];
            
            // Remove CSRF token from data if present
            unset($userData['csrf_test_name']);
            unset($userData['csrf_token']);
            
            // Remove password from update if not provided
            if (empty($userData['password'])) {
                unset($userData['password']);
            }
            
            // Don't allow username change
            unset($userData['username']);
            
            // Log the data being updated for debugging
            log_message('debug', 'Updating user ' . $userId . ' with data: ' . json_encode($userData));
            
            if ($this->userModel->update($userId, $userData)) {
                log_message('debug', 'User update successful');
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User updated successfully'
                ]);
            } else {
                $errors = $this->userModel->errors();
                log_message('error', 'Failed to update user: ' . json_encode($errors));
                
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user',
                    'errors' => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in updateUser: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Force JSON response even in exception
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
            exit;
        }
    }

public function getUserTimeline($userId)
{
    // Check if user is logged in
    if (!session()->get('auth_token')) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Unauthorized: Please login to continue'
        ])->setStatusCode(401);
    }
    
    $filter = $this->request->getVar('filter') ?? 'all';
    
    // Get current date
    $now = new \DateTime();
    $startDate = null;
    
    // Apply date filter
    switch ($filter) {
        case 'today':
            $startDate = new \DateTime('today');
            break;
        case 'yesterday':
            $startDate = new \DateTime('yesterday');
            $endDate = new \DateTime('today');
            break;
        case 'last_week':
            $startDate = new \DateTime('-1 week');
            break;
        case 'last_month':
            $startDate = new \DateTime('-1 month');
            break;
        case 'last_year':
            $startDate = new \DateTime('-1 year');
            break;
    }
    
    // Fetch real timeline data from the audit_logs table
    $timelineData = $this->auditLogModel->getUserTimeline($userId, $filter);
    // Filter by date if needed
    if ($startDate) {
        $timelineData = array_filter($timelineData, function($item) use ($startDate, $filter) {
            $itemDate = new \DateTime($item['created_at']);
            
            if ($filter === 'yesterday') {
                $endDate = new \DateTime('today');
                return $itemDate >= $startDate && $itemDate < $endDate;
            }
            
            return $itemDate >= $startDate;
        });
    }
    
    // Re-index array after filtering
    $timelineData = array_values($timelineData);
    
    return $this->response->setJSON([
        'success' => true,
        'timeline' => $timelineData
    ]);
}

    public function territories()
    {
        $status = $this->request->getVar('status') ?? 'active';
        $search = $this->request->getVar('search') ?? '';
        
        // Start with a fresh query builder
        $builder = $this->territoryModel->builder();
        
        // Join with users table to get creator name
        $builder->select('territories.*, CONCAT(users.first_name, " ", users.last_name) as creator_name')
                ->join('users', 'users.id = territories.created_by', 'left');
        
        // Apply status filter
        if ($status !== 'all') {
            $builder->where('territories.status', $status);
        }
        
        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('territories.name', $search)
                    ->orLike('territories.description', $search)
                    ->groupEnd();
        }
        
        // Get all territories
        $territories = $builder->get()->getResultArray();
        
        $data = [
            'title' => 'Territories',
            'activeTab' => 'territories',
            'territories' => $territories,
            'status' => $status,
            'search' => $search
        ];
        
        return view('settings/territories', $data);
    }
    
    public function addTerritory()
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // Log the request method for debugging
        log_message('debug', 'addTerritory called - Method from getMethod(): ' . $method);
        log_message('debug', 'addTerritory called - Method from $_SERVER: ' . $serverMethod);
        log_message('debug', 'addTerritory called - POST data exists: ' . (!empty($this->request->getPost()) ? 'YES' : 'NO'));
        
        // For now, accept any method if POST data exists
        // This is a temporary workaround for the routing issue
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $territoryData = $this->request->getPost();
        
        // Remove CSRF token
        unset($territoryData['csrf_test_name']);
        unset($territoryData['csrf_token']);
        unset($territoryData['csrf_ghash']);
        
        // Set created_by from session
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id'])) {
            $territoryData['created_by'] = $currentUser['id'];
        } else {
            // If no user ID in session, default to 1 (admin)
            $territoryData['created_by'] = 1;
        }
        
        if ($this->territoryModel->save($territoryData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Territory added successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add territory', 'errors' => $this->territoryModel->errors()]);
        }
    }

    public function getTerritory($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $territory = $this->territoryModel->find($id);
        
        if ($territory) {
            return $this->response->setJSON([
                'success' => true,
                'territory' => $territory
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Territory not found'
            ])->setStatusCode(404);
        }
    }

    public function updateTerritory($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // Log the request method for debugging
        log_message('debug', 'updateTerritory called - Method from getMethod(): ' . $method);
        log_message('debug', 'updateTerritory called - Method from $_SERVER: ' . $serverMethod);
        log_message('debug', 'updateTerritory called - POST data exists: ' . (!empty($this->request->getPost()) ? 'YES' : 'NO'));
        
        // For now, accept any method if POST data exists
        // This is a temporary workaround for the routing issue
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $territoryData = $this->request->getPost();
        
        // Remove CSRF token and id field (id is passed in URL)
        unset($territoryData['csrf_test_name']);
        unset($territoryData['csrf_token']);
        unset($territoryData['csrf_ghash']);
        unset($territoryData['id']);
        
        if ($this->territoryModel->update($id, $territoryData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Territory updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update territory', 'errors' => $this->territoryModel->errors()]);
        }
    }

    public function deleteTerritory($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // Log the request method for debugging
        log_message('debug', 'deleteTerritory called - Method from getMethod(): ' . $method);
        log_message('debug', 'deleteTerritory called - Method from $_SERVER: ' . $serverMethod);
        log_message('debug', 'deleteTerritory called - POST data exists: ' . (!empty($this->request->getPost()) ? 'YES' : 'NO'));
        
        // For now, accept any method if POST data exists (including empty POST for delete operations)
        // Delete operations might not have body data, so we're more lenient here
        if ($method !== 'post' && $serverMethod !== 'POST' && empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        if ($this->territoryModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Territory deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete territory']);
        }
    }

    public function skills()
    {
        $status = $this->request->getVar('status') ?? 'active';
        $search = $this->request->getVar('search') ?? '';
        
        // Get skills with creator information
        $skills = $this->skillModel->getSkillsWithCreator($status, $search);
        
        $data = [
            'title' => 'Skills',
            'activeTab' => 'skills',
            'skills' => $skills,
            'status' => $status,
            'search' => $search
        ];
        
        return view('settings/skills', $data);
    }
    
    public function addSkill()
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // For now, accept any method if POST data exists
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $skillData = $this->request->getPost();
        
        // Remove CSRF token
        unset($skillData['csrf_test_name']);
        unset($skillData['csrf_token']);
        unset($skillData['csrf_ghash']);
        
        // Set created_by from session
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id'])) {
            $skillData['created_by'] = $currentUser['id'];
        } else {
            // If no user ID in session, default to 1 (admin)
            $skillData['created_by'] = 1;
        }
        
        if ($this->skillModel->save($skillData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Skill added successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add skill', 'errors' => $this->skillModel->errors()]);
        }
    }

    public function getSkill($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $skill = $this->skillModel->find($id);
        
        if ($skill) {
            return $this->response->setJSON([
                'success' => true,
                'skill' => $skill
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Skill not found'
            ])->setStatusCode(404);
        }
    }

    public function updateSkill($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // For now, accept any method if POST data exists
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $skillData = $this->request->getPost();
        
        // Remove CSRF token and id field (id is passed in URL)
        unset($skillData['csrf_test_name']);
        unset($skillData['csrf_token']);
        unset($skillData['csrf_ghash']);
        unset($skillData['id']);
        
        if ($this->skillModel->update($id, $skillData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Skill updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update skill', 'errors' => $this->skillModel->errors()]);
        }
    }

    public function deleteSkill($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // For now, accept any method if POST data exists (including empty POST for delete operations)
        if ($method !== 'post' && $serverMethod !== 'POST' && empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        if ($this->skillModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Skill deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete skill']);
        }
    }

    public function holiday()
    {
        $data = [
            'title' => 'Holiday',
            'activeTab' => 'holiday'
        ];
        
        return view('settings/holiday', $data);
    }

    public function billing()
    {
        $data = [
            'title' => 'Billing Setup',
            'activeTab' => 'billing'
        ];
        
        return view('settings/billing', $data);
    }

    public function taxSettings()
    {
        $data = [
            'title' => 'Tax Settings',
            'activeTab' => 'tax-settings'
        ];
        
        return view('settings/tax-settings', $data);
    }
    
    public function fiscalYear()
    {
        $fiscalYear = $this->fiscalYearModel->getByOrganizationId(1);
        
        $data = [
            'title' => 'Fiscal Year Settings',
            'activeTab' => 'organization',
            'fiscalYear' => $fiscalYear,
            'monthOptions' => $this->fiscalYearModel->getMonthOptions(),
            'dayOptions' => $this->fiscalYearModel->getDayOptions(),
            'yearOptions' => $this->fiscalYearModel->getYearOptions()
        ];
        
        return view('settings/fiscal_year', $data);
    }
    
    public function updateFiscalYear()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/settings/fiscal-year');
        }
        
        // Get POST data
        $data = $this->request->getPost();
        
        // Remove CSRF token from data
        unset($data['csrf_test_name']);
        
        // Remove month/day fields that were used for UI
        unset($data['fiscal_start_month']);
        unset($data['fiscal_start_day']);
        unset($data['fiscal_end_month']);
        unset($data['fiscal_end_day']);
        
        // Add organization ID
        $data['organization_id'] = 1;
        
        // Check if fiscal year exists
        $existingFiscalYear = $this->fiscalYearModel->getByOrganizationId(1);
        
        if ($existingFiscalYear) {
            // Update existing record
            if ($this->fiscalYearModel->update($existingFiscalYear['id'], $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Fiscal year settings updated successfully'
                ]);
            }
        } else {
            // Insert new record
            if ($this->fiscalYearModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Fiscal year settings created successfully'
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update fiscal year settings',
            'errors' => $this->fiscalYearModel->errors()
        ]);
    }
    
    public function updateBusinessHours()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/settings/organization');
        }
        
        // Get POST data
        $data = $this->request->getPost();
        
        // Remove CSRF token from data
        unset($data['csrf_test_name']);
        
        // Add organization ID
        $data['organization_id'] = 1;
        
        // If business hours type is not custom, clear all time fields
        if ($data['business_hours_type'] !== 'custom') {
            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($weekdays as $day) {
                $data[$day . '_start'] = null;
                $data[$day . '_end'] = null;
            }
        }
        
        // Check if business hours exists
        $existingBusinessHours = $this->businessHoursModel->getByOrganizationId(1);
        
        if ($existingBusinessHours) {
            // Update existing record
            if ($this->businessHoursModel->update($existingBusinessHours['id'], $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Business hours updated successfully'
                ]);
            }
        } else {
            // Insert new record
            if ($this->businessHoursModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Business hours created successfully'
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update business hours',
            'errors' => $this->businessHoursModel->errors()
        ]);
    }
    
    public function storeCurrency()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        $currencyModel = new \App\Models\CurrencyModel();
        
        // Get JSON data
        $json = $this->request->getJSON();
        
        $data = [
            'name' => $json->name,
            'symbol' => $json->symbol,
            'iso_code' => strtoupper($json->iso_code),
            'exchange_rate' => $json->exchange_rate,
            'thousand_separator' => $json->thousand_separator,
            'decimal_spaces' => $json->decimal_spaces,
            'decimal_separator' => $json->decimal_separator,
            'is_active' => 1
        ];
        
        if ($currencyModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Currency added successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add currency', 'errors' => $currencyModel->errors()]);
        }
    }
    
    public function updateCurrency($id = null)
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        $currencyModel = new \App\Models\CurrencyModel();
        
        // Get JSON data
        $json = $this->request->getJSON();
        
        $data = [
            'id' => $id,
            'exchange_rate' => $json->exchange_rate,
            'thousand_separator' => $json->thousand_separator,
            'decimal_spaces' => $json->decimal_spaces,
            'decimal_separator' => $json->decimal_separator
        ];
        
        if ($currencyModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Currency updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update currency', 'errors' => $currencyModel->errors()]);
        }
    }
    
    public function getCurrency($id)
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        $currencyModel = new \App\Models\CurrencyModel();
        $currency = $currencyModel->find($id);
        
        if ($currency) {
        return $this->response->setJSON(['success' => true, 'currency' => $currency]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Currency not found'])->setStatusCode(404);
        }
    }
    
    // CATEGORY MANAGEMENT METHODS
    public function categories()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return redirect()->to('/login');
        }
        
        $status = $this->request->getVar('status') ?? 'active';
        $search = $this->request->getVar('search') ?? '';
        
        // Initialize default categories if none exist
        $this->categoryModel->initializeDefaultCategories();
        
        // Get categories with creator information
        $categories = $this->categoryModel->getCategoriesWithCreator($status, $search);
        
        $data = [
            'title' => 'Categories',
            'activeTab' => 'categories',
            'categories' => $categories,
            'status' => $status,
            'search' => $search,
            'categoryStats' => $this->categoryModel->getCategoryStats()
        ];
        
        return view('settings/categories', $data);
    }
    
    public function addCategory()
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // For now, accept any method if POST data exists
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $categoryData = $this->request->getPost();
        
        // Remove CSRF token
        unset($categoryData['csrf_test_name']);
        unset($categoryData['csrf_token']);
        unset($categoryData['csrf_ghash']);
        
        // Set created_by from session
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id'])) {
            $categoryData['created_by'] = $currentUser['id'];
        } else {
            // If no user ID in session, default to 1 (admin)
            $categoryData['created_by'] = 1;
        }
        
        // Set default status if not provided
        if (!isset($categoryData['is_active'])) {
            $categoryData['is_active'] = 1;
        }
        
        if ($this->categoryModel->save($categoryData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Category added successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add category', 'errors' => $this->categoryModel->errors()]);
        }
    }
    
    public function getCategory($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $category = $this->categoryModel->find($id);
        
        if ($category) {
            return $this->response->setJSON([
                'success' => true,
                'category' => $category
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Category not found'
            ])->setStatusCode(404);
        }
    }
    
    public function testCategory()
    {
        header('Content-Type: application/json');
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Test endpoint working'
        ]);
    }
    
    public function updateCategory($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // For now, accept any method if POST data exists
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $categoryData = $this->request->getPost();
        
        // Remove CSRF token
        unset($categoryData['csrf_test_name']);
        unset($categoryData['csrf_token']);
        unset($categoryData['csrf_ghash']);
        
        // Manual validation for name uniqueness (excluding current record)
        if (!empty($categoryData['name'])) {
            $existingCategory = $this->categoryModel
                ->where('name', $categoryData['name'])
                ->where('id !=', $id)
                ->first();
            
            if ($existingCategory) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update category',
                    'errors' => ['name' => 'Category name already exists.']
                ]);
            }
        }
        
        // Set updated_by from session
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id'])) {
            $categoryData['updated_by'] = $currentUser['id'];
        }
        
        if ($this->categoryModel->update($id, $categoryData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Category updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update category', 'errors' => $this->categoryModel->errors()]);
        }
    }
    
    public function deleteCategory($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // For now, accept any method if POST data exists (including empty POST for delete operations)
        if ($method !== 'post' && $serverMethod !== 'POST' && empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        // Check if category is being used in parts or services
        if ($this->categoryModel->isCategoryInUse($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cannot delete category: It is currently being used by parts or services.'
            ])->setStatusCode(400);
        }
        
        if ($this->categoryModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Category deleted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete category']);
        }
    }
    
    public function getCategoryOptions()
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $options = $this->categoryModel->getCategoryOptions();
        
        return $this->response->setJSON([
            'success' => true,
            'options' => $options
        ]);
    }
    
    // User Skills Management Methods
    
    public function getUserSkills($userId)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $userSkills = $this->userSkillModel->getUserSkillsWithDetails($userId);
        $availableSkills = $this->userSkillModel->getAvailableSkills();
        
        return $this->response->setJSON([
            'success' => true,
            'userSkills' => $userSkills,
            'availableSkills' => $availableSkills
        ]);
    }
    
    public function assignUserSkill()
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        
        // Set assigned_by from session
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id'])) {
            $data['assigned_by'] = $currentUser['id'];
        } else {
            $data['assigned_by'] = 1; // Default to admin
        }
        
        // Check if user already has this skill
        if ($this->userSkillModel->userHasSkill($data['user_id'], $data['skill_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User already has this skill assigned'
            ]);
        }
        
        if ($this->userSkillModel->assignSkillToUser($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Skill assigned to user successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to assign skill to user', 'errors' => $this->userSkillModel->errors()]);
        }
    }
    
    public function updateUserSkill($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        if (empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token and id field (id is passed in URL)
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        unset($data['id']);
        
        if ($this->userSkillModel->updateUserSkill($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'User skill updated successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update user skill', 'errors' => $this->userSkillModel->errors()]);
        }
    }
    
    public function removeUserSkill()
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        $userId = $data['user_id'] ?? null;
        $skillId = $data['skill_id'] ?? null;
        
        if (!$userId || !$skillId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID and Skill ID are required'
            ]);
        }
        
        if ($this->userSkillModel->removeSkillFromUser($userId, $skillId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Skill removed from user successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to remove skill from user']);
        }
    }
    
    public function deleteUser($id)
    {
        // Force JSON response to prevent debug toolbar injection
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Check multiple ways to detect POST request
        $method = $this->request->getMethod();
        $serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        
        // Log the request method for debugging
        log_message('debug', 'deleteUser called - Method from getMethod(): ' . $method);
        log_message('debug', 'deleteUser called - Method from $_SERVER: ' . $serverMethod);
        log_message('debug', 'deleteUser called - POST data exists: ' . (!empty($this->request->getPost()) ? 'YES' : 'NO'));
        
        // For now, accept any method if POST data exists (including empty POST for delete operations)
        // Delete operations might not have body data, so we're more lenient here
        if ($method !== 'post' && $serverMethod !== 'POST' && empty($this->request->getPost()) && empty($_POST)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method. Method: ' . $method . ' / ' . $serverMethod
            ])->setStatusCode(400);
        }
        
        // Prevent deletion of current user
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id']) && $currentUser['id'] == $id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You cannot delete your own user account'
            ])->setStatusCode(403);
        }
        
        // Check if user exists
        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ])->setStatusCode(404);
        }
        
        // Log deletion attempt
        log_message('info', 'Attempting to delete user: ' . $user['email'] . ' (ID: ' . $id . ')');
        
        if ($this->userModel->delete($id)) {
            log_message('info', 'User deleted successfully: ' . $user['email'] . ' (ID: ' . $id . ')');
            return $this->response->setJSON(['success' => true, 'message' => 'User deleted successfully.']);
        } else {
            log_message('error', 'Failed to delete user: ' . $user['email'] . ' (ID: ' . $id . ')');
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete user']);
        }
    }

    // PROFILES MANAGEMENT
    public function profiles()
    {
        $profileModel = new \App\Models\ProfileModel();
        
        $data = [
            'title' => 'Profiles',
            'activeTab' => 'profiles',
            'profiles' => $profileModel->getProfilesWithUserCount()
        ];
        
        return view('settings/profiles', $data);
    }
    
    public function addProfile()
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $profileModel = new \App\Models\ProfileModel();
        $data = $this->request->getPost();
        
        // Process permissions if provided
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = $data['permissions'];
        } else {
            $data['permissions'] = $profileModel->getDefaultPermissions();
        }
        
        if ($profileModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile added successfully.',
                'profile' => $profileModel->find($profileModel->getInsertID())
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add profile',
                'errors' => $profileModel->errors()
            ]);
        }
    }
    
    public function getProfile($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $profileModel = new \App\Models\ProfileModel();
        $profile = $profileModel->find($id);
        
        if ($profile) {
            return $this->response->setJSON([
                'success' => true,
                'profile' => $profile
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Profile not found'
            ])->setStatusCode(404);
        }
    }
    
    public function updateProfile($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $profileModel = new \App\Models\ProfileModel();
        $data = $this->request->getPost();
        
        // Check if profile exists
        $profile = $profileModel->find($id);
        if (!$profile) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Profile not found'
            ])->setStatusCode(404);
        }
        
        // Process permissions if provided
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = $data['permissions'];
        }
        
        // Remove id from data to prevent updating primary key
        unset($data['id']);
        
        if ($profileModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'profile' => $profileModel->find($id)
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update profile',
                'errors' => $profileModel->errors()
            ]);
        }
    }
    
    public function deleteProfile($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $profileModel = new \App\Models\ProfileModel();
        
        // Check if profile exists
        $profile = $profileModel->find($id);
        if (!$profile) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Profile not found'
            ])->setStatusCode(404);
        }
        
        try {
            if ($profileModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Profile deleted successfully.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete profile'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(400);
        }
    }
    
    public function auditLog()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return redirect()->to('/login');
        }
        
        // Get filter parameters
        $dateFilter = $this->request->getVar('date') ?? 'last-30-days';
        $userFilter = $this->request->getVar('user') ?? '';
        $subTypeFilter = $this->request->getVar('sub_type') ?? '';
        $actionFilter = $this->request->getVar('action') ?? '';
        $tabFilter = $this->request->getVar('tab') ?? 'audit';
        
        // Entity Log specific filters
        $entityTypeFilter = $this->request->getVar('entity_type') ?? '';
        $entityActionFilter = $this->request->getVar('entity_action') ?? '';
        
        // Get all users for filter dropdown
        $users = $this->userModel->select('id, first_name, last_name, email')
                                 ->orderBy('first_name', 'ASC')
                                 ->findAll();
        
        // Build audit log query
        $builder = $this->auditLogModel->builder();
        $builder->select('audit_logs.*, CONCAT(users.first_name, " ", users.last_name) as user_name')
                ->join('users', 'users.id = audit_logs.user_id', 'left')
                ->orderBy('audit_logs.created_at', 'DESC');
        
        // Apply date filter
        $this->applyDateFilter($builder, $dateFilter);
        
        // Apply user filter
        if (!empty($userFilter)) {
            $builder->where('audit_logs.user_id', $userFilter);
        }
        
        // Apply sub type filter
        if (!empty($subTypeFilter)) {
            $builder->where('audit_logs.module', $subTypeFilter);
        }
        
        // Apply action filter
        if (!empty($actionFilter)) {
            $builder->where('audit_logs.event_type', $actionFilter);
        }
        
        // Get audit logs
        $auditLogs = $builder->get()->getResultArray();
        
        // Get entity logs if Entity Log tab is active
        $entityLogs = [];
        if ($tabFilter === 'entity') {
            $entityLogs = $this->getEntityLogs($dateFilter, $userFilter, $entityTypeFilter, $entityActionFilter);
        }
        
        // Format the data for display
        foreach ($auditLogs as &$log) {
            // Format created_at for display
            $log['formatted_date'] = date('M j, Y g:i A', strtotime($log['created_at']));
            
            // Format module name for display
            $log['sub_type_display'] = $this->formatSubType($log['module'] ?? $log['event_type']);
            
            // Format action for display
            $log['action_display'] = $this->formatAction($log['event_type']);
            
            // Ensure user name is set
            if (empty($log['user_name'])) {
                $log['user_name'] = 'System';
            }
        }
        
        $data = [
            'title' => 'Audit Log',
            'activeTab' => 'audit-log',
            'auditLogs' => $auditLogs,
            'entityLogs' => $entityLogs,
            'users' => $users,
            'filters' => [
                'date' => $dateFilter,
                'user' => $userFilter,
                'sub_type' => $subTypeFilter,
                'action' => $actionFilter,
                'tab' => $tabFilter,
                'entity_type' => $entityTypeFilter,
                'entity_action' => $entityActionFilter
            ],
            'subTypes' => $this->getSubTypes(),
            'actions' => $this->getActions(),
            'entityTypes' => $this->getEntityTypes(),
            'entityActions' => $this->getEntityActions()
        ];
        
        return view('settings/audit_log', $data);
    }
    
    private function applyDateFilter($builder, $dateFilter)
    {
        $now = new \DateTime();
        
        switch ($dateFilter) {
            case 'today':
                $startDate = new \DateTime('today');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
                
            case 'yesterday':
                $startDate = new \DateTime('yesterday');
                $endDate = new \DateTime('today');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                $builder->where('audit_logs.created_at <', $endDate->format('Y-m-d H:i:s'));
                break;
                
            case 'last-7-days':
                $startDate = new \DateTime('-7 days');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
                
            case 'last-30-days':
            default:
                $startDate = new \DateTime('-30 days');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
                
            case 'last-90-days':
                $startDate = new \DateTime('-90 days');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
                
            case 'last-year':
                $startDate = new \DateTime('-1 year');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
        }
    }
    
    private function formatSubType($module)
    {
        $subTypes = [
            'users' => 'USERS',
            'customers' => 'CUSTOMERS',
            'orders' => 'ORDERS',
            'holidays' => 'HOLIDAYS',
            'org_details' => 'ORG_DETAILS',
            'other_settings' => 'OTHER_SETTINGS'
        ];
        
        return $subTypes[$module] ?? strtoupper($module);
    }
    
    private function formatAction($eventType)
    {
        $actions = [
            'created' => 'CREATE',
            'updated' => 'UPDATE',
            'deleted' => 'DELETE',
            'user_created' => 'CREATE',
            'user_updated' => 'UPDATE',
            'user_deleted' => 'DELETE',
            'login' => 'LOGIN',
            'logout' => 'LOGOUT',
            'password_changed' => 'UPDATE',
            'status_changed' => 'UPDATE',
            'role_changed' => 'UPDATE',
            'disable' => 'DISABLE'
        ];
        
        return $actions[$eventType] ?? strtoupper($eventType);
    }
    
    private function getSubTypes()
    {
        return [
            'users' => 'Users',
            'customers' => 'Customers',
            'orders' => 'Orders',
            'holidays' => 'Holidays',
            'org_details' => 'Organization Details',
            'other_settings' => 'Other Settings'
        ];
    }
    
    private function getActions()
    {
        return [
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'login' => 'Login',
            'logout' => 'Logout',
            'disable' => 'Disable'
        ];
    }
    
    private function getEntityLogs($dateFilter, $userFilter, $entityTypeFilter, $entityActionFilter)
    {
        // Create sample entity log data for demonstration
        // In a real application, this would query a dedicated entity_logs table
        $sampleEntityLogs = [
            [
                'id' => 1,
                'entity_type' => 'Customer',
                'entity_id' => 'CUST001',
                'entity_name' => 'Acme Corporation',
                'action' => 'Create',
                'user_name' => 'John Smith',
                'user_id' => 1,
                'description' => 'Customer record created with initial contact information',
                'old_values' => null,
                'new_values' => json_encode([
                    'name' => 'Acme Corporation',
                    'email' => 'contact@acme.com',
                    'status' => 'active'
                ]),
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'formatted_date' => date('M j, Y g:i A', strtotime('-2 hours'))
            ],
            [
                'id' => 2,
                'entity_type' => 'Order',
                'entity_id' => 'ORD-2024-001',
                'entity_name' => 'Service Installation Order',
                'action' => 'Update',
                'user_name' => 'Sarah Johnson',
                'user_id' => 2,
                'description' => 'Order status updated from pending to scheduled',
                'old_values' => json_encode(['status' => 'pending']),
                'new_values' => json_encode(['status' => 'scheduled', 'scheduled_date' => '2024-01-15']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours')),
                'formatted_date' => date('M j, Y g:i A', strtotime('-4 hours'))
            ],
            [
                'id' => 3,
                'entity_type' => 'User',
                'entity_id' => 'USR003',
                'entity_name' => 'Mike Wilson',
                'action' => 'Update',
                'user_name' => 'Admin User',
                'user_id' => 1,
                'description' => 'User profile updated - changed department and role',
                'old_values' => json_encode(['department' => 'Sales', 'role' => 'Representative']),
                'new_values' => json_encode(['department' => 'Support', 'role' => 'Manager']),
                'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'formatted_date' => date('M j, Y g:i A', strtotime('-6 hours'))
            ],
            [
                'id' => 4,
                'entity_type' => 'Territory',
                'entity_id' => 'TER005',
                'entity_name' => 'North Region',
                'action' => 'Create',
                'user_name' => 'John Smith',
                'user_id' => 1,
                'description' => 'New territory created for northern region coverage',
                'old_values' => null,
                'new_values' => json_encode([
                    'name' => 'North Region',
                    'description' => 'Covers northern districts',
                    'status' => 'active'
                ]),
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'formatted_date' => date('M j, Y g:i A', strtotime('-1 day'))
            ],
            [
                'id' => 5,
                'entity_type' => 'Customer',
                'entity_id' => 'CUST002',
                'entity_name' => 'TechCorp Solutions',
                'action' => 'Delete',
                'user_name' => 'Sarah Johnson',
                'user_id' => 2,
                'description' => 'Customer record deleted due to duplicate entry',
                'old_values' => json_encode([
                    'name' => 'TechCorp Solutions',
                    'email' => 'info@techcorp.com',
                    'status' => 'inactive'
                ]),
                'new_values' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'formatted_date' => date('M j, Y g:i A', strtotime('-2 days'))
            ]
        ];
        
        // Apply filters
        $filteredLogs = $sampleEntityLogs;
        
        // Apply date filter
        if (!empty($dateFilter) && $dateFilter !== 'all') {
            $filteredLogs = array_filter($filteredLogs, function($log) use ($dateFilter) {
                $logDate = new \DateTime($log['created_at']);
                $now = new \DateTime();
                
                switch ($dateFilter) {
                    case 'today':
                        $startDate = new \DateTime('today');
                        return $logDate >= $startDate;
                    case 'yesterday':
                        $startDate = new \DateTime('yesterday');
                        $endDate = new \DateTime('today');
                        return $logDate >= $startDate && $logDate < $endDate;
                    case 'last-7-days':
                        $startDate = new \DateTime('-7 days');
                        return $logDate >= $startDate;
                    case 'last-30-days':
                    default:
                        $startDate = new \DateTime('-30 days');
                        return $logDate >= $startDate;
                    case 'last-90-days':
                        $startDate = new \DateTime('-90 days');
                        return $logDate >= $startDate;
                    case 'last-year':
                        $startDate = new \DateTime('-1 year');
                        return $logDate >= $startDate;
                }
                return true;
            });
        }
        
        // Apply user filter
        if (!empty($userFilter)) {
            $filteredLogs = array_filter($filteredLogs, function($log) use ($userFilter) {
                return $log['user_id'] == $userFilter;
            });
        }
        
        // Apply entity type filter
        if (!empty($entityTypeFilter)) {
            $filteredLogs = array_filter($filteredLogs, function($log) use ($entityTypeFilter) {
                return strtolower($log['entity_type']) === strtolower($entityTypeFilter);
            });
        }
        
        // Apply entity action filter
        if (!empty($entityActionFilter)) {
            $filteredLogs = array_filter($filteredLogs, function($log) use ($entityActionFilter) {
                return strtolower($log['action']) === strtolower($entityActionFilter);
            });
        }
        
        // Re-index array and sort by date (newest first)
        $filteredLogs = array_values($filteredLogs);
        usort($filteredLogs, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $filteredLogs;
    }
    
    private function getEntityTypes()
    {
        return [
            'customer' => 'Customer',
            'order' => 'Order', 
            'user' => 'User',
            'territory' => 'Territory',
            'skill' => 'Skill',
            'profile' => 'Profile'
        ];
    }
    
    private function getEntityActions()
    {
        return [
            'create' => 'Create',
            'update' => 'Update', 
            'delete' => 'Delete',
            'assign' => 'Assign',
            'unassign' => 'Unassign'
        ];
    }
    
    public function piiFields()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return redirect()->to('/login');
        }
        
        // Get filter parameters
        $categoryFilter = $this->request->getVar('category') ?? 'contacts';
        $searchQuery = $this->request->getVar('search') ?? '';
        
        // Get PII fields data based on category
        $piiFields = $this->getPiiFieldsByCategory($categoryFilter, $searchQuery);
        
        $data = [
            'title' => 'PII Fields',
            'activeTab' => 'pii-fields',
            'piiFields' => $piiFields,
            'categories' => $this->getPiiCategories(),
            'filters' => [
                'category' => $categoryFilter,
                'search' => $searchQuery
            ]
        ];
        
        return view('settings/pii_fields', $data);
    }
    
    private function getPiiFieldsByCategory($category, $search = '')
    {
        // Sample PII fields data based on the mockup
        $allFields = [
            'contacts' => [
                [
                    'label' => 'First Name',
                    'data_type' => 'text',
                    'api_name' => 'First_Name',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Last Name',
                    'data_type' => 'text',
                    'api_name' => 'Last_Name',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Email',
                    'data_type' => 'email',
                    'api_name' => 'Email',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Phone',
                    'data_type' => 'phone',
                    'api_name' => 'Phone',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Mobile',
                    'data_type' => 'phone',
                    'api_name' => 'Mobile',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Tax Name',
                    'data_type' => 'text',
                    'api_name' => 'Tax_Name',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Tax Exemption Code',
                    'data_type' => 'text',
                    'api_name' => 'Tax_Exemption_Code',
                    'is_pii' => false,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Tax Authority',
                    'data_type' => 'text',
                    'api_name' => 'Tax_Authority',
                    'is_pii' => false,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Full Name',
                    'data_type' => 'text',
                    'api_name' => 'Full_Name',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Date of Birth',
                    'data_type' => 'date',
                    'api_name' => 'Date_of_Birth',
                    'is_pii' => true,
                    'category' => 'contacts'
                ],
                [
                    'label' => 'Social Security Number',
                    'data_type' => 'text',
                    'api_name' => 'SSN',
                    'is_pii' => true,
                    'category' => 'contacts'
                ]
            ],
            'companies' => [
                [
                    'label' => 'Company Name',
                    'data_type' => 'text',
                    'api_name' => 'Company_Name',
                    'is_pii' => false,
                    'category' => 'companies'
                ],
                [
                    'label' => 'Business Registration Number',
                    'data_type' => 'text',
                    'api_name' => 'Business_Registration_Number',
                    'is_pii' => true,
                    'category' => 'companies'
                ],
                [
                    'label' => 'Contact Person',
                    'data_type' => 'text',
                    'api_name' => 'Contact_Person',
                    'is_pii' => true,
                    'category' => 'companies'
                ],
                [
                    'label' => 'Tax ID Number',
                    'data_type' => 'text',
                    'api_name' => 'Tax_ID_Number',
                    'is_pii' => true,
                    'category' => 'companies'
                ],
                [
                    'label' => 'Industry Type',
                    'data_type' => 'text',
                    'api_name' => 'Industry_Type',
                    'is_pii' => false,
                    'category' => 'companies'
                ],
                [
                    'label' => 'Website URL',
                    'data_type' => 'url',
                    'api_name' => 'Website_URL',
                    'is_pii' => false,
                    'category' => 'companies'
                ]
            ],
            'services_and_parts' => [
                [
                    'label' => 'Service Description',
                    'data_type' => 'text',
                    'api_name' => 'Service_Description',
                    'is_pii' => false,
                    'category' => 'services_and_parts'
                ],
                [
                    'label' => 'Part Serial Number',
                    'data_type' => 'text',
                    'api_name' => 'Part_Serial_Number',
                    'is_pii' => false,
                    'category' => 'services_and_parts'
                ],
                [
                    'label' => 'Service Category',
                    'data_type' => 'text',
                    'api_name' => 'Service_Category',
                    'is_pii' => false,
                    'category' => 'services_and_parts'
                ],
                [
                    'label' => 'Part Manufacturer',
                    'data_type' => 'text',
                    'api_name' => 'Part_Manufacturer',
                    'is_pii' => false,
                    'category' => 'services_and_parts'
                ],
                [
                    'label' => 'Warranty Information',
                    'data_type' => 'text',
                    'api_name' => 'Warranty_Info',
                    'is_pii' => false,
                    'category' => 'services_and_parts'
                ]
            ],
            'requests' => [
                [
                    'label' => 'Request Details',
                    'data_type' => 'text',
                    'api_name' => 'Request_Details',
                    'is_pii' => false,
                    'category' => 'requests'
                ],
                [
                    'label' => 'Customer Notes',
                    'data_type' => 'text',
                    'api_name' => 'Customer_Notes',
                    'is_pii' => true,
                    'category' => 'requests'
                ],
                [
                    'label' => 'Request Priority',
                    'data_type' => 'text',
                    'api_name' => 'Request_Priority',
                    'is_pii' => false,
                    'category' => 'requests'
                ],
                [
                    'label' => 'Customer Feedback',
                    'data_type' => 'text',
                    'api_name' => 'Customer_Feedback',
                    'is_pii' => true,
                    'category' => 'requests'
                ]
            ],
            'estimates' => [
                [
                    'label' => 'Estimate Amount',
                    'data_type' => 'currency',
                    'api_name' => 'Estimate_Amount',
                    'is_pii' => false,
                    'category' => 'estimates'
                ],
                [
                    'label' => 'Estimate Description',
                    'data_type' => 'text',
                    'api_name' => 'Estimate_Description',
                    'is_pii' => false,
                    'category' => 'estimates'
                ],
                [
                    'label' => 'Labor Cost',
                    'data_type' => 'currency',
                    'api_name' => 'Labor_Cost',
                    'is_pii' => false,
                    'category' => 'estimates'
                ]
            ],
            'assets' => [
                [
                    'label' => 'Asset Tag',
                    'data_type' => 'text',
                    'api_name' => 'Asset_Tag',
                    'is_pii' => false,
                    'category' => 'assets'
                ],
                [
                    'label' => 'Asset Location',
                    'data_type' => 'text',
                    'api_name' => 'Asset_Location',
                    'is_pii' => false,
                    'category' => 'assets'
                ],
                [
                    'label' => 'Asset Owner',
                    'data_type' => 'text',
                    'api_name' => 'Asset_Owner',
                    'is_pii' => true,
                    'category' => 'assets'
                ],
                [
                    'label' => 'Purchase Date',
                    'data_type' => 'date',
                    'api_name' => 'Purchase_Date',
                    'is_pii' => false,
                    'category' => 'assets'
                ]
            ],
            'work_orders' => [
                [
                    'label' => 'Work Order Notes',
                    'data_type' => 'text',
                    'api_name' => 'Work_Order_Notes',
                    'is_pii' => true,
                    'category' => 'work_orders'
                ],
                [
                    'label' => 'Customer Signature',
                    'data_type' => 'text',
                    'api_name' => 'Customer_Signature',
                    'is_pii' => true,
                    'category' => 'work_orders'
                ],
                [
                    'label' => 'Work Order Status',
                    'data_type' => 'text',
                    'api_name' => 'Work_Order_Status',
                    'is_pii' => false,
                    'category' => 'work_orders'
                ],
                [
                    'label' => 'Technician Notes',
                    'data_type' => 'text',
                    'api_name' => 'Technician_Notes',
                    'is_pii' => true,
                    'category' => 'work_orders'
                ],
                [
                    'label' => 'Completion Time',
                    'data_type' => 'datetime',
                    'api_name' => 'Completion_Time',
                    'is_pii' => false,
                    'category' => 'work_orders'
                ]
            ]
        ];
        
        $fields = $allFields[$category] ?? [];
        
        // Apply search filter if provided
        if (!empty($search)) {
            $searchTerm = strtolower(trim($search));
            $fields = array_filter($fields, function($field) use ($searchTerm) {
                $label = strtolower($field['label']);
                $apiName = strtolower($field['api_name']);
                $dataType = strtolower($field['data_type']);
                
                // Search in label, API name, and data type
                return stripos($label, $searchTerm) !== false || 
                       stripos($apiName, $searchTerm) !== false ||
                       stripos($dataType, $searchTerm) !== false;
            });
        }
        
        // Re-index array after filtering
        return array_values($fields);
    }
    
    private function getPiiCategories()
    {
        return [
            'contacts' => 'Contacts',
            'companies' => 'Companies',
            'services_and_parts' => 'Services And Parts',
            'requests' => 'Requests',
            'estimates' => 'Estimates',
            'assets' => 'Assets',
            'work_orders' => 'Work Orders'
        ];
    }
    
    // RECORD TEMPLATES MANAGEMENT
    public function recordTemplates()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return redirect()->to('/login');
        }
        
        // Get filter parameters
        $moduleFilter = $this->request->getVar('module') ?? 'All';
        $searchQuery = $this->request->getVar('search') ?? '';
        
        // Get templates with creator information
        $templates = $this->recordTemplateModel->getTemplatesWithCreator($moduleFilter, $searchQuery);
        
        $data = [
            'title' => 'Record Templates',
            'activeTab' => 'record-templates',
            'templates' => $templates,
            'modules' => $this->recordTemplateModel->getAvailableModules(),
            'filters' => [
                'module' => $moduleFilter,
                'search' => $searchQuery
            ]
        ];
        
        return view('settings/record_templates', $data);
    }
    
    public function createRecordTemplate()
    {
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        
        // Get default template fields for the module
        if (isset($data['module'])) {
            $defaultFields = $this->recordTemplateModel->getDefaultTemplateFields($data['module']);
            $data['template_data'] = $defaultFields;
        }
        
        if ($this->recordTemplateModel->createTemplate($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Record template created successfully.',
                'template' => $this->recordTemplateModel->getTemplateWithCreator($this->recordTemplateModel->getInsertID())
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to create record template',
                'errors' => $this->recordTemplateModel->errors()
            ]);
        }
    }
    
    public function getRecordTemplate($id)
    {
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $template = $this->recordTemplateModel->getTemplateWithCreator($id);
        
        if ($template) {
            // Decode template_data JSON if it exists
            if (isset($template['template_data']) && is_string($template['template_data'])) {
                $template['template_data'] = json_decode($template['template_data'], true);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'template' => $template
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Record template not found'
            ])->setStatusCode(404);
        }
    }
    
    public function updateRecordTemplate($id)
    {
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token and id field (id is passed in URL)
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        unset($data['id']);
        
        if ($this->recordTemplateModel->updateTemplate($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Record template updated successfully.',
                'template' => $this->recordTemplateModel->getTemplateWithCreator($id)
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update record template',
                'errors' => $this->recordTemplateModel->errors()
            ]);
        }
    }
    
    public function deleteRecordTemplate($id)
    {
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        if ($this->recordTemplateModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Record template deleted successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete record template'
            ]);
        }
    }
    
    public function duplicateRecordTemplate($id)
    {
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        $newName = $data['name'] ?? null;
        
        if ($this->recordTemplateModel->duplicateTemplate($id, $newName)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Record template duplicated successfully.',
                'template' => $this->recordTemplateModel->getTemplateWithCreator($this->recordTemplateModel->getInsertID())
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to duplicate record template'
            ]);
        }
    }
    
    // ACCOUNT REGISTRY MANAGEMENT
    public function accountRegistry()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            return redirect()->to('/login');
        }
        
        // Initialize account sequences if not already done
        $this->accountSequenceModel->initializeDefaults();
        
        // Get filter parameters
        $tab = $this->request->getVar('tab') ?? 'clients';
        $status = $this->request->getVar('status') ?? 'active';
        $search = $this->request->getVar('search') ?? '';
        $serviceType = $this->request->getVar('service_type') ?? 'all';
        
        // Get data based on active tab
        $clients = [];
        $services = [];
        $sequences = [];
        $clientStats = [];
        $serviceStats = [];
        
        if ($tab === 'clients') {
            $clients = $this->clientModel->getClients($status, $search);
            $clientStats = $this->clientModel->getClientStats();
        } elseif ($tab === 'services') {
            $services = $this->serviceRegistryModel->getServicesWithClients($status, $search, $serviceType);
            $serviceStats = $this->serviceRegistryModel->getServiceStats();
        } elseif ($tab === 'sequences') {
            $sequences = $this->accountSequenceModel->getSequencesWithStats();
        }
        
        $data = [
            'title' => 'Account Registry',
            'activeTab' => 'account-registry',
            'tab' => $tab,
            'clients' => $clients,
            'services' => $services,
            'sequences' => $sequences,
            'serviceTypes' => $this->serviceRegistryModel->getServiceTypes(),
            'clientStats' => $clientStats,
            'serviceStats' => $serviceStats,
            'filters' => [
                'status' => $status,
                'search' => $search,
                'service_type' => $serviceType
            ]
        ];
        
        return view('settings/account_registry', $data);
    }
    
    // CLIENT MANAGEMENT
    public function addClient()
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        
        // Set created_by from session
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id'])) {
            $data['created_by'] = $currentUser['id'];
        }
        
        if ($this->clientModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Client added successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add client',
                'errors' => $this->clientModel->errors()
            ]);
        }
    }
    
    public function getClient($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $client = $this->clientModel->getClientWithServices($id);
        
        if ($client) {
            return $this->response->setJSON([
                'success' => true,
                'client' => $client
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client not found'
            ])->setStatusCode(404);
        }
    }
    
    public function updateClient($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token and id field
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        unset($data['id']);
        
        if ($this->clientModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Client updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update client',
                'errors' => $this->clientModel->errors()
            ]);
        }
    }
    
    public function deleteClient($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        if ($this->clientModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Client deleted successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete client'
            ]);
        }
    }
    
    // SERVICE REGISTRY MANAGEMENT
    public function addService()
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        
        // Get client info for abbreviation
        $client = $this->clientModel->find($data['client_id']);
        if (!$client) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Client not found'
            ])->setStatusCode(404);
        }
        
        // Generate client abbreviation and account code
        $clientAbbreviation = $this->clientModel->generateClientAbbreviation($client['client_name']);
        $accountCode = $this->serviceRegistryModel->generateAccountCode($data['service_type'], $clientAbbreviation);
        
        $data['client_abbreviation'] = $clientAbbreviation;
        $data['account_code'] = $accountCode;
        $data['group_id'] = str_pad($this->accountSequenceModel->getNextSequence($data['service_type']), 3, '0', STR_PAD_LEFT);
        
        // Set created_by from session
        $currentUser = session()->get('user');
        if ($currentUser && isset($currentUser['id'])) {
            $data['created_by'] = $currentUser['id'];
        }
        
        if ($this->serviceRegistryModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service added successfully.',
                'account_code' => $accountCode
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add service',
                'errors' => $this->serviceRegistryModel->errors()
            ]);
        }
    }
    
    public function getService($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $service = $this->serviceRegistryModel->getServiceWithClient($id);
        
        if ($service) {
            return $this->response->setJSON([
                'success' => true,
                'service' => $service
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service not found'
            ])->setStatusCode(404);
        }
    }
    
    public function updateService($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token and id field
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        unset($data['id']);
        
        if ($this->serviceRegistryModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update service',
                'errors' => $this->serviceRegistryModel->errors()
            ]);
        }
    }
    
    public function deleteService($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        if ($this->serviceRegistryModel->deleteService($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service deleted successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete service'
            ]);
        }
    }
    
    // SEQUENCE MANAGEMENT
    public function updateSequence($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $data = $this->request->getPost();
        
        // Remove CSRF token and id field
        unset($data['csrf_test_name']);
        unset($data['csrf_token']);
        unset($data['csrf_ghash']);
        unset($data['id']);
        
        if ($this->accountSequenceModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Sequence updated successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update sequence',
                'errors' => $this->accountSequenceModel->errors()
            ]);
        }
    }
    
    public function getSequence($id)
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $sequence = $this->accountSequenceModel->find($id);
        
        if ($sequence) {
            return $this->response->setJSON([
                'success' => true,
                'sequence' => $sequence
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sequence not found'
            ])->setStatusCode(404);
        }
    }
    
    public function getClientsForDropdown()
    {
        header('Content-Type: application/json');
        
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        $clients = $this->clientModel->where('status', 'active')
                                    ->orderBy('client_name', 'ASC')
                                    ->findAll();
        
        return $this->response->setJSON([
            'success' => true,
            'clients' => $clients
        ]);
    }
}
