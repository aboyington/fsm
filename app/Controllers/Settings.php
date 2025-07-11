<?php

namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\FiscalYearModel;

class Settings extends BaseController
{
    protected $organizationModel;
    protected $fiscalYearModel;
    
    public function __construct()
    {
        $this->organizationModel = new OrganizationModel();
        $this->fiscalYearModel = new FiscalYearModel();
    }
    
    public function index()
    {
        return redirect()->to('/settings/organization');
    }

    public function organization()
    {
        $organization = $this->organizationModel->getOrganization();
        
        $data = [
            'title' => 'Organization Profile',
            'activeTab' => 'organization',
            'organization' => $organization,
            'industryOptions' => $this->organizationModel->getIndustryOptions(),
            'timezoneOptions' => $this->organizationModel->getTimezoneOptions()
        ];
        
        return view('settings/organization', $data);
    }

    public function updateOrganization()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/settings/organization');
        }
        
        // Get POST data
        $data = $this->request->getPost();
        
        // Remove CSRF token from data
        unset($data['csrf_test_name']);
        
        // Validate and update
        if ($this->organizationModel->updateOrganization(1, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Organization details updated successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update organization details',
                'errors' => $this->organizationModel->errors()
            ]);
        }
    }

    public function general()
    {
        $data = [
            'title' => 'General Settings',
            'activeTab' => 'general'
        ];
        
        return view('settings/general', $data);
    }

    public function currency()
    {
        $data = [
            'title' => 'Currency Settings',
            'activeTab' => 'currency'
        ];
        
        return view('settings/currency', $data);
    }

    public function users()
    {
        $data = [
            'title' => 'Users',
            'activeTab' => 'users'
        ];
        
        return view('settings/users', $data);
    }

    public function territories()
    {
        $data = [
            'title' => 'Territories',
            'activeTab' => 'territories'
        ];
        
        return view('settings/territories', $data);
    }

    public function skills()
    {
        $data = [
            'title' => 'Skills',
            'activeTab' => 'skills'
        ];
        
        return view('settings/skills', $data);
    }

    public function holiday()
    {
        $data = [
            'title' => 'Holiday',
            'activeTab' => 'holiday'
        ];
        
        return view('settings/holiday', $data);
    }

    public function billing()
    {
        $data = [
            'title' => 'Billing Setup',
            'activeTab' => 'billing'
        ];
        
        return view('settings/billing', $data);
    }

    public function taxSettings()
    {
        $data = [
            'title' => 'Tax Settings',
            'activeTab' => 'tax-settings'
        ];
        
        return view('settings/tax-settings', $data);
    }
    
    public function fiscalYear()
    {
        $fiscalYear = $this->fiscalYearModel->getByOrganizationId(1);
        
        $data = [
            'title' => 'Fiscal Year Settings',
            'activeTab' => 'organization',
            'fiscalYear' => $fiscalYear,
            'monthOptions' => $this->fiscalYearModel->getMonthOptions(),
            'dayOptions' => $this->fiscalYearModel->getDayOptions(),
            'yearOptions' => $this->fiscalYearModel->getYearOptions()
        ];
        
        return view('settings/fiscal_year', $data);
    }
    
    public function updateFiscalYear()
    {
        // Check if user is logged in
        if (!session()->get('auth_token')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Unauthorized: Please login to continue'
                ])->setStatusCode(401);
            }
            return redirect()->to('/login');
        }
        
        // Check if request is AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/settings/fiscal-year');
        }
        
        // Get POST data
        $data = $this->request->getPost();
        
        // Remove CSRF token from data
        unset($data['csrf_test_name']);
        
        // Remove month/day fields that were used for UI
        unset($data['fiscal_start_month']);
        unset($data['fiscal_start_day']);
        unset($data['fiscal_end_month']);
        unset($data['fiscal_end_day']);
        
        // Add organization ID
        $data['organization_id'] = 1;
        
        // Check if fiscal year exists
        $existingFiscalYear = $this->fiscalYearModel->getByOrganizationId(1);
        
        if ($existingFiscalYear) {
            // Update existing record
            if ($this->fiscalYearModel->update($existingFiscalYear['id'], $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Fiscal year settings updated successfully'
                ]);
            }
        } else {
            // Insert new record
            if ($this->fiscalYearModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Fiscal year settings created successfully'
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update fiscal year settings',
            'errors' => $this->fiscalYearModel->errors()
        ]);
    }
}
