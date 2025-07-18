# FSM Database Schema Documentation

## Overview

This document provides a comprehensive overview of the Field Service Management (FSM) platform database schema, focusing on the customer management modules (Companies, Contacts, and Assets).

## Database Configuration

- **Database Type**: SQLite
- **Location**: `/writable/database/fsm.db`
- **WAL Mode**: Enabled for better concurrent access
- **Migration System**: CodeIgniter 4 migrations

## Customer Management Schema

### Companies Table

The companies table stores business customer information and organizational data.

```sql
CREATE TABLE companies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    
    -- Core Information
    company_name VARCHAR(255) NOT NULL,
    legal_name VARCHAR(255),
    company_code VARCHAR(50),
    business_type VARCHAR(100),
    industry VARCHAR(100),
    website VARCHAR(255),
    description TEXT,
    
    -- Contact Information
    primary_email VARCHAR(255),
    primary_phone VARCHAR(20),
    secondary_phone VARCHAR(20),
    fax VARCHAR(20),
    
    -- Financial Information
    tax_id VARCHAR(50),
    currency_id INTEGER,
    payment_terms VARCHAR(100),
    credit_limit DECIMAL(12,2),
    is_taxable BOOLEAN DEFAULT 1,
    tax_rate DECIMAL(5,2),
    
    -- Address Information
    billing_address_id INTEGER,
    shipping_address_id INTEGER,
    
    -- Business Details
    established_date DATE,
    employee_count INTEGER,
    annual_revenue DECIMAL(15,2),
    territory_id INTEGER,
    status VARCHAR(50) DEFAULT 'active',
    tags VARCHAR(255),
    
    -- System Fields
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER,
    updated_by INTEGER,
    
    -- Foreign Key Constraints
    FOREIGN KEY (currency_id) REFERENCES currencies(id),
    FOREIGN KEY (territory_id) REFERENCES territories(id),
    FOREIGN KEY (billing_address_id) REFERENCES addresses(id),
    FOREIGN KEY (shipping_address_id) REFERENCES addresses(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);
```

**Indexes:**
- `idx_companies_name` on `company_name`
- `idx_companies_industry` on `industry`
- `idx_companies_territory` on `territory_id`
- `idx_companies_status` on `status`

### Contacts Table

The contacts table manages individual people within customer organizations.

```sql
CREATE TABLE contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    
    -- Personal Information
    first_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    title VARCHAR(100),
    department VARCHAR(100),
    salutation VARCHAR(20),
    suffix VARCHAR(20),
    
    -- Contact Information
    primary_email VARCHAR(255) NOT NULL UNIQUE,
    secondary_email VARCHAR(255),
    primary_phone VARCHAR(20),
    secondary_phone VARCHAR(20),
    mobile_phone VARCHAR(20),
    fax VARCHAR(20),
    preferred_contact_method VARCHAR(20) DEFAULT 'email',
    
    -- Professional Information
    company_id INTEGER,
    job_title VARCHAR(100),
    manager_id INTEGER,
    assistant_id INTEGER,
    direct_reports INTEGER DEFAULT 0,
    
    -- Account Information
    account_number VARCHAR(50),
    
    -- Address Information
    primary_address_id INTEGER,
    billing_address_id INTEGER,
    mailing_address_id INTEGER,
    
    -- Additional Details
    date_of_birth DATE,
    anniversary DATE,
    lead_source VARCHAR(100),
    social_linkedin VARCHAR(255),
    social_twitter VARCHAR(255),
    notes TEXT,
    tags VARCHAR(255),
    language VARCHAR(10) DEFAULT 'en',
    timezone VARCHAR(50),
    
    -- Financial Information
    currency_id INTEGER,
    payment_terms VARCHAR(100),
    credit_limit DECIMAL(12,2),
    is_taxable BOOLEAN DEFAULT 1,
    
    -- System Fields
    status VARCHAR(50) DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER,
    updated_by INTEGER,
    
    -- Foreign Key Constraints
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (manager_id) REFERENCES contacts(id),
    FOREIGN KEY (assistant_id) REFERENCES contacts(id),
    FOREIGN KEY (currency_id) REFERENCES currencies(id),
    FOREIGN KEY (primary_address_id) REFERENCES addresses(id),
    FOREIGN KEY (billing_address_id) REFERENCES addresses(id),
    FOREIGN KEY (mailing_address_id) REFERENCES addresses(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);
```

