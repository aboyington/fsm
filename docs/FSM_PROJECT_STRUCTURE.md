# Field Service Management Platform Structure

## Overview
This document outlines the complete structure for the Field Service Management (FSM) platform that integrates with Canvass Global for managing camera installations, repairs, and customer relationships.

## Core Feature Modules

### 1. Work Order Management
- Service Requests, Estimates, Work Orders
- Service/Part/Task Line Items
- Service Reports & Templates
- Multiple Views (Gantt, Grid, Map, Calendar)
- Trips, Time Sheets, Multi-currency support
- Maintenance Plans & Scheduled Maintenance

### 2. Contact Management  
- Customers, Companies, Assets
- Customer History & Timeline
- Notes, Notifications, Custom Alerts
- Advanced Filters & Contact Merge

### 3. Workforce Management
- Users, Equipment, Crews, Skills
- Service Territories & Business Hours
- Holiday & Time Off Management
- Live Location Tracking

### 4. Service Management
- Services & Parts Catalog
- Asset Management & History

### 5. Billing (Zoho Integration)
- 2-way sync with Zoho Invoice/Books
- Invoices, Payments, Gateway Integration
- Location-specific Tax Management

### 6. Customization
- Module & Job Sheet Custom Fields
- Multiple Field Types (Text, Number, Date, Currency, etc.)
- Custom Views & PDF Templates
- Image Upload Support

### 7. Automation
- Workflow Rules (Time-based & Event-based)
- Custom Functions & Scripts
- Field Updates & Email Templates
- Webhooks for External Integration

### 8. Reports & Analytics
- Standard & Custom Reports
- Real-time Dashboard
- KPI Monitoring

### 9. Data Management
- Mass Updates & Import/Export
- File Storage & Document Management
- Import History & Audit Trails

### 10. Security & Privacy
- Custom Profiles & Role-based Access
- Data Encryption at Rest (EAR)
- GDPR Compliance
- Complete Audit Trail

### 11. Developer Tools
- RESTful APIs with Rate Limiting
- External Connections
- Embeddable Webforms

### 12. Mobile Support
- Native Mobile App for Field Technicians
- Offline Data Access & Sync

### 13. Canvass Global Integration
- Camera Owner Data Synchronization
- Service History Sharing
- Real-time Bidirectional Updates

## Technology Stack
- **Backend**: CodeIgniter 4
- **Database**: SQLite
- **Frontend**: Modern JavaScript with responsive design
- **API Integration**: RESTful API to connect with Canvass Global
- **Authentication**: Hybrid session-based authentication with OAuth 2.0 (matching Canvass Global architecture)

## Development Environment
- **Local Development**: MAMP Server
- **Project Directory**: `/Users/anthony/Sites/fsm`
- **Local URL**: `http://localhost/fsm/`
- **All Projects Location**: `/Users/anthony/Sites/`

## Deployment Strategy
- **Initial Deployment**: Namecheap Shared Hosting
  - Document root: `/public`
  - Environment detection for API URLs
  - SQLite database in `/writable/database/`
- **Future Growth**: Migration to VPS/Dedicated Server when scale demands
