<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationModel extends Model
{
    protected $table            = 'organizations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'company_name',
        'industry_type',
        'industry',
        'website',
        'phone',
        'mobile',
        'fax',
        'business_location',
        'street',
        'city',
        'state',
        'zip_code',
        'country',
        'time_zone',
        'date_format',
        'time_format',
        'distance_unit'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'company_name'     => 'required|min_length[2]|max_length[255]',
        'industry_type'    => 'permit_empty|in_list[Commercial,Industrial,Residential]',
        'industry'         => 'permit_empty|max_length[255]',
        'website'          => 'permit_empty|valid_url|max_length[255]',
        'phone'            => 'permit_empty|max_length[50]',
        'mobile'           => 'permit_empty|max_length[50]',
        'fax'              => 'permit_empty|max_length[50]',
        'business_location'=> 'permit_empty|max_length[100]',
        'street'           => 'permit_empty|max_length[255]',
        'city'             => 'permit_empty|max_length[100]',
        'state'            => 'permit_empty|max_length[100]',
        'zip_code'         => 'permit_empty|max_length[20]',
        'country'          => 'permit_empty|max_length[100]',
        'time_zone'        => 'required|max_length[100]',
        'date_format'      => 'required|in_list[MM/DD/YYYY,DD/MM/YYYY,YYYY-MM-DD]',
        'time_format'      => 'required|in_list[12 Hour,24 Hour]',
        'distance_unit'    => 'required|in_list[Miles,Kilometers]'
    ];

    protected $validationMessages   = [
        'company_name' => [
            'required' => 'Company name is required.',
            'min_length' => 'Company name must be at least 2 characters long.',
            'max_length' => 'Company name cannot exceed 255 characters.'
        ],
        'website' => [
            'valid_url' => 'Please enter a valid URL including http:// or https://'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get the organization settings
     * 
     * @param int $id Organization ID (default: 1)
     * @return array|null
     */
    public function getOrganization($id = 1)
    {
        return $this->find($id);
    }

    /**
     * Update organization settings
     * 
     * @param int $id Organization ID
     * @param array $data Data to update
     * @return bool
     */
    public function updateOrganization($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Get industry options
     * 
     * @return array
     */
    public function getIndustryOptions()
    {
        return [
            'Commercial' => [
                'Healthcare',
                'Education',
                'Retail',
                'Office Buildings',
                'Hospitality',
                'Security and CCTV',
                'Other'
            ],
            'Industrial' => [
                'Manufacturing',
                'Warehousing',
                'Food Processing',
                'Chemical',
                'Automotive',
                'Other'
            ],
            'Residential' => [
                'Single Family',
                'Multi-Family',
                'Apartments',
                'Condominiums',
                'Senior Living',
                'Other'
            ]
        ];
    }

    /**
     * Get timezone options
     * 
     * @return array
     */
    public function getTimezoneOptions()
    {
        return [
            'America/New_York' => 'Eastern Time (US & Canada)',
            'America/Chicago' => 'Central Time (US & Canada)',
            'America/Denver' => 'Mountain Time (US & Canada)',
            'America/Los_Angeles' => 'Pacific Time (US & Canada)',
            'America/Phoenix' => 'Arizona',
            'America/Toronto' => 'Eastern Time (Canada)',
            'America/Vancouver' => 'Pacific Time (Canada)',
            'Europe/London' => 'London',
            'Europe/Paris' => 'Paris',
            'Europe/Berlin' => 'Berlin',
            'Asia/Tokyo' => 'Tokyo',
            'Asia/Shanghai' => 'Beijing',
            'Australia/Sydney' => 'Sydney',
            'Pacific/Auckland' => 'Auckland'
        ];
    }
}
