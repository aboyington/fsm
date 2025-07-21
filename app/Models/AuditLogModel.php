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
        'entity_type',
        'entity_id',
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
    public function logEvent($eventType, $title, $description = null, $targetUserId = null, $oldValue = null, $newValue = null, $module = 'users', $entityType = null, $entityId = null)
    {
        $request = \Config\Services::request();
        $session = session();
        
        $data = [
            'user_id' => $session->get('user')['id'] ?? null,
            'target_user_id' => $targetUserId,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'event_type' => $eventType,
            'module' => $module,
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
        $this->applyDateFilter($builder, $filter);
        
        // Order by newest first
        $builder->orderBy('audit_logs.created_at', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        // Format the results
        return $this->formatTimelineResults($results);
    }
    
    /**
     * Get timeline for a specific request
     */
    public function getRequestTimeline($requestId, $filter = 'all')
    {
        $builder = $this->builder();
        
        // Base query - get events where request is the entity
        $builder->select('audit_logs.*, users.first_name, users.last_name')
                ->join('users', 'users.id = audit_logs.user_id', 'left')
                ->where('audit_logs.entity_type', 'request')
                ->where('audit_logs.entity_id', $requestId);
        
        // Apply date filter
        $this->applyDateFilter($builder, $filter);
        
        // Order by newest first
        $builder->orderBy('audit_logs.created_at', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        // Format the results
        return $this->formatTimelineResults($results);
    }
    
    /**
     * Get timeline for a specific work order
     */
    public function getWorkOrderTimeline($workOrderId, $filter = 'all')
    {
        $builder = $this->builder();
        
        // Base query - get events where work order is the entity
        $builder->select('audit_logs.*, users.first_name, users.last_name')
                ->join('users', 'users.id = audit_logs.user_id', 'left')
                ->where('audit_logs.entity_type', 'work_order')
                ->where('audit_logs.entity_id', $workOrderId);
        
        // Apply date filter
        $this->applyDateFilter($builder, $filter);
        
        // Order by newest first
        $builder->orderBy('audit_logs.created_at', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        // Format the results
        return $this->formatTimelineResults($results);
    }

    /**
     * Apply date filter to query builder
     */
    private function applyDateFilter($builder, $filter)
    {
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
    }
    
    /**
     * Format timeline results for display
     */
    private function formatTimelineResults($results)
    {
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
            
            // Format date for display
            if (!empty($result['created_at'])) {
                $result['formatted_date'] = date('M j, Y g:i A', strtotime($result['created_at']));
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
    
    // Request-specific events
    const EVENT_REQUEST_CREATED = 'request_created';
    const EVENT_REQUEST_UPDATED = 'request_updated';
    const EVENT_REQUEST_DELETED = 'request_deleted';
    const EVENT_REQUEST_STATUS_CHANGED = 'request_status_changed';
    const EVENT_REQUEST_PRIORITY_CHANGED = 'request_priority_changed';
    const EVENT_REQUEST_ASSIGNED = 'request_assigned';
    const EVENT_REQUEST_CONVERTED = 'request_converted';
    const EVENT_REQUEST_NOTE_ADDED = 'request_note_added';
    const EVENT_REQUEST_ATTACHMENT_ADDED = 'request_attachment_added';
    
    // Work Order-specific events
    const EVENT_WORK_ORDER_CREATED = 'work_order_created';
    const EVENT_WORK_ORDER_UPDATED = 'work_order_updated';
    const EVENT_WORK_ORDER_DELETED = 'work_order_deleted';
    const EVENT_WORK_ORDER_STATUS_CHANGED = 'work_order_status_changed';
    const EVENT_WORK_ORDER_PRIORITY_CHANGED = 'work_order_priority_changed';
    const EVENT_WORK_ORDER_ASSIGNED = 'work_order_assigned';
    const EVENT_WORK_ORDER_COMPLETED = 'work_order_completed';
    const EVENT_WORK_ORDER_CANCELLED = 'work_order_cancelled';
    const EVENT_WORK_ORDER_NOTE_ADDED = 'work_order_note_added';
    const EVENT_WORK_ORDER_ATTACHMENT_ADDED = 'work_order_attachment_added';
    const EVENT_WORK_ORDER_SERVICE_APPOINTMENT_SCHEDULED = 'work_order_service_appointment_scheduled';
    const EVENT_WORK_ORDER_INVOICE_GENERATED = 'work_order_invoice_generated';
}
