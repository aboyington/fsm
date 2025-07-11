# FSM User Management Documentation

## Overview
The User Management module provides comprehensive user administration capabilities for the FSM platform, including user creation, role assignment, permissions management, and activity tracking.

## Implemented Features

### 1. User Administration
Complete user lifecycle management from creation to deactivation.

**Features:**
- User creation with comprehensive profiles
- User listing with filtering and search
- User editing with tabbed interface
- Status management (Active, Inactive, Suspended)
- Password management and security
- Multi-language support

**Database Table:** `users`

**Access:** Settings → Workforce → Users

### 2. User Profile Fields

#### Basic Information
- First Name (required)
- Last Name (required)
- Email (required, unique)
- Employee ID
- Username (auto-generated from email)
- Password (minimum 6 characters)

#### Contact Information
- Phone
- Mobile
- Language preference (English, French, Spanish)

#### Address Information
- Street
- City
- State/Province
- Country
- Zip/Postal Code

#### System Information
- Role/Profile (Administrator, Call Center Agent, Dispatcher, Field Agent, Limited Field Agent)
- Status (Active, Inactive, Suspended)
- Created By
- Created Date
- Last Login
- Session Token

### 3. User Roles and Profiles

**Available Roles:**
1. **Administrator** (`admin`)
   - Full system access
   - User management
   - System configuration

2. **Call Center Agent** (`call_center_agent`)
   - Customer service operations
   - Work order creation
   - Customer management

3. **Dispatcher** (`dispatcher`)
   - Work order assignment
   - Schedule management
   - Resource allocation

4. **Field Agent** (`field_agent`)
   - Field service execution
   - Work order completion
   - Time tracking

5. **Limited Field Agent** (`limited_field_agent`)
   - Restricted field operations
   - View-only permissions for certain areas

### 4. User Interface Features

#### List View
- Filterable by status (Active, Inactive, All)
- Real-time search functionality
- Sortable columns
- Quick edit access
- Visual status indicators

#### Edit User Modal
**Tabs:**
1. **Overview** - All user information and settings
2. **Timeline** - Activity history and audit trail
3. **Calendar** - Schedule and appointments
4. **Service Appointments** - Assigned work orders
5. **Additional Tabs** (via dropdown):
   - Time Sheets
   - Territories
   - Crew Assignments
   - Skills
   - Trips
   - Related Lists

### 5. Timeline/Audit Trail
Track all user activities and system events.

**Tracked Events:**
- User creation
- Profile updates
- Status changes
- Role changes
- Login/Logout events
- Password changes
- Service assignments
- Work order completions

**Features:**
- Chronological event display
- Date filtering (Today, Yesterday, Last Week, Last Month, Last Year, All Time)
- Event categorization with icons
- User attribution

## Technical Implementation

### Database Schema

