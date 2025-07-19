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
            
            // Create custom validation rules for new contacts (without id placeholder)
            $validationRules = [
                'first_name' => 'required|max_length[100]',
                'last_name' => 'required|max_length[100]',
                'email' => 'permit_empty|valid_email|max_length[255]|is_unique[contacts.email]',
                'phone' => 'permit_empty|max_length[50]',
                'mobile' => 'permit_empty|max_length[50]',
                'job_title' => 'permit_empty|max_length[100]',
                'company_id' => 'permit_empty|integer',
                'territory_id' => 'permit_empty|integer',
                'city' => 'permit_empty|max_length[100]',
                'state' => 'permit_empty|max_length[100]',
                'zip_code' => 'permit_empty|max_length[20]',
                'country' => 'permit_empty|max_length[100]',
                'status' => 'permit_empty|in_list[active,inactive]',
                'is_primary' => 'permit_empty|in_list[0,1]'
            ];
            
            // Use validation service directly
            $validation = \Config\Services::validation();
            $validation->setRules($validationRules);
            
            if (!$validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
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
            
            // Insert the contact (skip model validation since we already validated)
            $this->contactModel->skipValidation(true);
            if ($this->contactModel->insert($data)) {
                $this->contactModel->skipValidation(false);
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

    public function view($id)
    {
        $contact = $this->contactModel->getContactWithCompany($id);
        
        if (!$contact) {
            return redirect()->to('/customers/contacts')->with('error', 'Contact not found');
        }
        
        // Remove sensitive data if any
        // (No sensitive data in contacts table currently)
        
        $data = [
            'title' => $contact['first_name'] . ' ' . $contact['last_name'] . ' - Contact Details',
            'contact' => $contact,
            'assets' => [], // TODO: Get contact assets
            'activities' => [] // TODO: Get contact activities
        ];
        
        return view('contacts/view', $data);
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
            // Create custom validation rules for updates
            $validationRules = [
                'first_name' => 'required|max_length[100]',
                'last_name' => 'required|max_length[100]',
                'email' => 'permit_empty|valid_email|max_length[255]|is_unique[contacts.email,id,'.$id.']',
                'phone' => 'permit_empty|max_length[50]',
                'mobile' => 'permit_empty|max_length[50]',
                'job_title' => 'permit_empty|max_length[100]',
                'company_id' => 'permit_empty|integer',
                'territory_id' => 'permit_empty|integer',
                'city' => 'permit_empty|max_length[100]',
                'state' => 'permit_empty|max_length[100]',
                'zip_code' => 'permit_empty|max_length[20]',
                'country' => 'permit_empty|max_length[100]',
                'status' => 'permit_empty|in_list[active,inactive]',
                'is_primary' => 'permit_empty|in_list[0,1]'
            ];
            
            // Use validation service directly
            $validation = \Config\Services::validation();
            $validation->setRules($validationRules);
            
            if (!$validation->run($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
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
            
            // Update the contact (skip model validation)
            $this->contactModel->skipValidation(true);
            if ($this->contactModel->update($id, $data)) {
                $this->contactModel->skipValidation(false);
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

    /**
     * Export contacts to CSV
     */
    public function export()
    {
        $contacts = $this->contactModel->getContacts();
        
        // Set headers for CSV download
        $filename = 'contacts_export_' . date('Y-m-d_H-i-s') . '.csv';
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        // Create CSV content
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        $headers = [
            'first_name', 'last_name', 'email', 'phone', 'mobile', 'job_title',
            'company_name', 'address', 'city', 'state', 'zip_code', 'country', 'status', 'is_primary', 'notes'
        ];
        fputcsv($output, $headers);
        
        // CSV Data
        foreach ($contacts as $contact) {
            $row = [
                $contact['first_name'] ?? '',
                $contact['last_name'] ?? '',
                $contact['email'] ?? '',
                $contact['phone'] ?? '',
                $contact['mobile'] ?? '',
                $contact['job_title'] ?? '',
                $contact['company_name'] ?? '',
                $contact['address'] ?? '',
                $contact['city'] ?? '',
                $contact['state'] ?? '',
                $contact['zip_code'] ?? '',
                $contact['country'] ?? '',
                $contact['status'] ?? 'active',
                $contact['is_primary'] ?? '0',
                $contact['notes'] ?? ''
            ];
            fputcsv($output, $row);
        }
        
        fclose($output);
        return $this->response;
    }

    /**
     * Import contacts from CSV
     */
    public function import()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }
        
        $file = $this->request->getFile('import_file');
        $updateExisting = $this->request->getPost('update_existing');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please select a valid CSV file'
            ]);
        }
        
        // Check file type
        if ($file->getClientExtension() !== 'csv') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please upload a CSV file'
            ]);
        }
        
        // Process CSV file
        $csvData = [];
        $handle = fopen($file->getTempName(), 'r');
        
        if ($handle !== FALSE) {
            // Read header row
            $headers = fgetcsv($handle);
            
            // Expected headers
            $expectedHeaders = [
                'first_name', 'last_name', 'email', 'phone', 'mobile', 'job_title',
                'company_name', 'address', 'city', 'state', 'zip_code', 'country', 'status', 'is_primary', 'notes'
            ];
            
            // Validate headers
            if (!in_array('first_name', $headers) || !in_array('last_name', $headers)) {
                fclose($handle);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'CSV file must contain "first_name" and "last_name" columns'
                ]);
            }
            
            // Read data rows
            $rowNumber = 2; // Start from row 2 (after header)
            $errors = [];
            $processed = 0;
            $updated = 0;
            $created = 0;
            
            while (($row = fgetcsv($handle)) !== FALSE) {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $rowNumber++;
                    continue;
                }
                
                // Map CSV row to data array
                $data = [];
                foreach ($headers as $index => $header) {
                    $data[$header] = isset($row[$index]) ? trim($row[$index]) : '';
                }
                
                // Validate required fields
                if (empty($data['first_name']) || empty($data['last_name'])) {
                    $errors[] = "Row {$rowNumber}: First name and last name are required";
                    $rowNumber++;
                    continue;
                }
                
                // Set default values
                $data['status'] = !empty($data['status']) ? strtolower($data['status']) : 'active';
                $data['country'] = !empty($data['country']) ? $data['country'] : 'Canada';
                $data['is_primary'] = !empty($data['is_primary']) ? ($data['is_primary'] === '1' ? '1' : '0') : '0';
                $data['created_by'] = session()->get('user_id');
                
                // Validate status
                if (!in_array($data['status'], ['active', 'inactive'])) {
                    $data['status'] = 'active';
                }
                
                // Handle company lookup
                if (!empty($data['company_name'])) {
                    $company = $this->clientModel->where('client_name', $data['company_name'])->first();
                    if ($company) {
                        $data['company_id'] = $company['id'];
                    } else {
                        $errors[] = "Row {$rowNumber}: Company '{$data['company_name']}' not found";
                    }
                }
                unset($data['company_name']); // Remove company_name from data
                
                // Remove empty fields to avoid validation issues
                $data = array_filter($data, function($value) {
                    return $value !== '';
                });
                
                // Check if contact exists (by email or by first/last name)
                $existingContact = null;
                if (!empty($data['email'])) {
                    $existingContact = $this->contactModel->where('email', $data['email'])->first();
                } else {
                    $existingContact = $this->contactModel
                        ->where('first_name', $data['first_name'])
                        ->where('last_name', $data['last_name'])
                        ->first();
                }
                
                if ($existingContact) {
                    if ($updateExisting) {
                        // Update existing contact
                        unset($data['created_by']); // Don't update created_by
                        
                        // Skip validation for updates to avoid unique constraint issues
                        $this->contactModel->skipValidation(true);
                        if ($this->contactModel->update($existingContact['id'], $data)) {
                            $updated++;
                        } else {
                            $validationErrors = $this->contactModel->errors();
                            $errorMsg = !empty($validationErrors) ? implode(', ', $validationErrors) : 'Unknown error';
                            $errors[] = "Row {$rowNumber}: Failed to update contact '{$data['first_name']} {$data['last_name']}' - {$errorMsg}";
                        }
                        $this->contactModel->skipValidation(false);
                    } else {
                        // Skip existing contact
                        $errors[] = "Row {$rowNumber}: Contact '{$data['first_name']} {$data['last_name']}' already exists (skipped)";
                    }
                } else {
                    // Create new contact
                    // Skip validation for inserts to avoid unique constraint issues in bulk import
                    $this->contactModel->skipValidation(true);
                    if ($this->contactModel->insert($data)) {
                        $created++;
                    } else {
                        $validationErrors = $this->contactModel->errors();
                        $errorMsg = !empty($validationErrors) ? implode(', ', $validationErrors) : 'Unknown error';
                        $errors[] = "Row {$rowNumber}: Failed to create contact '{$data['first_name']} {$data['last_name']}' - {$errorMsg}";
                    }
                    $this->contactModel->skipValidation(false);
                }
                
                $processed++;
                $rowNumber++;
            }
            
            fclose($handle);
            
            // Prepare response message
            $message = "Import completed. ";
            if ($created > 0) {
                $message .= "{$created} contacts created. ";
            }
            if ($updated > 0) {
                $message .= "{$updated} contacts updated. ";
            }
            if (!empty($errors)) {
                $message .= count($errors) . " errors occurred.";
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $message,
                'details' => [
                    'processed' => $processed,
                    'created' => $created,
                    'updated' => $updated,
                    'errors' => $errors
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to read CSV file'
            ]);
        }
    }

    /**
     * Download CSV template for importing contacts
     */
    public function template()
    {
        // Set headers for CSV download
        $filename = 'contacts_template.csv';
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        // Create CSV content
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        $headers = [
            'first_name', 'last_name', 'email', 'phone', 'mobile', 'job_title',
            'company_name', 'address', 'city', 'state', 'zip_code', 'country', 'status', 'is_primary', 'notes'
        ];
        fputcsv($output, $headers);
        
        // Add sample data rows
        $sampleData = [
            [
                'John',
                'Smith',
                'john@abcsecurity.com',
                '555-0123',
                '555-0124',
                'Security Manager',
                'ABC Security Systems',
                '123 Main Street',
                'Toronto',
                'Ontario',
                'M5V 3A1',
                'Canada',
                'active',
                '1',
                'Primary contact for security systems'
            ],
            [
                'Jane',
                'Doe',
                'jane@techsolutions.com',
                '555-0789',
                '555-0790',
                'IT Director',
                'Tech Solutions Inc',
                '456 Business Ave',
                'Vancouver',
                'British Columbia',
                'V6B 2M9',
                'Canada',
                'active',
                '0',
                'Technology integration specialist'
            ]
        ];
        
        foreach ($sampleData as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        return $this->response;
    }
}