**Indexes:**
- `idx_contacts_email` on `primary_email`
- `idx_contacts_name` on `last_name, first_name`
- `idx_contacts_company` on `company_id`
- `idx_contacts_status` on `status`
- `idx_contacts_account_number` on `account_number`

### Assets Table

The assets table provides comprehensive tracking of customer-owned equipment and devices.

```sql
CREATE TABLE assets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    
    -- Core Asset Information
    asset_name VARCHAR(255) NOT NULL,
    asset_number VARCHAR(100),
    asset_code VARCHAR(50),
    serial_number VARCHAR(100),
    model_number VARCHAR(100),
    manufacturer VARCHAR(100),
    vendor VARCHAR(100),
    part_number VARCHAR(100),
    description TEXT,
    
    -- Location and Assignment
    location VARCHAR(255),
    department VARCHAR(100),
    contact_id INTEGER,
    company_id INTEGER,
    territory_id INTEGER,
    site_address_id INTEGER,
    parent_asset_id INTEGER,
    
    -- Financial Information
    cost DECIMAL(12,2),
    book_value DECIMAL(12,2),
    depreciation_method VARCHAR(50),
    useful_life INTEGER COMMENT 'Useful life in months',
    purchase_date DATE,
    installation_date DATE,
    warranty_expiration DATE,
    currency_id INTEGER,
    
    -- Technical Specifications
    condition VARCHAR(50),
    status VARCHAR(50) DEFAULT 'active',
    mac_address VARCHAR(17),
    ip_address VARCHAR(45),
    operating_system VARCHAR(100),
    software_licenses TEXT,
    technical_specs TEXT,
    
    -- Maintenance Information
    maintenance_schedule VARCHAR(100),
    last_maintenance DATE,
    next_maintenance DATE,
    maintenance_notes TEXT,
    service_provider VARCHAR(100),
    
    -- Lifecycle Management
    commissioned_date DATE,
    retired_date DATE,
    disposal_date DATE,
    disposal_method VARCHAR(100),
    
    -- Custom Fields
    tags VARCHAR(255),
    notes TEXT,
    
    -- System Fields
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER,
    updated_by INTEGER,
    
    -- Foreign Key Constraints
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (territory_id) REFERENCES territories(id),
    FOREIGN KEY (parent_asset_id) REFERENCES assets(id),
    FOREIGN KEY (site_address_id) REFERENCES addresses(id),
    FOREIGN KEY (currency_id) REFERENCES currencies(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);
```

**Indexes:**
- `idx_assets_serial` on `serial_number`
- `idx_assets_model` on `model_number`
- `idx_assets_manufacturer` on `manufacturer`
- `idx_assets_location` on `location`
- `idx_assets_company` on `company_id`
- `idx_assets_territory` on `territory_id`
- `idx_assets_status` on `status`

## Supporting Tables

### Addresses Table

The addresses table stores location information for companies, contacts, and assets.

```sql
CREATE TABLE addresses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    
    -- Address Components
    address_line_1 VARCHAR(255),
    address_line_2 VARCHAR(255),
    city VARCHAR(100),
    state_province VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100),
    
    -- Geographic Coordinates
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    
    -- Address Metadata
    address_type VARCHAR(50), -- billing, shipping, service, mailing
    is_primary BOOLEAN DEFAULT 0,
    is_verified BOOLEAN DEFAULT 0,
    
    -- System Fields
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Currencies Table

The currencies table manages multi-currency support for international operations.

```sql
CREATE TABLE currencies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    
    -- Currency Information
    currency_code VARCHAR(3) NOT NULL UNIQUE,
    currency_name VARCHAR(100) NOT NULL,
    currency_symbol VARCHAR(10),
    
    -- Exchange Rate Information
    exchange_rate DECIMAL(10,6) DEFAULT 1.0,
    is_base_currency BOOLEAN DEFAULT 0,
    
    -- Formatting
    decimal_places INTEGER DEFAULT 2,
    thousand_separator VARCHAR(1) DEFAULT ',',
    decimal_separator VARCHAR(1) DEFAULT '.',
    
    -- Status
    is_active BOOLEAN DEFAULT 1,
    
    -- System Fields
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Territories Table

