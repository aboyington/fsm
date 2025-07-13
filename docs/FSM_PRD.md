# Product Requirements Document (PRD): Field Service Management Platform

## Executive Summary
The Field Service Management (FSM) Platform is a comprehensive solution designed to support Canvass Global's field operations, including camera installations, repairs, and customer relationship management. The platform will enable efficient management of field technicians, work orders, customer data, and seamless integration with the existing Canvass Global camera registry system.

## Business Context
### Problem Statement
Canvass Global requires a robust system to:
- Manually canvass cities to identify and register camera owners
- Manage on-site camera repairs and maintenance
- Install new camera systems
- Track field service operations and customer interactions
- Synchronize field data with the main Canvass Global platform

### Target Users
1. **Field Technicians**: Perform installations, repairs, and customer visits
2. **Dispatchers**: Schedule and assign work orders
3. **Service Managers**: Oversee operations and workforce
4. **Customers**: Camera owners requiring service
5. **Administrators**: System configuration and management

## Objectives
- **Streamline Field Operations**: Reduce time from service request to completion by 40%
- **Improve Customer Satisfaction**: Track and manage customer interactions professionally
- **Optimize Resource Utilization**: Efficiently allocate technicians and parts
- **Ensure Data Synchronization**: Real-time sync with Canvass Global platform
- **Enable Data-Driven Decisions**: Comprehensive reporting and analytics

## Detailed Features

### 1. Work Order Management
- **Requests**: Service request intake and management
- **Estimates**: Cost estimation for services
- **Work Orders**: Complete work order lifecycle management
  - Service Line Items
  - Part Line Items
  - Service Task Line Items
- **Service Tasks**: Individual task management within work orders
- **Service Reports**: Comprehensive service completion reports
- **Record Templates**: Reusable templates for common work orders
- **Views**: Multiple visualization options
  - Gantt View
  - Grid View
  - Map View
  - Calendar View
- **Trips**: Field trip planning and tracking
- **Time Sheet**: Time tracking for technicians
- **Parts and Services**: Separate management for Parts and Service offerings
- **Multi-currency**: Support for international operations
- **Multi-day appointments**: Extended service scheduling
- **Maintenance Plans**: Preventive maintenance scheduling
- **Scheduled Maintenance**: Automated maintenance reminders

### 2. Contact Management
- **Customers**: Individual customer profiles and history
- **Companies**: Business customer management
- **Customer History**: Complete interaction history
- **Notes**: Detailed notes and communication logs
- **Notifications**: Automated customer notifications
- **Custom Notifications**: Configurable notification templates
- **Timeline**: Visual customer interaction timeline
- **Advanced Filters**: Sophisticated search and filtering
- **Contact Merge**: Duplicate management and merging

### 3. Workforce Management
- **Users**: Field technician profiles and management
- **Equipments**: Equipment assignment and tracking
- **Crew**: Team formation and management
- **Skills**: Skill tracking and assignment
- **Service Territories**: Geographic territory management
- **Business Hours**: Working hours configuration
- **Holiday**: Holiday calendar management
- **Time Off**: PTO and absence tracking
- **Field Agent Live Location Tracking**: Real-time GPS tracking

### 4. Service Management
- **Services and Parts**: Comprehensive catalog management
- **Asset Management**: Customer asset tracking and history

### 5. Billing (Powered by Zoho Invoice)
- **2-way sync**: Bidirectional sync with Zoho Invoice/Books
- **Invoices**: Invoice generation and management
- **Payments**: Payment processing and tracking
- **Payment Gateway Integration**: Multiple payment options
- **Taxes**: Location-specific tax calculation

### 6. Product Customization
- **Module Custom Fields**: Flexible field addition
  - Text & Choice fields (Single Line, Multi Line, Phone, Email, Pick List, URL)
  - Decimal & Currency fields
  - Date Time & Long Integer fields
  - Checkbox
  - Number
  - Date
- **Job Sheets Custom Fields**: Extensive customization
  - Text & Choice fields (Single Line, Multi Line, Phone, Email, Pick List, URL, Multi-Select Pick List, Check List, Radio Choice)
  - Decimal & Currency fields
  - Date Time & Long Integer fields
  - Checkbox
  - Number & Rating fields
  - Date
  - Image Upload
- **FSM List Views**: Customizable list displays
- **Custom Views**: User-defined view configurations
- **PDF Templates**: Custom PDF generation

### 7. Automation
- **Workflow Rules**: Business process automation
- **Time-based Workflow Rules**: Scheduled automation
- **Custom Functions**: JavaScript-based custom logic
- **Standalone Functions**: Independent automation scripts
- **Field Updates**: Automated field value changes
- **Email Templates**: Automated email communications
- **Email Notifications**: Event-driven notifications
- **Webhooks**: External system integration

### 8. Reports
- **Standard Reports**: Pre-built report templates
- **Custom Reports**: User-defined reporting
- **Dashboard**: Real-time KPI monitoring

### 9. Data Management
- **Mass Update**: Bulk data operations
- **File Storage**: Document management
- **Additional File Storage**: Expandable storage options
- **Data Storage**: Database management
- **Import Data**: Bulk data import capabilities
- **Export Data**: Data export functionality
- **Import History**: Import audit trail

### 10. Security and Privacy
- **Custom Profiles**: Role-based access control
- **Data Encryption (EAR)**: Encryption at rest
- **Audit Trail**: Complete activity logging
- **GDPR Compliance**: Privacy regulation compliance
- **Hybrid session-based authentication with OAuth 2.0 integration** (aligned with Canvass Global)

### 11. Developer Tools
- **APIs**: RESTful API access
  - Daily call limits
  - API Window limits
  - API Concurrency limits
- **Connections**: Third-party integrations
- **Webforms**: Embeddable web forms

### 12. Mobile Support
- **Mobile App for Field Techs**: Native mobile application
- **Offline Data Access**: Work without connectivity

### 13. Integration with Canvass Global
- **Camera Owner Sync**: Automatic synchronization of camera owner data
- **Service History**: Share service records with main platform
- **Real-time Updates**: Bidirectional data flow

## Non-functional Requirements
- **Performance**: The system should handle concurrent requests efficiently.
- **Scalability**: Capable of scaling to accommodate growing user base.
- **Reliability**: Ensure high availability and reliability.
- **Usability**: User-friendly interface for agents and administrators.

## Development & Hosting Environment
### Development Environment
- **Local Server**: MAMP (Mac, Apache, MySQL, PHP)
- **Project Location**: `/Users/anthony/Sites/fsm`
- **Development URL**: `http://localhost/fsm/`
- **Environment**: PHP 8.x, Apache 2.4

### Hosting Environment
- **Initial Hosting**: Namecheap Shared Hosting (https://www.namecheap.com/)
  - Cost-effective for initial deployment
  - Supports PHP/CodeIgniter applications
  - SQLite database compatible
- **Future Scaling**: Migration path to VPS or dedicated server as user base grows
- **Domain Configuration**: Will align with Canvass Global domain structure

## Timeline
1. **Phase 1**: Establish core features - Customer and Work Order Management. Target Date: 3 months from project initiation.
2. **Phase 2**: Add integrations with Canvass Global and enhance reporting features. Target Date: 5 months from project initiation.
3. **Phase 3**: Implement advanced features such as invoicing and developer customization. Target Date: 8 months from project initiation.

## Risks
- **Data Synchronization**: Ensuring reliable data sync with Canvass Global.
- **Security Risks**: Protecting sensitive customer and operational data.

---

*Version*: 1.0  
*Last Updated*: 2025-07-11

---
