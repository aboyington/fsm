# User Profile Management

## Overview

The User Profile page provides a comprehensive view of individual user information, activities, and related data within the FSM platform. This centralized profile system allows administrators and managers to access detailed user information, track activities, and manage user-specific data across multiple modules.

## Features

### ✅ Implemented Features

#### Profile Information Panel
- **User Avatar**: Displays user initials in a circular avatar
- **Basic Information**: Name, email, employee ID, role, and status
- **Contact Information**: Phone numbers, mobile, and language preferences
- **Address Information**: Complete address details
- **Status Badge**: Visual indicator of user status (Active/Inactive/Suspended)
- **Edit Profile**: Quick access to edit user information

#### Tabbed Navigation System
- **Clean Design**: Removed blue color and bold formatting from inactive tabs
- **Primary Color Highlighting**: Active tabs use the application's primary color (#198754)
- **Responsive Layout**: Optimized for all device sizes
- **Intuitive Navigation**: Easy switching between different data views

#### Timeline Tab (Default)
- **Activity Tracking**: Chronological view of all user activities
- **Event Types**: Skill assignments, profile updates, resource creation
- **Time Filtering**: Dropdown to filter activities by time period
- **Visual Indicators**: Color-coded icons for different activity types
- **User Attribution**: Shows who performed each action

#### Calendar Tab
- **Calendar View**: Visual calendar interface for user scheduling
- **Period Selection**: Dropdown to select viewing period (week/month)
- **Event Management**: View and manage scheduled appointments
- **Integration Ready**: Prepared for calendar system integration

#### Service Appointments Tab
- **Appointment List**: Table view of all user's service appointments
- **Appointment Details**: Date, time, customer, service type, and status
- **Quick Actions**: Edit and manage appointments directly
- **New Appointment**: Button to create new appointments
- **Status Indicators**: Visual status badges for appointment states

#### Time Sheets Tab
- **Time Tracking**: Comprehensive time sheet management
- **Summary Cards**: 
  - Total Hours (current period)
  - Regular Hours breakdown
  - Overtime Hours tracking
- **Detailed View**: Table with start/end times, breaks, and totals
- **Period Selection**: Filter by week, month, or custom periods
- **Status Tracking**: Time sheet approval status

#### Territories Tab
- **Territory Assignments**: View user's assigned territories
- **Geographic Information**: Territory boundaries and coverage areas
- **Assignment History**: Track territory assignment changes
- **Management Tools**: Assign or remove territories

#### Crew Tab
- **Team Membership**: View user's crew assignments
- **Team Hierarchy**: Show reporting relationships
- **Crew Performance**: Team-based metrics and statistics
- **Assignment Management**: Add or remove from crews

#### Skills Tab
- **Skill Inventory**: Complete list of user's skills and certifications
- **Skill Levels**: Proficiency levels for each skill
- **Certification Management**: Track certifications and expiration dates
- **Skill Assignment**: Add or remove skills from user profile

#### Trips Tab
- **Trip History**: Record of all user trips and travel
- **Mileage Tracking**: Distance and travel time information
- **Expense Tracking**: Trip-related expenses and reimbursements
- **Trip Reports**: Generate trip summaries and reports

## User Interface Design

### Layout Structure

#### Left Panel (User Information)
- **Fixed Width**: 25% of screen width (responsive)
- **User Avatar**: Large circular avatar with initials
- **Basic Info Card**: Name, email, status, role, employee ID
- **Contact Info Card**: Phone, mobile, language, address
- **Edit Button**: Primary action button for profile editing

#### Right Panel (Tabbed Content)
- **Flexible Width**: 75% of screen width (responsive)
- **Tab Navigation**: Horizontal tabs with clean styling
- **Content Area**: Dynamic content based on selected tab
- **Minimum Height**: 400px for consistent layout

### Visual Design Elements

#### Color Scheme
- **Primary Color**: #198754 (FSM green)
- **Secondary Color**: #6c757d (muted gray)
- **Success Color**: #28a745 (green)
- **Warning Color**: #ffc107 (yellow)
- **Danger Color**: #dc3545 (red)
- **Info Color**: #17a2b8 (blue)

