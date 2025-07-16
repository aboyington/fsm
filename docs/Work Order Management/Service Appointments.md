# Service Appointments Module

## Overview
The Service Appointments module manages scheduling and calendar integration for field service operations.

## Features
- Schedule service appointments from work orders
- Calendar integration with technician availability
- Time slot management and conflict resolution
- Customer notification system
- Appointment status tracking
- Recurring appointment support

## Statuses
- **Scheduled**: Appointment is booked and confirmed
- **Confirmed**: Customer has confirmed the appointment
- **In Progress**: Technician is currently at the appointment
- **Completed**: Appointment has been finished
- **Cancelled**: Appointment has been cancelled
- **No Show**: Customer was not available
- **Rescheduled**: Appointment has been moved to a different time

## Database Schema

### Main Fields
- `id` - Primary key
- `work_order_id` - Link to associated work order
- `client_id` - Customer company
- `contact_id` - Primary contact for the appointment
- `asset_id` - Asset being serviced (nullable)
- `assigned_to` - Technician assigned to the appointment
- `territory_id` - Service territory
- `appointment_date` - Scheduled date
- `start_time` - Appointment start time
- `end_time` - Appointment end time
- `duration` - Expected duration in minutes
- `status` - Current appointment status
- `priority` - Appointment priority level
- `description` - Appointment description
- `notes` - Additional notes and instructions
- `location` - Service location address
- `customer_instructions` - Special instructions from customer
- `technician_notes` - Notes from technician
- `created_by` - User who created the appointment
- `updated_by` - User who last updated the appointment
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp
- `deleted_at` - Soft delete timestamp

### Priority Levels
- **Low**: Non-urgent appointments
- **Medium**: Standard priority
- **High**: Important appointments
- **Urgent**: Critical appointments requiring immediate attention

## API Endpoints

### RESTful Routes
- `GET /service-appointments` - List all appointments
- `GET /service-appointments/create` - Show create form
- `POST /service-appointments/store` - Create new appointment
- `GET /service-appointments/view/{id}` - View appointment details
- `GET /service-appointments/edit/{id}` - Get appointment for editing
- `POST /service-appointments/update/{id}` - Update appointment
- `POST /service-appointments/delete/{id}` - Soft delete appointment

### AJAX Endpoints
- `GET /service-appointments/calendar` - Calendar view data
- `POST /service-appointments/reschedule/{id}` - Reschedule appointment
- `GET /service-appointments/technician/{id}` - Get appointments for specific technician
- `GET /service-appointments/available-slots` - Get available time slots

## User Interface

### Main View
- Empty state with professional calendar illustration
- Calendar view with appointment visualization
- List view with filtering and search capabilities
- Quick action buttons for common operations
- Status indicators with color coding

### Create/Edit Modal
- Two-column responsive layout
- Work order and client selection
- Date and time picker with availability checking
- Duration estimation and adjustment
- Priority and status management
- Location and instruction fields
- Technician assignment with availability

### Calendar Integration
- Full calendar view with drag-and-drop support
- Day, week, and month views
- Technician-specific calendar views
- Conflict detection and resolution
- Time zone support

## Business Logic

### Appointment Scheduling
1. Created from work orders automatically or manually
2. Checks technician availability before scheduling
3. Validates time slots and prevents conflicts
4. Sends notifications to customers and technicians
5. Supports recurring appointments for maintenance

### Status Workflow
```
Scheduled → Confirmed → In Progress → Completed
    ↓           ↓
Cancelled   No Show
    ↓
Rescheduled → Scheduled
```

### Validation Rules
- **Required Fields**: work_order_id, client_id, appointment_date, start_time, assigned_to
- **Date Validation**: appointment_date must be in the future
- **Time Validation**: start_time must be before end_time
- **Availability Check**: Technician must be available at scheduled time
- **Business Hours**: Appointments must be within business hours

## Integration Points

