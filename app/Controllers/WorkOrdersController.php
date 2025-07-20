<?php

namespace App\Controllers;

use App\Models\WorkOrderModel;
use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\AssetModel;
use App\Models\ServiceRegistryModel;
use App\Models\ServicesModel;
use App\Models\PartsModel;
use App\Models\SkillModel;
use App\Models\UserSessionModel;

class WorkOrdersController extends BaseController
{
    protected $workOrderModel;
    protected $clientModel;
    protected $contactModel;
    protected $assetModel;
    protected $serviceRegistryModel;
    protected $servicesModel;
    protected $partsModel;
    protected $skillModel;
    protected $userSessionModel;

    public function __construct()
    {
        $this->workOrderModel = new WorkOrderModel();
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->assetModel = new AssetModel();
        $this->serviceRegistryModel = new ServiceRegistryModel();
        $this->servicesModel = new ServicesModel();
        $this->partsModel = new PartsModel();
        $this->skillModel = new SkillModel();
        $this->userSessionModel = new UserSessionModel();
    }
    
    /**
     * Get current user ID from session
     */
    protected function getCurrentUserId()
    {
        $authToken = session()->get('auth_token');
        
        if (!$authToken) {
            return null;
        }
        
        $session = $this->userSessionModel->validateSession($authToken);
        
        if ($session) {
            return $session['user_id'];
        }
        
        return null;
    }

    public function index()
    {
        $workOrders = $this->workOrderModel->getWorkOrders();
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        $assets = $this->assetModel->findAll();
        
        // Load active services from product_skus table (category = 'SRV')
        $services = $this->servicesModel->getAllServices(['status' => 'active']);
        
        // Load active parts from product_skus table (category = 'PRT')
        $parts = $this->partsModel->getAllParts(['status' => 'active']);
        
        // Load active skills
        $skills = $this->skillModel->getActiveSkills();
        
        $data = [
            'title' => 'Work Orders - FSM Platform',
            'workOrders' => $workOrders,
            'companies' => $companies,
            'contacts' => $contacts,
            'assets' => $assets,
            'services' => $services,
            'parts' => $parts,
            'skills' => $skills,
            'total_work_orders' => count($workOrders),
            'pending_work_orders' => count(array_filter($workOrders, function($w) { return $w['status'] === 'pending'; })),
            'in_progress_work_orders' => count(array_filter($workOrders, function($w) { return $w['status'] === 'in_progress'; })),
            'completed_work_orders' => count(array_filter($workOrders, function($w) { return $w['status'] === 'completed'; })),
        ];
        
        return view('work_orders/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Validate the data
            if (!$this->workOrderModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->workOrderModel->errors()
                ]);
            }
            
            // Set created_by to current user
            $userId = $this->getCurrentUserId();
            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }
            $data['created_by'] = $userId;
            
            // Insert the work order
            if ($this->workOrderModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create work order'
                ]);
            }
        }
        
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        $assets = $this->assetModel->findAll();
        $services = $this->serviceModel->findAll();
        
        return view('work_orders/create', [
            'companies' => $companies, 
            'contacts' => $contacts,
            'assets' => $assets,
            'services' => $services
        ]);
    }

    public function get($id)
    {
        $workOrder = $this->workOrderModel->getWorkOrderWithDetails($id);
        
        if (!$workOrder) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Work Order not found'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $workOrder
        ]);
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Find the work order
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ]);
            }
            
            // Validate the data
            if (!$this->workOrderModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->workOrderModel->errors()
                ]);
            }
            
            // Update the work order
            if ($this->workOrderModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update work order'
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
            $workOrder = $this->workOrderModel->find($id);
            
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ]);
            }
            
            // Delete the work order
            if ($this->workOrderModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete work order'
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
        $companyId = $this->request->getGet('company_id');
        
        $workOrders = $this->workOrderModel->getWorkOrders($status, $searchTerm, $companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $workOrders
        ]);
    }

    public function updateStatus($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $status = $data['status'] ?? '';
            
            $workOrder = $this->workOrderModel->find($id);
            
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ]);
            }
            
            if ($this->workOrderModel->update($id, ['status' => $status])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update work order status'
                ]);
            }
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }

    public function getByCompany($companyId)
    {
        $workOrders = $this->workOrderModel->getWorkOrdersByCompany($companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $workOrders
        ]);
    }
    
}
