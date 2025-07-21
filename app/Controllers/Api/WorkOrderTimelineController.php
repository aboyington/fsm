<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\WorkOrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class WorkOrderTimelineController extends BaseController
{
    protected $auditLogModel;
    protected $workOrderModel;
    protected $session;
    
    public function __construct()
    {
        $this->auditLogModel = new AuditLogModel();
        $this->workOrderModel = new WorkOrderModel();
        $this->session = session();
    }
    
    /**
     * Get timeline events for a work order
     */
    public function index($workOrderId = null)
    {
        if (!$workOrderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Work Order ID is required'
            ])->setStatusCode(400);
        }
        
        // Validate that work order exists
        $workOrder = $this->workOrderModel->getWorkOrderWithDetails($workOrderId);
        if (!$workOrder) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Work Order not found'
            ])->setStatusCode(404);
        }
        
        // Get filter from request
        $filter = $this->request->getGet('filter') ?? 'all';
        
        try {
            // Get timeline events from audit logs
            $timelineEvents = $this->auditLogModel->getWorkOrderTimeline($workOrderId, $filter);
            
            // If no events found, create a basic "Work Order Created" event
            if (empty($timelineEvents) && $workOrder) {
                $timelineEvents = $this->generateBasicTimelineEvents($workOrder);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'timeline' => $timelineEvents,
                'count' => count($timelineEvents),
                'work_order_id' => $workOrderId,
                'filter' => $filter
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading timeline: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Generate basic timeline events when no audit logs exist
     */
    private function generateBasicTimelineEvents($workOrder)
    {
        $events = [];
        
        // Work Order Created event
        $events[] = [
            'id' => 'wo_created_' . $workOrder['id'],
            'event_type' => 'work_order_created',
            'title' => 'Work Order Created',
            'description' => 'Work Order was created with summary: ' . ($workOrder['summary'] ?? 'No summary'),
            'user_name' => $workOrder['created_by_name'] ?? 'System',
            'created_at' => $workOrder['created_at'],
            'formatted_date' => $workOrder['created_at'] ? date('M j, Y g:i A', strtotime($workOrder['created_at'])) : null,
            'entity_type' => 'work_order',
            'entity_id' => $workOrder['id'],
            'module' => 'work_orders'
        ];
        
        // Status change events based on current status
        if (!empty($workOrder['status']) && $workOrder['status'] !== 'new') {
            $events[] = [
                'id' => 'wo_status_' . $workOrder['id'],
                'event_type' => 'work_order_status_changed',
                'title' => 'Status Changed',
                'description' => 'Work Order status changed to: ' . ucfirst($workOrder['status']),
                'user_name' => $workOrder['updated_by_name'] ?? $workOrder['created_by_name'] ?? 'System',
                'created_at' => $workOrder['updated_at'] ?? $workOrder['created_at'],
                'formatted_date' => $workOrder['updated_at'] ? date('M j, Y g:i A', strtotime($workOrder['updated_at'])) : null,
                'entity_type' => 'work_order',
                'entity_id' => $workOrder['id'],
                'module' => 'work_orders'
            ];
        }
        
        return $events;
    }
    
    /**
     * Get timeline statistics for a work order
     */
    public function stats($workOrderId = null)
    {
        if (!$workOrderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Work Order ID is required'
            ])->setStatusCode(400);
        }
        
        try {
            $builder = $this->auditLogModel->builder();
            
            // Get event counts by type
            $eventCounts = $builder->select('event_type, COUNT(*) as count')
                                 ->where('entity_type', 'work_order')
                                 ->where('entity_id', $workOrderId)
                                 ->groupBy('event_type')
                                 ->get()
                                 ->getResultArray();
            
            // Get total event count
            $totalEvents = array_sum(array_column($eventCounts, 'count'));
            
            // Get first and last event dates
            $firstEvent = $this->auditLogModel->where('entity_type', 'work_order')
                                             ->where('entity_id', $workOrderId)
                                             ->orderBy('created_at', 'ASC')
                                             ->first();
                                             
            $lastEvent = $this->auditLogModel->where('entity_type', 'work_order')
                                            ->where('entity_id', $workOrderId)
                                            ->orderBy('created_at', 'DESC')
                                            ->first();
            
            return $this->response->setJSON([
                'success' => true,
                'stats' => [
                    'total_events' => $totalEvents,
                    'event_counts' => $eventCounts,
                    'first_event_date' => $firstEvent ? $firstEvent['created_at'] : null,
                    'last_event_date' => $lastEvent ? $lastEvent['created_at'] : null,
                    'work_order_id' => $workOrderId
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading timeline stats: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Log a new timeline event for a work order
     */
    public function logEvent($workOrderId = null)
    {
        if (!$workOrderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Work Order ID is required'
            ])->setStatusCode(400);
        }
        
        $input = $this->request->getJSON(true);
        
        if (!isset($input['event_type']) || !isset($input['title'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Event type and title are required'
            ])->setStatusCode(400);
        }
        
        try {
            // Validate that work order exists
            $workOrder = $this->workOrderModel->find($workOrderId);
            if (!$workOrder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Work Order not found'
                ])->setStatusCode(404);
            }
            
            // Log the event
            $eventId = $this->auditLogModel->logEvent(
                $input['event_type'],
                $input['title'],
                $input['description'] ?? null,
                $input['target_user_id'] ?? null,
                $input['old_value'] ?? null,
                $input['new_value'] ?? null,
                'work_orders',
                'work_order',
                $workOrderId
            );
            
            if ($eventId) {
                // Get the created event
                $event = $this->auditLogModel->select('
                    audit_logs.*,
                    users.first_name, users.last_name
                ')
                ->join('users', 'users.id = audit_logs.user_id', 'left')
                ->find($eventId);
                
                // Format the event manually since formatTimelineResults is private
                if ($event) {
                    $event['user_name'] = trim(($event['first_name'] ?? '') . ' ' . ($event['last_name'] ?? ''));
                    if (empty($event['user_name'])) {
                        $event['user_name'] = 'System';
                    }
                    if (!empty($event['created_at'])) {
                        $event['formatted_date'] = date('M j, Y g:i A', strtotime($event['created_at']));
                    }
                }
                $formattedEvent = $event;
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Timeline event logged successfully',
                    'event' => $formattedEvent
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to log timeline event'
                ])->setStatusCode(400);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error logging timeline event: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
