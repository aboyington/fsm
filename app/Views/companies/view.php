<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Company Information Panel -->
        <div class="col-md-4 col-lg-3">
            <!-- Company Header -->
            <div class="card mb-3">
                <div class="card-body text-center">
                    <!-- Company Avatar/Icon -->
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <span class="text-white h3 mb-0"><i class="bi bi-building"></i></span>
                        </div>
                    </div>
                    
                    <!-- Company Name -->
                    <h5 class="mb-1"><?= esc($company['client_name']) ?></h5>
                    <div class="text-muted small mb-2"><?= esc($company['contact_person'] ?? '') ?></div>
                    
                    <!-- Status Badge -->
                    <div class="mb-3">
                        <?php 
                        $statusClass = match($company['status'] ?? 'active') {
                            'active' => 'bg-success',
                            'inactive' => 'bg-secondary',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($company['status'] ?? 'active') ?></span>
                    </div>
                    
                    <!-- Quick Contact Info -->
                    <?php if (!empty($company['phone'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone"></i> <span class="text-muted"><?= esc($company['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($company['email'])): ?>
                    <div class="mb-3">
                        <i class="bi bi-envelope"></i> <span class="text-muted"><?= esc($company['email']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Edit Button -->
                    <button class="btn btn-primary btn-sm w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editCompanyModal"
                            onclick="loadCompanyForEdit(<?= $company['id'] ?>)">
                        <i class="bi bi-pencil"></i> Edit Company
                    </button>
                </div>
            </div>
            
            <!-- Details Section -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Details</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($company['website'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-globe"></i> <strong>Website</strong><br>
                        <a href="<?= esc($company['website']) ?>" target="_blank" class="text-decoration-none small">
                            <?= esc($company['website']) ?> <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-2">
                        <i class="bi bi-tag"></i> <strong>Account Type</strong><br>
                        <span class="small text-muted"><?= esc($company['company_type'] ?? 'Customer') ?></span>
                    </div>
                    
                    <?php if (!empty($company['phone'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone"></i> <strong>Phone</strong><br>
                        <span class="small text-muted"><?= esc($company['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($company['email'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-envelope"></i> <strong>Email</strong><br>
                        <span class="small text-muted"><?= esc($company['email']) ?></span>
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
                    <?php if (!empty($company['address']) || !empty($company['city']) || !empty($company['state']) || !empty($company['country'])): ?>
                    <div class="mb-3">
                        <i class="bi bi-house"></i> <strong>Service Address</strong><br>
                        <div class="text-muted small">
                            <?php if (!empty($company['address'])): ?>
                                <?= esc($company['address']) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($company['city']) || !empty($company['state'])): ?>
                                <?= esc($company['city']) ?><?= !empty($company['state']) ? ', ' . esc($company['state']) : '' ?><br>
                            <?php endif; ?>
                            <?php if (!empty($company['zip_code'])): ?>
                                <?= esc($company['zip_code']) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($company['country'])): ?>
                                <?= esc($company['country']) ?>
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
                            on <?= isset($company['created_at']) ? date('M j, Y g:i A (T)', strtotime($company['created_at'])) : 'Unknown' ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($company['updated_at'])): ?>
                    <div class="mb-2">
                        <i class="bi bi-pencil-square"></i> <strong>Modified By</strong><br>
                        <div class="small text-muted">
                            Anthony Boyington<br>
                            on <?= date('M j, Y g:i A (T)', strtotime($company['updated_at'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-8 col-lg-9">
            <!-- Company Navigation Tabs -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="companyTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="false">
                                <i class="bi bi-clock-history"></i> Timeline
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">
                                <i class="bi bi-bar-chart"></i> Dashboard
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="true">
                                <i class="bi bi-people"></i> Contacts
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
                            <button class="nav-link" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="billing" aria-selected="false">
                                <i class="bi bi-receipt"></i> Billing
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
                    <div class="tab-content" id="companyTabsContent">
                        <!-- Contacts Tab Content -->
                        <div class="tab-pane fade show active" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
                            <h3>Contacts</h3>
                            <?php if (!empty($contacts)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Job Title</th>
                                                <th>Status</th>
                                                <th>Primary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($contacts as $contact): ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?= base_url('customers/contacts/view/' . $contact['id']) ?>" class="text-decoration-none">
                                                            <strong><?= esc($contact['first_name'] . ' ' . $contact['last_name']); ?></strong>
                                                        </a>
                                                    </td>
                                                    <td><?= esc($contact['email'] ?? ''); ?></td>
                                                    <td><?= esc($contact['phone'] ?? ''); ?></td>
                                                    <td><?= esc($contact['job_title'] ?? ''); ?></td>
                                                    <td>
                                                        <?php 
                                                        $statusClass = match($contact['status']) {
                                                            'active' => 'bg-success',
                                                            'inactive' => 'bg-secondary',
                                                            default => 'bg-secondary'
                                                        };
                                                        ?>
                                                        <span class="badge <?= $statusClass ?>"><?= ucfirst($contact['status']); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($contact['is_primary']): ?>
                                                            <span class="badge bg-primary">Primary</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    No contacts found for this company
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Placeholder content for other tabs -->
                        <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                            <h3>Timeline</h3>
                            <p>Timeline events will be added here.</p>
                        </div>
                        <div class="tab-pane fade" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                            <h3>Dashboard</h3>
                            <p>Dashboard content will be added here.</p>
                        </div>
                        <div class="tab-pane fade" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                            <h3>Addresses</h3>
                            <p>Address content will be added here.</p>
                        </div>
                        <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                            <h3>Notes</h3>
                            <p>Notes content will be added here.</p>
                        </div>
                        <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                            <h3>Attachments</h3>
                            <p>Attachments content will be added here.</p>
                        </div>
                        <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                            <h3>Billing</h3>
                            <p>Billing content will be added here.</p>
                        </div>
                        <div class="tab-pane fade" id="related-list" role="tabpanel" aria-labelledby="related-list-tab">
                            <h3>Related List</h3>
                            <p>Related content will be added here.</p>
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
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function someFunctionRelatedToCompany() {
    // JavaScript related to company view
}
</script>
<?= $this->endSection() ?>
