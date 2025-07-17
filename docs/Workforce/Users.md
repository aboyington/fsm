# User Management System

## Overview

The User Management system provides comprehensive user administration capabilities for the FSM platform. This module allows administrators to create, manage, and maintain user accounts with full CRUD operations, role-based permissions, and real-time database integration.

## Features

### ✅ Implemented Features

#### Core Functionality
- **Create Users**: Add new user accounts with complete profile information
- **Edit Users**: Update existing user information with real-time database persistence
- **Delete Users**: Soft delete users (sets status to inactive)
- **View Users**: Display all users with filtering and search capabilities
- **Real-time Updates**: All changes are immediately saved to the database

#### User Interface
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Modal Forms**: Clean, professional modal dialogs for user operations
- **Form Validation**: Client-side and server-side validation
- **Loading States**: Visual feedback during form submissions
- **Error Handling**: Comprehensive error messages and recovery

#### Data Management
- **Database Integration**: Full SQLite database connectivity
- **Data Persistence**: All changes are saved to the database immediately
- **Data Validation**: Strong validation rules for data integrity
- **Security**: CSRF protection, input sanitization, and secure password handling

## User Interface Components

### Main User List
- **Location**: `/workforce/users`
- **Features**:
  - Displays all users in a clean, organized table
  - Shows user avatars (initials-based)
  - Displays key information: Name, Employee ID, Email, Role, Created Date
  - Action buttons for View, Edit, and Delete operations

### Filter Panel
- **First Name Filter**: Search by first name
- **Last Name Filter**: Search by last name
- **Email Filter**: Search by email address
- **Employee ID Filter**: Search by employee ID
- **Profile Filter**: Filter by user role/profile
- **Clear Filters**: Reset all filters at once

### User Status Dropdown
- **Active Users**: Default view showing only active users
- **Inactive Users**: Shows only inactive/deleted users
- **All Users**: Shows all users regardless of status

### Add User Modal
- **Basic Information**:
  - First Name (required)
  - Last Name (required)
  - Employee ID (optional)
  - Email (required, unique)
  - Role/Profile (required)
  - Language preference
- **Form Features**:
  - Client-side validation
  - Real-time feedback
  - Loading states during submission
  - Success/error notifications

### Edit User Modal
- **User Information Section**:
  - First Name, Last Name
  - Email, Employee ID
  - Phone, Mobile
  - Role, Language
- **Address Information Section**:
  - Street Address
  - City, State
  - Country, Zip Code
- **Form Features**:
  - Pre-populated with existing data
  - Real-time database updates
  - Comprehensive validation
  - Proper modal cleanup

### Delete Confirmation Modal
- **Safety Features**:
  - Confirmation dialog
  - User name display
  - Warning about permanence
  - Soft delete (status change to inactive)

## Technical Implementation

### Backend Architecture

#### Controller: `WorkforceController`
Located at: `/app/Controllers/WorkforceController.php`

**Key Methods**:
- `users()`: Display user list page
- `createUser()`: Handle user creation
- `getUser($id)`: Fetch user data for editing
- `updateUser($id)`: Update user information
- `deleteUser($id)`: Soft delete user
- `searchUsers()`: Search and filter users

#### Model: `UserModel`
Located at: `/app/Models/UserModel.php`

**Features**:
- Data validation and sanitization
- Password hashing (bcrypt)
- Unique email/username enforcement
- Session management
- Authentication methods

#### Database Schema
Table: `users`

```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR NOT NULL UNIQUE,
    username VARCHAR,
    password VARCHAR,
    first_name VARCHAR NOT NULL,
    last_name VARCHAR NOT NULL,
    phone VARCHAR,
    mobile VARCHAR,
    language VARCHAR DEFAULT 'en-US',
    role VARCHAR NOT NULL DEFAULT 'field_agent',
    status VARCHAR NOT NULL DEFAULT 'active',
    employee_id VARCHAR,
    street VARCHAR,
    city VARCHAR,
    state VARCHAR,
    country VARCHAR,
    zip_code VARCHAR,
    created_by VARCHAR,
    session_token VARCHAR,
    last_login DATETIME,
    created_at DATETIME,
    updated_at DATETIME
);
```

