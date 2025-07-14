// Assets management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const assetForm = document.getElementById('assetForm');
    const createModal = new bootstrap.Modal(document.getElementById('createAssetModal'));
    
    // Form submission
    assetForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(assetForm);
        const assetId = document.getElementById('assetId').value;
        
        const url = assetId ? 
            `${baseUrl}/customers/assets/update/${assetId}` : 
            `${baseUrl}/customers/assets/create`;
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createModal.hide();
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                if (data.errors) {
                    displayFormErrors(data.errors);
                } else {
                    showAlert('danger', data.message);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while saving the asset.');
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchAssets');
    const statusFilter = document.getElementById('statusFilter');
    const companyFilter = document.getElementById('companyFilter');
    
    if (searchInput && statusFilter && companyFilter) {
        let searchTimeout;
        
        function performSearch() {
            const searchTerm = searchInput.value;
            const status = statusFilter.value;
            const companyId = companyFilter.value;
            
            const params = new URLSearchParams();
            if (searchTerm) params.append('q', searchTerm);
            if (status) params.append('status', status);
            if (companyId) params.append('company_id', companyId);
            
            fetch(`${baseUrl}/customers/assets/search?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAssetsTable(data.data);
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            });
        }
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300);
        });
        
        statusFilter.addEventListener('change', performSearch);
        companyFilter.addEventListener('change', performSearch);
    }

    // Company selection change handler to filter contacts
    const companySelect = document.getElementById('company_id');
    const contactSelect = document.getElementById('contact_id');
    
    if (companySelect && contactSelect) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            
            // Reset contact selection
            contactSelect.innerHTML = '<option value="">Search Contact</option>';
            
            if (companyId) {
                // Filter contacts by company
                const allContactOptions = contactSelect.querySelectorAll('option[data-company]');
                
                // Re-populate with contacts from selected company
                fetch(`${baseUrl}/customers/assets/contacts/company/${companyId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.data.forEach(contact => {
                            const option = document.createElement('option');
                            option.value = contact.id;
                            option.textContent = `${contact.first_name} ${contact.last_name}`;
                            contactSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading contacts:', error);
                });
            }
        });
    }

    // Load parent assets when modal opens
    document.getElementById('createAssetModal').addEventListener('shown.bs.modal', function() {
        loadParentAssets();
    });

    // Search button handlers (placeholders for future implementation)
    document.getElementById('productSearchBtn')?.addEventListener('click', function() {
        // Placeholder for product search functionality
        showAlert('info', 'Product search functionality coming soon!');
    });

    document.getElementById('parentAssetSearchBtn')?.addEventListener('click', function() {
        // Placeholder for parent asset search functionality
        showAlert('info', 'Parent asset search functionality coming soon!');
    });

    document.getElementById('companySearchBtn')?.addEventListener('click', function() {
        // Placeholder for company search functionality
        showAlert('info', 'Company search functionality coming soon!');
    });

    document.getElementById('contactSearchBtn')?.addEventListener('click', function() {
        // Placeholder for contact search functionality
        showAlert('info', 'Contact search functionality coming soon!');
    });

    document.getElementById('addressSearchBtn')?.addEventListener('click', function() {
        // Placeholder for address search/lookup functionality
        showAlert('info', 'Address lookup functionality coming soon!');
    });
});

function editAsset(id) {
    fetch(`${baseUrl}/customers/assets/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const asset = data.data;
            
            // Populate form fields
            document.getElementById('assetId').value = asset.id;
            document.getElementById('asset_name').value = asset.asset_name || '';
            document.getElementById('asset_number').value = asset.asset_number || '';
            document.getElementById('description').value = asset.description || '';
            document.getElementById('product').value = asset.product || '';
            document.getElementById('parent_asset').value = asset.parent_asset || '';
            document.getElementById('giai').value = asset.giai || '';
            document.getElementById('ordered_date').value = asset.ordered_date || '';
            document.getElementById('installation_date').value = asset.installation_date || '';
            document.getElementById('purchased_date').value = asset.purchased_date || '';
            document.getElementById('warranty_expiration').value = asset.warranty_expiration || '';
            document.getElementById('company_id').value = asset.company_id || '';
            document.getElementById('contact_id').value = asset.contact_id || '';
            document.getElementById('address').value = asset.address || '';
            document.getElementById('status').value = asset.status || 'active';
            
            // Load parent assets and set the value
            loadParentAssets(asset.id, asset.parent_asset);
            
            // If company is selected, load its contacts
            if (asset.company_id) {
                loadContactsByCompany(asset.company_id, asset.contact_id);
            }
            
            // Update modal title and button
            document.getElementById('createAssetModalLabel').textContent = 'Edit Asset';
            document.getElementById('saveAssetBtn').textContent = 'Update Asset';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('createAssetModal'));
            modal.show();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Failed to load asset data.');
    });
}

function deleteAsset(id) {
    if (confirm('Are you sure you want to delete this asset? This action cannot be undone.')) {
        fetch(`${baseUrl}/customers/assets/delete/${id}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Failed to delete asset.');
        });
    }
}

