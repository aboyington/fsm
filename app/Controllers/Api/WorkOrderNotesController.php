<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\WorkOrderNoteModel;
use App\Models\AuditLogModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class WorkOrderNotesController extends BaseController
{
    use ResponseTrait;
    protected WorkOrderNoteModel $noteModel;
    protected $auditLogModel;
    protected $session;

    public function __construct()
    {
        $this->noteModel = new WorkOrderNoteModel();
        $this->auditLogModel = new AuditLogModel();
        $this->session = session();
    }

    /**
     * Get all notes for a specific work order
     * GET /api/work-orders/{id}/notes
     */
    public function index(int $workOrderId): ResponseInterface
    {
        if (!$workOrderId) {
            return $this->respond([
                'success' => false,
                'message' => 'Work Order ID is required'
            ], 400);
        }
        
        try {
            $notes = $this->noteModel->getWorkOrderNotes($workOrderId);
            
            return $this->respond([
                'success' => true,
                'notes' => $notes,
                'count' => count($notes)
            ]);
            
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Error loading notes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new note for a work order
     * POST /api/work-orders/{id}/notes
     */
    public function create(int $workOrderId): ResponseInterface
    {
        if (!$workOrderId) {
            return $this->respond([
                'success' => false,
                'message' => 'Work Order ID is required'
            ], 400);
        }
        
        $input = $this->request->getJSON(true);
        
        if (!isset($input['content']) || empty(trim($input['content']))) {
            return $this->respond([
                'success' => false,
                'message' => 'Note content is required'
            ], 400);
        }
        
        // Get user ID from session or set a default for testing
        $userId = $this->session->get('user_id') ?? 1; // Default to user ID 1 for testing
        
        $data = [
            'work_order_id' => $workOrderId,
            'content' => trim($input['content']),
            'is_pinned' => $input['is_pinned'] ?? 0,
            'created_by' => $userId
        ];
        
        try {
            $noteId = $this->noteModel->insert($data);
            
            if ($noteId) {
                // Get the created note with user info
                $notes = $this->noteModel->getWorkOrderNotes($workOrderId);
                $note = array_filter($notes, function($n) use ($noteId) {
                    return $n['id'] == $noteId;
                });
                $note = array_shift($note);
                
                // Log audit event
                $this->auditLogModel->logEvent(
                    'work_order_note_added',
                    'Note added: ' . substr(trim($input['content']), 0, 100) . (strlen(trim($input['content'])) > 100 ? '...' : ''),
                    'work_orders',
                    $userId,
                    'work_order',
                    $workOrderId
                );
                
                return $this->respond([
                    'success' => true,
                    'message' => 'Note created successfully',
                    'note' => $note
                ]);
            } else {
                return $this->respond([
                    'success' => false,
                    'message' => 'Failed to create note',
                    'errors' => $this->noteModel->errors()
                ], 400);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Error creating note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific note
     * PUT /api/work-order-notes/{id}
     */
    public function update(int $noteId): ResponseInterface
    {
        if (!$noteId) {
            return $this->respond([
                'success' => false,
                'message' => 'Note ID is required'
            ], 400);
        }
        
        $input = $this->request->getJSON(true);
        
        if (!isset($input['content']) || empty(trim($input['content']))) {
            return $this->respond([
                'success' => false,
                'message' => 'Note content is required'
            ], 400);
        }
        
        // Check if note exists
        $existingNote = $this->noteModel->find($noteId);
        
        if (!$existingNote) {
            return $this->respond([
                'success' => false,
                'message' => 'Note not found'
            ], 404);
        }
        
        $data = [
            'content' => trim($input['content']),
            'updated_by' => $this->session->get('user_id') ?? 1 // Default to user ID 1 for testing
        ];
        
        try {
            $updated = $this->noteModel->update($noteId, $data);
            
            if ($updated) {
                // Get the updated note with user info
                $notes = $this->noteModel->getWorkOrderNotes($existingNote['work_order_id']);
                $note = array_filter($notes, function($n) use ($noteId) {
                    return $n['id'] == $noteId;
                });
                $note = array_shift($note);
                
                // Log audit event
                $this->auditLogModel->logEvent(
                    'work_order_note_updated',
                    'Note updated: ' . substr(trim($input['content']), 0, 100) . (strlen(trim($input['content'])) > 100 ? '...' : ''),
                    'work_orders',
                    $this->session->get('user_id') ?? 1,
                    'work_order',
                    $existingNote['work_order_id']
                );
                
                return $this->respond([
                    'success' => true,
                    'message' => 'Note updated successfully',
                    'note' => $note
                ]);
            } else {
                return $this->respond([
                    'success' => false,
                    'message' => 'Failed to update note',
                    'errors' => $this->noteModel->errors()
                ], 400);
            }
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Error updating note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific note
     * DELETE /api/work-order-notes/{id}
     */
    public function delete(int $noteId): ResponseInterface
    {
        if (!$noteId) {
            return $this->respond([
                'success' => false,
                'message' => 'Note ID is required'
            ], 400);
        }
        
        try {
            $note = $this->noteModel->find($noteId);
            if (!$note) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Note not found'
                ], 404);
            }

            if ($this->noteModel->delete($noteId)) {
                // Log audit event
                $this->auditLogModel->logEvent(
                    'work_order_note_deleted',
                    'Note deleted: ' . substr($note['content'], 0, 100) . (strlen($note['content']) > 100 ? '...' : ''),
                    'work_orders',
                    $this->session->get('user_id') ?? 1,
                    'work_order',
                    $note['work_order_id']
                );
                
                return $this->respond([
                    'success' => true,
                    'message' => 'Note deleted successfully'
                ]);
            } else {
                return $this->respond([
                    'success' => false,
                    'message' => 'Failed to delete note'
                ], 500);
            }
            
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Error deleting note: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle pin status of a note
     * POST /api/work-order-notes/{id}/toggle-pin
     */
    public function togglePin(int $noteId): ResponseInterface
    {
        if (!$noteId) {
            return $this->respond([
                'success' => false,
                'message' => 'Note ID is required'
            ], 400);
        }
        
        try {
            $userId = $this->session->get('user_id') ?? 1;
            $existingNote = $this->noteModel->find($noteId);
            
            if (!$existingNote) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Note not found'
                ], 404);
            }

            if ($this->noteModel->togglePin($noteId, $userId)) {
                $note = $this->noteModel->find($noteId);
                
                // Log audit event
                $this->auditLogModel->logEvent(
                    $note['is_pinned'] ? 'work_order_note_pinned' : 'work_order_note_unpinned',
                    ($note['is_pinned'] ? 'Note pinned: ' : 'Note unpinned: ') . substr($note['content'], 0, 100) . (strlen($note['content']) > 100 ? '...' : ''),
                    'work_orders',
                    $userId,
                    'work_order',
                    $existingNote['work_order_id']
                );
                
                return $this->respond([
                    'success' => true,
                    'message' => $note['is_pinned'] ? 'Note pinned successfully' : 'Note unpinned successfully',
                    'is_pinned' => $note['is_pinned']
                ]);
            } else {
                return $this->respond([
                    'success' => false,
                    'message' => 'Failed to toggle pin status'
                ], 400);
            }
            
        } catch (\Exception $e) {
            return $this->respond([
                'success' => false,
                'message' => 'Error toggling pin status: ' . $e->getMessage()
            ], 500);
        }
    }
}
