<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header with Actions -->
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('requests') ?>">Requests</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($request['request_number']) ?></li>
                </ol>
            </nav>
            <h1 class="h4 mb-0"><?= esc($request['request_name'] ?? 'Request Details') ?></h1>
            <p class="text-muted mb-0"><?= esc($request['description']) ?></p>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2 align-items-center">
                <!-- Convert to Work Order Primary Button -->
                <button type="button" class="btn btn-success" onclick="convertToWorkOrder(<?= $request['id'] ?>)">
                    Convert to Work Order
                </button>
                
                <!-- Actions Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="requestActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="requestActionsDropdown">
                        <li><a class="dropdown-item" href="#" onclick="editRequest(<?= $request['id'] ?>)"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                        <li><a class="dropdown-item" href="#" onclick="convertToEstimate(<?= $request['id'] ?>)"><i class="bi bi-calculator me-2"></i>Convert to Estimate</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="cancelRequest(<?= $request['id'] ?>)"><i class="bi bi-x-circle me-2"></i>Cancel</a></li>
                        <li><a class="dropdown-item" href="#" onclick="terminateRequest(<?= $request['id'] ?>)"><i class="bi bi-stop-circle me-2"></i>Terminate</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="downloadRequest(<?= $request['id'] ?>)"><i class="bi bi-download me-2"></i>Download</a></li>
                        <li><a class="dropdown-item" href="#" onclick="printRequest(<?= $request['id'] ?>)"><i class="bi bi-printer me-2"></i>Print</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Sidebar - Request Information Panel -->
        <div class="col-md-4 col-lg-3">
            <!-- Request Header -->
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <span class="text-white h3 mb-0"><i class="bi bi-clipboard-check"></i></span>
                        </div>
                    </div>
                    <h5 class="mb-1">Request <?= esc($request['request_number']) ?></h5>
                    
                    <!-- Status Badge -->
                    <div class="mb-3">
                        <?php
                        $statusClass = match ($request['status'] ?? 'pending') {
                            'pending' => 'bg-warning',
                            'in_progress' => 'bg-info',
                            'on_hold' => 'bg-secondary',
                            'completed' => 'bg-success',
                            default => 'bg-secondary',
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($request['status'] ?? 'pending') ?></span>
                    </div>
                    
                    <!-- Priority -->
                    <div class="mb-3">
                        <span class="badge bg-light text-dark">Priority: <?= ucfirst($request['priority'] ?? 'medium') ?></span>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Details</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($request['description'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-file-text"></i> <strong>Description</strong><br />
                        <span class="small text-muted"><?= esc($request['description']) ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($request['due_date'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-calendar-event"></i> <strong>Due Date</strong><br />
                        <span class="small text-muted"><?= date('M j, Y', strtotime($request['due_date'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-people"></i> Contact</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($request['client_name'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-building"></i> <strong>Company</strong><br />
                        <span class="small text-muted"><?= esc($request['client_name']) ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($request['contact_first_name']) && !empty($request['contact_last_name'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-person"></i> <strong>Contact</strong><br />
                        <span class="small text-muted"><?= esc($request['contact_first_name'] . ' ' . $request['contact_last_name']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Address</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($request['service_address'])): ?>
                    <div class="mb-3">
                        <i class="bi bi-house"></i> <strong>Service Address</strong><br />
                        <span class="text-muted small"><?= esc($request['service_address']) ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="mb-2">
                        <i class="bi bi-receipt"></i> <strong>Billing Address</strong><br />
                        <span class="text-muted small">Same as Service Address</span>
                    </div>
                </div>
            </div>
            
            <!-- Asset Details -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-gear"></i> Asset Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-cpu"></i> <strong>Asset</strong><br />
                        <span class="small text-muted">--</span>
                    </div>
                </div>
            </div>
            
            <!-- Preference -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-sliders"></i> Preference</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($request['preferred_date_1'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-calendar"></i> <strong>Preferred Date 1</strong><br />
                        <span class="small text-muted"><?= date('M j, Y', strtotime($request['preferred_date_1'])) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($request['preferred_time'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-clock"></i> <strong>Preferred Time</strong><br />
                        <span class="small text-muted"><?= esc($request['preferred_time']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($request['preference_note'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-sticky"></i> <strong>Preference Note</strong><br />
                        <span class="small text-muted"><?= esc($request['preference_note']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Owner Information -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-person"></i> <strong>Owner</strong><br />
                        <div class="small text-muted">
                            <?= esc($request['created_by_first_name'] . ' ' . $request['created_by_last_name']) ?><br />
                            boyington@protonmail.com
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <i class="bi bi-calendar-plus"></i> <strong>Created By</strong><br />
                        <div class="small text-muted">
                            <?= esc($request['created_by_first_name'] . ' ' . $request['created_by_last_name']) ?><br />
                            on <?= isset($request['created_at']) ? date('M j, Y g:i A (\G\M\T P)', strtotime($request['created_at'])) : 'Unknown' ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($request['updated_at'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-pencil-square"></i> <strong>Modified By</strong><br />
                        <div class="small text-muted">
                            <?= esc($request['created_by_first_name'] . ' ' . $request['created_by_last_name']) ?><br />
                            on <?= date('M j, Y g:i A (\G\M\T P)', strtotime($request['updated_at'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Main Content Area -->
        <div class="col-md-8 col-lg-9">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="requestTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="false">
                                <i class="bi bi-clock-history"></i> Timeline
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab" aria-controls="notes" aria-selected="true">
                                <i class="bi bi-journal-text"></i> Notes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="attachments-tab" data-bs-toggle="tab" data-bs-target="#attachments" type="button" role="tab" aria-controls="attachments" aria-selected="false">
                                <i class="bi bi-paperclip"></i> Attachments
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="related-list-tab" data-bs-toggle="tab" data-bs-target="#related-list" type="button" role="tab" aria-controls="related-list" aria-selected="false">
                                <i class="bi bi-list-ul"></i> Related List
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="requestTabsContent">
                        <!-- Timeline Tab Content -->
                        <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                            <h3>Timeline</h3>
                            <p class="text-muted">All the actions and events related to this Request are recorded in a chronological order.</p>
                            <div class="timeline-item">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-plus-circle text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Request created</h6>
                                        <p class="text-muted mb-0 small">01:00 PM , <?= esc($request['created_by_first_name'] . ' ' . $request['created_by_last_name']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Tab Content -->
                        <div class="tab-pane fade show active" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                            <h3>Notes</h3>
                            <p class="text-muted">Add notes and comments about this request.</p>
                            <!-- Notes content will go here -->
                        </div>

                        <!-- Attachments Tab Content -->
                        <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                            <h3>Attachments</h3>
                            <p class="text-muted">Upload and manage files related to this request.</p>
                            <!-- Attachments content will go here -->
                        </div>

                        <!-- Related List Tab Content -->
                        <div class="tab-pane fade" id="related-list" role="tabpanel" aria-labelledby="related-list-tab">
                            <h3>Related List</h3>
                            <p class="text-muted">View related work orders, estimates, and other connected records.</p>
                            <!-- Related list content will go here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?><?= $this->section('styles') ?>
<style>
/* Tab styling improvements - matching company view */
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: normal;
    border: none;
    border-bottom: 2px solid transparent;
    background: transparent;
}

.nav-tabs .nav-link:hover {
    color: #495057;
    background: #f8f9fa;
    border-color: transparent;
}

.nav-tabs .nav-link.active {
    color: #198754;
    font-weight: 600;
    border-color: #198754;
    background: transparent;
}

.nav-tabs .nav-link.active:hover {
    color: #198754;
    background: transparent;
}

/* Tab content styling */
.tab-content {
    min-height: 400px;
}

/* Card styling improvements */
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

/* Status badge styling */
.badge {
    font-size: 0.75em;
    font-weight: 500;
}

/* Timeline styling */
.timeline-item {
    border-left: 2px solid #e9ecef;
    padding-left: 1rem;
    margin-left: 1rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Define baseUrl for JavaScript
const baseUrl = '<?= base_url() ?>';

// Action functions
function convertToWorkOrder(requestId) {
    if (confirm('Convert this request to a work order?')) {
        // Redirect to work order creation with request data pre-filled
        window.location.href = `${baseUrl}/work-orders/create?from_request=${requestId}`;
    }
}

function editRequest(requestId) {
    // Open the request modal in edit mode
    // You can implement this by triggering the modal and pre-filling data
    window.location.href = `${baseUrl}/requests/edit/${requestId}`;
}

function convertToEstimate(requestId) {
    if (confirm('Convert this request to an estimate?')) {
        // Redirect to estimate creation with request data pre-filled
        window.location.href = `${baseUrl}/estimates/create?from_request=${requestId}`;
    }
}

function cancelRequest(requestId) {
    if (confirm('Are you sure you want to cancel this request?')) {
        fetch(`${baseUrl}/api/requests/${requestId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error cancelling request: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error cancelling request');
        });
    }
}

function terminateRequest(requestId) {
    if (confirm('Are you sure you want to terminate this request? This action cannot be undone.')) {
        fetch(`${baseUrl}/api/requests/${requestId}/terminate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error terminating request: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error terminating request');
        });
    }
}

function downloadRequest(requestId) {
    // Download request as PDF
    window.open(`${baseUrl}/requests/${requestId}/download`, '_blank');
}

function printRequest(requestId) {
    // Open print-friendly version in new window
    const printWindow = window.open(`${baseUrl}/requests/${requestId}/print`, '_blank');
    if (printWindow) {
        printWindow.onload = function() {
            printWindow.print();
        };
    }
}

// Debug: Log when DOM is loaded
console.log('DOM Content Loaded - checking for tabs');
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tabs container:', document.querySelector('#requestTabs'));
    console.log('Tab content:', document.querySelector('#requestTabsContent'));
    console.log('Main content column:', document.querySelector('.col-md-8.col-lg-9'));
});
</script>
<script src="<?= base_url('js/requests.js') ?>"></script>
<?= $this->endSection() ?>