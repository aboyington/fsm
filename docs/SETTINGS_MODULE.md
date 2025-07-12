# FSM Settings Module Documentation

## Overview
The Settings module provides comprehensive configuration management for the FSM platform, allowing organizations to customize their system according to their business needs.

## Implemented Features

### 1. Organization Profile
Manage core organization information and preferences.

**Features:**
- Company Information (Name, Industry, Website, Phone, Mobile, Fax)
- Location Details (Business Location, Address, City, State, Zip)
- Preferences (Currency, Time Zone, Date Format, Time Format, Distance Unit)
- Business Hours Configuration

**Database Table:** `organizations`

**Access:** Settings → Organization Details

### 2. Fiscal Year Settings
Configure financial year periods for reporting and accounting.

**Features:**
- Calendar Year or Custom Fiscal Year selection
- Custom start and end date configuration
- Year-over-year tracking support

**Database Table:** `fiscal_years`

**Access:** Settings → Organization Details → Fiscal Year Tab

### 3. Business Hours Management
Define operational hours for the organization.

**Features:**
- Pre-configured options: 24x7, 24x5, Custom
- Custom hours per weekday
- Week start day configuration
- Time selection in 30-minute increments

**Database Table:** `business_hours`

**Access:** Settings → Organization Details → Configure (Business Hours)

### 4. Currency Management
Support for multiple currencies and exchange rates.

**Features:**
- Multiple currency support
- Base currency designation
- Exchange rate management
- Number formatting preferences:
  - Thousand separator (comma, period, space, none)
  - Decimal separator (period, comma)
  - Decimal places (0-10)
- ISO code compliance

**Database Table:** `currencies`

**Access:** Settings → Currency

### 5. User Management
Comprehensive user administration and access control.

**Features:**
- User creation with full profile management
- Role-based access control (5 predefined roles)
- Status management (Active, Inactive, Suspended)
- Advanced filtering and search
- Activity timeline and audit trail
- Multi-language support with RTL preparation
- Address management
- Employee ID tracking

**Key Capabilities:**
- Tabbed interface for user editing
- Real-time search and filtering
- Password security with bcrypt hashing
- Session-based authentication
- CSRF protection

**Database Table:** `users`

**Access:** Settings → Workforce → Users

**Documentation:** See [USER_MANAGEMENT.md](USER_MANAGEMENT.md) for detailed documentation

### 6. Territory Management
Geographic territory definition and assignment for field service operations.

**Features:**
- Territory creation and management
- Geographic boundary definition
- Status management (Active/Inactive)
- Address-based territory mapping
- Search and filter capabilities
- Audit trail with creator tracking

**Key Capabilities:**
- Full CRUD operations for territories
- Real-time search and filtering
- Modal-based editing interface
- Status-based filtering
- Address components (Street, City, State, Zip, Country)
- Description field for additional details

**Database Table:** `territories`

**Access:** Settings → Workforce → Territories

**Documentation:** See [TERRITORIES.md](TERRITORIES.md) for detailed documentation

### 7. Skills Management
Comprehensive skill tracking and workforce capability management.

**Features:**
- Custom skill definition and categorization
- Skill level tracking (Beginner, Intermediate, Advanced, Expert)
- Multiple skill categories (Technical, Soft Skills, Certifications, Safety, Specialized)
- Status management (Active/Inactive)
- Search and filtering capabilities
- Audit trail with creator tracking

**Key Capabilities:**
- Full CRUD operations for skills
- Real-time search and filtering
- Modal-based editing interface
- Category-based organization
- Detailed skill descriptions
- Proficiency level tracking

**Database Table:** `skills`

**Access:** Settings → Workforce → Skills

### 8. Holiday Management
Business holiday configuration and calendar management.

**Features:**
- Year-based holiday management (2023-2027)
- Custom holiday definition
- Holiday calendar visualization
- Bulk holiday operations
- Automatic day-of-week calculation
- Multi-year holiday planning

**Key Capabilities:**
- Year-specific holiday configuration
- Flexible holiday editing interface
- Pre-configured common holidays
- Easy holiday addition/removal
- Clear calendar view
- Year navigation support

**Database Table:** `holidays`

**Access:** Settings → Workforce → Holiday

**Documentation:** See [SKILLS_HOLIDAY_MANAGEMENT.md](SKILLS_HOLIDAY_MANAGEMENT.md) for detailed documentation

## Technical Implementation

### Models
- `OrganizationModel` - Manages organization data
- `FiscalYearModel` - Handles fiscal year configurations
- `BusinessHoursModel` - Manages business hours settings
- `CurrencyModel` - Handles currency data and validations
- `TerritoryModel` - Manages territory data and geographic assignments
- `UserModel` - Handles user authentication and profile management
- `AuditLogModel` - Tracks user activities and system events
- `SkillModel` - Manages skill definitions and categorization
- `HolidayModel` - Handles holiday configuration and year-based management

