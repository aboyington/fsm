<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header with Actions -->
    <div class="row mb-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('work-order-management/work-orders') ?>">Work Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($workOrder['work_order_number'] ?? 'WO-000') ?></li>
                </ol>
            </nav>
            <h1 class="h4 mb-0"><?= esc($workOrder['summary'] ?? 'Work Order Details') ?></h1>
            <p class="text-muted mb-0"><?= esc($workOrder['description'] ?? '') ?></p>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2 align-items-center">
                <!-- Schedule Service Appointment Primary Button -->
                <button type="button" class="btn btn-success" onclick="scheduleServiceAppointment(<?= $workOrder['id'] ?>)">
                    Schedule Service Appointment
                </button>
                
                <!-- Actions Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="workOrderActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="workOrderActionsDropdown">
                        <li><a class="dropdown-item" href="#" onclick="editWorkOrder(<?= $workOrder['id'] ?>)"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                        <li><a class="dropdown-item" href="#" onclick="generateInvoice(<?= $workOrder['id'] ?>)"><i class="bi bi-receipt me-2"></i>Generate Invoice</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="completeWorkOrder(<?= $workOrder['id'] ?>)"><i class="bi bi-check-circle me-2"></i>Complete</a></li>
                        <li><a class="dropdown-item" href="#" onclick="cancelWorkOrder(<?= $workOrder['id'] ?>)"><i class="bi bi-x-circle me-2"></i>Cancel</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="downloadWorkOrder(<?= $workOrder['id'] ?>)"><i class="bi bi-download me-2"></i>Download</a></li>
                        <li><a class="dropdown-item" href="#" onclick="printWorkOrder(<?= $workOrder['id'] ?>)"><i class="bi bi-printer me-2"></i>Print</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Sidebar - Work Order Information Panel -->
        <div class="col-md-4 col-lg-3">
            <!-- Work Order Header -->
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <span class="text-white h3 mb-0"><i class="bi bi-wrench"></i></span>
                        </div>
                    </div>
                    <h5 class="mb-1">Work Order <?= esc($workOrder['work_order_number'] ?? 'WO-000') ?></h5>
                    
                    <!-- Status Badge -->
                    <div class="mb-3">
                        <?php
                        $statusClass = match ($workOrder['status'] ?? 'pending') {
                            'pending' => 'bg-warning',
                            'scheduled' => 'bg-info',
                            'in_progress' => 'bg-primary',
                            'on_hold' => 'bg-secondary',
                            'completed' => 'bg-success',
                            'cancelled' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($workOrder['status'] ?? 'pending') ?></span>
                    </div>
                    
                    <!-- Priority -->
                    <div class="mb-3">
                        <span class="badge bg-light text-dark">Priority: <?= ucfirst($workOrder['priority'] ?? 'medium') ?></span>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Details</h6>
                </div>
                <div class="card-body">
                    <!-- Work Order Type -->
                    <div class="mb-2">
                        <i class="bi bi-tag"></i> <strong>Type</strong><br />
                        <span class="small text-muted"><?= esc($workOrder['work_order_type'] ?? 'Service Request') ?></span>
                    </div>

                    <!-- Created Date -->
                    <?php if (!empty($workOrder['created_at'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-calendar-plus"></i> <strong>Created Date</strong><br />
                        <span class="small text-muted"><?= date('M j, Y g:i A', strtotime($workOrder['created_at'])) ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Description -->
                    <div class="mb-2">
                        <i class="bi bi-file-text"></i> <strong>Description</strong><br />
                        <span class="small text-muted"><?= esc($workOrder['description'] ?? 'No description provided') ?></span>
                    </div>

                    <!-- Scheduled Date -->
                    <?php if (!empty($workOrder['scheduled_date'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-calendar-event"></i> <strong>Scheduled Date</strong><br />
                        <span class="small text-muted"><?= date('M j, Y', strtotime($workOrder['scheduled_date'])) ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Due Date -->
                    <?php if (!empty($workOrder['due_date'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-calendar-x"></i> <strong>Due Date</strong><br />
                        <span class="small text-muted"><?= date('M j, Y', strtotime($workOrder['due_date'])) ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Estimated Duration -->
                    <?php if (!empty($workOrder['estimated_duration'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-clock"></i> <strong>Estimated Duration</strong><br />
                        <span class="small text-muted"><?= esc($workOrder['estimated_duration']) ?> hours</span>
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
                    <?php if (!empty($workOrder['client_name'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-building"></i> <strong>Company</strong><br />
                        <span class="small text-muted"><?= esc($workOrder['client_name']) ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($workOrder['first_name']) && !empty($workOrder['last_name'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-person"></i> <strong>Contact</strong><br />
                        <span class="small text-muted"><?= esc($workOrder['first_name'] . ' ' . $workOrder['last_name']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($workOrder['contact_email'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-envelope"></i> <strong>Email</strong><br />
                        <span class="small text-muted">
                            <a href="mailto:<?= esc($workOrder['contact_email']) ?>" class="text-decoration-none"><?= esc($workOrder['contact_email']) ?></a>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($workOrder['phone'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone"></i> <strong>Phone</strong><br />
                        <span class="small text-muted">
                            <a href="tel:<?= esc($workOrder['phone']) ?>" class="text-decoration-none"><?= esc($workOrder['phone']) ?></a>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($workOrder['mobile'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-phone"></i> <strong>Mobile</strong><br />
                        <span class="small text-muted">
                            <a href="tel:<?= esc($workOrder['mobile']) ?>" class="text-decoration-none"><?= esc($workOrder['mobile']) ?></a>
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
                    <?php if (!empty($workOrder['service_address'])): ?>
                    <div class="mb-3">
                        <i class="bi bi-house"></i> <strong>Service Address</strong><br />
                        <span class="text-muted small"><?= esc($workOrder['service_address']) ?></span>
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
                        <span class="small text-muted"><?= esc($workOrder['asset_name'] ?? '--') ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Assigned Technician -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-person-badge"></i> Assigned Technician</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($workOrder['assigned_technician_name'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-person"></i> <strong>Technician</strong><br />
                        <span class="small text-muted"><?= esc($workOrder['assigned_technician_name']) ?></span>
                    </div>
                    <?php else: ?>
                    <div class="mb-2">
                        <i class="bi bi-person"></i> <strong>Technician</strong><br />
                        <span class="small text-muted">Not assigned</span>
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
                            <?= esc($workOrder['created_by_name'] ?? 'Unknown') ?><br />
                            <?= esc($workOrder['created_by_email'] ?? 'no-email@domain.com') ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <i class="bi bi-calendar-plus"></i> <strong>Created By</strong><br />
                        <div class="small text-muted">
                            <?= esc($workOrder['created_by_name'] ?? 'Unknown') ?><br />
                            on <?= isset($workOrder['created_at']) ? date('M j, Y g:i A (\\G\\M\\T P)', strtotime($workOrder['created_at'])) : 'Unknown' ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($workOrder['updated_at'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-pencil-square"></i> <strong>Modified By</strong><br />
                        <div class="small text-muted">
                            <?= esc($workOrder['updated_by_name'] ?? $workOrder['created_by_name'] ?? 'Unknown') ?><br />
                            on <?= date('M j, Y g:i A (\\G\\M\\T P)', strtotime($workOrder['updated_at'])) ?>
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
                    <ul class="nav nav-tabs card-header-tabs" id="workOrderTabs" role="tablist">
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
                            <button class="nav-link" id="service-parts-tab" data-bs-toggle="tab" data-bs-target="#service-parts" type="button" role="tab" aria-controls="service-parts" aria-selected="false">
                                <i class="bi bi-gear"></i> Service and Parts
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="service-appointments-tab" data-bs-toggle="tab" data-bs-target="#service-appointments" type="button" role="tab" aria-controls="service-appointments" aria-selected="false">
                                <i class="bi bi-calendar3"></i> Service Appointments
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
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab" aria-controls="invoices" aria-selected="false">
                                <i class="bi bi-receipt"></i> Invoices
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="workOrderTabsContent">
                        <!-- Timeline Tab Content -->
                        <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h3 class="mb-1">Timeline</h3>
                                    <p class="text-muted mb-0">All the actions and events related to this Work Order are recorded in a chronological order.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <select class="form-select form-select-sm" id="timelineFilter" onchange="changeTimelineFilter()" style="width: auto;">
                                        <option value="all">All Time</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="last_week">Last Week</option>
                                        <option value="last_month">Last Month</option>
                                        <option value="last_year">Last Year</option>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadTimeline(<?= $workOrder['id'] ?? 0 ?>)" title="Refresh Timeline">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Timeline Content - loaded dynamically -->
                            <div id="timelineContainer">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading timeline...</span>
                                    </div>
                                    <p class="text-muted mt-2">Loading timeline events...</p>
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
                                    the work order. Document decisions, changes, and communications 
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

                        <!-- Service and Parts Tab Content -->
                        <div class="tab-pane fade" id="service-parts" role="tabpanel" aria-labelledby="service-parts-tab">
                            <!-- Services Section -->
                            <div class="mb-5">
                                <h5 class="text-primary mb-3">Services</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 30%">Service Line Item Name</th>
                                                <th style="width: 25%">Service</th>
                                                <th style="width: 10%">Quantity</th>
                                                <th style="width: 12%">List Price</th>
                                                <th style="width: 12%">Tax Name</th>
                                                <th style="width: 15%">Line Item Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="servicesViewTableBody">
                                            <!-- Services data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Parts Section -->
                            <div class="mb-5">
                                <h5 class="text-primary mb-3">Parts</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 30%">Part Line Item Name</th>
                                                <th style="width: 25%">Part</th>
                                                <th style="width: 10%">Quantity</th>
                                                <th style="width: 12%">List Price</th>
                                                <th style="width: 12%">Tax Name</th>
                                                <th style="width: 15%">Line Item Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="partsViewTableBody">
                                            <!-- Parts data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Totals Section -->
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-sm mb-0">
                                                <tr>
                                                    <td class="text-end border-0">Sub Total:</td>
                                                    <td class="text-end border-0 fw-medium" id="viewSubTotalDisplay">CA$ 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end border-0">Tax Amount:</td>
                                                    <td class="text-end border-0 fw-medium" id="viewTaxAmountDisplay">CA$ 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end border-0">Discount:</td>
                                                    <td class="text-end border-0 fw-medium" id="viewDiscountDisplay">CA$ 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end border-0">Adjustment:</td>
                                                    <td class="text-end border-0 fw-medium" id="viewAdjustmentDisplay">CA$ 0.00</td>
                                                </tr>
                                                <tr class="table-active">
                                                    <td class="text-end fw-bold">Grand Total:</td>
                                                    <td class="text-end fw-bold" id="viewGrandTotalDisplay">CA$ 0.00</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Appointments Tab Content -->
                        <div class="tab-pane fade" id="service-appointments" role="tabpanel" aria-labelledby="service-appointments-tab">
                            <h3>Service Appointments</h3>
                            <p class="text-muted">Manage service appointments scheduled for this work order.</p>
                            
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-calendar3" style="font-size: 4rem; color: #dee2e6;"></i>
                                </div>
                                <h5 class="text-muted">No Service Appointments</h5>
                                <p class="text-muted">No service appointments have been scheduled for this work order yet.</p>
                                <button type="button" class="btn btn-success" onclick="scheduleServiceAppointment(<?= $workOrder['id'] ?>)">
                                    Schedule Service Appointment
                                </button>
                            </div>
                        </div>

                        <!-- Attachments Tab Content -->
                        <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                            <h3>Attachments</h3>
                            <p class="text-muted">Upload and manage files related to this work order.</p>

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
                                    Upload and manage files related to this work order. Drag and drop files or click the button below to upload.
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
                            <!-- Service Reports Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-primary">Service Reports</h6>
                                        <button type="button" class="btn btn-sm btn-success" onclick="createServiceReport(<?= $workOrder['id'] ?? 0 ?>)">
                                            <i class="bi bi-plus"></i> Create Service Report
                                        </button>
                                    </div>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center py-5">
                                            <i class="bi bi-clipboard-data display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted mb-2">No Records Found</h6>
                                            <p class="text-muted small mb-0">No service reports have been created for this work order yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service Appointments Section -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-primary">Service Appointments</h6>
                                        <button type="button" class="btn btn-sm btn-success" onclick="scheduleServiceAppointment(<?= $workOrder['id'] ?? 0 ?>)">
                                            <i class="bi bi-plus"></i> Schedule Appointment
                                        </button>
                                    </div>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center py-5">
                                            <i class="bi bi-calendar-event display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted mb-2">No Records Found</h6>
                                            <p class="text-muted small mb-0">No service appointments have been scheduled for this work order yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoices Tab Content -->
                        <div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                            <h3>Invoices</h3>
                            <p class="text-muted">Manage invoices generated for this work order.</p>
                            
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-receipt" style="font-size: 4rem; color: #dee2e6;"></i>
                                </div>
                                <h5 class="text-muted">No Invoices</h5>
                                <p class="text-muted">No invoices have been generated for this work order yet.</p>
                                <button type="button" class="btn btn-success" onclick="generateInvoice(<?= $workOrder['id'] ?>)">
                                    Generate Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Tab styling improvements - matching request view */
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
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Define baseUrl for JavaScript
const baseUrl = '<?= base_url() ?>';

// Work Order specific action functions
function scheduleServiceAppointment(workOrderId) {
    // Redirect to service appointment creation with work order data pre-filled
    window.location.href = `${baseUrl}/work-order-management/service-appointments/create?work_order=${workOrderId}`;
}

function editWorkOrder(workOrderId) {
    // Open the work order modal in edit mode
    window.location.href = `${baseUrl}/work-order-management/work-orders/edit/${workOrderId}`;
}

function generateInvoice(workOrderId) {
    if (confirm('Generate invoice for this work order?')) {
        // Redirect to invoice generation
        window.location.href = `${baseUrl}/invoices/create?work_order=${workOrderId}`;
    }
}

function completeWorkOrder(workOrderId) {
    if (confirm('Mark this work order as completed?')) {
        fetch(`${baseUrl}/work-order-management/work-orders/status/${workOrderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: 'completed' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error completing work order: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error completing work order');
        });
    }
}

function cancelWorkOrder(workOrderId) {
    if (confirm('Are you sure you want to cancel this work order?')) {
        fetch(`${baseUrl}/work-order-management/work-orders/status/${workOrderId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: 'cancelled' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error cancelling work order: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error cancelling work order');
        });
    }
}

function downloadWorkOrder(workOrderId) {
    // Download work order as PDF
    window.open(`${baseUrl}/work-order-management/work-orders/${workOrderId}/download`, '_blank');
}

function printWorkOrder(workOrderId) {
    // Open print-friendly version in new window
    const printWindow = window.open(`${baseUrl}/work-order-management/work-orders/${workOrderId}/print`, '_blank');
    if (printWindow) {
        printWindow.onload = function() {
            printWindow.print();
        };
    }
}

// Related list actions
function createServiceReport(workOrderId) {
    // Redirect to service report creation with work order data pre-filled
    window.location.href = `${baseUrl}/work-order-management/service-reports/create?work_order=${workOrderId}`;
}

// Notes functionality (reused from requests view)
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

// Attachments functionality is now handled in work_orders.js

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Load existing data on page load
    const workOrderId = <?= $workOrder['id'] ?? 0 ?>;
    if (workOrderId) {
        // Initialize timeline loading when timeline tab becomes active
        const timelineTab = document.getElementById('timeline-tab');
        if (timelineTab) {
            timelineTab.addEventListener('shown.bs.tab', function() {
                if (typeof loadTimeline === 'function') {
                    loadTimeline(workOrderId);
                }
            });
        }
        
        // Initialize services and parts loading when Service and Parts tab becomes active
        const servicePartsTab = document.getElementById('service-parts-tab');
        if (servicePartsTab) {
            servicePartsTab.addEventListener('shown.bs.tab', function() {
                loadWorkOrderServicesAndPartsView(workOrderId);
            });
            
            // Load services and parts if Service and Parts tab is active on page load
            if (servicePartsTab.classList.contains('active')) {
                loadWorkOrderServicesAndPartsView(workOrderId);
            }
        }
    }
});
</script>

<!-- Include work orders JavaScript for notes functionality -->
<script src="<?= base_url('js/work_orders.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>
