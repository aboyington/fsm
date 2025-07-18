# Contact Detail View - Enhanced UI Documentation

## Overview

The Contact Detail View provides a comprehensive interface for viewing and managing detailed contact information. This view features an enhanced sidebar with detailed contact information and a tabbed main content area for organized data presentation, matching the design consistency of the Company Detail View.

## Page Structure

### URL Pattern
```
/customers/contacts/view/{contact_id}
```

### Layout Components
1. **Enhanced Sidebar** (Left Column - 25% width)
2. **Main Content Area** (Right Column - 75% width) with tabbed interface

## Enhanced Sidebar Components

### 1. Contact Header Card
**Purpose**: Primary contact identification and quick actions

**Components**:
- **Contact Avatar**: Circular avatar with contact initials (80px diameter)
- **Contact Name**: Full name (first + last name) (H5 heading)
- **Email Address**: Primary email address
- **Status Badge**: Active/Inactive status with color coding
  - Active: Green badge (`bg-success`)
  - Inactive: Gray badge (`bg-secondary`)
- **Quick Contact Info**: Phone and mobile numbers (if available)
- **Edit Button**: Primary action button (full width)

**Data Fields**:
- `contact['first_name']` - First name
- `contact['last_name']` - Last name
- `contact['email']` - Email address
- `contact['status']` - Contact status
- `contact['phone']` - Phone number
- `contact['mobile']` - Mobile number

### 2. Details Section Card
**Purpose**: Core contact information display

**Header**: "Details" with info circle icon

**Information Displayed**:
- **Email**: Primary email address
- **Phone**: Primary phone number (if available)
- **Mobile**: Mobile phone number (if available)

**Data Fields**:
- `contact['email']` - Email address
- `contact['phone']` - Phone number
- `contact['mobile']` - Mobile number

### 3. Company Information Card
**Purpose**: Associated company details

**Header**: "Company" with building icon

**Information Displayed**:
- **Company Name**: Clickable link to company detail page
- **Company Website**: External link (if available)
- **Company Phone**: Company phone number (if available)

**Data Fields**:
- `contact['company_name']` - Associated company name
- `contact['company_id']` - Company ID for linking
- `contact['company_website']` - Company website URL
- `contact['company_phone']` - Company phone number

**Empty State**: 
- Shows "No company associated" when contact is not linked to a company

**Navigation**:
- Company name links to: `/customers/companies/view/{company_id}`

### 4. Address Information Card
**Purpose**: Physical location management

**Header**: "Address" with location icon

**Address Types**:
- **Service Address**: Primary contact location
  - Street address
  - City, State
  - ZIP code
  - Country
- **Billing Address**: "Same as Service Address" (default)

**Data Fields**:
- `contact['address']` - Street address
- `contact['city']` - City
- `contact['state']` - State/Province
- `contact['zip_code']` - Postal code
- `contact['country']` - Country

### 5. Tax Information Card
**Purpose**: Tax configuration and compliance

**Header**: "Tax" with calculator icon

**Information**:
- **Tax Rule**: Currently displays "--" (placeholder for future implementation)

### 6. Invoice Information Card
**Purpose**: Billing system integration status

**Header**: "Invoice Information" with document icon

**Status**: "Record not linked yet" with link icon (placeholder for future integration)

### 7. Owner Information Card
**Purpose**: Ownership and audit trail

**Information Sections**:
- **Owner**: Current owner details
  - Name: Anthony Boyington
  - Email: boyington@protonmail.com
- **Created By**: Creation audit information
  - User: Anthony Boyington
  - Timestamp: Formatted creation date
- **Modified By**: Last modification audit (when available)
  - User: Anthony Boyington
  - Timestamp: Formatted update date

**Data Fields**:
- `contact['created_at']` - Creation timestamp
- `contact['updated_at']` - Last update timestamp

## Main Content Area - Tabbed Interface

### Tab Structure
The main content area features 7 tabs with consistent styling:

1. **Timeline** - Historical activity feed (default active)
2. **Dashboard** - Analytics and metrics
3. **Addresses** - Multiple address management
4. **Notes** - Contact notes and comments
5. **Attachments** - File management
6. **Related List** - Related records
7. **Billing** - Financial information

### Timeline Tab (Primary Implementation)

**Purpose**: Display chronological activity history for the contact

**Features**:
- **Filter Controls**: Dropdown menus for filtering activities
- **Date Headers**: Organized by date sections
- **Activity Items**: Individual activity entries with icons
- **Time Stamps**: Specific time information for each activity

**Filter Options**:
- **Activity Type**: "Show all updates" filter
- **Time Range**: "All Time" filter with options:
  - All Time
  - Last 7 days
  - Last 30 days
  - Last 4 months
  - Last 12 months

**Sample Timeline Items**:
1. **Contact Details Updated** (Warning icon, 03:00 PM)
2. **Contact Created** (Success icon, 02:47 PM)

### Dashboard Tab

**Purpose**: Contact performance metrics and analytics

**Features**:
- **Metric Cards**: Performance indicators in grid layout
- **Time Period Filter**: Monthly view selection
- **Real-time Data**: Dynamic content updates

**Metric Categories**:
- **Service Metrics**: Open requests, estimates, work orders, appointments
- **Performance Metrics**: Overdue items, revenue generation
- **Activity Metrics**: Communication frequency, response rates

### Addresses Tab

**Purpose**: Comprehensive address management

