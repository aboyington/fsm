# Scheduled Maintenances Module

## Overview
The Scheduled Maintenances module automates recurring service tasks by scheduling work orders and appointments to proactively address potential issues before they escalate into major problems or downtime.

## Features
- Create and manage recurring maintenance schedules
- Flexible scheduling options (daily, weekly, monthly, yearly, custom)
- Automatic work order and appointment generation
- Link to specific customers and assets
- Assign technicians and territories
- Track maintenance history and completion rates
- Pause and resume maintenance plans as needed

## Statuses
- **Draft**: Initial state when maintenance plan is being prepared
- **Active**: Maintenance plan is running and generating work orders
- **Inactive**: Maintenance plan is disabled
- **Paused**: Maintenance plan is temporarily suspended

## Database Schema

### Main Fields
- `id` - Primary key
- `name` - Maintenance plan name
- `description` - Detailed description of maintenance activities
- `schedule_type` - Type of schedule (daily, weekly, monthly, yearly, custom)
- `start_date` - When maintenance schedule begins
- `end_date` - When maintenance schedule ends (nullable)
- `frequency` - How often to repeat (e.g., every 2 weeks)
- `schedule_details` - JSON field for additional scheduling details
- `client_id` - Customer company (nullable)
- `asset_id` - Asset being maintained (nullable)
- `assigned_to` - Default technician for generated work orders (nullable)
- `territory_id` - Service territory (nullable)
- `priority` - Priority level for generated work orders
- `estimated_duration` - Expected duration in minutes
- `status` - Current maintenance plan status
- `notes` - Additional notes and instructions
- `next_due_date` - Next scheduled maintenance date
- `last_generated_date` - Last time a work order was generated
- `created_by` - User who created the maintenance plan
- `updated_by` - User who last updated the maintenance plan
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `deleted_at` - Soft delete timestamp

### Schedule Types
- **Daily**: Runs every day or every X days
- **Weekly**: Runs on specific days of the week
- **Monthly**: Runs on specific dates of the month
- **Yearly**: Runs on specific dates of the year
- **Custom**: Complex scheduling rules

### Priority Levels
- **Low**: Non-urgent maintenance
- **Medium**: Standard priority
- **High**: Important maintenance requiring attention
- **Urgent**: Critical maintenance requiring immediate attention

## API Endpoints

### RESTful Routes
- `GET /scheduled-maintenances` - List all maintenance plans
- `GET /scheduled-maintenances/create` - Show create form
- `POST /scheduled-maintenances/store` - Create new maintenance plan
- `GET /scheduled-maintenances/view/{id}` - View maintenance plan details
- `GET /scheduled-maintenances/edit/{id}` - Get maintenance plan for editing
- `POST /scheduled-maintenances/update/{id}` - Update maintenance plan
- `POST /scheduled-maintenances/delete/{id}` - Soft delete maintenance plan

### AJAX Endpoints
- `POST /scheduled-maintenances/pause/{id}` - Pause maintenance plan
- `POST /scheduled-maintenances/resume/{id}` - Resume maintenance plan
- `GET /scheduled-maintenances/due` - Get maintenance plans due for execution
- `POST /scheduled-maintenances/generate/{id}` - Manually generate work order

## User Interface

### Main View
- Empty state with professional calendar illustration when no maintenance plans exist
- Comprehensive data table with sorting, filtering, and search
- Status indicators with color coding
- Quick action buttons for common operations

### Create/Edit Modal
- Two-column responsive layout
- Schedule type selection with dynamic frequency units
- Client and asset selection with filtering
- Territory and technician assignment
- Priority and status management
- Date range selection for maintenance period
- Rich text areas for descriptions and notes

### View Details
- Complete maintenance plan information
- Generated work order history
- Next scheduled dates and execution log
- Performance metrics and completion rates

## Business Logic

### Maintenance Plan Creation
1. Define maintenance activities and schedule
2. Set up recurring pattern (daily, weekly, monthly, yearly, custom)
3. Link to specific customers and assets
4. Assign default technicians and territories
5. Set priority levels and estimated durations

### Schedule Processing
- System checks for due maintenance plans regularly
- Automatically generates work orders when maintenance is due
- Creates service appointments based on technician availability
- Updates next due date based on schedule pattern
- Tracks completion rates and maintenance history

### Status Workflow
```
Draft → Active → Paused → Active
         ↓         ↓
      Inactive   Inactive
```

### Validation Rules
- **Required Fields**: name, schedule_type, start_date, status
- **Date Validation**: start_date must be valid, end_date must be after start_date
- **Frequency Validation**: frequency must be greater than 0
- **Schedule Logic**: Validates schedule_details based on schedule_type

