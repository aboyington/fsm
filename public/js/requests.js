// Request management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const requestForm = document.getElementById('requestForm');
    const createModal = new bootstrap.Modal(document.getElementById('createRequestModal'));
    
    // Form submission
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
    document.getElementById('createRequestModal').addEventListener('hidden.bs.modal', function() {
        requestForm.reset();
        document.getElementById('requestId').value = '';
        document.getElementById('createRequestModalLabel').textContent = 'Create Request';
        document.getElementById('saveRequestBtn').textContent = 'Create Request';
        
        // Clear any error messages
        const errorElements = document.querySelectorAll('.text-danger');
        errorElements.forEach(element => element.remove());
        
        // Remove error classes
        const errorInputs = document.querySelectorAll('.is-invalid');
        errorInputs.forEach(input => input.classList.remove('is-invalid'));
    });
});

function editRequest(id) {
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
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">No requests found</td></tr>';
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
        
        const description = request.description ? 
            `<br><small class="text-muted">${escapeHtml(request.description.substring(0, 50))}${request.description.length > 50 ? '...' : ''}</small>` : 
            '';
        
        const createdDate = new Date(request.created_at).toLocaleDateString('en-US', {
            month: 'short', 
            day: 'numeric', 
            year: 'numeric'
        });
        
        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">${escapeHtml(request.request_name)}</h6>
                            <small class="text-muted">ID: ${request.id}</small>
                            ${description}
                        </div>
                    </div>
                </td>
                <td>${companyBadge}</td>
                <td>${contactName}</td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td><span class="badge ${priorityClass}">${priorityText}</span></td>
                <td>
                    <small class="text-muted">${createdDate}</small>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
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
