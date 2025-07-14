<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'client_name', 'contact_person', 'email', 'website', 'company_type', 'territory_id', 'phone', 'address', 
        'city', 'state', 'zip_code', 'country', 'status', 'notes', 'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'client_name' => 'required|max_length[255]|is_unique[clients.client_name,id,{id}]',
        'contact_person' => 'permit_empty|max_length[255]',
        'email' => 'permit_empty|valid_email|max_length[255]',
        'website' => 'permit_empty|valid_url|max_length[255]',
        'company_type' => 'permit_empty|in_list[Analyst,Competitor,Customer,Distributor,Integrator,Investor,Other,Partner,Press,Prospect,Reseller,Supplier,Vendor]',
        'territory_id' => 'permit_empty|integer',
        'phone' => 'permit_empty|max_length[50]',
        'address' => 'permit_empty',
        'city' => 'permit_empty|max_length[100]',
        'state' => 'permit_empty|max_length[100]',
        'zip_code' => 'permit_empty|max_length[20]',
        'country' => 'permit_empty|max_length[100]',
        'status' => 'permit_empty|in_list[active,inactive]',
        'notes' => 'permit_empty'
    ];

    protected $validationMessages = [
        'client_name' => [
            'required' => 'Client name is required.',
            'is_unique' => 'This client name already exists.'
        ],
        'email' => [
            'valid_email' => 'Please provide a valid email address.'
        ],
        'website' => [
            'valid_url' => 'Please provide a valid website URL.'
        ],
        'company_type' => [
            'in_list' => 'Please select a valid company type.'
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
        $data = $this->passwordHash($data);
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data = $this->passwordHash($data);
        return $data;
    }

    protected function passwordHash(array $data)
    {
        if (isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }

    /**
     * Get all clients with optional filtering
     */
    public function getClients($status = null, $search = null)
    {
        $builder = $this->builder();
        
        if ($status && $status !== 'all') {
            $builder->where('status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                   ->like('client_name', $search)
                   ->orLike('contact_person', $search)
                   ->orLike('email', $search)
                   ->groupEnd();
        }
        
        return $builder->orderBy('client_name', 'ASC')->get()->getResultArray();
    }

    /**
     * Get client with service registry information
     */
    public function getClientWithServices($id)
    {
        $client = $this->find($id);
        if (!$client) {
            return null;
        }
        
        $serviceModel = new ServiceRegistryModel();
        $client['services'] = $serviceModel->getServicesByClient($id);
        
        return $client;
    }

    /**
     * Generate client abbreviation from name
     */
    public function generateClientAbbreviation($clientName)
    {
        // Remove common words and get first 4 letters of significant words
        $commonWords = ['THE', 'AND', 'OR', 'BUT', 'IN', 'ON', 'AT', 'TO', 'FOR', 'OF', 'WITH', 'BY', 'FROM', 'UP', 'ABOUT', 'INTO', 'THROUGH', 'DURING', 'BEFORE', 'AFTER', 'ABOVE', 'BELOW', 'BETWEEN', 'AMONG', 'WITHIN', 'WITHOUT', 'AGAINST', 'TOWARD', 'UPON', 'COMPANY', 'CORP', 'LLC', 'LTD', 'INC'];
        
        $words = explode(' ', strtoupper($clientName));
        $abbreviation = '';
        
        foreach ($words as $word) {
            $word = preg_replace('/[^A-Z0-9]/', '', $word);
            if (!in_array($word, $commonWords) && strlen($word) > 0) {
                $abbreviation .= substr($word, 0, 4);
                if (strlen($abbreviation) >= 4) {
                    break;
                }
            }
        }
        
        return substr($abbreviation, 0, 4);
    }

    /**
     * Check if client name is unique
     */
    public function isClientNameUnique($name, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('client_name', $name);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() === 0;
    }

    /**
     * Get client statistics
     */
    public function getClientStats()
    {
        $total = $this->countAll();
        $active = $this->where('status', 'active')->countAllResults();
        $inactive = $this->where('status', 'inactive')->countAllResults();
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive
        ];
    }
}
