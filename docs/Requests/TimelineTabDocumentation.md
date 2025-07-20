# Timeline Tab Documentation

## Overview

The Timeline tab provides a chronological audit trail of all actions and events related to a request. It displays a dynamic, filterable timeline showing when events occurred, who performed them, and detailed descriptions of each action.

## Features

- **Real-time Event Logging**: Automatically logs all request-related activities
- **Chronological Display**: Events shown in reverse chronological order (newest first)
- **Filtering Options**: Filter events by time periods (All Time, Today, Yesterday, Last Week, Last Month, Last Year)
- **User Attribution**: Shows which user performed each action
- **Event Categorization**: Different icons and colors for different event types
- **Refresh Functionality**: Manual refresh button to reload timeline data
- **Empty State Handling**: Graceful handling when no events exist

## Backend Implementation

### 1. Database Schema

The audit logging system uses the `audit_logs` table with the following structure:

```sql
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_type VARCHAR(100) NOT NULL,
    description TEXT,
    module VARCHAR(50) NOT NULL,
    user_id INT,
    entity_type VARCHAR(50),
    entity_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### 2. AuditLogModel

Located at: `app/Models/AuditLogModel.php`

#### Key Methods:

**logEvent()** - Logs a new audit event
```php
public function logEvent($eventType, $description, $module, $userId, $entityType = null, $entityId = null)
```

**getRequestTimeline()** - Retrieves timeline events for a specific request
```php
public function getRequestTimeline($requestId, $filter = 'all')
```

**applyDateFilter()** - Applies date filtering to timeline queries
```php
private function applyDateFilter($builder, $filter)
```

**formatTimelineResults()** - Formats timeline data for frontend consumption
```php
private function formatTimelineResults($results)
```

### 3. Controller Integration

#### RequestsController Updates

Added timeline API endpoint:

```php
public function getTimeline($id)
{
    $filter = $this->request->getGet('filter') ?? 'all';
    $timeline = $this->auditLogModel->getRequestTimeline($id, $filter);
    
    return $this->response->setJSON([
        'success' => true,
        'timeline' => $timeline
    ]);
}
```

#### Route Configuration

Added to `app/Config/Routes.php`:

```php
$routes->get('request/timeline/(:num)', 'Requests::getTimeline/$1');
```

### 4. Event Logging Integration

#### RequestModel Auto-logging

The `RequestModel` automatically logs events for:
- `request_created` - When a new request is created
- `request_updated` - When a request is modified
- `request_deleted` - When a request is deleted

#### Notes Integration

`RequestNotesController` logs:
- `request_note_added` - When a note is created
- `request_note_updated` - When a note is edited
- `request_note_deleted` - When a note is deleted

#### Attachments Integration

`AttachmentsController` logs:
- `request_attachment_added` - When a file is uploaded
- `request_attachment_deleted` - When a file is deleted

## Frontend Implementation

### 1. HTML Structure

The timeline tab is integrated into the existing tab system:

```html
<div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
    <!-- Timeline content loaded dynamically via JavaScript -->
</div>
```

### 2. JavaScript Functions

#### Core Timeline Functions

**loadTimeline()** - Main function to load timeline data
```javascript
function loadTimeline(requestId, filter = 'all')
```

**renderTimeline()** - Renders timeline HTML from data
```javascript
function renderTimeline(timelineData, requestId, filter)
```

**changeTimelineFilter()** - Handles filter changes
```javascript
function changeTimelineFilter()
```

#### Event Styling Functions

**getTimelineEventIcon()** - Returns appropriate icon for event type
```javascript
function getTimelineEventIcon(eventType)
```

**getTimelineEventColor()** - Returns appropriate color for event type
```javascript
function getTimelineEventColor(eventType)
```

### 3. Event Types and Styling

| Event Type | Icon | Color | Description |
|------------|------|-------|-------------|
| `request_created` | `bi-plus-circle` | Success (Green) | Request creation |
| `request_updated` | `bi-pencil-square` | Primary (Blue) | Request modifications |
| `request_deleted` | `bi-trash` | Danger (Red) | Request deletion |
| `request_note_added` | `bi-journal-plus` | Info (Light Blue) | Note creation |
| `request_note_updated` | `bi-journal-text` | Primary (Blue) | Note edits |
| `request_note_deleted` | `bi-journal-minus` | Danger (Red) | Note deletion |
| `request_attachment_added` | `bi-paperclip` | Info (Light Blue) | File uploads |
| `request_attachment_deleted` | `bi-trash` | Danger (Red) | File deletion |

### 4. CSS Styling

Key CSS classes for timeline appearance:

```css
.timeline-item {
    position: relative;
}

