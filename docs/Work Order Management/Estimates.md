# Estimates Module

## Overview
The Estimates module deals with cost estimation for services.

## Features
- Generate cost estimates linked to requests.
- PDF generation for customer distribution.
- Line item management for accurate cost breakdowns.

## Statuses
- **Draft**: Initial state for cost estimation.
- **Sent**: Estimate sent to the customer.
- **Accepted**: Customer has accepted the estimate.
- **Rejected**: Customer has declined the estimate.

## API Endpoints
- `GET /estimates`
- `POST /estimates/store`
- `GET /estimates/edit/{id}`
- `POST /estimates/update/{id}`
- `POST /estimates/delete/{id}`

## Technical Details
- **Database**: `estimates` table
- **Primary Key**: `id`
- **Key Fields**: `request_id`, `total_cost`

## Integration
- Creates work orders based on accepted estimates.

## Future Enhancements
- Enhanced integration with financial systems.
