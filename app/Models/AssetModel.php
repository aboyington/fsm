<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table = 'assets';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'asset_name',
        'asset_number', 
        'description',
        'product',
        'parent_asset',
        'giai',
        'ordered_date',
        'installation_date',
        'purchased_date',
        'warranty_expiration',
        'company_id',
        'contact_id',
        'address',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'asset_name' => 'required|max_length[255]',
        'status' => 'required|in_list[active,inactive,maintenance,retired]'
    ];

    protected $validationMessages = [
        'asset_name' => [
            'required' => 'Asset name is required.',
            'max_length' => 'Asset name cannot exceed 255 characters.'
        ],
        'status' => [
            'required' => 'Status is required.',
            'in_list' => 'Status must be one of: active, inactive, maintenance, retired.'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get assets with related company, contact, and parent asset information
     */
    public function getAssetsWithRelations()
    {
        return $this->select('
                assets.*,
                clients.client_name as company_name,
                CONCAT(contacts.first_name, " ", contacts.last_name) as contact_name,
                contacts.email as contact_email,
                contacts.phone as contact_phone,
                parent_assets.asset_name as parent_asset_name
            ')
            ->join('clients', 'clients.id = assets.company_id', 'left')
            ->join('contacts', 'contacts.id = assets.contact_id', 'left')
            ->join('assets as parent_assets', 'parent_assets.id = assets.parent_asset', 'left')
            ->orderBy('assets.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get query builder for assets with relations for search functionality
     */
    public function getAssetsWithRelationsBuilder()
    {
        return $this->db->table('assets')
            ->select('
                assets.*,
                clients.client_name as company_name,
                CONCAT(contacts.first_name, " ", contacts.last_name) as contact_name,
                contacts.email as contact_email,
                contacts.phone as contact_phone,
                parent_assets.asset_name as parent_asset_name
            ')
            ->join('clients', 'clients.id = assets.company_id', 'left')
            ->join('contacts', 'contacts.id = assets.contact_id', 'left')
            ->join('assets as parent_assets', 'parent_assets.id = assets.parent_asset', 'left')
            ->orderBy('assets.created_at', 'DESC');
    }

    /**
     * Get assets that can be used as parent assets (excluding the current asset to prevent circular references)
     */
    public function getAvailableParentAssets($excludeId = null)
    {
        $builder = $this->select('id, asset_name, asset_number')
                        ->where('status', 'active');
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->orderBy('asset_name', 'ASC')->findAll();
    }

    /**
     * Get asset statistics
     */
    public function getAssetStats()
    {
        $stats = [
            'total' => $this->countAll(),
            'active' => $this->where('status', 'active')->countAllResults(false),
            'inactive' => $this->where('status', 'inactive')->countAllResults(false),
            'maintenance' => $this->where('status', 'maintenance')->countAllResults(false),
            'retired' => $this->where('status', 'retired')->countAllResults(false)
        ];

        return $stats;
    }

    /**
     * Get assets by company
     */
    public function getAssetsByCompany($companyId)
    {
        return $this->where('company_id', $companyId)
                    ->where('status !=', 'retired')
                    ->orderBy('asset_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get assets by contact
     */
    public function getAssetsByContact($contactId)
    {
        return $this->where('contact_id', $contactId)
                    ->where('status !=', 'retired')
                    ->orderBy('asset_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get assets with upcoming warranty expirations
     */
    public function getAssetsWithUpcomingWarrantyExpiration($days = 30)
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        
        return $this->select('
                assets.*,
                clients.client_name as company_name,
                CONCAT(contacts.first_name, " ", contacts.last_name) as contact_name
            ')
            ->join('clients', 'clients.id = assets.company_id', 'left')
            ->join('contacts', 'contacts.id = assets.contact_id', 'left')
            ->where('assets.warranty_expiration <=', $futureDate)
            ->where('assets.warranty_expiration >=', date('Y-m-d'))
            ->where('assets.status', 'active')
            ->orderBy('assets.warranty_expiration', 'ASC')
            ->findAll();
    }
}
