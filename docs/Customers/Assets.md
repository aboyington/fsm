# Assets Management

The Assets module provides comprehensive tracking and management of customer-owned equipment, products, and devices. Assets represent physical items that require ongoing service, maintenance, or support within the FSM platform. This module enables complete lifecycle management from installation to disposal.

## Asset Data Structure

### Core Asset Information
- **Asset Name**: Descriptive name for the asset
- **Asset Number**: Unique identifier for tracking
- **Asset Code**: Internal reference code
- **Serial Number**: Manufacturer's serial number
- **Model Number**: Product model designation
- **Manufacturer**: Equipment manufacturer
- **Vendor**: Supplier or vendor information
- **Part Number**: Related part or product number
- **Description**: Detailed asset description

### Location and Assignment
- **Location**: Physical location of the asset
- **Department**: Organizational department
- **Contact**: Primary contact responsible for the asset
- **Company**: Owning company or organization
- **Territory**: Service territory assignment
- **Site Address**: Specific installation address
- **Parent Asset**: Hierarchical asset relationships

### Financial Information
- **Cost**: Original purchase cost
- **Book Value**: Current book value
- **Depreciation Method**: Depreciation calculation method
- **Useful Life**: Expected useful life in months
- **Purchase Date**: Date of purchase
- **Installation Date**: Date of installation
- **Warranty Expiration**: Warranty end date
- **Currency**: Transaction currency

### Technical Specifications
- **Condition**: Current asset condition
- **Status**: Operational status
- **MAC Address**: Network MAC address (for networked devices)
- **IP Address**: Network IP address
- **Operating System**: Installed operating system
- **Software Licenses**: Associated software licenses
- **Technical Specifications**: Detailed technical information

### Maintenance Information
- **Maintenance Schedule**: Regular maintenance frequency
- **Last Maintenance**: Date of last service
- **Next Maintenance**: Scheduled next maintenance
- **Maintenance Notes**: Service history notes
- **Service Provider**: Preferred service provider

### Lifecycle Management
- **Asset Status**: Active, Inactive, Retired, Disposed
- **Installation Date**: When asset was installed
- **Commissioned Date**: When asset became operational
- **Retired Date**: When asset was retired from service
- **Disposal Date**: When asset was disposed
- **Disposal Method**: How asset was disposed

### Custom Fields
- **Tags**: Custom categorization tags
- **Custom Fields**: Organization-specific data fields
- **Notes**: Internal notes and comments
- **Attachments**: Related documents and files

## Adding Assets

Assets can be added through multiple methods:

1. **Manual Entry**: Create assets individually through the Assets module
2. **Bulk Import**: Import assets from CSV or Excel files
3. **API Integration**: Sync assets from external systems
4. **Mobile App**: Create assets using the mobile application
5. **Work Order Creation**: Add assets during service delivery

### To add an asset manually:

**Permission Required**: Assets module access with Create permission

1. Navigate to **Customers** → **Assets** and click **Create**
2. Enter the required **Asset Name**
3. Provide **Asset Number** or allow system to auto-generate
4. Complete core information:
   - Serial number and model number
   - Manufacturer and vendor details
   - Asset description and specifications
5. Set location and assignment:
   - Select owning **Company** and primary **Contact**
   - Specify **Location** and **Department**
   - Assign to appropriate **Territory**
6. Configure financial details:
   - **Cost** and current **Book Value**
   - **Depreciation Method** and **Useful Life**
   - **Purchase Date** and **Installation Date**
   - **Warranty Expiration** date
7. Add technical information:
   - **Condition** and **Status**
   - Network details (MAC/IP addresses)
   - Operating system and software licenses
8. Set maintenance information:
   - **Maintenance Schedule** and service provider
   - **Last Maintenance** and **Next Maintenance** dates
9. Configure lifecycle details:
   - Current **Asset Status**
   - Important dates (commissioned, retired, disposed)
10. Add custom information:
    - Relevant **Tags** for categorization
    - Internal **Notes** and documentation
11. Click **Save** to create the asset