.timeline-item.timeline-item-connected::after {
    content: '';
    position: absolute;
    left: 19px;
    top: 40px;
    bottom: -16px;
    width: 2px;
    background-color: #e9ecef;
    z-index: 1;
}
```

## API Endpoints

### GET `/work-order-management/request/timeline/{id}`

**Parameters:**
- `id` (required) - Request ID
- `filter` (optional) - Time filter (`all`, `today`, `yesterday`, `last_week`, `last_month`, `last_year`)

**Response:**
```json
{
    "success": true,
    "timeline": [
        {
            "id": 1,
            "event_type": "request_created",
            "title": "Request Created",
            "description": "Request REQ-001 was created",
            "user_name": "John Doe",
            "module": "requests",
            "created_at": "2025-01-20 10:30:00",
            "formatted_date": "Jan 20, 2025 10:30 AM"
        }
    ]
}
```

## Implementation for Other Modules

### Step 1: Database Migration

Ensure the `audit_logs` table includes `entity_type` and `entity_id` fields:

```php
// Migration: Add entity fields to audit_logs
$fields = [
    'entity_type' => [
        'type' => 'VARCHAR',
        'constraint' => 50,
        'null' => true,
        'after' => 'user_id'
    ],
    'entity_id' => [
        'type' => 'INT',
        'null' => true,
        'after' => 'entity_type'
    ]
];

