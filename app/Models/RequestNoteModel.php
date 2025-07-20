<?php

namespace App\Models;

use CodeIgniter\Model;

class RequestNoteModel extends Model
{
    protected $table            = 'request_notes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'request_id',
        'content',
        'is_pinned',
        'created_by',
        'updated_by'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'request_id' => 'required|integer',
        'content'    => 'required|min_length[1]|max_length[10000]',
        'is_pinned'  => 'in_list[0,1]',
        'created_by' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'content' => [
            'required'   => 'Note content is required.',
            'min_length' => 'Note content must not be empty.',
            'max_length' => 'Note content cannot exceed 10000 characters.'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setCreatedBy'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setUpdatedBy'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    /**
     * Get notes for a specific request with user information
     */
    public function getRequestNotes(int $requestId): array
    {
        return $this->select('
                request_notes.*,
                CONCAT(users.first_name, " ", users.last_name) as created_by_name,
                users.email as created_by_email
            ')
            ->join('users', 'users.id = request_notes.created_by', 'left')
            ->where('request_notes.request_id', $requestId)
            ->orderBy('request_notes.is_pinned', 'DESC')
            ->orderBy('request_notes.created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Pin or unpin a note
     */
    public function togglePin(int $noteId, int $userId): bool
    {
        $note = $this->find($noteId);
        if (!$note) {
            return false;
        }
        
        return $this->update($noteId, [
            'is_pinned' => $note['is_pinned'] ? 0 : 1,
            'updated_by' => $userId
        ]);
    }
    
    /**
     * Get pinned notes count for a request
     */
    public function getPinnedCount(int $requestId): int
    {
        return $this->where('request_id', $requestId)
                   ->where('is_pinned', 1)
                   ->countAllResults();
    }
    
    /**
     * Set created_by before insert
     */
    protected function setCreatedBy(array $data): array
    {
        // Only set if not already provided
        if (!isset($data['data']['created_by'])) {
            $session = session();
            if ($session->has('user_id')) {
                $data['data']['created_by'] = $session->get('user_id');
            } else {
                // Use default user ID if no session
                $data['data']['created_by'] = 1;
            }
        }
        return $data;
    }
    
    /**
     * Set updated_by before update
     */
    protected function setUpdatedBy(array $data): array
    {
        // Only set if not already provided
        if (!isset($data['data']['updated_by'])) {
            $session = session();
            if ($session->has('user_id')) {
                $data['data']['updated_by'] = $session->get('user_id');
            } else {
                // Use default user ID if no session
                $data['data']['updated_by'] = 1;
            }
        }
        return $data;
    }
}
