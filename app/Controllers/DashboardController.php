<?php

namespace App\Controllers;

use App\Models\RequestModel;
use App\Models\WorkOrderModel;
use App\Models\EstimateModel;
use App\Models\ServiceAppointmentModel;

class DashboardController extends BaseController
{
    public function index()
    {
        return $this->overview();
    }

    public function overview()
    {
        $data = [
            'title' => 'Overview - FSM Platform',
            'current_view' => 'overview',
            'overview_stats' => $this->getOverviewStats()
        ];
        
        return view('dashboard/overview', $data);
    }

    public function requestManagement()
    {
        $data = [
            'title' => 'Request Management - FSM Platform',
            'current_view' => 'request-management',
            'request_stats' => $this->getRequestStats()
        ];
        
        return view('dashboard/request-management', $data);
    }

    public function requestManagementDebug()
    {
        $data = [
            'title' => 'Request Management Debug - FSM Platform',
            'current_view' => 'request-management',
            'request_stats' => $this->getRequestStats()
        ];
        
        return view('dashboard/request-management-debug', $data);
    }

    public function serviceAppointmentManagement()
    {
        $data = [
            'title' => 'Service Appointment Management - FSM Platform',
            'current_view' => 'service-appointment-management',
            'appointment_stats' => $this->getAppointmentStats()
        ];
        
        return view('dashboard/service-appointment-management', $data);
    }

