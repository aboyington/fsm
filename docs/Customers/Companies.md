# Companies Management

The Companies module allows you to manage business customers and their information within the FSM platform. Companies serve as the primary business entities that can have multiple contacts, assets, and service locations.

## Company Data Structure

### Core Information
- **Company Name**: Primary business name
- **Legal Name**: Official legal business name
- **Company Code**: Unique identifier for internal reference
- **Business Type**: Type of business entity
- **Industry**: Industry classification
- **Website**: Company website URL
- **Description**: Company description and notes

### Contact Information
- **Primary Email**: Main business email
- **Primary Phone**: Main business phone number
- **Secondary Phone**: Alternative contact number
- **Fax**: Fax number (if applicable)

### Financial Information
- **Tax ID**: Tax identification number
- **Currency**: Default currency for transactions
- **Payment Terms**: Standard payment terms
- **Credit Limit**: Approved credit limit
- **Taxable Status**: Tax exemption status
- **Tax Rate**: Applicable tax rate

### Address Management
- **Billing Address**: Primary billing location
- **Shipping Address**: Default shipping location
- **Multiple Service Addresses**: Various service locations

### Business Details
- **Established Date**: When the business was established
- **Employee Count**: Number of employees
- **Annual Revenue**: Estimated annual revenue
- **Territory**: Assigned service territory
- **Status**: Active/Inactive status
- **Tags**: Custom tags for categorization

## Adding Companies

Companies can be added in the following ways:

1. **Manual Entry**: Add companies individually through the Companies module
2. **Data Import**: Import companies from external sources (CSV, Excel)
3. **API Integration**: Sync companies from external systems
4. **Mobile App**: Create companies using the mobile application

### To add a company manually:

**Permission Required**: Companies module access with Create permission

1. Navigate to **Customers** → **Companies** and click **Create**
2. Enter the required **Company Name**
3. Fill in the core business information:
   - Legal name and business type
   - Industry classification
   - Contact information (email, phone)
4. Configure financial settings:
   - Select **Currency** for transactions
   - Set **Payment Terms** and **Credit Limit**
   - Configure **Taxable** status:
     - **Taxable**: Select appropriate tax rate
     - **Non-Taxable**: Choose exemption reason
5. Add address information:
   - Billing address (required)
   - Shipping address (if different)
   - Service locations as needed
6. Set additional details:
   - Assign to **Territory**
   - Add relevant **Tags**
   - Set company **Status**
7. Click **Save** to create the company

## Company Address Management

### Adding Addresses
Companies can have multiple addresses for different purposes:
- **Billing Address**: For invoicing
- **Shipping Address**: For parts and equipment delivery
- **Service Addresses**: Multiple locations where services are provided

### Address Types
1. **Primary Billing**: Main billing location
2. **Primary Shipping**: Default delivery location
3. **Service Location**: Various service sites
4. **Branch Office**: Additional company locations

### Managing Addresses
1. From the company details page, select the **Addresses** tab
2. Click **Add Address** to create new addresses
3. Specify address type and complete location details
4. Use the geocoding feature to set accurate coordinates
5. Set default addresses for billing and shipping

### Deleting Company Addresses

**Note**: Deleting an address will not affect existing records (work orders, service appointments, etc.) where this address is used.

**Permission Required**: Delete permission for Companies module

1. Navigate to **Companies** and select the company
2. Go to the **Addresses** tab
3. Hover over the address and click the **Delete** icon
4. Confirm deletion in the popup message

## Company Relationships

### Linked Contacts
- Companies can have multiple associated contacts
- Each contact represents a person within the organization
- Contacts inherit company settings unless overridden

### Associated Assets
- Track equipment and assets owned by the company
- Link assets to specific company locations
- Maintain service history per asset

### Territory Assignment
- Companies are assigned to service territories
- Territory determines service availability and routing
- Multiple territories can service a single company with multiple locations

## Company Status Management

### Status Types
- **Active**: Company is actively serviced
- **Inactive**: Company is not currently active
- **Prospect**: Potential customer
- **Lead**: Sales lead requiring follow-up

### Status Workflow
1. **Lead** → **Prospect** → **Active**
2. **Active** ↔ **Inactive** (as needed)

## Mobile App Usage

### Creating Companies on Mobile
1. Open the FSM mobile app
2. Navigate to **Companies** in the main menu
3. Tap the **+** (add) icon
4. Complete the company information form
5. Use device GPS for accurate address geocoding
6. Save the company record

### Editing Companies on Mobile
1. Select the company from the list
2. Tap the **Edit** icon
3. Make necessary changes
4. Save the updates

## Search and Filtering

### Available Filters
- **Company Name**: Search by name or partial name
- **Industry**: Filter by business industry
- **Territory**: Filter by assigned territory
- **Status**: Filter by company status
- **Tags**: Filter by assigned tags
- **Date Range**: Filter by creation or last modified date

### Advanced Search Options
- **Multiple Criteria**: Combine multiple filters
- **Saved Searches**: Save frequently used search criteria
- **Quick Filters**: Predefined common filters

## Integration Features

### Billing Integration
- Automatic sync with billing systems
- Invoice generation using company details
- Payment tracking and credit management

### Work Order Integration
- Companies appear in work order customer selection
- Service history accessible from company profile
- Automatic territory and contact population

### Reporting Integration
- Company performance metrics
- Revenue tracking per company
- Service frequency analysis

## Best Practices

### Data Entry
1. **Consistent Naming**: Use standardized company name formats
2. **Complete Profiles**: Fill all relevant fields for better service
3. **Regular Updates**: Keep contact information current
4. **Proper Categorization**: Use appropriate industry and tags

### Address Management
1. **Accurate Geocoding**: Ensure precise location coordinates
2. **Address Standardization**: Use consistent address formats
3. **Service Location Planning**: Plan service addresses strategically

### Territory Assignment
1. **Geographic Logic**: Assign based on service area coverage
2. **Workload Balance**: Distribute companies evenly across territories
3. **Special Requirements**: Consider company-specific service needs

## Security and Permissions

### Access Control
- **View**: See company information
- **Create**: Add new companies
- **Edit**: Modify existing company data
- **Delete**: Remove company records
- **Export**: Export company data

### Data Protection
- Company information is encrypted at rest
- Audit trail tracks all changes
- GDPR compliance for data handling

---

*Last Updated*: January 2025  
*Version*: 2.0  
*Module*: Companies Management
