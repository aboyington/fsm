<?php

namespace App\Controllers;

use App\Models\EstimateModel;
use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\AssetModel;
use App\Models\ServiceRegistryModel;

class EstimatesController extends BaseController
{
    protected $estimateModel;
    protected $clientModel;
    protected $contactModel;
    protected $assetModel;
    protected $serviceModel;

    public function __construct()
    {
        $this->estimateModel = new EstimateModel();
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->assetModel = new AssetModel();
        $this->serviceModel = new ServiceRegistryModel();
    }

    public function index()
    {
        $estimates = $this->estimateModel->getEstimates();
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        $assets = $this->assetModel->findAll();
        $services = $this->serviceModel->findAll();
        
        $data = [
            'title' => 'Estimates - FSM Platform',
            'estimates' => $estimates,
            'companies' => $companies,
            'contacts' => $contacts,
            'assets' => $assets,
            'services' => $services,
            'total_estimates' => count($estimates),
            'draft_estimates' => count(array_filter($estimates, function($e) { return $e['status'] === 'draft'; })),
            'sent_estimates' => count(array_filter($estimates, function($e) { return $e['status'] === 'sent'; })),
            'accepted_estimates' => count(array_filter($estimates, function($e) { return $e['status'] === 'accepted'; })),
            'rejected_estimates' => count(array_filter($estimates, function($e) { return $e['status'] === 'rejected'; }))
        ];
        
        return view('estimates/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Validate the data
            if (!$this->estimateModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->estimateModel->errors()
                ]);
            }
            
            // Set created_by to current user
            $data['created_by'] = session()->get('user_id');
            
            // Insert the estimate
            if ($this->estimateModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estimate created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create estimate'
                ]);
            }
        }
        
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        $assets = $this->assetModel->findAll();
        $services = $this->serviceModel->findAll();
        
        return view('estimates/create', [
            'companies' => $companies, 
            'contacts' => $contacts,
            'assets' => $assets,
            'services' => $services
        ]);
    }

    public function get($id)
    {
        $estimate = $this->estimateModel->getEstimateWithDetails($id);
        
        if (!$estimate) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Estimate not found'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $estimate
        ]);
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Find the estimate
            $estimate = $this->estimateModel->find($id);
            if (!$estimate) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estimate not found'
                ]);
            }
            
            // Validate the data
            if (!$this->estimateModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->estimateModel->errors()
                ]);
            }
            
            // Update the estimate
            if ($this->estimateModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estimate updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update estimate'
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
            $estimate = $this->estimateModel->find($id);
            
            if (!$estimate) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estimate not found'
                ]);
            }
            
            // Delete the estimate
            if ($this->estimateModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estimate deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete estimate'
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
        
        $estimates = $this->estimateModel->getEstimates($status, $searchTerm, $companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $estimates
        ]);
    }

    public function updateStatus($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $status = $data['status'] ?? '';
            
            $estimate = $this->estimateModel->find($id);
            
            if (!$estimate) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estimate not found'
                ]);
            }
            
            if ($this->estimateModel->update($id, ['status' => $status])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Estimate status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update estimate status'
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
        $estimates = $this->estimateModel->getEstimatesByCompany($companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $estimates
        ]);
    }
}