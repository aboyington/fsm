<?php

namespace App\Controllers;

use App\Models\ServiceAppointmentModel;
use App\Models\WorkOrderModel;
use App\Models\UserModel;

class ServiceAppointmentsController extends BaseController
{
    protected $appointmentModel;
    protected $workOrderModel;
    protected $userModel;

    public function __construct()
    {
        $this->appointmentModel = new ServiceAppointmentModel();
        $this->workOrderModel = new WorkOrderModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $appointments = $this->appointmentModel->getAppointments();
        $workOrders = $this->workOrderModel->findAll();
        $technicians = $this->userModel->where('role', 'technician')->findAll();
        
        $data = [
            'title' => 'Service Appointments - FSM Platform',
            'appointments' => $appointments,
            'work_orders' => $workOrders,
            'technicians' => $technicians,
            'total_appointments' => count($appointments),
            'scheduled_appointments' => count(array_filter($appointments, function($a) { return $a['status'] === 'scheduled'; })),
            'in_progress_appointments' => count(array_filter($appointments, function($a) { return $a['status'] === 'in_progress'; })),
            'completed_appointments' => count(array_filter($appointments, function($a) { return $a['status'] === 'completed'; })),
            'cancelled_appointments' => count(array_filter($appointments, function($a) { return $a['status'] === 'cancelled'; }))
        ];
        
        return view('service_appointments/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Validate the data
            if (!$this->appointmentModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->appointmentModel->errors()
                ]);
            }
            
            // Set created_by to current user
            $data['created_by'] = session()->get('user_id');
            
            // Insert the appointment
            if ($this->appointmentModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service appointment created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create service appointment'
                ]);
            }
        }
        
        $workOrders = $this->workOrderModel->findAll();
        $technicians = $this->userModel->where('role', 'technician')->findAll();
        
        return view('service_appointments/create', [
            'work_orders' => $workOrders,
            'technicians' => $technicians
        ]);
    }

    public function get($id)
    {
        $appointment = $this->appointmentModel->getAppointmentWithDetails($id);
        
        if (!$appointment) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service appointment not found'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $appointment
        ]);
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Find the appointment
            $appointment = $this->appointmentModel->find($id);
            if (!$appointment) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service appointment not found'
                ]);
            }
            
            // Validate the data
            if (!$this->appointmentModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->appointmentModel->errors()
                ]);
            }
            
            // Update the appointment
            if ($this->appointmentModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service appointment updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service appointment'
                ]);
            }
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }

    public function delete($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $appointment = $this->appointmentModel->find($id);
            
            if (!$appointment) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service appointment not found'
                ]);
            }
            
            // Delete the appointment
            if ($this->appointmentModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service appointment deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete service appointment'
                ]);
            }
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }

    public function search()
    {
        $searchTerm = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $workOrderId = $this->request->getGet('work_order_id');
        $technicianId = $this->request->getGet('technician_id');
        
        $appointments = $this->appointmentModel->getAppointments($status, $searchTerm, $workOrderId, $technicianId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $appointments
        ]);
    }

    public function updateStatus($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $status = $data['status'] ?? '';
            
            $appointment = $this->appointmentModel->find($id);
            
            if (!$appointment) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service appointment not found'
                ]);
            }
            
            if ($this->appointmentModel->update($id, ['status' => $status])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service appointment status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service appointment status'
                ]);
            }
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }

    public function getByWorkOrder($workOrderId)
    {
        $appointments = $this->appointmentModel->getAppointmentsByWorkOrder($workOrderId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $appointments
        ]);
    }

    public function getByTechnician($technicianId)
    {
        $appointments = $this->appointmentModel->getAppointmentsByTechnician($technicianId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $appointments
        ]);
    }
}
