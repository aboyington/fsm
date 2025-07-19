<?php

namespace App\Controllers;

use App\Models\RequestModel;
use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\TerritoryModel;
use App\Models\UserSessionModel;

class RequestsController extends BaseController
{
    protected $requestModel;
    protected $clientModel;
    protected $contactModel;
    protected $territoryModel;
    protected $userSessionModel;

    public function __construct()
    {
        $this->requestModel = new RequestModel();
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->territoryModel = new TerritoryModel();
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
        $requests = $this->requestModel->getRequests();
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $territories = $this->territoryModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        
        $data = [
            'title' => 'Requests - FSM Platform',
            'requests' => $requests,
            'companies' => $companies,
            'territories' => $territories,
            'contacts' => $contacts,
            'total_requests' => count($requests),
            'pending_requests' => count(array_filter($requests, function($r) { return $r['status'] === 'pending'; })),
            'in_progress_requests' => count(array_filter($requests, function($r) { return $r['status'] === 'in_progress'; })),
            'on_hold_requests' => count(array_filter($requests, function($r) { return $r['status'] === 'on_hold'; })),
            'completed_requests' => count(array_filter($requests, function($r) { return $r['status'] === 'completed'; }))
        ];
        
        return view('requests/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Validate the data
            if (!$this->requestModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->requestModel->errors()
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
            
            // Insert the request
            if ($this->requestModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Request created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create request'
                ]);
            }
        }
        
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        return view('requests/create', ['companies' => $companies, 'contacts' => $contacts]);
    }

    public function get($id)
    {
        $request = $this->requestModel->getRequestWithDetails($id);
        
        if (!$request) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Request not found'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $request
        ]);
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Find the request
            $request = $this->requestModel->find($id);
            if (!$request) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Request not found'
                ]);
            }
            
            // Validate the data
            if (!$this->requestModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->requestModel->errors()
                ]);
            }
            
            // Update the request
            if ($this->requestModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Request updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update request'
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
            $request = $this->requestModel->find($id);
            
            if (!$request) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Request not found'
                ]);
            }
            
            // Delete the request
            if ($this->requestModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Request deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete request'
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
        $priority = $this->request->getGet('priority');
        
        $requests = $this->requestModel->getRequests($status, $searchTerm, $companyId, $priority);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function updateStatus($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $status = $data['status'] ?? '';
            
            $request = $this->requestModel->find($id);
            
            if (!$request) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Request not found'
                ]);
            }
            
            if ($this->requestModel->update($id, ['status' => $status])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Request status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update request status'
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
        $requests = $this->requestModel->getRequestsByCompany($companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function view($id)
    {
        $request = $this->requestModel->getRequestWithDetails($id);
        
        if (!$request) {
            return redirect()->to('/work-order-management/requests')->with('error', 'Request not found');
        }
        
        $data = [
            'title' => 'REQ' . str_pad($id, 3, '0', STR_PAD_LEFT) . ' - Request Details',
            'request' => $request
        ];
        
        return view('requests/view', $data);
    }
}
