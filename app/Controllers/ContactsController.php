<?php

namespace App\Controllers;

use App\Models\ContactModel;
use App\Models\ClientModel;
use App\Models\TerritoryModel;

class ContactsController extends BaseController
{
    protected $contactModel;
    protected $clientModel;
    protected $territoryModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
        $this->clientModel = new ClientModel();
        $this->territoryModel = new TerritoryModel();
    }

    public function index()
    {
        $contacts = $this->contactModel->getContacts();
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $territories = $this->territoryModel->where('status', 'active')->findAll();
        
        $data = [
            'title' => 'Contacts - FSM Platform',
            'contacts' => $contacts,
            'companies' => $companies,
            'territories' => $territories,
            'total_contacts' => count($contacts),
            'active_contacts' => count(array_filter($contacts, function($c) { return $c['status'] === 'active'; })),
            'inactive_contacts' => count(array_filter($contacts, function($c) { return $c['status'] === 'inactive'; }))
        ];
        
        return view('contacts/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Validate the data
            if (!$this->contactModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->contactModel->errors()
                ]);
            }
            
            // Set created_by to current user
            $data['created_by'] = session()->get('user_id');
            
            // Handle primary contact logic
            if (isset($data['is_primary']) && $data['is_primary'] == '1' && !empty($data['company_id'])) {
                // Remove primary status from other contacts in this company
                $this->contactModel->where('company_id', $data['company_id'])
                                  ->set('is_primary', 0)
                                  ->update();
            }
            
            // Insert the contact
            if ($this->contactModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contact created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create contact'
                ]);
            }
        }
        
        $companies = $this->clientModel->where('status', 'active')->findAll();
        return view('contacts/create', ['companies' => $companies]);
    }

    public function get($id)
    {
        $contact = $this->contactModel->getContactWithCompany($id);
        
        if (!$contact) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Contact not found'
            ]);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $contact
        ]);
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Find the contact
            $contact = $this->contactModel->find($id);
            if (!$contact) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contact not found'
                ]);
            }
            
            // Validate the data
            if (!$this->contactModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->contactModel->errors()
                ]);
            }
            
            // Handle primary contact logic
            if (isset($data['is_primary']) && $data['is_primary'] == '1' && !empty($data['company_id'])) {
                // Remove primary status from other contacts in this company
                $this->contactModel->where('company_id', $data['company_id'])
                                  ->where('id !=', $id)
                                  ->set('is_primary', 0)
                                  ->update();
            }
            
            // Update the contact
            if ($this->contactModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contact updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update contact'
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
            $contact = $this->contactModel->find($id);
            
            if (!$contact) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contact not found'
                ]);
            }
            
            // Delete the contact
            if ($this->contactModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contact deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete contact'
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
        
        $contacts = $this->contactModel->getContacts($status, $searchTerm, $companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $contacts
        ]);
    }

    public function setPrimary($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $contact = $this->contactModel->find($id);
            
            if (!$contact) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contact not found'
                ]);
            }
            
            if (empty($contact['company_id'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Contact must be associated with a company to be set as primary'
                ]);
            }
            
            if ($this->contactModel->setPrimaryContact($id, $contact['company_id'])) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Contact set as primary successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to set contact as primary'
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
        $contacts = $this->contactModel->getContactsByCompany($companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $contacts
        ]);
    }
}
