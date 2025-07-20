/**
 * Work Orders JavaScript functionality
 */

// Work Order management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const workOrderForm = document.getElementById('workOrderForm');
    const createModalElement = document.getElementById('createWorkOrderModal');
    const createModal = createModalElement ? new bootstrap.Modal(createModalElement) : null;
    
    // Form submission
    if (workOrderForm) {
        workOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(workOrderForm);
            const workOrderId = document.getElementById('workOrderId').value;
            
            const url = workOrderId ? 
                `${baseUrl}/work-order-management/work-orders/update/${workOrderId}` : 
                `${baseUrl}/work-order-management/work-orders/create`;
            
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
                showAlert('danger', 'An error occurred while saving the work order.');
            });
        });
    }
    
    // Search functionality
    const searchInput = document.getElementById('searchWorkOrders');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const companyFilter = document.getElementById('companyFilter');
    
    if (searchInput && statusFilter && priorityFilter && companyFilter) {
        let searchTimeout;
        
        function performSearch() {
            const searchTerm = searchInput.value;
            const status = statusFilter.value;
            const priority = priorityFilter.value;
            const companyId = companyFilter.value;
            
            const params = new URLSearchParams();
            if (searchTerm) params.append('q', searchTerm);
            if (status) params.append('status', status);
            if (priority) params.append('priority', priority);
            if (companyId) params.append('company_id', companyId);
            
            fetch(`${baseUrl}/work-order-management/work-orders/search?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateWorkOrdersTable(data.data);
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
        priorityFilter.addEventListener('change', performSearch);
        companyFilter.addEventListener('change', performSearch);
    }
    
    // Company selection change handler for contact filtering
    const companySelect = document.getElementById('company_id');
    const contactSelect = document.getElementById('contact_id');
    
    if (companySelect && contactSelect) {
        companySelect.addEventListener('change', function() {
            const selectedCompanyId = this.value;
            const contactOptions = contactSelect.querySelectorAll('option');
            
            contactOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }
                
                const optionCompanyId = option.getAttribute('data-company');
                if (selectedCompanyId === '' || optionCompanyId === selectedCompanyId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Reset contact selection if current selection is not valid
            const currentContact = contactSelect.value;
            if (currentContact) {
                const currentOption = contactSelect.querySelector(`option[value="${currentContact}"]`);
                if (currentOption && currentOption.style.display === 'none') {
                    contactSelect.value = '';
                    // Clear contact info fields
                    clearContactInfo();
                }
            }
        });
        
        // Contact selection handler to populate contact info
        contactSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const email = selectedOption.getAttribute('data-email') || '';
                const phone = selectedOption.getAttribute('data-phone') || '';
                const mobile = selectedOption.getAttribute('data-mobile') || '';
                
                // Populate the contact info fields
                const emailField = document.getElementById('email');
                const phoneField = document.getElementById('phone');
                const mobileField = document.getElementById('mobile');
                
                if (emailField) emailField.value = email;
                if (phoneField) phoneField.value = phone;
                if (mobileField) mobileField.value = mobile;
            } else {
                clearContactInfo();
            }
        });
    }
    
    // Helper function to clear contact info fields
    function clearContactInfo() {
        const emailField = document.getElementById('email');
        const phoneField = document.getElementById('phone');
        const mobileField = document.getElementById('mobile');
        
        if (emailField) emailField.value = '';
        if (phoneField) phoneField.value = '';
        if (mobileField) mobileField.value = '';
    }
    
    // Reset modal when closed
    const modalElement = document.getElementById('createWorkOrderModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            workOrderForm.reset();
            document.getElementById('workOrderId').value = '';
            document.getElementById('createWorkOrderModalLabel').textContent = 'Create Work Order';
            document.getElementById('saveWorkOrderBtn').textContent = 'Create Work Order';
            
            // Reset default values
            document.getElementById('priority').value = 'medium';
            
            // Clear any error messages
            const errorElements = document.querySelectorAll('.text-danger');
            errorElements.forEach(element => element.remove());
            
            // Remove error classes
            const errorInputs = document.querySelectorAll('.is-invalid');
            errorInputs.forEach(input => input.classList.remove('is-invalid'));
        });
    }
});

function updateWorkOrdersTable(workOrders) {
    const tbody = document.getElementById('workOrdersTableBody');
    
    if (workOrders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">No work orders found</td></tr>';
        return;
    }
    
    tbody.innerHTML = workOrders.map(workOrder => {
        const companyBadge = workOrder.client_name ? 
            `<span class="badge bg-light text-dark">${escapeHtml(workOrder.client_name)}</span>` : 
            '<span class="text-muted">-</span>';
        
        const contactName = (workOrder.contact_first_name && workOrder.contact_last_name) ?
            `${escapeHtml(workOrder.contact_first_name)} ${escapeHtml(workOrder.contact_last_name)}` :
            '<span class="text-muted">-</span>';
        
        let statusClass = '';
        let statusText = '';

        switch (workOrder.status) {
            case 'pending':
                statusClass = 'bg-warning';
                statusText = 'Pending';
                break;
            case 'in_progress':
                statusClass = 'bg-info';
                statusText = 'In Progress';
                break;
            case 'completed':
                statusClass = 'bg-success';
                statusText = 'Completed';
                break;
            default:
                statusClass = 'bg-secondary';
                statusText = 'Unknown';
        }
        
        let priorityClass = '';
        let priorityText = '';

        switch (workOrder.priority || 'medium') {
            case 'low':
                priorityClass = 'bg-secondary';
                priorityText = 'Low';
                break;
            case 'medium':
                priorityClass = 'bg-warning';
                priorityText = 'Medium';
                break;
            case 'high':
                priorityClass = 'bg-danger';
                priorityText = 'High';
                break;
            default:
                priorityClass = 'bg-secondary';
                priorityText = 'Medium';
        }
        
        
        const createdDate = new Date(workOrder.created_at).toLocaleDateString('en-US', {
            month: 'short', 
            day: 'numeric', 
            year: 'numeric'
        });
        
        return `
            <tr>
                <td>
                    <div class="fw-medium">
                        <a href="${baseUrl}/work-order-management/work-orders/view/${workOrder.id}" class="text-decoration-none">
                            ${escapeHtml(workOrder.work_order_number || '')}
                        </a>
                    </div>
                </td>
                <td>
                    <span class="fw-medium">${escapeHtml(workOrder.summary)}</span>
                </td>
                <td>${companyBadge}</td>
                <td>${contactName}</td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td><span class="badge ${priorityClass}">${priorityText}</span></td>
                <td><div class="fw-medium">${createdDate}</div></td>
                <td><div class="fw-medium">${escapeHtml(workOrder.created_by_name || 'Unknown')}</div></td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="viewWorkOrder(${workOrder.id})" title="View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editWorkOrder(${workOrder.id})" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteWorkOrder(${workOrder.id})" title="Delete">
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

// Function to display form validation errors
function displayFormErrors(errors) {
    // Clear previous error messages
    const errorElements = document.querySelectorAll('.text-danger');
    errorElements.forEach(element => element.remove());
    
    // Remove previous error classes
    const errorInputs = document.querySelectorAll('.is-invalid');
    errorInputs.forEach(input => input.classList.remove('is-invalid'));
    
    // Display new errors
    Object.keys(errors).forEach(fieldName => {
        const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('is-invalid');
            
            // Create error message element
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger small mt-1';
            errorDiv.textContent = errors[fieldName];
            
            // Insert error message after the field
            if (field.parentElement.classList.contains('input-group')) {
                field.parentElement.parentElement.appendChild(errorDiv);
            } else {
                field.parentElement.appendChild(errorDiv);
            }
        }
    });
}

function editWorkOrder(id) {
    // Check if we're on the view page (no form elements)
    if (!document.getElementById('workOrderId')) {
        // If no form exists, redirect to the work orders list page instead
        window.location.href = `${baseUrl}/work-order-management/work-orders`;
        return;
    }
    
    fetch(`${baseUrl}/work-order-management/work-orders/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const workOrder = data.data;
            
            // Populate basic form fields
            document.getElementById('workOrderId').value = workOrder.id;
            document.getElementById('summary').value = workOrder.summary || '';
            document.getElementById('priority').value = workOrder.priority || 'medium';
            document.getElementById('type').value = workOrder.type || '';
            document.getElementById('due_date').value = workOrder.due_date || '';
            document.getElementById('company_id').value = workOrder.company_id || '';
            document.getElementById('contact_id').value = workOrder.contact_id || '';
            document.getElementById('email').value = workOrder.email || '';
            document.getElementById('phone').value = workOrder.phone || '';
            document.getElementById('mobile').value = workOrder.mobile || '';
            document.getElementById('asset_id').value = workOrder.asset_id || '';
            document.getElementById('service_address').value = workOrder.service_address || '';
            document.getElementById('billing_address').value = workOrder.billing_address || '';
            document.getElementById('preferred_date_1').value = workOrder.preferred_date_1 || '';
            document.getElementById('preferred_date_2').value = workOrder.preferred_date_2 || '';
            document.getElementById('preferred_time').value = workOrder.preferred_time || '';
            document.getElementById('preference_note').value = workOrder.preference_note || '';
            
            // Filter contacts based on selected company
            const companySelect = document.getElementById('company_id');
            const contactSelect = document.getElementById('contact_id');
            
            if (companySelect.value) {
                const contactOptions = contactSelect.querySelectorAll('option');
                contactOptions.forEach(option => {
                    if (option.value === '') {
                        option.style.display = 'block';
                        return;
                    }
                    
                    const optionCompanyId = option.getAttribute('data-company');
                    if (companySelect.value === '' || optionCompanyId === companySelect.value) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }
            
            // Update modal title and button
            document.getElementById('createWorkOrderModalLabel').textContent = 'Edit Work Order';
            document.getElementById('saveWorkOrderBtn').textContent = 'Update Work Order';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('createWorkOrderModal'));
            modal.show();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Failed to load work order data.');
    });
}

function viewWorkOrder(id) {
    // Redirect to the work order view page
    window.location.href = `${baseUrl}/work-order-management/work-orders/view/${id}`;
}

function deleteWorkOrder(id) {
    if (confirm('Are you sure you want to delete this work order? This action cannot be undone.')) {
        fetch(`${baseUrl}/work-order-management/work-orders/delete/${id}`, {
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
            showAlert('danger', 'Failed to delete work order.');
        });
    }
}

// Function to show alert messages
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert-dismissible');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the page
    const container = document.querySelector('.container-fluid');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-hide success messages after 3 seconds
        if (type === 'success') {
            setTimeout(() => {
                if (alertDiv && alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }
    }
}
