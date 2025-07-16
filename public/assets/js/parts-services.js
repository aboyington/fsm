document.addEventListener('DOMContentLoaded', function() {
    const typeFilter = document.getElementById('typeFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const partsServicesTable = document.getElementById('partsServicesTable');
    const createForm = document.getElementById('createForm');    

    // Handle Type Filter Change
    typeFilter.addEventListener('change', function() {
        loadPartsServicesData();
    });

    // Handle Category Filter Change
    categoryFilter.addEventListener('change', function() {
        loadPartsServicesData();
    });

    // Handle Status Filter Change
    statusFilter.addEventListener('change', function() {
        loadPartsServicesData();
    });

    // Handle Search Input Typing
    searchInput.addEventListener('input', function() {
        loadPartsServicesData();
    });

    // Initialize Table Data and Insights
    loadPartsServicesData();
    loadInsights();

    async function loadPartsServicesData() {
        try {
            const type = typeFilter.value;
            const category = categoryFilter.value;
            const status = statusFilter.value;
            const search = searchInput.value;

            const response = await fetch(`${window.location.origin}/fsm/public/parts-services/data?type=${type}&category=${category}&status=${status}&search=${search}`);
            const data = await response.json();

            populateTable(data);
        } catch (error) {
            console.error('Failed to load data:', error);
        }
    }

    function populateTable(data) {
        const tbody = partsServicesTable.querySelector('tbody');
        tbody.innerHTML = '';

        data.data.forEach(item => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${item.type === 'part' ? 'Part' : 'Service'}</td>
                <td>${item.name}</td>
                <td>${item.sku}</td>
                <td>${item.category}</td>
                <td>${item.unit_price.toFixed(2)}</td>
                <td>${item.type === 'part' ? item.quantity_on_hand : item.duration_minutes + ' min'}</td>
                <td>${item.is_active ? 'Active' : 'Inactive'}</td>
                <td>
                    <button class='btn btn-info btn-sm' onclick="editItem(${item.id}, '${item.type}')">Edit</button>
                    <button class='btn btn-danger btn-sm' onclick="deleteItem(${item.id}, '${item.type}')">Delete</button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    }

    // Handle form submissions
    createForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(createForm);
        const type = formData.get('type');
        const itemId = formData.get('id');
        const method = 'POST';
        const url = itemId ? `${window.location.origin}/fsm/public/parts-services/update/${itemId}` : `${window.location.origin}/fsm/public/parts-services/create`;

        fetch(url, {
            method: method,
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#createModal').modal('hide');
                loadPartsServicesData();
                showSuccess(data.message);
            } else {
                handleFormErrors(data);
            }
        })
        .catch(error => {
            console.error('Form submission error:', error);
            showError('An unexpected error occurred. Please try again.');
        });
    });

});

function setModalType(type) {
    const itemTypeInput = document.getElementById('itemType');
    const partFields = document.getElementById('partFields');
    const serviceFields = document.getElementById('serviceFields');

    itemTypeInput.value = type;

    if (type === 'part') {
        partFields.style.display = 'block';
        serviceFields.style.display = 'none';
    } else {
        partFields.style.display = 'none';
        serviceFields.style.display = 'block';
    }
    
    // Reset form for new item creation
    resetForm();
    document.getElementById('createModalLabel').textContent = `Add New ${type === 'part' ? 'Part' : 'Service'}`;
    document.getElementById('submitBtn').textContent = 'Create Item';
}

function resetForm() {
    const form = document.getElementById('createForm');
    form.reset();
    document.getElementById('itemId').value = '';
    document.getElementById('isActive').checked = true;
    
    // Clear any existing validation errors
    clearFormErrors();
}

function populateEditForm(item) {
    // Set the item type and show appropriate fields
    setModalType(item.type);
    
    // Set item ID for update
    document.getElementById('itemId').value = item.id;
    
    // Populate common fields
    document.getElementById('itemName').value = item.name || '';
    document.getElementById('itemSku').value = item.sku || '';
    document.getElementById('itemCategory').value = item.category || '';
    document.getElementById('itemUnitPrice').value = item.unit_price || '';
    document.getElementById('itemCostPrice').value = item.cost_price || '';
    document.getElementById('itemDescription').value = item.description || '';
    document.getElementById('isActive').checked = item.is_active == 1;
    
    // Populate part-specific fields
    if (item.type === 'part') {
        document.getElementById('quantityOnHand').value = item.quantity_on_hand || '';
        document.getElementById('minimumStock').value = item.minimum_stock || '';
        document.getElementById('supplier').value = item.supplier || '';
        document.getElementById('manufacturer').value = item.manufacturer || '';
        document.getElementById('manufacturerPartNumber').value = item.manufacturer_part_number || '';
        document.getElementById('warrantyPeriod').value = item.warranty_period || '';
        document.getElementById('weight').value = item.weight || '';
        document.getElementById('dimensions').value = item.dimensions || '';
    }
    
    // Populate service-specific fields
    if (item.type === 'service') {
        document.getElementById('durationMinutes').value = item.duration_minutes || '';
        document.getElementById('isTaxable').checked = item.is_taxable == 1;
    }
}

