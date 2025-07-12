<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <div class="mb-4">
        <div class="mb-3">
            <h4 class="mb-2">Profiles</h4>
            <p class="text-muted mb-0">Add and manage users of your field service organization here. Manage your field service operations efficiently by assigning Profile(s), Crew, Territories and Skills to the users.</p>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProfileModal">
                <i class="bi bi-plus me-1"></i>New Profile
            </button>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="profilesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Profile Name</th>
                            <th>Description</th>
                            <th>Created Time</th>
                            <th>Modified Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($profiles)): ?>
                            <?php foreach ($profiles as $profile): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong><?= esc($profile['name']) ?></strong>
                                            <?php if ($profile['is_default']): ?>
                                                <span class="badge bg-primary ms-2 small">Default</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            <?= esc($profile['description']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($profile['created_at']): ?>
                                            <?= date('M d, Y', strtotime($profile['created_at'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($profile['updated_at']): ?>
                                            <?= date('M d, Y', strtotime($profile['updated_at'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-link text-primary edit-profile" 
                                                    data-id="<?= $profile['id'] ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editProfileModal"
                                                    title="Edit Profile">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <?php if (!$profile['is_default']): ?>
                                                <button class="btn btn-sm btn-link text-danger delete-profile" 
                                                        data-id="<?= $profile['id'] ?>" 
                                                        data-name="<?= esc($profile['name']) ?>"
                                                        title="Delete Profile">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-people fs-1 d-block mb-2"></i>
                                        <p>No profiles found</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Profile Modal -->
<div class="modal fade" id="addProfileModal" tabindex="-1" aria-labelledby="addProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProfileModalLabel">Add New Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addProfileForm">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="add_profile_name" class="form-label">Profile Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_profile_name" name="name" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="add_profile_description" class="form-label">Description</label>
                            <textarea class="form-control" id="add_profile_description" name="description" rows="3" placeholder="Enter a description for this profile..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_profile_status" class="form-label">Status</label>
                            <select class="form-select" id="add_profile_status" name="status" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Permissions</h6>
                    <div class="row" id="permissionsContainer">
                        <!-- Permissions will be loaded here via JavaScript -->
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addProfileForm" class="btn btn-success">Add Profile</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_profile_id" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_profile_name" class="form-label">Profile Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_profile_name" name="name" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_profile_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_profile_description" name="description" rows="3" placeholder="Enter a description for this profile..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_profile_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_profile_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Permissions</h6>
                    <div class="row" id="editPermissionsContainer">
                        <!-- Permissions will be loaded here via JavaScript -->
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editProfileForm" class="btn btn-primary">Update Profile</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Profile Modal -->
<div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this profile? This action cannot be undone.</p>
                <p class="text-muted small">Users currently assigned to this profile will need to be reassigned to another profile.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProfile">Delete Profile</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentProfileId = null;
    
    // Define available permissions
    const availablePermissions = {
        'settings': { name: 'Settings', actions: ['read', 'write', 'delete'] },
        'users': { name: 'Users', actions: ['read', 'write', 'delete'] },
        'customers': { name: 'Customers', actions: ['read', 'write', 'delete'] },
        'work_orders': { name: 'Work Orders', actions: ['read', 'write', 'delete'] },
        'dispatch': { name: 'Dispatch', actions: ['read', 'write', 'delete'] },
        'billing': { name: 'Billing', actions: ['read', 'write', 'delete'] },
        'reports': { name: 'Reports', actions: ['read', 'write', 'delete'] },
        'territories': { name: 'Territories', actions: ['read', 'write', 'delete'] },
        'skills': { name: 'Skills', actions: ['read', 'write', 'delete'] },
        'holidays': { name: 'Holidays', actions: ['read', 'write', 'delete'] }
    };
    
    // Generate permissions HTML
    function generatePermissionsHTML(containerId, selectedPermissions = {}) {
        const container = document.getElementById(containerId);
        let html = '';
        
        Object.keys(availablePermissions).forEach(module => {
            const moduleInfo = availablePermissions[module];
            const modulePermissions = selectedPermissions[module] || [];
            
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header py-2">
                            <h6 class="mb-0">${moduleInfo.name}</h6>
                        </div>
                        <div class="card-body py-2">
                            ${moduleInfo.actions.map(action => `
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" 
                                           id="${containerId}_${module}_${action}" 
                                           name="permissions[${module}][]" 
                                           value="${action}"
                                           ${modulePermissions.includes(action) ? 'checked' : ''}>
                                    <label class="form-check-label" for="${containerId}_${module}_${action}">
                                        ${action.charAt(0).toUpperCase() + action.slice(1)}
                                    </label>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    // Initialize permissions for add modal
    generatePermissionsHTML('permissionsContainer');
    
    // Add Profile Form
    document.getElementById('addProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Process permissions
        const permissions = {};
        const checkboxes = this.querySelectorAll('input[type="checkbox"]:checked');
        checkboxes.forEach(checkbox => {
            const name = checkbox.name;
            const match = name.match(/permissions\[(.+?)\]\[\]/);
            if (match) {
                const module = match[1];
                if (!permissions[module]) {
                    permissions[module] = [];
                }
                permissions[module].push(checkbox.value);
            }
        });
        
        // Add permissions to form data
        formData.append('permissions', JSON.stringify(permissions));
        
        fetch('<?= base_url('settings/profiles/add') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('addProfileModal')).hide();
                location.reload(); // Reload to show new profile
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while adding the profile', 'error');
        });
    });
    
    // Edit Profile
    document.querySelectorAll('.edit-profile').forEach(button => {
        button.addEventListener('click', function() {
            const profileId = this.dataset.id;
            currentProfileId = profileId;
            
            fetch(`<?= base_url('settings/profiles/get') ?>/${profileId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const profile = data.profile;
                        
                        document.getElementById('edit_profile_id').value = profile.id;
                        document.getElementById('edit_profile_name').value = profile.name;
                        document.getElementById('edit_profile_description').value = profile.description || '';
                        document.getElementById('edit_profile_status').value = profile.status;
                        
                        // Generate permissions with current values
                        generatePermissionsHTML('editPermissionsContainer', profile.permissions || {});
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('An error occurred while loading profile data', 'error');
                });
        });
    });
    
    // Update Profile Form
    document.getElementById('editProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Process permissions
        const permissions = {};
        const checkboxes = this.querySelectorAll('input[type="checkbox"]:checked');
        checkboxes.forEach(checkbox => {
            const name = checkbox.name;
            const match = name.match(/permissions\[(.+?)\]\[\]/);
            if (match) {
                const module = match[1];
                if (!permissions[module]) {
                    permissions[module] = [];
                }
                permissions[module].push(checkbox.value);
            }
        });
        
        // Add permissions to form data
        formData.append('permissions', JSON.stringify(permissions));
        
        fetch(`<?= base_url('settings/profiles/update') ?>/${currentProfileId}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
                location.reload(); // Reload to show updated profile
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating the profile', 'error');
        });
    });
    
    // Delete Profile
    document.querySelectorAll('.delete-profile').forEach(button => {
        button.addEventListener('click', function() {
            const profileId = this.dataset.id;
            const profileName = this.dataset.name;
            currentProfileId = profileId;
            
            // Show delete modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteProfileModal'));
            deleteModal.show();
        });
    });
    
    // Confirm Delete
    document.getElementById('confirmDeleteProfile').addEventListener('click', function() {
        if (!currentProfileId) return;
        
        fetch(`<?= base_url('settings/profiles/delete') ?>/${currentProfileId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('deleteProfileModal')).hide();
                location.reload(); // Reload to remove deleted profile
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting the profile', 'error');
        });
    });
    
    // Alert function
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }
});
</script>

<style>
.table th {
    font-weight: 600;
    font-size: 0.9rem;
    border-bottom: 2px solid #dee2e6;
}

.form-check-inline {
    margin-right: 1rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-lg {
    max-width: 800px;
}

.badge {
    font-size: 0.7rem;
}
</style>
<?= $this->endSection() ?>
