<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table            = 'profiles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'description',
        'permissions',
        'status',
        'is_default',
        'created_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'permissions' => 'json',
        'is_default' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[255]|is_unique[profiles.name,id,{id}]',
        'description' => 'permit_empty|max_length[1000]',
        'status' => 'required|in_list[active,inactive]',
        'permissions' => 'permit_empty',
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Profile name is required',
            'max_length' => 'Profile name cannot exceed 255 characters',
            'is_unique' => 'A profile with this name already exists'
        ],
        'status' => [
            'required' => 'Profile status is required',
            'in_list' => 'Status must be either active or inactive'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setCreatedBy'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['checkProfileInUse'];
    protected $afterDelete    = [];

    /**
     * Set created_by field before insert
     */
    protected function setCreatedBy(array $data)
    {
        if (!isset($data['data']['created_by']) && session()->has('user_id')) {
            $data['data']['created_by'] = session('user_id');
        }
        return $data;
    }

    /**
     * Check if profile is in use before delete
     */
    protected function checkProfileInUse(array $data)
    {
        $userModel = new \App\Models\UserModel();
        $profileId = $data['id'][0] ?? null;
        
        if ($profileId) {
            // Check if any users are using this profile
            $usersCount = $userModel->where('role', $this->find($profileId)['name'])->countAllResults();
            
            if ($usersCount > 0) {
                throw new \Exception('Cannot delete profile that is currently assigned to users.');
            }
            
            // Check if it's a default profile
            $profile = $this->find($profileId);
            if ($profile && $profile['is_default']) {
                throw new \Exception('Cannot delete default system profiles.');
            }
        }
        
        return $data;
    }

    /**
     * Get all active profiles
     */
    public function getActiveProfiles()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get profiles with user count
     */
    public function getProfilesWithUserCount()
    {
        $builder = $this->db->table($this->table . ' p')
            ->select('p.*, COUNT(u.id) as user_count')
            ->join('users u', 'u.role = p.name', 'left')
            ->groupBy('p.id')
            ->orderBy('p.name');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get profile by name
     */
    public function getByName(string $name)
    {
        return $this->where('name', $name)->first();
    }

    /**
     * Get default permissions structure
     */
    public function getDefaultPermissions()
    {
        return [
            'settings' => [],
            'users' => [],
            'customers' => [],
            'work_orders' => [],
            'dispatch' => [],
            'billing' => [],
            'reports' => [],
            'territories' => [],
            'skills' => [],
            'holidays' => [],
        ];
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(array $profile, string $module, string $action = 'read')
    {
        $permissions = $profile['permissions'] ?? [];
        
        if (empty($permissions)) {
            return false;
        }
        
        return isset($permissions[$module]) && in_array($action, $permissions[$module]);
    }
}
