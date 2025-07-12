<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Users</h4>
    <p class="text-muted">Add and manage Users of your field service organization here. Manage your field service operations efficiently by assigning Profile(s), Crew, Territories and Skills to the users.</p>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <select class="form-select" style="width: auto;" id="userStatusFilter">
                <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active Users</option>
                <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>Inactive Users</option>
                <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>All Users</option>
            </select>
            <input type="search" class="form-control" style="width: 300px;" placeholder="Search" id="userSearch">
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-plus"></i> New User
        </button>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>All Profiles <i class="bi bi-caret-down-fill"></i></th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                                        </div>
                                        <span class="ms-2"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></span>
                                    </div>
                                </td>
                                <td><?= esc($user['email']) ?></td>
                                <td><?= ucfirst(str_replace('_', ' ', $user['role'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $user['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td><?= esc($user['created_by'] ?? 'System') ?></td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-link text-primary edit-user" 
                                            data-id="<?= $user['id'] ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger delete-user" 
                                            data-id="<?= $user['id'] ?>" 
                                            data-name="<?= esc($user['first_name'] . ' ' . $user['last_name']) ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <p class="text-muted small">The user's data, including their skills assignments and related records, will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUser">Delete User</button>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="<?= base_url('settings/addUser') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <h6 class="mb-3">User Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="add_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="add_last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="add_last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="add_email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="add_employee_id" class="form-label">Employee Id</label>
                            <input type="text" class="form-control" id="add_employee_id" name="employee_id">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="add_phone" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label for="add_mobile" class="form-label">Mobile</label>
                            <input type="tel" class="form-control" id="add_mobile" name="mobile">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="add_language" class="form-label">Language</label>
                            <select class="form-select" id="add_language" name="language">
                                <option value="en-US">English - United States</option>
                                <option value="fr-CA">French - Canada</option>
                                <option value="es-MX">Spanish - Mexico</option>
                            </select>
                            <div class="form-text mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="add_enable_rtl_info" disabled>
                                    <label class="form-check-label text-muted" for="add_enable_rtl_info">
                                        Note: Some features are supported only in English language. We are working on it, and full support will be available in the upcoming updates.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="add_password" name="password" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label for="add_confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="add_confirm_password" name="confirm_password" required minlength="6">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_role" class="form-label">Profile</label>
                            <select class="form-select" id="add_role" name="role" required>
                                <option value="admin">Administrator</option>
                                <option value="call_center_agent">Call Center Agent</option>
                                <option value="dispatcher">Dispatcher</option>
                                <option value="field_agent">Field Agent</option>
                                <option value="limited_field_agent">Limited Field Agent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add_status" class="form-label">Status</label>
                            <select class="form-select" id="add_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="mb-3 mt-4">Address Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="add_street" class="form-label">Street</label>
                            <input type="text" class="form-control" id="add_street" name="street">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_city" class="form-label">City</label>
                            <input type="text" class="form-control" id="add_city" name="city">
                        </div>
                        <div class="col-md-6">
                            <label for="add_state" class="form-label">State</label>
                            <input type="text" class="form-control" id="add_state" name="state">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="add_country" name="country">
                        </div>
                        <div class="col-md-6">
                            <label for="add_zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="add_zip_code" name="zip_code">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addUserForm" class="btn btn-primary">Add User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?= base_url('settings/updateUser') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_user_id" name="id">
                    
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#overview-tab" type="button">Overview</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#timeline-tab" type="button">Timeline</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#calendar-tab" type="button">Calendar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#appointments-tab" type="button">Service appointments</button>
                        </li>
                        <li class="nav-item dropdown" role="presentation">
                            <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="tab" data-bs-target="#timesheets-tab">Time Sheets</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="tab" data-bs-target="#territories-tab">Territories</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="tab" data-bs-target="#crew-tab">Crew</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="tab" data-bs-target="#skills-tab">Skills</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="tab" data-bs-target="#trips-tab">Trips</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="tab" data-bs-target="#related-list-tab">Related List</a></li>
                            </ul>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="overview-tab">
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
                                <div class="col-md-12">
                                    <label for="edit_language" class="form-label">Language</label>
                                    <select class="form-select" id="edit_language" name="language">
                                        <option value="en-US">English - United States</option>
                                        <option value="fr-CA">French - Canada</option>
                                        <option value="es-MX">Spanish - Mexico</option>
                                    </select>
                                    <div class="form-text mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="enable_rtl_info" disabled>
                                            <label class="form-check-label text-muted" for="enable_rtl_info">
                                                Note: Some features are supported only in English language. We are working on it, and full support will be available in the upcoming updates.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="edit_role" class="form-label">Profile</label>
                                    <select class="form-select" id="edit_role" name="role" required>
                                        <option value="admin">Administrator</option>
                                        <option value="call_center_agent">Call Center Agent</option>
                                        <option value="dispatcher">Dispatcher</option>
                                        <option value="field_agent">Field Agent</option>
                                        <option value="limited_field_agent">Limited Field Agent</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" name="status" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
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
                            
                            <h6 class="mb-3 mt-4">Territories</h6>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Select territories">
                            </div>
                            
                            <h6 class="mb-3 mt-4">Shift Details</h6>
                            <p class="text-muted">No Shifts Found</p>
                            
                            <h6 class="mb-3 mt-4">Crew</h6>
                            <p class="text-muted">No Crew Found</p>
                            
                            <h6 class="mb-3 mt-4">Skills</h6>
                            <div id="overviewSkillsContent">
                                <p class="text-muted">Loading skills...</p>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="timeline-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="text-muted mb-0">All the actions and events related to this Service Resource are recorded in a chronological order.</p>
                                <select class="form-select" style="width: auto;" id="timeline-filter">
                                    <option value="all">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="last_week">Last Week</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="last_year">Last Year</option>
                                </select>
                            </div>
                            
                            <div id="timeline-content">
                                <!-- Timeline items will be loaded here -->
                                <div class="timeline-loading text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="calendar-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-filter"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 280px;">
                                            <h6 class="dropdown-header px-0">Calendar Settings</h6>
                                            <div class="dropdown-divider"></div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="maintainScroll" checked>
                                                <label class="form-check-label" for="maintainScroll">
                                                    Maintain Scroll across dates
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" id="showAllEvents" checked>
                                                <label class="form-check-label" for="showAllEvents">
                                                    Show all events of a day
                                                </label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="showOnlyActive">
                                                <label class="form-check-label" for="showOnlyActive">
                                                    Show only active appointments
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-prev">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <h5 class="mb-0" id="calendar-month-year">July, 2025</h5>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="calendar-next">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <select class="form-select form-select-sm" id="calendar-view-type" style="width: auto;">
                                        <option value="live">Live</option>
                                        <option value="past">Past</option>
                                    </select>
                                    <select class="form-select form-select-sm" id="calendar-view-mode" style="width: auto;">
                                        <option value="month">Month</option>
                                        <option value="week">Week</option>
                                        <option value="day">Day</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="calendar-container" id="calendar-container">
                                <!-- Calendar content will be rendered here based on view mode -->
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="appointments-tab">
                            <p>Service appointments content will go here</p>
                        </div>
                        
                        <div class="tab-pane fade" id="timesheets-tab">
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No Records Found</p>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="territories-tab">
                            <div class="mb-3">
                                <label class="form-label">Assigned Territories</label>
                                <div class="text-muted">No territories assigned to this user</div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus"></i> Assign Territory
                            </button>
                        </div>
                        
                        <div class="tab-pane fade" id="crew-tab">
                            <div class="mb-3">
                                <label class="form-label">Crew Assignments</label>
                                <div class="text-muted">User is not part of any crew</div>
                            </div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus"></i> Add to Crew
                            </button>
                        </div>
                        
                        <div class="tab-pane fade" id="skills-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Skills</h6>
                                <button class="btn btn-sm btn-success" id="assignSkillBtn">
                                    <i class="bi bi-plus"></i> Assign
                                </button>
                            </div>
                            
                            <div id="userSkillsContent">
                                <div class="text-center py-3">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2">Loading skills...</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="trips-tab">
                            <div class="text-center py-5">
                                <p class="text-muted mb-0">No Records Found</p>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="related-list-tab">
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">Work Orders</h6>
                                        <span class="badge bg-secondary">0</span>
                                    </div>
                                    <p class="mb-0 text-muted small">No related work orders found</p>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">Service Reports</h6>
                                        <span class="badge bg-secondary">0</span>
                                    </div>
                                    <p class="mb-0 text-muted small">No related service reports found</p>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">Time Entries</h6>
                                        <span class="badge bg-secondary">0</span>
                                    </div>
                                    <p class="mb-0 text-muted small">No related time entries found</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveUserChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize calendar date
    let currentDate = new Date();

// Render the calendar
    renderCalendar(currentDate);
    
    // Update calendar view on mode change
    $('#calendar-view-mode').change(function() {
        renderCalendar(currentDate);
    });

    // Event listeners for calendar navigation
    $('#calendar-prev').click(function() {
        const viewMode = $('#calendar-view-mode').val();
        if (viewMode === 'week') {
            currentDate.setDate(currentDate.getDate() - 7);
        } else if (viewMode === 'day') {
            currentDate.setDate(currentDate.getDate() - 1);
        } else {
            currentDate.setMonth(currentDate.getMonth() - 1);
        }
        renderCalendar(currentDate);
    });

    $('#calendar-next').click(function() {
        const viewMode = $('#calendar-view-mode').val();
        if (viewMode === 'week') {
            currentDate.setDate(currentDate.getDate() + 7);
        } else if (viewMode === 'day') {
            currentDate.setDate(currentDate.getDate() + 1);
        } else {
            currentDate.setMonth(currentDate.getMonth() + 1);
        }
        renderCalendar(currentDate);
    });

// Function to render the calendar
    function renderCalendar(date) {
        const viewMode = $('#calendar-view-mode').val();

        if (viewMode === 'week') {
            renderWeekView(date);
        } else if (viewMode === 'day') {
            renderDayView(date);
        } else {
            renderMonthView(date);
        }
    }

    // Function to render the month view
    function renderMonthView(date) {
        const monthYear = date.toLocaleString('default', { month: 'long', year: 'numeric' });
        $('#calendar-month-year').text(monthYear);

        const year = date.getFullYear();
        const month = date.getMonth();
        const today = new Date();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const prevLastDay = new Date(year, month, 0);
        
        let daysHtml = '<tr>';
        
        // Previous month days
        const firstDayOfWeek = firstDay.getDay();
        for (let i = firstDayOfWeek; i > 0; i--) {
            const day = prevLastDay.getDate() - i + 1;
            daysHtml += `<td class="other-month"><span class="date-number">${day}</span></td>`;
        }
        
        // Current month days
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const currentDate = new Date(year, month, day);
            const dayOfWeek = currentDate.getDay();
            
            if (dayOfWeek === 0 && day !== 1) {
                daysHtml += '</tr><tr>';
            }
            
            const isToday = currentDate.toDateString() === today.toDateString();
            const todayClass = isToday ? 'today' : '';
            
            daysHtml += `<td class="${todayClass}"><span class="date-number">${day}</span></td>`;
        }
        
        // Next month days
        const lastDayOfWeek = lastDay.getDay();
        if (lastDayOfWeek < 6) {
            for (let day = 1; day <= 6 - lastDayOfWeek; day++) {
                daysHtml += `<td class="other-month"><span class="date-number">${day}</span></td>`;
            }
        }
        
        daysHtml += '</tr>';
        
        // Add empty rows if needed to maintain consistent height
        const totalCells = $('#calendar-body').find('td').length;
        if (totalCells < 42) { // 6 rows x 7 days
            daysHtml += '<tr>';
            for (let i = 0; i < 7; i++) {
                daysHtml += '<td></td>';
            }
            daysHtml += '</tr>';
        }
        
$('#calendar-container').html(`
            <table class="table table-bordered calendar-table">
                <thead>
                    <tr>
                        <th class="text-center">Sun</th>
                        <th class="text-center">Mon</th>
                        <th class="text-center">Tue</th>
                        <th class="text-center">Wed</th>
                        <th class="text-center">Thu</th>
                        <th class="text-center">Fri</th>
                        <th class="text-center">Sat</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    ${daysHtml}
                </tbody>
            </table>
        `);
    }

    // Function to render the week view
    function renderWeekView(date) {
        const startOfWeek = new Date(date);
        startOfWeek.setDate(date.getDate() - date.getDay());
        const endOfWeek = new Date(startOfWeek);
        endOfWeek.setDate(startOfWeek.getDate() + 6);
        
        // Update header to show week range
        const weekRange = `${startOfWeek.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' })} - ${endOfWeek.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' })}`;
        $('#calendar-month-year').text(weekRange);

        // Generate header with day names and dates
        let headerHtml = '<tr><th style="width: 80px;"></th>';
        for (let i = 0; i < 7; i++) {
            const currentDay = new Date(startOfWeek);
            currentDay.setDate(startOfWeek.getDate() + i);
            const dayName = currentDay.toLocaleDateString('en-US', { weekday: 'short' });
            const dayNum = currentDay.getDate();
            headerHtml += `<th class="text-center">${dayNum} ${dayName}</th>`;
        }
        headerHtml += '</tr>';

        // Generate time slots
        let bodyHtml = '';
        const timeSlots = [
            '1 AM', '2 AM', '3 AM', '4 AM', '5 AM', '6 AM', 
            '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM',
            '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM',
            '7 PM', '8 PM', '9 PM', '10 PM', '11 PM'
        ];
        
        timeSlots.forEach(time => {
            bodyHtml += '<tr>';
            bodyHtml += `<td class="text-muted" style="font-size: 0.875rem; vertical-align: top; padding: 0.5rem;">${time}</td>`;
            
            for (let i = 0; i < 7; i++) {
                const currentDay = new Date(startOfWeek);
                currentDay.setDate(startOfWeek.getDate() + i);
                const isToday = currentDay.toDateString() === (new Date()).toDateString();
                const todayClass = isToday ? 'bg-light' : '';
                bodyHtml += `<td class="${todayClass}" style="height: 60px; vertical-align: top;"></td>`;
            }
            
            bodyHtml += '</tr>';
        });

        $('#calendar-container').html(`
            <div style="overflow-x: auto;">
                <table class="table table-bordered calendar-table mb-0">
                    <thead>
                        ${headerHtml}
                    </thead>
                    <tbody>
                        ${bodyHtml}
                    </tbody>
                </table>
            </div>
        `);
    }

    // Function to render the day view
    function renderDayView(date) {
        const isToday = date.toDateString() === (new Date()).toDateString();
        const todayClass = isToday ? 'bg-light' : '';
        
        // Update header to show selected date
        const dateString = date.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
        $('#calendar-month-year').text(dateString);
        
        // Generate header
        const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
        let headerHtml = `<tr><th style="width: 80px;"></th><th class="text-center">${dayName}</th></tr>`;
        
        // Generate time slots
        let bodyHtml = '';
        
        // All Day row
        bodyHtml += '<tr>';
        bodyHtml += '<td class="text-muted" style="font-size: 0.875rem; vertical-align: top; padding: 0.5rem;">All Day</td>';
        bodyHtml += `<td class="${todayClass}" style="height: 40px; vertical-align: top;"></td>`;
        bodyHtml += '</tr>';
        
        // UQ (Unassigned Queue) row
        bodyHtml += '<tr>';
        bodyHtml += '<td class="text-muted" style="font-size: 0.875rem; vertical-align: top; padding: 0.5rem;">UQ</td>';
        bodyHtml += `<td class="${todayClass}" style="height: 40px; vertical-align: top;"></td>`;
        bodyHtml += '</tr>';
        
        // 12 AM row
        bodyHtml += '<tr>';
        bodyHtml += '<td class="text-muted" style="font-size: 0.875rem; vertical-align: top; padding: 0.5rem;">12 AM</td>';
        bodyHtml += `<td class="${todayClass}" style="height: 40px; vertical-align: top;"></td>`;
        bodyHtml += '</tr>';
        
        // Generate hourly time slots from 1 AM to 11 PM
        const timeSlots = [
            '1 AM', '2 AM', '3 AM', '4 AM', '5 AM', '6 AM', 
            '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM',
            '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM',
            '7 PM', '8 PM', '9 PM', '10 PM', '11 PM'
        ];
        
        timeSlots.forEach(time => {
            bodyHtml += '<tr>';
            bodyHtml += `<td class="text-muted" style="font-size: 0.875rem; vertical-align: top; padding: 0.5rem;">${time}</td>`;
            bodyHtml += `<td class="${todayClass}" style="height: 60px; vertical-align: top;"></td>`;
            bodyHtml += '</tr>';
        });

        $('#calendar-container').html(`
            <div style="overflow-x: auto;">
                <table class="table table-bordered calendar-table mb-0">
                    <thead>
                        ${headerHtml}
                    </thead>
                    <tbody>
                        ${bodyHtml}
                    </tbody>
                </table>
            </div>
        `);
    }

    // Edit user button click
    $('.edit-user').on('click', function() {
        const userId = $(this).data('id');
        
        // Fetch user data via AJAX
            $.ajax({
                url: '<?= base_url('settings/getUser') ?>/' + userId,
                type: 'GET',
            success: function(response) {
                if (response.success) {
                    const user = response.user;
                    $('#edit_user_id').val(user.id);
                    $('#edit_first_name').val(user.first_name);
                    $('#edit_last_name').val(user.last_name);
                    $('#edit_email').val(user.email);
                    $('#edit_employee_id').val(user.employee_id || '');
                    $('#edit_phone').val(user.phone || '');
                    $('#edit_mobile').val(user.mobile || '');
                    $('#edit_language').val(user.language || 'en-US');
                    $('#edit_role').val(user.role);
                    $('#edit_status').val(user.status);
                    
                    // Populate address fields
                    $('#edit_street').val(user.street || '');
                    $('#edit_city').val(user.city || '');
                    $('#edit_state').val(user.state || '');
                    $('#edit_country').val(user.country || '');
                    $('#edit_zip_code').val(user.zip_code || '');
                    
                    // Load timeline data when modal opens
                    loadUserTimeline(user.id);
                    
                    // Load skills for overview tab
                    loadOverviewSkills(user.id);
                }
            }
        });
    });
    
    // Delete user button click
    $('.delete-user').on('click', function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        
        // Update modal content with user name
        $('#deleteUserModal .modal-body p:first').text(
            `Are you sure you want to delete ${userName}? This action cannot be undone.`
        );
        
        // Show the modal
        $('#deleteUserModal').modal('show');
        
        // Store the user ID for the confirm button
        $('#confirmDeleteUser').data('user-id', userId);
    });
    
    // Confirm delete user
    $('#confirmDeleteUser').on('click', function() {
        const userId = $(this).data('user-id');
        
        $.ajax({
            url: '<?= base_url('settings/deleteUser') ?>/' + userId,
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('User deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting user: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error deleting user: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
        
        // Hide the modal
        $('#deleteUserModal').modal('hide');
    });
    
    // Load user timeline data
    function loadUserTimeline(userId) {
        const filter = $('#timeline-filter').val() || 'all';
        
            $.ajax({
                url: '<?= base_url('settings/getUserTimeline') ?>/' + userId,
                type: 'GET',
            data: { filter: filter },
            success: function(response) {
                if (response.success) {
                    displayTimeline(response.timeline);
                } else {
                    $('#timeline-content').html('<p class="text-center text-muted py-4">No timeline events found</p>');
                }
            },
            error: function() {
                $('#timeline-content').html('<p class="text-center text-danger py-4">Error loading timeline</p>');
            }
        });
    }
    
    // Display timeline items
    function displayTimeline(timelineData) {
        if (!timelineData || timelineData.length === 0) {
            $('#timeline-content').html('<p class="text-center text-muted py-4">No timeline events found</p>');
            return;
        }
        
        let timelineHtml = '<div class="timeline">';
        let currentDate = '';
        
        timelineData.forEach(function(item) {
            // Group by date
            const itemDate = formatDate(item.created_at);
            if (itemDate !== currentDate) {
                currentDate = itemDate;
                timelineHtml += `<div class="timeline-date">${currentDate}</div>`;
            }
            
            // Timeline item
            timelineHtml += `
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="bi bi-${getIconForEvent(item.event_type)}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <span class="timeline-title">${item.title}</span>
                            <span class="timeline-time">${formatTime(item.created_at)}</span>
                        </div>
                        <div class="timeline-body">
                            ${item.description}
                            ${item.user_name ? `<span class="text-muted"> · ${item.user_name}</span>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        timelineHtml += '</div>';
        $('#timeline-content').html(timelineHtml);
    }
    
    // Format date for grouping
    function formatDate(dateString) {
        const date = new Date(dateString);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        if (date.toDateString() === today.toDateString()) {
            return 'Today - ' + date.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' });
        } else if (date.toDateString() === yesterday.toDateString()) {
            return 'Yesterday - ' + date.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' });
        } else {
            return date.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' });
        }
    }
    
    // Format time
    function formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
    }
    
    // Get icon for event type
    function getIconForEvent(eventType) {
        const icons = {
            'created': 'plus-circle-fill',
            'updated': 'pencil-fill',
            'status_changed': 'arrow-repeat',
            'role_changed': 'person-badge-fill',
            'login': 'box-arrow-in-right',
            'logout': 'box-arrow-right',
            'password_changed': 'key-fill',
            'profile_updated': 'person-fill',
            'service_assigned': 'calendar-check-fill',
            'service_completed': 'check-circle-fill',
            'default': 'circle-fill'
        };
        
        return icons[eventType] || icons['default'];
    }
    
    // Timeline filter change
    $('#timeline-filter').on('change', function() {
        const userId = $('#edit_user_id').val();
        if (userId) {
            loadUserTimeline(userId);
        }
    });
    
    // Save user changes
    $('#saveUserChanges').on('click', function(e) {
        e.preventDefault();
        
        const form = document.getElementById('editUserForm');
        const formData = new FormData(form);
        
        // Log form data for debugging
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        $.ajax({
            url: form.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    alert('User updated successfully!');
                    location.reload();
                } else {
                    let errorMsg = 'Error updating user: ' + (response.message || 'Unknown error');
                    if (response.errors) {
                        errorMsg += '\n\nValidation errors:\n';
                        for (let field in response.errors) {
                            errorMsg += field + ': ' + response.errors[field] + '\n';
                        }
                    }
                    alert(errorMsg);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                let errorMsg = 'Error updating user: ';
                if (xhr.status === 404) {
                    errorMsg += 'URL not found. Please check your configuration.';
                } else if (xhr.status === 500) {
                    errorMsg += 'Server error. Check the logs for details.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
    });
    
    // Add user form submission
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submission started');
        
        // Validate passwords match
        const password = $('#add_password').val();
        const confirmPassword = $('#add_confirm_password').val();
        
        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            $('#add_confirm_password').focus();
            return false;
        }
        
        const formData = $(this).serialize();
        console.log('Form data:', formData);
        
        $.ajax({
            url: '<?= base_url('settings/addUser') ?>',
            type: 'POST',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    alert('User added successfully!');
                    location.reload();
                } else {
                    alert('Error adding user: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                let errorMsg = 'Error adding user: ';
                if (xhr.status === 404) {
                    errorMsg += 'URL not found. Please check your routes.';
                } else if (xhr.status === 500) {
                    errorMsg += 'Server error. Check the logs for details.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
    });
    
    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status') || 'active';
    const currentSearch = urlParams.get('search') || '';
    
    $('#userStatusFilter').val(currentStatus);
    $('#userSearch').val(currentSearch);
    
    // User status filter
    $('#userStatusFilter').on('change', function() {
        applyFilters();
    });
    
    // User search
    let searchTimer;
    $('#userSearch').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            applyFilters();
        }, 500); // Debounce for 500ms
    });
    
    // Function to apply filters and reload page
    function applyFilters() {
        const status = $('#userStatusFilter').val();
        const search = $('#userSearch').val();
        
        const params = new URLSearchParams();
        if (status) {
            params.append('status', status);
        }
        if (search) {
            params.append('search', search);
        }
        
        const queryString = params.toString();
        const newUrl = '<?= base_url('settings/users') ?>' + (queryString ? '?' + queryString : '');
        
        window.location.href = newUrl;
    }
    
    // Skills management functionality
    let currentUserId = null;
    let availableSkills = [];
    
    // Load skills for overview tab
    function loadOverviewSkills(userId) {
        $('#overviewSkillsContent').html('<p class="text-muted">Loading skills...</p>');
        
        $.ajax({
            url: '<?= base_url('settings/users') ?>/' + userId + '/skills',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    displayOverviewSkills(response.userSkills);
                } else {
                    $('#overviewSkillsContent').html('<p class="text-muted">Error loading skills</p>');
                }
            },
            error: function() {
                $('#overviewSkillsContent').html('<p class="text-muted">Error loading skills</p>');
            }
        });
    }
    
    // Display skills in overview tab
    function displayOverviewSkills(userSkills) {
        if (!userSkills || userSkills.length === 0) {
            $('#overviewSkillsContent').html('<p class="text-muted">No skills assigned to this user</p>');
            return;
        }
        
        let skillsHtml = '<div class="row">';
        
        userSkills.forEach(function(skill, index) {
            const statusBadgeClass = skill.certificate_status === 'active' ? 'bg-success' : 
                                   skill.certificate_status === 'pending' ? 'bg-warning' : 
                                   skill.certificate_status === 'expired' ? 'bg-danger' : 'bg-secondary';
            
            if (index > 0 && index % 3 === 0) {
                skillsHtml += '</div><div class="row">';
            }
            
            skillsHtml += `
                <div class="col-md-4 mb-2">
                    <div class="card card-body p-2 border-0 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="fw-bold">${skill.skill_name || 'N/A'}</small>
                                ${skill.skill_level ? `<br><small class="text-muted">Level: ${skill.skill_level}</small>` : ''}
                            </div>
                            <span class="badge ${statusBadgeClass} badge-sm">${skill.certificate_status}</span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        skillsHtml += '</div>';
        $('#overviewSkillsContent').html(skillsHtml);
    }
    
    // Load user skills when Skills tab is clicked
    $(document).on('click', 'a[data-bs-target="#skills-tab"]', function(e) {
        e.preventDefault();
        const userId = $('#edit_user_id').val();
        if (userId && userId !== currentUserId) {
            currentUserId = userId;
            loadUserSkills(userId);
        }
    });
    
    // Also handle when Skills tab is shown (Bootstrap tab shown event)
    $(document).on('shown.bs.tab', 'a[data-bs-target="#skills-tab"]', function(e) {
        const userId = $('#edit_user_id').val();
        if (userId && userId !== currentUserId) {
            currentUserId = userId;
            loadUserSkills(userId);
        }
    });
    
    // Load user skills
    function loadUserSkills(userId) {
        $('#userSkillsContent').html(`
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Loading skills...</p>
            </div>
        `);
        
        $.ajax({
            url: '<?= base_url('settings/users') ?>/' + userId + '/skills',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    availableSkills = response.availableSkills;
                    displayUserSkills(response.userSkills);
                } else {
                    $('#userSkillsContent').html('<p class="text-center text-muted py-4">Error loading skills</p>');
                }
            },
            error: function() {
                $('#userSkillsContent').html('<p class="text-center text-danger py-4">Error loading skills</p>');
            }
        });
    }
    
    // Display user skills
    function displayUserSkills(userSkills) {
        if (!userSkills || userSkills.length === 0) {
            $('#userSkillsContent').html(`
                <div class="text-center py-4">
                    <p class="text-muted mb-0">No skills assigned to this user</p>
                </div>
            `);
            return;
        }
        
        let skillsHtml = `
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Skill</th>
                            <th>Level</th>
                            <th>Certificate Status</th>
                            <th>Issue</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        userSkills.forEach(function(skill) {
            const statusBadgeClass = skill.certificate_status === 'active' ? 'bg-success' : 
                                   skill.certificate_status === 'pending' ? 'bg-warning' : 
                                   skill.certificate_status === 'expired' ? 'bg-danger' : 'bg-secondary';
            
            skillsHtml += `
                <tr>
                    <td>${skill.skill_name || 'N/A'}</td>
                    <td>${skill.skill_name || 'N/A'}</td>
                    <td>${skill.skill_level || ''}</td>
                    <td><span class="badge ${statusBadgeClass}">${skill.certificate_status}</span></td>
                    <td>${skill.issue_date ? new Date(skill.issue_date).toLocaleDateString() : ''}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary edit-user-skill" data-id="${skill.id}" data-skill-id="${skill.skill_id}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger remove-user-skill ms-1" data-user-id="${skill.user_id}" data-skill-id="${skill.skill_id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        skillsHtml += `
                    </tbody>
                </table>
            </div>
        `;
        
        $('#userSkillsContent').html(skillsHtml);
    }
    
    // Assign skill button click
    $(document).on('click', '#assignSkillBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Assign button clicked');
        console.log('Current User ID:', currentUserId);
        console.log('Available Skills:', availableSkills);
        
        if (!currentUserId) {
            alert('Please select a user first');
            return;
        }
        
        if (availableSkills.length === 0) {
            alert('No skills available for assignment');
            return;
        }
        
        showAssignSkillModal();
    });
    
    // Show assign skill modal
    function showAssignSkillModal() {
        let skillOptions = '';
        availableSkills.forEach(function(skill) {
            skillOptions += `<option value="${skill.id}">${skill.name}</option>`;
        });
        
        const modalHtml = `
            <div class="modal fade" id="assignSkillModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assign Skill</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="assignSkillForm">
                                <div class="mb-3">
                                    <label class="form-label">Skill *</label>
                                    <select class="form-select" name="skill_id" required>
                                        <option value="">Select a skill</option>
                                        ${skillOptions}
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Level</label>
                                    <input type="text" class="form-control" name="skill_level" placeholder="e.g., L1, L2, Beginner, Advanced">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Certificate Status</label>
                                    <select class="form-select" name="certificate_status" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                        <option value="expired">Expired</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Issue Date</label>
                                        <input type="date" class="form-control" name="issue_date">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="date" class="form-control" name="expiry_date">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="assignSkillConfirm">Assign Skill</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        $('#assignSkillModal').remove();
        
        // Add modal to body and show
        $('body').append(modalHtml);
        $('#assignSkillModal').modal('show');
        
        // Handle assign confirm
        $('#assignSkillConfirm').off('click').on('click', function() {
            const formData = $('#assignSkillForm').serialize();
            
            $.ajax({
                url: '<?= base_url('settings/users/skills/assign') ?>',
                type: 'POST',
                data: formData + '&user_id=' + currentUserId + '&<?= csrf_token() ?>=<?= csrf_hash() ?>',
                success: function(response) {
                    if (response.success) {
                        $('#assignSkillModal').modal('hide');
                        loadUserSkills(currentUserId);
                        loadOverviewSkills(currentUserId); // Reload overview skills
                        alert('Skill assigned successfully!');
                    } else {
                        alert('Error: ' + (response.message || 'Failed to assign skill'));
                    }
                },
                error: function() {
                    alert('Error assigning skill');
                }
            });
        });
    }
    
    // Remove user skill
    $(document).on('click', '.remove-user-skill', function() {
        const userId = $(this).data('user-id');
        const skillId = $(this).data('skill-id');
        
        if (confirm('Are you sure you want to remove this skill from the user?')) {
            $.ajax({
                url: '<?= base_url('settings/users/skills/remove') ?>',
                type: 'POST',
                data: {
                    user_id: userId,
                    skill_id: skillId,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        loadUserSkills(currentUserId);
                        loadOverviewSkills(currentUserId); // Reload overview skills
                        alert('Skill removed successfully!');
                    } else {
                        alert('Error: ' + (response.message || 'Failed to remove skill'));
                    }
                },
                error: function() {
                    alert('Error removing skill');
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>

