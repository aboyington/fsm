<?php

namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Models\FiscalYearModel;
use App\Models\BusinessHoursModel;

class Settings extends BaseController
{
    protected $organizationModel;
    protected $fiscalYearModel;
    protected $businessHoursModel;
    
    public function __construct()
    {
        $this->organizationModel = new OrganizationModel();
        $this->fiscalYearModel = new FiscalYearModel();
        $this->businessHoursModel = new BusinessHoursModel();
    }
    
    public function index()
    {
        return redirect()->to('/settings/organization');
    }

    public function organization()
    {
        $organization = $this->organizationModel->getOrganization();
        $businessHours = $this->businessHoursModel->getByOrganizationId(1);
        
        $data = [
            'title' => 'Organization Profile',
            'activeTab' => 'organization',
            'organization' => $organization,
            'businessHours' => $businessHours,
            'businessHoursFormatted' => $this->businessHoursModel->getFormattedHours($businessHours),
            'industryOptions' => $this->organizationModel->getIndustryOptions(),
            'timezoneOptions' => $this->organizationModel->getTimezoneOptions(),
            'weekdays' => $this->businessHoursModel->getWeekdays(),
            'timeOptions' => $this->businessHoursModel->getTimeOptions()
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
        $currencyModel = new \App\Models\CurrencyModel();
        
        $data = [
            'title' => 'Currency Settings',
            'activeTab' => 'currency',
            'currencies' => $currencyModel->findAll()
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
    
    public function updateBusinessHours()
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
        
        // Add organization ID
        $data['organization_id'] = 1;
        
        // If business hours type is not custom, clear all time fields
        if ($data['business_hours_type'] !== 'custom') {
            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($weekdays as $day) {
                $data[$day . '_start'] = null;
                $data[$day . '_end'] = null;
            }
        }
        
        // Check if business hours exists
        $existingBusinessHours = $this->businessHoursModel->getByOrganizationId(1);
        
        if ($existingBusinessHours) {
            // Update existing record
            if ($this->businessHoursModel->update($existingBusinessHours['id'], $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Business hours updated successfully'
                ]);
            }
        } else {
            // Insert new record
            if ($this->businessHoursModel->insert($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Business hours created successfully'
                ]);
            }
        }
        
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update business hours',
            'errors' => $this->businessHoursModel->errors()
        ]);
    }
    
    public function storeCurrency()
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
        
        $currencyModel = new \App\Models\CurrencyModel();
        
        // Get JSON data
        $json = $this->request->getJSON();
        
        $data = [
            'name' => $json->name,
            'symbol' => $json->symbol,
            'iso_code' => strtoupper($json->iso_code),
            'exchange_rate' => $json->exchange_rate,
            'thousand_separator' => $json->thousand_separator,
            'decimal_spaces' => $json->decimal_spaces,
            'decimal_separator' => $json->decimal_separator,
            'is_active' => 1
        ];
        
        if ($currencyModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Currency added successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to add currency', 'errors' => $currencyModel->errors()]);
        }
    }
    
    public function updateCurrency($id = null)
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
        
        $currencyModel = new \App\Models\CurrencyModel();
        
        // Get JSON data
        $json = $this->request->getJSON();
        
        $data = [
            'id' => $id,
            'exchange_rate' => $json->exchange_rate,
            'thousand_separator' => $json->thousand_separator,
            'decimal_spaces' => $json->decimal_spaces,
            'decimal_separator' => $json->decimal_separator
        ];
        
        if ($currencyModel->save($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Currency updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update currency', 'errors' => $currencyModel->errors()]);
        }
    }
    
    public function getCurrency($id)
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
        
        $currencyModel = new \App\Models\CurrencyModel();
        $currency = $currencyModel->find($id);
        
        if ($currency) {
            return $this->response->setJSON(['success' => true, 'currency' => $currency]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Currency not found'])->setStatusCode(404);
        }
    }
}