## Integration Points

### Work Order Management
- Automatically generates work orders when maintenance is due
- Inherits customer, asset, and technician information
- Sets appropriate priority and estimated duration

### Service Appointments
- Creates appointments for generated work orders
- Respects technician availability and business hours
- Integrates with calendar systems

### Customer Management
- Links to client companies and assets
- Tracks maintenance history for customers
- Supports asset-specific maintenance schedules

### Reporting
- Generates maintenance completion reports
- Tracks preventive maintenance effectiveness
- Provides insights into asset reliability

## Technical Implementation

### Model Layer
- **ScheduledMaintenanceModel**: Main model with validation and business logic
- **Soft Deletes**: Maintains data integrity
- **Audit Trails**: Tracks all changes
- **Relationships**: Links to all related entities

### Controller Layer
- **ScheduledMaintenancesController**: RESTful API endpoints
- **JSON Responses**: AJAX-friendly responses
- **Error Handling**: Comprehensive error management
- **Authorization**: Role-based access control

### View Layer
- **Responsive Design**: Mobile-friendly interface
- **AJAX Forms**: Real-time form submission
- **Dynamic Content**: Schedule type-specific form fields
- **Status Updates**: Real-time status indicators

### Automation Layer
- **Cron Jobs**: Scheduled task processing
- **Background Jobs**: Work order generation
- **Notification System**: Alerts for due maintenance

## Security Features

### Access Control
- Territory-based restrictions
- Role-based permissions
- Maintenance plan ownership validation

### Data Protection
- Input sanitization and validation
- SQL injection prevention
- XSS protection

## Performance Optimization

### Database
- Strategic indexing on schedule and date fields
- Efficient recurring schedule queries
- Pagination for large datasets

### Frontend
- AJAX loading for improved user experience
- Caching of frequently accessed data
- Optimized form rendering

### Background Processing
- Queue system for work order generation
- Batch processing for multiple maintenance plans
- Efficient scheduling algorithms

## Reporting and Analytics

### Available Reports
- Maintenance completion rates
- Asset reliability metrics
- Technician workload analysis
- Cost savings from preventive maintenance

### Key Metrics
- Maintenance plan adherence
- Average completion time
- Work order generation efficiency
- Customer satisfaction with maintenance

## Mobile Support

### Field Technician Features
- Mobile access to maintenance schedules
- Quick status updates
- Photo documentation for completed maintenance

### Manager Features
- Mobile monitoring of maintenance plans
- Real-time approval workflows
- Performance dashboards

## Automation Features

### Schedule Processing
- Automatic work order generation
- Intelligent scheduling based on availability
- Conflict resolution and rescheduling
- Notification system for upcoming maintenance

### Maintenance Templates
- Reusable maintenance plan templates
- Industry-specific maintenance schedules
- Customizable maintenance checklists
- Best practice recommendations

## Future Enhancements

### Planned Features
- **Predictive Maintenance**: AI-powered maintenance prediction
- **IoT Integration**: Sensor-based maintenance triggers
- **Advanced Analytics**: Machine learning for optimization
- **Mobile App**: Native mobile application
- **3rd Party Integration**: Integration with equipment manufacturers

### Scalability Improvements
- **Caching Layer**: Redis for improved performance
- **Queue System**: Background processing for heavy operations
- **API Rate Limiting**: Enhanced security and performance
- **Load Balancing**: Multi-server deployment support

## Troubleshooting

### Common Issues
- **Schedule Generation**: Check date validation and business rules
- **Work Order Creation**: Verify technician availability and permissions
- **Performance**: Use database indexing and caching
- **Automation**: Monitor cron job execution and queue processing

### Debug Tools
- **Error Logging**: Comprehensive error tracking
- **SQL Profiling**: Query optimization tools
- **Performance Monitoring**: Real-time performance metrics
- **Schedule Debugging**: Tools for testing schedule logic

## Best Practices

### Maintenance Planning
- Create clear, specific maintenance descriptions
- Set realistic frequency and duration estimates
- Link to specific assets when possible
- Use appropriate priority levels
- Regularly review and update maintenance plans

### System Administration
- Monitor automatic schedule processing
- Review generated work orders for accuracy
- Keep maintenance plans up to date
- Regular system performance monitoring
- Backup maintenance plan configurations

### Performance Optimization
- Use efficient scheduling patterns
- Batch process multiple maintenance plans
- Optimize database queries
- Monitor system resource usage

For more information on related modules, see:
- [Work Orders Module](./Work%20Orders.md)
- [Service Appointments Module](./Service%20Appointments.md)
- [Service Reports Module](./Service%20Reports.md)
