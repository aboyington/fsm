<?php

namespace App\Controllers;

use App\Models\WorkOrderModel;
use App\Models\ClientModel;
use App\Models\ContactModel;
use App\Models\AssetModel;
use App\Models\ServiceRegistryModel;
use App\Models\ServicesModel;
use App\Models\PartsModel;
use App\Models\SkillModel;
use App\Models\UserSessionModel;
use App\Models\AuditLogModel;

class WorkOrdersController extends BaseController
{
    protected $workOrderModel;
    protected $clientModel;
    protected $contactModel;
    protected $assetModel;
    protected $serviceRegistryModel;
    protected $servicesModel;
    protected $partsModel;
    protected $skillModel;
    protected $userSessionModel;
    protected $auditLogModel;

    public function __construct()
    {
        $this->workOrderModel = new WorkOrderModel();
        $this->clientModel = new ClientModel();
        $this->contactModel = new ContactModel();
        $this->assetModel = new AssetModel();
        $this->serviceRegistryModel = new ServiceRegistryModel();
        $this->servicesModel = new ServicesModel();
        $this->partsModel = new PartsModel();
        $this->skillModel = new SkillModel();
        $this->userSessionModel = new UserSessionModel();
        $this->auditLogModel = new AuditLogModel();
    }
    
    /**
     * Get current user ID from session
     */
    protected function getCurrentUserId()
    {
        $authToken = session()->get('auth_token');
        
        if (!$authToken) {
            return null;
        }
        
        $session = $this->userSessionModel->validateSession($authToken);
        
        if ($session) {
            return $session['user_id'];
        }
        
        return null;
    }

    public function index()
    {
        $workOrders = $this->workOrderModel->getWorkOrders();
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        $assets = $this->assetModel->findAll();
        
        // Load active services from product_skus table (category = 'SRV')
        $services = $this->servicesModel->getAllServices(['status' => 'active']);
        
        // Load active parts from product_skus table (category = 'PRT')
        $parts = $this->partsModel->getAllParts(['status' => 'active']);
        
        // Load active skills
        $skills = $this->skillModel->getActiveSkills();
        
        $data = [
            'title' => 'Work Orders - FSM Platform',
            'workOrders' => $workOrders,
            'companies' => $companies,
            'contacts' => $contacts,
            'assets' => $assets,
            'services' => $services,
            'parts' => $parts,
            'skills' => $skills,
            'total_work_orders' => count($workOrders),
            'pending_work_orders' => count(array_filter($workOrders, function($w) { return $w['status'] === 'pending'; })),
            'in_progress_work_orders' => count(array_filter($workOrders, function($w) { return $w['status'] === 'in_progress'; })),
            'completed_work_orders' => count(array_filter($workOrders, function($w) { return $w['status'] === 'completed'; })),
        ];
        
        return view('work_orders/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Validate the data
            if (!$this->workOrderModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->workOrderModel->errors()
                ]);
            }
            
