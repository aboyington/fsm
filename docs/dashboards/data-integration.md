# Dashboard Data Integration Guide

## Overview

This document describes the data integration patterns and implementation details for the FSM dashboard system, specifically focusing on real database connections and session-based filtering implemented in v2.10.0-alpha.

## Data Architecture

### Database Schema Integration

The dashboard system integrates with several key database tables:

```sql
-- Primary tables used in dashboard integration
service_appointments
├── id (Primary Key)
├── work_order_id (Foreign Key)
├── technician_id (Foreign Key)
├── appointment_date
├── appointment_time
├── duration
├── status ('scheduled', 'in_progress', 'completed', 'cancelled')
├── notes
├── created_at
└── updated_at

work_orders
├── id (Primary Key)
├── summary
├── priority ('low', 'medium', 'high')
└── status

requests
├── id (Primary Key)
├── request_number
├── request_name
├── priority
└── status

estimates
├── id (Primary Key)
├── email
├── grand_total
├── status
└── created_at
```

## Dashboard Data Methods

### Technician Dashboard Integration

The `getTechnicianStats()` method implements real data integration:

```php
private function getTechnicianStats()
{
    $db = \Config\Database::connect();
    
    // Get user ID from session (default to 1 for demo)
    $user_id = session()->get('user_id') ?? 1;
    
    // Valid appointment statuses
    $valid_statuses = ['scheduled', 'in_progress', 'completed', 'cancelled'];
    
    // Build WHERE clause for status filtering
    $status_placeholders = implode(',', array_fill(0, count($valid_statuses), '?'));
    
    // Get appointment counts by status
    $appointments = $db->query("
        SELECT status, COUNT(*) as count 
        FROM service_appointments 
        WHERE technician_id = ? AND status IN ({$status_placeholders})
        GROUP BY status
    ", array_merge([$user_id], $valid_statuses))->getResultArray();
    
    // Process results and return structured data
    return [
        'upcoming_appointments' => $stats['scheduled'] ?? 0,
        'in_progress_appointments' => $stats['in_progress'] ?? 0,
        'completed_appointments' => $stats['completed'] ?? 0,
        'total_trips' => array_sum($stats),
        'my_dispatched_appointments' => $this->getAppointmentsByStatus($user_id, 'scheduled'),
        'my_in_progress_appointments' => $this->getAppointmentsByStatus($user_id, 'in_progress'),
        'my_completed_appointments' => $this->getAppointmentsByStatus($user_id, 'completed'),
        'my_cancelled_appointments' => $this->getAppointmentsByStatus($user_id, 'cancelled')
    ];
}
```

### Session-Based Filtering

The system implements session-based filtering to show user-specific data:

```php
// Session handling with fallback
$user_id = session()->get('user_id') ?? 1; // Default to technician ID 1 for demo

// Filter appointments by technician
private function getAppointmentsByStatus($technician_id, $status)
{
    $db = \Config\Database::connect();
    return $db->query("
        SELECT sa.*, wo.summary 
        FROM service_appointments sa
        LEFT JOIN work_orders wo ON sa.work_order_id = wo.id
        WHERE sa.technician_id = ? AND sa.status = ?
        ORDER BY sa.appointment_date ASC
    ", [$technician_id, $status])->getResultArray();
}
```

## Data Flow Patterns

### 1. Controller → Model → Database

```
DashboardController::technicianView()
    ↓
getTechnicianStats()
    ↓
Database Query Execution
    ↓
Result Processing
    ↓
View Data Structure
```

### 2. Status-Based Filtering

```sql
-- Example queries used in dashboard integration

-- Count appointments by status
SELECT status, COUNT(*) as count 
FROM service_appointments 
WHERE technician_id = ? AND status IN ('scheduled', 'in_progress', 'completed', 'cancelled')
GROUP BY status;

-- Get detailed appointment data
SELECT sa.*, wo.summary 
FROM service_appointments sa
LEFT JOIN work_orders wo ON sa.work_order_id = wo.id
WHERE sa.technician_id = ? AND sa.status = ?
ORDER BY sa.appointment_date ASC;
```

## Sample Data Structure

### Current Test Data

The system includes sample data for testing dashboard integration:

```sql
-- Sample service appointments data
INSERT INTO service_appointments VALUES
(1, 1, 1, '2025-01-24', '09:00:00', 120, 'scheduled', 'Regular maintenance visit', '2025-01-23', '2025-01-23'),
(2, 2, 2, '2025-01-24', '11:00:00', 90, 'in_progress', 'Equipment repair in progress', '2025-01-23', '2025-01-23'),
(3, 3, 2, '2025-01-23', '14:00:00', 60, 'completed', 'Service completed successfully', '2025-01-23', '2025-01-23'),
(4, 4, 2, '2025-01-25', '10:00:00', 180, 'scheduled', 'Quarterly inspection', '2025-01-23', '2025-01-23'),
(5, 5, 3, '2025-01-23', '16:00:00', 45, 'cancelled', 'Customer cancelled appointment', '2025-01-23', '2025-01-23'),
(6, 6, 3, '2025-01-26', '08:00:00', 150, 'scheduled', 'Installation service', '2025-01-23', '2025-01-23');
```

### Data Distribution by Technician

**Technician ID 1:**
- 1 scheduled appointment

**Technician ID 2:**
- 1 scheduled appointment
- 1 in-progress appointment  
- 1 completed appointment

**Technician ID 3:**
- 1 scheduled appointment
- 1 cancelled appointment

## Error Handling

### Empty Data States

The system properly handles empty data scenarios:

