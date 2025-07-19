<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestModel extends Model
{
    protected $table = 'requests';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'request_number',
        'request_name',
        'description',
        'client_id',
        'contact_id',
        'status',
        'priority',
        'due_date',
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
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'request_name' => 'required|max_length[100]',
        'description' => 'permit_empty|max_length[1000]',
        'client_id' => 'permit_empty|integer',
        'contact_id' => 'permit_empty|integer',
        'status' => 'required|in_list[pending,in_progress,on_hold,completed]',
        'priority' => 'permit_empty|in_list[low,medium,high]'
    ];

    protected $validationMessages = [
        'request_name' => [
            'required' => 'Request name is required',
            'max_length' => 'Request name cannot exceed 100 characters'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Invalid status'
        ]
    ];

    protected $skipValidation = false;

    // Auto-generate request number before insert
    protected $beforeInsert = ['generateRequestNumber'];

    protected function generateRequestNumber(array $data)
    {
        if (!isset($data['data']['request_number']) || empty($data['data']['request_number'])) {
            // Get the next ID to generate the request number
            $db = \Config\Database::connect();
            $nextId = $db->table($this->table)->selectMax('id')->get()->getRow()->id ?? 0;
            $nextId += 1;
            
            $data['data']['request_number'] = 'REQ-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }
        
        return $data;
    }

    public function getRequests($status = null, $searchTerm = null, $clientId = null, $priority = null)
    {
        $builder = $this->select('requests.*, clients.client_name as client_name, contacts.first_name as contact_first_name, contacts.last_name as contact_last_name, users.first_name as created_by_first_name, users.last_name as created_by_last_name')
                        ->join('clients', 'clients.id = requests.client_id', 'left')
                        ->join('contacts', 'contacts.id = requests.contact_id', 'left')
                        ->join('users', 'users.id = requests.created_by', 'left');

        if ($status) {
            $builder->where('requests.status', $status);
        }

        if ($clientId) {
            $builder->where('requests.client_id', $clientId);
        }

        if ($priority) {
            $builder->where('requests.priority', $priority);
        }

        if ($searchTerm) {
            $builder->groupStart()
                   ->like('requests.request_number', $searchTerm)
                   ->orLike('requests.request_name', $searchTerm)
                   ->orLike('requests.description', $searchTerm)
                   ->orLike('clients.client_name', $searchTerm)
                   ->orLike('contacts.first_name', $searchTerm)
                   ->orLike('contacts.last_name', $searchTerm)
                   ->groupEnd();
        }

        return $builder->orderBy('requests.created_at', 'DESC')->findAll();
    }

    public function getRequestsByCompany($companyId)
    {
        return $this->where('client_id', $companyId)->orderBy('created_at', 'DESC')->findAll();
    }

    public function getRequestWithDetails($id)
    {
        return $this->select('requests.*, clients.client_name as client_name, contacts.first_name as contact_first_name, contacts.last_name as contact_last_name, users.first_name as created_by_first_name, users.last_name as created_by_last_name')
                    ->join('clients', 'clients.id = requests.client_id', 'left')
                    ->join('contacts', 'contacts.id = requests.contact_id', 'left')
                    ->join('users', 'users.id = requests.created_by', 'left')
                    ->where('requests.id', $id)
                    ->first();
    }
}
