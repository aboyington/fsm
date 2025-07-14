<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Account Registry</h4>
    <p class="text-muted">Manage client accounts, service codes, and account sequences for your field service operations. Organize your clients and services with auto-generated account codes for streamlined billing and record keeping.</p>
    
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="accountRegistryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $tab === 'clients' ? 'active' : '' ?>" 
                    id="clients-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#clients" 
                    type="button" 
                    role="tab">
                <i class="bi bi-people me-1"></i>
                Clients
                <?php if (!empty($clientStats)): ?>
                    <span class="badge bg-secondary ms-1"><?= $clientStats['total'] ?? 0 ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $tab === 'services' ? 'active' : '' ?>" 
                    id="services-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#services" 
                    type="button" 
                    role="tab">
                <i class="bi bi-gear me-1"></i>
                Service Registry
                <?php if (!empty($serviceStats)): ?>
                    <span class="badge bg-secondary ms-1"><?= $serviceStats['total'] ?? 0 ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $tab === 'sequences' ? 'active' : '' ?>" 
                    id="sequences-tab" 
                    data-bs-toggle="tab" 
                    data-bs-target="#sequences" 
                    type="button" 
                    role="tab">
                <i class="bi bi-list-ol me-1"></i>
                Sequences
                <?php if (!empty($sequences)): ?>
                    <span class="badge bg-secondary ms-1"><?= count($sequences) ?></span>
                <?php endif; ?>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="accountRegistryTabContent">
        <!-- Clients Tab -->
        <div class="tab-pane fade <?= $tab === 'clients' ? 'show active' : '' ?>" 
             id="clients" 
             role="tabpanel" 
             aria-labelledby="clients-tab">
            
            <!-- Clients Header Controls -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select" style="width: auto;" id="clientStatusFilter">
                        <option value="active" <?= $filters['status'] == 'active' ? 'selected' : '' ?>>Active Clients</option>
                        <option value="inactive" <?= $filters['status'] == 'inactive' ? 'selected' : '' ?>>Inactive Clients</option>
                        <option value="all" <?= $filters['status'] == 'all' ? 'selected' : '' ?>>All Clients</option>
                    </select>
                    <input type="search" class="form-control" style="width: 300px;" placeholder="Search clients..." id="clientSearch" value="<?= esc($filters['search']) ?>">
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addClientModal">
                    <i class="bi bi-plus"></i> New Client
                </button>
            </div>

            <!-- Clients Table -->
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Client Code</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clients)): ?>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <?= strtoupper(substr($client['client_name'], 0, 1)) ?>
                                                </div>
                                                <span class="ms-2"><?= esc($client['client_name']) ?></span>
                                            </div>
                                        </td>
                                        <td><code><?= esc($client['client_code']) ?></code></td>
                                        <td><?= esc($client['contact_person'] ?? '-') ?></td>
                                        <td><?= esc($client['email'] ?? '-') ?></td>
                                        <td><?= esc($client['phone'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge bg-<?= $client['status'] == 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($client['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($client['created_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-link text-primary edit-client" 
                                                    data-id="<?= $client['id'] ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editClientModal">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-link text-danger delete-client" 
                                                    data-id="<?= $client['id'] ?>" 
                                                    data-name="<?= esc($client['client_name']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">No clients found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Services Tab -->
        <div class="tab-pane fade <?= $tab === 'services' ? 'show active' : '' ?>" 
             id="services" 
             role="tabpanel" 
             aria-labelledby="services-tab">
            
            <!-- Services Header Controls -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select" style="width: auto;" id="serviceStatusFilter">
                        <option value="active" <?= $filters['status'] == 'active' ? 'selected' : '' ?>>Active Services</option>
                        <option value="inactive" <?= $filters['status'] == 'inactive' ? 'selected' : '' ?>>Inactive Services</option>
                        <option value="all" <?= $filters['status'] == 'all' ? 'selected' : '' ?>>All Services</option>
                    </select>
                    <select class="form-select" style="width: auto;" id="serviceTypeFilter">
                        <option value="all" <?= $filters['service_type'] == 'all' ? 'selected' : '' ?>>All Types</option>
                        <?php if (!empty($serviceTypes)): ?>
                            <?php foreach ($serviceTypes as $type): ?>
                                <option value="<?= esc($type) ?>" <?= $filters['service_type'] == $type ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('_', ' ', $type)) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="search" class="form-control" style="width: 300px;" placeholder="Search services..." id="serviceSearch" value="<?= esc($filters['search']) ?>">
                </div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                    <i class="bi bi-plus"></i> New Service
                </button>
            </div>

            <!-- Services Table -->
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Account Code</th>
                                <th>Client</th>
                                <th>Service Type</th>
                                <th>Group ID</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($services)): ?>
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td><?= esc($service['service_name']) ?></td>
                                        <td><code><?= esc($service['account_code']) ?></code></td>
                                        <td><?= esc($service['client_name'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= ucfirst(str_replace('_', ' ', $service['service_type'])) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($service['group_id']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $service['status'] == 'active' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($service['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($service['created_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-link text-primary edit-service" 
                                                    data-id="<?= $service['id'] ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editServiceModal">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-link text-danger delete-service" 
                                                    data-id="<?= $service['id'] ?>" 
                                                    data-name="<?= esc($service['service_name']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">No services found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sequences Tab -->
        <div class="tab-pane fade <?= $tab === 'sequences' ? 'show active' : '' ?>" 
             id="sequences" 
             role="tabpanel" 
             aria-labelledby="sequences-tab">
            
            <!-- Sequences Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Account Sequences</h5>
                    <p class="text-muted mb-0">Manage auto-incrementing sequences for different service types</p>
                </div>
            </div>

            <!-- Sequences Table -->
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Service Type</th>
                                <th>Prefix</th>
                                <th>Current Value</th>
                                <th>Next Value</th>
                                <th>Description</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sequences)): ?>
                                <?php foreach ($sequences as $sequence): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= ucfirst(str_replace('_', ' ', $sequence['service_type'])) ?>
                                            </span>
                                        </td>
                                        <td><code><?= esc($sequence['prefix']) ?></code></td>
                                        <td><strong><?= $sequence['current_value'] ?></strong></td>
                                        <td><span class="text-muted"><?= $sequence['current_value'] + 1 ?></span></td>
                                        <td><?= esc($sequence['description'] ?? '-') ?></td>
                                        <td><?= date('M d, Y', strtotime($sequence['updated_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-link text-primary edit-sequence" 
                                                    data-id="<?= $sequence['id'] ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editSequenceModal">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">No sequences found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addClientForm" action="<?= base_url('settings/clients/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_client_name" class="form-label">Client Name *</label>
                            <input type="text" class="form-control" id="add_client_name" name="client_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="add_client_code" class="form-label">Client Code *</label>
                            <input type="text" class="form-control" id="add_client_code" name="client_code" required>
                            <div class="form-text">Unique identifier for this client</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_contact_person" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="add_contact_person" name="contact_person">
                        </div>
                        <div class="col-md-6">
                            <label for="add_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="add_email" name="email">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="add_phone" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label for="add_status" class="form-label">Status</label>
                            <select class="form-select" id="add_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add_address" class="form-label">Address</label>
                        <textarea class="form-control" id="add_address" name="address" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="add_notes" name="notes" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addClientForm" class="btn btn-success">Add Client</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editClientForm" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_client_id" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_client_name" class="form-label">Client Name *</label>
                            <input type="text" class="form-control" id="edit_client_name" name="client_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_client_code" class="form-label">Client Code *</label>
                            <input type="text" class="form-control" id="edit_client_code" name="client_code" required>
                            <div class="form-text">Unique identifier for this client</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_contact_person" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="edit_contact_person" name="contact_person">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editClientForm" class="btn btn-primary">Update Client</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addServiceForm" action="<?= base_url('settings/services/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_service_name" class="form-label">Service Name *</label>
                            <input type="text" class="form-control" id="add_service_name" name="service_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="add_client_id" class="form-label">Client *</label>
                            <select class="form-select" id="add_client_id" name="client_id" required>
                                <option value="">Select a client...</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_service_type" class="form-label">Service Type *</label>
                            <select class="form-select" id="add_service_type" name="service_type" required>
                                <option value="">Select service type...</option>
                                <option value="materials">Materials</option>
                                <option value="hardware">Hardware</option>
                                <option value="parts">Parts</option>
                                <option value="services">Services</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_service_status" class="form-label">Status</label>
                            <select class="form-select" id="add_service_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add_service_description" class="form-label">Description</label>
                        <textarea class="form-control" id="add_service_description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        The account code will be automatically generated based on the service type and client abbreviation.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addServiceForm" class="btn btn-success">Add Service</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editServiceForm" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_service_id" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_service_name" class="form-label">Service Name *</label>
                            <input type="text" class="form-control" id="edit_service_name" name="service_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_service_client_id" class="form-label">Client *</label>
                            <select class="form-select" id="edit_service_client_id" name="client_id" required>
                                <option value="">Select a client...</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_service_type" class="form-label">Service Type *</label>
                            <select class="form-select" id="edit_service_type" name="service_type" required>
                                <option value="">Select service type...</option>
                                <option value="materials">Materials</option>
                                <option value="hardware">Hardware</option>
                                <option value="parts">Parts</option>
                                <option value="services">Services</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_service_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_service_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_service_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_service_description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editServiceForm" class="btn btn-primary">Update Service</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sequence Modal -->
<div class="modal fade" id="editSequenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sequence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSequenceForm" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_sequence_id" name="id">
                    
                    <div class="mb-3">
                        <label for="edit_sequence_service_type" class="form-label">Service Type</label>
                        <input type="text" class="form-control" id="edit_sequence_service_type" name="service_type" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_sequence_prefix" class="form-label">Prefix</label>
                        <input type="text" class="form-control" id="edit_sequence_prefix" name="prefix" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_sequence_current_value" class="form-label">Current Value</label>
                        <input type="number" class="form-control" id="edit_sequence_current_value" name="current_value" required min="0">
                        <div class="form-text">Next account code will use the value: <span id="nextValuePreview">1</span></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_sequence_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_sequence_description" name="description" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editSequenceForm" class="btn btn-primary">Update Sequence</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tab navigation with URL parameters
    $('#accountRegistryTabs button').on('click', function(e) {
        e.preventDefault();
        const tab = $(this).attr('data-bs-target').substring(1);
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    });
    
    // Load clients for dropdown when modals open
    $('#addServiceModal, #editServiceModal').on('shown.bs.modal', function() {
        loadClientsDropdown();
    });
    
    function loadClientsDropdown() {
        $.get('<?= base_url('settings/clients/dropdown') ?>')
            .done(function(response) {
                if (response.success) {
                    const options = response.clients.map(client => 
                        `<option value="${client.id}">${client.client_name}</option>`
                    ).join('');
                    $('#add_client_id, #edit_service_client_id').html('<option value="">Select a client...</option>' + options);
                }
            });
    }
    
    // Filter handlers
    $('#clientStatusFilter, #clientSearch').on('change keyup', function() {
        applyFilters('clients');
    });
    
    $('#serviceStatusFilter, #serviceTypeFilter, #serviceSearch').on('change keyup', function() {
        applyFilters('services');
    });
    
    function applyFilters(tab) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        
        if (tab === 'clients') {
            url.searchParams.set('status', $('#clientStatusFilter').val());
            url.searchParams.set('search', $('#clientSearch').val());
        } else if (tab === 'services') {
            url.searchParams.set('status', $('#serviceStatusFilter').val());
            url.searchParams.set('service_type', $('#serviceTypeFilter').val());
            url.searchParams.set('search', $('#serviceSearch').val());
        }
        
        window.location.href = url.toString();
    }
    
    // Add Client Form
    $('#addClientForm').on('submit', function(e) {
        e.preventDefault();
        
        $.post($(this).attr('action'), $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#addClientModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert('danger', response.message);
                }
            })
            .fail(function() {
                showAlert('danger', 'An error occurred while adding the client.');
            });
    });
    
    // Edit Client
    $('.edit-client').on('click', function() {
        const clientId = $(this).data('id');
        
        $.get(`<?= base_url('settings/clients/get') ?>/${clientId}`)
            .done(function(response) {
                if (response.success) {
                    const client = response.client;
                    $('#edit_client_id').val(client.id);
                    $('#edit_client_name').val(client.client_name);
                    $('#edit_client_code').val(client.client_code);
                    $('#edit_contact_person').val(client.contact_person || '');
                    $('#edit_email').val(client.email || '');
                    $('#edit_phone').val(client.phone || '');
                    $('#edit_status').val(client.status);
                    $('#edit_address').val(client.address || '');
                    $('#edit_notes').val(client.notes || '');
                    
                    $('#editClientForm').attr('action', `<?= base_url('settings/clients/update') ?>/${client.id}`);
                }
            });
    });
    
    // Update Client Form
    $('#editClientForm').on('submit', function(e) {
        e.preventDefault();
        
        $.post($(this).attr('action'), $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#editClientModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert('danger', response.message);
                }
            })
            .fail(function() {
                showAlert('danger', 'An error occurred while updating the client.');
            });
    });
    
    // Add Service Form
    $('#addServiceForm').on('submit', function(e) {
        e.preventDefault();
        
        $.post($(this).attr('action'), $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message + ' Account code: ' + response.account_code);
                    $('#addServiceModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert('danger', response.message);
                }
            })
            .fail(function() {
                showAlert('danger', 'An error occurred while adding the service.');
            });
    });
    
    // Edit Service
    $('.edit-service').on('click', function() {
        const serviceId = $(this).data('id');
        
        $.get(`<?= base_url('settings/services/get') ?>/${serviceId}`)
            .done(function(response) {
                if (response.success) {
                    const service = response.service;
                    $('#edit_service_id').val(service.id);
                    $('#edit_service_name').val(service.service_name);
                    $('#edit_service_client_id').val(service.client_id);
                    $('#edit_service_type').val(service.service_type);
                    $('#edit_service_status').val(service.status);
                    $('#edit_service_description').val(service.description || '');
                    
                    $('#editServiceForm').attr('action', `<?= base_url('settings/services/update') ?>/${service.id}`);
                }
            });
    });
    
    // Update Service Form
    $('#editServiceForm').on('submit', function(e) {
        e.preventDefault();
        
        $.post($(this).attr('action'), $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#editServiceModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert('danger', response.message);
                }
            })
            .fail(function() {
                showAlert('danger', 'An error occurred while updating the service.');
            });
    });
    
    // Edit Sequence
    $('.edit-sequence').on('click', function() {
        const sequenceId = $(this).data('id');
        
        $.get(`<?= base_url('settings/sequences/get') ?>/${sequenceId}`)
            .done(function(response) {
                if (response.success) {
                    const sequence = response.sequence;
                    $('#edit_sequence_id').val(sequence.id);
                    $('#edit_sequence_service_type').val(sequence.service_type);
                    $('#edit_sequence_prefix').val(sequence.prefix);
                    $('#edit_sequence_current_value').val(sequence.current_value);
                    $('#edit_sequence_description').val(sequence.description || '');
                    updateNextValuePreview(sequence.current_value);
                    
                    $('#editSequenceForm').attr('action', `<?= base_url('settings/sequences/update') ?>/${sequence.id}`);
                }
            });
    });
    
    // Update next value preview
    $('#edit_sequence_current_value').on('input', function() {
        updateNextValuePreview($(this).val());
    });
    
    function updateNextValuePreview(currentValue) {
        const nextValue = parseInt(currentValue) + 1;
        $('#nextValuePreview').text(nextValue);
    }
    
    // Update Sequence Form
    $('#editSequenceForm').on('submit', function(e) {
        e.preventDefault();
        
        $.post($(this).attr('action'), $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#editSequenceModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert('danger', response.message);
                }
            })
            .fail(function() {
                showAlert('danger', 'An error occurred while updating the sequence.');
            });
    });
    
    // Delete handlers
    let deleteUrl = '';
    let deleteType = '';
    
    $('.delete-client').on('click', function() {
        const clientId = $(this).data('id');
        const clientName = $(this).data('name');
        deleteUrl = `<?= base_url('settings/clients/delete') ?>/${clientId}`;
        deleteType = 'client';
        $('#deleteMessage').text(`Are you sure you want to delete the client "${clientName}"? This action cannot be undone.`);
        $('#deleteConfirmModal').modal('show');
    });
    
    $('.delete-service').on('click', function() {
        const serviceId = $(this).data('id');
        const serviceName = $(this).data('name');
        deleteUrl = `<?= base_url('settings/services/delete') ?>/${serviceId}`;
        deleteType = 'service';
        $('#deleteMessage').text(`Are you sure you want to delete the service "${serviceName}"? This action cannot be undone.`);
        $('#deleteConfirmModal').modal('show');
    });
    
    $('#confirmDelete').on('click', function() {
        $.post(deleteUrl, { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
            .done(function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#deleteConfirmModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showAlert('danger', response.message);
                }
            })
            .fail(function() {
                showAlert('danger', `An error occurred while deleting the ${deleteType}.`);
            });
    });
    
    // Alert helper function
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of the content
        $('.tab-content').prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>

<style>
.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-link:hover {
    color: #495057;
    border-bottom-color: #dee2e6;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    background-color: transparent;
    border-bottom-color: #0d6efd;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
}

code {
    background-color: #f8f9fa;
    color: #e83e8c;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}
</style>

<?= $this->endSection() ?>
