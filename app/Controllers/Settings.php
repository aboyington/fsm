<?php

namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\FiscalYearModel;
use App\Models\BusinessHoursModel;
use App\Models\UserModel;
use App\Models\AuditLogModel;

class Settings extends BaseController
{
    protected $organizationModel;
    protected $fiscalYearModel;
    protected $businessHoursModel;
    protected $userModel;
    protected $auditLogModel;
    
    public function __construct()
    {
        $this->organizationModel = new OrganizationModel();
        $this->fiscalYearModel = new FiscalYearModel();
        $this->businessHoursModel = new BusinessHoursModel();
        $this->userModel = new UserModel();
        $this->auditLogModel = new AuditLogModel();
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

public function addUser()
{
    if ($this->request->getMethod() === 'post') {
        $userData = $this->request->getPost();
        
        // Remove CSRF and confirm password fields
        unset($userData['csrf_test_name']);
        unset($userData['csrf_token']);
        unset($userData['confirm_password']);
        
        // Generate username from email if not provided
        if (!isset($userData['username']) || empty($userData['username'])) {
            $userData['username'] = explode('@', $userData['email'])[0];
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
        
        if ($this->userModel->save($userData)) {
            return $this->response->setJSON(['success' => true, 'message' => 'User added successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add user', 'errors' => $this->userModel->errors()]);
        }
    }
    
    return redirect()->to('/settings/users');
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
        $data = [
            'title' => 'Territories',
            'activeTab' => 'territories'
        ];
        
        return view('settings/territories', $data);
    }

    public function skills()
    {
        $data = [
            'title' => 'Skills',
            'activeTab' => 'skills'
        ];
        
        return view('settings/skills', $data);
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
}
