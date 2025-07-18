<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Filter Panel -->
        <div class="col-md-3 col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-funnel"></i> Filter users
                    </h6>
                    
                    <div class="mb-3">
                        <label for="searchInput" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search users...">
                    </div>
                    
                    <div class="mb-3">
                        <label for="profileFilter" class="form-label">Profile</label>
                        <select class="form-select" id="profileFilter">
                            <option value="">All Profiles</option>
                            <option value="admin">Admin</option>
                            <option value="call_center_agent">Call Center Agent</option>
                            <option value="manager">Manager</option>
                            <option value="technician">Technician</option>
                            <option value="dispatcher">Dispatcher</option>
                            <option value="field_agent">Field Agent</option>
                        </select>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" type="button" id="applyFilter">Apply Filter</button>
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="clearFilter">Clear</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0"><?= esc($title) ?></h5>
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= ($current_status ?? 'active') === 'active' ? 'active' : '' ?>" href="<?= base_url('workforce/users?status=active') ?>">Active Users</a></li>
                                <li><a class="dropdown-item <?= ($current_status ?? 'active') === 'inactive' ? 'active' : '' ?>" href="<?= base_url('workforce/users?status=inactive') ?>">Inactive Users</a></li>
                                <li><a class="dropdown-item <?= ($current_status ?? 'active') === 'invited' ? 'active' : '' ?>" href="<?= base_url('workforce/users?status=invited') ?>">Invited Users</a></li>
                                <li><a class="dropdown-item <?= ($current_status ?? 'active') === 'deleted' ? 'active' : '' ?>" href="<?= base_url('workforce/users?status=deleted') ?>">Deleted Users</a></li>
                                <li><a class="dropdown-item <?= ($current_status ?? 'active') === 'all' ? 'active' : '' ?>" href="<?= base_url('workforce/users?status=all') ?>">All Users</a></li>
                            </ul>
                        </div>
                    </div>
                    <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus"></i> New User
                    </button>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Full Name</th>
                                    <th>Employee Id</th>
                                    <th>Email</th>
                                    <th>All Profiles <i class="bi bi-caret-down-fill"></i></th>
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
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <span class="text-white small"><?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?></span>
                                                </div>
                                                <a href="<?= base_url('workforce/users/profile/' . $user['id']) ?>" class="text-decoration-none">
                                                    <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>
                                                </a>
                                            </div>
                                        </td>
                                        <td><?= esc($user['employee_id'] ?? 'N/A') ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= ucfirst(str_replace('_', ' ', $user['role'] ?? 'User')) ?></td>
                                        <td><?= esc($user['created_by_name'] ?? 'System') ?></td>
                                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('workforce/users/profile/' . $user['id']) ?>" class="btn btn-sm btn-link text-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
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
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                            No users found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Total Users: <strong><?= count($users) ?></strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#">1</a>
                                    </li>
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="ms-3">
                                <select class="form-select form-select-sm" style="width: auto;">
                                    <option>10 Records per page</option>
                                    <option>25 Records per page</option>
                                    <option>50 Records per page</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?= base_url('workforce/updateUser') ?>" method="POST">
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
                    <div class="form-text mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_enable_rtl_info" disabled>
                            <label class="form-check-label text-muted" for="edit_enable_rtl_info">
                                <i class="bi bi-info-circle"></i> Note: Some features are supported only in English language. We are working on it, and full support will be available in the upcoming updates.
                            </label>
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

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <p class="text-muted small">The user's data, including their assignments and related records, will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUser">Delete User</button>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="<?= base_url('workforce/users/create') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <h6 class="mb-3">User Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="userFirstName" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="userLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="userLastName" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userPhone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="userPhone" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label for="userMobile" class="form-label">Mobile</label>
                            <input type="tel" class="form-control" id="userMobile" name="mobile">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userEmployeeId" class="form-label">Employee Id</label>
                            <input type="text" class="form-control" id="userEmployeeId" name="employee_id">
                        </div>
                        <div class="col-md-6">
                            <label for="userLanguage" class="form-label">Language</label>
                            <select class="form-select" id="userLanguage" name="language">
                                <option value="en-US">English - United States</option>
                                <option value="fr-CA">French - Canada</option>
                                <option value="es-MX">Spanish - Mexico</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-text mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="enable_rtl_info" disabled>
                            <label class="form-check-label text-muted" for="enable_rtl_info">
                                <i class="bi bi-info-circle"></i> Note: Some features are supported only in English language. We are working on it, and full support will be available in the upcoming updates.
                            </label>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="userConfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="userConfirmPassword" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userProfile" class="form-label">Profile</label>
                            <select class="form-select" id="userProfile" name="role" required>
                                <option value="">Select</option>
                                <option value="admin">Administrator</option>
                                <option value="call_center_agent">Call Center Agent</option>
                                <option value="dispatcher">Dispatcher</option>
                                <option value="field_agent">Field Agent</option>
                                <option value="limited_field_agent">Limited Field Agent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="userStatus" class="form-label">Status</label>
                            <select class="form-select" id="userStatus" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="mb-3 mt-4">Address Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="userStreet" class="form-label">Street</label>
                            <input type="text" class="form-control" id="userStreet" name="street">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="userCity" name="city">
                        </div>
                        <div class="col-md-6">
                            <label for="userState" class="form-label">State</label>
                            <input type="text" class="form-control" id="userState" name="state">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userCountry" class="form-label">Country</label>
                            <input type="text" class="form-control" id="userCountry" name="country">
                        </div>
                        <div class="col-md-6">
                            <label for="userZipCode" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="userZipCode" name="zip_code">
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to clean up modal backdrops and body styles
    function cleanupModal() {
        // Clean up modal backdrop if it exists
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        
        // Remove modal-open class from body
        document.body.classList.remove('modal-open');
        document.body.style.paddingRight = '';
        document.body.style.overflow = '';
    }
    // Handle add user form submission
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Basic validation
        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('#addUserModal .btn-primary');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
        
        fetch('<?= base_url('workforce/users/create') ?>', {
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
                alert('User created successfully!');
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                modal.hide();
                this.reset();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to create user'));
                if (data.errors) {
                    console.log('Validation errors:', data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating user: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // Handle filter functionality
    document.getElementById('applyFilter').addEventListener('click', function() {
        applyFilters();
    });
    
    document.getElementById('clearFilter').addEventListener('click', function() {
        // Clear all filter inputs
        document.getElementById('searchInput').value = '';
        document.getElementById('profileFilter').selectedIndex = 0;
        
        // Apply filters to show all users
        applyFilters();
    });
    
    // Function to apply filters
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value.trim();
        const profileFilter = document.getElementById('profileFilter').value;
        const currentStatus = '<?= $current_status ?? "active" ?>';
        
        // Build query parameters
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (profileFilter) params.append('role', profileFilter);
        params.append('status', currentStatus);
        
        // Show loading state
        const applyBtn = document.getElementById('applyFilter');
        const originalText = applyBtn.innerHTML;
        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Filtering...';
        
        // Make AJAX request to search endpoint
        fetch(`<?= base_url('workforce/users/search') ?>?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateUserTable(data.users);
                updateUserCount(data.users.length);
            } else {
                alert('Error: ' + (data.message || 'Failed to filter users'));
            }
        })
        .catch(error => {
            console.error('Filter Error:', error);
            alert('Error filtering users: ' + error.message);
        })
        .finally(() => {
            // Reset button state
            applyBtn.disabled = false;
            applyBtn.innerHTML = originalText;
        });
    }
    
    // Function to update user table with filtered results
    function updateUserTable(users) {
        const tbody = document.querySelector('table tbody');
        
        if (users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                        No users found
                    </td>
                </tr>
            `;
            return;
        }
        
        let html = '';
        users.forEach(user => {
            const initials = (user.first_name.charAt(0) + user.last_name.charAt(0)).toUpperCase();
            const fullName = `${user.first_name} ${user.last_name}`;
            const role = user.role ? user.role.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'User';
            const createdDate = new Date(user.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            
            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <span class="text-white small">${initials}</span>
                            </div>
                            <a href="<?= base_url('workforce/users/profile/') ?>${user.id}" class="text-decoration-none">
                                ${fullName}
                            </a>
                        </div>
                    </td>
                    <td>${user.employee_id || 'N/A'}</td>
                    <td>${user.email}</td>
                    <td>${role}</td>
                    <td>${user.created_by_name || 'System'}</td>
                    <td>${createdDate}</td>
                    <td>
                        <a href="<?= base_url('workforce/users/profile/') ?>${user.id}" class="btn btn-sm btn-link text-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-sm btn-link text-primary edit-user" 
                                data-id="${user.id}" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editUserModal">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-link text-danger delete-user" 
                                data-id="${user.id}" 
                                data-name="${fullName}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        
        // Re-attach event listeners for the new buttons
        attachUserActionListeners();
    }
    
    // Function to update user count
    function updateUserCount(count) {
        const countElement = document.querySelector('.text-muted strong');
        if (countElement) {
            countElement.textContent = count;
        }
    }
    
    // Function to attach event listeners to user action buttons
    function attachUserActionListeners() {
        // Edit user buttons
        document.querySelectorAll('.edit-user').forEach(function(button) {
            button.addEventListener('click', handleEditUser);
        });
        
        // Delete user buttons
        document.querySelectorAll('.delete-user').forEach(function(button) {
            button.addEventListener('click', handleDeleteUser);
        });
    }
    
    // Handle edit user button click
    function handleEditUser(e) {
        e.preventDefault();
        const userId = this.getAttribute('data-id');
        
        console.log('Edit button clicked for user ID:', userId);
        
        // Check if userId exists
        if (!userId) {
            console.error('No user ID found');
            alert('Error: No user ID found');
            return;
        }
        
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
                console.log('User data:', user);
                
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
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            } else {
                alert('Error: ' + (data.message || 'User not found'));
            }
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            alert('Error fetching user data: ' + error.message);
        });
    }
    
    // Handle delete user button click
    function handleDeleteUser(e) {
        e.preventDefault();
        const userId = this.getAttribute('data-id');
        const userName = this.getAttribute('data-name');
        
        // Update modal content with user name
        document.querySelector('#deleteUserModal .modal-body p').textContent = 
            `Are you sure you want to delete ${userName}? This action cannot be undone.`;
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        modal.show();
        
        // Store the user ID for the confirm button
        document.getElementById('confirmDeleteUser').setAttribute('data-user-id', userId);
    }
    
    // Add Enter key support for search input
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });
    
    // Initialize event listeners for existing buttons
    attachUserActionListeners();
    
    // Handle edit form submission
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Basic validation
        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }
        
        console.log('Saving user changes...');
        console.log('Form data:', Object.fromEntries(formData));
        
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
            console.log('Update response:', data);
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
            
            // Clean up modal backdrop if it exists
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
        });
    });
    
    
    // Confirm delete user
    document.getElementById('confirmDeleteUser').addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        
        fetch(`<?= base_url('workforce/users/delete') ?>/${userId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully!');
                location.reload();
            } else {
                alert('Error deleting user: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Delete Error:', error);
            alert('Error deleting user: ' + error.message);
        });
        
        // Hide the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
        modal.hide();
    });
    
    // Add event listeners for all modal close events
    const modals = ['editUserModal', 'addUserModal', 'deleteUserModal'];
    modals.forEach(function(modalId) {
        const modalElement = document.getElementById(modalId);
        if (modalElement) {
            // Listen for when modal is hidden
            modalElement.addEventListener('hidden.bs.modal', function() {
                cleanupModal();
            });
            
            // Listen for when modal is about to hide
            modalElement.addEventListener('hide.bs.modal', function() {
                setTimeout(cleanupModal, 150); // Small delay to ensure proper cleanup
            });
        }
    });
    
    // Also clean up on page click outside modal
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            cleanupModal();
        }
    });
});
</script>
<?= $this->endSection() ?>
