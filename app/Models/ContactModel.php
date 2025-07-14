<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'first_name',
        'last_name', 
        'email',
        'phone',
        'mobile',
        'job_title',
        'company_id',
        'territory_id',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'notes',
        'status',
        'is_primary',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'first_name' => 'required|max_length[100]',
        'last_name' => 'required|max_length[100]',
        'email' => 'permit_empty|valid_email|max_length[255]',
        'phone' => 'permit_empty|max_length[50]',
        'mobile' => 'permit_empty|max_length[50]',
        'job_title' => 'permit_empty|max_length[100]',
        'company_id' => 'permit_empty|integer',
        'territory_id' => 'permit_empty|integer',
        'city' => 'permit_empty|max_length[100]',
        'state' => 'permit_empty|max_length[100]',
        'zip_code' => 'permit_empty|max_length[20]',
        'country' => 'permit_empty|max_length[100]',
        'status' => 'permit_empty|in_list[active,inactive]',
        'is_primary' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'max_length' => 'First name cannot exceed 100 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'max_length' => 'Last name cannot exceed 100 characters'
        ],
        'email' => [
            'valid_email' => 'Please enter a valid email address',
            'max_length' => 'Email cannot exceed 255 characters'
        ]
    ];

    // Skip validation
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all contacts with company information
     */
    public function getContacts($status = null, $searchTerm = null, $companyId = null)
    {
        $builder = $this->select('contacts.*, clients.client_name as company_name')
                        ->join('clients', 'clients.id = contacts.company_id', 'left');

        if ($status) {
            $builder->where('contacts.status', $status);
        }

        if ($companyId) {
            $builder->where('contacts.company_id', $companyId);
        }

        if ($searchTerm) {
            $builder->groupStart()
                   ->like('contacts.first_name', $searchTerm)
                   ->orLike('contacts.last_name', $searchTerm)
                   ->orLike('contacts.email', $searchTerm)
                   ->orLike('contacts.phone', $searchTerm)
                   ->orLike('contacts.mobile', $searchTerm)
                   ->orLike('contacts.job_title', $searchTerm)
                   ->orLike('clients.client_name', $searchTerm)
                   ->groupEnd();
        }

        return $builder->orderBy('contacts.first_name', 'ASC')
                      ->orderBy('contacts.last_name', 'ASC')
                      ->findAll();
    }

    /**
     * Get contacts by company
     */
    public function getContactsByCompany($companyId)
    {
        return $this->where('company_id', $companyId)
                   ->orderBy('is_primary', 'DESC')
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    /**
     * Get primary contact for a company
     */
    public function getPrimaryContact($companyId)
    {
        return $this->where('company_id', $companyId)
                   ->where('is_primary', 1)
                   ->first();
    }

    /**
     * Set primary contact (unset other primary contacts for the company)
     */
    public function setPrimaryContact($contactId, $companyId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Remove primary status from all contacts in this company
            $this->where('company_id', $companyId)
                 ->set('is_primary', 0)
                 ->update();

            // Set the specified contact as primary
            $this->where('id', $contactId)
                 ->set('is_primary', 1)
                 ->update();

            $db->transComplete();
            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    /**
     * Search contacts
     */
    public function search($searchTerm)
    {
        return $this->select('contacts.*, clients.client_name as company_name')
                   ->join('clients', 'clients.id = contacts.company_id', 'left')
                   ->groupStart()
                   ->like('contacts.first_name', $searchTerm)
                   ->orLike('contacts.last_name', $searchTerm)
                   ->orLike('contacts.email', $searchTerm)
                   ->orLike('contacts.phone', $searchTerm)
                   ->orLike('contacts.mobile', $searchTerm)
                   ->orLike('contacts.job_title', $searchTerm)
                   ->orLike('clients.client_name', $searchTerm)
                   ->groupEnd()
                   ->orderBy('contacts.first_name', 'ASC')
                   ->findAll();
    }

    /**
     * Get contacts by status
     */
    public function getByStatus($status)
    {
        return $this->select('contacts.*, clients.client_name as company_name')
                   ->join('clients', 'clients.id = contacts.company_id', 'left')
                   ->where('contacts.status', $status)
                   ->orderBy('contacts.first_name', 'ASC')
                   ->findAll();
    }

    /**
     * Get contact with company information
     */
    public function getContactWithCompany($id)
    {
        return $this->select('contacts.*, clients.client_name as company_name')
                   ->join('clients', 'clients.id = contacts.company_id', 'left')
                   ->where('contacts.id', $id)
                   ->first();
    }
}
