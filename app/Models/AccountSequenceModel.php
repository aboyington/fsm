<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountSequenceModel extends Model
{
    protected $table = 'account_sequences';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'prefix', 'prefix_name', 'current_sequence', 'sequence_format', 'description', 'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'prefix' => 'required|max_length[10]|is_unique[account_sequences.prefix,id,{id}]',
        'prefix_name' => 'required|max_length[100]',
        'current_sequence' => 'permit_empty|integer|greater_than_equal_to[0]',
        'sequence_format' => 'permit_empty|max_length[20]',
        'description' => 'permit_empty',
        'status' => 'permit_empty|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'prefix' => [
            'required' => 'Prefix is required.',
            'is_unique' => 'This prefix already exists.'
        ],
        'prefix_name' => [
            'required' => 'Prefix name is required.'
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
     * Initialize default sequences
     */
    public function initializeDefaults()
    {
        $defaultSequences = [
            [
                'prefix' => 'ALA',
                'prefix_name' => 'Alarm & Access',
                'current_sequence' => 0,
                'sequence_format' => '000',
                'description' => 'Alarm and access control systems',
                'status' => 'active'
            ],
            [
                'prefix' => 'CAM',
                'prefix_name' => 'Camera Systems',
                'current_sequence' => 0,
                'sequence_format' => '000',
                'description' => 'Camera surveillance systems',
                'status' => 'active'
            ],
            [
                'prefix' => 'ITS',
                'prefix_name' => 'IT Services & Support',
                'current_sequence' => 0,
                'sequence_format' => '000',
                'description' => 'IT services and technical support',
                'status' => 'active'
            ],
            [
                'prefix' => 'SUB',
                'prefix_name' => 'Subcontracted Services',
                'current_sequence' => 0,
                'sequence_format' => '000',
                'description' => 'Subcontracted or third-party services',
                'status' => 'active'
            ]
        ];

        foreach ($defaultSequences as $sequence) {
            $existing = $this->where('prefix', $sequence['prefix'])->first();
            if (!$existing) {
                $this->insert($sequence);
            }
        }
    }

    /**
     * Get next sequence number for a prefix
     */
    public function getNextSequence($prefix)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        
        // Use a database transaction to ensure atomicity
        $db->transStart();
        
        try {
            // Lock the row for update
            $sequence = $builder->where('prefix', $prefix)
                              ->where('status', 'active')
                              ->get()
                              ->getRowArray();
            
            if (!$sequence) {
                $db->transRollback();
                throw new \Exception("Sequence not found for prefix: {$prefix}");
            }
            
            $nextNumber = $sequence['current_sequence'] + 1;
            
            // Update the sequence
            $builder->where('prefix', $prefix)
                   ->update(['current_sequence' => $nextNumber]);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception("Failed to update sequence for prefix: {$prefix}");
            }
            
            return $nextNumber;
            
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    /**
     * Get formatted sequence number
     */
    public function getFormattedSequence($prefix)
    {
        $sequence = $this->where('prefix', $prefix)->first();
        if (!$sequence) {
            return null;
        }
        
        $nextNumber = $this->getNextSequence($prefix);
        $format = $sequence['sequence_format'] ?: '000';
        
        return str_pad($nextNumber, strlen($format), '0', STR_PAD_LEFT);
    }

    /**
     * Reset sequence to a specific number
     */
    public function resetSequence($prefix, $number = 0)
    {
        return $this->where('prefix', $prefix)
                   ->update(['current_sequence' => $number]);
    }

    /**
     * Get all active sequences
     */
    public function getActiveSequences()
    {
        return $this->where('status', 'active')
                   ->orderBy('prefix', 'ASC')
                   ->findAll();
    }

    /**
     * Get sequences with usage statistics
     */
    public function getSequencesWithStats()
    {
        $sequences = $this->orderBy('prefix', 'ASC')->findAll();
        
        foreach ($sequences as &$sequence) {
            // Get usage count from service_registry
            $serviceModel = new ServiceRegistryModel();
            $usage = $serviceModel->where('service_type', $sequence['prefix'])->countAllResults();
            $sequence['usage_count'] = $usage;
        }
        
        return $sequences;
    }

    /**
     * Update sequence format
     */
    public function updateSequenceFormat($prefix, $format)
    {
        return $this->where('prefix', $prefix)
                   ->update(['sequence_format' => $format]);
    }

    /**
     * Get sequence by prefix
     */
    public function getSequenceByPrefix($prefix)
    {
        return $this->where('prefix', $prefix)->first();
    }

    /**
     * Check if prefix exists
     */
    public function prefixExists($prefix, $excludeId = null)
    {
        $builder = $this->builder();
        $builder->where('prefix', $prefix);
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Get available prefixes for dropdown
     */
    public function getAvailablePrefixes()
    {
        $sequences = $this->where('status', 'active')->findAll();
        $prefixes = [];
        
        foreach ($sequences as $sequence) {
            $prefixes[$sequence['prefix']] = $sequence['prefix_name'];
        }
        
        return $prefixes;
    }
}
