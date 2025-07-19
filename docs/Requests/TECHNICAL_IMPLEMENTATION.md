# Technical Implementation - Requests Module

## Architecture Overview
The Requests module follows the MVC (Model-View-Controller) pattern implemented in CodeIgniter 4, with clean separation of concerns between data access, business logic, and presentation layers.

## File Structure

```
app/
├── Controllers/
│   └── RequestsController.php          # Main controller handling HTTP requests
├── Models/
│   └── RequestModel.php                 # Data access layer for requests
├── Views/
│   └── requests/
│       ├── index.php                   # Main requests listing page
│       ├── view.php                    # Request detail view
│       ├── _requestsList.php           # Partial for requests table rows
│       └── _modal.php                  # Create/edit modal component
public/
└── js/
    └── requests.js                     # Client-side JavaScript functionality
```

## Controller Implementation

### RequestsController Class

**Namespace**: `App\Controllers`  
**Extends**: `BaseController`

**Dependencies**:
- `RequestModel` - Primary data access
- `ClientModel` - Company information
- `ContactModel` - Contact management
- `TerritoryModel` - Territory associations
- `UserSessionModel` - User authentication

### Key Methods

#### `index()`
**Purpose**: Display main requests listing page  
**Returns**: Rendered view with requests data  
**Data Provided**:
- `$requests` - Array of request records with joined data
- `$companies` - List of companies for filtering
- `$total_requests` - Total request count
- `$pending_requests` - Pending status count
- `$in_progress_requests` - In progress status count
- `$on_hold_requests` - On hold status count
- `$completed_requests` - Completed status count#### `view($id)`
**Purpose**: Display detailed request information  
**Parameters**: `$id` - Request ID  
**Returns**: Rendered detail view with request data  
**Features**:
- Comprehensive request information
- Related records (company, contact, creator)
- Tabbed interface for different data sections
- Action buttons for request management

#### `store()`
**Purpose**: Create new request  
**Method**: POST  
**Validation**: Server-side validation with error handling  
**Response**: JSON with success/error status  

#### `update($id)`
**Purpose**: Update existing request  
**Parameters**: `$id` - Request ID  
**Method**: POST  
**Validation**: Server-side validation  
**Response**: JSON with success/error status  

#### `delete($id)`
**Purpose**: Delete request record  
**Parameters**: `$id` - Request ID  
**Method**: POST  
**Security**: Confirmation required  
**Response**: JSON with success/error status  

### Authentication & Authorization

#### Session Management
- Uses `UserSessionModel` for session validation
- `getCurrentUserId()` method extracts user from session token
- All actions require valid authentication

## Model Implementation

### RequestModel Class

**Namespace**: `App\Models`  
**Extends**: `BaseModel`  
**Primary Table**: `requests`

#### Key Properties
- `$table = 'requests'`
- `$primaryKey = 'id'`
- `$allowedFields` - Defines fields that can be mass-assigned
- `$useTimestamps = true` - Automatic created_at/updated_at
- `$dateFormat = 'datetime'`

#### Core Methods

##### `getRequests()`
**Purpose**: Retrieve requests with joined related data  
**Returns**: Array of requests with company and contact information  
**Query Features**:
- LEFT JOINs with clients and contacts tables
- User information from users table
- Proper field aliasing to prevent conflicts

**SQL Structure**:
```sql
SELECT r.*, 
       c.client_name,
       cont.first_name as contact_first_name,
       cont.last_name as contact_last_name,
       u.first_name as created_by_first_name,
       u.last_name as created_by_last_name
FROM requests r
LEFT JOIN clients c ON r.company_id = c.id
LEFT JOIN contacts cont ON r.contact_id = cont.id
LEFT JOIN users u ON r.created_by = u.id
ORDER BY r.created_at DESC
```

##### `getRequest($id)`
**Purpose**: Get single request with all related data  
**Parameters**: `$id` - Request ID  
**Returns**: Single request record with joins  
**Usage**: Detail views and edit operations

##### `getRequestCounts()`
**Purpose**: Get count statistics by status  
**Returns**: Array with count totals  
**Used For**: Dashboard metrics and filter statistics

### Database Relationships

#### Primary Relationships
- **requests.company_id** → **clients.id** (Optional)
- **requests.contact_id** → **contacts.id** (Optional)
- **requests.created_by** → **users.id** (Required)
- **requests.territory_id** → **territories.id** (Optional)

