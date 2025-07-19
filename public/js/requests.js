// Request management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const requestForm = document.getElementById('requestForm');
    const createModalElement = document.getElementById('createRequestModal');
    const createModal = createModalElement ? new bootstrap.Modal(createModalElement) : null;
    
    // Form submission
    if (requestForm) {
        requestForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(requestForm);
        const requestId = document.getElementById('requestId').value;
        
        const url = requestId ? 
            `${baseUrl}/work-order-management/request/update/${requestId}` : 
            `${baseUrl}/work-order-management/request/create`;
        
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
            showAlert('danger', 'An error occurred while saving the request.');
        });
        });
    }
    
    // Search functionality
    const searchInput = document.getElementById('searchRequests');
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
            
            fetch(`${baseUrl}/work-order-management/request/search?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateRequestsTable(data.data);
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
    const companySelect = document.getElementById('client_id');
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
    const modalElement = document.getElementById('createRequestModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
        requestForm.reset();
        document.getElementById('requestId').value = '';
        document.getElementById('createRequestModalLabel').textContent = 'Create Request';
        document.getElementById('saveRequestBtn').textContent = 'Create Request';
        
        // Reset default values
        document.getElementById('priority').value = 'medium';
        document.getElementById('status').value = 'pending';
        
        // Clear any error messages
        const errorElements = document.querySelectorAll('.text-danger');
        errorElements.forEach(element => element.remove());
        
        // Remove error classes
        const errorInputs = document.querySelectorAll('.is-invalid');
        errorInputs.forEach(input => input.classList.remove('is-invalid'));
        });
    }
});

function editRequest(id) {
    // Check if we're on the view page (no form elements)
    if (!document.getElementById('requestId')) {
        // If no form exists, redirect to the requests list page instead
        window.location.href = `${baseUrl}/work-order-management/request`;
        return;
    }
    
    fetch(`${baseUrl}/work-order-management/request/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const request = data.data;
            
            // Populate basic form fields
            document.getElementById('requestId').value = request.id;
            document.getElementById('request_name').value = request.request_name || '';
            document.getElementById('description').value = request.description || '';
            document.getElementById('client_id').value = request.client_id || '';
            document.getElementById('contact_id').value = request.contact_id || '';
            document.getElementById('priority').value = request.priority || 'medium';
            document.getElementById('status').value = request.status || 'pending';
            
            // Populate new fields
            if (document.getElementById('due_date')) {
                document.getElementById('due_date').value = request.due_date || '';
            }
            if (document.getElementById('email')) {
                document.getElementById('email').value = request.email || '';
            }
            if (document.getElementById('phone')) {
                document.getElementById('phone').value = request.phone || '';
            }
            if (document.getElementById('mobile')) {
                document.getElementById('mobile').value = request.mobile || '';
            }
            if (document.getElementById('asset_id')) {
                document.getElementById('asset_id').value = request.asset_id || '';
            }
            if (document.getElementById('service_address')) {
                document.getElementById('service_address').value = request.service_address || '';
            }
            if (document.getElementById('billing_address')) {
                document.getElementById('billing_address').value = request.billing_address || '';
            }
            if (document.getElementById('preferred_date_1')) {
                document.getElementById('preferred_date_1').value = request.preferred_date_1 || '';
            }
            if (document.getElementById('preferred_date_2')) {
                document.getElementById('preferred_date_2').value = request.preferred_date_2 || '';
            }
            if (document.getElementById('preferred_time')) {
                document.getElementById('preferred_time').value = request.preferred_time || '';
            }
            if (document.getElementById('preference_note')) {
                document.getElementById('preference_note').value = request.preference_note || '';
            }
            
            // Filter contacts based on selected company
            const companySelect = document.getElementById('client_id');
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
            document.getElementById('createRequestModalLabel').textContent = 'Edit Request';
            document.getElementById('saveRequestBtn').textContent = 'Update Request';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('createRequestModal'));
            modal.show();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Failed to load request data.');
    });
}

function viewRequest(id) {
    // Redirect to the request view page
    window.location.href = `${baseUrl}/work-order-management/request/view/${id}`;
}

function deleteRequest(id) {
    if (confirm('Are you sure you want to delete this request? This action cannot be undone.')) {
        fetch(`${baseUrl}/work-order-management/request/delete/${id}`, {
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
            showAlert('danger', 'Failed to delete request.');
        });
    }
}

function updateRequestsTable(requests) {
    const tbody = document.getElementById('requestsTableBody');
    
    if (requests.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">No requests found</td></tr>';
        return;
    }
    
    tbody.innerHTML = requests.map(request => {
        const companyBadge = request.client_name ? 
            `<span class="badge bg-light text-dark">${escapeHtml(request.client_name)}</span>` : 
            '<span class="text-muted">-</span>';
        
        const contactName = (request.contact_first_name && request.contact_last_name) ?
            `${escapeHtml(request.contact_first_name)} ${escapeHtml(request.contact_last_name)}` :
            '<span class="text-muted">-</span>';
        
        let statusClass = '';
        let statusText = '';
        switch (request.status) {
            case 'pending':
                statusClass = 'bg-warning';
                statusText = 'Pending';
                break;
            case 'in_progress':
                statusClass = 'bg-info';
                statusText = 'In Progress';
                break;
            case 'on_hold':
                statusClass = 'bg-secondary';
                statusText = 'On Hold';
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
        switch (request.priority || 'medium') {
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
        
        
        const createdDate = new Date(request.created_at).toLocaleDateString('en-US', {
            month: 'short', 
            day: 'numeric', 
            year: 'numeric'
        });
        
        const createdBy = (request.created_by_first_name && request.created_by_last_name) ?
            `<span class="text-dark">${escapeHtml(request.created_by_first_name)} ${escapeHtml(request.created_by_last_name)}</span>` :
            '<span class="text-muted">-</span>';
        
        return `
            <tr>
                <td>
                    <div class="text-center">
                        <a href="${baseUrl}/work-order-management/request/view/${request.id}" class="fw-medium text-decoration-none">
                            ${escapeHtml(request.request_number || '')}
                        </a>
                    </div>
                </td>
                <td>
                    <span class="fw-medium">${escapeHtml(request.request_name)}</span>
                </td>
                <td>${companyBadge}</td>
                <td>${contactName}</td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td><span class="badge ${priorityClass}">${priorityText}</span></td>
                <td>
                    <small class="text-muted">${createdDate}</small>
                </td>
                <td>
                    ${createdBy}
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="viewRequest(${request.id})" title="View">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editRequest(${request.id})" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRequest(${request.id})" title="Delete">
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
