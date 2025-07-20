<?php

namespace App\Models;

use CodeIgniter\Model;

class EstimateModel extends Model
{
    protected $table = 'estimates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'estimate_number',
        'request_id',
        'summary',
        'expiry_date',
        'company_id',
        'contact_id',
        'email',
        'phone',
        'mobile',
        'asset_id',
        'service_address',
        'billing_address',
        'sub_total',
        'tax_amount',
        'discount',
        'adjustment',
        'grand_total',
        'terms_and_conditions',
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
        'expiry_date' => 'permit_empty|valid_date',
        'company_id' => 'permit_empty|integer',
        'contact_id' => 'permit_empty|integer',
        'email' => 'permit_empty|valid_email',
        'phone' => 'permit_empty|max_length[20]',
        'mobile' => 'permit_empty|max_length[20]',
        'asset_id' => 'permit_empty|integer',
        'service_address' => 'permit_empty|max_length[500]',
        'billing_address' => 'permit_empty|max_length[500]',
        'sub_total' => 'permit_empty|decimal',
        'tax_amount' => 'permit_empty|decimal',
        'discount' => 'permit_empty|decimal',
        'adjustment' => 'permit_empty|decimal',
        'grand_total' => 'permit_empty|decimal',
        'terms_and_conditions' => 'permit_empty|max_length[1000]',
        'status' => 'permit_empty|in_list[draft,sent,accepted,rejected,cancelled]',
        'created_by' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'summary' => [
            'required' => 'Estimate summary is required',
            'min_length' => 'Estimate summary must be at least 3 characters long',
            'max_length' => 'Estimate summary cannot exceed 255 characters'
        ],
        'expiry_date' => [
            'valid_date' => 'Please enter a valid expiry date'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateEstimateNumber'];
    protected $beforeUpdate = [];
    protected $afterInsert = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Generate estimate number before insert
     */
    protected function generateEstimateNumber(array $data)
    {
        if (!isset($data['data']['estimate_number']) || empty($data['data']['estimate_number'])) {
            $data['data']['estimate_number'] = $this->getNextEstimateNumber();
        }
        return $data;
    }

    /**
     * Get next estimate number
     */
    public function getNextEstimateNumber()
    {
        $prefix = 'EST-';
        $year = date('Y');
        $lastEstimate = $this->selectMax('estimate_number')
                           ->where('estimate_number LIKE', $prefix . $year . '%')
                           ->first();
        
        if ($lastEstimate && $lastEstimate['estimate_number']) {
            $lastNumber = intval(substr($lastEstimate['estimate_number'], -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get all estimates with company and contact details
     */
    public function getEstimates($status = null, $searchTerm = null, $companyId = null)
    {
        $builder = $this->db->table($this->table . ' e')
                           ->select('e.*, c.client_name as company_name, ct.first_name, ct.last_name, ct.email as contact_email, a.asset_name, u.username as created_by_name')
                           ->join('clients c', 'c.id = e.company_id', 'left')
                           ->join('contacts ct', 'ct.id = e.contact_id', 'left')
                           ->join('assets a', 'a.id = e.asset_id', 'left')
                           ->join('users u', 'u.id = e.created_by', 'left')
                           ->where('e.deleted_at IS NULL')
                           ->orderBy('e.created_at', 'DESC');
        
        if ($status) {
            $builder->where('e.status', $status);
        }
        
        if ($searchTerm) {
            $builder->groupStart()
                    ->like('e.summary', $searchTerm)
                    ->orLike('e.estimate_number', $searchTerm)
                    ->orLike('c.client_name', $searchTerm)
                    ->orLike('ct.first_name', $searchTerm)
                    ->orLike('ct.last_name', $searchTerm)
                    ->groupEnd();
        }
        
        if ($companyId) {
            $builder->where('e.company_id', $companyId);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get estimate with full details
     */
    public function getEstimateWithDetails($id)
    {
        return $this->db->table($this->table . ' e')
                       ->select('e.*, c.client_name as company_name, c.client_code, ct.first_name, ct.last_name, ct.email as contact_email, ct.phone as contact_phone, a.asset_name, a.model as asset_model, u.username as created_by_name')
                       ->join('clients c', 'c.id = e.company_id', 'left')
                       ->join('contacts ct', 'ct.id = e.contact_id', 'left')
                       ->join('assets a', 'a.id = e.asset_id', 'left')
                       ->join('users u', 'u.id = e.created_by', 'left')
                       ->where('e.id', $id)
                       ->where('e.deleted_at IS NULL')
                       ->get()
                       ->getRowArray();
    }

    /**
     * Get estimates by company
     */
    public function getEstimatesByCompany($companyId)
    {
        return $this->db->table($this->table . ' e')
                       ->select('e.*, ct.first_name, ct.last_name, a.asset_name')
                       ->join('contacts ct', 'ct.id = e.contact_id', 'left')
                       ->join('assets a', 'a.id = e.asset_id', 'left')
                       ->where('e.company_id', $companyId)
                       ->where('e.deleted_at IS NULL')
                       ->orderBy('e.created_at', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get estimates by request ID
     */
    public function getEstimatesByRequest($requestId)
    {
        return $this->db->table($this->table . ' e')
                       ->select('e.*, c.client_name as company_name, ct.first_name, ct.last_name, a.asset_name, u.username as created_by_name')
                       ->join('clients c', 'c.id = e.company_id', 'left')
                       ->join('contacts ct', 'ct.id = e.contact_id', 'left')
                       ->join('assets a', 'a.id = e.asset_id', 'left')
                       ->join('users u', 'u.id = e.created_by', 'left')
                       ->where('e.request_id', $requestId)
                       ->where('e.deleted_at IS NULL')
                       ->orderBy('e.created_at', 'DESC')
                       ->get()
                       ->getResultArray();
    }

    /**
     * Get estimate statistics
     */
    public function getEstimateStats()
    {
        $total = $this->countAllResults();
        $draft = $this->where('status', 'draft')->countAllResults();
        $sent = $this->where('status', 'sent')->countAllResults();
        $accepted = $this->where('status', 'accepted')->countAllResults();
        $rejected = $this->where('status', 'rejected')->countAllResults();
        
        return [
            'total' => $total,
            'draft' => $draft,
            'sent' => $sent,
            'accepted' => $accepted,
            'rejected' => $rejected
        ];
    }
}