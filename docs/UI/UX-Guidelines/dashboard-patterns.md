# Dashboard UI Patterns and Standards

## Overview

This document defines the standardized UI patterns and design guidelines for all dashboard views in the FSM platform. These patterns ensure consistency, accessibility, and maintainability across the application.

## Card Header Standards

### Color Standards (Updated v2.10.0-alpha)

**Primary Standard**: All dashboard card headers now use the `text-body` CSS class, which corresponds to the Bootstrap 5 `--bs-body-color` CSS custom property.

```html
<!-- ✅ CORRECT: Standard card header -->
<h6 class="mb-0 text-body">
    <i class="bi bi-calendar-week"></i> Card Title
</h6>

<!-- ❌ INCORRECT: Do not use color-specific classes -->
<h6 class="mb-0 text-warning">
    <i class="bi bi-calendar-week"></i> Card Title
</h6>
```

### Benefits of --bs-body-color

1. **Theme Consistency**: Automatically adapts to theme changes
2. **Accessibility**: Maintains proper contrast ratios
3. **Future-proof**: Works with light/dark mode implementations
4. **Brand Neutral**: Professional, non-distracting appearance
5. **Maintainability**: Single source of truth for body text color

## Dashboard Card Structure

### Standard Card Layout

```html
<div class="col-md-6 mb-4">
    <div class="card border-0 h-100">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-body">
                <i class="bi bi-icon-name"></i> Card Title
            </h6>
            <button class="btn btn-sm btn-outline-warning">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        <div class="card-body">
            <!-- Card content -->
        </div>
    </div>
</div>
```

### Card Header Components

1. **Title Structure**: Icon + Text with consistent spacing
2. **Color**: Always use `text-body` class
3. **Typography**: `h6` element with `mb-0` class
4. **Icons**: Bootstrap Icons with semantic naming
5. **Actions**: Optional refresh/action buttons

## Empty State Patterns

### Standard Empty State

```html
<div class="text-center py-5">
    <i class="bi bi-icon-name display-1 text-muted"></i>
    <p class="text-muted mt-3">No Records Found</p>
</div>
```

### Empty State Guidelines

1. **Icon**: Large display icon (`display-1`) in muted color
2. **Message**: Clear, concise "No Records Found" text
3. **Spacing**: Generous padding (`py-5`) for visual breathing room
4. **Consistency**: Same pattern across all dashboard views

## Data Table Patterns

### Standard Table Structure

```html
<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows -->
        </tbody>
    </table>
</div>
<div class="mt-2 text-center">
    <small class="text-muted">Total records: X</small>
</div>
```

## Status Badge Patterns

### Standard Status Colors

```html
<!-- Status badges with semantic colors -->
<span class="badge bg-info">Scheduled</span>
<span class="badge bg-primary">In Progress</span>
<span class="badge bg-success">Completed</span>
<span class="badge bg-danger">Cancelled</span>
<span class="badge bg-warning">New</span>
<span class="badge bg-secondary">Terminated</span>
```

### Status Color Guidelines

- **Blue (info)**: Scheduled, Dispatched
- **Primary (primary)**: Active, In Progress
- **Green (success)**: Completed, Approved
- **Red (danger)**: Cancelled, Rejected, Error
- **Yellow (warning)**: New, Pending, Draft
- **Gray (secondary)**: Terminated, Inactive

## Icon Usage Standards

### Icon Sizing Hierarchy

```css
/* Standard icon sizes */
.fs-1 { font-size: 2.5rem; }      /* Large display icons */
.fs-2 { font-size: 2rem; }        /* Section headers */
.fs-3 { font-size: 1.75rem; }     /* Card headers */
default { font-size: 1rem; }       /* Inline icons */
.fs-sm { font-size: 0.875rem; }   /* Small UI elements */
```

### Icon Usage Context

1. **Card Headers**: Default size (1rem) with consistent spacing
2. **Empty States**: Large size (`display-1`) for visual impact
3. **Buttons**: Small size for inline actions
4. **Status Indicators**: Default size for data tables

## Responsive Design Patterns

### Grid Breakpoints

