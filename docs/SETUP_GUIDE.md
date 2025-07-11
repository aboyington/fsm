# FSM Platform Setup Guide

## Overview
This document provides information about the Field Service Management (FSM) platform setup that has been completed, including development environment configuration, database schema, authentication system, and initial API implementation.

## Development Environment Setup

### Local Development
- **Server**: MAMP
- **Project Location**: `/Users/anthony/Sites/fsm`
- **Local URL**: `http://localhost/fsm/public/`
- **PHP Version**: 8.x
- **Framework**: CodeIgniter 4.6.1

### Configuration Files
- **Environment**: `.env` (configured for development)
- **Database**: SQLite at `/Users/anthony/Sites/fsm/writable/database/fsm.db`
  - **Mode**: WAL (Write-Ahead Logging) enabled for better performance
  - **Optimization**: Concurrent reads/writes supported
- **Base URL**: `http://localhost/fsm/public/`

## Database Schema

### Tables Created
1. **users** - System users with comprehensive profiles
   - Full address management
   - Multi-language support
   - Employee ID tracking
   - Session management
2. **customers** - Customer information with Canvass Global integration
3. **work_orders** - Work order management
4. **organizations** - Company settings and preferences
5. **fiscal_years** - Fiscal year configuration
6. **business_hours** - Operating hours management
7. **currencies** - Multi-currency support
8. **audit_logs** - User activity tracking

### Migrations
- `2025-07-11-000001_CreateUsersTable.php`
- `2025-07-11-000002_CreateCustomersTable.php`
- `2025-07-11-000003_CreateWorkOrdersTable.php`

Run migrations with: `php spark migrate`

## Authentication System

### Implementation
- **Type**: Session-based authentication (aligned with Canvass Global)
- **Token Storage**: 64-character hex string in `users.session_token`
- **Auth Filter**: `/app/Filters/AuthFilter.php`
- **Headers Supported**: 
  - `Authorization: Bearer <token>`
  - `X-API-Token: <token>` (fallback)

### User Roles
- **admin**: Full system access
- **call_center_agent**: Customer service operations
- **dispatcher**: Work order management and scheduling
- **field_agent**: Field operations and work order completion
- **limited_field_agent**: Restricted field operations

### Initial Users (Seeded)
| Role | Username | Password | Email |
|------|----------|----------|--------|
| Admin | admin | admin123 | admin@fsm.local |
| Dispatcher | dispatcher | dispatch123 | dispatcher@fsm.local |
| Field Tech | fieldtech | tech123 | tech@fsm.local |

## API Endpoints Implemented

### Authentication
- `POST /api/auth/login` - User login (returns session token)
- `POST /api/auth/logout` - User logout (requires auth)
- `GET /api/auth/me` - Get current user info (requires auth)
- `POST /api/auth/register` - Register new user (admin only)

### Customers
- `GET /api/customers` - List all customers (with pagination, search, status filter)
- `GET /api/customers/:id` - Get single customer
- `POST /api/customers` - Create new customer
- `PUT /api/customers/:id` - Update customer
- `DELETE /api/customers/:id` - Delete customer
- `GET /api/customers/nearby?lat=&lng=&radius=` - Get customers near location
- `POST /api/customers/sync` - Sync with Canvass Global (placeholder)

## File Structure

### Controllers
- `/app/Controllers/AuthController.php` - Web authentication pages
- `/app/Controllers/Api/AuthController.php` - API authentication
- `/app/Controllers/Api/CustomerController.php` - Customer API

### Models
- `/app/Models/UserModel.php` - User management with authentication
- `/app/Models/CustomerModel.php` - Customer data management

### Views
- `/app/Views/layouts/main.php` - Main layout template
- `/app/Views/auth/login.php` - Login page

### Assets
- `/public/assets/js/api-client.js` - API client library
- `/public/assets/js/app.js` - Main application JavaScript
- `/public/assets/css/style.css` - Custom styles

