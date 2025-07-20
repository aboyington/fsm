<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Contact Information Panel -->
        <div class="col-md-4 col-lg-3">
            <!-- Contact Header -->
            <div class="card mb-3">
                <div class="card-body text-center">
                    <!-- Contact Avatar -->
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <span class="text-white h3 mb-0"><?= strtoupper(substr($contact['first_name'], 0, 1) . substr($contact['last_name'], 0, 1)) ?></span>
                        </div>
                    </div>
                    
                    <!-- Contact Name -->
                    <h5 class="mb-1"><?= esc($contact['first_name'] . ' ' . $contact['last_name']) ?></h5>
                    <div class="text-muted small mb-2"><?= esc($contact['email']) ?></div>
                    
                    <!-- Status Badge -->
                    <div class="mb-3">
                        <?php 
                        $statusClass = match($contact['status']) {
                            'active' => 'bg-success',
                            'inactive' => 'bg-secondary',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($contact['status']) ?></span>
                    </div>
                    
                    <!-- Phone -->
                    <?php if (!empty($contact['phone'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone"></i> <span class="text-muted"><?= esc($contact['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Mobile -->
                    <?php if (!empty($contact['mobile'])): ?>
                    <div class="mb-3">
                        <i class="bi bi-phone"></i> <span class="text-muted"><?= esc($contact['mobile']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Edit Button -->
                    <button class="btn btn-primary btn-sm w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editContactModal"
                            onclick="loadContactForEdit(<?= $contact['id'] ?>)">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                </div>
            </div>
            
            <!-- Details Section -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-envelope"></i> <strong>Email</strong><br>
                        <span class="small text-muted"><?= esc($contact['email']) ?></span>
                    </div>
                    
                    <?php if (!empty($contact['phone'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone"></i> <strong>Phone</strong><br>
                        <span class="small text-muted"><?= esc($contact['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($contact['mobile'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-phone"></i> <strong>Mobile</strong><br>
                        <span class="small text-muted"><?= esc($contact['mobile']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Company Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-building"></i> Company</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($contact['company_name'])): ?>
                    <div class="mb-2">
                        <a href="<?= base_url('customers/companies/view/' . $contact['company_id']) ?>" class="text-decoration-none">
                            <i class="bi bi-building"></i> <?= esc($contact['company_name']) ?>
                        </a>
                    </div>
                    <?php if (!empty($contact['company_website'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-globe"></i> <a href="<?= esc($contact['company_website']) ?>" target="_blank" class="text-decoration-none small">
                            <?= esc($contact['company_website']) ?> <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($contact['company_phone'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone"></i> <span class="small text-muted"><?= esc($contact['company_phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php else: ?>
                    <p class="text-muted small">No company associated</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Address Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Address</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($contact['address']) || !empty($contact['city']) || !empty($contact['state']) || !empty($contact['country'])): ?>
                    <div class="mb-3">
                        <i class="bi bi-house"></i> <strong>Service Address</strong><br>
                        <div class="text-muted small">
                            <?php if (!empty($contact['address'])): ?>
                                <?= esc($contact['address']) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($contact['city']) || !empty($contact['state'])): ?>
                                <?= esc($contact['city']) ?><?= !empty($contact['state']) ? ', ' . esc($contact['state']) : '' ?><br>
                            <?php endif; ?>
                            <?php if (!empty($contact['zip_code'])): ?>
                                <?= esc($contact['zip_code']) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($contact['country'])): ?>
                                <?= esc($contact['country']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <i class="bi bi-receipt"></i> <strong>Billing Address</strong><br>
                        <div class="text-muted small">
                            Same as Service Address
                        </div>
                    </div>
                    <?php else: ?>
                    <p class="text-muted small">No address information</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Tax Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-calculator"></i> Tax</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-percent"></i> <strong>Tax Rule</strong><br>
                        <span class="small text-muted">--</span>
                    </div>
                </div>
            </div>
            
            <!-- Invoice Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> Invoice Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-exclamation-circle"></i> <strong>Record not linked yet</strong>
                        <i class="bi bi-link-45deg"></i>
                    </div>
                </div>
            </div>
            
            <!-- Owner Information -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-person"></i> <strong>Owner</strong><br>
                        <div class="small text-muted">
                            Anthony Boyington<br>
                            boyington@protonmail.com
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <i class="bi bi-calendar-plus"></i> <strong>Created By</strong><br>
                        <div class="small text-muted">
                            Anthony Boyington<br>
                            on <?= isset($contact['created_at']) ? date('M j, Y g:i A (T)', strtotime($contact['created_at'])) : 'Unknown' ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($contact['updated_at'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-pencil-square"></i> <strong>Modified By</strong><br>
                        <div class="small text-muted">
                            Anthony Boyington<br>
                            on <?= date('M j, Y g:i A (T)', strtotime($contact['updated_at'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-8 col-lg-9">
            <!-- Navigation Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="contactTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="true">
                                <i class="bi bi-clock-history"></i> Timeline
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">
                                <i class="bi bi-bar-chart"></i> Dashboard
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="addresses-tab" data-bs-toggle="tab" data-bs-target="#addresses" type="button" role="tab" aria-controls="addresses" aria-selected="false">
                                <i class="bi bi-geo-alt"></i> Addresses
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab" aria-controls="notes" aria-selected="false">
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
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="billing" aria-selected="false">
                                <i class="bi bi-receipt"></i> Billing
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content" id="contactTabsContent">
                        <!-- Timeline Tab -->
                        <div class="tab-pane fade show active" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">All the actions and events related to this Contact are recorded in a chronological order.</h6>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-funnel"></i> Show all updates
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">All Time</a></li>
                                        <li><a class="dropdown-item" href="#">Last 7 days</a></li>
                                        <li><a class="dropdown-item" href="#">Last 30 days</a></li>
                                        <li><a class="dropdown-item" href="#">Last 4 months</a></li>
                                        <li><a class="dropdown-item" href="#">Last 12 months</a></li>
                                    </ul>
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-clock"></i> All Time
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">All Time</a></li>
                                        <li><a class="dropdown-item" href="#">Last 7 days</a></li>
                                        <li><a class="dropdown-item" href="#">Last 30 days</a></li>
                                        <li><a class="dropdown-item" href="#">Last 4 months</a></li>
                                        <li><a class="dropdown-item" href="#">Last 12 months</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="timeline-date-header">
                                <strong>Today - <?= date('M j, Y') ?></strong>
                            </div>
                            
                            <!-- Sample Timeline Items -->
                            <div class="timeline-item">
                                <div class="timeline-icon bg-warning">
                                    <i class="bi bi-pencil text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-time">03:00 PM</div>
                                    <div class="timeline-text">
                                        <strong>Contact details updated</strong><br>
                                        <small class="text-muted">Anthony Boyington</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success">
                                    <i class="bi bi-plus text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-time">02:47 PM</div>
                                    <div class="timeline-text">
                                        <strong>Contact created</strong><br>
                                        <small class="text-muted">Anthony Boyington</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dashboard Tab -->
                        <div class="tab-pane fade" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Monitor and analyze the contact's data in real-time. Glean insights that will empower you to enhance and optimize your services for this contact, as well as identify strategies to maximize business opportunities.</h6>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-calendar"></i> This Month
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">Last 3 months</a></li>
                                        <li><a class="dropdown-item" href="#">Last 6 months</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Dashboard Cards -->
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Open Requests</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Open Estimates</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Open Work Orders</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Open Service Appointments</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Overdue Work Orders</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Overdue Service Appointments</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Overdue Invoices</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">0</h5>
                                            <p class="card-text small text-muted">Amount Generated from Work Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Addresses Tab -->
                        <div class="tab-pane fade" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Addresses</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> Add Address
                                </button>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Service Address</h6>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <?php if (!empty($contact['address']) || !empty($contact['city']) || !empty($contact['state']) || !empty($contact['country'])): ?>
                                            <div class="text-muted small">
                                                <?php if (!empty($contact['address'])): ?>
                                                    <?= esc($contact['address']) ?><br>
                                                <?php endif; ?>
                                                <?php if (!empty($contact['city']) || !empty($contact['state'])): ?>
                                                    <?= esc($contact['city']) ?><?= !empty($contact['state']) ? ', ' . esc($contact['state']) : '' ?><br>
                                                <?php endif; ?>
                                                <?php if (!empty($contact['zip_code'])): ?>
                                                    <?= esc($contact['zip_code']) ?><br>
                                                <?php endif; ?>
                                                <?php if (!empty($contact['country'])): ?>
                                                    <?= esc($contact['country']) ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php else: ?>
                                            <p class="text-muted">No address information</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><i class="bi bi-receipt"></i> Billing Address</h6>
                                            <button class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Same as Service Address</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes Tab -->
                        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Notes</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> Add Note
                                </button>
                            </div>
                            
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-journal-text fs-1 d-block mb-2"></i>
                                No notes available yet
                            </div>
                        </div>
                        
                        <!-- Attachments Tab -->
                        <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Attachments</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-upload"></i> Upload Attachment
                                </button>
                            </div>
                            
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-paperclip fs-1 d-block mb-2"></i>
                                No attachments available yet
                            </div>
                        </div>
                        
                        <!-- Related List Tab -->
                        <div class="tab-pane fade" id="related-list" role="tabpanel" aria-labelledby="related-list-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Related List</h6>
                            </div>
                            
                            <!-- Estimates Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-primary">Estimates</h6>
                                        <button type="button" class="btn btn-sm btn-success" onclick="createEstimateForContact(<?= $contact['id'] ?? 0 ?>)">
                                            <i class="bi bi-plus"></i> Create Estimate
                                        </button>
                                    </div>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center py-5">
                                            <i class="bi bi-calculator display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted mb-2">No Estimates Found</h6>
                                            <p class="text-muted small mb-0">No estimates have been created for this contact yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Work Orders Section -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-primary">Work Orders</h6>
                                        <button type="button" class="btn btn-sm btn-success" onclick="createWorkOrderForContact(<?= $contact['id'] ?? 0 ?>)">
                                            <i class="bi bi-plus"></i> Create Work Order
                                        </button>
                                    </div>
                                    <div class="card border-0 bg-light">
                                        <div class="card-body text-center py-5">
                                            <i class="bi bi-clipboard-check display-4 text-muted mb-3"></i>
                                            <h6 class="text-muted mb-2">No Work Orders Found</h6>
                                            <p class="text-muted small mb-0">No work orders have been created for this contact yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Billing Tab -->
                        <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Billing</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> Create Invoice
                                </button>
                            </div>
                            
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                                No billing information available yet
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
/* Tab styling improvements */
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

/* Timeline Styles */
.timeline-date-header {
    margin: 20px 0 15px 0;
    font-weight: 600;
    color: #495057;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    margin-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 15px;
    top: 35px;
    bottom: -20px;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    z-index: 1;
}

.timeline-content {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-time {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 14px;
    line-height: 1.4;
}

.timeline-text strong {
    color: #495057;
}

.timeline-text small {
    display: block;
    margin-top: 8px;
    color: #6c757d;
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

/* Responsive improvements */
@media (max-width: 768px) {
    .timeline-item {
        padding-left: 40px;
    }
    
    .timeline-icon {
        width: 25px;
        height: 25px;
        font-size: 10px;
    }
    
    .timeline-content {
        padding: 10px 12px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function loadContactForEdit(contactId) {
    // This function will be implemented when the edit modal is created
    console.log('Loading contact for edit:', contactId);
}

function createEstimateForContact(contactId) {
    // Redirect to estimate creation with contact pre-selected
    const baseUrl = '<?= base_url() ?>';
    window.location.href = `${baseUrl}/work-order-management/estimates/create?contact_id=${contactId}`;
}

function createWorkOrderForContact(contactId) {
    // Redirect to work order creation with contact pre-selected
    const baseUrl = '<?= base_url() ?>';
    window.location.href = `${baseUrl}/work-order-management/work-orders/create?contact_id=${contactId}`;
}
</script>
<?= $this->endSection() ?>

