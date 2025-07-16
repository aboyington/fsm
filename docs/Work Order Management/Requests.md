# Requests Module

## Overview
The Requests module manages incoming customer requests and inquiries.

## Features
- Track all incoming customer requests.
- Link requests to customer details and assets.
- Status updates for efficient tracking.

## Statuses
- **New**: Initial status when a request is created.
- **In Progress**: When the request is being assessed.
- **Completed**: When the request has been resolved.
- **Archived**: Requests no longer active.

## API Endpoints
- `GET /requests`
- `POST /requests/store`
- `GET /requests/edit/{id}`
- `POST /requests/update/{id}`
- `POST /requests/delete/{id}`

## Technical Details
- **Database**: `requests` table
- **Primary Key**: `id`
- **Key Fields**: `customer_id`, `status`, `description`

## Integration
- Customer details linkage for personalized service.

## Future Enhancements
- Improved filtering and sorting options.
