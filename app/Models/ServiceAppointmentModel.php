<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceAppointmentModel extends Model
{
    protected $table = 'service_appointments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'work_order_id',
        'appointment_date',
        'appointment_time',
        'duration',
        'status',
        'technician_id',
        'notes',
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
        'work_order_id' => 'required|numeric',
        'appointment_date' => 'required|valid_date',
        'appointment_time' => 'required',
        'duration' => 'required|numeric',
        'status' => 'required|in_list[scheduled,in_progress,completed,cancelled]',
        'technician_id' => 'permit_empty|numeric',
        'notes' => 'permit_empty|max_length[1000]'
    ];

    protected $validationMessages = [
        'work_order_id' => [
            'required' => 'Work order is required',
            'numeric' => 'Work order must be numeric'
        ],
        'appointment_date' => [
            'required' => 'Appointment date is required',
            'valid_date' => 'Please provide a valid date'
        ],
        'appointment_time' => [
            'required' => 'Appointment time is required'
        ],
        'duration' => [
            'required' => 'Duration is required',
            'numeric' => 'Duration must be numeric'
        ],
        'status' => [
            'required' => 'Status is required',
            'in_list' => 'Status must be one of: scheduled, in_progress, completed, cancelled'
        ],
        'notes' => [
            'max_length' => 'Notes cannot exceed 1000 characters'
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
     * Get all appointments with work order and technician details
     */
    public function getAppointments($status = null, $searchTerm = null, $workOrderId = null, $technicianId = null)
    {
        $this->select('service_appointments.*, work_orders.summary as work_order_title, work_orders.work_order_number, CONCAT(users.first_name, " ", users.last_name) as technician_name')
             ->join('work_orders', 'work_orders.id = service_appointments.work_order_id', 'left')
             ->join('users', 'users.id = service_appointments.technician_id', 'left')
             ->orderBy('service_appointments.appointment_date', 'DESC')
             ->orderBy('service_appointments.appointment_time', 'DESC');

        if ($status) {
            $this->where('service_appointments.status', $status);
        }

        if ($searchTerm) {
            $this->groupStart()
                 ->like('work_orders.summary', $searchTerm)
                 ->orLike('work_orders.work_order_number', $searchTerm)
                 ->orLike('users.first_name', $searchTerm)
                 ->orLike('users.last_name', $searchTerm)
                 ->orLike('service_appointments.notes', $searchTerm)
                 ->groupEnd();
        }

        if ($workOrderId) {
            $this->where('service_appointments.work_order_id', $workOrderId);
        }

        if ($technicianId) {
            $this->where('service_appointments.technician_id', $technicianId);
        }

        return $this->findAll();
    }

    /**
     * Get appointment with full details
     */
    public function getAppointmentWithDetails($id)
    {
        return $this->select('service_appointments.*, work_orders.summary as work_order_title, work_orders.work_order_number, CONCAT(users.first_name, " ", users.last_name) as technician_name, users.email as technician_email')
                    ->join('work_orders', 'work_orders.id = service_appointments.work_order_id', 'left')
                    ->join('users', 'users.id = service_appointments.technician_id', 'left')
                    ->find($id);
    }

    /**
     * Get appointments by work order
     */
    public function getAppointmentsByWorkOrder($workOrderId)
    {
        return $this->select('service_appointments.*, users.name as technician_name')
                    ->join('users', 'users.id = service_appointments.technician_id', 'left')
                    ->where('service_appointments.work_order_id', $workOrderId)
                    ->orderBy('service_appointments.appointment_date', 'ASC')
                    ->orderBy('service_appointments.appointment_time', 'ASC')
                    ->findAll();
    }

    /**
     * Get appointments by technician
     */
    public function getAppointmentsByTechnician($technicianId)
    {
        return $this->select('service_appointments.*, work_orders.summary as work_order_title, work_orders.work_order_number')
                    ->join('work_orders', 'work_orders.id = service_appointments.work_order_id', 'left')
                    ->where('service_appointments.technician_id', $technicianId)
                    ->orderBy('service_appointments.appointment_date', 'ASC')
                    ->orderBy('service_appointments.appointment_time', 'ASC')
                    ->findAll();
    }

    /**
     * Get appointments for today
     */
    public function getTodaysAppointments()
    {
        return $this->select('service_appointments.*, work_orders.summary as work_order_title, users.name as technician_name')
                    ->join('work_orders', 'work_orders.id = service_appointments.work_order_id', 'left')
                    ->join('users', 'users.id = service_appointments.technician_id', 'left')
                    ->where('service_appointments.appointment_date', date('Y-m-d'))
                    ->orderBy('service_appointments.appointment_time', 'ASC')
                    ->findAll();
    }

    /**
     * Get upcoming appointments
     */
    public function getUpcomingAppointments($limit = 10)
    {
        return $this->select('service_appointments.*, work_orders.summary as work_order_title, users.name as technician_name')
                    ->join('work_orders', 'work_orders.id = service_appointments.work_order_id', 'left')
                    ->join('users', 'users.id = service_appointments.technician_id', 'left')
                    ->where('service_appointments.appointment_date >=', date('Y-m-d'))
                    ->where('service_appointments.status', 'scheduled')
                    ->orderBy('service_appointments.appointment_date', 'ASC')
                    ->orderBy('service_appointments.appointment_time', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get overdue appointments
     */
    public function getOverdueAppointments()
    {
        return $this->select('service_appointments.*, work_orders.summary as work_order_title, users.name as technician_name')
                    ->join('work_orders', 'work_orders.id = service_appointments.work_order_id', 'left')
                    ->join('users', 'users.id = service_appointments.technician_id', 'left')
                    ->where('service_appointments.appointment_date <', date('Y-m-d'))
                    ->where('service_appointments.status', 'scheduled')
                    ->orderBy('service_appointments.appointment_date', 'DESC')
                    ->orderBy('service_appointments.appointment_time', 'DESC')
                    ->findAll();
    }
}