#### Typography
- **Headers**: Bold, clear hierarchy
- **Body Text**: Easy-to-read font sizes
- **Status Badges**: Distinct, readable badges
- **Navigation**: Clean, minimal styling

#### Spacing and Layout
- **Consistent Padding**: 15px standard spacing
- **Card Spacing**: 3px margin between cards
- **Tab Spacing**: Balanced spacing between tabs
- **Content Margins**: Proper content separation

## Technical Implementation

### Backend Architecture

#### Controller: `WorkforceController::userProfile()`
**Location**: `/app/Controllers/WorkforceController.php`

```php
public function userProfile($userId)
{
    $userModel = new UserModel();
    $user = $userModel->find($userId);
    
    if (!$user) {
        throw new PageNotFoundException('User not found');
    }
    
    // Get related data
    $skills = $this->getUserSkills($userId);
    $territories = $this->getUserTerritories($userId);
    $crews = $this->getUserCrews($userId);
    
    return view('workforce/user_profile', [
        'user' => $user,
        'skills' => $skills,
        'territories' => $territories,
        'crews' => $crews
    ]);
}
```

#### Route Configuration
**Location**: `/app/Config/Routes.php`

```php
$routes->get('users/profile/(:num)', 'WorkforceController::userProfile/$1');
```

#### View Template
**Location**: `/app/Views/workforce/user_profile.php`

**Structure**:
- Main layout with two-column design
- User information panel (left)
- Tabbed content area (right)
- Edit user modal
- Custom CSS for tab styling
- JavaScript for tab functionality

### Database Integration

#### Primary Table: `users`
**User Information**:
- Personal details (name, email, phone)
- Employment details (employee_id, role, status)
- Address information
- System details (created_at, updated_at)

#### Related Tables
- `user_skills`: User skill assignments
- `user_territories`: Territory assignments
- `user_crews`: Crew memberships
- `time_sheets`: Time tracking data
- `service_appointments`: Appointment assignments
- `trips`: Trip records

### Frontend Architecture

#### HTML Structure
```html
<div class="container-fluid">
    <div class="row">
        <!-- User Information Panel -->
        <div class="col-md-4 col-lg-3">
            <!-- User cards -->
        </div>
        
        <!-- Main Content -->
        <div class="col-md-8 col-lg-9">
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs">
                <!-- Tab buttons -->
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Tab panes -->
            </div>
        </div>
    </div>
</div>
```

#### CSS Styling
**Custom Tab Styling**:
```css
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: normal;
    border: none;
    border-bottom: 2px solid transparent;
    background: transparent;
}

.nav-tabs .nav-link.active {
    color: #198754;
    font-weight: 600;
    border-color: #198754;
    background: transparent;
}
```

#### JavaScript Functionality
- **Tab Management**: Bootstrap 5 tab system
- **Modal Management**: Edit profile modal
- **Form Handling**: Profile update forms
- **AJAX Requests**: Dynamic content loading

## Data Management

### User Profile Data

#### Core Information
- **Name**: First and last name
- **Email**: Primary contact email
- **Employee ID**: Unique identifier
- **Role**: User's system role
- **Status**: Active/Inactive/Suspended
- **Language**: Preferred language setting

#### Contact Information
- **Phone**: Primary phone number
- **Mobile**: Mobile phone number
- **Address**: Complete address details
  - Street address
  - City, State, Country
  - Postal/ZIP code

#### Activity Data
- **Timeline Events**: Chronological activity log
- **Skill Assignments**: Skills and certifications
- **Territory Assignments**: Geographic responsibilities
- **Crew Memberships**: Team assignments
- **Time Sheets**: Work hour tracking
- **Service Appointments**: Scheduled appointments
- **Trips**: Travel and mileage records

### Data Relationships

#### One-to-Many Relationships
- User → Skills
- User → Territories
- User → Crews
- User → Time Sheets
- User → Service Appointments
- User → Trips

#### Many-to-Many Relationships
- Users ↔ Skills (through user_skills)
- Users ↔ Territories (through user_territories)
- Users ↔ Crews (through user_crews)

## Security and Access Control

### Permission Levels

#### View Profile
- **Self**: Users can view their own profile
- **Manager**: Can view team members' profiles
- **Administrator**: Can view all profiles
- **Dispatcher**: Can view field agents' profiles

