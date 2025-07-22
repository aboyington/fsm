# Work Orders Module

## Overview
The Work Orders module manages the formal work authorizations and job management for field service operations.

## Features
- Create and manage work orders from accepted estimates or direct requests
- Track work order progress through various stages
- Assign technicians and resources to work orders
- Link to customer information, assets, and service history
- Generate work order documentation for technicians

## Statuses
- **Draft**: Initial state when work order is being prepared
- **Open**: Work order is ready for assignment and scheduling
- **In Progress**: Work is currently being performed
- **Completed**: Work has been finished successfully
- **Cancelled**: Work order has been cancelled
- **On Hold**: Work order is temporarily paused

## Database Schema

### Work Orders Table
- `id` - Primary key
- `work_order_number` - Unique work order identifier (auto-generated)
- `summary` - Brief description of work to be performed
- `description` - Detailed work description
- `priority` - Work priority level (low, medium, high, critical)
- `type` - Work order type (service, corrective, preventive, etc.)
- `status` - Current work order status (pending, in_progress, completed, cancelled)
- `due_date` - Target completion date
- `company_id` - Customer company reference
- `contact_id` - Primary contact for the work
- `asset_id` - Asset being serviced (nullable)
- `email` - Contact email
- `phone` - Contact phone
- `mobile` - Contact mobile
- `service_address` - Service location
- `billing_address` - Billing address
- `preferred_date_1` - First preferred service date
- `preferred_date_2` - Second preferred service date
- `preferred_time` - Preferred service time
- `preference_note` - Additional scheduling preferences
- `sub_total` - Subtotal amount before tax
- `tax_amount` - Tax amount
- `discount` - Discount amount
- `adjustment` - Additional adjustments
- `grand_total` - Final total amount
- `created_by` - User who created the work order
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `deleted_at` - Soft delete timestamp

### Work Order Items Table
- `id` - Primary key
- `work_order_id` - Reference to work order
- `item_type` - Type of item (service, part, skill)
- `service_id` - Reference to product/service SKU
- `item_name` - Name of the service/part
- `line_item_name` - **Unique line item identifier (SVC-1, SVC-2, PRT-1, PRT-2, etc.)**
- `description` - Item description
- `quantity` - Quantity ordered
- `rate` - Unit rate/price
- `amount` - Total amount (quantity × rate)
- `sort_order` - Display order
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### Priority Levels
- **Low**: Non-urgent work
- **Medium**: Standard priority
- **High**: Important work requiring attention
- **Urgent**: Critical work requiring immediate attention

## API Endpoints

### RESTful Routes
- `GET /work-orders` - List all work orders
- `GET /work-orders/create` - Show create form
- `POST /work-orders/store` - Create new work order
- `GET /work-orders/view/{id}` - View work order details
- `GET /work-orders/edit/{id}` - Get work order for editing
- `POST /work-orders/update/{id}` - Update work order
- `POST /work-orders/delete/{id}` - Soft delete work order

### AJAX Endpoints
- `GET /work-orders/edit/{id}` - Returns JSON data for editing
- `POST /work-orders/update-status/{id}` - Quick status updates
- `GET /work-orders/technician/{id}` - Get work orders for specific technician

## User Interface

### Main View
- Empty state with professional illustration when no work orders exist
- Comprehensive data table with sorting, filtering, and search
- Quick action buttons for common operations
- Status indicators with color coding

### Table Structure
The main Work Orders table displays the following columns:
- **Work Order #**: Unique work order identifier (clickable link to details)
- **Summary**: Descriptive title of the work to be performed
- **Company**: Associated customer company name
- **Contact**: Primary contact person with email
- **Status**: Current work order status with color-coded badges
- **Priority**: Work priority level with color-coded badges
- **Created**: Date when the work order was created (Month Day, Year format)
- **Created By**: Full name of the user who created the work order
- **Actions**: View, Edit, and additional action buttons

### Create/Edit Modal
- Two-column responsive layout
- Client and contact selection with dynamic loading
- Asset selection filtered by client
- Territory and technician assignment
- Priority and status management
- Date and time scheduling
- Rich text areas for descriptions and notes

### View Details
- Complete work order information display
- Related records (estimate, request, appointments)
- Action history and audit trail
- Quick actions for status changes

## Business Logic

### Work Order Creation
1. Can be created from accepted estimates
2. Can be created directly from requests
3. Can be created independently
4. Auto-assigns territory based on client location
5. Sets default priority based on request type

### Status Workflow
```
Draft → Open → In Progress → Completed
         ↓
      Cancelled
         ↓
      On Hold → Open
```

### Validation Rules
- **Required Fields**: client_id, description, priority, status
- **Date Validation**: scheduled_date must be in the future for new orders
- **User Validation**: assigned_to must be valid technician
- **Status Transitions**: Enforced business rules for status changes

## Integration Points

### Customer Management
- Links to client companies and contacts
- Asset information and service history
- Territory-based assignment

