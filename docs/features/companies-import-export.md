# Companies Import/Export Feature

## Overview
The Companies Import/Export feature allows you to efficiently manage large numbers of companies by importing them from CSV files and exporting existing companies for backup or data migration purposes.

## Features

### 1. Export Companies
- Export all companies to a CSV file
- Includes all company fields: name, contact person, email, phone, website, company type, address, city, state, zip code, country, status, and notes
- Timestamped filename for easy organization
- Automatic download to your device

### 2. Import Companies
- Import companies from CSV files
- Support for bulk company creation
- Option to update existing companies (match by name)
- Comprehensive error reporting
- Data validation and cleansing

### 3. Template Download
- Download a pre-formatted CSV template
- Includes sample data for reference
- Ensures correct column headers and data format

## How to Use

### Accessing Import/Export Features
1. Navigate to **Customers** → **Companies**
2. Click the **More** dropdown button (next to Add Company)
3. Select from available options:
   - **Export Companies**: Download all companies to CSV
   - **Import Companies**: Upload CSV file to add/update companies
   - **Download Companies Template**: Get CSV template for proper formatting

### Exporting Companies
1. Click **More** → **Export Companies**
2. System generates CSV file with all company data
3. File downloads automatically to your device
4. Filename format: `companies_export_YYYY-MM-DD_HH-MM-SS.csv`

### Importing Companies
1. Click **More** → **Import Companies**
2. Select CSV file from your device
3. Choose import options:
   - **Update existing companies**: Match by name and update information
   - **Skip existing companies**: Only add new companies
4. Click **Import Companies** to process
5. Review import results and any error messages

### CSV Format Requirements

#### Required Fields
- `client_name`: Company name (required, must be unique)

#### Optional Fields
- `contact_person`: Primary contact person
- `email`: Company email address
- `phone`: Phone number
- `website`: Company website URL
- `company_type`: Business type (Customer, Partner, Prospect, etc.)
- `address`: Street address
- `city`: City name
- `state`: State/Province
- `zip_code`: Postal/ZIP code
- `country`: Country (defaults to Canada)
- `status`: active or inactive (defaults to active)
- `notes`: Additional notes about the company

#### Sample CSV Format
```csv
client_name,contact_person,email,phone,website,company_type,address,city,state,zip_code,country,status,notes
ABC Security Systems,John Smith,john@abcsecurity.com,555-0123,https://www.abcsecurity.com,Customer,123 Main Street,Toronto,Ontario,M5V 3A1,Canada,active,Premier security solutions provider
Tech Solutions Inc,Jane Doe,jane@techsolutions.com,555-0789,https://www.techsolutions.com,Partner,456 Business Ave,Vancouver,British Columbia,V6B 2M9,Canada,active,Technology integration specialists
```

## Data Validation

### Automatic Validation
- Company names are checked for uniqueness
- Email addresses must be valid format (if provided)
- Website URLs must be valid format (if provided)
- Status must be either 'active' or 'inactive'
- Empty fields are handled gracefully

### Error Handling
- Detailed error reporting for each row
- Validation errors are reported with specific row numbers
- Import continues even if some rows fail
- Summary report shows total processed, created, updated, and errors

## Import Results

After import completion, you'll receive a summary showing:
- **Total processed**: Number of rows processed
- **Companies created**: Number of new companies added
- **Companies updated**: Number of existing companies updated
- **Errors**: List of any validation or processing errors

## Best Practices

### Before Importing
1. **Download the template** to ensure correct formatting
2. **Backup existing data** by exporting before importing
3. **Validate your data** to ensure company names are unique
4. **Test with small batches** before importing large datasets

### Data Preparation
1. Ensure company names are unique and descriptive
2. Use consistent formatting for addresses and contact information
3. Validate email addresses and website URLs
4. Use standard format for phone numbers
5. Check that all required fields are populated

### After Importing
1. Review the import summary for any errors
2. Check that all companies were imported correctly
3. Verify company information is accurate
4. Update any companies that had import errors

## Troubleshooting

### Common Issues
1. **"CSV file must contain a 'client_name' column"**
   - Ensure your CSV has the correct header row
   - Check that the column is named exactly 'client_name'

2. **"Company already exists"**
   - Use the "Update existing companies" option to modify existing companies
   - Ensure company names are unique in your CSV file

3. **"Failed to create company"**
   - Check that all required fields are populated
   - Verify data format matches expected values
   - Check for special characters that might cause issues

### File Format Requirements
- File must be in CSV format (.csv extension)
- Use comma separators
- Include header row with column names
- Ensure proper encoding (UTF-8 recommended)

## Security Considerations

- Only authorized users can access import/export features
- File uploads are validated for security
- Imported data is subject to the same validation rules as manual entry
- All import activities are logged for audit purposes

---

*Feature available in FSM v2.4.1-alpha and later*
*Last Updated: July 2025*