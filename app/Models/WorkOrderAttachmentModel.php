<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkOrderAttachmentModel extends Model
{
    protected $table = 'work_order_attachments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'work_order_id',
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
        'work_order_id' => 'required|integer',
        'file_name' => 'required|max_length[255]',
        'original_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[500]',
        'file_size' => 'required|integer',
        'mime_type' => 'required|max_length[100]',
        'uploaded_by' => 'required|integer'
    ];

    protected $validationMessages = [
        'work_order_id' => [
            'required' => 'Work Order ID is required',
            'integer' => 'Work Order ID must be an integer'
        ],
        'file_name' => [
            'required' => 'File name is required',
            'max_length' => 'File name cannot exceed 255 characters'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get all attachments for a specific work order with uploader details
     */
    public function getAttachmentsByWorkOrder($workOrderId)
    {
        return $this->select('work_order_attachments.*, users.first_name, users.last_name, 
                            CONCAT(users.first_name, " ", users.last_name) as uploaded_by_name')
                    ->join('users', 'users.id = work_order_attachments.uploaded_by', 'left')
                    ->where('work_order_attachments.work_order_id', $workOrderId)
                    ->orderBy('work_order_attachments.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get attachment with work order details
     */
    public function getAttachmentWithWorkOrder($attachmentId)
    {
        return $this->select('work_order_attachments.*, work_orders.work_order_number')
                    ->join('work_orders', 'work_orders.id = work_order_attachments.work_order_id', 'left')
                    ->where('work_order_attachments.id', $attachmentId)
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
     * Get total file size for a work order
     */
    public function getTotalSizeByWorkOrder($workOrderId)
    {
        $result = $this->selectSum('file_size', 'total_size')
                      ->where('work_order_id', $workOrderId)
                      ->first();
        
        return $result['total_size'] ?? 0;
    }

    /**
     * Get attachment count by work order
     */
    public function getCountByWorkOrder($workOrderId)
    {
        return $this->where('work_order_id', $workOrderId)->countAllResults();
    }
}