### Frontend Architecture

#### JavaScript Implementation
- **Framework**: Vanilla JavaScript (no jQuery dependency)
- **AJAX**: Fetch API for all server communications
- **Modals**: Bootstrap 5 modal system
- **Validation**: HTML5 validation with custom JavaScript

#### Key JavaScript Features
- **Modal Management**: Proper modal lifecycle management
- **Form Handling**: Comprehensive form submission handling
- **Error Handling**: Robust error handling and user feedback
- **Loading States**: Visual feedback during operations
- **Cleanup Functions**: Proper modal backdrop cleanup

#### CSS Framework
- **Bootstrap 5**: Modern, responsive design
- **Custom Styles**: Minimal custom CSS for specific features
- **Icons**: Bootstrap Icons for consistent iconography
- **Responsive**: Mobile-first design approach

### API Endpoints

#### User Management Endpoints
- `GET /workforce/users` - Display user list page
- `POST /workforce/users/create` - Create new user
- `GET /workforce/users/get/{id}` - Get user data
- `POST /workforce/users/update/{id}` - Update user
- `POST /workforce/users/delete/{id}` - Delete user
- `GET /workforce/users/search` - Search users

#### Request/Response Format
All endpoints use JSON for API communication:

**Success Response**:
```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": { ... }
}
```

**Error Response**:
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

## User Roles and Permissions

### Available Roles
1. **Admin** (`admin`)
   - Full system access
   - Can manage all users
   - Can access all features

2. **Dispatcher** (`dispatcher`)
   - Can view and manage technicians
   - Can assign work orders
   - Can manage schedules

3. **Field Agent** (`field_agent`)
   - Can view their own profile
   - Can update work order status
   - Can log time and expenses

4. **Call Center Agent** (`call_center_agent`)
   - Can create and manage customer requests
   - Can view customer information
   - Can create work orders

5. **Manager** (`manager`)
   - Can view team performance
   - Can approve time off requests
   - Can manage territory assignments

6. **Technician** (`technician`)
   - Can view assigned work orders
   - Can update job status
   - Can log time and materials

7. **Limited Field Agent** (`limited_field_agent`)
   - Restricted access to system features
   - Can only view assigned work orders
   - Cannot access sensitive data

## Security Features

### Data Protection
- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation for all inputs
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Output escaping for all user data
- **Password Security**: Bcrypt hashing with salt

### Access Control
- **Role-Based Access**: Different permissions for different roles
- **Session Management**: Secure session handling
- **Authentication**: Username/password authentication
- **Status Checking**: Inactive users cannot access the system

### Validation Rules
- **Email**: Must be valid email format and unique
- **Password**: Minimum 6 characters (when provided)
- **Names**: Required, maximum 100 characters
- **Role**: Must be from predefined list
- **Phone Numbers**: Optional, format validation
- **Employee ID**: Optional, maximum 50 characters

## Usage Guide

### Adding a New User
1. Navigate to `/workforce/users`
2. Click the "New User" button
3. Fill in the required information:
   - First Name and Last Name
   - Email address
   - Select Role/Profile
   - Choose Language preference
4. Click "Save" to create the user
5. Success message will appear and page will refresh

### Editing an Existing User
1. Navigate to `/workforce/users`
2. Click the edit (pencil) icon next to the user
3. The edit modal will open with pre-populated data
4. Update any fields as needed:
   - User Information section
   - Address Information section
5. Click "Save Changes"
6. Success message will appear and changes will be saved

### Deleting a User
1. Navigate to `/workforce/users`
2. Click the delete (trash) icon next to the user
3. Confirm deletion in the dialog
4. User will be soft-deleted (status set to inactive)
5. User will no longer appear in the active users list

