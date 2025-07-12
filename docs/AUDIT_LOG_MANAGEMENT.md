# FSM Audit Log Management Documentation

## Overview
The Audit Log Management system provides comprehensive tracking and monitoring of all system activities, user actions, and entity changes within the FSM platform. This dual-purpose logging system ensures compliance, security monitoring, and data integrity tracking.

## Features Overview

### Dual-Tab Interface
- **Audit Log Tab**: System-wide activity tracking
- **Entity Log Tab**: Detailed entity change tracking with before/after values

### Advanced Filtering
- Date range filtering (Today, Yesterday, Last 7/30/90 days, Last Year)
- User-specific filtering
- Module/action filtering
- Entity type and action filtering

### Real-time Filtering
- Automatic filter application with 500ms debounce
- No "Apply" button required - filters activate on change
- Instant URL parameter updates for shareable filtered views
- Tab state preservation across filter changes

## Audit Log Tab

### Purpose
Tracks system-wide user activities, authentication events, and administrative actions for security monitoring and compliance.

### Tracked Activities
- **User Authentication:**
  - Login events
  - Logout events
  - Failed login attempts
  - Session timeouts

- **User Management:**
  - User creation
  - User profile updates
  - Password changes
  - Role/status changes
  - User deletions

- **System Settings:**
  - Organization profile changes
  - Currency modifications
  - Business hours updates
  - Fiscal year changes

- **Security Events:**
  - Permission changes
  - Profile assignments
  - Security setting modifications

### Data Structure
Each audit log entry contains:
```php
[
    'id' => 1,
    'user_id' => 2,
    'user_name' => 'John Smith',
    'module' => 'users',
    'event_type' => 'user_created',
    'description' => 'User account created for jane.doe@example.com',
    'ip_address' => '192.168.1.100',
    'user_agent' => 'Mozilla/5.0...',
    'created_at' => '2024-01-12 14:30:00',
    'formatted_date' => 'Jan 12, 2024 2:30 PM',
    'sub_type_display' => 'USERS',
    'action_display' => 'CREATE'
]
```

### Filtering Options

#### Date Range Filters
- **Today**: Activities from today
- **Yesterday**: Activities from yesterday only
- **Last 7 days**: Activities from the past week
- **Last 30 days**: Activities from the past month (default)
- **Last 90 days**: Activities from the past quarter
- **Last Year**: Activities from the past year

#### User Filter
- Filter by specific user who performed the action
- Dropdown with all system users
- Shows user's full name and email

#### Sub Type Filter
- **Users**: User management activities
- **Customers**: Customer-related activities
- **Orders**: Work order activities
- **Holidays**: Holiday management
- **Organization Details**: Org settings changes
- **Other Settings**: Miscellaneous settings

#### Action Filter
- **Create**: Creation events
- **Update**: Modification events
- **Delete**: Deletion events
- **Login**: Authentication events
- **Logout**: Session termination
- **Disable**: Account/feature disabling

## Entity Log Tab

### Purpose
Provides detailed tracking of entity changes with before/after value comparison for data integrity and change auditing.

### Tracked Entities
- **Customers**: Customer record changes
- **Orders**: Work order modifications
- **Users**: User profile changes
- **Territories**: Territory updates
- **Skills**: Skill modifications
- **Profiles**: Profile and permission changes

### Data Structure
Each entity log entry contains:
```php
[
    'id' => 1,
    'entity_type' => 'Customer',
    'entity_id' => 'CUST001',
    'entity_name' => 'Acme Corporation',
    'action' => 'Update',
    'user_id' => 2,
    'user_name' => 'John Smith',
    'description' => 'Customer contact information updated',
    'old_values' => '{"email":"old@acme.com","phone":"555-0001"}',
    'new_values' => '{"email":"new@acme.com","phone":"555-0002"}',
    'created_at' => '2024-01-12 14:30:00',
    'formatted_date' => 'Jan 12, 2024 2:30 PM'
]
```

