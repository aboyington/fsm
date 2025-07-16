<?php

namespace App\Models;

use CodeIgniter\Model;

class PartsModel extends Model
{
    protected $table = 'product_skus';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'sku_code',
        'category',
        'subcategory',
        'name',
        'description',
        'price',
        'status',
        'created_by',
        'unit_price',
        'cost_price',
        'quantity_on_hand',
        'minimum_stock',
        'supplier',
        'manufacturer',
        'manufacturer_part_number',
        'warranty_period',
        'weight',
        'dimensions',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'sku_code' => 'required|max_length[50]|is_unique[product_skus.sku_code,id,{id}]',
        'category' => 'required|in_list[MAT,HRD,PRT,SRV]',
        'name' => 'required|max_length[255]',
        'description' => 'permit_empty|max_length[1000]',
        'price' => 'permit_empty|decimal',
        'status' => 'permit_empty|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'sku_code' => [
            'required' => 'SKU code is required',
            'is_unique' => 'SKU code must be unique'
        ],
        'category' => [
            'required' => 'Category is required',
            'in_list' => 'Category must be one of: MAT, HRD, PRT, SRV'
        ],
        'name' => [
            'required' => 'Name is required',
            'max_length' => 'Name cannot exceed 255 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all parts (category = 'PRT')
     */
    public function getAllParts($filters = [])
    {
        $builder = $this->where('category', 'PRT');
        
        if (!empty($filters['search'])) {
            $builder->groupStart()
                   ->like('name', $filters['search'])
                   ->orLike('sku_code', $filters['search'])
                   ->orLike('description', $filters['search'])
                   ->groupEnd();
        }
        
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        
        if (!empty($filters['subcategory'])) {
            $builder->where('subcategory', $filters['subcategory']);
        }
        
        return $builder->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Create a new part
     */
    public function createPart($data)
    {
        $partData = [
            'sku_code' => $data['sku'],
            'category' => 'PRT',
            'subcategory' => $this->mapCategoryToSubcategory($data['category']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['unit_price'],
            'cost_price' => $data['cost_price'] ?? null,
            'quantity_on_hand' => $data['quantity_on_hand'] ?? 0,
            'minimum_stock' => $data['minimum_stock'] ?? 0,
            'supplier' => $data['supplier'] ?? null,
            'manufacturer' => $data['manufacturer'] ?? null,
            'manufacturer_part_number' => $data['manufacturer_part_number'] ?? null,
            'warranty_period' => $data['warranty_period'] ?? null,
            'weight' => $data['weight'] ?? null,
            'dimensions' => $data['dimensions'] ?? null,
            'is_active' => isset($data['is_active']) && $data['is_active'] ? 1 : 0,
            'status' => isset($data['is_active']) && $data['is_active'] ? 'active' : 'inactive',
            'created_by' => session()->get('user_id')
        ];
        
        return $this->save($partData);
    }

    /**
     * Update an existing part
     */
    public function updatePart($id, $data)
    {
        $partData = [
            'sku_code' => $data['sku'],
            'subcategory' => $this->mapCategoryToSubcategory($data['category']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['unit_price'],
            'cost_price' => $data['cost_price'] ?? null,
            'quantity_on_hand' => $data['quantity_on_hand'] ?? 0,
            'minimum_stock' => $data['minimum_stock'] ?? 0,
            'supplier' => $data['supplier'] ?? null,
            'manufacturer' => $data['manufacturer'] ?? null,
            'manufacturer_part_number' => $data['manufacturer_part_number'] ?? null,
            'warranty_period' => $data['warranty_period'] ?? null,
            'weight' => $data['weight'] ?? null,
            'dimensions' => $data['dimensions'] ?? null,
            'is_active' => isset($data['is_active']) && $data['is_active'] ? 1 : 0,
            'status' => isset($data['is_active']) && $data['is_active'] ? 'active' : 'inactive'
        ];
        
        return $this->update($id, $partData);
    }

    /**
     * Map frontend category to database subcategory
     */
    private function mapCategoryToSubcategory($category)
    {
        $mapping = [
            'CCTV' => 'CCTV',
            'Alarm' => 'ALM',
            'Access Control' => 'ACC',
            'Networking' => 'NET',
            'I.T' => 'IT',
            'Security' => 'SEC',
            'General' => 'GEN'
        ];
        
        return $mapping[$category] ?? 'GEN';
    }

    /**
     * Get low stock items
     */
    public function getLowStockItems($threshold = 10)
    {
        return $this->where('category', 'PRT')
                   ->where('quantity_on_hand <=', $threshold)
                   ->where('status', 'active')
                   ->findAll();
    }
}
