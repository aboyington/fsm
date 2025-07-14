<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\ServiceRegistryModel;
use App\Models\AccountSequenceModel;
use App\Models\TerritoryModel;

class CompaniesController extends BaseController
{
    protected $clientModel;
    protected $serviceModel;
    protected $sequenceModel;
    protected $territoryModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->serviceModel = new ServiceRegistryModel();
        $this->sequenceModel = new AccountSequenceModel();
        $this->territoryModel = new TerritoryModel();
    }

    public function index()
    {
        $companies = $this->clientModel->getClients();
        $territories = $this->territoryModel->where('status', 'active')->findAll();
        
        $data = [
            'title' => 'Companies - FSM Platform',
            'companies' => $companies,
            'territories' => $territories,
            'total_companies' => count($companies),
            'active_companies' => count(array_filter($companies, function($c) { return $c['status'] === 'active'; })),
            'inactive_companies' => count(array_filter($companies, function($c) { return $c['status'] === 'inactive'; }))
        ];
        
        return view('companies/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Validate the data
            if (!$this->clientModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->clientModel->errors()
                ]);
            }
            
            // Set created_by to current user
            $data['created_by'] = session()->get('user_id');
            
            // Insert the company
            if ($this->clientModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Company created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create company'
                ]);
            }
        }
        
        return view('companies/create');
    }

    public function get($id)
    {
        $company = $this->clientModel->find($id);
        
        if (!$company) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Company not found'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $company
        ]);
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Find the company
            $company = $this->clientModel->find($id);
            if (!$company) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Company not found'
                ]);
            }
            
            // Validate the data
            if (!$this->clientModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->clientModel->errors()
                ]);
            }
            
            // Update the company
            if ($this->clientModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Company updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update company'
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
            // Check if company has any services
            $services = $this->serviceModel->where('client_id', $id)->findAll();
            
            if (!empty($services)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete company. It has associated services.'
                ]);
            }
            
            // Delete the company
            if ($this->clientModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Company deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete company'
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
        
        $companies = $this->clientModel->getClients($status, $searchTerm);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $companies
        ]);
    }
}
