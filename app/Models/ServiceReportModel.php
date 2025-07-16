<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceReportModel extends Model
{
    protected $table = 'service_reports';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'service_appointment_id',
        'work_order_id',
        'technician_id',
        'report_date',
        'status',
        'service_type',
        'work_summary',
        'parts_used',
        'time_spent',
        'labor_cost',
        'material_cost',
        'total_cost',
        'customer_feedback',
        'recommendations',
        'additional_notes',
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
        'service_appointment_id' => 'required|numeric',
        'work_order_id' => 'required|numeric',
        'technician_id' => 'permit_empty|numeric',
        'report_date' => 'required|valid_date',
        'status' => 'required|in_list[draft,completed,submitted,approved]',
        'service_type' => 'permit_empty|in_list[installation,repair,maintenance,inspection,consultation,other]',
        'work_summary' => 'required|max_length[2000]',
        'time_spent' => 'permit_empty|decimal',
        'labor_cost' => 'permit_empty|decimal',
        'material_cost' => 'permit_empty|decimal',
        'total_cost' => 'permit_empty|decimal',
        'customer_feedback' => 'permit_empty|max_length[1000]',
        'recommendations' => 'permit_empty|max_length[1000]',
        'additional_notes' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'service_appointment_id' => [
            'required' => 'Service appointment is required',
            'numeric' => 'Service appointment must be numeric'
        ],
        'work_order_id' => [
            'required' => 'Work order is required',
            'numeric' => 'Work order must be numeric'
        ],
        'report_date' => [
            'required' => 'Report date is required',
            'valid_date' => 'Please provide a valid date'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: draft, completed, submitted, approved'
        ],
        'work_summary' => [
            'required' => 'Work summary is required',
            'max_length' => 'Work summary cannot exceed 2000 characters'
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

    /**
     * Get all reports with related details
     */
    public function getReports($status = null, $searchTerm = null, $date = null)
    {
        $this->select('service_reports.*, work_orders.summary as work_order_title, work_orders.work_order_number, CONCAT(users.first_name, " ", users.last_name) as technician_name')
            ->join('work_orders', 'work_orders.id = service_reports.work_order_id', 'left')
            ->join('users', 'users.id = service_reports.technician_id', 'left')
            ->orderBy('service_reports.report_date', 'DESC');

        if ($status) {
            $this->where('service_reports.status', $status);
        }

        if ($searchTerm) {
            $this->groupStart()
                ->like('work_orders.summary', $searchTerm)
                ->orLike('users.first_name', $searchTerm)
                ->orLike('users.last_name', $searchTerm)
                ->orLike('service_reports.work_summary', $searchTerm)
                ->groupEnd();
        }

        if ($date) {
            $this->where('DATE(service_reports.report_date)', $date);
        }

        return $this->findAll();
    }

    /**
     * Get report with full details
     */
    public function getReportWithDetails($id)
    {
        return $this->select('service_reports.*, work_orders.summary as work_order_title, work_orders.work_order_number, CONCAT(users.first_name, " ", users.last_name) as technician_name, users.email as technician_email')
                    ->join('work_orders', 'work_orders.id = service_reports.work_order_id', 'left')
                    ->join('users', 'users.id = service_reports.technician_id', 'left')
                    ->find($id);
    }
}

