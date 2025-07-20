<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkOrderModel extends Model
{
    protected $table = 'work_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'work_order_number',
        'summary',
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
        $prefix = 'WO-';
        $year = date('Y');
        $lastWorkOrder = $this->selectMax('work_order_number')
                           ->where('work_order_number LIKE', $prefix . $year . '%')
                           ->first();
        
        if ($lastWorkOrder && $lastWorkOrder['work_order_number']) {
            $lastNumber = intval(substr($lastWorkOrder['work_order_number'], -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get all work orders with company and contact details
     */
    public function getWorkOrders($status = null, $searchTerm = null, $companyId = null)
    {
        $builder = $this->db->table($this->table . ' w')
                           ->select('w.*, c.client_name as company_name, ct.first_name, ct.last_name, ct.email as contact_email, a.asset_name, COALESCE(u.first_name || " " || u.last_name, u.username) as created_by_name')
                           ->join('clients c', 'c.id = w.company_id', 'left')
                           ->join('contacts ct', 'ct.id = w.contact_id', 'left')
                           ->join('assets a', 'a.id = w.asset_id', 'left')
                           ->join('users u', 'u.id = w.created_by', 'left')
                           ->where('w.deleted_at IS NULL')
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
                       ->select('w.*, c.client_name as company_name, c.client_code, ct.first_name, ct.last_name, ct.email as contact_email, ct.phone as contact_phone, a.asset_name, a.model as asset_model, COALESCE(u.first_name || " " || u.last_name, u.username) as created_by_name')
                       ->join('clients c', 'c.id = w.company_id', 'left')
                       ->join('contacts ct', 'ct.id = w.contact_id', 'left')
                       ->join('assets a', 'a.id = w.asset_id', 'left')
                       ->join('users u', 'u.id = w.created_by', 'left')
                       ->where('w.id', $id)
                       ->where('w.deleted_at IS NULL')
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
                       ->where('w.deleted_at IS NULL')
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
}