#### Edit Profile
- **Self**: Limited editing of own profile
- **Manager**: Can edit team members' profiles
- **Administrator**: Full editing capabilities
- **HR**: Can edit employee information

### Data Protection
- **Role-based Access**: Different views based on user role
- **Sensitive Data**: Restrict access to personal information
- **Audit Trail**: Log all profile changes
- **CSRF Protection**: Secure form submissions

## Usage Guide

### Accessing User Profiles

#### From User List
1. Navigate to `/workforce/users`
2. Click on any user's name or "View" button
3. Profile page opens with Timeline tab active

#### Direct URL Access
- **Format**: `/workforce/users/profile/{user_id}`
- **Example**: `/workforce/users/profile/1`

### Navigating the Profile

#### Tab Navigation
1. **Timeline**: View user activity history
2. **Calendar**: Check scheduled events
3. **Service Appointments**: Review appointments
4. **Time Sheets**: Check work hours
5. **Territories**: View assigned areas
6. **Crew**: Check team memberships
7. **Skills**: Review skills and certifications
8. **Trips**: View travel history

#### Information Panels
- **User Info**: Basic profile information
- **Contact Info**: Communication details
- **Status**: Current user status
- **Edit**: Quick profile editing

### Profile Management

#### Editing Profile Information
1. Click "Edit Profile" button
2. Modal opens with current information
3. Update fields as needed
4. Click "Save Changes"
5. Profile updates immediately

#### Viewing Activity Timeline
1. Timeline tab shows recent activities
2. Use time filter dropdown to adjust period
3. Different icons indicate activity types
4. Click items for more details

#### Managing Skills
1. Navigate to Skills tab
2. View current skill assignments
3. Add new skills with "Add Skill" button
4. Edit skill levels as needed
5. Remove skills if necessary

## Responsive Design

### Mobile Optimization

#### Layout Adjustments
- **Stacked Layout**: Panels stack vertically on mobile
- **Tab Scrolling**: Horizontal scroll for many tabs
- **Touch Targets**: Larger buttons and links
- **Readable Text**: Appropriate font sizes

#### Breakpoints
- **Large Screens**: 3-column layout
- **Medium Screens**: 2-column layout
- **Small Screens**: Single column, stacked

### Cross-browser Compatibility
- **Modern Browsers**: Chrome, Firefox, Safari, Edge
- **CSS Grid**: Flexbox fallbacks
- **JavaScript**: ES6 with fallbacks
- **Icons**: Bootstrap Icons (web fonts)

## Performance Optimization

### Loading Strategies

#### Initial Load
- **Core Data**: Load essential profile information
- **Lazy Loading**: Load tab content on demand
- **Image Optimization**: Efficient avatar loading
- **CSS Minification**: Optimized stylesheets

#### Data Caching
- **Browser Cache**: Static assets cached
- **Session Storage**: Temporary data storage
- **Database Optimization**: Efficient queries
- **CDN Integration**: Fast asset delivery

### Performance Metrics
- **Page Load Time**: Target < 2 seconds
- **Time to Interactive**: Target < 3 seconds
- **First Paint**: Target < 1 second
- **Largest Contentful Paint**: Target < 2.5 seconds

## Integration Points

### Internal System Integration

#### Work Order Management
- **Assignment History**: Show assigned work orders
- **Performance Metrics**: Work order statistics
- **Availability**: Check user availability

#### Customer Management
- **Service History**: Customer interactions
- **Appointment Scheduling**: Customer appointments
- **Feedback**: Customer ratings and reviews

#### Asset Management
- **Equipment Assignments**: Assigned tools and equipment
- **Maintenance Records**: Equipment maintenance history
- **Inventory Access**: Equipment check-out/in

### External System Integration

#### Calendar Systems
- **Google Calendar**: Sync appointments
- **Outlook**: Exchange integration
- **iCal**: Calendar export/import

#### HR Systems
- **Employee Database**: Sync employee information
- **Payroll**: Time sheet integration
- **Benefits**: Employee benefits information

#### Communication Systems
- **Email**: Automated notifications
- **SMS**: Text message alerts
- **Push Notifications**: Mobile app notifications

