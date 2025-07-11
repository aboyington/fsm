# FSM Territory Management Documentation

## Overview
The Territory Management module enables organizations to define and manage geographic service areas for field operations. Territories help map field technicians and dispatchers to specific regions, ensuring efficient assignment of the right technician to service requests.

## Features

### Core Functionality
- **Territory Definition**: Create and manage geographic service areas
- **Address Mapping**: Define territories using standard address components
- **Status Management**: Active/Inactive status control
- **Search and Filter**: Real-time search with status-based filtering
- **Audit Trail**: Track who created each territory and when

### User Interface
- **List View**: Sortable table with all territories
- **Modal Forms**: Clean interface for adding/editing territories
- **Quick Actions**: Edit and delete buttons for each territory
- **Responsive Design**: Works on desktop and mobile devices

## Database Schema

### Territories Table
```sql
CREATE TABLE territories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    street VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(20),
    country VARCHAR(100),
    status VARCHAR(20) DEFAULT 'active',
    created_by INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Field Descriptions
- **id**: Unique identifier (auto-increment)
- **name**: Territory name (required)
- **description**: Additional details about the territory
- **street**: Street address for territory center/office
- **city**: City name
- **state**: State or province
- **zip_code**: Postal/ZIP code
- **country**: Country name
- **status**: Active or Inactive
- **created_by**: User ID who created the territory
- **created_at**: Creation timestamp
- **updated_at**: Last modification timestamp

## Implementation Details

### Model: TerritoryModel
Location: `app/Models/TerritoryModel.php`

**Key Features:**
- Extends CodeIgniter's Model class
- Automatic timestamp handling
- Validation rules for data integrity
- Allowed fields configuration

**Validation Rules:**
```php
protected $validationRules = [
    'name' => 'required|min_length[3]|max_length[255]',
    'status' => 'in_list[active,inactive]'
];
```

### Controller Methods
Location: `app/Controllers/Settings.php`

#### territories()
- **Purpose**: Display territory list with filtering
- **Route**: GET `/settings/territories`
- **Parameters**: 
  - `status`: Filter by status (active/inactive/all)
  - `search`: Search term for name/description

#### addTerritory()
- **Purpose**: Create new territory
- **Route**: POST `/settings/territories/add`
- **Authentication**: Required
- **CSRF**: Protected

#### getTerritory($id)
- **Purpose**: Retrieve territory details
- **Route**: GET `/settings/territories/get/{id}`
- **Authentication**: Required
- **Response**: JSON with territory data

#### updateTerritory($id)
- **Purpose**: Update existing territory
- **Route**: POST `/settings/territories/update/{id}`
- **Authentication**: Required
- **CSRF**: Protected

#### deleteTerritory($id)
- **Purpose**: Delete territory
- **Route**: POST `/settings/territories/delete/{id}`
- **Authentication**: Required
- **CSRF**: Protected

### View: territories.php
Location: `app/Views/settings/territories.php`

**Components:**
1. **Filter Controls**
   - Status dropdown (Active/Inactive/All)
   - Search input with real-time filtering

2. **Territory Table**
   - Columns: Name, Description, Created By, Created Time, Actions
   - Sortable headers
   - Action buttons (Edit/Delete)

3. **Modals**
   - New Territory Modal
   - Edit Territory Modal
   - Both include full form fields

## Usage Guide

### Adding a New Territory

1. Navigate to **Settings → Workforce → Territories**
2. Click the **"New Territory"** button
3. Fill in the form:
   - **Territory Name** (required): e.g., "Downtown District"
   - **Street**: e.g., "123 Main Street"
   - **City**: e.g., "Toronto"
   - **State**: e.g., "Ontario"
   - **Zip Code**: e.g., "M5V 3A8"
   - **Country**: e.g., "Canada"
   - **Description**: e.g., "Covers downtown core including financial district"
   - **Status**: Select Active or Inactive
4. Click **"Save Territory"**

### Editing a Territory

1. Find the territory in the list
2. Click the **pencil icon** in the Actions column
3. Update the information in the modal
4. Click **"Update Territory"**

### Deleting a Territory

1. Find the territory in the list
2. Click the **trash icon** in the Actions column
3. Confirm the deletion when prompted

### Filtering and Searching

**By Status:**
- Use the dropdown to show:
  - Active Territories only
  - Inactive Territories only
  - All Territories

**By Search:**
- Type in the search box to filter by:
  - Territory name
  - Description content
- Search is performed in real-time

## API Reference

### Request/Response Examples

#### Create Territory
```javascript
POST /settings/territories/add
Content-Type: multipart/form-data

