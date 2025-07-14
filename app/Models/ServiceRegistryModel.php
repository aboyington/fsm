<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceRegistryModel extends Model
{
    protected $table = 'service_registry';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'client_id', 'service_type', 'service_name', 'account_code', 
        'client_abbreviation', 'group_id', 'status', 'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'client_id' => 'required|integer|greater_than[0]',
        'service_type' => 'required|in_list[ALA,CAM,ITS,SUB]',
        'service_name' => 'required|max_length[255]',
        'account_code' => 'required|max_length[50]|is_unique[service_registry.account_code,id,{id}]',
        'client_abbreviation' => 'required|max_length[10]',
        'group_id' => 'required|max_length[10]',
        'status' => 'permit_empty|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'client_id' => [
            'required' => 'Client is required.',
            'integer' => 'Invalid client selection.',
            'greater_than' => 'Invalid client selection.'
        ],
        'service_type' => [
            'required' => 'Service type is required.',
            'in_list' => 'Invalid service type selected.'
        ],
        'service_name' => [
            'required' => 'Service name is required.'
        ],
        'account_code' => [
            'required' => 'Account code is required.',
            'is_unique' => 'This account code already exists.'
        ],
        'client_abbreviation' => [
            'required' => 'Client abbreviation is required.'
        ],
        'group_id' => [
            'required' => 'Group ID is required.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['beforeUpdate'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function beforeInsert(array $data)
    {
        $data = $this->setCreatedBy($data);
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data = $this->setCreatedBy($data);
        return $data;
    }

    protected function setCreatedBy(array $data)
    {
        if (!isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Get service types with descriptions
     */
    public function getServiceTypes()
    {
        return [
            'ALA' => 'Alarm & Access',
            'CAM' => 'Camera Systems',
            'ITS' => 'IT Services & Support',
            'SUB' => 'Subcontracted Services'
        ];
    }

    /**
     * Get services by client ID
     */
    public function getServicesByClient($clientId)
    {
        return $this->where('client_id', $clientId)
                   ->orderBy('service_type', 'ASC')
                   ->findAll();
    }

    /**
     * Get all service registry entries with client information
     */
    public function getServicesWithClients($status = null, $search = null, $serviceType = null)
    {
        $builder = $this->db->table('service_registry sr')
                           ->select('sr.*, c.client_name, c.contact_person, c.email, c.phone')
                           ->join('clients c', 'sr.client_id = c.id', 'left');
        
        if ($status && $status !== 'all') {
            $builder->where('sr.status', $status);
        }
        
        if ($serviceType && $serviceType !== 'all') {
            $builder->where('sr.service_type', $serviceType);
        }
        
        if ($search) {
            $builder->groupStart()
                   ->like('c.client_name', $search)
                   ->orLike('sr.service_name', $search)
                   ->orLike('sr.account_code', $search)
                   ->groupEnd();
        }
        
        return $builder->orderBy('c.client_name', 'ASC')
                      ->orderBy('sr.service_type', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    /**
     * Generate next account code for a service type
     */
    public function generateAccountCode($serviceType, $clientAbbreviation)
    {
        $sequenceModel = new AccountSequenceModel();
        $sequence = $sequenceModel->getNextSequence($serviceType);
        
        return $serviceType . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT) . '-' . $clientAbbreviation;
    }

    /**
     * Check if account code is unique
     */
    public function isAccountCodeUnique($accountCode, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('account_code', $accountCode);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() === 0;
    }

    /**
     * Get service registry statistics
     */
    public function getServiceStats()
    {
        $serviceTypes = $this->getServiceTypes();
        $stats = [];
        
        foreach ($serviceTypes as $type => $name) {
            $stats[$type] = [
                'name' => $name,
                'total' => $this->where('service_type', $type)->countAllResults(),
                'active' => $this->where(['service_type' => $type, 'status' => 'active'])->countAllResults(),
                'inactive' => $this->where(['service_type' => $type, 'status' => 'inactive'])->countAllResults()
            ];
        }
        
        return $stats;
    }

    /**
     * Get service registry entry with client details
     */
    public function getServiceWithClient($id)
    {
        return $this->db->table('service_registry sr')
                       ->select('sr.*, c.client_name, c.contact_person, c.email, c.phone')
                       ->join('clients c', 'sr.client_id = c.id', 'left')
                       ->where('sr.id', $id)
                       ->get()
                       ->getRowArray();
    }

    /**
     * Delete service registry entry
     */
    public function deleteService($id)
    {
        return $this->delete($id);
    }

    /**
     * Get services for dropdown/select options
     */
    public function getServicesForDropdown($clientId = null)
    {
        $builder = $this->db->table('service_registry sr')
                           ->select('sr.id, sr.account_code, sr.service_name, sr.service_type, c.client_name')
                           ->join('clients c', 'sr.client_id = c.id', 'left')
                           ->where('sr.status', 'active');
        
        if ($clientId) {
            $builder->where('sr.client_id', $clientId);
        }
        
        return $builder->orderBy('c.client_name', 'ASC')
                      ->orderBy('sr.service_type', 'ASC')
                      ->get()
                      ->getResultArray();
    }
}