## UI Framework

### Architecture
- **Design Pattern**: Multi-Page Application (MPA)
- **Page Navigation**: Traditional server-side routing with full page loads
- **View Rendering**: Server-side PHP templates using CodeIgniter's view system
- **API Integration**: RESTful API calls for dynamic data updates within pages

### Technologies
- **CSS Framework**: Bootstrap 5.3.0
- **Icons**: Bootstrap Icons
- **JavaScript**: Vanilla JS with modern ES6+ features

### Features Implemented
- Responsive navigation with role-based menu
- Login page with API integration
- Alert system for user feedback
- Loading spinner for async operations
- Form validation helpers
- Utility functions for date/currency formatting

### Multi-Page Structure
Each major feature has its own dedicated page:
- `/login` - Authentication page
- `/dashboard` - Main dashboard
- `/customers` - Customer management
- `/work-orders` - Work order management
- `/calendar` - Scheduling calendar
- `/profile` - User profile
- `/settings` - System settings

This approach provides:
- Better SEO capabilities
- Simpler state management
- Faster initial page loads
- Browser history support
- Easier deployment to shared hosting

## Testing the Platform

### 1. Access the Application
Navigate to: `http://localhost/fsm/public/`

### 2. Login
Use one of the seeded credentials:
- Admin: `admin / admin123`
- Dispatcher: `dispatcher / dispatch123`
- Field Tech: `fieldtech / tech123`

### 3. API Testing
You can test the API using curl or any API client:

```bash
# Login
curl -X POST http://localhost/fsm/public/api/auth/login \
  -d "username=admin&password=admin123"

# Get customers (replace TOKEN with the token from login)
curl -X GET http://localhost/fsm/public/api/customers \
  -H "Authorization: Bearer TOKEN"
```

## Next Steps

### Completed Features
1. ✓ Comprehensive Settings module
   - Organization profile management
   - Business hours configuration
   - Fiscal year settings
   - Multi-currency support
   - User management with roles
2. ✓ User Management System
   - Full CRUD operations
   - Advanced filtering and search
   - Activity timeline
   - Tabbed interface
   - Address management

### Immediate Tasks
1. Create dashboard view with statistics
2. Implement work order management
3. Build customer management UI
4. Add calendar view for scheduling

### Phase 2 Features
- Canvass Global API integration
- Mobile-responsive field tech interface
- Real-time location tracking
- Report generation

### Phase 3 Features
- Parts inventory management
- Invoice generation
- Advanced scheduling algorithms
- Mobile app development

## Deployment Preparation

### For Namecheap Shared Hosting
1. Update `.env` file:
   - Change `CI_ENVIRONMENT` to `production`
   - Update `app.baseURL` to your domain
   - Ensure database path is relative

2. Upload files via FTP/cPanel:
   - Set document root to `/public` folder
   - Ensure `/writable` folder has write permissions
   - Upload database file to `/writable/database/`

3. Update JavaScript API client:
   - The API client auto-detects environment
   - No manual changes needed for deployment

## Troubleshooting

### Common Issues
1. **404 errors**: Check `.htaccess` in public folder
2. **Database errors**: Verify SQLite is enabled in PHP
3. **Permission errors**: Ensure `/writable` folder is writable
4. **API auth failures**: Check token in localStorage/headers

### Debug Mode
Currently in development mode with full error reporting.
Change to production before deployment.

## Security Notes
- Passwords are hashed using bcrypt
- Session tokens are generated using secure random bytes
- CSRF protection available (currently disabled for API)
- Input validation on all forms and API endpoints

---

*Last Updated: 2025-07-11*
*Version: 1.1.0*

## Recent Updates (v1.1.0)
- Implemented comprehensive Settings module
- Added User Management with advanced features
- Enabled SQLite WAL mode for better performance
- Added multi-language support preparation
- Implemented audit logging for user activities
- Enhanced security with role-based access control

 #212129
Background
#0D6EFD