name: Downtown District
street: 123 Main Street
city: Toronto
state: Ontario
zip_code: M5V 3A8
country: Canada
description: Covers downtown core
status: active
csrf_test_name: [csrf_token]
```

**Success Response:**
```json
{
    "success": true,
    "message": "Territory added successfully."
}
```

#### Get Territory
```javascript
GET /settings/territories/get/1
```

**Response:**
```json
{
    "success": true,
    "territory": {
        "id": 1,
        "name": "Downtown District",
        "street": "123 Main Street",
        "city": "Toronto",
        "state": "Ontario",
        "zip_code": "M5V 3A8",
        "country": "Canada",
        "description": "Covers downtown core",
        "status": "active",
        "created_by": 1,
        "created_at": "2025-01-11 10:00:00",
        "updated_at": "2025-01-11 10:00:00"
    }
}
```

## Integration Points

### With User Management
- Territories can be assigned to users (field agents)
- Creator tracking links to user profiles
- Future: Role-based territory access

### With Work Orders
- Work orders can be filtered by territory
- Auto-assignment based on territory
- Territory-based reporting

### With Scheduling
- Technician availability by territory
- Travel time calculations
- Territory-based capacity planning

## Best Practices

### Territory Design
1. **Size Appropriately**: Balance between coverage and manageable workload
2. **Clear Boundaries**: Use natural boundaries (rivers, highways) or ZIP codes
3. **Consider Demographics**: Account for customer density and service frequency
4. **Plan for Growth**: Design territories that can be split as business grows

### Naming Conventions
- Use descriptive names: "North Toronto - Residential"
- Include major landmarks: "Airport District"
- Be consistent: If using numbers, use them throughout

### Status Management
- **Active**: Currently serviced territories
- **Inactive**: Temporarily not serviced or archived
- Regularly review inactive territories

## Troubleshooting

### Common Issues

#### "Invalid request method" Error
- **Cause**: CodeIgniter method detection issue
- **Solution**: Already fixed with flexible POST detection

#### Territory Not Saving
- **Check**: Required field (name) is filled
- **Verify**: User is authenticated
- **Review**: Browser console for errors

#### Search Not Working
- **Ensure**: JavaScript is enabled
- **Check**: No console errors
- **Verify**: Search triggers on keyup event

## Future Enhancements

### Planned Features
1. **Map Integration**
   - Visual territory boundaries on map
   - Draw territories using polygon tools
   - Geocoding for addresses

2. **Territory Assignment**
   - Assign multiple users to territory
   - Primary/backup technician designation
   - Territory coverage schedules

3. **Analytics**
   - Service metrics by territory
   - Territory performance dashboards
   - Workload balancing reports

4. **Advanced Features**
   - Territory overlap detection
   - Automatic territory optimization
   - Customer density heat maps

### Integration Opportunities
- Google Maps API for visualization
- Route optimization services
- Customer location plotting
- Real-time technician tracking

## Security Considerations

### Access Control
- All operations require authentication
- Future: Role-based permissions
- Audit trail for all changes

### Data Validation
- Server-side validation for all inputs
- SQL injection prevention via Query Builder
- XSS protection through output escaping

### Best Practices
- Regular access reviews
- Monitor territory changes
- Implement approval workflows for critical territories

---

*Last Updated*: January 2025  
*Version*: 1.0  
*Module*: Territory Management
