# FSM Documentation Index

## Overview
Welcome to the Field Service Management (FSM) platform documentation. This system is designed to support Canvass Global's field operations, including camera installations, repairs, and customer relationship management.

## Documentation Structure

### 📋 Project Overview
- **[Product Requirements Document (PRD)](FSM_PRD.md)** - Complete project requirements and business context
- **[Project Structure](FSM_PROJECT_STRUCTURE.md)** - High-level architecture and module overview
- **[Development Roadmap](DEVELOPMENT_ROADMAP.md)** - Implementation timeline and milestones
- **[File Structure](FILE_STRUCTURE.md)** - Detailed directory and file organization

### 🚀 Getting Started
- **[Setup Guide](SETUP_GUIDE.md)** - Installation and configuration instructions
- **[Troubleshooting](TROUBLESHOOTING.md)** - Common issues and solutions

### 📆 Module Documentation
- **[Dashboard Overview](DASHBOARD_OVERVIEW.md)** - Dashboard architecture, KPIs, and user interface
- **[Dashboard Implementation](DASHBOARD_IMPLEMENTATION.md)** - Technical implementation, troubleshooting, and best practices
- **[Navigation Structure](NAVIGATION_STRUCTURE.md)** - Navigation organization and recent updates
- **[Settings Module](SETTINGS_MODULE.md)** - Organization, currency, and system configuration
- **[User Management](USER_MANAGEMENT.md)** - User administration and access control
- **[Territory Management](TERRITORIES.md)** - Geographic service area management
- **[Skills & Holiday Management](SKILLS_HOLIDAY_MANAGEMENT.md)** - Skill tracking and holiday configuration
- **[Profiles Management](PROFILES_MANAGEMENT.md)** - User profiles and permission management
- **[Audit Log Management](AUDIT_LOG_MANAGEMENT.md)** - System activity tracking and compliance
- **[Version Management](VERSION_MANAGEMENT.md)** - Automated version control and release management

### 🔧 Technical Details
- **Database**: SQLite with CodeIgniter 4 migrations
- **Backend**: PHP 8.x with CodeIgniter 4 framework
- **Frontend**: Bootstrap 5, jQuery, Font Awesome
- **Authentication**: Session-based with CSRF protection
- **Version Management**: Automated versioning system with semantic versioning

## Quick Links

### For Developers
1. Start with the [Setup Guide](SETUP_GUIDE.md)
2. Review the [File Structure](FILE_STRUCTURE.md)
3. Check [Troubleshooting](TROUBLESHOOTING.md) for common issues

### For Project Managers
1. Review the [PRD](FSM_PRD.md) for business requirements
2. Check the [Development Roadmap](DEVELOPMENT_ROADMAP.md) for timeline
3. See module docs for feature details

### For System Administrators
1. Follow the [Setup Guide](SETUP_GUIDE.md) for installation
2. Configure the system via [Settings Module](SETTINGS_MODULE.md)
3. Manage users with [User Management](USER_MANAGEMENT.md)

## Recent Updates (January 2025)

### New Features
- ✅ **Dashboard System**: Complete implementation with dropdown navigation
  - ✅ Overview Dashboard with comprehensive metrics
  - ✅ Request Management Dashboard
  - ✅ Service Appointment Management Dashboard
  - ✅ Technician View Dashboard
- ✅ **Dashboard Navigation**: Dropdown menu integration with all views
- ✅ Enhanced navigation structure with optimized submenus
- ✅ Workforce module with comprehensive submenu (Users, Crew, Equipments, Trips, Auto Log, Time Off)
- ✅ Parts And Service module with focused submenu (Parts, Service)
- ✅ Unified icon system with consistent sizing across all modules
- ✅ Reports relocated to Settings page for better organization
- ✅ Territory Management system implemented
- ✅ Skills Management with categorization and proficiency tracking
- ✅ Holiday Management with year-based configuration
- ✅ Profiles Management with permission matrix
- ✅ Audit Log Management with dual-tab interface
- ✅ User Management with role-based access
- ✅ Organization and currency configuration
- ✅ Business hours and fiscal year settings
- ✅ **Version Management System**: Automated versioning with semantic versioning support

### Bug Fixes
- ✅ Fixed HTTP method detection issues in controllers
- ✅ Resolved 400 Bad Request errors for POST operations
- ✅ Improved delete operation handling

### Documentation Updates
- ✅ Created comprehensive Dashboard Overview documentation
- ✅ Added Navigation Structure documentation with recent updates
- ✅ Updated FSM PRD to reflect new navigation organization
- ✅ Enhanced README with new module documentation links
- ✅ Added comprehensive troubleshooting guide
- ✅ Created detailed territory management documentation
- ✅ Updated settings module documentation
- ✅ Added this documentation index

## Module Status

| Module | Status | Documentation |
|--------|--------|---------------|
| Authentication | ✅ Complete | Integrated in User Management |
| Dashboard | ✅ Complete | [DASHBOARD_OVERVIEW.md](DASHBOARD_OVERVIEW.md) / [DASHBOARD_IMPLEMENTATION.md](DASHBOARD_IMPLEMENTATION.md) |
| Settings | ✅ Complete | [SETTINGS_MODULE.md](SETTINGS_MODULE.md) |
| Users | ✅ Complete | [USER_MANAGEMENT.md](USER_MANAGEMENT.md) |
| Parts And Service | ✅ Complete | Integrated in FSM Documentation |
| Territories | ✅ Complete | [TERRITORIES.md](TERRITORIES.md) |
| Skills | ✅ Complete | [SKILLS_HOLIDAY_MANAGEMENT.md](SKILLS_HOLIDAY_MANAGEMENT.md) |
| Holidays | ✅ Complete | [SKILLS_HOLIDAY_MANAGEMENT.md](SKILLS_HOLIDAY_MANAGEMENT.md) |
| Profiles | ✅ Complete | [PROFILES_MANAGEMENT.md](PROFILES_MANAGEMENT.md) |
| Audit Log | ✅ Complete | [AUDIT_LOG_MANAGEMENT.md](AUDIT_LOG_MANAGEMENT.md) |
| Version Management | ✅ Complete | [VERSION_MANAGEMENT.md](VERSION_MANAGEMENT.md) |
| Customers | 🚧 In Progress | Coming Soon |
| Work Orders | 📋 Planned | Coming Soon |
| Scheduling | 📋 Planned | Coming Soon |
| Billing | 📋 Planned | Coming Soon |
| Reports | 📋 Planned | Coming Soon |

## Contributing

When adding new features or fixing bugs:

1. **Update relevant documentation** - Keep docs in sync with code
2. **Add to troubleshooting** - Document any issues and solutions
3. **Update module status** - Mark progress in this README
4. **Follow conventions** - Use existing patterns and structures

## Support

For questions or issues:
1. Check the [Troubleshooting Guide](TROUBLESHOOTING.md)
2. Review module-specific documentation
3. Check application logs in `/writable/logs/`
4. Enable debug mode for detailed error messages

---

*Last Updated*: January 2025  
*Version*: 1.0  
*Platform*: FSM - Field Service Management
