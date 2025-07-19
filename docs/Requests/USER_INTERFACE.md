# User Interface Guide - Requests Module

## Overview
This guide covers all user interface components and interactions within the Requests module, providing detailed information for both end users and developers.

## Page Structure

### Main Navigation
The Requests module is accessible via:
- **Primary Path**: Work Order Management → Request
- **Breadcrumb**: Dashboard → Work Order Management → Requests
- **Direct URL**: `/work-order-management/request`

### Index Page Layout

#### Header Section
- **Title**: "Requests" with breadcrumb navigation
- **Primary Action**: "Create Request" button (green, with plus icon)
- **Page Description**: Brief explanation of requests functionality

#### Filter and Search Bar

**Search Input**
- **Location**: Left side of filter bar
- **Placeholder**: "Search requests..."
- **Icon**: Search/magnifying glass
- **Functionality**: Real-time search across request names and descriptions

**Status Filter**
- **Options**: All Status, Pending, In Progress, On Hold, Completed
- **Default**: All Status
- **Styling**: Bootstrap form-select

**Priority Filter**
- **Options**: All Priority, Low, Medium, High
- **Default**: All Priority
- **Styling**: Bootstrap form-select

**Company Filter**
- **Options**: All Companies + dynamic list of companies
- **Default**: All Companies
- **Data Source**: Populated from clients table**Statistics Summary**
- **Location**: Right side of filter bar
- **Displays**: Total, Pending, In Progress, On Hold, Completed counts
- **Format**: "Total: X | Pending: X | In Progress: X | On Hold: X | Completed: X"
- **Styling**: Muted text for subtle information display

#### Requests Table

**Table Structure**
| Column | Description | Data Type | Sortable |
|--------|-------------|-----------|----------|
| Request Number | Unique identifier | Link | No |
| Request Name | Descriptive name | Text | No |
| Company | Associated company | Badge | No |
| Contact | Primary contact | Text | No |
| Status | Current status | Badge | No |
| Priority | Request priority | Badge | No |
| Created | Creation date | Date | No |
| Created By | User who created | Text | No |
| Actions | Available actions | Buttons | No |

**Status Badge Colors**
- **Pending**: Warning (Yellow) - `bg-warning`
- **In Progress**: Info (Blue) - `bg-info`
- **On Hold**: Secondary (Gray) - `bg-secondary`
- **Completed**: Success (Green) - `bg-success`

**Priority Badge Colors**
- **Low**: Secondary (Gray) - `bg-secondary`
- **Medium**: Warning (Yellow) - `bg-warning`
- **High**: Danger (Red) - `bg-danger`

**Action Buttons**
- **View**: Eye icon - `bi-eye` - Info outline style
- **Edit**: Pencil icon - `bi-pencil` - Primary outline style
- **Delete**: Trash icon - `bi-trash` - Danger outline style

#### Empty State

**Displayed When**: No requests exist in the system

**Visual Elements**:
- **Icon**: Large clipboard-check icon (`bi-clipboard-check`)
- **Background**: White rounded container with shadow
- **Title**: "Service Requests" (h4 heading)
- **Description**: Explanatory text about creating and managing requests
- **Call-to-Action**: Large "Create Request" button

**Layout**: Centered vertically and horizontally on the page

### Detail View Page

#### Header Section
- **Breadcrumb**: Dashboard → Requests → [Request Number]
- **Title**: Request name or "Request Details"
- **Subtitle**: Request description
- **Primary Action**: "Convert to Work Order" (green button)
- **Secondary Actions**: Dropdown with additional options

#### Action Dropdown Options
- **Edit**: Edit request details
- **Convert to Estimate**: Create estimate from request
- **Divider**: Visual separator
- **Cancel**: Cancel the request
- **Terminate**: Terminate the request
- **Divider**: Visual separator
- **Download**: Download request as PDF
- **Print**: Open print-friendly version

#### Left Sidebar - Request Information Panel

**Request Header Card**
- **Icon**: Large circular badge with clipboard-check icon
- **Request Number**: Display request identifier
- **Status Badge**: Current status with appropriate color

**Quick Info Sections**
- **Company Information**: Company name, contact details
- **Priority Level**: Visual priority indicator
- **Created Information**: Creation date and creator
- **Last Modified**: Modification date and user

#### Main Content Area - Tabbed Interface

**Tab Navigation**
- **Style**: Bootstrap nav-tabs with custom styling
- **Active State**: Green bottom border and bold text
- **Hover State**: Light background color

**Available Tabs**:

1. **Details Tab** (Default)
   - Request information summary
   - Description field
   - Key metadata

2. **Timeline Tab**
   - Activity history
   - Status changes
   - User actions
   - Creation event

3. **Notes Tab**
   - Add and view notes
   - Comments section
   - Internal documentation

4. **Attachments Tab**
   - File uploads
   - Document management
   - Image handling

5. **Related List Tab**
   - Connected work orders
   - Related estimates
   - Linked records

### Create/Edit Modal

#### Modal Structure
- **Size**: Standard Bootstrap modal
- **Title**: "Create Request" or "Edit Request"
- **Form Layout**: Two-column responsive design

#### Form Fields

**Required Fields**
- **Request Name**: Text input with validation

**Optional Fields**
- **Company**: Dropdown select with search
- **Contact**: Dependent dropdown (filtered by company)
- **Priority**: Radio buttons or dropdown
- **Description**: Textarea for detailed information

**Form Validation**
- **Client-side**: Real-time validation
- **Server-side**: Backend validation with error display
- **Error Display**: Red text below fields with specific messages

#### Modal Actions
- **Save**: Primary button (blue/green)
- **Cancel**: Secondary button (gray)
- **Close**: X button in header

## Responsive Design

### Desktop (≥992px)
- Full table display with all columns
- Side-by-side filter layout
- Complete detail view with sidebar

### Tablet (768px - 991px)
- Stacked filter elements
- Responsive table with horizontal scroll
- Sidebar stacks below main content

### Mobile (<768px)
- Vertical filter stack
- Card-based request display instead of table
- Single column detail view
- Collapsible sections for better navigation

## Accessibility Features

### ARIA Labels
- **Navigation**: `aria-label` on breadcrumbs
- **Buttons**: Descriptive labels for screen readers
- **Tables**: Proper header associations
- **Forms**: Label associations and error descriptions

### Keyboard Navigation
- **Tab Order**: Logical progression through interface
- **Focus Indicators**: Visible focus states on all interactive elements
- **Shortcuts**: Enter key submission on forms

### Color Contrast
- All text meets WCAG AA standards
- Status badges maintain readability
- Interactive elements have sufficient contrast

## Interactive Features

### Real-time Search
- **Trigger**: Keystroke in search field
- **Debouncing**: 300ms delay to prevent excessive requests
- **Results**: Table updates without page refresh

### Filter Combinations
- Multiple filters can be applied simultaneously
- URL parameters preserve filter state
- Clear all filters option available

### Status Updates
- **Method**: AJAX requests for seamless updates
- **Feedback**: Success/error messages
- **Optimistic Updates**: UI updates before server confirmation

### Modal Interactions
- **Backdrop Click**: Closes modal (configurable)
- **Escape Key**: Closes modal
- **Form Submission**: AJAX with validation feedback

---

*Last Updated*: January 2025  
*Version*: 1.0  
*Module*: Requests - User Interface Documentation