# Request Management Dashboard Card Components

This directory contains reusable card components for the Request Management dashboard.

## Stat Cards (Top Row)

### 1. Total Requests Stat Card
**File:** `total-requests-stat.php`
**Description:** Displays total number of requests with percentage change
**Icon:** bi-inbox
**Color:** text-primary
**Required Data:** `$request_stats['total_requests']`

### 2. Total Converted Requests Stat Card
**File:** `converted-requests-stat.php`
**Description:** Shows requests that have been converted to work orders
**Icon:** bi-check-circle
**Color:** text-success
**Required Data:** `$request_stats['converted_requests']`

### 3. Completed Requests Stat Card
**File:** `completed-requests-stat.php`
**Description:** Displays completed requests count
**Icon:** bi-calendar-event
**Color:** text-info
**Required Data:** `$request_stats['completed_requests']`

### 4. Cancelled/Terminated Requests Stat Card
**File:** `cancelled-requests-stat.php`
**Description:** Shows cancelled or terminated requests
**Icon:** bi-x-circle
**Color:** text-warning
**Required Data:** `$request_stats['cancelled_requests']`

## Data Cards (Bottom Grid)

### 5. New Requests Card
**File:** `new-requests-card.php`
**Description:** Table of new/pending requests with details
**Icon:** bi-inbox
**Color:** text-primary
**Required Data:** `$request_data['new_requests']`
**Table Columns:** Request Number, Request Name, Status, Priority

### 6. New Estimates Card
**File:** `new-estimates-card.php`
**Description:** Table of new estimates awaiting approval
**Icon:** bi-calculator
**Color:** text-primary
**Required Data:** `$request_data['new_estimates']`
**Table Columns:** Estimate #, Customer, Amount, Status, Date

### 7. Completed Requests Card
**File:** `completed-requests-card.php`
**Description:** Table of completed requests
**Icon:** bi-check-circle
**Color:** text-primary
**Required Data:** `$request_data['completed_requests']`
**Table Columns:** Request Number, Request Name, Status, Completed Date

### 8. Cancelled Requests Card
**File:** `cancelled-requests-card.php`
**Description:** Table of cancelled requests
**Icon:** bi-x-circle
**Color:** text-primary
**Required Data:** `$request_data['cancelled_requests']`
**Table Columns:** Request Number, Request Name, Status, Cancelled Date

### 9. Approved Estimates Card
**File:** `approved-estimates-card.php`
**Description:** Table of approved estimates
**Icon:** bi-check-circle
**Color:** text-primary
**Required Data:** `$request_data['approved_estimates']`
**Table Columns:** Estimate #, Customer, Amount, Status, Approved Date

### 10. Cancelled Estimates Card
**File:** `cancelled-estimates-card.php`
**Description:** Table of cancelled estimates
**Icon:** bi-x-circle
**Color:** text-primary
**Required Data:** `$request_data['cancelled_estimates']`
**Table Columns:** Estimate #, Customer, Amount, Status, Cancelled Date

## Usage

To include a card component in a dashboard view:

```php
<?= $this->include('components/cards/new-requests-card') ?>
```

## Data Structure Expected

### For Stat Cards:
```php
$request_stats = [
    'total_requests' => 4,
    'converted_requests' => 0,
    'completed_requests' => 0,
    'cancelled_requests' => 0
];
```

### For Data Cards:
```php
$request_data = [
    'new_requests' => [
        [
            'id' => 1,
            'request_number' => 'REQ-001',
            'request_name' => 'HVAC Repair',
            'priority' => 'high'
        ]
    ],
    'new_estimates' => [
        [
            'id' => 1,
            'customer_name' => 'John Doe',
            'total_amount' => 500.00,
            'created_at' => '2025-01-23'
        ]
    ],
    // ... other arrays
];
```

## Design Notes

- All cards use Bootstrap 5 classes
- Icons are from Bootstrap Icons (bi-*)
- Cards have consistent hover effects and responsive behavior
- Empty states show appropriate "No Records Found" message
- Tables are responsive with table-responsive wrapper
- Color scheme follows dashboard theme (primary, success, info, warning)
