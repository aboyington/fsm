<?php

namespace App\Models;

use CodeIgniter\Model;

class SkillModel extends Model
{
    protected $table = 'skills';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'description',
        'status',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'description' => 'permit_empty|max_length[1000]',
        'status' => 'required|in_list[active,inactive]',
        'created_by' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Skill name is required.',
            'max_length' => 'Skill name cannot exceed 255 characters.'
        ],
        'description' => [
            'max_length' => 'Description cannot exceed 1000 characters.'
        ],
        'status' => [
            'required' => 'Status is required.',
            'in_list' => 'Status must be either active or inactive.'
        ]
    ];

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
     * Get skills with creator information
     */
    public function getSkillsWithCreator($status = null, $search = null)
    {
        $builder = $this->builder();
        
        // Join with users table to get creator name
        $builder->select('skills.*, CONCAT(users.first_name, " ", users.last_name) as creator_name')
                ->join('users', 'users.id = skills.created_by', 'left');
        
        // Apply status filter
        if ($status && $status !== 'all') {
            $builder->where('skills.status', $status);
        }
        
        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('skills.name', $search)
                    ->orLike('skills.description', $search)
                    ->groupEnd();
        }
        
        // Order by created_at desc
        $builder->orderBy('skills.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get active skills for dropdowns
     */
    public function getActiveSkills()
    {
        return $this->where('status', 'active')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Check if skill name exists (for validation)
     */
    public function skillNameExists($name, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('name', $name);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }
}
