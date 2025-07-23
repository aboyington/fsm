<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkOrderModel extends Model
{
    protected $table = 'work_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'work_order_number',
        'summary',
        'description',
        'priority',
        'type',
        'due_date',
        'company_id',
        'contact_id',
        'email',
        'phone',
        'mobile',
        'asset_id',
        'service_address',
        'billing_address',
        'preferred_date_1',
        'preferred_date_2',
        'preferred_time',
        'preference_note',
        'sub_total',
        'tax_amount',
        'discount',
        'adjustment',
        'grand_total',
        'status',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'summary' => 'required|min_length[3]|max_length[255]',
        'description' => 'permit_empty|max_length[2000]',
        'priority' => 'permit_empty|in_list[none,low,medium,critical,high]',
        'type' => 'permit_empty|in_list[none,corrective,preventive,service,site_survey,inspection,installation,maintenance,emergency,scheduled_maintenance,standard]',
        'due_date' => 'permit_empty|valid_date',
        'company_id' => 'permit_empty|integer',
        'contact_id' => 'permit_empty|integer',
        'email' => 'permit_empty|valid_email',
        'phone' => 'permit_empty|max_length[20]',
        'mobile' => 'permit_empty|max_length[20]',
        'asset_id' => 'permit_empty|integer',
        'service_address' => 'permit_empty|max_length[500]',
        'billing_address' => 'permit_empty|max_length[500]',
        'preferred_date_1' => 'permit_empty|valid_date',
        'preferred_date_2' => 'permit_empty|valid_date',
        'preferred_time' => 'permit_empty|in_list[-none-,any,morning,afternoon,evening]',
        'preference_note' => 'permit_empty|max_length[1000]',
        'sub_total' => 'permit_empty|decimal',
        'tax_amount' => 'permit_empty|decimal',
        'discount' => 'permit_empty|decimal',
        'adjustment' => 'permit_empty|decimal',
        'grand_total' => 'permit_empty|decimal',
        'status' => 'permit_empty|in_list[pending,in_progress,completed,cancelled]',
        'created_by' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'summary' => [
            'required' => 'Work order summary is required',
            'min_length' => 'Work order summary must be at least 3 characters long',
            'max_length' => 'Work order summary cannot exceed 255 characters'
        ],
        'priority' => [
            'in_list' => 'Priority must be none, low, medium, critical, or high'
        ],
        'type' => [
            'in_list' => 'Type must be none, corrective, preventive, service, site_survey, inspection, installation, maintenance, emergency, scheduled_maintenance, or standard'
        ],
        'due_date' => [
            'valid_date' => 'Please enter a valid due date'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateWorkOrderNumber'];
    protected $beforeUpdate = [];
    protected $afterInsert = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Generate work order number before insert
     */
    protected function generateWorkOrderNumber(array $data)
    {
        if (!isset($data['data']['work_order_number']) || empty($data['data']['work_order_number'])) {
            $data['data']['work_order_number'] = $this->getNextWorkOrderNumber();
        }
        return $data;
    }

    /**
     * Get next work order number
     */
    public function getNextWorkOrderNumber()
    {
        $prefix = 'WRK-';
        $year = substr(date('Y'), -3); // Use last 3 digits of year (e.g., 025 for 2025)
        $yearPrefix = $prefix . $year . '-';
        
        // Get all existing work order numbers for this year
        $query = "SELECT work_order_number FROM {$this->table} 
                  WHERE work_order_number LIKE ? 
                  ORDER BY work_order_number DESC 
                  LIMIT 1";
        
        $result = $this->db->query($query, [$yearPrefix . '%']);
        $row = $result->getRowArray();
        
        if ($row && $row['work_order_number']) {
            // Extract the numeric part and increment
            $number = intval(substr($row['work_order_number'], -4));
            $newNumber = $number + 1;
        } else {
            // Start with 1 if no previous work orders found
            $newNumber = 1;
        }
        
        // Keep checking and incrementing until we find an unused number
        do {
            $candidateNumber = $yearPrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            
            // Check if this work order number already exists
            $checkQuery = "SELECT id FROM {$this->table} WHERE work_order_number = ?";
            $checkResult = $this->db->query($checkQuery, [$candidateNumber]);
            
            if ($checkResult->getNumRows() === 0) {
                // Found an unused number
                return $candidateNumber;
            }
            
            // This number exists, try the next one
            $newNumber++;
        } while (true);
    }

    /**
     * Get all work orders with company and contact details
     */
    public function getWorkOrders($status = null, $searchTerm = null, $companyId = null)
    {
        $builder = $this->db->table($this->table . ' w')
                           ->select('w.*, c.client_name as company_name, ct.first_name, ct.last_name, ct.email as contact_email, a.asset_name, COALESCE(CONCAT(u.first_name, " ", u.last_name), u.username) as created_by_name')
                           ->join('clients c', 'c.id = w.company_id', 'left')
                           ->join('contacts ct', 'ct.id = w.contact_id', 'left')
                           ->join('assets a', 'a.id = w.asset_id', 'left')
                           ->join('users u', 'u.id = w.created_by', 'left')
                           ->orderBy('w.created_at', 'DESC');
        
        if ($status) {
            $builder->where('w.status', $status);
        }
        
        if ($searchTerm) {
            $builder->groupStart()
                    ->like('w.summary', $searchTerm)
                    ->orLike('w.work_order_number', $searchTerm)
                    ->orLike('c.client_name', $searchTerm)
                    ->orLike('ct.first_name', $searchTerm)
                    ->orLike('ct.last_name', $searchTerm)
                    ->groupEnd();
        }
        
        if ($companyId) {
            $builder->where('w.company_id', $companyId);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get work order with full details
     */
    public function getWorkOrderWithDetails($id)
    {
        return $this->db->table($this->table . ' w')
                       ->select('w.*, c.client_name as company_name, ct.first_name, ct.last_name, ct.email as contact_email, ct.phone as contact_phone, a.asset_name, COALESCE(CONCAT(u.first_name, " ", u.last_name), u.username) as created_by_name')
                       ->join('clients c', 'c.id = w.company_id', 'left')
                       ->join('contacts ct', 'ct.id = w.contact_id', 'left')
                       ->join('assets a', 'a.id = w.asset_id', 'left')
                       ->join('users u', 'u.id = w.created_by', 'left')
                       ->where('w.id', $id)
                       ->get()
                       ->getRowArray();
    }

    /**
     * Get work orders by request ID (placeholder - request_id field doesn't exist yet)
     */
    public function getWorkOrdersByRequest($requestId)
    {
        // Note: request_id field doesn't exist in the current schema
        // This method is a placeholder for future functionality
        return [];
    }

    /**
     * Get work orders by company
     */
    public function getWorkOrdersByCompany($companyId)
    {
        return $this->db->table($this->table . ' w')
                       ->select('w.*, ct.first_name, ct.last_name, a.asset_name')
                       ->join('contacts ct', 'ct.id = w.contact_id', 'left')
                       ->join('assets a', 'a.id = w.asset_id', 'left')
                       ->where('w.company_id', $companyId)
                       ->orderBy('w.created_at', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get work order statistics
     */
    public function getWorkOrderStats()
    {
        $total = $this->countAllResults();
        $pending = $this->where('status', 'pending')->countAllResults();
        $inProgress = $this->where('status', 'in_progress')->countAllResults();
        $completed = $this->where('status', 'completed')->countAllResults();
        $cancelled = $this->where('status', 'cancelled')->countAllResults();
        
        return [
            'total' => $total,
            'pending' => $pending,
            'in_progress' => $inProgress,
            'completed' => $completed,
            'cancelled' => $cancelled
        ];
    }
    
    /**
     * Get services for a work order
     */
    public function getWorkOrderServices($workOrderId)
    {
        $services = $this->db->table('work_order_items woi')
                             ->select('woi.*, ps.name as service_name, ps.price as service_rate')
                             ->join('product_skus ps', 'ps.id = woi.service_id', 'left')
                             ->where('woi.work_order_id', $workOrderId)
                             ->where('woi.item_type', 'service')
                             ->orderBy('woi.id')
                             ->get()
                             ->getResultArray();
        
        // Generate unique line item names for services if not already set
        $serviceCounter = 1;
        foreach ($services as &$service) {
            if (empty($service['line_item_name'])) {
                $service['line_item_name'] = 'SVC-' . $serviceCounter;
                $service['service_line_item_name'] = 'SVC-' . $serviceCounter; // For backward compatibility
            } else {
                $service['service_line_item_name'] = $service['line_item_name'];
            }
            $serviceCounter++;
        }
        
        return $services;
    }
    
    /**
     * Get parts for a work order
     */
    public function getWorkOrderParts($workOrderId)
    {
        $parts = $this->db->table('work_order_items woi')
                          ->select('woi.*, ps.name as part_name, ps.price as part_rate')
                          ->join('product_skus ps', 'ps.id = woi.service_id', 'left')
                          ->where('woi.work_order_id', $workOrderId)
                          ->where('woi.item_type', 'part')
                          ->orderBy('woi.id')
                          ->get()
                          ->getResultArray();
        
        // Generate unique line item names for parts if not already set
        $partCounter = 1;
        foreach ($parts as &$part) {
            if (empty($part['line_item_name'])) {
                $part['line_item_name'] = 'PRT-' . $partCounter;
                $part['part_line_item_name'] = 'PRT-' . $partCounter; // For backward compatibility
            } else {
                $part['part_line_item_name'] = $part['line_item_name'];
            }
            $partCounter++;
        }
        
        return $parts;
    }
    
    /**
     * Save work order services
     */
    public function saveWorkOrderServices($workOrderId, $services)
    {
        // First, delete existing services for this work order
        $this->db->table('work_order_items')
                 ->where('work_order_id', $workOrderId)
                 ->where('item_type', 'service')
                 ->delete();
        
        // Insert new services
        if (!empty($services)) {
            $serviceCounter = 1;
            foreach ($services as $service) {
                if (!empty($service['service_id'])) {
                    // Generate unique line item name
                    $lineItemName = $service['line_item_name'] ?? 'SVC-' . $serviceCounter;
                    
                    $data = [
                        'work_order_id' => $workOrderId,
                        'item_type' => 'service',
                        'service_id' => $service['service_id'],
                        'item_name' => $service['item_name'] ?? '', // Service/Product name
                        'line_item_name' => $lineItemName, // Unique line item identifier
                        'quantity' => $service['quantity'] ?? 1,
                        'rate' => $service['rate'] ?? 0,
                        'amount' => $service['amount'] ?? ($service['quantity'] * $service['rate']),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('work_order_items')->insert($data);
                    $serviceCounter++;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Save work order parts
     */
    public function saveWorkOrderParts($workOrderId, $parts)
    {
        // First, delete existing parts for this work order
        $this->db->table('work_order_items')
                 ->where('work_order_id', $workOrderId)
                 ->where('item_type', 'part')
                 ->delete();
        
        // Insert new parts
        if (!empty($parts)) {
            $partCounter = 1;
            foreach ($parts as $part) {
                if (!empty($part['part_id'])) {
                    // Generate unique line item name
                    $lineItemName = $part['line_item_name'] ?? 'PRT-' . $partCounter;
                    
                    $data = [
                        'work_order_id' => $workOrderId,
                        'item_type' => 'part',
                        'service_id' => $part['part_id'], // Note: using service_id column for both services and parts
                        'item_name' => $part['item_name'] ?? '', // Part/Product name
                        'line_item_name' => $lineItemName, // Unique line item identifier
                        'quantity' => $part['quantity'] ?? 1,
                        'rate' => $part['rate'] ?? 0,
                        'amount' => $part['amount'] ?? ($part['quantity'] * $part['rate']),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('work_order_items')->insert($data);
                    $partCounter++;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Get skills for a work order
     */
    public function getWorkOrderSkills($workOrderId)
    {
        return $this->db->table('work_order_skills wos')
                       ->select('wos.*, s.name as skill_name')
                       ->join('skills s', 's.id = wos.skill_id', 'left')
                       ->where('wos.work_order_id', $workOrderId)
                       ->orderBy('wos.id')
                       ->get()
                       ->getResultArray();
    }
    
    /**
     * Save work order skills
     */
    public function saveWorkOrderSkills($workOrderId, $skills)
    {
        // First, delete existing skills for this work order
        $this->db->table('work_order_skills')
                 ->where('work_order_id', $workOrderId)
                 ->delete();
        
        // Insert new skills
        if (!empty($skills)) {
            foreach ($skills as $skill) {
                if (!empty($skill['skill_id'])) {
                    $data = [
                        'work_order_id' => $workOrderId,
                        'skill_id' => $skill['skill_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db->table('work_order_skills')->insert($data);
                }
            }
        }
        
        return true;
    }
}
