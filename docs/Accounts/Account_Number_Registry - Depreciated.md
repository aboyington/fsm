
# Depreciated

# Account Number Registry for Business Services

This document outlines the internal account codes assigned to various operational areas of the company. The account number format has been updated for simplicity, automation, and scalability. Each account number now uses a standardized 3-part structure: `<Prefix>-<Year/ID>-<Code>` (e.g., `INV-025-0001`).

---

## Account Code Structure (New Format)

```
<3-Char Category Prefix>-<3-Digit Group or Year ID>-<4-Digit Sequential Code or Abbreviation>
```

### Legend of 3-Character Prefixes

| Prefix | Category               |
|--------|------------------------|
| ALA    | Alarm & Access         |
| CAM    | Camera Systems         |
| ITS    | IT Services & Support  |
| SUB    | Subcontracted Services |
| MAT    | Materials (SKU)        |
| HRD    | Hardware (SKU)         |
| PRT    | Parts (SKU)            |
| SRV    | Services (SKU)         |
| INV    | Invoice Document       |
| EST    | Estimate Document      |

---

## Sample Account Registry

| Code Type      | Code Example | Description                             |
| -------------- | ------------ | --------------------------------------- |
| Account Number | ALA-001-ACME | Alarm & Access for ACME Limited         |
| Account Number | CAM-002-NWFU | Camera System for New York Furs         |
| Account Number | ITS-003-SMIT | IT Support for Smith I.T.               |
| Account Number | SUB-004-JSGR | Subcontracted job for Johnson Group     |
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

## Handling Clients with Multiple Services

Clients that receive multiple services (e.g., both Alarm & Access and Camera Systems) will have **separate account entries**, each with a unique account number. This allows for clear revenue segregation, easier automation, and precise reporting.

### Example:

| Client Name     | Service Type      | Account Code     |
|------------------|--------------------|------------------|
| New York Furs    | Alarm & Access     | ALA-002-NWFU     |
| New York Furs    | Camera Systems     | CAM-002-NWFU     |

For better organization, maintain two linked tables:

1. **Client Table (master)** – Lists each client only once.
2. **Service Registry Table** – Lists each client–service combination with its account number.

---

## Automation & Integration Recommendations

1. **Automated ID Generation**: Maintain a sequence per prefix (e.g., ALA-001, CAM-002...).
2. **Client Registry**: Maintain a centralized registry linking clients and their account codes.
3. **QuickBooks**: Set up service prefixes as parent accounts (ALA, CAM, etc.) with each client-service combo as a subaccount.  
   - In QuickBooks, go to *Chart of Accounts* and create the main service categories (e.g., ALA - Alarm & Access, CAM - Camera Systems).  
   - Then, for each client-service relationship (e.g., ACME alarm system), create a subaccount such as **ALA-001-ACME** with "subaccount of ALA" checked.  
   - This structure enables clear income segregation, makes reporting by service line easy, and ensures that your invoices and service items flow to the correct P&L lines.  
   - When creating income items, assign them to these subaccounts so revenue is tracked appropriately.
4. **Use Classes or Tags**: For project-level or detailed client reporting.

---

## Template for Future Use

| Code Type      | Code Example     | Description                    |
|----------------|------------------|--------------------------------|
| Account Number | CAM-XXX-CLIENT   | Camera Systems for CLIENT      |
| Invoice        | INV-025-XXXX     | Invoice for year 2025          |
| Estimate       | EST-025-XXXX     | Estimate for year 2025         |
| SKU - Material | MAT-XXXX         | Material item                  |
| SKU - Hardware | HRD-XXXX         | Hardware item                  |
| SKU - Parts    | PRT-XXXX         | Replacement part               |
| SKU - Service  | SRV-XXX-XXXX     | Service/maintenance visit      |

This pattern helps you scale cleanly while maintaining traceability and visual consistency.


