<?php

namespace App\Controllers;

use App\Models\ServiceReportModel;
use App\Models\ServiceAppointmentModel;
use App\Models\WorkOrderModel;
use App\Models\UserModel;

class ServiceReportsController extends BaseController
{
    protected $reportModel;
    protected $appointmentModel;
    protected $workOrderModel;
    protected $userModel;

    public function __construct()
    {
        $this->reportModel = new ServiceReportModel();
        $this->appointmentModel = new ServiceAppointmentModel();
        $this->workOrderModel = new WorkOrderModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $reports = $this->reportModel->getReports();
        $serviceAppointments = $this->appointmentModel->findAll();
        $workOrders = $this->workOrderModel->findAll();
        $technicians = $this->userModel->where('role', 'technician')->findAll();

        $data = [
            'title' => 'Service Reports - FSM Platform',
            'reports' => $reports,
            'service_appointments' => $serviceAppointments,
            'work_orders' => $workOrders,
            'technicians' => $technicians,
            'total_reports' => count($reports),
            'draft_reports' => count(array_filter($reports, function($r) { return $r['status'] === 'draft'; })),
            'completed_reports' => count(array_filter($reports, function($r) { return $r['status'] === 'completed'; })),
            'submitted_reports' => count(array_filter($reports, function($r) { return $r['status'] === 'submitted'; })),
            'approved_reports' => count(array_filter($reports, function($r) { return $r['status'] === 'approved'; }))
        ];

        return view('service_reports/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();

            // Validate the data
            if (!$this->reportModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->reportModel->errors()
                ]);
            }

            // Set created_by to current user
            $data['created_by'] = session()->get('user_id');

            // Insert the report
            if ($this->reportModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service report created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create service report'
                ]);
            }
        }

        return redirect()->back();
    }

    public function get($id)
    {
        $report = $this->reportModel->getReportWithDetails($id);

        if (!$report) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Service report not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $report
        ]);
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();

            // Find the report
            $report = $this->reportModel->find($id);
            if (!$report) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service report not found'
                ]);
            }

            // Validate the data
            if (!$this->reportModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->reportModel->errors()
                ]);
            }

            // Update the report
            if ($this->reportModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service report updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service report'
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
            $report = $this->reportModel->find($id);

            if (!$report) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service report not found'
                ]);
            }

            // Delete the report
            if ($this->reportModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service report deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete service report'
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
        $date = $this->request->getGet('date');

        $reports = $this->reportModel->getReports($status, $searchTerm, $date);

        return $this->response->setJSON([
            'success' => true,
            'data' => $reports
        ]);
    }

    public function updateStatus($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $status = $data['status'] ?? '';

            $report = $this->reportModel->find($id);

            if (!$report) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Service report not found'
                ]);
            }

            if ($this->reportModel->update($id, ['status' => $status])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Service report status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update service report status'
                ]);
            }
        }

        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
}

