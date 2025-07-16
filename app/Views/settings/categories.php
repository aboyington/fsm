<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Categories</h4>
    <p class="text-muted">Manage categories for your parts and services. Categories help organize your inventory and service offerings for better management and reporting.</p>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <select class="form-select" style="width: auto;" id="categoryStatusFilter">
                <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active Categories</option>
                <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>Inactive Categories</option>
                <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>All Categories</option>
            </select>
            <input type="search" class="form-control" style="width: 300px;" placeholder="Search categories..." id="categorySearch" value="<?= esc($search) ?>">
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus"></i> New Category
        </button>
    </div>
    
    
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-tag"></i>
                                        </div>
                                        <span class="ms-2"><?= esc($category['name']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $category['category_type'] == 'parts' ? 'info' : ($category['category_type'] == 'services' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($category['category_type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?= esc($category['description']) ?>">
                                        <?= esc($category['description']) ?: '--' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $category['is_active'] ? 'success' : 'secondary' ?>">
                                        <?= $category['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td><?= esc($category['creator_name'] ?? 'System') ?></td>
                                <td><?= date('M d, Y', strtotime($category['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-link text-primary edit-category" 
                                            data-id="<?= $category['id'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger delete-category" 
                                            data-id="<?= $category['id'] ?>" 
                                            data-name="<?= esc($category['name']) ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-tags text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No categories found</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="bi bi-plus"></i> Add Your First Category
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" action="<?= base_url('settings/categories/add') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="add_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_name" name="name" required maxlength="255">
                        </div>
                        <div class="col-md-4">
                            <label for="add_category_type" class="form-label">Category Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="add_category_type" name="category_type" required>
                                <option value="both">Both (Parts & Services)</option>
                                <option value="parts">Parts Only</option>
                                <option value="services">Services Only</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="add_description" class="form-label">Description</label>
                            <textarea class="form-control" id="add_description" name="description" rows="3" placeholder="Brief description of this category..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_is_active" class="form-label">Status</label>
                            <select class="form-select" id="add_is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addCategoryForm" class="btn btn-success">Add Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_category_id" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="edit_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required maxlength="255">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_category_type" class="form-label">Category Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_category_type" name="category_type" required>
                                <option value="both">Both (Parts & Services)</option>
                                <option value="parts">Parts Only</option>
                                <option value="services">Services Only</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" placeholder="Brief description of this category..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_is_active" class="form-label">Status</label>
                            <select class="form-select" id="edit_is_active" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editCategoryForm" class="btn btn-primary">Update Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category "<strong id="deleteCategoryName"></strong>"?</p>
                <p class="text-muted small">This action cannot be undone. Categories that are in use by parts or services cannot be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCategory">Delete Category</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let deleteId = null;
    let editId = null;
    
    // Status filter change
    const statusFilter = document.getElementById('categoryStatusFilter');
    statusFilter.addEventListener('change', function() {
        const status = this.value;
        const search = document.getElementById('categorySearch').value;
        window.location.href = `<?= base_url('settings/categories') ?>?status=${status}&search=${encodeURIComponent(search)}`;
    });
    
    // Search functionality
    const searchInput = document.getElementById('categorySearch');
    searchInput.addEventListener('keyup', function(e) {
        if (e.keyCode === 13) { // Enter key
            const status = statusFilter.value;
            const search = this.value;
            window.location.href = `<?= base_url('settings/categories') ?>?status=${status}&search=${encodeURIComponent(search)}`;
        }
    });
    
    // Add Category Form
    const addCategoryForm = document.getElementById('addCategoryForm');
    addCategoryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const addModal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                addModal.hide();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to add category'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: Failed to add category');
        });
    });
    
    // Edit Category - Use event delegation for dynamically loaded content
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-category')) {
            const button = e.target.closest('.edit-category');
            editId = button.dataset.id;
            console.log('Edit button clicked, ID:', editId);
            
            // Test if modal exists
            const editModal = document.getElementById('editCategoryModal');
            if (!editModal) {
                console.error('Edit modal not found in DOM');
                return;
            }
            
            fetch(`<?= base_url('settings/categories/get/') ?>${editId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                console.log('Edit AJAX response:', data);
                if (data.success) {
                    const category = data.category;
                    document.getElementById('edit_category_id').value = category.id;
                    document.getElementById('edit_name').value = category.name;
                    document.getElementById('edit_category_type').value = category.category_type;
                    document.getElementById('edit_description').value = category.description;
                    document.getElementById('edit_is_active').value = category.is_active;
                    
                    document.getElementById('editCategoryForm').action = `<?= base_url('settings/categories/update/') ?>${editId}`;
                    
                    // Open the edit modal after data is loaded
                    console.log('Opening edit modal');
                    const modal = new bootstrap.Modal(editModal);
                    modal.show();
                } else {
                    console.error('Edit failed:', data.message);
                    alert('Error: ' + (data.message || 'Failed to load category'));
                }
            })
            .catch(error => {
                console.error('Edit Error:', error);
                alert('Error: Failed to load category');
            });
        }
    });
    
    // Update Category Form
    const editCategoryForm = document.getElementById('editCategoryForm');
    editCategoryForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        console.log('Form action:', this.action);
        console.log('Form data:', Object.fromEntries(formData.entries()));
        
        fetch(this.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.json();
        })
        .then(data => {
            console.log('Update response:', data);
            if (data.success) {
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editCategoryModal'));
                editModal.hide();
                location.reload();
            } else {
                console.error('Update failed:', data);
                alert('Error: ' + (data.message || 'Failed to update category') + (data.errors ? '\nErrors: ' + JSON.stringify(data.errors) : ''));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Error: Failed to update category - ' + error.message);
        });
    });
    
    // Delete Category - Use event delegation for dynamically loaded content
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-category')) {
            const button = e.target.closest('.delete-category');
            deleteId = button.dataset.id;
            const categoryName = button.dataset.name;
            
            document.getElementById('deleteCategoryName').textContent = categoryName;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
            deleteModal.show();
        }
    });
    
    // Confirm Delete
    const confirmDeleteBtn = document.getElementById('confirmDeleteCategory');
    confirmDeleteBtn.addEventListener('click', function() {
        if (deleteId) {
            fetch(`<?= base_url('settings/categories/delete/') ?>${deleteId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteCategoryModal'));
                    deleteModal.hide();
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete category'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: Failed to delete category');
            });
        }
    });
    
    // Reset forms when modals are closed
    const addModal = document.getElementById('addCategoryModal');
    addModal.addEventListener('hidden.bs.modal', function() {
        addCategoryForm.reset();
    });
    
    const editModal = document.getElementById('editCategoryModal');
    editModal.addEventListener('hidden.bs.modal', function() {
        editCategoryForm.reset();
        editId = null;
    });
    
    const deleteModal = document.getElementById('deleteCategoryModal');
    deleteModal.addEventListener('hidden.bs.modal', function() {
        deleteId = null;
        document.getElementById('deleteCategoryName').textContent = '';
    });
});
</script>

<?= $this->endSection() ?>