    public function technicianView()
    {
        $data = [
            'title' => 'Technician View - FSM Platform',
            'current_view' => 'technician-view',
            'technician_stats' => $this->getTechnicianStats()
        ];
        
        return view('dashboard/technician-view', $data);
    }

private function getOverviewStats()
    {
        try {
            $workOrderModel = new WorkOrderModel();
            $workOrderStats = $workOrderModel->getWorkOrderStats();
            
            // Calculate last month stats for comparisons
            $lastMonthStart = date('Y-m-01', strtotime('-1 month'));
            $lastMonthEnd = date('Y-m-t', strtotime('-1 month'));
            
            $lastMonthWorkOrders = (new WorkOrderModel())->where('created_at >=', $lastMonthStart)
                                                         ->where('created_at <=', $lastMonthEnd)
                                                         ->countAllResults();
            
            $lastMonthCompletedWorkOrders = (new WorkOrderModel())->where('status', 'completed')
                                                                  ->where('updated_at >=', $lastMonthStart)
                                                                  ->where('updated_at <=', $lastMonthEnd)
                                                                  ->countAllResults();
            
            $lastMonthAppointments = (new ServiceAppointmentModel())->where('created_at >=', $lastMonthStart)
                                                                    ->where('created_at <=', $lastMonthEnd)
                                                                    ->countAllResults();
            
            $lastMonthCompletedAppointments = (new ServiceAppointmentModel())->where('status', 'completed')
                                                                             ->where('updated_at >=', $lastMonthStart)
                                                                             ->where('updated_at <=', $lastMonthEnd)
                                                                             ->countAllResults();

            return [
                'total_work_orders' => $workOrderStats['total'],
                'completed_work_orders' => $workOrderStats['completed'],
                'total_service_appointments' => (new ServiceAppointmentModel())->whereNotIn('status', ['cancelled'])->countAllResults(),
                'completed_service_appointments' => (new ServiceAppointmentModel())->where('status', 'completed')->countAllResults(),
                'last_month_work_orders' => $lastMonthWorkOrders,
                'last_month_completed_work_orders' => $lastMonthCompletedWorkOrders,
                'last_month_appointments' => $lastMonthAppointments,
                'last_month_completed_appointments' => $lastMonthCompletedAppointments,
                'new_requests' => (new RequestModel())->where('status', 'pending')->orderBy('created_at', 'DESC')->findAll(5),
                'new_work_orders' => (new WorkOrderModel())->where('status', 'pending')->findAll(5),
                'approved_estimates' => (new EstimateModel())->where('status', 'accepted')->findAll(5),
                'estimates_waiting' => (new EstimateModel())->where('status', 'sent')->findAll(5)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Dashboard overview stats error: ' . $e->getMessage());
            // Return safe defaults if there's an error
            return [
                'total_work_orders' => 0,
                'completed_work_orders' => 0,
                'total_service_appointments' => 0,
                'completed_service_appointments' => 0,
                'last_month_work_orders' => 0,
                'last_month_completed_work_orders' => 0,
                'last_month_appointments' => 0,
                'last_month_completed_appointments' => 0,
                'new_requests' => [],
                'new_work_orders' => [],
                'approved_estimates' => [],
                'estimates_waiting' => []
            ];
        }
    }

private function getRequestStats()
    {
        try {
            return [
                'total_requests' => (new RequestModel())->countAllResults(),
                'converted_requests' => 0, // TODO: Add when request-to-work order conversion is implemented
                'completed_requests' => (new RequestModel())->where('status', 'completed')->countAllResults(),
                'cancelled_requests' => (new RequestModel())->where('status', 'cancelled')->countAllResults(),
                'new_requests' => (new RequestModel())->where('status', 'pending')->orderBy('created_at', 'DESC')->findAll(5),
                'new_estimates' => (new EstimateModel())->where('status', 'draft')->orderBy('created_at', 'DESC')->findAll(5),
                'completed_requests_list' => (new RequestModel())->where('status', 'completed')->orderBy('updated_at', 'DESC')->findAll(5),
                'cancelled_requests_list' => (new RequestModel())->where('status', 'cancelled')->orderBy('updated_at', 'DESC')->findAll(5),
                'approved_estimates' => (new EstimateModel())->where('status', 'accepted')->orderBy('updated_at', 'DESC')->findAll(5),
                'cancelled_estimates' => (new EstimateModel())->where('status', 'rejected')->orderBy('updated_at', 'DESC')->findAll(5)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Dashboard request stats error: ' . $e->getMessage());
            return [
                'total_requests' => 0,
                'converted_requests' => 0,
                'completed_requests' => 0,
                'cancelled_requests' => 0,
                'new_requests' => [],
                'new_estimates' => [],
                'completed_requests_list' => [],
                'cancelled_requests_list' => [],
                'approved_estimates' => [],
                'cancelled_estimates' => []
            ];
        }
    }

private function getAppointmentStats()
    {
        try {
            return [
                'total_appointments' => (new ServiceAppointmentModel())->countAllResults(),
                'in_progress_appointments' => (new ServiceAppointmentModel())->where('status', 'in_progress')->countAllResults(),
                'completed_appointments' => (new ServiceAppointmentModel())->where('status', 'completed')->countAllResults(),
                'cancelled_appointments' => (new ServiceAppointmentModel())->where('status', 'cancelled')->countAllResults(),
                'new_work_orders' => (new WorkOrderModel())->where('status', 'pending')->orderBy('created_at', 'DESC')->findAll(5),
                'scheduled_appointments' => (new ServiceAppointmentModel())->where('status', 'scheduled')->orderBy('appointment_date', 'ASC')->findAll(5),
                'dispatched_appointments' => [], // Not available - using scheduled instead
                'in_progress_appointments_list' => (new ServiceAppointmentModel())->where('status', 'in_progress')->orderBy('appointment_date', 'ASC')->findAll(5),
                'completed_appointments_list' => (new ServiceAppointmentModel())->where('status', 'completed')->orderBy('appointment_date', 'DESC')->findAll(5),
                'cancelled_appointments_list' => (new ServiceAppointmentModel())->where('status', 'cancelled')->orderBy('updated_at', 'DESC')->findAll(5),
                'terminated_appointments' => [] // Status not available in database schema
            ];
        } catch (\Exception $e) {
            log_message('error', 'Dashboard appointment stats error: ' . $e->getMessage());
            return [
                'total_appointments' => 0,
                'in_progress_appointments' => 0,
                'completed_appointments' => 0,
                'cancelled_appointments' => 0,
                'new_work_orders' => [],
                'scheduled_appointments' => [],
                'dispatched_appointments' => [],
                'in_progress_appointments_list' => [],
                'completed_appointments_list' => [],
                'cancelled_appointments_list' => [],
                'terminated_appointments' => []
            ];
        }
    }

private function getTechnicianStats()
    {
        try {
            $userId = session()->get('user_id'); // Get current logged-in technician
            
            // For demo purposes, if no user session, use technician ID 1 or 2
            if (!$userId) {
                $userId = 1; // Demo technician - can be changed later
            }

            // Get appointments for the current technician (user)
            $upcomingCount = (new ServiceAppointmentModel())->where('technician_id', $userId)
                                             ->where('status', 'scheduled')
                                             ->where('appointment_date >=', date('Y-m-d'))
                                             ->countAllResults();

            $inProgressCount = (new ServiceAppointmentModel())->where('technician_id', $userId)
                                               ->where('status', 'in_progress')
                                               ->countAllResults();

            $completedCount = (new ServiceAppointmentModel())->where('technician_id', $userId)
                                              ->where('status', 'completed')
                                              ->where('appointment_date >=', date('Y-m-d', strtotime('-6 months')))
                                              ->countAllResults();

            return [
                'upcoming_appointments' => $upcomingCount,
                'in_progress_appointments' => $inProgressCount,
                'completed_appointments' => $completedCount,
                'total_trips' => 0, // TODO: Implement when trips module is created
                'my_dispatched_appointments' => (new ServiceAppointmentModel())->where('technician_id', $userId)
                                                                 ->where('status', 'scheduled')
                                                                 ->orderBy('appointment_date', 'ASC')
                                                                 ->findAll(5),
                'my_in_progress_appointments' => (new ServiceAppointmentModel())->where('technician_id', $userId)
                                                                  ->where('status', 'in_progress')
                                                                  ->orderBy('appointment_date', 'ASC')
                                                                  ->findAll(5),
                'my_completed_appointments' => (new ServiceAppointmentModel())->where('technician_id', $userId)
                                                                ->where('status', 'completed')
                                                                ->orderBy('appointment_date', 'DESC')
                                                                ->findAll(5),
                'my_cancelled_appointments' => (new ServiceAppointmentModel())->where('technician_id', $userId)
                                                                ->where('status', 'cancelled')
                                                                ->orderBy('updated_at', 'DESC')
                                                                ->findAll(5)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Dashboard technician stats error: ' . $e->getMessage());
            return [
                'upcoming_appointments' => 0,
                'in_progress_appointments' => 0,
                'completed_appointments' => 0,
                'total_trips' => 0,
                'my_dispatched_appointments' => [],
                'my_in_progress_appointments' => [],
                'my_completed_appointments' => [],
                'my_cancelled_appointments' => []
            ];
        }
    }
}