### Scheduling
- Creates service appointments automatically
- Integrates with technician calendars
- Supports recurring maintenance schedules

### Reporting
- Generates service reports upon completion
- Tracks time and materials used
- Updates customer service history

### Financial
- Cost tracking and billing integration
- Time logging for payroll
- Material usage tracking

## Line Item Management

### Unique Line Item Names
Work orders support services and parts with automatically generated unique line item names:

- **Service Line Items**: Automatically assigned names like `SVC-1`, `SVC-2`, `SVC-3`, etc.
- **Parts Line Items**: Automatically assigned names like `PRT-1`, `PRT-2`, `PRT-3`, etc.
- **Sequential Numbering**: Line items are numbered sequentially within each work order
- **Persistent Storage**: Line item names are stored in the database for consistency
- **Backward Compatibility**: Support for both `line_item_name` and legacy field names

### Line Item Features
- **Dynamic Generation**: Line item names are generated when saving work order items
- **Unique Identification**: Each service/part has a distinct identifier within the work order
- **Frontend Display**: Line item names are displayed in work order views and forms
- **API Support**: Line item names are included in all API responses

### Implementation Details
```php
// Example of generated line item names
Services:
- SVC-1: "HVAC System Inspection"
- SVC-2: "Filter Replacement"
- SVC-3: "System Tune-up"

Parts:
- PRT-1: "Air Filter - 16x20x1"
- PRT-2: "Thermostat Battery"
- PRT-3: "Refrigerant R410A"
```

## Technical Implementation

### Model Layer
- **WorkOrderModel**: Main model with validation and business logic
- **Line Item Generation**: Automatic generation of unique line item names
- **Soft Deletes**: Maintains data integrity
- **Audit Trails**: Tracks all changes
- **Relationships**: Links to all related entities

### Controller Layer
- **WorkOrdersController**: RESTful API endpoints
- **JSON Responses**: AJAX-friendly responses
- **Error Handling**: Comprehensive error management
- **Authorization**: Role-based access control

### View Layer
- **Responsive Design**: Mobile-friendly interface
- **AJAX Forms**: Real-time form submission
- **Dynamic Content**: Client-filtered dropdowns
- **Status Updates**: Real-time status indicators

## Security Features

### Access Control
- Territory-based restrictions
- Role-based permissions
- Technician assignment validation

### Data Protection
- Input sanitization and validation
- SQL injection prevention
- XSS protection

## Performance Optimization

### Database
- Strategic indexing on frequently queried fields
- Efficient JOIN operations
- Pagination for large datasets

### Frontend
- AJAX loading for improved user experience
- Caching of frequently accessed data
- Optimized table rendering

## Reporting and Analytics

### Available Reports
- Work order completion rates
- Technician performance metrics
- Customer service history
- Territory workload analysis

### Key Metrics
- Average completion time
- First-time fix rates
- Customer satisfaction scores
- Revenue per work order

## Mobile Support

### Field Technician Features
- Mobile-optimized interface
- Offline capability for basic operations
- Quick status updates
- Photo attachment support

## Future Enhancements

### Planned Features
- **Mobile Application**: Native iOS/Android app
- **Real-time Tracking**: GPS tracking for field technicians
- **Advanced Scheduling**: AI-powered scheduling optimization
- **Integration APIs**: Third-party system integrations
- **Workflow Automation**: Advanced automation rules

### Scalability Improvements
- **Caching Layer**: Redis for improved performance
- **Queue System**: Background processing for heavy operations
- **API Rate Limiting**: Enhanced security and performance
- **Load Balancing**: Multi-server deployment support

## Troubleshooting

### Common Issues
- **Performance**: Use database indexing and pagination
- **Permissions**: Verify user roles and territory assignments
- **Integration**: Check API endpoints and data relationships
- **Validation**: Review model validation rules

### Work Order Form Issues
For comprehensive troubleshooting of Work Order form problems (modal submissions, validation errors, data persistence issues), see the detailed guide:

**📖 [Work Order Form Issues and Fixes](./WORK_ORDER_FORM_ISSUES.md)**

This guide covers all recent fixes applied in January 2025, including:
- Modal form submission failures
- HTTP method detection problems
- Form validation and data persistence
- Date and time handling issues
- Customer and user assignment problems
- Modal state management

### Debug Tools
- **Error Logging**: Comprehensive error tracking
- **SQL Profiling**: Query optimization tools
- **Performance Monitoring**: Real-time performance metrics

## Best Practices

### Work Order Management
- Always link to customer and asset information
- Use appropriate priority levels
- Maintain detailed descriptions and notes
- Update status promptly as work progresses

### System Administration
- Regular database maintenance
- Monitor system performance
- Keep user permissions up to date
- Regular backup procedures

For more information on related modules, see:
- [Estimates Module](./Estimates.md)
- [Service Appointments Module](./Service%20Appointments.md)
- [Service Reports Module](./Service%20Reports.md)
