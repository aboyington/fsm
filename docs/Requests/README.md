# Requests Module Documentation

## Overview
The Requests module is a core component of the Field Service Management (FSM) platform that handles customer service requests from initial creation through completion or conversion to work orders and estimates.

## Key Features
- **Customer Request Management**: Create, track, and manage service requests from customers
- **Status Tracking**: Monitor request progress through customizable status workflows
- **Priority Management**: Assign and track request priorities (Low, Medium, High)
- **Company and Contact Integration**: Link requests to specific companies and contacts
- **Conversion Capabilities**: Convert requests to work orders or estimates
- **Advanced Filtering**: Search and filter requests by status, priority, company, and more
- **Comprehensive Details View**: View detailed request information with tabbed interface

## Module Structure

### User Interface Components
- **Index Page**: Main requests listing with filters and search
- **Detail View**: Comprehensive request details with tabbed sections
- **Create/Edit Modal**: Form for creating and editing requests
- **Empty State**: User-friendly view when no requests exist

### Technical Components
- **Controller**: `RequestsController.php` - Handles HTTP requests and business logic
- **Model**: `RequestModel.php` - Database interactions and data management
- **Views**: Blade/PHP templates for UI rendering
- **JavaScript**: `requests.js` - Client-side functionality

## Documentation Index

1. [User Interface Guide](USER_INTERFACE.md) - Complete UI documentation
2. [Technical Implementation](TECHNICAL_IMPLEMENTATION.md) - Backend architecture
3. [API Documentation](API_DOCUMENTATION.md) - Endpoint specifications
4. [Database Schema](DATABASE_SCHEMA.md) - Data structure and relationships
5. [Integration Guide](INTEGRATION_GUIDE.md) - Integration with other modules
6. [Workflow Management](WORKFLOW_MANAGEMENT.md) - Status and priority workflows
7. [Conversion Features](CONVERSION_FEATURES.md) - Converting to work orders/estimates

## Quick Start

### Accessing Requests
1. Navigate to **Work Order Management** → **Request** in the main navigation
2. View the requests index page with all current requests
3. Use filters to narrow down specific requests
4. Click on any request number to view detailed information

### Creating a New Request
1. Click the **Create Request** button on the requests index page
2. Fill in the required information:
   - Request Name (required)
   - Company selection (optional)
   - Contact selection (optional)
   - Priority level
   - Description
3. Save the request to add it to the system

### Request Management
- **View**: Click on request number to see full details
- **Edit**: Use the edit button or actions dropdown
- **Convert**: Convert to work order or estimate from detail view
- **Status Updates**: Change status through the detail view interface

## Status Workflow

Requests follow a standard workflow:

1. **Pending**: Initial status when created
2. **In Progress**: Request is being actively worked on
3. **On Hold**: Temporarily paused (awaiting customer response, parts, etc.)
4. **Completed**: Request has been resolved

Additional actions:
- **Cancel**: Mark request as cancelled
- **Terminate**: Permanently close request

## Integration Points

- **Customers Module**: Links to companies and contacts
- **Work Orders**: Convert requests to work orders
- **Estimates**: Convert requests to estimates
- **User Management**: Track who created and modified requests
- **Territory Management**: Associate requests with territories

## Reporting and Analytics

- Request counts by status
- Request distribution by priority
- Request aging reports
- Conversion rate tracking (requests to work orders/estimates)
- User activity tracking

---

*Last Updated*: January 2025  
*Version*: 1.0  
*Module*: Requests - Field Service Management Platform