**Features**:
- **Add Address Button**: Create new addresses
- **Address Cards**: Multiple address display
- **Address Types**: Service and billing addresses
- **Edit Controls**: Modify existing addresses

**Address Management**:
- **Service Address**: Primary contact location
- **Billing Address**: Financial correspondence address
- **Additional Addresses**: Multiple location support

## Technical Implementation

### Controller Updates
**File**: `app/Controllers/ContactsController.php`

**Key Features**:
1. Enhanced data retrieval for contact details
2. Company information joining
3. Address management integration
4. Timeline data preparation

### View Implementation
**File**: `app/Views/contacts/view.php`

**Key Features**:
1. **Enhanced Sidebar Structure**: Multiple card-based sections
2. **Tabbed Interface**: Bootstrap nav-tabs with proper ARIA attributes
3. **Responsive Design**: Mobile-friendly layout
4. **Conditional Rendering**: Fields only display when data exists
5. **Security**: All output properly escaped using `esc()` function

### Styling Implementation

**Custom CSS Classes**:
- `.nav-tabs .nav-link` - Custom tab styling
- `.nav-tabs .nav-link.active` - Active tab highlighting
- `.timeline-item` - Timeline entry styling
- `.timeline-icon` - Activity icon styling
- `.timeline-content` - Activity content styling

**Timeline Styling**:
- **Date Headers**: Bold section dividers
- **Activity Icons**: Circular colored icons
- **Content Cards**: Light background with colored left border
- **Responsive Design**: Mobile-optimized timeline layout

## Data Flow

### Page Load Process
1. **Route**: `/customers/contacts/view/{id}`
2. **Controller**: `ContactsController::view($id)`
3. **Data Retrieval**:
   - Contact data via `ContactModel::getContactWithCompany($id)`
   - Timeline data preparation
   - Address information gathering
4. **View Rendering**: `contacts/view.php` with data array
5. **Client Rendering**: Enhanced sidebar and tabbed interface

### Company Navigation Flow
1. **User Action**: Click on company name in company card
2. **Navigation**: Browser navigates to `/customers/companies/view/{company_id}`
3. **Target Page**: Company detail view loads with full company information

## User Experience Features

### Visual Hierarchy
1. **Primary**: Contact name and status
2. **Secondary**: Contact information and company details
3. **Tertiary**: Audit information and technical details

### Interactive Elements
1. **Edit Button**: Primary action for contact modification
2. **Company Link**: Navigate to associated company details
3. **External Links**: Website links open in new tabs
4. **Tab Navigation**: Smooth switching between content areas
5. **Timeline Filters**: Dynamic content filtering

### Responsive Behavior
- **Desktop**: Full sidebar and main content side-by-side
- **Tablet**: Adapted layout with appropriate spacing
- **Mobile**: Stacked layout with touch-friendly interfaces

## Avatar Generation

### Contact Initials
- **Logic**: First letter of first name + first letter of last name
- **Styling**: White text on blue background
- **Size**: 80px diameter circular avatar
- **Fallback**: Default avatar for missing names

**Implementation**:
```php
<?= strtoupper(substr($contact['first_name'], 0, 1) . substr($contact['last_name'], 0, 1)) ?>
```

## Address Management Integration

### Service Address Display
- **Formatted Output**: Street, City, State, ZIP, Country
- **Conditional Rendering**: Only show available fields
- **Edit Capability**: Direct editing from sidebar
- **Geocoding Support**: Location coordinate management

### Billing Address Handling
- **Default Behavior**: Same as service address
- **Override Option**: Separate billing address when needed
- **Consistency**: Maintains data integrity across addresses

## Future Enhancements

### Planned Features
1. **Enhanced Timeline**: Real-time activity updates
2. **Communication Log**: Email and call history
3. **Task Management**: Action items and follow-ups
4. **Document Management**: File attachments and notes
5. **Integration Hooks**: CRM and third-party system sync
6. **Custom Fields**: Configurable contact attributes
7. **Relationship Mapping**: Contact hierarchy and relationships

### Data Integration Points
1. **Work Order System**: Service history and requests
2. **Communication System**: Email and phone call logs
3. **Billing System**: Invoice and payment history
4. **Task Management**: Follow-up actions and reminders
5. **Asset Management**: Equipment assignments and ownership

## Security Considerations

### Data Protection
- All user input properly escaped
- Database queries use parameterized statements
- Access control enforced at controller level
- Audit trail maintained for all changes

### Permission Requirements
- **View**: Read access to contacts module
- **Edit**: Modify access to contacts module
- **Navigate**: Access to both contacts and companies modules

### Privacy Compliance
- **Data Minimization**: Only display necessary information
- **Consent Tracking**: Communication preferences respect
- **Audit Logging**: Complete activity tracking for compliance

## Performance Considerations

### Data Loading
- **Lazy Loading**: Timeline data loaded on demand
- **Caching**: Frequent data cached for performance
- **Pagination**: Large datasets paginated appropriately
- **Optimization**: Database queries optimized for speed

### UI Responsiveness
- **Progressive Loading**: Critical data loaded first
- **Smooth Transitions**: CSS transitions for tab switching
- **Mobile Optimization**: Touch-friendly interface elements
- **Loading States**: Visual feedback during data loading

---

**Last Updated**: January 2025  
**Version**: 2.0  
**Module**: Contacts Management - Detail View  
**Dependencies**: ContactModel, ClientModel, Bootstrap 5, Bootstrap Icons
