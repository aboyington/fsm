# FSM File Structure Documentation

## Project Directory Structure

```
fsm/
├── app/
│   ├── Config/
│   │   ├── App.php                 # Application configuration
│   │   ├── Autoload.php            # PSR-4 autoload configuration
│   │   ├── Database.php            # Database configuration
│   │   ├── Filters.php             # HTTP filters configuration
│   │   ├── Routes.php              # Application routes
│   │   └── ...                     # Other configuration files
│   │
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php  # API authentication endpoints
│   │   │   └── CustomerController.php # Customer API endpoints
│   │   ├── AuthController.php      # Web authentication
│   │   ├── BaseController.php      # Base controller class
│   │   ├── CurrencyController.php  # Currency management
│   │   ├── Home.php                # Default controller
│   │   └── Settings.php            # Settings module controller
│   │
│   ├── Database/
│   │   ├── Migrations/
│   │   │   ├── 2025-01-11-000001_CreateUsersTable.php
│   │   │   ├── 2025-01-11-000001_CreateOrganizationsTable.php
│   │   │   ├── 2025-01-11-000002_CreateCustomersTable.php
│   │   │   ├── 2025-01-11-000002_CreateFiscalYearsTable.php
│   │   │   ├── 2025-01-11-000003_CreateWorkOrdersTable.php
│   │   │   ├── 2025-01-11-000005_CreateCurrenciesTable.php
│   │   │   └── 2025-01-11-101300_CreateBusinessHoursTable.php
│   │   └── Seeds/
│   │       ├── CustomerSeeder.php
│   │       └── UserSeeder.php
│   │
│   ├── Filters/
│   │   └── AuthFilter.php          # Authentication filter
│   │
│   ├── Helpers/                    # Custom helper functions
│   │
│   ├── Libraries/                  # Custom libraries
│   │
│   ├── Models/
│   │   ├── BusinessHoursModel.php  # Business hours data model
│   │   ├── CurrencyModel.php       # Currency data model
│   │   ├── CustomerModel.php       # Customer data model
│   │   ├── FiscalYearModel.php     # Fiscal year data model
│   │   ├── OrganizationModel.php   # Organization data model
│   │   ├── UserModel.php           # User data model
│   │   └── WorkOrderModel.php      # Work order data model
│   │
│   └── Views/
│       ├── auth/
│       │   └── login.php           # Login page
│       ├── currency/
│       │   └── index.php           # Currency listing (deprecated)
│       ├── dashboard/
│       │   └── index.php           # Main dashboard
│       ├── layouts/
│       │   ├── footer.php          # Page footer
│       │   ├── header.php          # Page header
│       │   └── main.php            # Main layout template
│       ├── settings/
│       │   ├── currency.php        # Currency management view
│       │   ├── fiscal_year.php     # Fiscal year settings
│       │   ├── index.php           # Settings index
│       │   ├── layout.php          # Settings layout
│       │   └── organization.php    # Organization profile
│       └── templates/
│           ├── footer.php          # Template footer
│           └── header.php          # Template header
│
├── docs/
│   ├── DEVELOPMENT_ROADMAP.md      # Development roadmap and progress
│   ├── FILE_STRUCTURE.md           # This file - detailed structure
│   ├── FSM_PRD.md                  # Product requirements document
│   ├── FSM_PROJECT_STRUCTURE.md    # High-level project structure
│   ├── SETTINGS_MODULE.md          # Settings module documentation
│   └── SETUP_GUIDE.md              # Setup and installation guide
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css           # Custom styles
│   │   └── js/
│   │       ├── api.js              # API client utilities
│   │       └── app.js              # Main application JavaScript
│   ├── index.php                   # Application entry point
│   └── .htaccess                   # Apache configuration
│
├── tests/                          # Test files and fixtures
│
├── vendor/                         # Composer dependencies
│
├── writable/
│   ├── cache/                      # Application cache
│   ├── database/
│   │   └── fsm.db                  # SQLite database
│   ├── logs/                       # Application logs
│   ├── session/                    # Session files
│   └── uploads/                    # User uploads
│
├── .env                            # Environment configuration
├── .gitignore                      # Git ignore rules
├── composer.json                   # Composer dependencies
├── composer.lock                   # Composer lock file
├── env                             # Environment template
├── LICENSE                         # License file
├── README.md                       # Project readme
└── spark                           # CodeIgniter CLI tool
```

## Key Files Description

### Controllers
- **Settings.php**: Manages all settings-related functionality including organization, currency, fiscal year, and business hours
- **CurrencyController.php**: Dedicated controller for currency CRUD operations (integrated with Settings)
- **AuthController.php**: Handles user authentication for web interface
- **Api/AuthController.php**: JWT-based authentication for API access

### Models
- **OrganizationModel.php**: Manages company profile and preferences
- **CurrencyModel.php**: Handles currency data with validation rules
- **FiscalYearModel.php**: Manages fiscal year configurations
- **BusinessHoursModel.php**: Handles business hours settings
- **UserModel.php**: User authentication and management
- **CustomerModel.php**: Customer data management
- **WorkOrderModel.php**: Work order tracking

### Views
- **settings/**: Contains all settings module views
  - **layout.php**: Settings module layout with sidebar navigation
  - **organization.php**: Company profile and preferences
  - **currency.php**: Currency management interface
  - **fiscal_year.php**: Fiscal year configuration
- **layouts/**: Shared layout components
- **templates/**: Legacy template files

### Database
- **Migrations**: Schema definitions for all tables
- **Seeds**: Sample data for testing and development

### Public Assets
- **assets/css/style.css**: Custom CSS styles
- **assets/js/api.js**: API client with authentication
- **assets/js/app.js**: Application-wide JavaScript

## Naming Conventions

### Files
- Controllers: PascalCase (e.g., `CurrencyController.php`)
- Models: PascalCase with Model suffix (e.g., `CurrencyModel.php`)
- Views: snake_case (e.g., `fiscal_year.php`)
- Migrations: Date_Description (e.g., `2025-01-11-000005_CreateCurrenciesTable.php`)

### Database
- Tables: Plural, snake_case (e.g., `currencies`, `business_hours`)
- Columns: Singular, snake_case (e.g., `exchange_rate`, `thousand_separator`)

### Routes
- Web routes: lowercase with hyphens (e.g., `/settings/business-hours`)
- API routes: RESTful conventions (e.g., `GET /api/customers`, `POST /api/auth/login`)

## Module Organization

### Settings Module
All settings-related functionality is organized under:
- Controller: `app/Controllers/Settings.php`
- Views: `app/Views/settings/`
- Routes: `/settings/*`
- Features:
  - Organization Profile
  - Fiscal Year
  - Business Hours
  - Currency Management
