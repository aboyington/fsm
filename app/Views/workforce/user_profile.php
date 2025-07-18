<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- User Information Panel -->
        <div class="col-md-4 col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <!-- User Avatar -->
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <span class="text-white h3 mb-0"><?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?></span>
                        </div>
                    </div>
                    
                    <!-- User Name -->
                    <h5 class="mb-1"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                    <div class="text-muted small mb-2"><?= esc($user['email']) ?></div>
                    
                    <!-- Status Badge -->
                    <div class="mb-3">
                        <?php 
                        $statusClass = match($user['status']) {
                            'active' => 'bg-success',
                            'inactive' => 'bg-secondary',
                            'suspended' => 'bg-warning',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= ucfirst($user['status']) ?></span>
                    </div>
                    
                    <!-- Role -->
                    <div class="mb-3">
                        <strong>Role:</strong><br>
                        <span class="text-muted"><?= ucfirst(str_replace('_', ' ', $user['role'] ?? 'User')) ?></span>
                    </div>
                    
                    <!-- Employee ID -->
                    <?php if (!empty($user['employee_id'])): ?>
                    <div class="mb-3">
                        <strong>Employee ID:</strong><br>
                        <span class="text-muted"><?= esc($user['employee_id']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Edit Button -->
                    <button class="btn btn-primary btn-sm w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editUserModal"
                            onclick="loadUserForEdit(<?= $user['id'] ?>)">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </button>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-telephone"></i> Contact Information</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($user['phone'])): ?>
                    <div class="mb-2">
                        <strong>Phone:</strong><br>
                        <span class="text-muted"><?= esc($user['phone']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($user['mobile'])): ?>
                    <div class="mb-2">
                        <strong>Mobile:</strong><br>
                        <span class="text-muted"><?= esc($user['mobile']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-2">
                        <strong>Language:</strong><br>
                        <span class="text-muted"><?= esc($user['language'] ?? 'en-US') ?></span>
                    </div>
                    
                    <?php if (!empty($user['street']) || !empty($user['city']) || !empty($user['state']) || !empty($user['country'])): ?>
                    <div class="mt-3">
                        <strong>Address:</strong><br>
                        <div class="text-muted small">
                            <?php if (!empty($user['street'])): ?>
                                <?= esc($user['street']) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($user['city']) || !empty($user['state'])): ?>
                                <?= esc($user['city']) ?><?= !empty($user['state']) ? ', ' . esc($user['state']) : '' ?><br>
                            <?php endif; ?>
                            <?php if (!empty($user['country'])): ?>
                                <?= esc($user['country']) ?>
                            <?php endif; ?>
                            <?php if (!empty($user['zip_code'])): ?>
                                <?= esc($user['zip_code']) ?>
                            <?php endif; ?>
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
                    <ul class="nav nav-tabs card-header-tabs" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="true">
                                <i class="bi bi-clock-history"></i> Timeline
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab" aria-controls="calendar" aria-selected="false">
                                <i class="bi bi-calendar"></i> Calendar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="service-appointments-tab" data-bs-toggle="tab" data-bs-target="#service-appointments" type="button" role="tab" aria-controls="service-appointments" aria-selected="false">
                                <i class="bi bi-calendar-check"></i> Service Appointments
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="time-sheets-tab" data-bs-toggle="tab" data-bs-target="#time-sheets" type="button" role="tab" aria-controls="time-sheets" aria-selected="false">
                                <i class="bi bi-clock"></i> Time Sheets
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="territories-tab" data-bs-toggle="tab" data-bs-target="#territories" type="button" role="tab" aria-controls="territories" aria-selected="false">
                                <i class="bi bi-geo-alt"></i> Territories
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="crew-tab" data-bs-toggle="tab" data-bs-target="#crew" type="button" role="tab" aria-controls="crew" aria-selected="false">
                                <i class="bi bi-people"></i> Crew
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="false">
                                <i class="bi bi-gear"></i> Skills
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="trips-tab" data-bs-toggle="tab" data-bs-target="#trips" type="button" role="tab" aria-controls="trips" aria-selected="false">
                                <i class="bi bi-truck"></i> Trips
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content" id="userTabsContent">
                        <!-- Timeline Tab -->
                        <div class="tab-pane fade show active" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">All the actions and events related to this Service Resource are recorded in a chronological order.</h6>
                                <div class="dropdown">
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
                                <strong><?= date('M j, Y') ?></strong>
                            </div>
                            
                            <!-- Timeline Items -->
                            <div class="timeline-item">
                                <div class="timeline-icon bg-primary">
                                    <i class="bi bi-wrench text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-time">06:18 PM</div>
                                    <div class="timeline-text">
                                        <strong>Skill Installation Tech L4</strong> linked<br>
                                        <small class="text-muted">Anthony Boyington</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon bg-danger">
                                    <i class="bi bi-trash text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-time">06:18 PM</div>
                                    <div class="timeline-text">
                                        <strong>Skill Installation Tech L4</strong> deleted<br>
                                        <small class="text-muted">Anthony Boyington</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon bg-primary">
                                    <i class="bi bi-wrench text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-time">06:14 PM</div>
                                    <div class="timeline-text">
                                        <strong>Skill Installation Tech L1</strong> linked<br>
                                        <small class="text-muted">Anthony Boyington</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon bg-warning">
                                    <i class="bi bi-pencil text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-time">01:28 PM</div>
                                    <div class="timeline-text">
                                        <strong>Service Resource details</strong> updated<br>
                                        <div class="mt-1 p-2 bg-light rounded">
                                            <small>Service Resource Name updated from <strong>Anthony</strong> to <strong>Anthony Boyington</strong></small>
                                        </div>
                                        <small class="text-muted">Anthony Boyington</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <div class="timeline-icon bg-success">
                                    <i class="bi bi-plus text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-time">02:06 AM</div>
                                    <div class="timeline-text">
                                        <strong>Service Resource</strong> created<br>
                                        <small class="text-muted">Anthony Boyington</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calendar Tab -->
                        <div class="tab-pane fade" id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Calendar</h6>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-calendar-month"></i> This Month
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">This Week</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">Next Month</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body text-center py-5">
                                            <i class="bi bi-calendar-event fs-1 text-muted mb-3"></i>
                                            <h6 class="text-muted">Calendar View</h6>
                                            <p class="text-muted small">View and manage your scheduled appointments and events</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Service Appointments Tab -->
                        <div class="tab-pane fade" id="service-appointments" role="tabpanel" aria-labelledby="service-appointments-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Service Appointments</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> New Appointment
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Customer</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                                No service appointments found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Skills Tab -->
                        <div class="tab-pane fade" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Skills</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> Add Skill
                                </button>
                            </div>
                            
                            <?php if (!empty($skills)): ?>
                                <div class="row">
                                    <?php foreach ($skills as $skill): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-start border-primary border-4">
                                                <div class="card-body">
                                                    <h6 class="card-title"><?= esc($skill['skill_name']) ?></h6>
                                                    <p class="card-text text-muted small"><?= esc($skill['skill_description']) ?></p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-primary">Level <?= $skill['skill_level'] ?? 1 ?></span>
                                                        <div>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-gear fs-1 d-block mb-2"></i>
                                    No skills assigned
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Territories Tab -->
                        <div class="tab-pane fade" id="territories" role="tabpanel" aria-labelledby="territories-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Territories</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> Assign Territory
                                </button>
                            </div>
                            
                            <?php if (!empty($territories)): ?>
                                <div class="row">
                                    <?php foreach ($territories as $territory): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-start border-success border-4">
                                                <div class="card-body">
                                                    <h6 class="card-title"><?= esc($territory['territory_name']) ?></h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-success">All</span>
                                                        <div>
                                                            <button class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-geo-alt fs-1 d-block mb-2"></i>
                                    No territories assigned
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Crew Tab -->
                        <div class="tab-pane fade" id="crew" role="tabpanel" aria-labelledby="crew-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Crew Assignments</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> Add to Crew
                                </button>
                            </div>
                            
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                No crew assignments found
                            </div>
                        </div>
                        
                        <!-- Trips Tab -->
                        <div class="tab-pane fade" id="trips" role="tabpanel" aria-labelledby="trips-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Trips</h6>
                                <button class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus"></i> New Trip
                                </button>
                            </div>
                            
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-truck fs-1 d-block mb-2"></i>
                                No trips found
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?= base_url('workforce/users/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_user_id" name="id">
                    
                    <h6 class="mb-3">User Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_employee_id" class="form-label">Employee Id</label>
                            <input type="text" class="form-control" id="edit_employee_id" name="employee_id">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_mobile" class="form-label">Mobile</label>
                            <input type="tel" class="form-control" id="edit_mobile" name="mobile">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                            <div class="form-text">Leave blank to keep the current password.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="edit_confirm_password" name="confirm_password">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_role" class="form-label">Profile</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="dispatcher">Dispatcher</option>
                                <option value="field_agent">Field Agent</option>
                                <option value="call_center_agent">Call Center Agent</option>
                                <option value="manager">Manager</option>
                                <option value="technician">Technician</option>
                                <option value="limited_field_agent">Limited Field Agent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_language" class="form-label">Language</label>
                            <select class="form-select" id="edit_language" name="language">
                                <option value="en-US">English - United States</option>
                                <option value="fr-CA">French - Canada</option>
                                <option value="es-MX">Spanish - Mexico</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <!-- Empty column for layout balance -->
                        </div>
                    </div>
                    
                    <h6 class="mb-3 mt-4">Address Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_street" class="form-label">Street</label>
                            <input type="text" class="form-control" id="edit_street" name="street">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_city" class="form-label">City</label>
                            <input type="text" class="form-control" id="edit_city" name="city">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_state" class="form-label">State</label>
                            <input type="text" class="form-control" id="edit_state" name="state">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="edit_country" name="country">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="edit_zip_code" name="zip_code">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editUserForm" class="btn btn-primary">Save Changes</button>
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
function loadUserForEdit(userId) {
    // Fetch user data from backend
    fetch(`<?= base_url('workforce/users/get') ?>/${userId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.user) {
            const user = data.user;
            
            // Populate form fields
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_first_name').value = user.first_name || '';
            document.getElementById('edit_last_name').value = user.last_name || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_employee_id').value = user.employee_id || '';
            document.getElementById('edit_phone').value = user.phone || '';
            document.getElementById('edit_mobile').value = user.mobile || '';
            document.getElementById('edit_role').value = user.role || '';
            document.getElementById('edit_language').value = user.language || 'en-US';
            document.getElementById('edit_status').value = user.status || 'active';
            
            // Populate address fields
            document.getElementById('edit_street').value = user.street || '';
            document.getElementById('edit_city').value = user.city || '';
            document.getElementById('edit_state').value = user.state || '';
            document.getElementById('edit_country').value = user.country || '';
            document.getElementById('edit_zip_code').value = user.zip_code || '';
        } else {
            alert('Error: ' + (data.message || 'User not found'));
        }
    })
    .catch(error => {
        console.error('Error fetching user data:', error);
        alert('Error fetching user data: ' + error.message);
    });
}

// Handle edit form submission
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Basic validation
    if (!this.checkValidity()) {
        this.reportValidity();
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#editUserModal .btn-primary');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
    
    const userId = formData.get('id');
    fetch(`<?= base_url('workforce/users/update') ?>/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User updated successfully!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update user'));
            if (data.errors) {
                console.log('Validation errors:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Update Error:', error);
        alert('Error updating user: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
<?= $this->endSection() ?>