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

## Technical Implementation

### Models
- `OrganizationModel` - Manages organization data
- `FiscalYearModel` - Handles fiscal year configurations
- `BusinessHoursModel` - Manages business hours settings
- `CurrencyModel` - Handles currency data and validations

### Controllers
- `Settings` - Main settings controller handling all settings routes
- `CurrencyController` - Dedicated controller for currency operations (integrated with Settings)

### Database Migrations
1. `2025-01-11-000001_CreateOrganizationsTable.php`
2. `2025-01-11-000002_CreateFiscalYearsTable.php`
3. `2025-01-11-000004_CreateBusinessHoursTable.php`
4. `2025-01-11-000005_CreateCurrenciesTable.php`

### API Endpoints

#### Organization
- `POST /settings/organization/update` - Update organization details
- `POST /settings/business-hours/update` - Update business hours

#### Currency
- `GET /settings/currency` - List all currencies
- `POST /settings/currency/store` - Add new currency
- `GET /settings/currency/get/{id}` - Get currency details
- `POST /settings/currency/update/{id}` - Update currency

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
