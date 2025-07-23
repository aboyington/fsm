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
                    
                    <?php if (!empty($request['contact_email'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-envelope"></i> <strong>Email</strong><br />
                        <span class="small text-muted">
                            <a href="mailto:<?= esc($request['contact_email']) ?>" class="text-decoration-none"><?= esc($request['contact_email']) ?></a>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($request['contact_phone'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone"></i> <strong>Phone</strong><br />
                        <span class="small text-muted">
                            <a href="tel:<?= esc($request['contact_phone']) ?>" class="text-decoration-none"><?= esc($request['contact_phone']) ?></a>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($request['contact_mobile'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-phone"></i> <strong>Mobile</strong><br />
                        <span class="small text-muted">
                            <a href="tel:<?= esc($request['contact_mobile']) ?>" class="text-decoration-none"><?= esc($request['contact_mobile']) ?></a>
                        </span>
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
                            <!-- Notes Empty State -->
                            <div class="text-center py-5" id="notesEmptyState">
                                <div class="mb-4">
                                    <div class="d-inline-block position-relative">
                                        <!-- Main notebook icon -->
                                        <div class="bg-light border rounded d-inline-block p-3" style="width: 120px; height: 90px;">
                                            <div class="row g-1 h-100">
                                                <div class="col-12">
                                                    <div class="bg-secondary rounded" style="height: 6px;"></div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="bg-secondary rounded" style="height: 6px;"></div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="bg-secondary rounded" style="height: 6px;"></div>
                                                </div>
                                                <div class="col-8">
                                                    <div class="bg-secondary rounded" style="height: 6px;"></div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="bg-light rounded" style="height: 6px; border: 2px dashed #dee2e6;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Floating document with plus icon -->
                                        <div class="position-absolute" style="top: -10px; right: -15px;">
                                            <div class="bg-white border rounded shadow-sm p-2" style="width: 40px; height: 30px;">
                                                <div class="row g-1 h-100">
                                                    <div class="col-12">
                                                        <div class="bg-light rounded" style="height: 3px;"></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="bg-light rounded" style="height: 3px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Plus icon -->
                                            <div class="position-absolute bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; top: -8px; right: -8px;">
                                                <i class="bi bi-plus text-white" style="font-size: 14px;"></i>
                                            </div>
                                        </div>
                                        <!-- Floating circles -->
                                        <div class="position-absolute bg-light rounded-circle" style="width: 12px; height: 12px; top: -20px; left: 20px; opacity: 0.6;"></div>
                                        <div class="position-absolute bg-light rounded-circle" style="width: 8px; height: 8px; top: -10px; left: 60px; opacity: 0.4;"></div>
                                        <div class="position-absolute bg-light rounded-circle" style="width: 10px; height: 10px; top: 20px; right: 40px; opacity: 0.5;"></div>
                                        <div class="position-absolute bg-light rounded-circle" style="width: 6px; height: 6px; top: 60px; right: 20px; opacity: 0.3;"></div>
                                        <div class="position-absolute bg-light rounded-circle" style="width: 14px; height: 14px; bottom: -15px; left: 40px; opacity: 0.4;"></div>
                                        <div class="position-absolute bg-light rounded-circle" style="width: 8px; height: 8px; bottom: 10px; left: 10px; opacity: 0.3;"></div>
                                    </div>
                                </div>
                                
                                <h5 class="mb-3">Notes</h5>
                                
                                <p class="text-muted mb-4 px-4">
                                    Add additional information you want to include regarding the task at hand. 
                                    Include attachments that provide context for understanding the specifics of 
                                    the service request. Document decisions, changes, and communications 
                                    ensuring everyone is on the same page.
                                </p>
                                
                                <button type="button" class="btn btn-success" onclick="showAddNoteForm()">
                                    Add Notes
                                </button>
                            </div>
                            
                            <!-- Notes List (hidden initially) -->
                            <div id="notesList" class="d-none">
                                <!-- Notes will be loaded here dynamically -->
                            </div>
                            
                            <!-- Add Note Form (hidden initially) -->
                            <div id="addNoteForm" class="d-none">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Add Note</h6>
                                        <button type="button" class="btn-close" onclick="hideAddNoteForm()"></button>
                                    </div>
                                    <div class="card-body">
                                        <form id="noteForm">
                                            <div class="mb-3">
                                                <label for="noteContent" class="form-label">Note Content</label>
                                                <textarea class="form-control" id="noteContent" name="content" rows="4" placeholder="Enter your note here..."></textarea>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-success">Save Note</button>
                                                <button type="button" class="btn btn-outline-secondary" onclick="hideAddNoteForm()">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attachments Tab Content -->
                        <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                            <h3>Attachments</h3>
                            <p class="text-muted">Upload and manage files related to this request.</p>
<!-- Attachments Empty State and Upload Functionality -->
<div class="text-center py-5" id="attachmentsEmptyState">
    <div class="mb-4">
        <div class="d-inline-block position-relative">
            <!-- Attachment icon -->
            <div class="bg-light border rounded d-inline-block p-3" style="width: 120px; height: 90px;">
                <div class="row g-1 h-100">
                    <div class="col-12">
                        <div class="bg-secondary rounded" style="height: 6px;"></div>
                    </div>
                    <div class="col-12">
                        <div class="bg-secondary rounded" style="height: 6px;"></div>
                    </div>
                    <div class="col-12">
                        <div class="bg-secondary rounded" style="height: 6px;"></div>
                    </div>
                    <div class="col-8">
                        <div class="bg-secondary rounded" style="height: 6px;"></div>
                    </div>
                    <div class="col-12">
                        <div class="bg-light rounded" style="height: 6px; border: 2px dashed #dee2e6;"></div>
                    </div>
                </div>
            </div>
            <!-- Floating document with plus icon -->
            <div class="position-absolute" style="top: -10px; right: -15px;">
                <div class="bg-white border rounded shadow-sm p-2" style="width: 40px; height: 30px;">
                    <div class="row g-1 h-100">
                        <div class="col-12">
                            <div class="bg-light rounded" style="height: 3px;"></div>
                        </div>
                        <div class="col-12">
                            <div class="bg-light rounded" style="height: 3px;"></div>
                        </div>
                    </div>
                </div>
                <!-- Plus icon -->
                <div class="position-absolute bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; top: -8px; right: -8px;">
                    <i class="bi bi-plus text-white" style="font-size: 14px;"></i>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Attachments</h5>

    <p class="text-muted mb-4 px-4">
        Upload and manage files related to this request. Drag and drop files or click the button below to upload.
    </p>

    <button type="button" class="btn btn-success" onclick="triggerFileUpload()">
        Add Attachment
    </button>
    <input type="file" id="fileUpload" multiple style="display: none;" onchange="handleFileUpload()"/>
</div>

<div id="uploadedFilesList" class="d-none">
    <!-- List of uploaded files will be rendered here -->
</div>
                        </div>

                        <!-- Related List Tab Content -->
                        <div class="tab-pane fade" id="related-list" role="tabpanel" aria-labelledby="related-list-tab">
                            <!-- Estimates Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-primary">Estimates</h6>
                                        <button type="button" class="btn btn-sm btn-success" onclick="createEstimateFromRequest(<?= $request['id'] ?? 0 ?>)">
                                            <i class="bi bi-plus"></i> Create Estimate
                                        </button>
                                    </div>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center py-5">
                                            <i class="bi bi-calculator display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted mb-2">No Records Found</h6>
                                            <p class="text-muted small mb-0">No estimates have been created for this request yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Work Orders Section -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-primary">Work Orders</h6>
                                        <button type="button" class="btn btn-sm btn-success" onclick="createWorkOrderFromRequest(<?= $request['id'] ?? 0 ?>)">
                                            <i class="bi bi-plus"></i> Create Work Order
                                        </button>
                                    </div>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center py-5">
                                            <i class="bi bi-clipboard-check display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted mb-2">No Records Found</h6>
                                            <p class="text-muted small mb-0">No work orders have been created for this request yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Work Order Modal -->
<?= $this->include('work_orders/_modal') ?>

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
    position: relative;
}

.timeline-item.timeline-item-connected::after {
    content: '';
    position: absolute;
    left: 19px;
    top: 40px;
    bottom: -16px;
    width: 2px;
    background-color: #e9ecef;
    z-index: 1;
}

.timeline-container {
    position: relative;
}

.timeline-items {
    position: relative;
}

/* Attachment drag-and-drop styling */
.drop-zone {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.drop-zone:hover {
    border-color: #198754;
    background-color: rgba(25, 135, 84, 0.05);
}

.drop-zone.dragover {
    border-color: #198754;
    background-color: rgba(25, 135, 84, 0.1);
    transform: scale(1.02);
}

.attachment-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.attachment-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Compact attachment list styling */
.compact-attachment-item {
    transition: background-color 0.2s ease;
    border-left: none !important;
    border-right: none !important;
}

.compact-attachment-item:hover {
    background-color: rgba(25, 135, 84, 0.05);
}

.compact-attachment-item:first-child {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.compact-attachment-item:last-child {
    border-bottom-left-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.compact-attachment-item .btn {
    font-size: 0.875rem;
}

/* Pagination styling for attachments */
.pagination-sm .page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Responsive adjustments for attachment info */
@media (max-width: 768px) {
    .compact-attachment-item .d-flex.flex-wrap {
        flex-direction: column;
        gap: 0.25rem !important;
    }
    
    .compact-attachment-item .d-flex.gap-1 {
        flex-direction: column;
        gap: 0.25rem !important;
    }
    
    .compact-attachment-item .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Define baseUrl for JavaScript
const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('js/work_orders.js') ?>"></script>
<script>
// Action functions
function convertToWorkOrder(requestId) {
    if (confirm('Convert this request to a work order?')) {
        // Disable the convert button and show loading state
        const convertBtn = document.querySelector(`button[onclick="convertToWorkOrder(${requestId})"]`);
        if (convertBtn) {
            convertBtn.disabled = true;
            const originalText = convertBtn.textContent;
            convertBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Converting...';
            convertBtn.setAttribute('data-original-text', originalText);
        }
        
        // Call the work order creation function with conversion flag
        createWorkOrderFromRequest(requestId);
    }
}

function editRequest(requestId) {
    // Open the request modal in edit mode
    // You can implement this by triggering the modal and pre-filling data
    window.location.href = `${baseUrl}requests/edit/${requestId}`;
}

function convertToEstimate(requestId) {
    if (confirm('Convert this request to an estimate?')) {
        // Redirect to estimate creation with request data pre-filled
        window.location.href = `${baseUrl}estimates/create?from_request=${requestId}`;
    }
}

// Functions for Related List empty state actions
function createEstimateFromRequest(requestId) {
    // Redirect to estimate creation with request data pre-filled
    window.location.href = `${baseUrl}work-order-management/estimates/create?from_request=${requestId}`;
}

function createWorkOrderFromRequest(requestId) {
    // Open work order modal with request data pre-filled
    openWorkOrderModalForConversion(requestId);
}

// Function to open work order modal with request data pre-filled
function openWorkOrderModalForConversion(requestId) {
    // Show loading state
    const modal = document.getElementById('createWorkOrderModal');
    const modalTitle = modal.querySelector('.modal-title');
    const originalTitle = modalTitle.innerHTML;
    modalTitle.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    
    // Fetch request data from the backend
    fetch(`${baseUrl}work-order-management/work-orders/convert-from-request/${requestId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Populate modal with request data
            populateWorkOrderModalFromRequest(data.requestData);
            
            // Update modal title to indicate conversion
            modalTitle.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Convert Request to Work Order';
            
            // Show the modal
            const workOrderModalInstance = new bootstrap.Modal(modal);
            workOrderModalInstance.show();
        } else {
            throw new Error(data.message || 'Failed to load request data');
        }
    })
    .catch(error => {
        console.error('Error loading request data:', error);
        modalTitle.innerHTML = originalTitle;
        showAlert('danger', 'Error loading request data: ' + error.message);
        
        // Restore convert button state on error
        const convertBtn = document.querySelector('button[disabled][data-original-text]');
        if (convertBtn) {
            convertBtn.disabled = false;
            const originalText = convertBtn.getAttribute('data-original-text');
            convertBtn.textContent = originalText;
            convertBtn.removeAttribute('data-original-text');
        }
    });
}

// Function to populate work order modal form with request data
function populateWorkOrderModalFromRequest(requestData) {
    const modal = document.getElementById('createWorkOrderModal');
    
    // Fill in the form fields with request data
    if (requestData.request_name) {
        const nameField = modal.querySelector('[name="summary"]'); // This matches the modal field
        if (nameField) nameField.value = requestData.request_name;
    }
    
    if (requestData.description) {
        const descField = modal.querySelector('[name="description"]');
        if (descField) descField.value = requestData.description;
    }
    
    if (requestData.priority) {
        const priorityField = modal.querySelector('[name="priority"]');
        if (priorityField) priorityField.value = requestData.priority;
    }
    
    if (requestData.due_date) {
        const dueDateField = modal.querySelector('[name="due_date"]');
        if (dueDateField) dueDateField.value = requestData.due_date;
    }
    
    // Client information
    if (requestData.client_id) {
        const clientField = modal.querySelector('[name="company_id"]'); // Changed from client_id to company_id
        if (clientField) clientField.value = requestData.client_id;
    }
    
    // Contact information - set the contact dropdown
    if (requestData.contact_id) {
        const contactField = modal.querySelector('[name="contact_id"]');
        if (contactField) contactField.value = requestData.contact_id;
    }
    
    // Fill individual contact detail fields
    if (requestData.contact_email) {
        const contactEmailField = modal.querySelector('[name="email"]'); // Changed from contact_email to email
        if (contactEmailField) contactEmailField.value = requestData.contact_email;
    }
    
    if (requestData.contact_phone) {
        const contactPhoneField = modal.querySelector('[name="phone"]'); // Changed from contact_phone to phone
        if (contactPhoneField) contactPhoneField.value = requestData.contact_phone;
    }
    
    if (requestData.contact_mobile) {
        const contactMobileField = modal.querySelector('[name="mobile"]'); // Changed from contact_mobile to mobile
        if (contactMobileField) contactMobileField.value = requestData.contact_mobile;
    }
    
    // Address information
    if (requestData.service_address) {
        const serviceAddressField = modal.querySelector('[name="service_address"]');
        if (serviceAddressField) serviceAddressField.value = requestData.service_address;
    }
    
    // Set a hidden field or data attribute to track this is a conversion
    const form = modal.querySelector('form');
    if (form) {
        // Add hidden field for request ID
        let requestIdField = form.querySelector('input[name="source_request_id"]');
        if (!requestIdField) {
            requestIdField = document.createElement('input');
            requestIdField.type = 'hidden';
            requestIdField.name = 'source_request_id';
            form.appendChild(requestIdField);
        }
        requestIdField.value = requestData.id;
        
        // Mark as conversion
        let conversionField = form.querySelector('input[name="is_conversion"]');
        if (!conversionField) {
            conversionField = document.createElement('input');
            conversionField.type = 'hidden';
            conversionField.name = 'is_conversion';
            form.appendChild(conversionField);
        }
        conversionField.value = '1';
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

// Notes functionality
function showAddNoteForm() {
    document.getElementById('notesEmptyState').classList.add('d-none');
    document.getElementById('addNoteForm').classList.remove('d-none');
    document.getElementById('noteContent').focus();
}

function hideAddNoteForm() {
    document.getElementById('addNoteForm').classList.add('d-none');
    document.getElementById('notesEmptyState').classList.remove('d-none');
    document.getElementById('noteContent').value = '';
}

function saveNote(requestId) {
    const content = document.getElementById('noteContent').value.trim();
    
    if (!content) {
        alert('Please enter a note before saving.');
        return;
    }
    
    fetch(`${baseUrl}/api/requests/${requestId}/notes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideAddNoteForm();
            loadNotes(requestId);
            showAlert('success', 'Note added successfully!');
        } else {
            showAlert('danger', 'Error saving note: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Error saving note');
    });
}

function loadNotes(requestId) {
    fetch(`${baseUrl}/api/requests/${requestId}/notes`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.notes.length > 0) {
            document.getElementById('notesEmptyState').classList.add('d-none');
            document.getElementById('notesList').classList.remove('d-none');
            renderNotes(data.notes);
        }
    })
    .catch(error => {
        console.error('Error loading notes:', error);
    });
}

// Global pagination state for notes
let notesPagination = {
    currentPage: 1,
    itemsPerPage: 10,
    totalItems: 0,
    totalPages: 0
};

function renderNotes(notes) {
    const notesList = document.getElementById('notesList');
    
    // Update pagination info
    notesPagination.totalItems = notes.length;
    notesPagination.totalPages = Math.ceil(notes.length / notesPagination.itemsPerPage);
    
    // Calculate start and end indices for current page
    const startIndex = (notesPagination.currentPage - 1) * notesPagination.itemsPerPage;
    const endIndex = startIndex + notesPagination.itemsPerPage;
    const currentPageNotes = notes.slice(startIndex, endIndex);
    
    let notesHtml = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Notes (${notes.length})</h5>
            <button type="button" class="btn btn-success btn-sm" onclick="showAddNoteForm()">
                <i class="bi bi-plus"></i> Add Note
            </button>
        </div>
    `;
    
    // Add pagination controls if needed
    if (notesPagination.totalPages > 1) {
        notesHtml += renderNotesPagination();
    }
    
    currentPageNotes.forEach(note => {
        const createdDate = new Date(note.created_at).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        
        const isPinned = parseInt(note.is_pinned) === 1;
        const cardClass = isPinned ? 'card mb-3 border-warning' : 'card mb-3';
        const pinIcon = isPinned ? 'bi-pin-fill text-warning' : 'bi-pin';
        const pinText = isPinned ? 'Unpin' : 'Pin to Top';
        
        notesHtml += `
            <div class="${cardClass}" id="note-${note.id}">
                ${isPinned ? '<div class="card-header bg-warning bg-opacity-10 py-2"><small class="text-warning fw-medium"><i class="bi bi-pin-fill me-1"></i>Pinned Note</small></div>' : ''}
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="bi bi-person text-white" style="font-size: 14px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">${escapeHtml(note.created_by_name || 'Unknown User')}</h6>
                                <small class="text-muted">${createdDate}</small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link btn-sm text-muted" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="toggleNotePin(${note.id})"><i class="bi ${pinIcon} me-2"></i>${pinText}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="editNote(${note.id})"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteNote(${note.id})"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="note-content-${note.id}">
                        <p class="mb-0">${escapeHtml(note.content).replace(/\n/g, '<br>')}</p>
                    </div>
                    <div id="note-edit-form-${note.id}" class="d-none">
                        <textarea class="form-control mb-2" id="edit-content-${note.id}" rows="3">${escapeHtml(note.content)}</textarea>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success btn-sm" onclick="saveEditedNote(${note.id})">Save</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelEditNote(${note.id})">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    // Add bottom pagination if needed
    if (notesPagination.totalPages > 1) {
        notesHtml += '<div class="mt-3">' + renderNotesPagination() + '</div>';
    }
    
    notesList.innerHTML = notesHtml;
}

function renderNotesPagination() {
    if (notesPagination.totalPages <= 1) return '';
    
    let paginationHtml = '<nav aria-label="Notes pagination">';
    paginationHtml += '<ul class="pagination pagination-sm justify-content-center mb-0">';
    
    // Previous button
    const prevDisabled = notesPagination.currentPage === 1 ? 'disabled' : '';
    paginationHtml += `
        <li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" onclick="changeNotesPage(${notesPagination.currentPage - 1})" 
               ${prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Page numbers (show max 5 pages)
    let startPage = Math.max(1, notesPagination.currentPage - 2);
    let endPage = Math.min(notesPagination.totalPages, startPage + 4);
    
    // Adjust start if we're near the end
    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }
    
    // Add first page and ellipsis if needed
    if (startPage > 1) {
        paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="changeNotesPage(1)">1</a></li>';
        if (startPage > 2) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    // Page number links
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === notesPagination.currentPage ? 'active' : '';
        paginationHtml += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="changeNotesPage(${i})">${i}</a>
            </li>
        `;
    }
    
    // Add last page and ellipsis if needed
    if (endPage < notesPagination.totalPages) {
        if (endPage < notesPagination.totalPages - 1) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changeNotesPage(${notesPagination.totalPages})">${notesPagination.totalPages}</a></li>`;
    }
    
    // Next button
    const nextDisabled = notesPagination.currentPage === notesPagination.totalPages ? 'disabled' : '';
    paginationHtml += `
        <li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" onclick="changeNotesPage(${notesPagination.currentPage + 1})" 
               ${nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `;
    
    paginationHtml += '</ul>';
    paginationHtml += '</nav>';
    
    return paginationHtml;
}

function changeNotesPage(newPage) {
    if (newPage < 1 || newPage > notesPagination.totalPages) return;
    
    notesPagination.currentPage = newPage;
    const requestId = <?= $request['id'] ?? 0 ?>;
    loadNotes(requestId);
}

function toggleNotePin(noteId) {
    const requestId = <?= $request['id'] ?? 0 ?>;
    
    fetch(`${baseUrl}/api/requests/${requestId}/notes/${noteId}/toggle-pin`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotes(requestId);
            showAlert('success', data.message);
        } else {
            showAlert('danger', 'Error toggling pin: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Error toggling pin status');
    });
}

function editNote(noteId) {
    // Hide the note content and show the edit form
    document.getElementById(`note-content-${noteId}`).classList.add('d-none');
    document.getElementById(`note-edit-form-${noteId}`).classList.remove('d-none');
    
    // Focus on the textarea
    document.getElementById(`edit-content-${noteId}`).focus();
}

function cancelEditNote(noteId) {
    // Show the note content and hide the edit form
    document.getElementById(`note-content-${noteId}`).classList.remove('d-none');
    document.getElementById(`note-edit-form-${noteId}`).classList.add('d-none');
}

function saveEditedNote(noteId) {
    const requestId = <?= $request['id'] ?? 0 ?>;
    const content = document.getElementById(`edit-content-${noteId}`).value.trim();
    
    if (!content) {
        alert('Please enter note content before saving.');
        return;
    }
    
    fetch(`${baseUrl}/api/requests/${requestId}/notes/${noteId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadNotes(requestId);
            showAlert('success', 'Note updated successfully!');
        } else {
            showAlert('danger', 'Error updating note: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Error updating note');
    });
}

function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
        const requestId = <?= $request['id'] ?? 0 ?>;
        
        fetch(`${baseUrl}/api/requests/${requestId}/notes/${noteId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotes(requestId);
                showAlert('success', 'Note deleted successfully!');
            } else {
                showAlert('danger', 'Error deleting note: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Error deleting note');
        });
    }
}

function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-dismissible');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the tab content
    const tabContent = document.querySelector('#notes .tab-pane');
    if (tabContent) {
        tabContent.insertBefore(alertDiv, tabContent.firstChild);
        
        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Debug: Log when DOM is loaded
console.log('DOM Content Loaded - checking for tabs');
document.addEventListener('DOMContentLoaded', function() {
    console.log('Tabs container:', document.querySelector('#requestTabs'));
    console.log('Tab content:', document.querySelector('#requestTabsContent'));
    console.log('Main content column:', document.querySelector('.col-md-8.col-lg-9'));
    
    // Handle note form submission
    const noteForm = document.getElementById('noteForm');
    if (noteForm) {
        noteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const requestId = <?= $request['id'] ?? 0 ?>;
            saveNote(requestId);
        });
    }
    
    // Load existing notes and timeline on page load
    const requestId = <?= $request['id'] ?? 0 ?>;
    if (requestId) {
        loadNotes(requestId);
        loadAttachments(requestId);
        loadTimeline(requestId);
    }
    
    // Load timeline when tab is clicked
    document.getElementById('timeline-tab').addEventListener('click', function() {
        if (requestId) {
            loadTimeline(requestId);
        }
    });
    
    // Load related list when tab is clicked - DISABLED since we now use static content
    // document.getElementById('related-list-tab').addEventListener('click', function() {
    //     if (requestId) {
    //         loadRelatedList(requestId);
    //     }
    // });
});

// Attachment functionality
function triggerFileUpload() {
    document.getElementById('fileUpload').click();
}

function handleFileUpload() {
    const fileInput = document.getElementById('fileUpload');
    const files = fileInput.files;
    
    if (files.length === 0) {
        return;
    }
    
    const requestId = <?= $request['id'] ?? 0 ?>;
    const requestNumber = '<?= esc($request['request_number'] ?? 'REQ-000') ?>';
    
    // Create FormData for file upload
    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    formData.append('request_id', requestId);
    formData.append('request_number', requestNumber);
    
    // Show upload progress
    showUploadProgress();
    
    fetch(`${baseUrl}/api/requests/${requestId}/attachments/upload`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideUploadProgress();
        if (data.success) {
            loadAttachments(requestId);
            showAttachmentAlert('success', 'Files uploaded successfully!');
            fileInput.value = ''; // Clear file input
        } else {
            showAttachmentAlert('danger', 'Error uploading files: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        hideUploadProgress();
        console.error('Error:', error);
        showAttachmentAlert('danger', 'Error uploading files');
    });
}

function loadAttachments(requestId) {
    fetch(`${baseUrl}/api/requests/${requestId}/attachments`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.attachments.length > 0) {
            document.getElementById('attachmentsEmptyState').classList.add('d-none');
            document.getElementById('uploadedFilesList').classList.remove('d-none');
            renderAttachments(data.attachments);
        } else {
            document.getElementById('attachmentsEmptyState').classList.remove('d-none');
            document.getElementById('uploadedFilesList').classList.add('d-none');
        }
    })
    .catch(error => {
        console.error('Error loading attachments:', error);
    });
}

// Global pagination state
let attachmentsPagination = {
    currentPage: 1,
    itemsPerPage: 20,
    totalItems: 0,
    totalPages: 0
};

function renderAttachments(attachments) {
    const attachmentsList = document.getElementById('uploadedFilesList');
    
    // Update pagination info
    attachmentsPagination.totalItems = attachments.length;
    attachmentsPagination.totalPages = Math.ceil(attachments.length / attachmentsPagination.itemsPerPage);
    
    // Calculate start and end indices for current page
    const startIndex = (attachmentsPagination.currentPage - 1) * attachmentsPagination.itemsPerPage;
    const endIndex = startIndex + attachmentsPagination.itemsPerPage;
    const currentPageAttachments = attachments.slice(startIndex, endIndex);
    
    let attachmentsHtml = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Attachments (${attachments.length})</h5>
            <button type="button" class="btn btn-success btn-sm" onclick="triggerFileUpload()">
                <i class="bi bi-plus"></i> Add Attachment
            </button>
        </div>
    `;
    
    // Add pagination controls if needed
    if (attachmentsPagination.totalPages > 1) {
        attachmentsHtml += renderAttachmentPagination();
    }
    
    // Use a compact list layout instead of cards
    attachmentsHtml += '<div class="list-group list-group-flush border rounded">';
    
    currentPageAttachments.forEach((attachment, index) => {
        const uploadedDate = new Date(attachment.created_at).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
        
        const fileExtension = attachment.file_name.split('.').pop().toLowerCase();
        const fileIcon = getFileIcon(fileExtension);
        const fileSize = formatFileSize(attachment.file_size);
        
        attachmentsHtml += `
            <div class="list-group-item d-flex align-items-center py-2 compact-attachment-item">
                <div class="me-3">
                    <i class="bi ${fileIcon}" style="font-size: 1.5rem; color: #6c757d;"></i>
                </div>
                <div class="flex-grow-1 min-width-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="min-width-0 flex-grow-1 me-3">
                            <h6 class="mb-0 text-truncate fw-medium" title="${escapeHtml(attachment.file_name)}">
                                ${escapeHtml(attachment.file_name)}
                            </h6>
                            <div class="d-flex flex-wrap gap-3 mt-1">
                                <small class="text-muted">
                                    <i class="bi bi-hdd me-1"></i>${fileSize}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>${uploadedDate}
                                </small>
                                <small class="text-muted">
                                    <i class="bi bi-person me-1"></i>${escapeHtml(attachment.uploaded_by_name || 'Unknown')}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex gap-1 flex-shrink-0">
                            ${isPreviewable(attachment.mime_type) ? `
                                <button type="button" class="btn btn-outline-info btn-sm px-2" onclick="previewAttachment(${attachment.id}, '${escapeHtml(attachment.original_name)}', '${attachment.mime_type}')" title="Preview">
                                    <i class="bi bi-eye"></i>
                                </button>
                            ` : ''}
                            <button type="button" class="btn btn-outline-primary btn-sm px-2" onclick="downloadAttachment(${attachment.id})" title="Download">
                                <i class="bi bi-download"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm px-2" onclick="deleteAttachment(${attachment.id})" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    attachmentsHtml += '</div>';
    
    // Add bottom pagination if needed
    if (attachmentsPagination.totalPages > 1) {
        attachmentsHtml += '<div class="mt-3">' + renderAttachmentPagination() + '</div>';
    }
    
    attachmentsList.innerHTML = attachmentsHtml;
}

function renderAttachmentPagination() {
    if (attachmentsPagination.totalPages <= 1) return '';
    
    let paginationHtml = '<nav aria-label="Attachments pagination">';
    paginationHtml += '<ul class="pagination pagination-sm justify-content-center mb-0">';
    
    // Previous button
    const prevDisabled = attachmentsPagination.currentPage === 1 ? 'disabled' : '';
    paginationHtml += `
        <li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" onclick="changeAttachmentsPage(${attachmentsPagination.currentPage - 1})" 
               ${prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Page numbers (show max 5 pages)
    let startPage = Math.max(1, attachmentsPagination.currentPage - 2);
    let endPage = Math.min(attachmentsPagination.totalPages, startPage + 4);
    
    // Adjust start if we're near the end
    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }
    
    // Add first page and ellipsis if needed
    if (startPage > 1) {
        paginationHtml += '<li class="page-item"><a class="page-link" href="#" onclick="changeAttachmentsPage(1)">1</a></li>';
        if (startPage > 2) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    // Page number links
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === attachmentsPagination.currentPage ? 'active' : '';
        paginationHtml += `
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="changeAttachmentsPage(${i})">${i}</a>
            </li>
        `;
    }
    
    // Add last page and ellipsis if needed
    if (endPage < attachmentsPagination.totalPages) {
        if (endPage < attachmentsPagination.totalPages - 1) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="changeAttachmentsPage(${attachmentsPagination.totalPages})">${attachmentsPagination.totalPages}</a></li>`;
    }
    
    // Next button
    const nextDisabled = attachmentsPagination.currentPage === attachmentsPagination.totalPages ? 'disabled' : '';
    paginationHtml += `
        <li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" onclick="changeAttachmentsPage(${attachmentsPagination.currentPage + 1})" 
               ${nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `;
    
    paginationHtml += '</ul>';
    paginationHtml += '</nav>';
    
    return paginationHtml;
}

function changeAttachmentsPage(newPage) {
    if (newPage < 1 || newPage > attachmentsPagination.totalPages) return;
    
    attachmentsPagination.currentPage = newPage;
    const requestId = <?= $request['id'] ?? 0 ?>;
    loadAttachments(requestId);
}

function getFileIcon(extension) {
    const iconMap = {
        // Images
        'jpg': 'bi-file-earmark-image',
        'jpeg': 'bi-file-earmark-image',
        'png': 'bi-file-earmark-image',
        'gif': 'bi-file-earmark-image',
        'bmp': 'bi-file-earmark-image',
        'webp': 'bi-file-earmark-image',
        
        // Documents
        'pdf': 'bi-file-earmark-pdf',
        'doc': 'bi-file-earmark-word',
        'docx': 'bi-file-earmark-word',
        'xls': 'bi-file-earmark-excel',
        'xlsx': 'bi-file-earmark-excel',
        'ppt': 'bi-file-earmark-ppt',
        'pptx': 'bi-file-earmark-ppt',
        'txt': 'bi-file-earmark-text',
        
        // Archives
        'zip': 'bi-file-earmark-zip',
        'rar': 'bi-file-earmark-zip',
        '7z': 'bi-file-earmark-zip',
        
        // Code
        'html': 'bi-file-earmark-code',
        'css': 'bi-file-earmark-code',
        'js': 'bi-file-earmark-code',
        'php': 'bi-file-earmark-code',
        'py': 'bi-file-earmark-code',
        
        // Default
        'default': 'bi-file-earmark'
    };
    
    return iconMap[extension] || iconMap['default'];
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadAttachment(attachmentId) {
    const requestId = <?= $request['id'] ?? 0 ?>;
    window.open(`${baseUrl}/api/requests/${requestId}/attachments/${attachmentId}/download`, '_blank');
}

function deleteAttachment(attachmentId) {
    if (confirm('Are you sure you want to delete this attachment? This action cannot be undone.')) {
        const requestId = <?= $request['id'] ?? 0 ?>;
        
        fetch(`${baseUrl}/api/requests/${requestId}/attachments/${attachmentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadAttachments(requestId);
                showAttachmentAlert('success', 'Attachment deleted successfully!');
            } else {
                showAttachmentAlert('danger', 'Error deleting attachment: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAttachmentAlert('danger', 'Error deleting attachment');
        });
    }
}

function showUploadProgress() {
    // Add a simple progress indicator
    const progressHtml = `
        <div id="uploadProgress" class="text-center py-3">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Uploading...</span>
            </div>
            <p class="mt-2 text-muted">Uploading files...</p>
        </div>
    `;
    
    const attachmentsTab = document.getElementById('attachments');
    const existingProgress = document.getElementById('uploadProgress');
    if (existingProgress) {
        existingProgress.remove();
    }
    attachmentsTab.insertAdjacentHTML('afterbegin', progressHtml);
}

function hideUploadProgress() {
    const progressDiv = document.getElementById('uploadProgress');
    if (progressDiv) {
        progressDiv.remove();
    }
}

function showAttachmentAlert(type, message) {
    // Remove existing alerts in attachments tab
    const existingAlerts = document.querySelectorAll('#attachments .alert-dismissible');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the attachments tab
    const attachmentsTab = document.getElementById('attachments');
    if (attachmentsTab) {
        attachmentsTab.insertBefore(alertDiv, attachmentsTab.firstChild);
        
        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }
    }
}

// Related List functionality - DISABLED since we now use static content
/*
function loadRelatedList(requestId) {
    const relatedListContainer = document.querySelector('#related-list');
    if (!relatedListContainer) return;

    // Show loading spinner
    relatedListContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading related data...</span>
            </div>
            <p class="mt-2 text-muted">Loading related estimates and work orders...</p>
        </div>
    `;

    // Fetch related list data
    fetch(`${baseUrl}/request/related-list/${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderRelatedList(data.estimates, data.workOrders);
            } else {
                relatedListContainer.innerHTML = '<p class="text-danger">Failed to load related list: ' + (data.message || 'Unknown error') + '</p>';
            }
        })
        .catch(error => {
            console.error('Error loading related list:', error);
            relatedListContainer.innerHTML = '<p class="text-danger">Error loading related list data</p>';
        });
}
*/

// DISABLED: Old renderRelatedList function since we now use static content
/*
function renderRelatedList(estimates, workOrders) {
    const relatedListContainer = document.querySelector('#related-list');
    const requestId = <?= $request['id'] ?? 0 ?>;
    
    let listHtml = '';

    // Always show both Estimates and Work Orders sections
    // Estimates Section
    listHtml += `
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-primary">Estimates</h6>
                    <button type="button" class="btn btn-sm btn-success" onclick="createEstimate(${requestId})">
                        <i class="bi bi-plus"></i> Create Estimate
                    </button>
                </div>
    `;
    
    if (estimates.length === 0) {
        listHtml += `
                <div class="card border-0 bg-light">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-calculator display-4 text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">No Records Found</h6>
                        <p class="text-muted small mb-0">No estimates have been created for this request yet.</p>
                    </div>
                </div>
        `;
    } else {
        listHtml += `
                <div class="card border-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            ${estimates.map(est => `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${escapeHtml(est.name || 'Estimate #' + est.id)}</strong>
                                        <br><small class="text-muted">Status: ${escapeHtml(est.status || 'Draft')}</small>
                                    </div>
                                    <span class="badge bg-primary rounded-pill">$${est.total_cost || '0.00'}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                </div>
        `;
    }
    
    listHtml += `
            </div>
        </div>
    `;
    
    // Work Orders Section
    listHtml += `
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-primary">Work Orders</h6>
                    <button type="button" class="btn btn-sm btn-success" onclick="createWorkOrder(${requestId})">
                        <i class="bi bi-plus"></i> Create Work Order
                    </button>
                </div>
    `;
    
    if (workOrders.length === 0) {
        listHtml += `
                <div class="card border-0 bg-light">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-clipboard-check display-4 text-muted mb-3"></i>
                        <h6 class="text-muted mb-2">No Records Found</h6>
                        <p class="text-muted small mb-0">No work orders have been created for this request yet.</p>
                    </div>
                </div>
        `;
    } else {
        listHtml += `
                <div class="card border-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            ${workOrders.map(wo => `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${escapeHtml(wo.name || 'Work Order #' + wo.id)}</strong>
                                        <br><small class="text-muted">Status: ${escapeHtml(wo.status || 'Pending')}</small>
                                    </div>
                                    <span class="badge bg-info rounded-pill">${escapeHtml(wo.priority || 'Medium')}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                </div>
        `;
    }
    
    listHtml += `
            </div>
        </div>
    `;

    relatedListContainer.innerHTML = listHtml;
}
*/

// Timeline functionality
function loadTimeline(requestId, filter = 'all') {
    const timelineContainer = document.querySelector('#timeline');
    if (!timelineContainer) return;
    
    // Show loading spinner
    timelineContainer.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Timeline</h3>
            <div class="d-flex gap-2">
                <select id="timelineFilter" class="form-select form-select-sm" onchange="changeTimelineFilter()"
                        style="width: auto;">
                    <option value="all" ${filter === 'all' ? 'selected' : ''}>All Time</option>
                    <option value="today" ${filter === 'today' ? 'selected' : ''}>Today</option>
                    <option value="yesterday" ${filter === 'yesterday' ? 'selected' : ''}>Yesterday</option>
                    <option value="last_week" ${filter === 'last_week' ? 'selected' : ''}>Last Week</option>
                    <option value="last_month" ${filter === 'last_month' ? 'selected' : ''}>Last Month</option>
                    <option value="last_year" ${filter === 'last_year' ? 'selected' : ''}>Last Year</option>
                </select>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="loadTimeline(${requestId})" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
        <p class="text-muted mb-4">All the actions and events related to this Request are recorded in chronological order.</p>
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading timeline...</span>
            </div>
            <p class="mt-2 text-muted">Loading timeline events...</p>
        </div>
    `;
    
    // Fetch timeline data
    fetch(`${baseUrl}/work-order-management/request/timeline/${requestId}?filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTimeline(data.timeline, requestId, filter);
            } else {
                showTimelineError('Failed to load timeline: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error loading timeline:', error);
            showTimelineError('Error loading timeline data');
        });
}

function changeTimelineFilter() {
    const filter = document.getElementById('timelineFilter').value;
    const requestId = <?= $request['id'] ?? 0 ?>;
    loadTimeline(requestId, filter);
}

function renderTimeline(timelineData, requestId, filter) {
    const timelineContainer = document.querySelector('#timeline');
    
    let timelineHtml = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Timeline</h3>
            <div class="d-flex gap-2">
                <select id="timelineFilter" class="form-select form-select-sm" onchange="changeTimelineFilter()"
                        style="width: auto;">
                    <option value="all" ${filter === 'all' ? 'selected' : ''}>All Time</option>
                    <option value="today" ${filter === 'today' ? 'selected' : ''}>Today</option>
                    <option value="yesterday" ${filter === 'yesterday' ? 'selected' : ''}>Yesterday</option>
                    <option value="last_week" ${filter === 'last_week' ? 'selected' : ''}>Last Week</option>
                    <option value="last_month" ${filter === 'last_month' ? 'selected' : ''}>Last Month</option>
                    <option value="last_year" ${filter === 'last_year' ? 'selected' : ''}>Last Year</option>
                </select>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="loadTimeline(${requestId})" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
        <p class="text-muted mb-4">All the actions and events related to this Request are recorded in chronological order.</p>
    `;
    
    if (timelineData.length === 0) {
        // Empty state
        timelineHtml += `
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-clock-history" style="font-size: 4rem; color: #dee2e6;"></i>
                </div>
                <h5 class="text-muted">No Timeline Events</h5>
                <p class="text-muted">No events found for the selected time period.</p>
            </div>
        `;
    } else {
        // Timeline events
        timelineHtml += `
            <div class="timeline-container">
                <div class="timeline-items">
        `;
        
        timelineData.forEach((event, index) => {
            const eventIcon = getTimelineEventIcon(event.event_type);
            const eventColor = getTimelineEventColor(event.event_type);
            
            timelineHtml += `
                <div class="timeline-item ${index < timelineData.length - 1 ? 'timeline-item-connected' : ''}">
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-${eventColor} rounded-circle p-2 me-3 flex-shrink-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi ${eventIcon} text-white" style="font-size: 1rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 fw-semibold">${escapeHtml(event.title || 'Event')}</h6>
                                <small class="text-muted">${event.formatted_date || event.created_at}</small>
                            </div>
                            <p class="text-muted mb-2 small">${escapeHtml(event.description || 'No description available')}</p>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-person me-1"></i>
                                <span>${escapeHtml(event.user_name || 'System')}</span>
                                ${event.module ? `<span class="ms-2">• ${escapeHtml(event.module).toUpperCase()}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        timelineHtml += `
                </div>
            </div>
        `;
    }
    
    timelineContainer.innerHTML = timelineHtml;
}

function getTimelineEventIcon(eventType) {
    const iconMap = {
        'request_created': 'bi-plus-circle',
        'request_updated': 'bi-pencil-square',
        'request_deleted': 'bi-trash',
        'request_status_changed': 'bi-arrow-left-right',
        'request_priority_changed': 'bi-exclamation-triangle',
        'request_assigned': 'bi-person-check',
        'request_converted': 'bi-arrow-right',
        'request_note_added': 'bi-journal-plus',
        'request_note_updated': 'bi-journal-text',
        'request_note_deleted': 'bi-journal-minus',
        'request_attachment_added': 'bi-paperclip',
        'request_attachment_deleted': 'bi-trash',
        'default': 'bi-clock-history'
    };
    return iconMap[eventType] || iconMap['default'];
}

function getTimelineEventColor(eventType) {
    const colorMap = {
        'request_created': 'success',
        'request_updated': 'primary',
        'request_deleted': 'danger',
        'request_status_changed': 'info',
        'request_priority_changed': 'warning',
        'request_assigned': 'primary',
        'request_converted': 'success',
        'request_note_added': 'info',
        'request_note_updated': 'primary',
        'request_note_deleted': 'danger',
        'request_attachment_added': 'info',
        'request_attachment_deleted': 'danger',
        'default': 'secondary'
    };
    return colorMap[eventType] || colorMap['default'];
}

function showTimelineError(message) {
    const timelineContainer = document.querySelector('#timeline');
    timelineContainer.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Timeline</h3>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="loadTimeline(${<?= $request['id'] ?? 0 ?>})" title="Retry">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        <p class="text-muted mb-4">All the actions and events related to this Request are recorded in chronological order.</p>
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            ${message}
        </div>
    `;
}

// Preview functionality
function isPreviewable(mimeType) {
    const previewableTypes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
        'application/pdf'
    ];
    return previewableTypes.includes(mimeType);
}

function previewAttachment(attachmentId, fileName, mimeType) {
    const requestId = <?= $request['id'] ?? 0 ?>;
    const previewUrl = `${baseUrl}/api/requests/${requestId}/attachments/${attachmentId}/preview`;
    
    // Create modal HTML
    const modalId = 'attachmentPreviewModal';
    
    // Remove existing modal if it exists
    const existingModal = document.getElementById(modalId);
    if (existingModal) {
        existingModal.remove();
    }
    
    let modalContent = '';
    
    if (mimeType.startsWith('image/')) {
        modalContent = `
            <div class="text-center">
                <img src="${previewUrl}" class="img-fluid" alt="${escapeHtml(fileName)}" style="max-height: 70vh; max-width: 100%;">
            </div>
        `;
    } else if (mimeType === 'application/pdf') {
        modalContent = `
            <div class="text-center">
                <iframe src="${previewUrl}" style="width: 100%; height: 70vh; border: none;"></iframe>
                <p class="mt-2 text-muted">If the PDF doesn't display, <a href="${previewUrl}" target="_blank">click here to view it in a new tab</a>.</p>
            </div>
        `;
    }
    
    const modalHtml = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="${modalId}Label" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="${modalId}Label">
                            <i class="bi bi-eye me-2"></i>Preview: ${escapeHtml(fileName)}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${modalContent}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" onclick="downloadAttachment(${attachmentId})">
                            <i class="bi bi-download me-1"></i>Download
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to the page
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();
    
    // Clean up modal when hidden
    document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}
</script>
<?= $this->endSection() ?>