$this->forge->addColumn('audit_logs', $fields);
```

### Step 2: Model Integration

Update your main model to include audit logging:

```php
class YourModel extends BaseModel
{
    protected $auditLogModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->auditLogModel = new AuditLogModel();
    }
    
    // Override insert method
    public function insert($data = null, bool $returnID = true)
    {
        $result = parent::insert($data, $returnID);
        
        if ($result && $returnID) {
            $this->auditLogModel->logEvent(
                'entity_created',
                'Entity created with ID: ' . $result,
                'your_module',
                session('user_id') ?? 1,
                'your_entity_type',
                $result
            );
        }
        
        return $result;
    }
    
    // Similar for update() and delete() methods
}
```

### Step 3: Controller Timeline Endpoint

Add timeline method to your controller:

```php
public function getTimeline($id)
{
    try {
        $auditLogModel = new AuditLogModel();
        $filter = $this->request->getGet('filter') ?? 'all';
        $timeline = $auditLogModel->getEntityTimeline($id, 'your_entity_type', $filter);
        
        return $this->response->setJSON([
            'success' => true,
            'timeline' => $timeline
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Timeline error: ' . $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => 'Failed to load timeline'
        ]);
    }
}
```

### Step 4: Add Generic Timeline Method to AuditLogModel

```php
public function getEntityTimeline($entityId, $entityType, $filter = 'all')
{
    $builder = $this->select('
        audit_logs.*,
        CASE 
            WHEN audit_logs.event_type LIKE "%_created" THEN "Created"
            WHEN audit_logs.event_type LIKE "%_updated" THEN "Updated" 
            WHEN audit_logs.event_type LIKE "%_deleted" THEN "Deleted"
            ELSE audit_logs.event_type
        END as title,
        CONCAT(users.first_name, " ", users.last_name) as user_name
    ')
    ->join('users', 'users.id = audit_logs.user_id', 'left')
    ->where('audit_logs.entity_type', $entityType)
    ->where('audit_logs.entity_id', $entityId)
    ->orderBy('audit_logs.created_at', 'DESC');

    $this->applyDateFilter($builder, $filter);
    
    $results = $builder->findAll();
    return $this->formatTimelineResults($results);
}
```

### Step 5: Frontend Integration

1. **Add Timeline Tab to HTML:**
```html
<li class="nav-item" role="presentation">
    <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="false">
        <i class="bi bi-clock-history"></i> Timeline
    </button>
</li>
```

2. **Copy Timeline JavaScript Functions:**
   - Copy all timeline-related functions from the requests implementation
   - Update API endpoint URL to match your module
   - Customize event types and styling as needed

3. **Add Event Type Mapping:**
```javascript
function getTimelineEventIcon(eventType) {
    const iconMap = {
        'entity_created': 'bi-plus-circle',
        'entity_updated': 'bi-pencil-square',
        'entity_deleted': 'bi-trash',
        // Add your custom event types
        'default': 'bi-clock-history'
    };
    return iconMap[eventType] || iconMap['default'];
}
```

### Step 6: Route Configuration

Add route for your timeline endpoint:

```php
$routes->get('your-module/timeline/(:num)', 'YourController::getTimeline/$1');
```

## Event Logging Best Practices

### 1. Consistent Event Naming

Use consistent naming patterns:
- `{module}_{entity}_{action}` (e.g., `work_order_created`, `invoice_updated`)
- Actions: `created`, `updated`, `deleted`, `status_changed`, `assigned`, etc.

### 2. Meaningful Descriptions

Provide clear, actionable descriptions:
```php
// Good
$this->auditLogModel->logEvent(
    'work_order_status_changed',
    'Status changed from "In Progress" to "Completed"',
    'work_orders',
    $userId,
    'work_order',
    $workOrderId
);

// Avoid generic descriptions
$this->auditLogModel->logEvent(
    'work_order_updated',
    'Work order updated',
    'work_orders',
    $userId
);
```

### 3. User Context

Always provide user context when available:
```php
$userId = session('user_id') ?? 1; // Fallback for system actions
```

### 4. Error Handling

Wrap audit logging in try-catch to prevent audit failures from breaking main functionality:
```php
try {
    $this->auditLogModel->logEvent(...);
} catch (\Exception $e) {
    log_message('error', 'Audit logging failed: ' . $e->getMessage());
    // Continue with main operation
}
```

## Security Considerations

1. **Access Control**: Ensure timeline endpoints have proper authentication
2. **Data Sanitization**: All timeline data is escaped in the frontend
3. **Sensitive Information**: Avoid logging sensitive data in descriptions
4. **Performance**: Consider pagination for entities with many events

## Performance Optimization

1. **Database Indexes**: Add indexes on frequently queried columns:
   ```sql
   CREATE INDEX idx_audit_entity ON audit_logs(entity_type, entity_id);
   CREATE INDEX idx_audit_created ON audit_logs(created_at);
   ```

2. **Caching**: Consider caching timeline data for frequently accessed entities

3. **Pagination**: Implement pagination for timelines with many events

## Testing

### Unit Tests

Test audit logging integration:
```php
public function testEventLogging()
{
    $model = new YourModel();
    $result = $model->insert(['name' => 'Test Entity']);
    
    $auditModel = new AuditLogModel();
    $logs = $auditModel->where('entity_id', $result)->findAll();
    
    $this->assertCount(1, $logs);
    $this->assertEquals('entity_created', $logs[0]['event_type']);
}
```

### Integration Tests

Test timeline API endpoints:
```php
public function testTimelineEndpoint()
{
    $response = $this->get('/your-module/timeline/1');
    
    $response->assertStatus(200);
    $response->assertJSONStructure([
        'success',
        'timeline' => [
            '*' => [
                'event_type',
                'title',
                'description',
                'user_name',
                'created_at'
            ]
        ]
    ]);
}
```

## Troubleshooting

### Common Issues

1. **Timeline Not Loading**
   - Check API endpoint URL
   - Verify route configuration
   - Check browser console for JavaScript errors

2. **Events Not Being Logged**
   - Ensure `AuditLogModel` is properly instantiated
   - Check if `logEvent()` is being called
   - Verify database table exists and is accessible

3. **Incorrect Event Display**
   - Check event type mapping in JavaScript
   - Verify icon and color configurations
   - Ensure CSS classes are loaded

### Debug Tips

1. **Enable Debug Logging**:
   ```php
   log_message('debug', 'Timeline data: ' . json_encode($timeline));
   ```

2. **Browser Console**:
   Check for JavaScript errors and network request failures

3. **Database Queries**:
   Enable query logging to verify audit events are being stored

## Future Enhancements

- **Real-time Updates**: WebSocket integration for live timeline updates
- **Advanced Filtering**: Filter by event type, user, or custom criteria
- **Export Functionality**: Export timeline as PDF or CSV
- **Event Grouping**: Group related events (e.g., bulk operations)
- **Mentions System**: @mention users in event descriptions
- **Rich Media**: Support for images or attachments in timeline events
