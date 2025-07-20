<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\RequestNoteModel;
use App\Models\AuditLogModel;
use CodeIgniter\HTTP\ResponseInterface;

class RequestNotesController extends BaseController
{
    protected $noteModel;
    protected $auditLogModel;
    protected $session;
    
    public function __construct()
    {
        $this->noteModel = new RequestNoteModel();
        $this->auditLogModel = new AuditLogModel();
        $this->session = session();
    }
    
    /**
     * Get all notes for a request
     */
    public function index($requestId = null)
    {
        if (!$requestId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Request ID is required'
            ])->setStatusCode(400);
        }
        
        try {
            $notes = $this->noteModel->getRequestNotes($requestId);
            
            return $this->response->setJSON([
                'success' => true,
                'notes' => $notes,
                'count' => count($notes)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading notes: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Create a new note
     */
    public function create($requestId = null)
    {
        if (!$requestId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Request ID is required'
            ])->setStatusCode(400);
        }
        
        $input = $this->request->getJSON(true);
        
        if (!isset($input['content']) || empty(trim($input['content']))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Note content is required'
            ])->setStatusCode(400);
        }
        
        // Get user ID from session or set a default for testing
        $userId = $this->session->get('user_id') ?? 1; // Default to user ID 1 for testing
        
        $data = [
            'request_id' => $requestId,
            'content' => trim($input['content']),
            'is_pinned' => $input['is_pinned'] ?? 0,
            'created_by' => $userId
        ];
        
        try {
            $noteId = $this->noteModel->insert($data);
            
            if ($noteId) {
                // Get the created note with user info
                $note = $this->noteModel->select('
                    request_notes.*,
                    CONCAT(users.first_name, " ", users.last_name) as created_by_name,
                    users.email as created_by_email
                ')
                ->join('users', 'users.id = request_notes.created_by', 'left')
                ->find($noteId);
                
                // Log audit event
                $this->auditLogModel->logEvent(
                    'request_note_added',
                    'Note added: ' . substr(trim($input['content']), 0, 100) . (strlen(trim($input['content'])) > 100 ? '...' : ''),
                    'requests',
                    $userId,
                    'request',
                    $requestId
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Note created successfully',
                    'note' => $note
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create note',
                    'errors' => $this->noteModel->errors()
                ])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error creating note: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Update a note
     */
    public function update($requestId = null, $noteId = null)
    {
        if (!$requestId || !$noteId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Request ID and Note ID are required'
            ])->setStatusCode(400);
        }
        
        $input = $this->request->getJSON(true);
        
        if (!isset($input['content']) || empty(trim($input['content']))) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Note content is required'
            ])->setStatusCode(400);
        }
        
        // Check if note exists and belongs to the request
        $existingNote = $this->noteModel->where('id', $noteId)
                                       ->where('request_id', $requestId)
                                       ->first();
        
        if (!$existingNote) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Note not found'
            ])->setStatusCode(404);
        }
        
        $data = [
            'content' => trim($input['content']),
            'updated_by' => $this->session->get('user_id') ?? 1 // Default to user ID 1 for testing
        ];
        
        try {
            $updated = $this->noteModel->update($noteId, $data);
            
            if ($updated) {
                // Get the updated note with user info
                $note = $this->noteModel->select('
                    request_notes.*,
                    CONCAT(users.first_name, " ", users.last_name) as created_by_name,
                    users.email as created_by_email
                ')
                ->join('users', 'users.id = request_notes.created_by', 'left')
                ->find($noteId);
                
                // Log audit event
                $this->auditLogModel->logEvent(
                    'request_note_updated',
                    'Note updated: ' . substr(trim($input['content']), 0, 100) . (strlen(trim($input['content'])) > 100 ? '...' : ''),
                    'requests',
                    $this->session->get('user_id') ?? 1,
                    'request',
                    $requestId
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Note updated successfully',
                    'note' => $note
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update note',
                    'errors' => $this->noteModel->errors()
                ])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error updating note: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Delete a note
     */
    public function delete($requestId = null, $noteId = null)
    {
        if (!$requestId || !$noteId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Request ID and Note ID are required'
            ])->setStatusCode(400);
        }
        
        // Check if note exists and belongs to the request
        $existingNote = $this->noteModel->where('id', $noteId)
                                       ->where('request_id', $requestId)
                                       ->first();
        
        if (!$existingNote) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Note not found'
            ])->setStatusCode(404);
        }
        
        try {
            $deleted = $this->noteModel->delete($noteId);
            
            if ($deleted) {
                // Log audit event
                $this->auditLogModel->logEvent(
                    'request_note_deleted',
                    'Note deleted: ' . substr($existingNote['content'], 0, 100) . (strlen($existingNote['content']) > 100 ? '...' : ''),
                    'requests',
                    $this->session->get('user_id') ?? 1,
                    'request',
                    $requestId
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Note deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete note'
                ])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error deleting note: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Toggle pin status of a note
     */
    public function togglePin($requestId = null, $noteId = null)
    {
        if (!$requestId || !$noteId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Request ID and Note ID are required'
            ])->setStatusCode(400);
        }
        
        // Check if note exists and belongs to the request
        $existingNote = $this->noteModel->where('id', $noteId)
                                       ->where('request_id', $requestId)
                                       ->first();
        
        if (!$existingNote) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Note not found'
            ])->setStatusCode(404);
        }
        
        try {
            $userId = $this->session->get('user_id') ?? 1; // Default to user ID 1 for testing
            $toggled = $this->noteModel->togglePin($noteId, $userId);
            
            if ($toggled) {
                // Get the updated note with user info
                $note = $this->noteModel->select('
                    request_notes.*,
                    CONCAT(users.first_name, " ", users.last_name) as created_by_name,
                    users.email as created_by_email
                ')
                ->join('users', 'users.id = request_notes.created_by', 'left')
                ->find($noteId);
                
                $action = $note['is_pinned'] ? 'pinned' : 'unpinned';
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Note {$action} successfully",
                    'note' => $note,
                    'is_pinned' => (bool)$note['is_pinned']
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to toggle pin status'
                ])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error toggling pin status: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