async function editItem(id, type) {
    try {
        // Fetch the item data
        const response = await fetch(`${window.location.origin}/fsm/public/parts-services/show/${id}?type=${type}`);
        const result = await response.json();
        
        if (result.success) {
            const item = result.data;
            
            // Populate the form with the item data
            populateEditForm(item);
            
            // Update modal title
            document.getElementById('createModalLabel').textContent = `Edit ${type === 'part' ? 'Part' : 'Service'}`;
            document.getElementById('submitBtn').textContent = 'Update Item';
            
            // Show the modal
            $('#createModal').modal('show');
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Failed to load item for editing:', error);
        alert('Failed to load item for editing');
    }
}

function deleteItem(id, type) {
    if (confirm('Are you sure you want to delete this item?')) {
        fetch(`${window.location.origin}/fsm/public/parts-services/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPartsServicesData();
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}


/**
 * Handle form validation errors
 */
function handleFormErrors(response) {
    // Clear any existing error messages
    clearFormErrors();
    
    if (response.errors) {
        // Display validation errors next to form fields
        Object.keys(response.errors).forEach(field => {
            const errorMessage = response.errors[field];
            displayFieldError(field, errorMessage);
        });
        
        // Show a general error message
        showError('Please correct the highlighted errors below.');
    } else if (response.message) {
        // Show the general error message
        showError(response.message);
    } else {
        // Fallback error message
        showError('An error occurred while processing your request.');
    }
}

/**
 * Display error message for a specific field
 */
function displayFieldError(fieldName, errorMessage) {
    // Map server field names to form field IDs
    const fieldMap = {
        'name': 'itemName',
        'sku': 'itemSku',
        'category': 'itemCategory',
        'unit_price': 'itemUnitPrice',
        'cost_price': 'itemCostPrice',
        'description': 'itemDescription',
        'quantity_on_hand': 'quantityOnHand',
        'minimum_stock': 'minimumStock',
        'supplier': 'supplier',
        'manufacturer': 'manufacturer',
        'manufacturer_part_number': 'manufacturerPartNumber',
        'warranty_period': 'warrantyPeriod',
        'weight': 'weight',
        'dimensions': 'dimensions',
        'duration_minutes': 'durationMinutes'
    };
    
    const fieldId = fieldMap[fieldName] || fieldName;
    const field = document.getElementById(fieldId);
    
    if (field) {
        // Add error class to field
        field.classList.add('is-invalid');
        
        // Create or update error message
        let errorElement = document.getElementById(fieldId + '_error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = fieldId + '_error';
            errorElement.className = 'invalid-feedback';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = errorMessage;
    }
}

/**
 * Clear all form errors
 */
function clearFormErrors() {
    // Remove error classes from all fields
    const fields = document.querySelectorAll('.is-invalid');
    fields.forEach(field => {
        field.classList.remove('is-invalid');
    });
    
    // Remove all error messages
    const errorMessages = document.querySelectorAll('.invalid-feedback');
    errorMessages.forEach(msg => {
        msg.remove();
    });
}

/**
 * Show success message
 */
function showSuccess(message) {
    // You can customize this to use a toast notification, modal, or other UI element
    alert('✅ ' + message);
}

/**
 * Show error message
 */
function showError(message) {
    // You can customize this to use a toast notification, modal, or other UI element
    alert('❌ ' + message);
}

// Load and display insights for most used services/parts and low stock alerts
async function loadInsights() {
    try {
        const response = await fetch(`${window.location.origin}/fsm/public/parts-services/insights`);
        const result = await response.json();
        
        if (result.success) {
            const insights = result.data;
            
            // Populate Most Used Services
            const mostUsedServicesContainer = document.getElementById('mostUsedServices');
            if (mostUsedServicesContainer) {
                mostUsedServicesContainer.innerHTML = '';
                if (insights.most_used_services && insights.most_used_services.length > 0) {
                    insights.most_used_services.forEach(service => {
                        const serviceItem = document.createElement('div');
                        serviceItem.className = 'mb-2';
                        serviceItem.innerHTML = `
                            <div class="d-flex justify-content-between">
                                <span>${service.name}</span>
                                <span class="badge bg-primary">${service.usage_count} uses</span>
                            </div>
                        `;
                        mostUsedServicesContainer.appendChild(serviceItem);
                    });
                } else {
                    mostUsedServicesContainer.innerHTML = '<p class="text-muted">No service usage data available</p>';
                }
            }
            
            // Populate Most Used Parts
            const mostUsedPartsContainer = document.getElementById('mostUsedParts');
            if (mostUsedPartsContainer) {
                mostUsedPartsContainer.innerHTML = '';
                if (insights.most_used_parts && insights.most_used_parts.length > 0) {
                    insights.most_used_parts.forEach(part => {
                        const partItem = document.createElement('div');
                        partItem.className = 'mb-2';
                        partItem.innerHTML = `
                            <div class="d-flex justify-content-between">
                                <span>${part.name}</span>
                                <span class="badge bg-success">${part.usage_count} uses</span>
                            </div>
                        `;
                        mostUsedPartsContainer.appendChild(partItem);
                    });
                } else {
                    mostUsedPartsContainer.innerHTML = '<p class="text-muted">No parts usage data available</p>';
                }
            }
            
            // Populate Low Stock Alerts
            const lowStockAlertsContainer = document.getElementById('lowStockAlerts');
            if (lowStockAlertsContainer) {
                lowStockAlertsContainer.innerHTML = '';
                if (insights.low_stock_alerts && insights.low_stock_alerts.length > 0) {
                    insights.low_stock_alerts.forEach(alert => {
                        const alertItem = document.createElement('div');
                        alertItem.className = 'alert alert-warning mb-2';
                        alertItem.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${alert.name}</strong>
                                    <br>
                                    <small>Current: ${alert.current_stock} | Minimum: ${alert.minimum_stock}</small>
                                </div>
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            </div>
                        `;
                        lowStockAlertsContainer.appendChild(alertItem);
                    });
                } else {
                    lowStockAlertsContainer.innerHTML = '<div class="alert alert-info">No low stock alerts at this time</div>';
                }
            }
        }
    } catch (error) {
        console.error('Failed to load insights:', error);
    }
}

