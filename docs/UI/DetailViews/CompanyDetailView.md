# Company Detail View - Enhanced UI Documentation

## Overview

The Company Detail View provides a comprehensive interface for viewing and managing detailed company information. This view features an enhanced sidebar with detailed company information and a tabbed main content area for organized data presentation.

## Page Structure

### URL Pattern
```
/customers/companies/view/{company_id}
```

### Layout Components
1. **Enhanced Sidebar** (Left Column - 25% width)
2. **Main Content Area** (Right Column - 75% width) with tabbed interface

## Enhanced Sidebar Components

### 1. Company Header Card
**Purpose**: Primary company identification and quick actions

**Components**:
- **Company Avatar**: Circular icon with building symbol (80px diameter)
- **Company Name**: Primary business name (H5 heading)
- **Contact Person**: Primary contact person name (if available)
- **Status Badge**: Active/Inactive status with color coding
  - Active: Green badge (`bg-success`)
  - Inactive: Gray badge (`bg-secondary`)
- **Quick Contact Info**: Phone and email (if available)
- **Edit Button**: Primary action button (full width)

**Data Fields**:
- `company['client_name']` - Company name
- `company['contact_person']` - Primary contact
- `company['status']` - Company status
- `company['phone']` - Phone number
- `company['email']` - Email address

### 2. Details Section Card
**Purpose**: Core company information display

**Header**: "Details" with info circle icon

**Information Displayed**:
- **Website**: Clickable external link with launch icon
- **Account Type**: Company type/category
- **Phone**: Primary phone number
- **Email**: Primary email address

**Data Fields**:
- `company['website']` - Company website URL
- `company['company_type']` - Business category
- `company['phone']` - Phone number
- `company['email']` - Email address

### 3. Address Information Card
**Purpose**: Physical location management

**Header**: "Address" with location icon

**Address Types**:
- **Service Address**: Primary business location
  - Street address
  - City, State
  - ZIP code
  - Country
- **Billing Address**: "Same as Service Address" (default)

**Data Fields**:
- `company['address']` - Street address
- `company['city']` - City
- `company['state']` - State/Province
- `company['zip_code']` - Postal code
- `company['country']` - Country

### 4. Tax Information Card
**Purpose**: Tax configuration and compliance

**Header**: "Tax" with calculator icon

**Information**:
- **Tax Rule**: Currently displays "--" (placeholder for future implementation)

### 5. Invoice Information Card
**Purpose**: Billing system integration status

**Header**: "Invoice Information" with document icon

**Status**: "Record not linked yet" with link icon (placeholder for future integration)

### 6. Owner Information Card
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
- `company['created_at']` - Creation timestamp
- `company['updated_at']` - Last update timestamp

## Main Content Area - Tabbed Interface

### Tab Structure
The main content area features 8 tabs with consistent styling:

1. **Timeline** - Historical activity feed
2. **Dashboard** - Analytics and metrics
3. **Contacts** - Associated contacts (default active)
4. **Addresses** - Multiple address management
5. **Notes** - Company notes and comments
6. **Attachments** - File management
7. **Billing** - Financial information
8. **Related List** - Related records

### Contacts Tab (Primary Implementation)

**Purpose**: Display and manage all contacts associated with the company

**Features**:
- **Responsive Table**: Bootstrap table with hover effects
- **Contact Links**: Clickable contact names linking to individual contact detail pages
- **Status Indicators**: Color-coded status badges
- **Primary Contact Marking**: Blue "Primary" badge for designated primary contacts

**Table Columns**:
1. **Name**: First name + Last name (clickable link)
2. **Email**: Contact email address
3. **Phone**: Primary phone number
4. **Job Title**: Professional title
5. **Status**: Active/Inactive with colored badges
6. **Primary**: Primary contact indicator

**Empty State**: 
- Shows centered message with people icon when no contacts exist
- Message: "No contacts found for this company"

**Data Source**:
- Retrieved via `ContactModel::getContactsByCompany($company_id)`
- Returns array of contact records with all necessary fields

**Navigation**:
- Contact names link to: `/customers/contacts/view/{contact_id}`

## Technical Implementation

### Controller Updates
**File**: `app/Controllers/CompaniesController.php`

**Key Changes**:
1. Added `ContactModel` import and initialization
2. Enhanced `view()` method to fetch related contacts
3. Passes contacts data to view

**Code Example**:
```php
// Get company contacts
$contacts = $this->contactModel->getContactsByCompany($id);

$data = [
    'title' => $company['client_name'] . ' - Company Details',
    'company' => $company,
    'contacts' => $contacts,
    'assets' => [], // TODO: Future implementation
    'activities' => [] // TODO: Future implementation
];
```

### View Implementation
**File**: `app/Views/companies/view.php`

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
- `.card` - Enhanced card shadows and borders
- `.badge` - Status badge styling

**Color Scheme**:
- Active elements: Green (`#198754`)
- Primary actions: Blue (Bootstrap primary)
- Success states: Green (Bootstrap success)
- Secondary states: Gray (Bootstrap secondary)

## Data Flow

### Page Load Process
1. **Route**: `/customers/companies/view/{id}`
2. **Controller**: `CompaniesController::view($id)`
3. **Data Retrieval**:
   - Company data via `ClientModel::find($id)`
   - Contact data via `ContactModel::getContactsByCompany($id)`
4. **View Rendering**: `companies/view.php` with data array
5. **Client Rendering**: Enhanced sidebar and tabbed interface

### Contact Navigation Flow
1. **User Action**: Click on contact name in contacts table
2. **Navigation**: Browser navigates to `/customers/contacts/view/{contact_id}`
3. **Target Page**: Contact detail view loads with full contact information

## User Experience Features

### Visual Hierarchy
1. **Primary**: Company name and status
2. **Secondary**: Contact information and details
3. **Tertiary**: Audit information and technical details

### Interactive Elements
1. **Edit Button**: Primary action for company modification
2. **Contact Links**: Navigate to individual contact details
3. **External Links**: Website links open in new tabs
4. **Tab Navigation**: Smooth switching between content areas

### Responsive Behavior
- **Desktop**: Full sidebar and main content side-by-side
- **Tablet**: Adapted layout with appropriate spacing
- **Mobile**: Stacked layout with touch-friendly interfaces

## Future Enhancements

### Planned Features
1. **Tax System Integration**: Dynamic tax rule configuration
2. **Invoice System Integration**: Link to billing records
3. **Activity Timeline**: Real-time activity feed
4. **Dashboard Analytics**: Company performance metrics
5. **Address Management**: Multiple address types and locations
6. **File Attachments**: Document management system
7. **Notes System**: Internal company notes and comments

### Data Integration Points
1. **Work Order System**: Display service history
2. **Asset Management**: Show company assets
3. **Billing System**: Financial records and invoices
4. **Territory Management**: Service area assignments
5. **User Activity**: Audit trail and timeline events

## Security Considerations

### Data Protection
- All user input properly escaped
- Database queries use parameterized statements
- Access control enforced at controller level
- Audit trail maintained for all changes

### Permission Requirements
- **View**: Read access to companies module
- **Edit**: Modify access to companies module
- **Navigate**: Access to both companies and contacts modules

---

**Last Updated**: January 2025  
**Version**: 2.0  
**Module**: Companies Management - Detail View  
**Dependencies**: ContactModel, ClientModel, Bootstrap 5, Bootstrap Icons
