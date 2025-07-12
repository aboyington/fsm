<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Skills</h4>
    <p class="text-muted">Add and manage Skills for your field service organization here. Skills help define the capabilities and expertise of your service resources.</p>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <select class="form-select" style="width: auto;" id="skillStatusFilter">
                <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active Skills</option>
                <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>Inactive Skills</option>
                <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>All Skills</option>
            </select>
            <input type="search" class="form-control" style="width: 300px;" placeholder="Search skills..." id="skillSearch" value="<?= esc($search ?? '') ?>">
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addSkillModal">
            <i class="bi bi-plus"></i> New Skill
        </button>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($skills)): ?>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td><?= esc($skill['name']) ?></td>
                                <td><?= esc($skill['description'] ?? '') ?></td>
                                <td>
                                    <span class="badge bg-<?= $skill['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($skill['status']) ?>
                                    </span>
                                </td>
                                <td><?= esc($skill['created_by_name'] ?? 'System') ?></td>
                                <td><?= date('M d, Y', strtotime($skill['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-link text-primary edit-skill me-2" 
                                            data-id="<?= $skill['id'] ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editSkillModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger delete-skill" 
                                            data-id="<?= $skill['id'] ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">No skills found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSkillForm">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="add_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="add_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_description" class="form-label">Description</label>
                        <textarea class="form-control" id="add_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="add_status" class="form-label">Status</label>
                        <select class="form-select" id="add_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addSkillForm" class="btn btn-primary">Add Skill</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Skill Modal -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSkillForm">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_skill_id" name="id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSkillChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Set initial values from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status') || 'active';
    const currentSearch = urlParams.get('search') || '';
    
    $('#skillStatusFilter').val(currentStatus);
    $('#skillSearch').val(currentSearch);
    
    // Skill status filter
    $('#skillStatusFilter').on('change', function() {
        applyFilters();
    });
    
    // Skill search
    let searchTimer;
    $('#skillSearch').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            applyFilters();
        }, 500); // Debounce for 500ms
    });
    
    // Function to apply filters and reload page
    function applyFilters() {
        const status = $('#skillStatusFilter').val();
        const search = $('#skillSearch').val();
        
        const params = new URLSearchParams();
        if (status) {
            params.append('status', status);
        }
        if (search) {
            params.append('search', search);
        }
        
        const queryString = params.toString();
        const newUrl = '<?= base_url('settings/skills') ?>' + (queryString ? '?' + queryString : '');
        
        window.location.href = newUrl;
    }
    
    // Add skill form submission
    $('#addSkillForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?= base_url('settings/skills/add') ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Skill added successfully!');
                    location.reload();
                } else {
                    alert('Error adding skill: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error adding skill: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
    });
    
    // Edit skill button click
    $('.edit-skill').on('click', function() {
        const skillId = $(this).data('id');
        
        // Fetch skill data via AJAX
        $.ajax({
            url: '<?= base_url('settings/skills/get') ?>/' + skillId,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const skill = response.skill;
                    $('#edit_skill_id').val(skill.id);
                    $('#edit_name').val(skill.name);
                    $('#edit_description').val(skill.description || '');
                    $('#edit_status').val(skill.status);
                }
            },
            error: function(xhr, status, error) {
                alert('Error loading skill data: ' + error);
            }
        });
    });
    
    // Save skill changes
    $('#saveSkillChanges').on('click', function(e) {
        e.preventDefault();
        
        const skillId = $('#edit_skill_id').val();
        const formData = $('#editSkillForm').serialize();
        
        $.ajax({
            url: '<?= base_url('settings/skills/update') ?>/' + skillId,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Skill updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating skill: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error updating skill: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
    });
    
    // Delete skill
    $('.delete-skill').on('click', function() {
        const skillId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this skill?')) {
            $.ajax({
                url: '<?= base_url('settings/skills/delete') ?>/' + skillId,
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Skill deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting skill: ' + (response.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    let errorMsg = 'Error deleting skill: ';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += xhr.responseJSON.message;
                    } else {
                        errorMsg += error || 'Unknown error';
                    }
                    alert(errorMsg);
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>
