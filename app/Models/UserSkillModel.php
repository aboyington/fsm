<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSkillModel extends Model
{
    protected $table = 'user_skills';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id', 'skill_id', 'skill_level', 'certificate_status', 
        'issue_date', 'expiry_date', 'assigned_by', 'assigned_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'skill_id' => 'required|integer',
        'skill_level' => 'permit_empty|string|max_length[100]',
        'certificate_status' => 'required|in_list[active,inactive,expired,pending]',
        'issue_date' => 'permit_empty|valid_date',
        'expiry_date' => 'permit_empty|valid_date',
        'assigned_by' => 'permit_empty|integer',
        'assigned_at' => 'permit_empty|valid_date'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be an integer'
        ],
        'skill_id' => [
            'required' => 'Skill ID is required',
            'integer' => 'Skill ID must be an integer'
        ],
        'skill_level' => [
            'max_length' => 'Skill level cannot exceed 100 characters'
        ],
        'certificate_status' => [
            'required' => 'Certificate status is required',
            'in_list' => 'Certificate status must be one of: active, inactive, expired, pending'
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
     * Get user skills with skill details
     */
    public function getUserSkillsWithDetails($userId)
    {
        return $this->select('user_skills.*, skills.name as skill_name, skills.description as skill_description, 
                            assigners.first_name as assigned_by_first_name, assigners.last_name as assigned_by_last_name')
                    ->join('skills', 'skills.id = user_skills.skill_id')
                    ->join('users as assigners', 'assigners.id = user_skills.assigned_by', 'left')
                    ->where('user_skills.user_id', $userId)
                    ->findAll();
    }

    /**
     * Get all skills available for assignment
     */
    public function getAvailableSkills()
    {
        $skillModel = new SkillModel();
        return $skillModel->where('status', 'active')->findAll();
    }

    /**
     * Assign skill to user
     */
    public function assignSkillToUser($data)
    {
        // Add assigned_at timestamp
        $data['assigned_at'] = date('Y-m-d H:i:s');
        
        return $this->insert($data);
    }

    /**
     * Update user skill assignment
     */
    public function updateUserSkill($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Remove skill from user
     */
    public function removeSkillFromUser($userId, $skillId)
    {
        return $this->where('user_id', $userId)
                    ->where('skill_id', $skillId)
                    ->delete();
    }

    /**
     * Check if user already has a skill assigned
     */
    public function userHasSkill($userId, $skillId)
    {
        return $this->where('user_id', $userId)
                    ->where('skill_id', $skillId)
                    ->first() !== null;
    }

    /**
     * Get user skills count by status
     */
    public function getUserSkillsCountByStatus($userId)
    {
        return $this->select('certificate_status, COUNT(*) as count')
                    ->where('user_id', $userId)
                    ->groupBy('certificate_status')
                    ->findAll();
    }
}