#### Users Table
```sql
CREATE TABLE `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `email` VARCHAR NOT NULL UNIQUE,
    `username` VARCHAR NOT NULL UNIQUE,
    `password` VARCHAR NOT NULL,
    `first_name` VARCHAR NOT NULL,
    `last_name` VARCHAR NOT NULL,
    `phone` VARCHAR NULL,
    `mobile` VARCHAR NULL,
    `language` VARCHAR NULL DEFAULT 'en-US',
    `enable_rtl` TINYINT NULL DEFAULT 0,
    `created_by` VARCHAR NULL,
    `employee_id` VARCHAR NULL,
    `street` VARCHAR NULL,
    `city` VARCHAR NULL,
    `state` VARCHAR NULL,
    `country` VARCHAR NULL,
    `zip_code` VARCHAR NULL,
    `role` TEXT CHECK(role IN ('admin','call_center_agent','dispatcher','field_agent','limited_field_agent')) NOT NULL DEFAULT 'field_agent',
    `status` TEXT CHECK(status IN ('active','inactive','suspended')) NOT NULL DEFAULT 'active',
    `session_token` VARCHAR NULL,
    `last_login` DATETIME NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
);
```

### Models
- `UserModel` - Manages user data with validation and authentication
- `AuditLogModel` - Tracks user activities and timeline events

### Controllers
- `Settings::users()` - User listing and filtering
- `Settings::addUser()` - Create new users
- `Settings::getUser($id)` - Retrieve user details
- `Settings::updateUser()` - Update user information
- `Settings::getUserTimeline($userId)` - Get user activity history

### API Endpoints

#### User Management
- `GET /settings/users` - List users with filtering
- `POST /settings/addUser` - Create new user
- `GET /settings/getUser/{id}` - Get user details
- `POST /settings/updateUser` - Update user information
- `GET /settings/getUserTimeline/{userId}` - Get user timeline

### Security Features

1. **Authentication**
   - Session-based authentication
   - Secure token generation
   - Automatic session expiration

2. **Password Security**
   - Bcrypt hashing (PASSWORD_DEFAULT)
   - Minimum length enforcement
   - Password confirmation on creation

3. **Data Protection**
   - CSRF protection on all forms
   - Input validation and sanitization
   - SQL injection prevention through query builder
   - XSS protection through output escaping

4. **Access Control**
   - Role-based permissions
   - Status-based access (inactive users cannot login)
   - Session validation on all requests

### Validation Rules

#### User Creation/Update
- Email: Required, valid email format, unique
- Username: 3-100 characters, unique (auto-generated if not provided)
- Password: Minimum 6 characters (only on creation or if provided on update)
- First Name: Required, max 100 characters
- Last Name: Required, max 100 characters
- Role: Must be valid role from allowed list
- Status: Must be valid status from allowed list

## Usage Examples

### Adding a New User
1. Navigate to Settings → Workforce → Users
2. Click "New User" button
3. Fill in required information:
   - First Name and Last Name
   - Email address
   - Password (minimum 6 characters)
   - Role/Profile selection
   - Status (defaults to Active)
4. Optionally add:
   - Employee ID
   - Contact information
   - Address details
5. Click "Add User"

### Editing a User
1. Navigate to Settings → Workforce → Users
2. Click the edit icon next to the user
3. Update information across different tabs:
   - Overview: Basic info, contact, address
   - Timeline: View activity history
   - Calendar: Check schedule
   - Other tabs for additional features
4. Click "Save Changes"

### Filtering Users
1. Use the status dropdown to filter by:
   - Active Users (default)
   - Inactive Users
   - All Users
2. Use the search box for real-time filtering by:
   - Name
   - Email
   - Other visible fields

## Database Optimizations

### Indexes
- Email (unique index for fast lookups)
- Session token (for authentication)
- Status (for filtering)
- Created date (for sorting)

### Performance Features
- SQLite WAL mode enabled for better concurrency
- Efficient query building with proper indexes
- Pagination ready (for future implementation)

## Recent Updates

### Database Migrations
1. `2025-07-11-200036_AddMobileToUsers.php` - Added mobile field
2. `2025-07-11-200145_UpdateUsersTableSchema.php` - Added language, enable_rtl, created_by fields
3. `2025-07-11-200525_AddAddressFieldsToUsers.php` - Added complete address fields

### UI Enhancements
- Comprehensive tabbed interface in edit modal
- Timeline integration with filtering
- Calendar view with month/week/day modes
- Improved form layouts with sections
- Language preference with RTL support preparation

## Future Enhancements

1. **Advanced Permissions**
   - Granular permission system
   - Custom role creation
   - Permission templates

2. **User Import/Export**
   - Bulk user import from CSV
   - User data export
   - Active Directory integration

3. **Enhanced Security**
   - Two-factor authentication
   - Password policies
   - Login attempt monitoring
   - IP-based access control

4. **Team Management**
   - User groups/teams
   - Hierarchy management
   - Delegation features

5. **Productivity Features**
   - User availability calendar
   - Skill-based routing
   - Performance metrics
   - Training records

6. **Integration Features**
   - SSO integration
   - API access tokens
   - Webhook notifications
   - Third-party authentication

## Troubleshooting

### Common Issues

1. **User Cannot Login**
   - Check user status (must be "active")
   - Verify password is correct
   - Check session configuration
   - Verify database connection

2. **Email Already Exists Error**
   - Email addresses must be unique
   - Check for existing user with same email
   - Consider using different email or updating existing user

3. **Form Submission Errors**
   - Ensure CSRF token is present
   - Check all required fields are filled
   - Verify data meets validation rules
   - Check browser console for JavaScript errors

4. **Timeline Not Loading**
   - Verify audit_logs table exists
   - Check user has proper permissions
   - Ensure AJAX endpoint is accessible
   - Check for JavaScript errors

### Debug Mode
Enable debug logging in the Settings controller to troubleshoot issues:
- Request method detection
- POST data validation
- Database query logging
- Session state verification
