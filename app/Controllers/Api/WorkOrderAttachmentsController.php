<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\WorkOrderAttachmentModel;
use App\Models\WorkOrderModel;
use App\Models\AuditLogModel;
use CodeIgniter\HTTP\ResponseInterface;

class WorkOrderAttachmentsController extends BaseController
{
    use \CodeIgniter\API\ResponseTrait;

    protected $workOrderAttachmentModel;
    protected $workOrderModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->workOrderAttachmentModel = new WorkOrderAttachmentModel();
        $this->workOrderModel = new WorkOrderModel();
        $this->auditLogModel = new AuditLogModel();
    }

    /**
     * Get all attachments for a work order
     * GET /api/work-orders/{workOrderId}/attachments
     */
    public function index($workOrderId)
    {
        try {
            // Verify the work order exists
            $workOrder = $this->workOrderModel->find($workOrderId);
            if (!$workOrder) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Work order not found'
                ], 404);
            }

            $attachments = $this->workOrderAttachmentModel->getAttachmentsByWorkOrder($workOrderId);

            return $this->respond([
                'success' => true,
                'attachments' => $attachments,
                'count' => count($attachments)
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching work order attachments: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Upload files for a work order
     * POST /api/work-orders/{workOrderId}/attachments/upload
     */
    public function upload($workOrderId)
    {
        try {
            // Verify the work order exists
            $workOrder = $this->workOrderModel->find($workOrderId);
            if (!$workOrder) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Work order not found'
                ], 404);
            }

            $files = $this->request->getFiles();
            if (!isset($files['files']) || empty($files['files'])) {
                return $this->respond([
                    'success' => false,
                    'message' => 'No files provided'
                ], 400);
            }

            $workOrderNumber = $this->request->getPost('work_order_number') ?? $workOrder['work_order_number'] ?? 'WO-' . $workOrderId;
            $uploadedFiles = [];
            $errors = [];

            // Create the upload directory based on work order number
            $uploadPath = WRITEPATH . 'uploads/work-orders/' . $workOrderNumber;
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
                    $relativePath = 'work-orders/' . $workOrderNumber . '/' . $fileName;
                    $fullPath = $uploadPath . '/' . $fileName;

                    // Move the file
                    if ($file->move($uploadPath, $fileName)) {
                        // Save to database
                        $attachmentData = [
                            'work_order_id' => $workOrderId,
                            'file_name' => $fileName,
                            'original_name' => $file->getClientName(),
                            'file_path' => $relativePath,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getClientMimeType(),
                            'uploaded_by' => session('user_id') ?? 1 // Default to user 1 if not logged in
                        ];

                        $attachmentId = $this->workOrderAttachmentModel->insert($attachmentData);
                        if ($attachmentId) {
                            $uploadedFiles[] = [
                                'id' => $attachmentId,
                                'name' => $file->getClientName(),
                                'size' => $file->getSize()
                            ];
                            
                            // Log audit event
                            $this->auditLogModel->logEvent(
                                'work_order_attachment_added',
                                'Attachment uploaded: ' . $file->getClientName(),
                                'work_orders',
                                session('user_id') ?? 1,
                                'work_order',
                                $workOrderId
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
                return $this->respond([
                    'success' => false,
                    'message' => 'Failed to upload files',
                    'errors' => $errors
                ], 400);
            }

            $response = [
                'success' => true,
                'message' => count($uploadedFiles) . ' file(s) uploaded successfully',
                'uploaded_files' => $uploadedFiles
            ];

            if (!empty($errors)) {
                $response['warnings'] = $errors;
            }

            return $this->respond($response);

        } catch (\Exception $e) {
            log_message('error', 'Error uploading work order attachments: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Download an attachment
     * GET /api/work-orders/{workOrderId}/attachments/{attachmentId}/download
     */
    public function download($workOrderId, $attachmentId)
    {
        try {
            $attachment = $this->workOrderAttachmentModel->where('work_order_id', $workOrderId)
                                                         ->find($attachmentId);

            if (!$attachment) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Attachment not found'
                ], 404);
            }

            $filePath = WRITEPATH . 'uploads/' . $attachment['file_path'];
            
            if (!file_exists($filePath)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'File not found on server'
                ], 404);
            }

            return $this->response->download($filePath, null)->setFileName($attachment['original_name']);

        } catch (\Exception $e) {
            log_message('error', 'Error downloading work order attachment: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Preview an attachment (for images and PDFs)
     * GET /api/work-orders/{workOrderId}/attachments/{attachmentId}/preview
     */
    public function preview($workOrderId, $attachmentId)
    {
        try {
            $attachment = $this->workOrderAttachmentModel->where('work_order_id', $workOrderId)
                                                         ->find($attachmentId);

            if (!$attachment) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Attachment not found'
                ], 404);
            }

            $filePath = WRITEPATH . 'uploads/' . $attachment['file_path'];
            
            if (!file_exists($filePath)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'File not found on server'
                ], 404);
            }

            // Check if file type is previewable
            $previewableTypes = [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
                'application/pdf'
            ];

            if (!in_array($attachment['mime_type'], $previewableTypes)) {
                return $this->respond([
                    'success' => false,
                    'message' => 'File type not supported for preview'
                ], 400);
            }

            // Set appropriate headers for preview
            $this->response->setHeader('Content-Type', $attachment['mime_type']);
            $this->response->setHeader('Content-Disposition', 'inline; filename="' . $attachment['original_name'] . '"');
            $this->response->setHeader('Cache-Control', 'public, max-age=3600');
            
            // Output file content
            return $this->response->setBody(file_get_contents($filePath));

        } catch (\Exception $e) {
            log_message('error', 'Error previewing work order attachment: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Delete an attachment
     * DELETE /api/work-orders/{workOrderId}/attachments/{attachmentId}
     */
    public function delete($workOrderId, $attachmentId)
    {
        try {
            $attachment = $this->workOrderAttachmentModel->where('work_order_id', $workOrderId)
                                                         ->find($attachmentId);

            if (!$attachment) {
                return $this->respond([
                    'success' => false,
                    'message' => 'Attachment not found'
                ], 404);
            }

            // Delete the attachment (model handles file deletion)
            if ($this->workOrderAttachmentModel->deleteAttachment($attachmentId)) {
                // Log audit event
                $this->auditLogModel->logEvent(
                    'work_order_attachment_deleted',
                    'Attachment deleted: ' . $attachment['original_name'],
                    'work_orders',
                    session('user_id') ?? 1,
                    'work_order',
                    $workOrderId
                );
                
                return $this->respond([
                    'success' => true,
                    'message' => 'Attachment deleted successfully'
                ]);
            } else {
                return $this->respond([
                    'success' => false,
                    'message' => 'Failed to delete attachment'
                ], 500);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error deleting work order attachment: ' . $e->getMessage());
            return $this->respond([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
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
