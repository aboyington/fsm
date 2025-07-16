<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'category_type', 'is_active', 'created_by', 'updated_by'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'description' => 'permit_empty|max_length[500]',
        'category_type' => 'permit_empty|in_list[parts,services,both]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required.',
            'min_length' => 'Category name must be at least 2 characters long.',
            'max_length' => 'Category name cannot exceed 100 characters.',
            'is_unique' => 'Category name already exists.'
        ]
    ];
    
    protected $skipValidation = false;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get all active categories
     */
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get categories with creator information
     */
    public function getCategoriesWithCreator($status = 'active', $search = '')
    {
        $builder = $this->builder();
        
        // Join with users table to get creator name
        $builder->select('categories.*, CONCAT(users.first_name, " ", users.last_name) as creator_name')
                ->join('users', 'users.id = categories.created_by', 'left');
        
        // Apply status filter
        if ($status !== 'all') {
            $builder->where('categories.is_active', $status === 'active' ? 1 : 0);
        }
        
        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('categories.name', $search)
                    ->orLike('categories.description', $search)
                    ->groupEnd();
        }
        
        return $builder->orderBy('categories.name', 'ASC')
                       ->get()
                       ->getResultArray();
    }
    
    /**
     * Initialize default categories for security industry
     */
    public function initializeDefaultCategories()
    {
        // Check if categories already exist
        $existingCount = $this->countAll();
        
        if ($existingCount > 0) {
            return; // Categories already exist
        }
        
        // Default categories for security industry
        $defaultCategories = [
            ['name' => 'CCTV', 'description' => 'Closed Circuit Television systems and components', 'category_type' => 'both', 'is_active' => 1, 'created_by' => 1],
            ['name' => 'Alarm', 'description' => 'Alarm systems and sensors', 'category_type' => 'both', 'is_active' => 1, 'created_by' => 1],
            ['name' => 'Access Control', 'description' => 'Access control systems and card readers', 'category_type' => 'both', 'is_active' => 1, 'created_by' => 1],
            ['name' => 'I.T', 'description' => 'Information Technology services and support', 'category_type' => 'services', 'is_active' => 1, 'created_by' => 1],
            ['name' => 'Networking', 'description' => 'Network infrastructure and equipment', 'category_type' => 'both', 'is_active' => 1, 'created_by' => 1],
            ['name' => 'Security', 'description' => 'General security services and maintenance', 'category_type' => 'both', 'is_active' => 1, 'created_by' => 1],
            ['name' => 'General', 'description' => 'General services and miscellaneous items', 'category_type' => 'both', 'is_active' => 1, 'created_by' => 1],
        ];
        
        // Insert default categories
        $this->insertBatch($defaultCategories);
    }
    
    /**
     * Get category statistics
     */
    public function getCategoryStats()
    {
        $stats = [];
        
        // Total categories
        $stats['total'] = $this->countAll();
        
        // Active categories
        $stats['active'] = $this->where('is_active', 1)->countAllResults();
        
        // Inactive categories
        $stats['inactive'] = $this->where('is_active', 0)->countAllResults();
        
        // Parts categories
        $stats['parts'] = $this->groupStart()
                              ->where('category_type', 'parts')
                              ->orWhere('category_type', 'both')
                              ->groupEnd()
                              ->countAllResults();
        
        // Services categories  
        $stats['services'] = $this->groupStart()
                                 ->where('category_type', 'services')
                                 ->orWhere('category_type', 'both')
                                 ->groupEnd()
                                 ->countAllResults();
        
        return $stats;
    }
    
    /**
     * Get category options for dropdown
     */
    public function getCategoryOptions()
    {
        $categories = $this->getActiveCategories();
        $options = [];
        
        foreach ($categories as $category) {
            $options[$category['id']] = $category['name'];
        }
        
        return $options;
    }
    
    /**
     * Check if category is being used in parts or services
     */
    public function isCategoryInUse($categoryId)
    {
        $db = \Config\Database::connect();
        
        // Check if category is used in parts table
        $partsCount = $db->table('parts')
                         ->where('category_id', $categoryId)
                         ->countAllResults();
        
        // Check if category is used in services table
        $servicesCount = $db->table('services')
                            ->where('category_id', $categoryId)
                            ->countAllResults();
        
        return ($partsCount > 0 || $servicesCount > 0);
    }
    
    /**
     * Soft delete category (set as inactive instead of deleting)
     */
    public function softDelete($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }
}