#### Foreign Key Constraints
- All relationships use proper foreign key constraints
- Cascading rules defined for data integrity
- NULL allowed for optional relationships

## View Implementation

### Template Engine
**Engine**: CodeIgniter 4 View Parser  
**Extension**: `.php`  
**Layout**: Uses layout inheritance with `layouts/main`

### Key View Files

#### `index.php`
**Purpose**: Main requests listing page  
**Features**:
- Dynamic empty state vs. data state
- Filter and search interface
- Responsive table layout
- Statistics summary
- Create button integration

**Conditional Rendering**:
```php
<?php if (empty($requests)): ?>
    <!-- Empty State -->
<?php else: ?>
    <!-- Requests Table -->
<?php endif; ?>
```

#### `view.php`
**Purpose**: Request detail view  
**Features**:
- Two-column layout (sidebar + main content)
- Tabbed interface for different data sections
- Action buttons with JavaScript integration
- Responsive design considerations

**Tab Implementation**:
- Bootstrap nav-tabs component
- JavaScript-powered tab switching
- Content loaded dynamically where needed

#### `_requestsList.php`
**Purpose**: Reusable table rows component  
**Usage**: Included in both initial page load and AJAX updates  
**Features**:
- Dynamic status and priority badges
- Action button groups
- Proper data escaping for security

#### `_modal.php`
**Purpose**: Create/edit modal component  
**Features**:
- Bootstrap modal structure
- Form validation integration
- Dependent dropdown functionality

## JavaScript Implementation

### Core File: `requests.js`

#### Key Functions

##### Search and Filter
```javascript
// Real-time search implementation
const searchInput = document.getElementById('searchRequests');
searchInput.addEventListener('input', debounce(performSearch, 300));

// Filter change handlers
const statusFilter = document.getElementById('statusFilter');
statusFilter.addEventListener('change', applyFilters);
```

##### AJAX Operations
```javascript
// Request deletion with confirmation
function deleteRequest(id) {
    if (confirm('Are you sure?')) {
        fetch(`${baseUrl}/work-order-management/request/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => handleResponse(data));
    }
}
```

##### Table Updates
```javascript
// Dynamic table row generation
function updateRequestsTable(requests) {
    const tbody = document.getElementById('requestsTableBody');
    tbody.innerHTML = requests.map(request => generateTableRow(request)).join('');
}
```

#### Security Features
- HTML escaping for dynamic content
- CSRF token validation
- XSS prevention measures
- Input sanitization

#### Performance Optimizations
- Debounced search input (300ms)
- Lazy loading for large datasets
- Efficient DOM manipulation
- Minimal HTTP requests

## Error Handling

### Server-Side Error Handling
- **Validation Errors**: Returned as JSON with field-specific messages
- **Database Errors**: Logged and generic error shown to user
- **Authentication Errors**: Redirect to login page
- **Authorization Errors**: 403 Forbidden response

### Client-Side Error Handling
- **Network Errors**: User-friendly error messages
- **Form Validation**: Real-time validation feedback
- **AJAX Failures**: Graceful degradation with retry options

### Logging
- All errors logged to CodeIgniter log files
- User actions tracked for audit purposes
- Performance metrics collected

## Security Implementation

### Input Validation
- **Server-Side**: All inputs validated using CodeIgniter validation
- **Client-Side**: Additional validation for user experience
- **Sanitization**: All database inputs sanitized

### Output Escaping
- All user data escaped using `esc()` function
- HTML attributes properly quoted
- JavaScript variables safely encoded

### CSRF Protection
- CSRF tokens required for all state-changing operations
- Token validation on every POST/PUT/DELETE request
- Automatic token refresh for long-running sessions

### SQL Injection Prevention
- Query Builder used for all database operations
- Prepared statements for dynamic queries
- No direct SQL string concatenation

## Performance Considerations

### Database Optimization
- **Indexes**: Proper indexing on frequently queried columns
- **Query Optimization**: Efficient JOINs and WHERE clauses
- **Pagination**: Large datasets paginated for performance

### Frontend Optimization
- **Asset Minification**: CSS and JavaScript minified in production
- **Caching**: Browser caching headers set appropriately
- **Lazy Loading**: Non-critical content loaded on demand

### Caching Strategy
- **Database Queries**: Frequently accessed data cached
- **View Fragments**: Reusable components cached
- **Static Assets**: Long-term caching for CSS/JS files

---

*Last Updated*: January 2025  
*Version*: 1.0  
*Module*: Requests - Technical Implementation Documentation