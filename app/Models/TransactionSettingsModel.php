<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionSettingsModel extends Model
{
    protected $table = 'transaction_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'setting_key' => 'required|max_length[100]',
        'setting_value' => 'permit_empty',
        'setting_type' => 'required|in_list[boolean,integer,string,decimal]'
    ];

    protected $validationMessages = [
        'setting_key' => [
            'required' => 'Setting key is required',
            'max_length' => 'Setting key cannot exceed 100 characters'
        ]
    ];

    /**
     * Get all transaction settings as key-value pairs
     */
    public function getSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->castValue($setting['setting_value'], $setting['setting_type']);
        }
        
        return $result;
    }
    
    /**
     * Get a specific setting value
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->where('setting_key', $key)->first();
        
        if ($setting) {
            return $this->castValue($setting['setting_value'], $setting['setting_type']);
        }
        
        return $default;
    }
    
    /**
     * Update or create a setting
     */
    public function setSetting($key, $value, $type = 'string', $description = null)
    {
        $existing = $this->where('setting_key', $key)->first();
        
        $data = [
            'setting_key' => $key,
            'setting_value' => $this->stringifyValue($value, $type),
            'setting_type' => $type,
            'description' => $description
        ];
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->save($data);
        }
    }
    
    /**
     * Update multiple settings at once
     */
    public function updateSettings($settings)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        foreach ($settings as $key => $config) {
            $value = $config['value'];
            $type = $config['type'] ?? 'string';
            $description = $config['description'] ?? null;
            
            $this->setSetting($key, $value, $type, $description);
        }
        
        $db->transComplete();
        
        return $db->transStatus();
    }
    
    /**
     * Cast string value to appropriate type
     */
    private function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'decimal':
                return (float) $value;
            case 'string':
            default:
                return (string) $value;
        }
    }
    
    /**
     * Convert value to string for storage
     */
    private function stringifyValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return $value ? '1' : '0';
            case 'integer':
            case 'decimal':
                return (string) $value;
            case 'string':
            default:
                return (string) $value;
        }
    }
    
    /**
     * Initialize default settings
     */
    public function initializeDefaults()
    {
        $defaults = [
            'allow_roundoff_transactions' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Allow roundoff for transactions'
            ],
            'password_protect_exported_files' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Password protect exported files'
            ],
            'mobile_checkin_preference' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Mobile App Check-In Preference'
            ],
            'allow_pricing_field_agent' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Allow Field Agent to see pricing'
            ],
            'allow_technicians_raise_invoices' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Allow technicians to raise invoices'
            ],
            'field_agent_appointment_confirmation' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Field Agent Appointment Confirmation'
            ],
            'minimum_interval_next_appointment' => [
                'value' => 1,
                'type' => 'integer',
                'description' => 'Minimum interval for next appointment (hours)'
            ],
            'allow_overlapping_appointments' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Allow Overlapping Appointments'
            ],
            'allow_overlapping_with_warning' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Allow Overlapping with Warning'
            ],
            'territory_restriction_appointment' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Territory Restriction for Appointment Scheduling'
            ],
            'auto_complete_work_order' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Automatically complete a work order'
            ],
            'prompt_complete_work_order' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Prompt to complete work order'
            ],
            'service_report_required_sa_completion' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Service Report required for SA completion'
            ],
            'jobsheets_completion_required_sa' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Jobsheets completion required for SA completion'
            ],
            'auto_pause_timesheet' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Auto Pause timesheet'
            ],
            'auto_pause_time' => [
                'value' => '17:59',
                'type' => 'string',
                'description' => 'Auto Pause Time'
            ],
            'allow_overlapping_timesheet_entries' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Allow Overlapping or Concurrent Timesheet Entries'
            ],
            'hide_attachments_service_reports' => [
                'value' => false,
                'type' => 'boolean',
                'description' => 'Hide attachments from service reports'
            ],
            'remove_customer_signature' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Remove customer signature on editing service reports'
            ],
            'estimate_email_approval' => [
                'value' => true,
                'type' => 'boolean',
                'description' => 'Estimate - Email Approval'
            ],
            'email_approval_expiry_days' => [
                'value' => 7,
                'type' => 'integer',
                'description' => 'Expiry Time for Email Approval Link (Days)'
            ],
            'terms_conditions_estimate' => [
                'value' => '',
                'type' => 'string',
                'description' => 'Terms & Conditions for estimate template'
            ],
            'customer_notes_estimate' => [
                'value' => '',
                'type' => 'string',
                'description' => 'Customer Notes for estimate template'
            ]
        ];
        
        foreach ($defaults as $key => $config) {
            // Only set if doesn't exist
            if (!$this->where('setting_key', $key)->first()) {
                $this->setSetting($key, $config['value'], $config['type'], $config['description']);
            }
        }
        
        return true;
    }
}