### Asset Hierarchies
Assets can be organized in hierarchical relationships:
- **Parent Assets**: Main equipment or systems
- **Child Assets**: Components or sub-assemblies
- **Related Assets**: Assets that work together
- **Replacement Assets**: Assets that replace others

## Related Information

Child Assets, Requests, Estimates, Work Orders, and Service Appointments can be created for an asset. These details can be found under the **Related List** tab of the _Assets View_ page.  

  

![](https://help.zoho.com/galleryDocuments/edbsn321647da20dcea178c43348ccafba84ee46c06c1408e000ed9f91d64fd0269161382f96b037e477eb3611a8dd436327b?inline=true)  

## Add Assets from Mobile App

To create an asset:  

1. Select **Assets**, in the left menu.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn6f943ee63b1eb88a731a5bf6da8331e81918423b02ad8590b95e97f34bb67b16013a170b82d838cc46d7ca385d31f532?inline=true)  
      
    
2. In the _Assets_ screen, tap the add [+] icon.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnbc8088d9ae0678d5a79b5ed1ce838c74ea92316661d056155c0d0cf643c49b94fd22e25ff30298d092fccc2560921def?inline=true)  
      
    
3. In the _Create Asset_ page, enter the necessary details and click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsn8eb2209f6cf1b303c268894ca22f8913bfca37ec7e900e5148f468177a60ea0a7d2f8be111f60f2071c5715d57392578?inline=true)  
      
    
4. In the lookup fields, you can do an [advanced search](https://help.zoho.com/portal/en/kb/fsm/mobilize-your-workforce/articles/fsm-mobile-app#Advanced_Lookup_Search).      
5. In the address fields, you can [add](https://help.zoho.com/portal/en/kb/fsm/mobilize-your-workforce/articles/fsm-mobile-app#Manage_Work_Order_Addresses) addresses.      
6. The Currency and Exchange Rate will be displayed only if [multiple currencies](https://help.zoho.com/portal/en/kb/fsm/getting-started/articles/organization-setting#Manage_multiple_currencies) are enabled.

The created asset can be edited. To edit an asset:  

1. Click the **Edit** [![](https://help.zoho.com/galleryDocuments/edbsnbd45e7270671b8f024b030beddfb4246cf0a9e159777e0dc3a82e6d517ce6c923d6fb7e7c5a64b808c10e322bd761422?inline=true)] icon on the top right side.      
2. Make the necessary changes and click **Save**.  
      
    ![](https://help.zoho.com/galleryDocuments/edbsnb4f0c6b4c7e0c30d46fe78611a07da475d3ee25c74a8788f3852692fc32cbc21d13991e3f13f923cde72bcd371759e32?inline=true)    ![](https://help.zoho.com/galleryDocuments/edbsn5af4772493c8bb39becc87272a7d78035630bfd818c9cd1978b0e66423e7117fd1ec80775b5d1b12e69a65fa842db006?inline=true) 

You can also add notes, and view the related records.

## Asset Status Management

### Asset Status Types
- **Active**: Asset is currently in service and operational
- **Inactive**: Asset is temporarily out of service
- **Under Maintenance**: Asset is currently being serviced
- **Retired**: Asset is no longer in active use
- **Disposed**: Asset has been permanently removed
- **Lost/Stolen**: Asset is missing or stolen
- **Damaged**: Asset is damaged and requires repair

### Asset Condition Categories
- **Excellent**: Asset is in like-new condition
- **Good**: Asset shows minimal wear and functions properly
- **Fair**: Asset shows some wear but remains functional
- **Poor**: Asset shows significant wear and may have issues
- **Critical**: Asset requires immediate attention
- **Non-Functional**: Asset is not working and needs repair

### Lifecycle Workflows
1. **Installation** → **Active** → **Maintenance** → **Active**
2. **Active** → **Retired** → **Disposed**
3. **Active** → **Damaged** → **Under Maintenance** → **Active**

## Asset Categories and Types

### Equipment Categories
- **IT Equipment**: Computers, servers, networking devices
- **Industrial Equipment**: Manufacturing machinery, tools
- **HVAC Systems**: Heating, ventilation, air conditioning
- **Security Systems**: Cameras, access control, alarms
- **Vehicles**: Company vehicles, service trucks
- **Furniture**: Office furniture, fixtures
- **Medical Equipment**: Healthcare devices and instruments
- **Communication**: Phone systems, radios, intercoms

### Asset Classifications
- **Critical Assets**: Mission-critical equipment requiring priority service
- **Standard Assets**: Regular equipment with standard service levels
- **Non-Critical Assets**: Equipment with flexible service requirements
- **Consumable Assets**: Items that are consumed or replaced regularly

## Maintenance Management

### Maintenance Types
- **Preventive Maintenance**: Scheduled regular maintenance
- **Corrective Maintenance**: Repair work to fix issues
- **Emergency Maintenance**: Urgent repairs for critical failures
- **Predictive Maintenance**: Data-driven maintenance scheduling
- **Condition-Based Maintenance**: Maintenance based on asset condition

### Maintenance Scheduling
- **Time-Based**: Maintenance scheduled by time intervals
- **Usage-Based**: Maintenance based on usage metrics
- **Condition-Based**: Maintenance triggered by condition monitoring
- **Calendar-Based**: Maintenance scheduled on specific dates

### Maintenance Records
- **Service History**: Complete maintenance history
- **Parts Used**: Track parts and materials consumed
- **Labor Hours**: Record time spent on maintenance
- **Cost Tracking**: Monitor maintenance costs
- **Performance Metrics**: Track asset performance improvements

## Asset Tracking and Monitoring

### Location Tracking
- **GPS Coordinates**: Precise asset location
- **Site Address**: Physical address of asset location
- **Building/Room**: Specific location within facilities
- **Move History**: Track asset relocations

### Performance Monitoring
- **Uptime Tracking**: Monitor asset availability
- **Performance Metrics**: Track operational efficiency
- **Utilization Rates**: Monitor asset usage patterns
- **Failure Analysis**: Analyze failure patterns and causes

### Asset Documentation
- **User Manuals**: Installation and operation guides
- **Technical Specifications**: Detailed technical information
- **Warranty Documents**: Warranty terms and conditions
- **Service Records**: Complete service history
- **Photos**: Visual documentation of asset condition

## Work Order Integration

### Service Request Creation
- **Automatic Work Orders**: Generate work orders from maintenance schedules
- **On-Demand Service**: Create service requests as needed
- **Emergency Requests**: Priority handling for critical assets
- **Warranty Claims**: Track warranty-covered services

### Service History
- **Complete Service Log**: All work performed on the asset
- **Technician Notes**: Detailed service documentation
- **Parts Replacement**: Track replaced components
- **Service Outcomes**: Document service results

### Performance Impact
- **Before/After Metrics**: Compare performance pre and post service
- **Downtime Tracking**: Monitor service-related downtime
- **Cost Analysis**: Analyze service costs vs. asset value
- **Reliability Improvements**: Track reliability gains from service

## Financial Management

### Asset Valuation
- **Purchase Cost**: Original acquisition cost
- **Current Book Value**: Depreciated value
- **Market Value**: Current market valuation
- **Replacement Cost**: Cost to replace with equivalent asset

### Depreciation Tracking
- **Straight-Line Depreciation**: Equal depreciation over useful life
- **Accelerated Depreciation**: Higher depreciation in early years
- **Custom Depreciation**: Organization-specific methods
- **Tax Depreciation**: Depreciation for tax purposes

### Cost Management
- **Total Cost of Ownership**: Complete lifecycle costs
- **Maintenance Costs**: Ongoing service and repair costs
- **Operating Costs**: Energy and operational expenses
- **Disposal Costs**: End-of-life disposal expenses

## Reporting and Analytics

### Asset Reports
- **Asset Inventory**: Complete asset listings
- **Asset Utilization**: Usage and performance reports
- **Maintenance Reports**: Service history and scheduling
- **Financial Reports**: Cost and depreciation analysis
- **Compliance Reports**: Regulatory and warranty compliance

### Key Performance Indicators
- **Asset Availability**: Percentage of time assets are operational
- **Mean Time Between Failures**: Reliability metrics
- **Maintenance Costs per Asset**: Cost efficiency analysis
- **Asset Lifecycle Value**: ROI and value analysis

### Predictive Analytics
- **Failure Prediction**: Predict when assets may fail
- **Maintenance Optimization**: Optimize maintenance schedules
- **Cost Forecasting**: Predict future maintenance costs
- **Replacement Planning**: Plan asset replacements

## Search and Filtering

### Basic Search Options
- **Asset Name/Number**: Search by identifier
- **Serial Number**: Find by manufacturer serial
- **Location**: Search by asset location
- **Company/Contact**: Find assets by owner

### Advanced Filtering
- **Multiple Criteria**: Combine search parameters
- **Status Filters**: Filter by asset status or condition
- **Date Ranges**: Filter by purchase, installation, or service dates
- **Financial Filters**: Filter by cost ranges or depreciation
- **Category Filters**: Filter by asset type or classification
- **Custom Field Filters**: Search using organization-specific fields

### Asset Lists and Views
- **Grid View**: Tabular asset listings
- **Map View**: Geographic asset distribution
- **Timeline View**: Asset lifecycle visualization
- **Hierarchy View**: Parent-child asset relationships

## Integration Features

### ERP Integration
- **Asset Sync**: Synchronize with enterprise resource planning systems
- **Financial Data**: Sync cost and depreciation information
- **Purchase Orders**: Link to procurement systems
- **Inventory Management**: Integrate with parts inventory

### IoT Integration
- **Sensor Data**: Collect real-time asset performance data
- **Condition Monitoring**: Automated condition assessment
- **Predictive Maintenance**: Data-driven maintenance triggers
- **Remote Monitoring**: Monitor assets from central locations

### Mobile App Features
- **Barcode/QR Scanning**: Quick asset identification
- **Photo Documentation**: Capture asset condition photos
- **GPS Location**: Update asset locations automatically
- **Offline Access**: Access asset information without connectivity

## Security and Compliance

### Data Security
- **Encryption**: Asset data encrypted at rest and in transit
- **Access Control**: Role-based access to asset information
- **Audit Trail**: Complete activity logging
- **Data Backup**: Regular backup of asset data

### Regulatory Compliance
- **Industry Standards**: Compliance with industry regulations
- **Safety Requirements**: Track safety-related asset information
- **Environmental Compliance**: Monitor environmental impact
- **Warranty Compliance**: Ensure warranty requirements are met

### Asset Security
- **Asset Tagging**: Physical identification tags
- **Theft Prevention**: Track and monitor valuable assets
- **Access Control**: Control physical access to assets
- **Insurance Tracking**: Maintain insurance information

## Best Practices

### Asset Data Management
1. **Standardized Naming**: Use consistent asset naming conventions
2. **Complete Documentation**: Maintain comprehensive asset records
3. **Regular Updates**: Keep asset information current
4. **Photo Documentation**: Visual records of asset condition

### Maintenance Optimization
1. **Preventive Focus**: Emphasize preventive over reactive maintenance
2. **Data-Driven Decisions**: Use performance data for maintenance planning
3. **Resource Planning**: Optimize maintenance resource allocation
4. **Vendor Management**: Maintain relationships with service providers

### Lifecycle Management
1. **Procurement Planning**: Plan asset acquisitions strategically
2. **Installation Standards**: Standardize installation procedures
3. **Performance Monitoring**: Continuously monitor asset performance
4. **Disposal Planning**: Plan end-of-life asset disposal

### Cost Control
1. **Budget Planning**: Plan and track asset-related costs
2. **ROI Analysis**: Analyze return on asset investments
3. **Lifecycle Costing**: Consider total ownership costs
4. **Vendor Negotiations**: Negotiate favorable service terms

---

*Last Updated*: January 2025  
*Version*: 2.0  
*Module*: Assets Management
