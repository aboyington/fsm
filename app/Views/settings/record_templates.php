<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Record Templates</h4>
    <p class="text-muted">Manage your record templates here. You can add, update, delete, and duplicate templates as needed.</p>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <input type="search" class="form-control" style="width: 300px;" placeholder="Search templates" id="templateSearch">
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTemplateModal">
            <i class="bi bi-plus"></i> New Template
        </button>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Module Type</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($templates)): ?>
                        <?php foreach ($templates as $template): ?>
                            <tr>
                                <td><?= esc($template['name']) ?></td>
                                <td><?= esc($template['description']) ?></td>
                                <td><?= ucfirst(str_replace('_', ' ', $template['module_type'])) ?></td>
                                <td><?= date('M d, Y', strtotime($template['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-link text-primary edit-template" 
                                            data-id="<?= $template['id'] ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editTemplateModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger delete-template" 
                                            data-id="<?= $template['id'] ?>" 
                                            data-name="<?= esc($template['name']) ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">No templates found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Template Modal -->
<div class="modal fade" id="deleteTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this template? This action cannot be undone.</p>
                <p class="text-muted small">The template and all its associated data will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTemplate">Delete Template</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Template Modal -->
<div class="modal fade" id="addTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTemplateForm" action="<?= base_url('settings/record-templates/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="add_template_name" class="form-label">Template Name</label>
                            <input type="text" class="form-control" id="add_template_name" name="name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="add_template_description" class="form-label">Description</label>
                            <textarea class="form-control" id="add_template_description" name="description" rows="3" placeholder="Enter a description for this template..."></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_module_type" class="form-label">Module Type</label>
                            <select class="form-select" id="add_module_type" name="module_type" required>
                                <option value="">Select Module</option>
                                <option value="work_orders">Work Orders</option>
                                <option value="estimates">Estimates</option>
                                <option value="invoices">Invoices</option>
                                <option value="service_reports">Service Reports</option>
                                <option value="customers">Customers</option>
                                <option value="assets">Assets</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="addTemplateForm" class="btn btn-primary">Add Template</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Template Modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTemplateForm" action="<?= base_url('settings/record-templates/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_template_id" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_template_name" class="form-label">Template Name</label>
                            <input type="text" class="form-control" id="edit_template_name" name="name" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_template_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_template_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_module_type" class="form-label">Module Type</label>
                            <select class="form-select" id="edit_module_type" name="module_type" required>
                                <option value="">Select Module</option>
                                <option value="work_orders">Work Orders</option>
                                <option value="estimates">Estimates</option>
                                <option value="invoices">Invoices</option>
                                <option value="service_reports">Service Reports</option>
                                <option value="customers">Customers</option>
                                <option value="assets">Assets</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveTemplateChanges">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    
    // Edit template button click
    $('.edit-template').on('click', function() {
        const templateId = $(this).data('id');
        
        // Fetch template data via AJAX
        $.ajax({
            url: '<?= base_url('settings/record-templates/get') ?>/' + templateId,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const template = response.template;
                    $('#edit_template_id').val(template.id);
                    $('#edit_template_name').val(template.name);
                    $('#edit_template_description').val(template.description || '');
                    $('#edit_module_type').val(template.module_type);
                }
            }
        });
    });
    
    // Delete template button click
    $('.delete-template').on('click', function() {
        const templateId = $(this).data('id');
        const templateName = $(this).data('name');
        
        // Update modal content with template name
        $('#deleteTemplateModal .modal-body p:first').text(
            `Are you sure you want to delete ${templateName}? This action cannot be undone.`
        );
        
        // Show the modal
        $('#deleteTemplateModal').modal('show');
        
        // Store the template ID for the confirm button
        $('#confirmDeleteTemplate').data('template-id', templateId);
    });
    
    // Confirm delete template
    $('#confirmDeleteTemplate').on('click', function() {
        const templateId = $(this).data('template-id');
        
        $.ajax({
            url: '<?= base_url('settings/record-templates/delete') ?>/' + templateId,
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    alert('Template deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting template: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error deleting template: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
        
        // Hide the modal
        $('#deleteTemplateModal').modal('hide');
    });
    
    // Save template changes
    $('#saveTemplateChanges').on('click', function(e) {
        e.preventDefault();
        
        const form = document.getElementById('editTemplateForm');
        const formData = new FormData(form);
        const templateId = $('#edit_template_id').val();
        
        $.ajax({
            url: '<?= base_url('settings/record-templates/update') ?>/' + templateId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    alert('Template updated successfully!');
                    location.reload();
                } else {
                    alert('Error updating template: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error updating template: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
    });
    
    // Add template form submission
    $('#addTemplateForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?= base_url('settings/record-templates/store') ?>',
            type: 'POST',
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    alert('Template added successfully!');
                    location.reload();
                } else {
                    alert('Error adding template: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error adding template: ';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += xhr.responseJSON.message;
                } else {
                    errorMsg += error || 'Unknown error';
                }
                alert(errorMsg);
            }
        });
    });
    
    // Search functionality
    $('#templateSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        const rows = $('table tbody tr');
        
        rows.each(function() {
            const row = $(this);
            const text = row.text().toLowerCase();
            
            if (text.includes(searchTerm) || searchTerm === '') {
                row.show();
            } else {
                row.hide();
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
