<?php

namespace App\Models;

use CodeIgniter\Model;

class RecordTemplateModel extends Model
{
    protected $table = 'record_templates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'description', 
        'module',
        'template_data',
        'is_active',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'module' => 'required|in_list[Requests,Estimates,Work Orders,Service Appointments,Time Sheets,Service Reports,Invoices,Products,Customers,Contacts]',
        'is_active' => 'in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Template name is required.',
            'min_length' => 'Template name must be at least 3 characters long.',
            'max_length' => 'Template name cannot exceed 255 characters.'
        ],
        'module' => [
            'required' => 'Module selection is required.',
            'in_list' => 'Please select a valid module.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = [];

    protected function setCreatedBy(array $data)
    {
        if (!isset($data['data']['created_by'])) {
            $currentUser = session()->get('user');
            if ($currentUser && isset($currentUser['id'])) {
                $data['data']['created_by'] = $currentUser['id'];
            }
        }
        return $data;
    }

    /**
     * Get all templates with creator information
     */
    public function getTemplatesWithCreator($module = null, $search = null)
    {
        $builder = $this->builder();
        $builder->select('record_templates.*, CONCAT(users.first_name, " ", users.last_name) as created_by_name')
                ->join('users', 'users.id = record_templates.created_by', 'left')
                ->orderBy('record_templates.created_at', 'DESC');

        if ($module && $module !== 'All') {
            $builder->where('record_templates.module', $module);
        }

        if ($search) {
            $builder->groupStart()
                    ->like('record_templates.name', $search)
                    ->orLike('record_templates.description', $search)
                    ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get available modules for templates
     */
    public function getAvailableModules()
    {
        return [
            'Requests' => 'Requests',
            'Estimates' => 'Estimates', 
            'Work Orders' => 'Work Orders',
            'Service Appointments' => 'Service Appointments',
            'Time Sheets' => 'Time Sheets',
            'Service Reports' => 'Service Reports',
            'Invoices' => 'Invoices',
            'Products' => 'Products',
            'Customers' => 'Customers',
            'Contacts' => 'Contacts'
        ];
    }

    /**
     * Get template by ID with creator info
     */
    public function getTemplateWithCreator($id)
    {
        return $this->builder()
                    ->select('record_templates.*, CONCAT(users.first_name, " ", users.last_name) as created_by_name')
                    ->join('users', 'users.id = record_templates.created_by', 'left')
                    ->where('record_templates.id', $id)
                    ->get()
                    ->getRowArray();
    }

    /**
     * Create a new template
     */
    public function createTemplate($data)
    {
        // Encode template_data as JSON if it's an array
        if (isset($data['template_data']) && is_array($data['template_data'])) {
            $data['template_data'] = json_encode($data['template_data']);
        }

        return $this->insert($data);
    }

    /**
     * Update a template
     */
    public function updateTemplate($id, $data)
    {
        // Encode template_data as JSON if it's an array
        if (isset($data['template_data']) && is_array($data['template_data'])) {
            $data['template_data'] = json_encode($data['template_data']);
        }

        return $this->update($id, $data);
    }

    /**
     * Get template count by module
     */
    public function getTemplateCountByModule()
    {
        return $this->builder()
                    ->select('module, COUNT(*) as count')
                    ->where('is_active', 1)
                    ->groupBy('module')
                    ->get()
                    ->getResultArray();
    }

    /**
     * Duplicate a template
     */
    public function duplicateTemplate($id, $newName = null)
    {
        $template = $this->find($id);
        if (!$template) {
            return false;
        }

        // Remove ID and timestamps to create new record
        unset($template['id'], $template['created_at'], $template['updated_at']);
        
        // Set new name if provided
        if ($newName) {
            $template['name'] = $newName;
        } else {
            $template['name'] = $template['name'] . ' (Copy)';
        }

        return $this->insert($template);
    }

    /**
     * Get default template fields for a module
     */
    public function getDefaultTemplateFields($module)
    {
        $defaultFields = [
            'Requests' => [
                ['name' => 'request_title', 'label' => 'Request Title', 'type' => 'text', 'required' => true],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => true],
                ['name' => 'priority', 'label' => 'Priority', 'type' => 'select', 'required' => false],
                ['name' => 'due_date', 'label' => 'Due Date', 'type' => 'date', 'required' => false]
            ],
            'Estimates' => [
                ['name' => 'estimate_title', 'label' => 'Estimate Title', 'type' => 'text', 'required' => true],
                ['name' => 'customer', 'label' => 'Customer', 'type' => 'lookup', 'required' => true],
                ['name' => 'valid_until', 'label' => 'Valid Until', 'type' => 'date', 'required' => false],
                ['name' => 'terms', 'label' => 'Terms & Conditions', 'type' => 'textarea', 'required' => false]
            ],
            'Work Orders' => [
                ['name' => 'work_order_title', 'label' => 'Work Order Title', 'type' => 'text', 'required' => true],
                ['name' => 'customer', 'label' => 'Customer', 'type' => 'lookup', 'required' => true],
                ['name' => 'assigned_to', 'label' => 'Assigned To', 'type' => 'lookup', 'required' => false],
                ['name' => 'scheduled_date', 'label' => 'Scheduled Date', 'type' => 'datetime', 'required' => false]
            ]
        ];

        return $defaultFields[$module] ?? [];
    }
}
