# FSM Troubleshooting Guide

## Overview
This document provides solutions to common issues encountered in the FSM platform and documents recent fixes applied to the system.

## Recent Fixes (January 2025)

### 1. PHP Fatal Error - Cannot redeclare function
**Problem:** Application crashes with "Fatal error: Cannot redeclare function esc()" when accessing certain pages.

**Symptoms:**
- White screen of death (WSOD) when loading pages
- Fatal error message about function redeclaration
- Application completely non-functional

**Root Cause:** The `esc()` function was being declared multiple times, likely due to duplicate includes or function definitions in different files.

**Solution Applied:**
Added function existence check before declaring the function:
```php
// Before declaring the function, check if it already exists
if (!function_exists('esc')) {
    function esc($data, $context = 'html', $encoding = null) {
        // Function implementation
    }
}
```

**Prevention:**
- Always use `function_exists()` checks for utility functions
- Avoid duplicate function declarations across files
- Use namespaces or classes for better organization
- Consider using CodeIgniter's built-in helper functions instead of custom ones

### 2. HTTP Method Detection Issues
**Problem:** CodeIgniter 4 was incorrectly detecting HTTP methods, causing 400 Bad Request errors for POST operations in territories and users modules.

**Symptoms:**
- "Invalid request method" errors when updating territories
- Failed POST requests despite correct form submission
- 400 Bad Request responses for valid operations

**Root Cause:** CodeIgniter's `$this->request->getMethod()` was returning incorrect values (e.g., 'get' instead of 'post') possibly due to method override headers or server configuration.

**Solution Applied:**
Updated controller methods to use flexible POST detection:
```php
// Instead of strict checking:
if ($this->request->getMethod() === 'post') { }

// Now using flexible detection:
$method = $this->request->getMethod();
$serverMethod = $_SERVER['REQUEST_METHOD'] ?? '';

// Accept request if POST data exists
if (empty($this->request->getPost()) && empty($_POST)) {
    return $this->response->setJSON([
        'success' => false,
        'message' => 'No data received. Method: ' . $method . ' / ' . $serverMethod
    ])->setStatusCode(400);
}
```

**Affected Methods:**
- `Settings::updateTerritory()`
- `Settings::addTerritory()`
- `Settings::deleteTerritory()`
- `Settings::updateUser()`

### 3. Delete Operation Flexibility
**Problem:** Delete operations were failing due to strict POST method checking, even though they might not include body data.

**Solution Applied:**
Special handling for delete operations that checks multiple conditions:
```php
if ($method !== 'post' && $serverMethod !== 'POST' && empty($this->request->getPost()) && empty($_POST)) {
    // Only reject if none of the conditions are met
}
```

## Common Issues and Solutions

### 1. Session/Authentication Issues

**Problem:** "Unauthorized: Please login to continue" errors

**Solutions:**
- Ensure session is properly initialized in login process
- Check if `auth_token` is set in session
- Verify session configuration in `app/Config/Session.php`
- Clear browser cookies and re-login

### 2. CSRF Token Errors

**Problem:** Form submissions fail with CSRF validation errors

**Solutions:**
- Ensure CSRF token is included in all forms: `<?= csrf_field() ?>`
- For AJAX requests, include token in FormData:
  ```javascript
  formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
  ```
- Check CSRF configuration in `app/Config/Security.php`

### 3. Database Connection Issues

**Problem:** "Database connection failed" or SQLite errors

**Solutions:**
- Verify SQLite database exists at `/writable/database/fsm.db`
- Check file permissions (should be writable by web server)
- Ensure SQLite PHP extension is enabled
- Verify database configuration in `.env` file

### 4. Modal Not Opening

**Problem:** Edit/Add modals don't open when buttons are clicked

**Solutions:**
- Check browser console for JavaScript errors
- Verify Bootstrap is properly loaded
- Ensure jQuery is loaded before Bootstrap
- Check for conflicting JavaScript that might prevent event handlers

**Advanced Solution - Start Over Pattern:**
If modal issues persist despite multiple fixes, consider starting over with a working pattern:

1. **Identify a Working Reference:** Find a similar page/modal that works correctly (e.g., users page, profiles page)
2. **Copy Exact Structure:** Use the working page as a template, copying:
   - Button structure with exact data attributes
   - Modal HTML structure
   - JavaScript event handling patterns
   - Form structure and naming
