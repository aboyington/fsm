<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'target_user_id',
        'event_type',
        'module',
        'title',
        'description',
        'old_value',
        'new_value',
        'ip_address',
        'user_agent'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'event_type' => 'required|max_length[50]',
        'title' => 'required|max_length[255]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Log an audit event
     */
    public function logEvent($eventType, $title, $description = null, $targetUserId = null, $oldValue = null, $newValue = null)
    {
        $request = \Config\Services::request();
        $session = session();
        
        $data = [
            'user_id' => $session->get('user')['id'] ?? null,
            'target_user_id' => $targetUserId,
            'event_type' => $eventType,
            'module' => 'users', // Can be made dynamic based on context
            'title' => $title,
            'description' => $description,
            'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
            'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString()
        ];
        
        return $this->insert($data);
    }

    /**
     * Get timeline for a specific user
     */
    public function getUserTimeline($userId, $filter = 'all')
    {
        $builder = $this->builder();
        
        // Base query - get events where user is the target
        $builder->select('audit_logs.*, users.first_name, users.last_name')
                ->join('users', 'users.id = audit_logs.user_id', 'left')
                ->where('audit_logs.target_user_id', $userId);
        
        // Apply date filter
        $now = new \DateTime();
        
        switch ($filter) {
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
                
            case 'last_week':
                $startDate = new \DateTime('-1 week');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
                
            case 'last_month':
                $startDate = new \DateTime('-1 month');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
                
            case 'last_year':
                $startDate = new \DateTime('-1 year');
                $builder->where('audit_logs.created_at >=', $startDate->format('Y-m-d H:i:s'));
                break;
        }
        
        // Order by newest first
        $builder->orderBy('audit_logs.created_at', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        // Format the results
        foreach ($results as &$result) {
            // Create user name from first and last name
            $result['user_name'] = trim(($result['first_name'] ?? '') . ' ' . ($result['last_name'] ?? ''));
            if (empty($result['user_name'])) {
                $result['user_name'] = 'System';
            }
            
            // Parse JSON values if needed
            if (!empty($result['old_value']) && $this->isJson($result['old_value'])) {
                $result['old_value'] = json_decode($result['old_value'], true);
            }
            if (!empty($result['new_value']) && $this->isJson($result['new_value'])) {
                $result['new_value'] = json_decode($result['new_value'], true);
            }
        }
        
        return $results;
    }

    /**
     * Check if a string is valid JSON
     */
    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * Common event types
     */
    const EVENT_USER_CREATED = 'user_created';
    const EVENT_USER_UPDATED = 'user_updated';
    const EVENT_USER_DELETED = 'user_deleted';
    const EVENT_STATUS_CHANGED = 'status_changed';
    const EVENT_ROLE_CHANGED = 'role_changed';
    const EVENT_PASSWORD_CHANGED = 'password_changed';
    const EVENT_LOGIN = 'login';
    const EVENT_LOGOUT = 'logout';
    const EVENT_SERVICE_ASSIGNED = 'service_assigned';
    const EVENT_SERVICE_COMPLETED = 'service_completed';
}