### Change Tracking
- **Old Values**: JSON-formatted previous state
- **New Values**: JSON-formatted new state
- **Description**: Human-readable change summary
- **Entity Identification**: Clear entity type, ID, and name

### Filtering Options

#### Entity Type Filter
- **Customer**: Customer record changes
- **Order**: Work order modifications
- **User**: User account changes
- **Territory**: Territory updates
- **Skill**: Skill definition changes
- **Profile**: Profile and permission modifications

#### Entity Action Filter
- **Create**: New entity creation
- **Update**: Entity modifications
- **Delete**: Entity removal
- **Assign**: Assignment operations
- **Unassign**: Removal of assignments

## Technical Implementation

### Database Schema

#### audit_logs Table
```sql
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    module VARCHAR(50),
    event_type VARCHAR(50),
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_module (module),
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at)
);
```

#### entity_logs Table (Planned)
```sql
CREATE TABLE entity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    entity_type VARCHAR(50),
    entity_id VARCHAR(100),
    entity_name VARCHAR(255),
    action VARCHAR(50),
    user_id INT,
    description TEXT,
    old_values JSON,
    new_values JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_entity_type (entity_type),
    INDEX idx_entity_id (entity_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);
```

### Controller Methods

#### Main Audit Log Method
```php
public function auditLog()
{
    // Get filter parameters
    $dateFilter = $this->request->getVar('date') ?? 'last-30-days';
    $userFilter = $this->request->getVar('user') ?? '';
    $subTypeFilter = $this->request->getVar('sub_type') ?? '';
    $actionFilter = $this->request->getVar('action') ?? '';
    $tabFilter = $this->request->getVar('tab') ?? 'audit';
    $entityTypeFilter = $this->request->getVar('entity_type') ?? '';
    $entityActionFilter = $this->request->getVar('entity_action') ?? '';
    
    // Apply filters and retrieve data
    // Return formatted view with both audit and entity logs
}
```

#### Sample Entity Log Data
```php
private function getEntityLogs($dateFilter, $userFilter, $entityTypeFilter, $entityActionFilter)
{
    // Generate sample entity logs for demonstration
    // In production, this would query the entity_logs table
    return $filteredEntityLogs;
}
```

### Frontend Implementation

#### Real-time Filter System
```javascript
document.addEventListener('DOMContentLoaded', function() {
    let filterTimeout;
    
    // Function to apply audit log filters
    function applyAuditFilters() {
        const dateFilter = document.getElementById('dateFilter').value;
        const userFilter = document.getElementById('userFilter').value;
        const subTypeFilter = document.getElementById('subTypeFilter').value;
        const actionFilter = document.getElementById('actionFilter').value;
        
        // Build query string
        const params = new URLSearchParams();
        if (dateFilter) params.append('date', dateFilter);
        if (userFilter) params.append('user', userFilter);
        if (subTypeFilter) params.append('sub_type', subTypeFilter);
        if (actionFilter) params.append('action', actionFilter);
        
        // Redirect with filters
        const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }
    
    // Function to handle filter changes with debounce
    function handleFilterChange(filterFunction) {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterFunction, 500);
    }
    
    // Audit Log filter event listeners
    const auditFilters = ['dateFilter', 'userFilter', 'subTypeFilter', 'actionFilter'];
    auditFilters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', function() {
                handleFilterChange(applyAuditFilters);
            });
        }
    });
});
```

#### Tab Switching with State Preservation
```javascript
// Check if we should show Entity Log tab based on URL parameters
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('tab') === 'entity') {
    // Switch to entity log tab
    const entityTab = document.getElementById('entity-log-tab');
    const auditTab = document.getElementById('audit-log-tab');
    const entityPane = document.getElementById('entity-log');
    const auditPane = document.getElementById('audit-log');
    
    // Remove active from audit log
    auditTab.classList.remove('active');
    auditTab.setAttribute('aria-selected', 'false');
    auditPane.classList.remove('show', 'active');
    
    // Add active to entity log
    entityTab.classList.add('active');
    entityTab.setAttribute('aria-selected', 'true');
    entityPane.classList.add('show', 'active');
}
```