### Controllers
- `Settings` - Main settings controller handling all settings routes
- `CurrencyController` - Dedicated controller for currency operations (integrated with Settings)

### Database Migrations
1. `2025-01-11-000001_CreateOrganizationsTable.php`
2. `2025-01-11-000002_CreateFiscalYearsTable.php`
3. `2025-01-11-000004_CreateBusinessHoursTable.php`
4. `2025-01-11-000005_CreateCurrenciesTable.php`
5. `2025-01-11-000003_CreateTerritoriesTable.php`
6. `2025-01-10-120000_create_users_table.php`
7. `2025-01-10-000001_create_audit_logs_table.php`
8. `2025-01-11-000006_CreateSkillsTable.php`
9. `2025-01-11-000007_CreateHolidaysTable.php`

### API Endpoints

#### Organization
- `POST /settings/organization/update` - Update organization details
- `POST /settings/business-hours/update` - Update business hours

#### Currency
- `GET /settings/currency` - List all currencies
- `POST /settings/currency/store` - Add new currency
- `GET /settings/currency/get/{id}` - Get currency details
- `POST /settings/currency/update/{id}` - Update currency

#### Territories
- `GET /settings/territories` - List territories with filtering
- `POST /settings/territories/add` - Create new territory
- `GET /settings/territories/get/{id}` - Get territory details
- `POST /settings/territories/update/{id}` - Update territory
- `POST /settings/territories/delete/{id}` - Delete territory

#### Users
- `GET /settings/users` - List users with filtering
- `POST /settings/addUser` - Create new user
- `GET /settings/getUser/{id}` - Get user details
- `POST /settings/updateUser` - Update user information
- `GET /settings/getUserTimeline/{userId}` - Get user activity timeline

#### Skills
- `GET /settings/skills` - List all skills with filtering
- `POST /settings/skills/add` - Create new skill
- `GET /settings/skills/get/{id}` - Get skill details
- `POST /settings/skills/update/{id}` - Update skill
- `POST /settings/skills/delete/{id}` - Delete skill

#### Holidays
- `GET /settings/holiday` - Display holiday management page
- `POST /settings/holiday/save` - Save holidays for a specific year
- `GET /settings/holiday/get/{year}` - Get holidays for a specific year
- `POST /settings/holiday/update/{year}` - Update holidays for a specific year
- `POST /settings/holiday/delete/{id}` - Delete a specific holiday

### Security
- All endpoints require authentication
- Session-based authentication for web interface
- CSRF protection on all forms
- Input validation and sanitization

## Usage Examples

### Adding a New Currency
1. Navigate to Settings → Currency
2. Click "Add Currency" button
3. Fill in:
   - Currency Name (e.g., "US Dollar - USD")
   - Symbol (e.g., "$")
   - ISO Code (e.g., "USD")
   - Exchange Rate (relative to base currency)
   - Number formatting preferences
4. Click "Add Currency"

### Configuring Business Hours
1. Navigate to Settings → Organization Details
2. Click "Configure" next to Business Hours Details
3. Select:
   - 24 Hours X 7 days
   - 24 Hours X 5 days
   - Custom (specify hours for each day)
4. Save changes

### Setting Fiscal Year
1. Navigate to Settings → Organization Details → Fiscal Year tab
2. Select Calendar Year or Custom
3. If Custom, set start and end dates
4. Save configuration

### Managing Territories
1. Navigate to Settings → Workforce → Territories
2. To add a new territory:
   - Click "New Territory" button
   - Enter territory name (required)
   - Fill in address details (optional):
     - Street, City, State, Zip Code, Country
   - Add description if needed
   - Select status (Active/Inactive)
   - Click "Save Territory"
3. To edit a territory:
   - Click the pencil icon in the Actions column
   - Update information in the modal
   - Click "Update Territory"
4. To delete a territory:
   - Click the trash icon in the Actions column
   - Confirm deletion
5. Use filters to view:
   - Active Territories
   - Inactive Territories
   - All Territories
6. Use search to find specific territories

## Default Configuration

### Base Currency
- Canadian Dollar (CAD) is set as the default base currency
- Exchange rate: 1.0000000000
- Format: CA$ 1,234.56

### Business Hours
- Default: 24 Hours X 7 days

### Organization
- Default values prompt user to complete setup

## Future Enhancements
1. Currency conversion utilities
2. Historical exchange rates
3. Holiday calendar integration
4. Multiple business locations support
5. Industry-specific presets
6. Import/Export settings configurations