```php
// Check for empty results
if (!empty($appointments) && count($appointments) > 0) {
    // Display appointment data
    foreach ($appointments as $appointment) {
        // Render appointment row
    }
} else {
    // Display empty state
    echo '<div class="text-center py-5">';
    echo '<i class="bi bi-calendar-week display-1 text-muted"></i>';
    echo '<p class="text-muted mt-3">No Records Found</p>';
    echo '</div>';
}
```

### Database Connection Handling

```php
try {
    $db = \Config\Database::connect();
    $result = $db->query($sql, $params);
    return $result->getResultArray();
} catch (\Exception $e) {
    log_message('error', 'Dashboard data query failed: ' . $e->getMessage());
    return []; // Return empty array on error
}
```

## Performance Considerations

### Query Optimization

1. **Indexed Columns**: Ensure `technician_id` and `status` columns are indexed
2. **Efficient JOINs**: Use LEFT JOIN only when necessary
3. **Limit Results**: Consider pagination for large datasets
4. **Caching**: Implement caching for frequently accessed data

```sql
-- Recommended indexes for performance
CREATE INDEX idx_service_appointments_technician_status 
ON service_appointments(technician_id, status);

CREATE INDEX idx_service_appointments_date 
ON service_appointments(appointment_date);
```

### Memory Management

```php
// Process large datasets efficiently
private function getAppointmentsByStatus($technician_id, $status, $limit = 50)
{
    $db = \Config\Database::connect();
    return $db->query("
        SELECT sa.*, wo.summary 
        FROM service_appointments sa
        LEFT JOIN work_orders wo ON sa.work_order_id = wo.id
        WHERE sa.technician_id = ? AND sa.status = ?
        ORDER BY sa.appointment_date ASC
        LIMIT ?
    ", [$technician_id, $status, $limit])->getResultArray();
}
```

## Security Implementation

### Data Access Control

```php
// Ensure users can only access their own data
private function validateTechnicianAccess($user_id, $technician_id)
{
    // In production, implement proper role-based access control
    if ($user_id !== $technician_id && !$this->hasAdminRole($user_id)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }
}
```

### SQL Injection Prevention

```php
// Always use parameterized queries
$appointments = $db->query("
    SELECT * FROM service_appointments 
    WHERE technician_id = ? AND status = ?
", [$technician_id, $status])->getResultArray();

// Never concatenate user input directly
// BAD: "WHERE technician_id = " . $user_input
// GOOD: "WHERE technician_id = ?" with parameter binding
```

## Testing Data Integration

### Manual Testing Steps

1. **Database Setup**: Ensure sample data exists
2. **Session Testing**: Test with different user sessions
3. **Status Filtering**: Verify each status filter works correctly
4. **Empty States**: Test scenarios with no data
5. **Error Handling**: Test database connection failures

### Test Queries

```sql
-- Verify data exists for testing
SELECT technician_id, status, COUNT(*) 
FROM service_appointments 
GROUP BY technician_id, status 
ORDER BY technician_id;

-- Expected results:
-- technician_id=1, status=scheduled, count=1
-- technician_id=2, status=completed, count=1
-- technician_id=2, status=in_progress, count=1
-- technician_id=2, status=scheduled, count=1
-- technician_id=3, status=cancelled, count=1
-- technician_id=3, status=scheduled, count=1
```

## Future Enhancements

### Real-Time Updates

```javascript
// Future implementation: WebSocket integration
const socket = new WebSocket('ws://localhost:8080/dashboard-updates');
socket.onmessage = function(event) {
    const update = JSON.parse(event.data);
    if (update.type === 'appointment_status_change') {
        refreshDashboardSection(update.section);
    }
};
```

### Advanced Filtering

```php
// Future enhancement: date range filtering
private function getAppointmentsByDateRange($technician_id, $start_date, $end_date)
{
    return $db->query("
        SELECT sa.*, wo.summary 
        FROM service_appointments sa
        LEFT JOIN work_orders wo ON sa.work_order_id = wo.id
        WHERE sa.technician_id = ? 
        AND sa.appointment_date BETWEEN ? AND ?
        ORDER BY sa.appointment_date ASC
    ", [$technician_id, $start_date, $end_date])->getResultArray();
}
```

### Caching Strategy

```php
// Future implementation: Redis caching
private function getCachedTechnicianStats($technician_id)
{
    $cache_key = "technician_stats_{$technician_id}";
    $cached_data = cache()->get($cache_key);
    
    if ($cached_data === null) {
        $cached_data = $this->getTechnicianStats();
        cache()->save($cache_key, $cached_data, 300); // 5 minutes TTL
    }
    
    return $cached_data;
}
```

## Troubleshooting

### Common Issues

1. **No Data Displaying**: Check database connection and sample data
2. **Wrong Data Showing**: Verify session handling and user ID filtering
3. **Performance Issues**: Check query optimization and indexes
4. **Empty States Not Showing**: Verify conditional logic in views

### Debug Queries

```php
// Debug data availability
private function debugDataAvailability()
{
    $db = \Config\Database::connect();
    
    echo "Total appointments: " . $db->table('service_appointments')->countAllResults() . "\n";
    echo "Appointments by technician:\n";
    
    $results = $db->query("
        SELECT technician_id, COUNT(*) as count 
        FROM service_appointments 
        GROUP BY technician_id
    ")->getResultArray();
    
    foreach ($results as $row) {
        echo "Technician {$row['technician_id']}: {$row['count']} appointments\n";
    }
}
```

---

*Last Updated: January 23, 2025*  
*Version: 2.10.0-alpha*  
*Implementation Status: Active*
