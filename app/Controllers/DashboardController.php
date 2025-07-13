<?php

namespace App\Controllers;

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
        return [
            'total_work_orders' => 0,
            'completed_work_orders' => 0,
            'total_service_appointments' => 0,
            'completed_service_appointments' => 0,
            'new_requests' => [],
            'new_work_orders' => [],
            'approved_estimates' => [],
            'estimates_waiting' => []
        ];
    }

    private function getRequestStats()
    {
        return [
            'total_requests' => 0,
            'converted_requests' => 0,
            'completed_requests' => 0,
            'cancelled_requests' => 0,
            'new_requests' => [],
            'new_estimates' => [],
            'completed_requests' => [],
            'cancelled_requests' => [],
            'approved_estimates' => [],
            'cancelled_estimates' => []
        ];
    }

    private function getAppointmentStats()
    {
        return [
            'total_appointments' => 0,
            'in_progress_appointments' => 0,
            'completed_appointments' => 0,
            'cancelled_appointments' => 0,
            'new_work_orders' => [],
            'scheduled_appointments' => [],
            'dispatched_appointments' => [],
            'in_progress_appointments' => [],
            'completed_appointments' => [],
            'cancelled_appointments' => [],
            'terminated_appointments' => []
        ];
    }

    private function getTechnicianStats()
    {
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
