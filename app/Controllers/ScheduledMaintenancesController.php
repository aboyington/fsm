<?php

namespace App\Controllers;

use App\Models\ScheduledMaintenanceModel;

class ScheduledMaintenancesController extends BaseController
{
    protected $maintenanceModel;

    public function __construct()
    {
        $this->maintenanceModel = new ScheduledMaintenanceModel();
    }

    public function index()
    {
        // Fetch all scheduled maintenances
        $maintenances = $this->maintenanceModel->findAll();

        return view('scheduled_maintenances/index', ['maintenances' => $maintenances]);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            if ($this->maintenanceModel->insert($data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Scheduled maintenance created successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'errors' => $this->maintenanceModel->errors()]);
            }
        }

        return redirect()->back();
    }
}

