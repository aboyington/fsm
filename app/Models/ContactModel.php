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
        'created_by',
        'account_number',
        'account_abbreviation'
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
        'email' => 'permit_empty|valid_email|max_length[255]|is_unique[contacts.email,id,{id}]',
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
        $data = $this->generateAccountNumber($data);
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
     * Generate account number for new contact (only if not associated with a company)
     */
    protected function generateAccountNumber(array $data)
    {
        // Only generate account number if:
        // 1. No account_number is already set
        // 2. Contact is NOT associated with a company (company_id is null/empty)
        if ((!isset($data['data']['account_number']) || empty($data['data']['account_number'])) &&
            (!isset($data['data']['company_id']) || empty($data['data']['company_id']))) {
            
            $accountSequenceModel = new \App\Models\AccountSequenceModel();
            
            // Generate abbreviation from first and last name
            $fullName = $data['data']['first_name'] . ' ' . $data['data']['last_name'];
            $abbreviation = $this->generateContactAbbreviation($fullName);
            
            // Get next sequence number for ACC prefix
            $nextSequence = $accountSequenceModel->getNextSequence('ACC');
            
            // Generate account number: ACC-011-JOHN (continuing from company sequence)
            $data['data']['account_number'] = sprintf('ACC-%03d-%s', $nextSequence, $abbreviation);
            $data['data']['account_abbreviation'] = $abbreviation;
        }
        
        return $data;
    }
    
    /**
     * Generate contact abbreviation from first and last name
     */
    public function generateContactAbbreviation($fullName)
    {
        $words = explode(' ', strtoupper($fullName));
        $abbreviation = '';
        
        // Take first 2 chars from first name and first 2 chars from last name
        if (count($words) >= 2) {
            $firstName = preg_replace('/[^A-Z0-9]/', '', $words[0]);
            $lastName = preg_replace('/[^A-Z0-9]/', '', $words[1]);
            
            $abbreviation = substr($firstName, 0, 2) . substr($lastName, 0, 2);
        } else {
            // If only one name, take first 4 characters
            $singleName = preg_replace('/[^A-Z0-9]/', '', $words[0]);
            $abbreviation = substr($singleName, 0, 4);
        }
        
        // Ensure we have at least 4 characters, pad with first letters if needed
        if (strlen($abbreviation) < 4) {
            foreach ($words as $word) {
                $word = preg_replace('/[^A-Z0-9]/', '', $word);
                if (strlen($word) > 0) {
                    $abbreviation .= substr($word, 0, 1);
                    if (strlen($abbreviation) >= 4) {
                        break;
                    }
                }
            }
        }
        
        return substr(strtoupper($abbreviation), 0, 4);
    }

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
