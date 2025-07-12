# FSM Profiles Management Documentation

## Overview
The Profiles Management module provides a structured approach to manage user roles and permissions within the FSM platform. It ensures that users have the right access to functionalities required for their roles.

## Implemented Features

### Profiles
Define and manage user profiles that dictate access rights and permissions for various modules.

**Features:**
- Profile creation and listing
- Default profile protection
- Role-based permission management
- Status management (Active/Inactive)

**Database Table:** `profiles`

**Access:** Settings → Security Control → Profiles

### Permissions Management
Allow administrators to configure permissions associated with each profile.

**Features:**
- Module-based permissions (Settings, Users, Territories, etc.)
- Action-based permissions (Read, Write, Delete)
- Profile-specific permission assignment

### User Interface Features

**List View**
- Profile display with description and status
- Add/Edit/Delete actions

**Profile Management Modal**
- Profile editing with permission configuration
- Visual status indicators

### Security Controls
- Default profiles cannot be deleted
- Only administrators can manage profiles

## Technical Implementation

### Database Schema

#### Profiles Table
```sql
CREATE TABLE `profiles` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` VARCHAR NOT NULL UNIQUE,
    `description` TEXT,
    `is_default` BOOLEAN DEFAULT 0,
    `status` TEXT CHECK(status IN ('active','inactive')) NOT NULL DEFAULT 'active',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL
);
```

### Models
- `ProfileModel` - Manages profile data with validation

### Controllers
- `Settings::profiles()` - Profile listing and management
- `Settings::addProfile()` - Create new profiles
- `Settings::updateProfile()` - Update profile information
- `Settings::deleteProfile()` - Delete profiles

### API Endpoints

#### Profile Management
- `GET /settings/profiles` - List profiles with filtering
- `POST /settings/profiles/add` - Create new profile
- `GET /settings/profiles/get/{id}` - Get profile details
- `POST /settings/profiles/update/{id}` - Update profile
- `POST /settings/profiles/delete/{id}` - Delete profile

## Security Features

1. **Access Control**
   - Profiles managed by administrators
   - Role-based permissions

2. **Data Protection**
   - CSRF protection on all forms
   - Input validation and sanitization

## Usage Examples

### Adding a New Profile
1. Navigate to Settings → Security Control → Profiles
2. Click "New Profile" button
3. Fill in profile details and assign permissions
4. Click "Add Profile"

### Editing a Profile
1. Navigate to Settings → Security Control → Profiles
2. Click the edit icon next to the profile
3. Update profile information and permissions
4. Click "Save Changes"

## Future Enhancements
1. Granular permission settings
2. Audit trails for profile changes
3. Profile import/export functionality