/**
 * Export data to CSV
 */
function exportData(type) {
    const url = `${window.location.origin}/fsm/public/parts-services/export/${type}`;
    
    // Create a temporary link and click it to download
    const link = document.createElement('a');
    link.href = url;
    link.download = `${type}-export-${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

/**
 * Show import modal
 */
function showImportModal(type) {
    document.getElementById('importType').value = type;
    document.getElementById('importModalLabel').textContent = `Import ${type.charAt(0).toUpperCase() + type.slice(1)}`;
    
    // Reset form
    document.getElementById('importForm').reset();
    document.getElementById('importType').value = type;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('importModal'));
    modal.show();
}

/**
 * Download CSV template
 */
function downloadTemplate(type) {
    const url = `${window.location.origin}/fsm/public/parts-services/template/${type}`;
    
    // Create a temporary link and click it to download
    const link = document.createElement('a');
    link.href = url;
    link.download = `${type}-template.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

/**
 * Handle import form submission
 */
document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importForm');
    
    if (importForm) {
        importForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(importForm);
            const type = formData.get('type');
            
            const submitBtn = document.getElementById('importSubmitBtn');
            const originalText = submitBtn.textContent;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importing...';
            
            fetch(`${window.location.origin}/fsm/public/parts-services/import`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
                    modal.hide();
                    
                    // Refresh data
                    loadPartsServicesData();
                    
                    // Show success message with import summary
                    let message = data.message;
                    if (data.summary) {
                        message += `\n\nImport Summary:\n- ${data.summary.created || 0} items created\n- ${data.summary.updated || 0} items updated\n- ${data.summary.skipped || 0} items skipped\n- ${data.summary.errors || 0} errors`;
                    }
                    showSuccess(message);
                } else {
                    // Show error with details
                    let errorMessage = data.message || 'Import failed';
                    if (data.errors && data.errors.length > 0) {
                        errorMessage += '\n\nErrors:\n' + data.errors.slice(0, 5).join('\n');
                        if (data.errors.length > 5) {
                            errorMessage += `\n... and ${data.errors.length - 5} more errors`;
                        }
                    }
                    showError(errorMessage);
                }
            })
            .catch(error => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                
                console.error('Import error:', error);
                showError('An unexpected error occurred during import. Please try again.');
            });
        });
    }
});
