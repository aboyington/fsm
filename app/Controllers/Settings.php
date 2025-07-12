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
}
