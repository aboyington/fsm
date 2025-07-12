<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4"><?= $title ?></h4>
    <p class="text-muted">Manage and classify Personally Identifiable Information (PII) fields across your field service organization. Proper PII classification helps ensure compliance with data protection regulations.</p>
                        
                        <!-- Filter Controls -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <label for="categoryFilter" class="form-label mb-0 fw-medium">Category:</label>
                                    <select id="categoryFilter" class="form-select" style="width: auto;">
                                        <?php foreach ($categories as $key => $name): ?>
                                            <option value="<?= $key ?>" <?= $filters['category'] === $key ? 'selected' : '' ?>>
                                                <?= $name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" id="searchFilter" class="form-control" 
                                           placeholder="Search by field name or API name..." 
                                           value="<?= esc($filters['search']) ?>">
                                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Fields Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Field Label</th>
                                        <th>Data Type</th>
                                        <th>API Name</th>
                                        <th>PII Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($piiFields)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                                No fields found for the selected category
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($piiFields as $field): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-tag me-2 text-muted"></i>
                                                        <span class="fw-medium"><?= esc($field['label']) ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">
                                                        <?= ucfirst(esc($field['data_type'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <code class="text-muted"><?= esc($field['api_name']) ?></code>
                                                </td>
                                                <td>
                                                    <?php if ($field['is_pii']): ?>
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-shield-lock me-1"></i>
                                                            PII Field
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-shield-check me-1"></i>
                                                            Non-PII
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" 
                                                                class="btn btn-outline-primary toggle-pii-btn" 
                                                                data-field="<?= esc($field['api_name']) ?>"
                                                                data-current="<?= $field['is_pii'] ? 'true' : 'false' ?>"
                                                                title="<?= $field['is_pii'] ? 'Mark as Non-PII' : 'Mark as PII' ?>">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                            Toggle
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-outline-secondary view-details-btn"
                                                                data-field="<?= esc($field['api_name']) ?>"
                                                                title="View Details">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Statistics Summary -->
                        <?php if (!empty($piiFields)): ?>
                            <?php 
                                $piiCount = count(array_filter($piiFields, function($field) { 
                                    return $field['is_pii']; 
                                }));
                                $nonPiiCount = count($piiFields) - $piiCount;
                            ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <h6 class="mb-1">Total Fields</h6>
                                                <span class="h5 text-primary"><?= count($piiFields) ?></span>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="mb-1">PII Fields</h6>
                                                <span class="h5 text-danger"><?= $piiCount ?></span>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="mb-1">Non-PII Fields</h6>
                                                <span class="h5 text-success"><?= $nonPiiCount ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Info Panel -->
                        <div class="mt-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-info-circle text-primary me-2"></i>
                                        About PII Field Management
                                    </h6>
                                    <p class="card-text mb-2">
                                        <strong>Personally Identifiable Information (PII)</strong> refers to any data that 
                                        could potentially identify a specific individual. Managing PII fields helps ensure 
                                        compliance with data protection regulations.
                                    </p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-danger">PII Fields typically include:</h6>
                                            <ul class="small mb-0">
                                                <li>Names (First, Last, Full)</li>
                                                <li>Contact Information (Email, Phone)</li>
                                                <li>Identification Numbers</li>
                                                <li>Personal Notes or Comments</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-success">Non-PII Fields typically include:</h6>
                                            <ul class="small mb-0">
                                                <li>Technical Specifications</li>
                                                <li>Product Codes and Descriptions</li>
                                                <li>General System Settings</li>
                                                <li>Anonymous Metrics</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
</div>

<!-- Field Details Modal -->
<div class="modal fade" id="fieldDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Field Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="fieldDetailsContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm PII Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="confirmMessage">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Fade-in animation for table rows */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeInUp 0.3s ease-out forwards;
    opacity: 0;
}

/* Search input focus enhancement */
#searchFilter:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-color: #86b7fe;
}

/* Filter badges styling */
.filter-badges .badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.filter-badges .btn-close {
    font-size: 0.7rem;
    margin-left: 0.5rem;
}

/* Enhanced table hover effect */
.table tbody tr:hover {
    background-color: rgba(13, 110, 253, 0.05);
    transition: background-color 0.2s ease;
}

/* PII badge styling enhancements */
.badge.bg-danger {
    background: linear-gradient(45deg, #dc3545, #b02a37) !important;
}

.badge.bg-success {
    background: linear-gradient(45deg, #198754, #146c43) !important;
}

/* Loading state styling */
.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Button group enhancements */
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Filter summary styling */
.filter-summary {
    border-left: 4px solid #0d6efd;
    background: linear-gradient(to right, rgba(13, 110, 253, 0.05), transparent);
}

/* Quick action buttons */
.toggle-pii-btn:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

.view-details-btn:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

/* Data type badges */
.badge.bg-light {
    color: #495057 !important;
    border: 1px solid #dee2e6;
}

/* Statistics cards enhancement */
.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    border-color: #b6d4da;
}

/* Category filter enhancement */
#categoryFilter {
    min-width: 200px;
}

/* Search clear button */
.search-clear {
    position: absolute;
    right: 45px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0;
    z-index: 3;
}

.search-clear:hover {
    color: #495057;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .filter-controls .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }
    
    .statistics-cards .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>

<script>
// Wait for jQuery to load
(function checkJQuery() {
    if (typeof jQuery === 'undefined') {
        setTimeout(checkJQuery, 50);
        return;
    }
    
    // Use jQuery safely
    jQuery(document).ready(function($) {
    // Initialize page
    initializeFilters();
    
    // Filter change handlers
    $('#categoryFilter').on('change', function() {
        applyFilters();
    });

    $('#searchBtn').on('click', function() {
        applyFilters();
    });

    $('#searchFilter').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            applyFilters();
        }
    });

    // Real-time search with debounce (simplified like Users page)
    let searchTimeout;
    $('#searchFilter').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            applyFilters();
        }, 500); // 500ms debounce
    });

    // Toggle PII status
    $('.toggle-pii-btn').on('click', function() {
        const fieldName = $(this).data('field');
        const currentStatus = $(this).data('current') === 'true';
        const newStatus = !currentStatus;
        
        const message = `
            <p>Are you sure you want to change the PII status for <strong>${fieldName}</strong>?</p>
            <div class="alert alert-${newStatus ? 'warning' : 'info'}">
                <strong>New Status:</strong> ${newStatus ? 'PII Field' : 'Non-PII Field'}
            </div>
            <p class="text-muted small">
                ${newStatus ? 
                    'This field will be treated as containing personally identifiable information.' : 
                    'This field will be treated as not containing personally identifiable information.'}
            </p>
        `;
        
        $('#confirmMessage').html(message);
        $('#confirmModal').modal('show');
        
        $('#confirmAction').off('click').on('click', function() {
            // Here you would typically make an AJAX call to update the status
            console.log(`Toggling PII status for ${fieldName} to ${newStatus}`);
            
            // For demo purposes, just show a success message
            showAlert('success', `PII status updated for ${fieldName}`);
            
            // Update the UI
            updateFieldUI(fieldName, newStatus);
            
            $('#confirmModal').modal('hide');
        });
    });

    // View field details
    $('.view-details-btn').on('click', function() {
        const fieldName = $(this).data('field');
        showFieldDetails(fieldName);
    });

    function initializeFilters() {
        // Update filter summary
        updateFilterSummary();
        
        // Add filter badges
        addFilterBadges();
    }

    function applyFilters() {
        const category = $('#categoryFilter').val();
        const search = $('#searchFilter').val().trim();
        
        const params = new URLSearchParams();
        if (category) params.append('category', category);
        if (search) params.append('search', search);
        
        const queryString = params.toString();
        const newUrl = '<?= site_url('settings/pii-fields') ?>' + (queryString ? '?' + queryString : '');
        
        window.location.href = newUrl;
    }

    function showLoadingState() {
        const tableBody = $('tbody');
        tableBody.html(`
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2 text-muted">Loading fields...</div>
                </td>
            </tr>
        `);
    }

    function updateFilterSummary() {
        const category = $('#categoryFilter option:selected').text();
        const search = $('#searchFilter').val();
        // Only count data rows, not the "no data" message row
        const totalFields = $('.table tbody tr').not('.no-data-row').length;
        
        let summaryText = `Showing fields for <strong>${category}</strong>`;
        if (search) {
            summaryText += ` matching "<strong>${search}</strong>"`;
        }
        summaryText += ` (${totalFields} field${totalFields !== 1 ? 's' : ''})`;
        
        // Add or update summary
        if ($('.filter-summary').length === 0) {
            $('.table-responsive').before(`
                <div class="filter-summary alert alert-light border mb-3">
                    <small class="text-muted">${summaryText}</small>
                </div>
            `);
        } else {
            $('.filter-summary small').html(summaryText);
        }
    }

    function addFilterBadges() {
        const search = $('#searchFilter').val();
        
        // Remove existing badges
        $('.filter-badges').remove();
        
        if (search) {
            const badgesHtml = `
                <div class="filter-badges mb-3">
                    <span class="badge bg-primary me-2">
                        Search: "${search}"
                        <button type="button" class="btn-close btn-close-white ms-1" 
                                onclick="clearSearchFilter()" aria-label="Clear search"></button>
                    </span>
                </div>
            `;
            $('.filter-summary').after(badgesHtml);
        }
    }

    // Global function for clearing search
    window.clearSearchFilter = function() {
        $('#searchFilter').val('');
        applyFilters();
    };

    // Add keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            $('#searchFilter').focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && $('#searchFilter').is(':focus')) {
            $('#searchFilter').val('');
            applyFilters();
        }
    });

    // Add filter animations
    $('.table tbody tr').each(function(index) {
        $(this).css('animation-delay', (index * 50) + 'ms');
        $(this).addClass('fade-in');
    });


    function updateFieldUI(fieldName, isPii) {
        const row = $(`.toggle-pii-btn[data-field="${fieldName}"]`).closest('tr');
        const statusCell = row.find('td:nth-child(4)');
        const toggleBtn = row.find('.toggle-pii-btn');
        
        if (isPii) {
            statusCell.html(`
                <span class="badge bg-danger">
                    <i class="bi bi-shield-lock me-1"></i>
                    PII Field
                </span>
            `);
            toggleBtn.attr('title', 'Mark as Non-PII').data('current', 'true');
        } else {
            statusCell.html(`
                <span class="badge bg-success">
                    <i class="bi bi-shield-check me-1"></i>
                    Non-PII
                </span>
            `);
            toggleBtn.attr('title', 'Mark as PII').data('current', 'false');
        }
    }

    function showFieldDetails(fieldName) {
        // Find the field data from the current table
        const row = $(`.view-details-btn[data-field="${fieldName}"]`).closest('tr');
        const label = row.find('td:nth-child(1) .fw-medium').text();
        const dataType = row.find('td:nth-child(2) .badge').text();
        const isPii = row.find('td:nth-child(4) .badge').hasClass('bg-danger');
        
        const content = `
            <div class="row">
                <div class="col-sm-4"><strong>Field Label:</strong></div>
                <div class="col-sm-8">${label}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4"><strong>API Name:</strong></div>
                <div class="col-sm-8"><code>${fieldName}</code></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4"><strong>Data Type:</strong></div>
                <div class="col-sm-8">${dataType}</div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4"><strong>PII Status:</strong></div>
                <div class="col-sm-8">
                    <span class="badge bg-${isPii ? 'danger' : 'success'}">
                        <i class="bi bi-shield-${isPii ? 'lock' : 'check'} me-1"></i>
                        ${isPii ? 'PII Field' : 'Non-PII Field'}
                    </span>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-4"><strong>Category:</strong></div>
                <div class="col-sm-8"><?= ucfirst(esc($filters['category'])) ?></div>
            </div>
        `;
        
        $('#fieldDetailsContent').html(content);
        $('#fieldDetailsModal').modal('show');
    }

    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('.card-body').prepend(alertHtml);
        
        // Auto-dismiss after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    }
    });
})();
</script>
<?= $this->endSection() ?>
