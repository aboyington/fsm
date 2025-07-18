
# Account Number Registry for Business Services

This document outlines the internal account codes assigned to various operational areas of the company. The account number format has been updated for simplicity, automation, and scalability. Each **client** now receives a single unified account number, and services/products are tracked through SKUs and invoice line items.

---

## Account Code Structure (New Format)

```
<3-Char Category Prefix>-<3-Digit Group or Year ID>-<4-Digit Sequential Code or Abbreviation>
```

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

## Sample Registry

| Code Type      | Code Example | Description                             |
| -------------- | ------------ | --------------------------------------- |
| Client Account | ACC-001-ACME | ACME Limited (client)                   |
| Client Account | ACC-002-NWFU | New York Furs (client)                  |
| Client Account | ACC-003-SMIT | Smith I.T. (client)                     |
| Invoice        | INV-025-0001 | Invoice #1 issued in 2025               |
| Invoice        | INV-025-0002 | Invoice #2 issued in 2025               |
| Estimate       | EST-025-0001 | Estimate #1 issued in 2025              |
| Estimate       | EST-025-0002 | Estimate #2 issued in 2025              |
| Material SKU   | MAT-0001     | Material item (cables, wire, etc.)      |
| Hardware SKU   | HRD-0002     | Hardware item (mounts, DVRs, brackets)  |
| Part SKU       | PRT-0003     | Part item (sensors, fittings, etc.)     |
| Service SKU    | SRV-CAM-0001 | Camera system service/maintenance visit |
| Service SKU    | SRV-ALA-0001 | Alarm system repair/maintenance visit   |
| Service SKU    | SRV-ITS-0001 | IT support or troubleshooting visit     |
| Service SKU    | SRV-GEN-0001 | General service/labour (multi-purpose)  |

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
| Invoice        | INV-025-XXXX     | Invoice for year 2025          |
| Estimate       | EST-025-XXXX     | Estimate for year 2025         |
| SKU - Material | MAT-XXXX         | Material item                  |
| SKU - Hardware | HRD-XXXX         | Hardware item                  |
| SKU - Parts    | PRT-XXXX         | Replacement part               |
| SKU - Service  | SRV-XXX-XXXX     | Service/maintenance visit      |

This unified pattern simplifies growth, billing, and system integration.
