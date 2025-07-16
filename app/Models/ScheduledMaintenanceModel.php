<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduledMaintenanceModel extends Model
{
    protected $table = 'scheduled_maintenances';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'description',
        'schedule_type',
        'start_date',
        'end_date',
        'frequency',
        'schedule_details',
        'client_id',
        'asset_id',
        'assigned_to',
        'territory_id',
        'priority',
        'estimated_duration',
        'status',
        'notes',
        'next_due_date',
        'last_generated_date',
        'created_by',
        'updated_by'
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
        'description' => 'permit_empty',
        'schedule_type' => 'required|in_list[daily,weekly,monthly,yearly,custom]',
        'start_date' => 'required|valid_date',
        'end_date' => 'permit_empty|valid_date',
        'frequency' => 'permit_empty|integer|greater_than[0]',
        'client_id' => 'permit_empty|integer',
        'asset_id' => 'permit_empty|integer',
        'assigned_to' => 'permit_empty|integer',
        'territory_id' => 'permit_empty|integer',
        'priority' => 'permit_empty|in_list[low,medium,high,urgent]',
        'estimated_duration' => 'permit_empty|integer|greater_than[0]',
        'status' => 'required|in_list[draft,active,inactive,paused]',
        'notes' => 'permit_empty'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'max_length' => 'Name cannot exceed 255 characters'
        ],
        'schedule_type' => [
            'required' => 'Schedule type is required',
            'in_list' => 'Schedule type must be one of: daily, weekly, monthly, yearly, custom'
        ],
        'start_date' => [
            'required' => 'Start date is required',
            'valid_date' => 'Please provide a valid start date'
        ],
        'end_date' => [
            'valid_date' => 'Please provide a valid end date'
        ],
        'frequency' => [
            'integer' => 'Frequency must be a number',
            'greater_than' => 'Frequency must be greater than 0'
        ],
        'priority' => [
            'in_list' => 'Priority must be one of: low, medium, high, urgent'
        ],
        'estimated_duration' => [
            'integer' => 'Estimated duration must be a number',
            'greater_than' => 'Estimated duration must be greater than 0'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: draft, active, inactive, paused'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        $data['data']['created_by'] = $data['data']['created_by'] ?? session()->get('user_id');
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_by'] = session()->get('user_id');
        return $data;
    }
}