3. **Adapt Content Only:** Change only the content-specific parts (field names, endpoints, etc.)
4. **Avoid Custom Modifications:** Don't add custom JavaScript or modify the working pattern initially

**Example Success Case:** Record Templates Modal Fix
- Problem: Custom `onclick="openModal()"` functions causing "undefined" errors
- Solution: Copied exact pattern from working users page:
  ```html
  <!-- Instead of custom onclick -->
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
  ```
- Result: Immediate fix with consistent behavior across application

### 5. Data Not Saving

**Problem:** Form submissions appear successful but data isn't saved

**Solutions:**
- Check browser Network tab for actual response
- Verify model validation rules aren't blocking saves
- Check database table structure matches model expectations
- Look for validation errors in response JSON
- Ensure all required fields are included in form

### 6. Search/Filter Not Working

**Problem:** Search or filter functionality doesn't update results

**Solutions:**
- Check if JavaScript events are properly bound
- Verify query parameters are being passed in URL
- Ensure controller is reading filter parameters correctly
- Check for JavaScript errors in console

## Debugging Tips

### 1. Enable Debug Mode
In `.env` file:
```
CI_ENVIRONMENT = development
```

### 2. Check Logs
- Application logs: `/writable/logs/`
- Look for files named `log-YYYY-MM-DD.log`

### 3. Browser Developer Tools
- Network tab: Monitor AJAX requests and responses
- Console: Check for JavaScript errors
- Application/Storage: Inspect cookies and session data

### 4. Database Queries
Enable query logging in development:
```php
// In controller or model
log_message('debug', $this->db->getLastQuery());
```

### 5. Debug Controller Methods
Add debug logging:
```php
log_message('debug', 'Method called - Data: ' . json_encode($data));
```

## Development Best Practices

### 1. When to Start Over vs. Debug

**Start Over When:**
- Multiple failed debugging attempts on the same issue
- Custom code has grown complex and hard to maintain
- You have a proven working pattern elsewhere in the codebase
- Debugging time exceeds rebuild time
- Code has accumulated too many "quick fixes"

**Continue Debugging When:**
- Issue is isolated and specific
- Working with unique/custom functionality
- No existing patterns to reference
- Learning/educational value in solving the problem

### 2. Pattern-Based Development

**Benefits:**
- Consistent user experience across the application
- Reduced development time
- Lower risk of introducing bugs
- Easier maintenance and updates
- Proven functionality

**Implementation:**
1. **Identify Successful Patterns:** Document working implementations (modals, forms, AJAX calls)
2. **Create Templates:** Save working code snippets for reuse
3. **Follow Conventions:** Use consistent naming, structure, and approaches
4. **Test Early:** Verify new implementations match existing patterns

### 3. Code Quality Guidelines

**Avoid:**
- Custom JavaScript when Bootstrap/jQuery patterns exist
- Reinventing functionality that already works
- Complex debugging when simple solutions exist
- Inconsistent patterns across similar features

**Prefer:**
- Proven, working code patterns
- Consistent naming conventions
- Standard library features over custom implementations
- Simple, readable solutions

## Performance Optimization

### 1. Database Indexes
Ensure proper indexes on frequently queried columns:
- `users.email`
- `users.status`
- `territories.status`
- Foreign key columns

### 2. Pagination
Implement pagination for large datasets instead of loading all records.

### 3. Caching
Consider implementing caching for:
- User roles and permissions
- Organization settings
- Currency exchange rates

## Security Best Practices

### 1. Always Validate Input
- Use CodeIgniter's validation rules
- Sanitize user input before database operations
- Validate data types and ranges

### 2. Authentication Checks
- Add auth checks to all controller methods
- Verify user permissions for specific operations
- Log security-related events

### 3. SQL Injection Prevention
- Use Query Builder instead of raw queries
- Bind parameters properly
- Escape data when necessary

### 4. XSS Prevention
- Use `esc()` helper for output
- Set proper Content-Type headers
- Validate and sanitize HTML content

## Need More Help?

If you encounter issues not covered in this guide:

1. Check the specific module documentation
2. Review recent commits for related changes
3. Enable debug mode and check logs
4. Search for similar issues in the codebase
5. Document new issues and solutions for future reference

---

*Last Updated*: January 2025
*Version*: 1.0