```html
<!-- Dashboard grid responsive patterns -->
<div class="col-md-3">    <!-- Stats cards: 4 columns on desktop -->
<div class="col-md-6">    <!-- Data cards: 2 columns on desktop -->
<div class="col-md-12">   <!-- Full-width cards when needed -->
```

### Mobile Considerations

1. **Card Stacking**: Single column layout on mobile
2. **Touch Targets**: Minimum 44px for touch interaction
3. **Table Scrolling**: Horizontal scroll for data tables
4. **Typography Scale**: Appropriate sizing for mobile screens

## Dashboard Layout Patterns

### Standard Dashboard Structure

```html
<?= $this->extend('dashboard/layout') ?>
<?= $this->section('dashboard-content') ?>

<!-- Stats Cards Row -->
<div class="row mb-4">
    <!-- KPI cards in 3 or 4 column layout -->
</div>

<!-- Data Cards Grid -->
<div class="row">
    <!-- Data tables and charts in responsive grid -->
</div>

<?= $this->endSection() ?>
```

## Accessibility Standards

### WCAG 2.1 Compliance

1. **Color Contrast**: All text meets AA contrast requirements
2. **Keyboard Navigation**: Full keyboard accessibility
3. **Screen Readers**: Proper ARIA labels and semantic HTML
4. **Focus Management**: Visible focus indicators

### Implementation Guidelines

```html
<!-- Accessible card header -->
<h6 class="mb-0 text-body" role="heading" aria-level="3">
    <i class="bi bi-calendar-week" aria-hidden="true"></i>
    <span>Scheduled Appointments</span>
</h6>

<!-- Accessible button -->
<button class="btn btn-sm btn-outline-warning" 
        aria-label="Refresh data">
    <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
</button>
```

## Performance Considerations

### CSS Optimization

1. **Utility Classes**: Use Bootstrap utilities over custom CSS
2. **Consistent Classes**: Reuse established patterns
3. **Minimal Custom CSS**: Leverage Bootstrap's design system
4. **No Inline Styles**: Keep styling in CSS files

### Loading States

```html
<!-- Loading state pattern -->
<div class="text-center py-5">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="text-muted mt-3">Loading data...</p>
</div>
```

## Implementation Checklist

### For New Dashboard Views

- [ ] Use `text-body` for all card headers
- [ ] Implement standard empty states
- [ ] Follow responsive grid patterns
- [ ] Include proper ARIA labels
- [ ] Test mobile responsiveness
- [ ] Validate color contrast
- [ ] Use semantic HTML structure
- [ ] Include loading states where appropriate

### For Existing View Updates

- [ ] Update card headers to `text-body`
- [ ] Standardize empty state messages
- [ ] Ensure responsive behavior
- [ ] Add missing accessibility features
- [ ] Validate against design patterns
- [ ] Test cross-browser compatibility

## Common Mistakes to Avoid

### ❌ Anti-patterns

```html
<!-- Don't use color-specific classes for headers -->
<h6 class="text-warning">Header</h6>

<!-- Don't use inconsistent empty states -->
<div class="alert alert-info">No data available</div>

<!-- Don't use non-semantic HTML -->
<div class="fake-header">Title</div>

<!-- Don't use inline styles -->
<h6 style="color: #ffc107;">Header</h6>
```

### ✅ Correct Patterns

```html
<!-- Use standardized header classes -->
<h6 class="mb-0 text-body">Header</h6>

<!-- Use consistent empty states -->
<div class="text-center py-5">
    <i class="bi bi-inbox display-1 text-muted"></i>
    <p class="text-muted mt-3">No Records Found</p>
</div>

<!-- Use semantic HTML -->
<h6 role="heading" aria-level="3">Title</h6>

<!-- Use CSS classes -->
<h6 class="dashboard-header">Header</h6>
```

## Version History

### v2.10.0-alpha (January 23, 2025)
- ✅ Standardized all card headers to use `text-body`
- ✅ Implemented consistent empty states
- ✅ Updated all dashboard views for visual consistency
- ✅ Established UI pattern documentation

### Future Enhancements
- Custom theme support
- Dark mode compatibility
- Enhanced accessibility features
- Advanced responsive patterns

---

*Last Updated: January 23, 2025*  
*Version: 2.10.0-alpha*  
*Status: Active Implementation Guidelines*
