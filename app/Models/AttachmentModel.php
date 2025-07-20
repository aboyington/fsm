<?php

namespace App\Models;

use CodeIgniter\Model;

class AttachmentModel extends Model
{
    protected $table = 'attachments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'request_id',
        'file_name',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'request_id' => 'required|integer',
        'file_name' => 'required|max_length[255]',
        'original_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[500]',
        'file_size' => 'required|integer',
        'mime_type' => 'required|max_length[100]',
        'uploaded_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'request_id' => [
            'required' => 'Request ID is required',
            'integer' => 'Request ID must be an integer'
        ],
        'file_name' => [
            'required' => 'File name is required',
            'max_length' => 'File name cannot exceed 255 characters'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get all attachments for a specific request with uploader details
     */
    public function getAttachmentsByRequest($requestId)
    {
        return $this->select('attachments.*, users.first_name, users.last_name, 
                            CONCAT(users.first_name, " ", users.last_name) as uploaded_by_name')
                    ->join('users', 'users.id = attachments.uploaded_by', 'left')
                    ->where('attachments.request_id', $requestId)
                    ->orderBy('attachments.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get attachment with request details
     */
    public function getAttachmentWithRequest($attachmentId)
    {
        return $this->select('attachments.*, requests.request_number')
                    ->join('requests', 'requests.id = attachments.request_id', 'left')
                    ->where('attachments.id', $attachmentId)
                    ->first();
    }

    /**
     * Delete attachment and its file
     */
    public function deleteAttachment($id)
    {
        $attachment = $this->find($id);
        if (!$attachment) {
            return false;
        }

        // Delete the physical file
        $filePath = WRITEPATH . 'uploads/' . $attachment['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the database record
        return $this->delete($id);
    }

    /**
     * Get total file size for a request
     */
    public function getTotalSizeByRequest($requestId)
    {
        $result = $this->selectSum('file_size', 'total_size')
                      ->where('request_id', $requestId)
                      ->first();
        
        return $result['total_size'] ?? 0;
    }

    /**
     * Get attachment count by request
     */
    public function getCountByRequest($requestId)
    {
        return $this->where('request_id', $requestId)->countAllResults();
    }
}