            // Set created_by to current user
            $userId = $this->getCurrentUserId();
            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }
            $data['created_by'] = $userId;
            
            // Insert the work order
            $workOrderId = $this->workOrderModel->insert($data);
            if ($workOrderId) {
                // Log the work order creation event
                $workOrderNumber = $data['work_order_number'] ?? 'WO-' . str_pad($workOrderId, 6, '0', STR_PAD_LEFT);
                $this->auditLogModel->logEvent(
                    AuditLogModel::EVENT_WORK_ORDER_CREATED,
                    'Work order created',
                    "Work order {$workOrderNumber} has been created",
                    null,
                    null,
                    json_encode($data),
                    'work_orders',
                    'work_order',
                    $workOrderId
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order created successfully',
                    'id' => $workOrderId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create work order'
                ]);
            }
        }
        
        $companies = $this->clientModel->where('status', 'active')->findAll();
        $contacts = $this->contactModel->findAll();
        $assets = $this->assetModel->findAll();
        $services = $this->serviceModel->findAll();
        
        return view('work_orders/create', [
            'companies' => $companies, 
            'contacts' => $contacts,
            'assets' => $assets,
            'services' => $services
        ]);
    }

    public function get($id)
    {
        try {
            $workOrder = $this->workOrderModel->getWorkOrderWithDetails($id);
            
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $workOrder
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching work order: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error fetching work order: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            
            // Find the work order
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ]);
            }
            
            // Validate the data
            if (!$this->workOrderModel->validate($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $this->workOrderModel->errors()
                ]);
            }
            
            // Track changes for audit log
            $changes = [];
            foreach ($data as $field => $newValue) {
                if (isset($workOrder[$field]) && $workOrder[$field] != $newValue) {
                    $changes[$field] = [
                        'old' => $workOrder[$field],
                        'new' => $newValue
                    ];
                }
            }
            
            // Update the work order
            if ($this->workOrderModel->update($id, $data)) {
                // Log specific change events
                $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                
                // Log status change separately if it occurred
                if (isset($changes['status'])) {
                    $this->auditLogModel->logEvent(
                        AuditLogModel::EVENT_WORK_ORDER_STATUS_CHANGED,
                        'Work order status changed',
                        "Work order {$workOrderNumber} status changed from {$changes['status']['old']} to {$changes['status']['new']}",
                        null,
                        $changes['status']['old'],
                        $changes['status']['new'],
                        'work_orders',
                        'work_order',
                        $id
                    );
                }
                
                // Log priority change separately if it occurred
                if (isset($changes['priority'])) {
                    $this->auditLogModel->logEvent(
                        AuditLogModel::EVENT_WORK_ORDER_PRIORITY_CHANGED,
                        'Work order priority changed',
                        "Work order {$workOrderNumber} priority changed from {$changes['priority']['old']} to {$changes['priority']['new']}",
                        null,
                        $changes['priority']['old'],
                        $changes['priority']['new'],
                        'work_orders',
                        'work_order',
                        $id
                    );
                }
                
                // Log assignment change separately if it occurred
                if (isset($changes['assigned_to'])) {
                    $oldAssignee = $changes['assigned_to']['old'] ? "User ID {$changes['assigned_to']['old']}" : 'Unassigned';
                    $newAssignee = $changes['assigned_to']['new'] ? "User ID {$changes['assigned_to']['new']}" : 'Unassigned';
                    
                    $this->auditLogModel->logEvent(
                        AuditLogModel::EVENT_WORK_ORDER_ASSIGNED,
                        'Work order assignment changed',
                        "Work order {$workOrderNumber} assignment changed from {$oldAssignee} to {$newAssignee}",
                        null,
                        $changes['assigned_to']['old'],
                        $changes['assigned_to']['new'],
                        'work_orders',
                        'work_order',
                        $id
                    );
                }
                
                // Log general update event if there were other changes
                if (!empty($changes)) {
                    $this->auditLogModel->logEvent(
                        AuditLogModel::EVENT_WORK_ORDER_UPDATED,
                        'Work order updated',
                        "Work order {$workOrderNumber} has been updated",
                        null,
                        json_encode($workOrder),
                        json_encode($data),
                        'work_orders',
                        'work_order',
                        $id
                    );
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update work order'
                ]);
            }
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }

    public function delete($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $workOrder = $this->workOrderModel->find($id);
            
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ]);
            }
            
            // Delete the work order
            if ($this->workOrderModel->delete($id)) {
                // Log the work order deletion event
                $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                $this->auditLogModel->logEvent(
                    AuditLogModel::EVENT_WORK_ORDER_DELETED,
                    'Work order deleted',
                    "Work order {$workOrderNumber} has been deleted",
                    null,
                    json_encode($workOrder),
                    null,
                    'work_orders',
                    'work_order',
                    $id
                );
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete work order'
                ]);
            }
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }

    public function search()
    {
        $searchTerm = $this->request->getGet('q');
        $status = $this->request->getGet('status');
        $companyId = $this->request->getGet('company_id');
        
        $workOrders = $this->workOrderModel->getWorkOrders($status, $searchTerm, $companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $workOrders
        ]);
    }

    public function updateStatus($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $status = $data['status'] ?? '';
            
            $workOrder = $this->workOrderModel->find($id);
            
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ]);
            }
            
            $oldStatus = $workOrder['status'];
            
            if ($this->workOrderModel->update($id, ['status' => $status])) {
                // Log the status change event
                $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
                
                // Log status change event
                $this->auditLogModel->logEvent(
                    AuditLogModel::EVENT_WORK_ORDER_STATUS_CHANGED,
                    'Work order status changed',
                    "Work order {$workOrderNumber} status changed from {$oldStatus} to {$status}",
                    null,
                    $oldStatus,
                    $status,
                    'work_orders',
                    'work_order',
                    $id
                );
                
                // Log specific completion/cancellation events
                if ($status === 'completed') {
                    $this->auditLogModel->logEvent(
                        AuditLogModel::EVENT_WORK_ORDER_COMPLETED,
                        'Work order completed',
                        "Work order {$workOrderNumber} has been completed",
                        null,
                        null,
                        $status,
                        'work_orders',
                        'work_order',
                        $id
                    );
                } elseif ($status === 'cancelled') {
                    $this->auditLogModel->logEvent(
                        AuditLogModel::EVENT_WORK_ORDER_CANCELLED,
                        'Work order cancelled',
                        "Work order {$workOrderNumber} has been cancelled",
                        null,
                        null,
                        $status,
                        'work_orders',
                        'work_order',
                        $id
                    );
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Work Order status updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update work order status'
                ]);
            }
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }

    public function view($id)
    {
        $workOrder = $this->workOrderModel->getWorkOrderWithDetails($id);
        
        if (!$workOrder) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Work Order not found');
        }
        
        $data = [
            'title' => 'Work Order ' . ($workOrder['work_order_number'] ?? '') . ' - FSM Platform',
            'workOrder' => $workOrder
        ];
        
        return view('work_orders/view', $data);
    }

    public function getByCompany($companyId)
    {
        $workOrders = $this->workOrderModel->getWorkOrdersByCompany($companyId);
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $workOrders
        ]);
    }
    
    /**
     * Get timeline for a specific work order
     */
    public function getTimeline($id)
    {
        // Check if user is authenticated
        if (!session()->get('auth_token')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please login to continue'
            ])->setStatusCode(401);
        }
        
        // Verify work order exists
        $workOrder = $this->workOrderModel->find($id);
        if (!$workOrder) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Work order not found'
            ])->setStatusCode(404);
        }
        
        $filter = $this->request->getVar('filter') ?? 'all';
        
        // Fetch timeline data from audit logs
        $timelineData = $this->auditLogModel->getWorkOrderTimeline($id, $filter);
        
        return $this->response->setJSON([
            'success' => true,
            'timeline' => $timelineData,
            'total' => count($timelineData)
        ]);
    }
    
    /**
     * Add a note to a work order
     */
    public function addNote($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $content = $data['content'] ?? '';
            
            // Verify work order exists
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work order not found'
                ])->setStatusCode(404);
            }
            
            if (empty($content)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Note content is required'
                ]);
            }
            
            // Log the note addition event
            $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $this->auditLogModel->logEvent(
                AuditLogModel::EVENT_WORK_ORDER_NOTE_ADDED,
                'Work order note added',
                "Note added to work order {$workOrderNumber}: {$content}",
                null,
                null,
                $content,
                'work_orders',
                'work_order',
                $id
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Note added successfully'
            ]);
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
    
    /**
     * Log attachment addition for a work order
     */
    public function logAttachment($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $filename = $data['filename'] ?? '';
            $action = $data['action'] ?? 'upload'; // upload, download, delete
            
            // Verify work order exists
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work order not found'
                ])->setStatusCode(404);
            }
            
            if (empty($filename)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Filename is required'
                ]);
            }
            
            // Log the attachment event
            $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $eventType = AuditLogModel::EVENT_WORK_ORDER_ATTACHMENT_ADDED;
            $actionText = '';
            
            switch ($action) {
                case 'upload':
                    $actionText = 'uploaded to';
                    break;
                case 'download':
                    $actionText = 'downloaded from';
                    break;
                case 'delete':
                    $actionText = 'deleted from';
                    break;
                default:
                    $actionText = 'attached to';
            }
            
            $this->auditLogModel->logEvent(
                $eventType,
                "Work order attachment {$action}",
                "File '{$filename}' was {$actionText} work order {$workOrderNumber}",
                null,
                null,
                json_encode(['filename' => $filename, 'action' => $action]),
                'work_orders',
                'work_order',
                $id
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Attachment event logged successfully'
            ]);
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
    
    /**
     * Log service appointment scheduling for a work order
     */
    public function logServiceAppointment($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $action = $data['action'] ?? 'scheduled'; // scheduled, updated, cancelled, completed
            $appointmentDetails = $data['appointment_details'] ?? '';
            
            // Verify work order exists
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work order not found'
                ])->setStatusCode(404);
            }
            
            // Log the service appointment event
            $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $eventType = AuditLogModel::EVENT_WORK_ORDER_SERVICE_APPOINTMENT_SCHEDULED;
            
            $actionText = match($action) {
                'scheduled' => 'scheduled for',
                'updated' => 'updated for', 
                'cancelled' => 'cancelled for',
                'completed' => 'completed for',
                default => 'processed for'
            };
            
            $this->auditLogModel->logEvent(
                $eventType,
                "Work order service appointment {$action}",
                "Service appointment was {$actionText} work order {$workOrderNumber}",
                null,
                null,
                json_encode(['action' => $action, 'details' => $appointmentDetails]),
                'work_orders',
                'work_order',
                $id
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service appointment event logged successfully'
            ]);
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
    
    /**
     * Log invoice generation for a work order
     */
    public function logInvoiceGeneration($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $invoiceNumber = $data['invoice_number'] ?? '';
            $amount = $data['amount'] ?? 0;
            
            // Verify work order exists
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work order not found'
                ])->setStatusCode(404);
            }
            
            // Log the invoice generation event
            $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $description = "Invoice {$invoiceNumber} generated for work order {$workOrderNumber}";
            if ($amount > 0) {
                $description .= " (Amount: $" . number_format($amount, 2) . ")";
            }
            
            $this->auditLogModel->logEvent(
                AuditLogModel::EVENT_WORK_ORDER_INVOICE_GENERATED,
                'Work order invoice generated',
                $description,
                null,
                null,
                json_encode(['invoice_number' => $invoiceNumber, 'amount' => $amount]),
                'work_orders',
                'work_order',
                $id
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Invoice generation event logged successfully'
            ]);
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
    
    /**
     * Log service and parts additions/modifications for a work order
     */
    public function logServiceAndParts($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $action = $data['action'] ?? 'added'; // added, updated, removed
            $itemType = $data['item_type'] ?? 'service'; // service, part
            $itemName = $data['item_name'] ?? '';
            $quantity = $data['quantity'] ?? 1;
            $cost = $data['cost'] ?? 0;
            
            // Verify work order exists
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work order not found'
                ])->setStatusCode(404);
            }
            
            // Log the service/parts event
            $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $description = ucfirst($itemType) . " '{$itemName}' was {$action} to work order {$workOrderNumber}";
            if ($quantity > 1) {
                $description .= " (Qty: {$quantity})";
            }
            if ($cost > 0) {
                $description .= " (Cost: $" . number_format($cost, 2) . ")";
            }
            
            $this->auditLogModel->logEvent(
                AuditLogModel::EVENT_WORK_ORDER_UPDATED,
                "Work order {$itemType} {$action}",
                $description,
                null,
                null,
                json_encode([
                    'action' => $action,
                    'item_type' => $itemType,
                    'item_name' => $itemName,
                    'quantity' => $quantity,
                    'cost' => $cost
                ]),
                'work_orders',
                'work_order',
                $id
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Service/parts event logged successfully'
            ]);
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
    
    /**
     * Log related list actions (service reports creation, etc.)
     */
    public function logRelatedAction($id)
    {
        if ($this->request->getMethod() === 'POST') {
            $data = $this->request->getPost();
            $action = $data['action'] ?? '';
            $relatedType = $data['related_type'] ?? 'service_report'; // service_report, service_appointment
            $relatedId = $data['related_id'] ?? null;
            $relatedName = $data['related_name'] ?? '';
            
            // Verify work order exists
            $workOrder = $this->workOrderModel->find($id);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work order not found'
                ])->setStatusCode(404);
            }
            
            // Log the related action event
            $workOrderNumber = $workOrder['work_order_number'] ?? 'WO-' . str_pad($id, 6, '0', STR_PAD_LEFT);
            $typeText = str_replace('_', ' ', $relatedType);
            $description = ucfirst($typeText) . " '{$relatedName}' was {$action} for work order {$workOrderNumber}";
            
            $this->auditLogModel->logEvent(
                AuditLogModel::EVENT_WORK_ORDER_UPDATED,
                "Work order {$typeText} {$action}",
                $description,
                null,
                null,
                json_encode([
                    'action' => $action,
                    'related_type' => $relatedType,
                    'related_id' => $relatedId,
                    'related_name' => $relatedName
                ]),
                'work_orders',
                'work_order',
                $id
            );
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Related action event logged successfully'
            ]);
        }
        
        return $this->response->setStatusCode(405)->setJSON([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
    }
    
}
