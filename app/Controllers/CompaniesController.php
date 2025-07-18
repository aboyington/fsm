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
            // Create custom validation rules for updates
            $validationRules = [
                'client_name' => 'required|max_length[255]|is_unique[clients.client_name,id,'.$id.']',
                'contact_person' => 'permit_empty|max_length[255]',
                'email' => 'permit_empty|valid_email|max_length[255]',
                'website' => 'permit_empty|valid_url|max_length[255]',
                'company_type' => 'permit_empty|in_list[Analyst,Competitor,Customer,Distributor,Integrator,Investor,Other,Partner,Press,Prospect,Reseller,Supplier,Vendor]',
                'territory_id' => 'permit_empty|integer',
                'phone' => 'permit_empty|max_length[50]',
                'address' => 'permit_empty',
                'city' => 'permit_empty|max_length[100]',
                'state' => 'permit_empty|max_length[100]',
                'zip_code' => 'permit_empty|max_length[20]',
                'country' => 'permit_empty|max_length[100]',
                'status' => 'permit_empty|in_list[active,inactive]',
                'notes' => 'permit_empty'
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
            
            // Update the company (skip model validation)
            $this->clientModel->skipValidation(true);
            if ($this->clientModel->update($id, $data)) {
                $this->clientModel->skipValidation(false);
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

    /**
     * Export companies to CSV
     */
    public function export()
    {
        $companies = $this->clientModel->getClients();
        
        // Set headers for CSV download
        $filename = 'companies_export_' . date('Y-m-d_H-i-s') . '.csv';
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        // Create CSV content
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        $headers = [
            'client_name', 'contact_person', 'email', 'phone', 'website', 'company_type',
            'address', 'city', 'state', 'zip_code', 'country', 'status', 'notes'
        ];
        fputcsv($output, $headers);
        
        // CSV Data
        foreach ($companies as $company) {
            $row = [
                $company['client_name'] ?? '',
                $company['contact_person'] ?? '',
                $company['email'] ?? '',
                $company['phone'] ?? '',
                $company['website'] ?? '',
                $company['company_type'] ?? '',
                $company['address'] ?? '',
                $company['city'] ?? '',
                $company['state'] ?? '',
                $company['zip_code'] ?? '',
                $company['country'] ?? '',
                $company['status'] ?? 'active',
                $company['notes'] ?? ''
            ];
            fputcsv($output, $row);
        }
        
        fclose($output);
        return $this->response;
    }

    /**
     * Import companies from CSV
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
                'client_name', 'contact_person', 'email', 'phone', 'website', 'company_type',
                'address', 'city', 'state', 'zip_code', 'country', 'status', 'notes'
            ];
            
            // Validate headers
            if (!in_array('client_name', $headers)) {
                fclose($handle);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'CSV file must contain a "client_name" column'
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
                if (empty($data['client_name'])) {
                    $errors[] = "Row {$rowNumber}: Company name is required";
                    $rowNumber++;
                    continue;
                }
                
                // Set default values
                $data['status'] = !empty($data['status']) ? strtolower($data['status']) : 'active';
                $data['country'] = !empty($data['country']) ? $data['country'] : 'Canada';
                $data['created_by'] = session()->get('user_id');
                
                // Validate status
                if (!in_array($data['status'], ['active', 'inactive'])) {
                    $data['status'] = 'active';
                }
                
                // Remove empty fields to avoid validation issues
                $data = array_filter($data, function($value) {
                    return $value !== '';
                });
                
                // Check if company exists
                $existingCompany = $this->clientModel->where('client_name', $data['client_name'])->first();
                
                if ($existingCompany) {
                    if ($updateExisting) {
                        // Update existing company
                        unset($data['created_by']); // Don't update created_by
                        
                        // Skip validation for updates to avoid unique constraint issues
                        $this->clientModel->skipValidation(true);
                        if ($this->clientModel->update($existingCompany['id'], $data)) {
                            $updated++;
                        } else {
                            $validationErrors = $this->clientModel->errors();
                            $errorMsg = !empty($validationErrors) ? implode(', ', $validationErrors) : 'Unknown error';
                            $errors[] = "Row {$rowNumber}: Failed to update company '{$data['client_name']}' - {$errorMsg}";
                        }
                        $this->clientModel->skipValidation(false);
                    } else {
                        // Skip existing company
                        $errors[] = "Row {$rowNumber}: Company '{$data['client_name']}' already exists (skipped)";
                    }
                } else {
                    // Create new company
                    // Skip validation for inserts to avoid unique constraint issues in bulk import
                    $this->clientModel->skipValidation(true);
                    if ($this->clientModel->insert($data)) {
                        $created++;
                    } else {
                        $validationErrors = $this->clientModel->errors();
                        $errorMsg = !empty($validationErrors) ? implode(', ', $validationErrors) : 'Unknown error';
                        $errors[] = "Row {$rowNumber}: Failed to create company '{$data['client_name']}' - {$errorMsg}";
                    }
                    $this->clientModel->skipValidation(false);
                }
                
                $processed++;
                $rowNumber++;
            }
            
            fclose($handle);
            
            // Prepare response message
            $message = "Import completed. ";
            if ($created > 0) {
                $message .= "{$created} companies created. ";
            }
            if ($updated > 0) {
                $message .= "{$updated} companies updated. ";
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
     * Download CSV template for importing companies
     */
    public function template()
    {
        // Set headers for CSV download
        $filename = 'companies_template.csv';
        $this->response->setHeader('Content-Type', 'text/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        
        // Create CSV content
        $output = fopen('php://output', 'w');
        
        // CSV Headers
        $headers = [
            'client_name', 'contact_person', 'email', 'phone', 'website', 'company_type',
            'address', 'city', 'state', 'zip_code', 'country', 'status', 'notes'
        ];
        fputcsv($output, $headers);
        
        // Add sample data rows
        $sampleData = [
            [
                'ABC Security Systems',
                'John Smith',
                'john@abcsecurity.com',
                '555-0123',
                'https://www.abcsecurity.com',
                'Customer',
                '123 Main Street',
                'Toronto',
                'Ontario',
                'M5V 3A1',
                'Canada',
                'active',
                'Premier security solutions provider'
            ],
            [
                'Tech Solutions Inc',
                'Jane Doe',
                'jane@techsolutions.com',
                '555-0789',
                'https://www.techsolutions.com',
                'Partner',
                '456 Business Ave',
                'Vancouver',
                'British Columbia',
                'V6B 2M9',
                'Canada',
                'active',
                'Technology integration specialists'
            ]
        ];
        
        foreach ($sampleData as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        return $this->response;
    }
}
