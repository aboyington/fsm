<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'canvass_global_id', 'first_name', 'last_name', 'email', 'phone',
        'company_name', 'address', 'city', 'state', 'zip_code',
        'latitude', 'longitude', 'customer_type', 'status', 'notes', 'synced_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'first_name'    => 'required|max_length[100]',
        'last_name'     => 'required|max_length[100]',
        'email'         => 'permit_empty|valid_email|max_length[255]',
        'phone'         => 'permit_empty|max_length[20]',
        'address'       => 'required|max_length[255]',
        'city'          => 'required|max_length[100]',
        'state'         => 'required|max_length[50]',
        'zip_code'      => 'required|max_length[20]',
        'customer_type' => 'required|in_list[residential,commercial]',
        'status'        => 'required|in_list[active,inactive,prospect]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Search customers
     */
    public function search($query)
    {
        return $this->like('first_name', $query)
                    ->orLike('last_name', $query)
                    ->orLike('email', $query)
                    ->orLike('phone', $query)
                    ->orLike('company_name', $query)
                    ->orLike('address', $query)
                    ->findAll();
    }

    /**
     * Get customers by status
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Get customers near location
     */
    public function getNearLocation($lat, $lng, $radiusKm = 10)
    {
        // Using Haversine formula for SQLite
        $sql = "SELECT *, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) AS distance 
                FROM customers 
                HAVING distance < ? 
                ORDER BY distance";
        
        return $this->db->query($sql, [$lat, $lng, $lat, $radiusKm])->getResultArray();
    }

    /**
     * Sync with Canvass Global
     */
    public function syncFromCanvassGlobal($canvassGlobalId, $data)
    {
        $existing = $this->where('canvass_global_id', $canvassGlobalId)->first();
        
        $data['canvass_global_id'] = $canvassGlobalId;
        $data['synced_at'] = date('Y-m-d H:i:s');
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
}