function loadParentAssets(excludeId = null, selectedValue = null) {
    const parentAssetSelect = document.getElementById('parent_asset');
    if (!parentAssetSelect) return;

    // Clear existing options except the first one
    parentAssetSelect.innerHTML = '<option value="">Search Parent Asset</option>';

    // For now, we'll use a simple approach since we don't have the getAvailableParentAssets endpoint
    // This would ideally be an API call to get available parent assets
    fetch(`${baseUrl}/customers/assets/search`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            data.data.forEach(asset => {
                if (!excludeId || asset.id != excludeId) {
                    const option = document.createElement('option');
                    option.value = asset.id;
                    option.textContent = `${asset.asset_name}${asset.asset_number ? ' (' + asset.asset_number + ')' : ''}`;
                    if (selectedValue && asset.id == selectedValue) {
                        option.selected = true;
                    }
                    parentAssetSelect.appendChild(option);
                }
            });
        }
    })
    .catch(error => {
        console.error('Error loading parent assets:', error);
    });
}

function loadContactsByCompany(companyId, selectedContactId = null) {
    const contactSelect = document.getElementById('contact_id');
    if (!contactSelect) return;

    fetch(`${baseUrl}/customers/assets/contacts/company/${companyId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear existing options
            contactSelect.innerHTML = '<option value="">Search Contact</option>';
            
            // Add contacts for the selected company
            data.data.forEach(contact => {
                const option = document.createElement('option');
                option.value = contact.id;
                option.textContent = `${contact.first_name} ${contact.last_name}`;
                if (selectedContactId && contact.id == selectedContactId) {
                    option.selected = true;
                }
                contactSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading contacts:', error);
    });
}

function updateAssetsTable(assets) {
    const tbody = document.getElementById('assetsTableBody');
    
    if (assets.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">No assets found</td></tr>';
        return;
    }
    
    tbody.innerHTML = assets.map(asset => {
        const statusClass = {
            'active': 'bg-success',
            'inactive': 'bg-secondary',
            'maintenance': 'bg-warning text-dark',
            'retired': 'bg-danger'
        };

        const companyBadge = asset.company_name ? 
            `<span class="badge bg-light text-dark">${escapeHtml(asset.company_name)}</span>` : 
            '<span class="text-muted">-</span>';

        let contactInfo = '<span class="text-muted">-</span>';
        if (asset.contact_name) {
            contactInfo = escapeHtml(asset.contact_name);
            if (asset.contact_phone) {
                contactInfo += `<br><small class="text-muted">${escapeHtml(asset.contact_phone)}</small>`;
            }
        }

        let warrantyInfo = '<span class="text-muted">-</span>';
        if (asset.warranty_expiration) {
            const warrantyDate = new Date(asset.warranty_expiration);
            const today = new Date();
            const isExpired = warrantyDate < today;
            const isExpiringSoon = (warrantyDate - today) < (30 * 24 * 60 * 60 * 1000);
            
            const dateStr = warrantyDate.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            
            const cssClass = isExpired ? 'text-danger' : (isExpiringSoon ? 'text-warning' : 'text-muted');
            warrantyInfo = `<span class="${cssClass}">${dateStr}</span>`;
        }

        const createdDate = new Date(asset.created_at).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
        
        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-gear"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">${escapeHtml(asset.asset_name)}</h6>
                            <small class="text-muted">ID: ${asset.id}</small>
                        </div>
                    </div>
                </td>
                <td>${escapeHtml(asset.asset_number || '-')}</td>
                <td>${escapeHtml(asset.product || '-')}</td>
                <td>${companyBadge}</td>
                <td>${contactInfo}</td>
                <td>
                    <span class="badge ${statusClass[asset.status] || 'bg-secondary'}">
                        ${asset.status.charAt(0).toUpperCase() + asset.status.slice(1)}
                    </span>
                </td>
                <td>${warrantyInfo}</td>
                <td>
                    <small class="text-muted">${createdDate}</small>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editAsset(${asset.id})" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAsset(${asset.id})" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showAlert(type, message) {
    // Create alert element
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Insert at top of container
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

function displayFormErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    // Display new errors
    for (const [field, message] of Object.entries(errors)) {
        const input = document.getElementById(field);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = message;
            input.parentNode.appendChild(feedback);
        }
    }
}

// Reset modal form when hidden
document.getElementById('createAssetModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('assetForm').reset();
    document.getElementById('assetId').value = '';
    document.getElementById('createAssetModalLabel').textContent = 'Create Asset';
    document.getElementById('saveAssetBtn').textContent = 'Save';
    
    // Reset contact dropdown
    const contactSelect = document.getElementById('contact_id');
    if (contactSelect) {
        contactSelect.innerHTML = '<option value="">Search Contact</option>';
        // Re-populate with all contacts (this is a simplified approach)
        // In a real application, you might want to keep a cache of all contacts
    }
    
    // Clear form errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
});

// Set base URL (should be available globally)
const baseUrl = window.location.origin;