## Error Handling

### Common Errors

#### User Not Found
- **Error Code**: 404
- **Message**: "User not found"
- **Action**: Redirect to user list

#### Insufficient Permissions
- **Error Code**: 403
- **Message**: "Access denied"
- **Action**: Show limited information

#### Database Connection
- **Error Code**: 500
- **Message**: "Database error"
- **Action**: Show error page with retry option

### Error Recovery
- **Graceful Degradation**: Show available information
- **Retry Mechanisms**: Automatic retry for transient errors
- **User Feedback**: Clear error messages
- **Logging**: Detailed error logs for debugging

## Analytics and Reporting

### Usage Analytics
- **Profile Views**: Track profile page visits
- **Tab Usage**: Monitor which tabs are used most
- **Edit Frequency**: Track profile update frequency
- **User Engagement**: Measure user interaction

### Performance Analytics
- **Page Load Times**: Monitor performance
- **Error Rates**: Track error frequency
- **User Satisfaction**: Collect feedback
- **System Health**: Monitor system performance

## Future Enhancements

### Planned Features

#### Enhanced Timeline
- **Real-time Updates**: Live activity feed
- **Filtering Options**: Advanced activity filters
- **Export Capabilities**: Export activity logs
- **Notifications**: Activity-based alerts

#### Advanced Calendar
- **Full Calendar Integration**: Complete calendar system
- **Availability Management**: Set available hours
- **Recurring Appointments**: Repeat scheduling
- **Calendar Sharing**: Share availability

#### Time Sheet Improvements
- **Automated Tracking**: GPS-based time tracking
- **Approval Workflow**: Multi-level approvals
- **Reporting Tools**: Advanced time reports
- **Integration**: Payroll system integration

#### Mobile App
- **Native App**: iOS and Android apps
- **Offline Capability**: Work without internet
- **Push Notifications**: Real-time alerts
- **Camera Integration**: Photo attachments

### Technical Improvements

#### Performance Enhancements
- **Lazy Loading**: Load content on demand
- **Caching**: Advanced caching strategies
- **Database Optimization**: Query optimization
- **CDN Integration**: Global content delivery

#### Security Enhancements
- **Two-Factor Authentication**: Enhanced security
- **Encryption**: Data encryption at rest
- **Audit Logging**: Comprehensive audit trails
- **Privacy Controls**: Enhanced privacy settings

#### User Experience
- **Personalization**: Customizable layouts
- **Accessibility**: WCAG compliance
- **Internationalization**: Multi-language support
- **Dark Mode**: Alternative color schemes

## Testing Strategy

### Unit Testing
- **Controller Tests**: Test profile data retrieval
- **Model Tests**: Test data relationships
- **View Tests**: Test HTML output
- **JavaScript Tests**: Test client-side functionality

### Integration Testing
- **API Testing**: Test profile endpoints
- **Database Testing**: Test data integrity
- **UI Testing**: Test user interactions
- **Performance Testing**: Test load times

### User Acceptance Testing
- **Usability Testing**: Test user experience
- **Accessibility Testing**: Test accessibility features
- **Cross-browser Testing**: Test browser compatibility
- **Mobile Testing**: Test responsive design

## Maintenance and Support

### Regular Maintenance
- **Database Cleanup**: Remove old data
- **Performance Monitoring**: Track system health
- **Security Updates**: Apply security patches
- **Backup Procedures**: Regular data backups

### Support Procedures
- **Issue Tracking**: Log and track issues
- **User Training**: Provide user guidance
- **Documentation Updates**: Keep docs current
- **Feature Requests**: Collect and prioritize requests

## Conclusion

The User Profile page serves as a comprehensive information hub for individual users within the FSM platform. With its clean, intuitive design and comprehensive feature set, it provides administrators, managers, and users themselves with easy access to all relevant information and functionality.

The implementation focuses on:
- **User Experience**: Clean, intuitive interface
- **Performance**: Fast loading and responsive design
- **Security**: Robust access controls and data protection
- **Scalability**: Designed to handle growing data volumes
- **Integration**: Seamless integration with other modules

This profile system provides a solid foundation for workforce management and can be extended with additional features as the platform evolves.
