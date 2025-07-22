
# Account Number Registry for Business Services

This document outlines the internal account codes assigned to various operational areas of the company. The account number format has been updated for simplicity, automation, and scalability. Each **client** now receives a single unified account number, and services/products are tracked through SKUs and invoice line items.

---

## Account Code Structure (New Format)

```
<3-Char Category Prefix>-<3-Digit Group or Year ID>-<4-Digit Sequential Code or Abbreviation>
```

### Year-Based Numbering System

For document types (INV, EST, REQ, WRK), the 3-digit group represents the year:
- **2025**: Uses format `XXX-025-XXXX` (e.g., REQ-025-0001)
- **2026**: Uses format `XXX-026-XXXX` (e.g., REQ-026-0001)
- **2027**: Uses format `XXX-027-XXXX` (e.g., REQ-027-0001)
- And so on...

**Important**: Each new year, the year code increments and sequential numbering resets to 0001.

### Legend of 3-Character Prefixes

| Prefix | Category               |
|--------|------------------------|
| ACC    | Client Account Numbers |
| MAT    | Materials (SKU)        |
| HRD    | Hardware (SKU)         |
| PRT    | Parts (SKU)            |
| SRV    | Services (SKU)         |
| INV    | Invoice Document       |
| EST    | Estimate Document      |

---

## Developer Implementation Notes for Year Changes

### Annual Year Code Update Process

Each January, the system requires updates to increment the year code in document numbering:

#### For January 2026 Implementation:
1. **Update Year Constants**: Change year code from `025` to `026`
2. **Reset Sequential Counters**: Reset all document sequence counters to `0001`
3. **Update Database Schema**: Ensure year-based partitioning or indexing is updated
4. **Test Document Generation**: Verify new documents generate with format `REQ-026-0001`, `INV-026-0001`, etc.

#### Code Locations to Update:
- Configuration files containing year constants
- Document generation functions/classes
- Database sequence generators
- Invoice/estimate/request numbering systems
- Work order numbering systems

#### Recommended Implementation:
```php
// Example configuration update for 2026
define('CURRENT_YEAR_CODE', '026'); // Change from '025' to '026'
define('DOCUMENT_SEQUENCE_START', 1); // Reset to 1 each year
```

#### Testing Checklist for 2026:
- [ ] New requests generate as REQ-026-0001, REQ-026-0002, etc.
- [ ] New invoices generate as INV-026-0001, INV-026-0002, etc.
- [ ] New estimates generate as EST-026-0001, EST-026-0002, etc.
- [ ] New work orders generate as WRK-026-0001, WRK-026-0002, etc.
- [ ] Previous year documents (025) remain accessible and unchanged
- [ ] Reporting functions handle both old and new year formats

---

## Sample Registry

| Code Type      | Code Example | Description                             |
| -------------- | ------------ | --------------------------------------- |
| Client Account | ACC-001-ACME | ACME Limited (client)                   |
| Client Account | ACC-002-NWFU | New York Furs (client)                  |
| Client Account | ACC-003-SMIT | Smith I.T. (client)                     |
| Invoice 2025   | INV-025-0001 | Invoice #1 issued in 2025               |
| Invoice 2025   | INV-025-0002 | Invoice #2 issued in 2025               |
| Invoice 2026   | INV-026-0001 | Invoice #1 issued in 2026 (new year)    |
| Estimate 2025  | EST-025-0001 | Estimate #1 issued in 2025              |
| Estimate 2025  | EST-025-0002 | Estimate #2 issued in 2025              |
| Estimate 2026  | EST-026-0001 | Estimate #1 issued in 2026 (new year)   |
| Material SKU   | MAT-0001     | Material item (cables, wire, etc.)      |
| Hardware SKU   | HRD-0002     | Hardware item (mounts, DVRs, brackets)  |
| Part SKU       | PRT-0003     | Part item (sensors, fittings, etc.)     |
| Service SKU    | SRV-CAM-0001 | Camera system service/maintenance visit |
| Service SKU    | SRV-ALA-0001 | Alarm system repair/maintenance visit   |
| Service SKU    | SRV-ITS-0001 | IT support or troubleshooting visit     |
| Service SKU    | SRV-GEN-0001 | General service/labour (multi-purpose)  |
| Work Order 2025| WRK-025-0001 | Work Order Management 2025              |
| Work Order 2026| WRK-026-0001 | Work Order Management 2026 (new year)   |
| Request 2025   | REQ-025-0001 | Work Order Request 2025                 |
| Request 2026   | REQ-026-0001 | Work Order Request 2026 (new year)      |

---

## Account Number Assignment

### Company Accounts
Each company receives a **single account number** when created, and all services/products delivered to them are tracked using **invoices, estimates, and SKUs**.

### Individual Contact Accounts
Individual contacts (those not associated with a company) automatically receive unique account numbers:
- **Format**: ACC-XXX-YYYY (e.g., ACC-001-JOHN, ACC-002-JANE)
- **Auto-generation**: Account numbers are automatically assigned when standalone contacts are created
- **Uniqueness**: Each individual contact gets a unique account identifier
- **Display**: Contact listings show "Company Account" for company-associated contacts, actual account numbers for standalone contacts

### Retroactive Assignment
Existing standalone contacts can be assigned account numbers using the batch generation script:
- **Script**: `scripts/generate_contact_account_numbers.php`
- **Safe operation**: Only assigns numbers to contacts without existing account numbers
- **Progress tracking**: Shows completion progress and statistics

## Handling Clients with Multiple Services

Each client (company or individual contact) has a **single account number**, and all services/products delivered to them are tracked using **invoices, estimates, and SKUs**.

### Example

| Client Name     | Account Code     | Invoice        | Line Items                        |
|----------------|------------------|----------------|------------------------------------|
| ACME Limited    | ACC-001-ACME     | INV-025-0001   | HRD-0002, PRT-0001, SRV-CAM-0001   |
| New York Furs   | ACC-002-NWFU     | INV-025-0002   | SRV-ALA-0001, HRD-0003            |

This structure simplifies reporting, client management, and accounting integration.

---

## Automation & Integration Recommendations

1. **Automated ID Generation**: Maintain a numeric sequence per code type.
2. **Client Registry**: Maintain a central list of client records using ACC-xxx-CLIENT format.
3. **QuickBooks Integration**:
   - Use **client accounts** (ACC codes) as customer records.
   - Services and products (SRV/MAT/HRD/PRT) are used as **line items** on invoices.
   - Assign income accounts to product/service items for clear tracking.
   - Use classes/tags for jobs or divisions if needed.

---

## Template for Future Use

| Code Type      | Code Example     | Description                    |
|----------------|------------------|--------------------------------|
| Client Account | ACC-XXX-CLIENT   | Unique client code             |
| Invoice        | INV-YYY-XXXX     | Invoice for year 20YY          |
| Estimate       | EST-YYY-XXXX     | Estimate for year 20YY         |
| Request        | REQ-YYY-XXXX     | Request for year 20YY          |
| Work Order     | WRK-YYY-XXXX     | Work Order for year 20YY       |
| SKU - Material | MAT-XXXX         | Material item                  |
| SKU - Hardware | HRD-XXXX         | Hardware item                  |
| SKU - Parts    | PRT-XXXX         | Replacement part               |
| SKU - Service  | SRV-XXX-XXXX     | Service/maintenance visit      |

**Note**: YYY represents the 3-digit year code (025 for 2025, 026 for 2026, etc.)

This unified pattern simplifies growth, billing, and system integration.
