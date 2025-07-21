<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkOrderNoteModel extends Model
{
    protected $table            = 'work_order_notes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'work_order_id',
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
        'work_order_id' => 'required|integer',
        'content'       => 'required|min_length[1]|max_length[10000]',
        'is_pinned'     => 'in_list[0,1]',
        'created_by'    => 'required|integer'
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
    protected $beforeUpdate   = ['setUpdatedBy'];

    /**
     * Get notes for a specific work order with user information
     */
    public function getWorkOrderNotes(int $workOrderId): array
    {
        return $this->select('
                work_order_notes.*,
                CONCAT(users.first_name, " ", users.last_name) as created_by_name,
                users.email as created_by_email
            ')
            ->join('users', 'users.id = work_order_notes.created_by', 'left')
            ->where('work_order_notes.work_order_id', $workOrderId)
            ->orderBy('work_order_notes.is_pinned', 'DESC')
            ->orderBy('work_order_notes.created_at', 'DESC')
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
     * Set created_by before insert
     */
    protected function setCreatedBy(array $data): array
    {
        if (!isset($data['data']['created_by'])) {
            $session = session();
            if ($session->has('user_id')) {
                $data['data']['created_by'] = $session->get('user_id');
            } else {
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
        if (!isset($data['data']['updated_by'])) {
            $session = session();
            if ($session->has('user_id')) {
                $data['data']['updated_by'] = $session->get('user_id');
            } else {
                $data['data']['updated_by'] = 1;
            }
        }
        return $data;
    }
}