The territories table manages geographic service areas for optimal resource allocation.

```sql
CREATE TABLE territories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    
    -- Territory Information
    territory_name VARCHAR(255) NOT NULL,
    territory_code VARCHAR(50),
    description TEXT,
    
    -- Geographic Boundaries
    boundary_coordinates TEXT, -- JSON format for polygon coordinates
    
    -- Assignment
    manager_id INTEGER,
    
    -- Status
    status VARCHAR(50) DEFAULT 'active',
    
    -- System Fields
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Key Constraints
    FOREIGN KEY (manager_id) REFERENCES users(id)
);
```

## Relationship Mapping

### Company-Contact Relationships
- **One-to-Many**: One company can have multiple contacts
- **Inheritance**: Contacts inherit company settings (currency, territory, tax settings)
- **Override**: Individual contacts can override inherited settings

### Company-Asset Relationships
- **One-to-Many**: One company can own multiple assets
- **Location Tracking**: Assets can be located at different company addresses
- **Service History**: All service activities are tracked per asset

### Contact-Asset Relationships
- **Assignment**: Contacts can be assigned as asset owners or operators
- **Communication**: Contacts receive asset-related notifications and updates

### Asset Hierarchies
- **Parent-Child**: Assets can have hierarchical relationships
- **Components**: Track sub-assemblies and components within larger systems
- **Dependencies**: Understand asset interdependencies for maintenance planning

## Data Migration Scripts

### Initial Companies Migration
```php
// Migration: 2025-07-14-040312_AddMissingFieldsToCompanies.php
public function up()
{
    $this->forge->addColumn('companies', [
        'legal_name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        'company_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        // ... additional fields
    ]);
}
```

### Initial Contacts Migration
```php
// Migration: 2025-07-14-040312_AddMissingFieldsToContacts.php
public function up()
{
    $this->forge->addColumn('contacts', [
        'first_name' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        'title' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        // ... additional fields
    ]);
}
```

### Initial Assets Migration
```php
// Migration: 2025-07-14-040313_AddMissingFieldsToAssets.php
public function up()
{
    $this->forge->addColumn('assets', [
        'serial_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        'model_number' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        // ... additional fields
    ]);
}
```

## Performance Considerations

### Indexing Strategy
1. **Primary Keys**: Auto-increment integers for optimal performance
2. **Foreign Keys**: Indexed for efficient joins
3. **Search Fields**: Common search fields are indexed
4. **Composite Indexes**: Multi-column indexes for complex queries

### Query Optimization
1. **Pagination**: Large result sets use pagination
2. **Selective Loading**: Load only required fields
3. **Eager Loading**: Use joins to prevent N+1 queries
4. **Caching**: Implement caching for frequently accessed data

### Data Integrity
1. **Foreign Key Constraints**: Maintain referential integrity
2. **Validation**: Application-level validation before database operations
3. **Transactions**: Use transactions for multi-table operations
4. **Backup Strategy**: Regular database backups with point-in-time recovery

## Security Measures

### Data Protection
1. **Encryption**: Sensitive data encrypted at rest
2. **Access Control**: Role-based access to customer data
3. **Audit Trail**: All changes logged with user attribution
4. **Data Masking**: Sensitive data masked in non-production environments

### Compliance
1. **GDPR**: Support for data portability and right to deletion
2. **Data Retention**: Configurable data retention policies
3. **Privacy**: Personal data handling in compliance with regulations
4. **Consent Management**: Track customer consent for data usage

---

*Last Updated*: January 2025  
*Version*: 2.0  
*Database Schema*: FSM Customer Management

