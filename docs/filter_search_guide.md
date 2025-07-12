# Filter and Search Implementation Guide

This document provides a consistent pattern for implementing filtering and searching features across web pages using jQuery.

## Key Features

- **Real-time search**: Filters data as you type with a 500ms debounce.
- **Category filtering**: Allows dropdown to filter specific categories.
- **Server-side processing**: Utilizes URL parameters for robust backend filtering.
- **Minimal configuration**: Can be applied to different pages with similar setups.

## Frontend Implementation

### HTML Structure

```html
<div class="d-flex align-items-center gap-3">
    <label for="categoryFilter" class="form-label mb-0 fw-medium">Category:</label>
    <select id="categoryFilter" class="form-select" style="width: auto;">
        <!-- Options generated dynamically -->
    </select>
</div>

<input type="text" id="searchFilter" class="form-control" placeholder="Search...">
```

### JavaScript Implementation

```javascript
// Wait for jQuery to load
(function checkJQuery() {
    if (typeof jQuery === 'undefined') {
        setTimeout(checkJQuery, 50);
        return;
    }

    // Use jQuery safely
    jQuery(document).ready(function($) {
        $('#categoryFilter').on('change', applyFilters);

        let searchTimeout;
        $('#searchFilter').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });
    });
})();

function applyFilters() {
    const category = $('#categoryFilter').val();
    const search = $('#searchFilter').val().trim();

    const params = new URLSearchParams();
    if (category) params.append('category', category);
    if (search) params.append('search', search);

    const queryString = params.toString();
    const newUrl = '/your-endpoint-here' + (queryString ? '?' + queryString : '');

    window.location.href = newUrl;
}
```

## Backend Considerations

Ensure your backend can handle the incoming category and search parameters. Filter data server-side based on these parameters.

### Example Code

```php
$categoryFilter = $_GET['category'] ?? 'default_category';
$searchQuery = $_GET['search'] ?? '';

$filteredData = filterData($categoryFilter, $searchQuery);

function filterData($category, $search) {
    // Implement your filtering logic
    // Return filtered results
}
```

## Consistent Experience

By following this guide, you ensure a consistent filtering and searching experience across all relevant pages.
