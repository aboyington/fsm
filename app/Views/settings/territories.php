<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Territories</h4>
    <p class="text-muted">Define your organization's Territories. Territories help you map field technicians and dispatchers to service areas, thereby enabling assignment of the right technician to service requests received in a region.</p>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
            <select class="form-select" style="width: auto;" id="statusFilter" onchange="filterTerritories()">
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active Territories</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive Territories</option>
                <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All Territories</option>
            </select>
            <input type="search" class="form-control" style="width: 300px;" placeholder="Search" id="searchInput" value="<?= esc($search) ?>" onkeyup="handleSearch(event)">
        </div>
        <button class="btn btn-success" onclick="openNewTerritoryModal()">
            <i class="bi bi-plus"></i> New Territory
        </button>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Territory Name</th>
                        <th>Description</th>
                        <th>Created By</th>
                        <th>Created Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="territoriesTableBody">
                    <?php if (empty($territories)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #6b7280;">
                                No territories found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($territories as $territory): ?>
                            <tr class="territory-row" data-id="<?= $territory['id'] ?>">
                                <td><?= esc($territory['name']) ?></td>
                                <td><?= esc($territory['description'] ?? '') ?></td>
                                <td><?= esc($territory['creator_name'] ?? 'System') ?></td>
                                <td><?= date('M d, Y h:i A', strtotime($territory['created_at'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-link text-primary" onclick="editTerritory(<?= $territory['id'] ?>)" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger" onclick="deleteTerritory(<?= $territory['id'] ?>, '<?= esc($territory['name']) ?>')" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Territory Modal -->
<div class="modal fade" id="editTerritoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Territory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTerritoryForm" onsubmit="updateTerritory(event)">
                <input type="hidden" name="id" id="editTerritoryId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Territory Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="editTerritoryName"
                               class="form-control" 
                               required
                               placeholder="Enter territory name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Street</label>
                        <input type="text" 
                               name="street" 
                               id="editTerritoryStreet"
                               class="form-control" 
                               placeholder="Enter street address">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" 
                               name="city" 
                               id="editTerritoryCity"
                               class="form-control" 
                               placeholder="Enter city">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <input type="text" 
                               name="state" 
                               id="editTerritoryState"
                               class="form-control" 
                               placeholder="Enter state/province">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Zip Code</label>
                        <input type="text" 
                               name="zip_code" 
                               id="editTerritoryZipCode"
                               class="form-control" 
                               placeholder="Enter zip/postal code">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" 
                               name="country" 
                               id="editTerritoryCountry"
                               class="form-control" 
                               placeholder="Enter country">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" 
                                 id="editTerritoryDescription"
                                 class="form-control" 
                                 rows="4"
                                 placeholder="Enter territory description"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="editTerritoryStatus" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Territory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- New Territory Modal -->
<div class="modal fade" id="newTerritoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Territory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="newTerritoryForm" onsubmit="saveNewTerritory(event)">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Territory Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               required
                               placeholder="Enter territory name">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Street</label>
                        <input type="text" 
                               name="street" 
                               class="form-control" 
                               placeholder="Enter street address">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" 
                               name="city" 
                               class="form-control" 
                               placeholder="Enter city">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <input type="text" 
                               name="state" 
                               class="form-control" 
                               placeholder="Enter state/province">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Zip Code</label>
                        <input type="text" 
                               name="zip_code" 
                               class="form-control" 
                               placeholder="Enter zip/postal code">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <input type="text" 
                               name="country" 
                               class="form-control" 
                               placeholder="Enter country">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" 
                                 class="form-control" 
                                 rows="4"
                                 placeholder="Enter territory description"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Territory</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterTerritories() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    
    window.location.href = `<?= base_url('settings/territories') ?>?status=${status}&search=${encodeURIComponent(search)}`;
}

function handleSearch(event) {
    if (event.key === 'Enter') {
        filterTerritories();
    }
}

function openNewTerritoryModal() {
    const modal = new bootstrap.Modal(document.getElementById('newTerritoryModal'));
    modal.show();
    
    // Focus on the first input when modal is shown
    document.getElementById('newTerritoryModal').addEventListener('shown.bs.modal', function () {
        document.querySelector('#newTerritoryModal input[name="name"]').focus();
    });
}

function saveNewTerritory(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Add CSRF token
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch('<?= base_url('settings/territories/add') ?>', {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and reload - no need to close modal since we're reloading
            alert(data.message || 'Territory added successfully');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to add territory');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the territory');
    });
}

function editTerritory(id) {
    // Prevent event bubbling
    event.stopPropagation();
    
    // Fetch territory data
    fetch(`<?= base_url('settings/territories/get') ?>/${id}`, {
        method: 'GET',
        credentials: 'same-origin',  // Include cookies for session
        headers: {
            'X-Requested-With': 'XMLHttpRequest'  // Mark as AJAX request
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response URL:', response.url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const territory = data.territory;
            document.getElementById('editTerritoryId').value = territory.id;
            document.getElementById('editTerritoryName').value = territory.name;
            document.getElementById('editTerritoryStreet').value = territory.street || '';
            document.getElementById('editTerritoryCity').value = territory.city || '';
            document.getElementById('editTerritoryState').value = territory.state || '';
            document.getElementById('editTerritoryZipCode').value = territory.zip_code || '';
            document.getElementById('editTerritoryCountry').value = territory.country || '';
            document.getElementById('editTerritoryDescription').value = territory.description || '';
            document.getElementById('editTerritoryStatus').value = territory.status;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editTerritoryModal'));
            modal.show();
        } else {
            alert(data.message || 'Failed to load territory data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while loading territory data: ' + error.message);
    });
}

function updateTerritory(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Add CSRF token
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    const territoryId = formData.get('id');
    
    // Log form data for debugging
    console.log('Updating territory ID:', territoryId);
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value);
    }
    
    fetch(`<?= base_url('settings/territories/update') ?>/${territoryId}`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
    })
    .then(response => {
        console.log('Update response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // First, let's see what type of content we're getting
        const contentType = response.headers.get('content-type');
        console.log('Content-Type:', contentType);
        
        // Check if response is ok before trying to parse JSON
        if (!response.ok) {
            // Try to get the response text to see what the server returned
            return response.text().then(text => {
                console.error('Error response text:', text);
                throw new Error(`Server error: ${response.status} - ${text}`);
            });
        }
        
        // Even if response is ok, check if it's JSON
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // If not JSON, get the text to see what it is
            return response.text().then(text => {
                console.error('Non-JSON response:', text);
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 200));
            });
        }
    })
    .then(data => {
        console.log('Update response data:', data);
        if (data && data.success) {
            // Show success message and reload - no need to close modal since we're reloading
            alert(data.message || 'Territory updated successfully');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to update territory');
            if (data && data.errors) {
                console.error('Validation errors:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Catch block error:', error);
        console.error('Error message:', error.message);
        console.error('Error stack:', error.stack);
        alert('An error occurred while updating the territory: ' + error.message);
    });
}

function deleteTerritory(id, name) {
    // Prevent event bubbling
    event.stopPropagation();
    
    if (confirm(`Are you sure you want to delete the territory "${name}"?`)) {
        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        
        fetch(`<?= base_url('settings/territories/delete') ?>/${id}`, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Territory deleted successfully');
                window.location.reload();
            } else {
                alert(data.message || 'Failed to delete territory');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the territory');
        });
    }
}

// Remove the row click event since we have specific edit/delete buttons
document.addEventListener('DOMContentLoaded', function() {
    // Add any additional initialization here if needed
});
</script>

<?= $this->endSection() ?>