### Filtering Users
- Use the filter panel on the left to search by:
  - First Name
  - Last Name
  - Email
  - Employee ID
  - Profile/Role
- Use the status dropdown to filter by:
  - Active Users (default)
  - Inactive Users
  - All Users

## Troubleshooting

### Common Issues

#### User Creation Fails
- **Check Email**: Ensure email is unique and valid format
- **Check Required Fields**: First Name, Last Name, Email, and Role are required
- **Check Network**: Ensure server is accessible
- **Check Console**: Look for JavaScript errors in browser console

#### Edit Modal Not Loading Data
- **Check User ID**: Ensure user exists in database
- **Check Network**: Verify AJAX call is successful
- **Check Permissions**: Ensure user has permission to edit
- **Check Console**: Look for JavaScript errors

#### Modal Backdrop Stays Visible
- **Issue**: Modal backdrop remains after closing modal
- **Solution**: This has been fixed with comprehensive cleanup functions
- **Prevention**: All modal close events now properly clean up backdrop

#### Form Submission Errors
- **Check CSRF Token**: Ensure CSRF token is included in form
- **Check Validation**: Verify all fields meet validation requirements
- **Check Server Logs**: Look for server-side errors
- **Check Database**: Ensure database is accessible and writable

### Debug Tips
1. **Enable Debug Mode**: Set `CI_ENVIRONMENT = development` in `.env`
2. **Check Server Logs**: Look in `/writable/logs/` for error logs
3. **Use Browser Console**: Check for JavaScript errors
4. **Inspect Network Tab**: Verify AJAX requests are successful
5. **Check Database**: Verify data is being saved correctly

## Performance Optimization

### Database Optimization
- **Indexes**: Proper indexes on frequently queried fields
- **Query Optimization**: Efficient queries with minimal overhead
- **Connection Pooling**: Efficient database connection management

### Frontend Optimization
- **Minimal JavaScript**: No unnecessary libraries or frameworks
- **Efficient DOM Updates**: Minimal DOM manipulation
- **Lazy Loading**: Only load data when needed
- **Caching**: Proper browser caching for static assets

### Server Optimization
- **Efficient Controllers**: Minimal processing in controllers
- **Proper Validation**: Server-side validation for security
- **Error Handling**: Comprehensive error handling
- **Response Optimization**: Minimal response payloads

## Future Enhancements

### Planned Features
1. **Bulk Operations**: Bulk user creation and updates
2. **Advanced Filtering**: More sophisticated search and filter options
3. **User Import/Export**: CSV import/export functionality
4. **Profile Pictures**: User avatar upload and management
5. **Advanced Permissions**: Granular permission system
6. **Audit Trail**: Complete audit log for all user actions
7. **Password Policies**: Configurable password requirements
8. **Two-Factor Authentication**: Enhanced security features

### Technical Improvements
1. **Pagination**: Support for large user datasets
2. **Real-time Updates**: WebSocket-based real-time updates
3. **Caching**: Redis-based caching for improved performance
4. **API Rate Limiting**: Prevent abuse of API endpoints
5. **Advanced Validation**: More sophisticated validation rules
6. **Mobile App**: Dedicated mobile application

## Testing

### Manual Testing Checklist
- [ ] Create new user successfully
- [ ] Edit existing user successfully
- [ ] Delete user successfully
- [ ] Filter users by various criteria
- [ ] Validate form inputs
- [ ] Test modal operations
- [ ] Verify data persistence
- [ ] Test error handling
- [ ] Verify security features

### Automated Testing
- Unit tests for model methods
- Integration tests for API endpoints
- Frontend tests for JavaScript functions
- End-to-end tests for user workflows

## Conclusion

The User Management system provides a robust, secure, and user-friendly interface for managing users in the FSM platform. With full database integration, comprehensive validation, and modern UI components, it serves as a solid foundation for the broader Workforce Management module.

The system is designed with scalability, security, and user experience in mind, making it suitable for both small teams and large enterprise deployments.
