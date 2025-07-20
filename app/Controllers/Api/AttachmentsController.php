<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AttachmentModel;
use App\Models\RequestModel;
use App\Models\AuditLogModel;

class AttachmentsController extends BaseController
{
    protected $attachmentModel;
    protected $requestModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->attachmentModel = new AttachmentModel();
        $this->requestModel = new RequestModel();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Get all attachments for a request
     * GET /api/requests/{requestId}/attachments
     */
    public function index($requestId)
    {
        try {
            // Verify the request exists
            $request = $this->requestModel->find($requestId);
            if (!$request) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Request not found'
                ]);
            }

            $attachments = $this->attachmentModel->getAttachmentsByRequest($requestId);

            return $this->response->setJSON([
                'success' => true,
                'attachments' => $attachments,
                'count' => count($attachments)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching attachments: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Internal server error'
            ]);
        }
    }

    /**
     * Upload files for a request
     * POST /api/requests/{requestId}/attachments/upload
     */
    public function upload($requestId)
    {
        try {
            // Verify the request exists
            $request = $this->requestModel->find($requestId);
            if (!$request) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Request not found'
                ]);
            }

            $files = $this->request->getFiles();
            if (!isset($files['files']) || empty($files['files'])) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'No files provided'
                ]);
            }

            $requestNumber = $this->request->getPost('request_number') ?? $request['request_number'] ?? 'REQ-' . $requestId;
            $uploadedFiles = [];
            $errors = [];

            // Create the upload directory based on request number
            $uploadPath = WRITEPATH . 'uploads/' . $requestNumber;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($files['files'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Validate file
                    $validationResult = $this->validateFile($file);
                    if ($validationResult !== true) {
                        $errors[] = $validationResult;
                        continue;
                    }

                    // Generate unique file name
                    $fileName = $this->generateUniqueFileName($file, $uploadPath);
                    $relativePath = $requestNumber . '/' . $fileName;
                    $fullPath = $uploadPath . '/' . $fileName;

                    // Move the file
                    if ($file->move($uploadPath, $fileName)) {
                        // Save to database
                        $attachmentData = [
                            'request_id' => $requestId,
                            'file_name' => $fileName,
                            'original_name' => $file->getClientName(),
                            'file_path' => $relativePath,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getClientMimeType(),
                            'uploaded_by' => session('user_id') ?? 1 // Default to user 1 if not logged in
                        ];

                        $attachmentId = $this->attachmentModel->insert($attachmentData);
                        if ($attachmentId) {
                            $uploadedFiles[] = [
                                'id' => $attachmentId,
                                'name' => $file->getClientName(),
                                'size' => $file->getSize()
                            ];
                            
                            // Log audit event
                            $this->auditLogModel->logEvent(
                                'request_attachment_added',
                                'Attachment uploaded: ' . $file->getClientName(),
                                'requests',
                                session('user_id') ?? 1,
                                'request',
                                $requestId
                            );
                        } else {
                            // Remove the uploaded file if database insert fails
                            unlink($fullPath);
                            $errors[] = 'Failed to save file: ' . $file->getClientName();
                        }
                    } else {
                        $errors[] = 'Failed to upload file: ' . $file->getClientName();
                    }
                }
            }

            if (empty($uploadedFiles) && !empty($errors)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Failed to upload files',
                    'errors' => $errors
                ]);
            }

            $response = [
                'success' => true,
                'message' => count($uploadedFiles) . ' file(s) uploaded successfully',
                'uploaded_files' => $uploadedFiles
            ];

            if (!empty($errors)) {
                $response['warnings'] = $errors;
            }

            return $this->response->setJSON($response);

        } catch (\Exception $e) {
            log_message('error', 'Error uploading attachments: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Internal server error'
            ]);
        }
    }

    /**
     * Download an attachment
     * GET /api/requests/{requestId}/attachments/{attachmentId}/download
     */
    public function download($requestId, $attachmentId)
    {
        try {
            $attachment = $this->attachmentModel->where('request_id', $requestId)
                                              ->find($attachmentId);

            if (!$attachment) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Attachment not found'
                ]);
            }

            $filePath = WRITEPATH . 'uploads/' . $attachment['file_path'];
            
            if (!file_exists($filePath)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'File not found on server'
                ]);
            }

            return $this->response->download($filePath, null)->setFileName($attachment['original_name']);

        } catch (\Exception $e) {
            log_message('error', 'Error downloading attachment: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Internal server error'
            ]);
        }
    }

    /**
     * Preview an attachment (for images and PDFs)
     * GET /api/requests/{requestId}/attachments/{attachmentId}/preview
     */
    public function preview($requestId, $attachmentId)
    {
        try {
            $attachment = $this->attachmentModel->where('request_id', $requestId)
                                              ->find($attachmentId);

            if (!$attachment) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Attachment not found'
                ]);
            }

            $filePath = WRITEPATH . 'uploads/' . $attachment['file_path'];
            
            if (!file_exists($filePath)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'File not found on server'
                ]);
            }

            // Check if file type is previewable
            $previewableTypes = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
                'application/pdf'
            ];

            if (!in_array($attachment['mime_type'], $previewableTypes)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'File type not supported for preview'
                ]);
            }

            // Set appropriate headers for preview
            $this->response->setHeader('Content-Type', $attachment['mime_type']);
            $this->response->setHeader('Content-Disposition', 'inline; filename="' . $attachment['original_name'] . '"');
            $this->response->setHeader('Cache-Control', 'public, max-age=3600');
            
            // Output file content
            return $this->response->setBody(file_get_contents($filePath));

        } catch (\Exception $e) {
            log_message('error', 'Error previewing attachment: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Internal server error'
            ]);
        }
    }

    /**
     * Delete an attachment
     * DELETE /api/requests/{requestId}/attachments/{attachmentId}
     */
    public function delete($requestId, $attachmentId)
    {
        try {
            $attachment = $this->attachmentModel->where('request_id', $requestId)
                                              ->find($attachmentId);

            if (!$attachment) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Attachment not found'
                ]);
            }

            // Delete the attachment (model handles file deletion)
            if ($this->attachmentModel->deleteAttachment($attachmentId)) {
                // Log audit event
                $this->auditLogModel->logEvent(
                    'request_attachment_deleted',
                    'Attachment deleted: ' . $attachment['original_name'],
                    'requests',
                    session('user_id') ?? 1,
                    'request',
                    $requestId
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Attachment deleted successfully'
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete attachment'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deleting attachment: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Internal server error'
            ]);
        }
    }

    /**
     * Validate uploaded file
     */
    private function validateFile($file)
    {
        // Check file size (max 10MB)
        $maxSize = 10 * 1024 * 1024; // 10MB in bytes
        if ($file->getSize() > $maxSize) {
            return 'File size too large: ' . $file->getClientName() . '. Maximum size is 10MB.';
        }

        // Check file extension
        $allowedExtensions = [
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf',
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg',
            'zip', 'rar', '7z',
            'mp4', 'avi', 'mov', 'wmv',
            'mp3', 'wav', 'aac'
        ];

        $extension = strtolower($file->getClientExtension());
        if (!in_array($extension, $allowedExtensions)) {
            return 'File type not allowed: ' . $file->getClientName() . '. Allowed types: ' . implode(', ', $allowedExtensions);
        }

        return true;
    }

    /**
     * Generate a unique file name to prevent conflicts
     */
    private function generateUniqueFileName($file, $uploadPath)
    {
        $extension = $file->getClientExtension();
        $baseName = pathinfo($file->getClientName(), PATHINFO_FILENAME);
        
        // Clean the base name
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);
        $baseName = substr($baseName, 0, 50); // Limit length
        
        $fileName = $baseName . '.' . $extension;
        $counter = 1;
        
        // Check if file already exists and generate unique name
        while (file_exists($uploadPath . '/' . $fileName)) {
            $fileName = $baseName . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $fileName;
    }
}