## User Interface

### Layout Structure
```
┌─────────────────────────────────────────────────────────┐
│  Audit Log Management                                   │
├─────────────────────────────────────────────────────────┤
│  [Audit Log] [Entity Log]  <-- Tab Navigation          │
├─────────────────────────────────────────────────────────┤
│  Filters: [Date▼] [User▼] [Sub Type▼] [Action▼] [Apply]│
├─────────────────────────────────────────────────────────┤
│  Data Table with:                                      │
│  - Timestamp                                           │
│  - User                                                │
│  - Action/Entity Type                                  │
│  - Description                                         │
│  - Details (expandable for entity logs)               │
└─────────────────────────────────────────────────────────┘
```

### Visual Design
- Clean, professional interface matching FSM design system
- Responsive layout for desktop and mobile
- Clear visual hierarchy with proper spacing
- Intuitive filter controls with dropdown menus
- Expandable rows for detailed entity change information

## Usage Scenarios

### Security Monitoring
1. **Monitor Failed Logins**:
   - Filter by Action: "Login"
   - Review failed authentication attempts
   - Identify potential security threats

2. **Track Administrative Changes**:
   - Filter by Sub Type: "Users"
   - Monitor user creation/modification
   - Review permission changes

### Compliance Auditing
1. **Generate Activity Reports**:
   - Select appropriate date range
   - Export filtered results
   - Document user activities for compliance

2. **Track Data Changes**:
   - Use Entity Log tab
   - Filter by entity type
   - Review before/after values

### Data Integrity Monitoring
1. **Monitor Critical Entity Changes**:
   - Focus on Customer/Order entities
   - Review modification patterns
   - Verify change authorization

2. **Audit Permission Changes**:
   - Filter by Profile entities
   - Track permission modifications
   - Ensure proper authorization

## Best Practices

### For Administrators
1. **Regular Monitoring**:
   - Review audit logs daily
   - Set up alerts for critical events
   - Monitor unusual activity patterns

2. **Filter Optimization**:
   - Use specific date ranges for performance
   - Combine filters for targeted searches
   - Regular cleanup of old logs

3. **Compliance Preparation**:
   - Document audit procedures
   - Regular export of critical logs
   - Maintain retention policies

### For Development
1. **Logging Standards**:
   - Consistent event naming
   - Detailed but concise descriptions
   - Proper entity identification

2. **Performance Considerations**:
   - Index critical columns
   - Implement log rotation
   - Consider archiving strategies

## Integration Points

### Authentication System
- Automatic logging of login/logout events
- Failed authentication tracking
- Session management integration

### User Management
- User creation/modification logging
- Profile assignment tracking
- Status change monitoring

### Entity Management
- Automatic change detection
- Before/after value capture
- User attribution for changes

## Future Enhancements

### Planned Features
1. **Real-time Notifications**:
   - Alert system for critical events
   - Email notifications for admin actions
   - Dashboard widgets for quick monitoring

2. **Advanced Analytics**:
   - Activity pattern analysis
   - User behavior tracking
   - Security threat detection

3. **Export Capabilities**:
   - CSV/PDF export options
   - Scheduled report generation
   - Custom report formats

4. **API Integration**:
   - RESTful API for log access
   - Webhook support for real-time events
   - Third-party SIEM integration

### Long-term Goals
1. **Machine Learning**:
   - Anomaly detection
   - Predictive security analysis
   - Automated threat response

2. **Advanced Compliance**:
   - GDPR compliance tools
   - SOX audit support
   - Industry-specific reporting

## Troubleshooting

### Common Issues
1. **Slow Load Times**:
   - Use more specific date ranges
   - Reduce result set with filters
   - Check database indexing

2. **Missing Logs**:
   - Verify user permissions
   - Check filter settings
   - Confirm logging is enabled

3. **Filter Not Working**:
   - Clear browser cache
   - Check URL parameters
   - Verify filter combinations

### Support Information
- Contact system administrator for access issues
- Report bugs through the standard support channels
- Documentation updates available in the docs folder