### Work Order Management
- Links to work orders for service context
- Updates work order status based on appointment progress
- Inherits customer and asset information

### Customer Management
- Links to client companies and contacts
- Retrieves customer location and preferences
- Tracks customer service history

### Technician Management
- Assigns technicians based on skills and availability
- Integrates with technician calendars
- Tracks technician performance metrics

### Notification System
- Email notifications for appointment confirmations
- SMS reminders for upcoming appointments
- Push notifications for mobile app users

## Technical Implementation

### Model Layer
- **ServiceAppointmentModel**: Main model with validation and business logic
- **Soft Deletes**: Maintains data integrity
- **Audit Trails**: Tracks all changes
- **Relationships**: Links to all related entities

### Controller Layer
- **ServiceAppointmentsController**: RESTful API endpoints
- **JSON Responses**: AJAX-friendly responses
- **Error Handling**: Comprehensive error management
- **Authorization**: Role-based access control

### View Layer
- **Responsive Design**: Mobile-friendly interface
- **AJAX Forms**: Real-time form submission
- **Calendar Integration**: Full-featured calendar widget
- **Real-time Updates**: Live appointment status updates

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
- Strategic indexing on date and technician fields
- Efficient calendar queries
- Pagination for large datasets

### Frontend
- AJAX loading for calendar views
- Caching of frequently accessed data
- Optimized calendar rendering

## Reporting and Analytics

### Available Reports
- Appointment completion rates
- Technician utilization metrics
- Customer satisfaction scores
- No-show analysis

### Key Metrics
- Average appointment duration
- First-time fix rates
- Customer punctuality
- Technician efficiency

## Mobile Support

### Field Technician Features
- Mobile-optimized appointment view
- GPS navigation to appointment locations
- Quick status updates
- Photo attachment for service documentation

### Customer Features
- Appointment confirmation and rescheduling
- Real-time technician tracking
- Service feedback submission

## Calendar Integration

### Supported Calendars
- Google Calendar synchronization
- Outlook calendar integration
- iCal export functionality
- Custom calendar API

### Features
- Two-way synchronization
- Conflict detection
- Automatic updates
- Time zone handling

## Notification System

### Email Notifications
- Appointment confirmations
- Reminder emails
- Cancellation notices
- Status updates

### SMS Notifications
- Appointment reminders
- Technician arrival notifications
- Emergency updates

### Push Notifications
- Mobile app notifications
- Real-time status updates
- Schedule changes

## Future Enhancements

### Planned Features
- **AI Scheduling**: Intelligent scheduling optimization
- **Route Optimization**: GPS-based route planning
- **Video Calls**: Virtual appointment support
- **IoT Integration**: Smart device monitoring
- **Predictive Analytics**: Appointment prediction models

### Scalability Improvements
- **Caching Layer**: Redis for improved performance
- **Queue System**: Background processing for notifications
- **API Rate Limiting**: Enhanced security and performance
- **Load Balancing**: Multi-server deployment support

## Troubleshooting

### Common Issues
- **Scheduling Conflicts**: Check technician availability and business hours
- **Notification Failures**: Verify email/SMS configuration
- **Calendar Sync**: Check API credentials and permissions
- **Performance**: Use database indexing and caching

### Debug Tools
- **Error Logging**: Comprehensive error tracking
- **SQL Profiling**: Query optimization tools
- **Performance Monitoring**: Real-time performance metrics

## Best Practices

### Appointment Management
- Always confirm appointments with customers
- Use appropriate time buffers between appointments
- Maintain detailed location and instruction information
- Update status promptly as appointments progress

### System Administration
- Regular calendar synchronization
- Monitor notification delivery rates
- Keep technician availability up to date
- Regular backup procedures

For more information on related modules, see:
- [Work Orders Module](./Work%20Orders.md)
- [Service Reports Module](./Service%20Reports.md)
- [Scheduled Maintenances Module](./Scheduled%20Maintenances.md)
