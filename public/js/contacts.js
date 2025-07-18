// Contact management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const createModal = new bootstrap.Modal(document.getElementById('createContactModal'));
    
    // Form submission
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(contactForm);
        const contactId = document.getElementById('contactId').value;
        
        const url = contactId ? 
            `${baseUrl}/customers/contacts/update/${contactId}` : 
            `${baseUrl}/customers/contacts/create`;
        
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
            showAlert('danger', 'An error occurred while saving the contact.');
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchContacts');
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
            
            fetch(`${baseUrl}/customers/contacts/search?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateContactsTable(data.data);
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
    
    // Company selection change handler for primary contact checkbox
    const companySelect = document.getElementById('company_id');
    const primaryCheckbox = document.getElementById('is_primary');
    
    if (companySelect && primaryCheckbox) {
        companySelect.addEventListener('change', function() {
            if (this.value) {
                primaryCheckbox.disabled = false;
                primaryCheckbox.parentElement.style.opacity = '1';
            } else {
                primaryCheckbox.disabled = true;
                primaryCheckbox.checked = false;
                primaryCheckbox.parentElement.style.opacity = '0.5';
            }
        });
        
        // Initial state
        if (!companySelect.value) {
            primaryCheckbox.disabled = true;
            primaryCheckbox.parentElement.style.opacity = '0.5';
        }
    }
});

function editContact(id) {
    fetch(`${baseUrl}/customers/contacts/get/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const contact = data.data;
            
            // Populate form fields
            document.getElementById('contactId').value = contact.id;
            document.getElementById('first_name').value = contact.first_name || '';
            document.getElementById('last_name').value = contact.last_name || '';
            document.getElementById('email').value = contact.email || '';
            document.getElementById('job_title').value = contact.job_title || '';
            document.getElementById('account_number').value = contact.account_number || '';
            document.getElementById('company_id').value = contact.company_id || '';
            document.getElementById('phone').value = contact.phone || '';
            document.getElementById('mobile').value = contact.mobile || '';
            document.getElementById('address').value = contact.address || '';
            document.getElementById('city').value = contact.city || '';
            document.getElementById('state').value = contact.state || '';
            document.getElementById('zip_code').value = contact.zip_code || '';
            document.getElementById('country').value = contact.country || 'Canada';
            document.getElementById('territory_id').value = contact.territory_id || '';
            document.getElementById('status').value = contact.status || 'active';
            document.getElementById('notes').value = contact.notes || '';
            document.getElementById('is_primary').checked = contact.is_primary == 1;
            
            // Enable/disable primary checkbox based on company selection
            const companySelect = document.getElementById('company_id');
            const primaryCheckbox = document.getElementById('is_primary');
            if (companySelect.value) {
                primaryCheckbox.disabled = false;
                primaryCheckbox.parentElement.style.opacity = '1';
            } else {
                primaryCheckbox.disabled = true;
                primaryCheckbox.parentElement.style.opacity = '0.5';
            }
            
            // Update modal title and button
            document.getElementById('createContactModalLabel').textContent = 'Edit Contact';
            document.getElementById('saveContactBtn').textContent = 'Update Contact';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('createContactModal'));
            modal.show();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Failed to load contact data.');
    });
}

function deleteContact(id) {
    if (confirm('Are you sure you want to delete this contact? This action cannot be undone.')) {
        fetch(`${baseUrl}/customers/contacts/delete/${id}`, {
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
            showAlert('danger', 'Failed to delete contact.');
        });
    }
}

function setPrimaryContact(id) {
    if (confirm('Set this contact as the primary contact for their company?')) {
        fetch(`${baseUrl}/customers/contacts/setPrimary/${id}`, {
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
            showAlert('danger', 'Failed to set primary contact.');
        });
    }
}

function updateContactsTable(contacts) {
    const tbody = document.getElementById('contactsTableBody');
    
    if (contacts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-muted">No contacts found</td></tr>';
        return;
    }
    
    tbody.innerHTML = contacts.map(contact => {
        const fullName = `${contact.first_name} ${contact.last_name}`;
        
        const accountNumber = contact.company_id ? 
            '<span class="text-muted">Company Account</span>' : 
            (contact.account_number || '-');
        
        const companyBadge = contact.company_name ? 
            `<span class="badge bg-light text-dark">${escapeHtml(contact.company_name)}</span>` : 
            '<span class="text-muted">-</span>';
        
        const emailLink = contact.email ?
            `<a href="mailto:${escapeHtml(contact.email)}" class="text-decoration-none">${escapeHtml(contact.email)}</a>` : 
            '-';
        
        let phoneLink = '-';
        if (contact.phone) {
            phoneLink = `<a href="tel:${escapeHtml(contact.phone)}" class="text-decoration-none">${escapeHtml(contact.phone)}</a>`;
        } else if (contact.mobile) {
            phoneLink = `<a href="tel:${escapeHtml(contact.mobile)}" class="text-decoration-none">${escapeHtml(contact.mobile)} <small class="text-muted">(mobile)</small></a>`;
        }
        
        const statusBadge = contact.status === 'active' ? 
            '<span class="badge bg-success">Active</span>' : 
            '<span class="badge bg-secondary">Inactive</span>';
        
        const primaryBadge = contact.is_primary ? 
            '<span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Primary</span>' : 
            '<span class="text-muted">-</span>';
        
        const primaryButton = (!contact.is_primary && contact.company_id) ? 
            `<button type="button" class="btn btn-sm btn-outline-warning" onclick="setPrimaryContact(${contact.id})" title="Set as Primary">
                <i class="bi bi-star"></i>
            </button>` : '';
        
        const createdDate = new Date(contact.created_at).toLocaleDateString('en-US', {
            month: 'short', 
            day: 'numeric', 
            year: 'numeric'
        });
        
        return `
            <tr>
                <td>
                    <a href="${baseUrl}/customers/contacts/view/${contact.id}" class="text-decoration-none">
                        <h6 class="mb-1">${escapeHtml(fullName)}</h6>
                    </a>
                </td>
                <td>${accountNumber}</td>
                <td>${companyBadge}</td>
                <td>${contact.job_title || '-'}</td>
                <td>${emailLink}</td>
                <td>${phoneLink}</td>
                <td>${statusBadge}</td>
                <td>${primaryBadge}</td>
                <td>
                    <small class="text-muted">${createdDate}</small>
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        ${primaryButton}
                        <a href="${baseUrl}/customers/contacts/view/${contact.id}" class="btn btn-sm btn-outline-primary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editContact(${contact.id})" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteContact(${contact.id})" title="Delete">
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
document.getElementById('createContactModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('contactForm').reset();
    document.getElementById('contactId').value = '';
    document.getElementById('account_number').value = '';
    document.getElementById('createContactModalLabel').textContent = 'Add Contact';
    document.getElementById('saveContactBtn').textContent = 'Add Contact';
    
    // Reset primary checkbox state
    const primaryCheckbox = document.getElementById('is_primary');
    primaryCheckbox.disabled = true;
    primaryCheckbox.parentElement.style.opacity = '0.5';
    
    // Clear form errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
});

// Set base URL (use global if available, fallback to origin)
const baseUrl = window.FSM_BASE_URL || window.location.origin;

// Export Functions
function exportContacts() {
    showAlert('info', 'Preparing contacts export...');
    
    // Create a temporary form to trigger the export
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `${baseUrl}/customers/contacts/export`;
    form.style.display = 'none';
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Import Functions
function showImportModal() {
    const modalHtml = `
        <div class="modal fade" id="importContactsModal" tabindex="-1" aria-labelledby="importContactsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importContactsModalLabel">Import Contacts</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="importContactsForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="importFile" class="form-label">Choose CSV File</label>
                                <input type="file" class="form-control" id="importFile" name="import_file" accept=".csv" required>
                                <div class="form-text">
                                    Upload a CSV file with contact data. <a href="#" onclick="downloadTemplate()">Download template</a> for the required format.
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing" value="1">
                                    <label class="form-check-label" for="updateExisting">
                                        Update existing contacts (match by email or name)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Import Contacts</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if present
    const existingModal = document.getElementById('importContactsModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('importContactsModal'));
    modal.show();
    
    // Handle form submission
    document.getElementById('importContactsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Importing...';
        
        fetch(`${baseUrl}/customers/contacts/import`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.hide();
                showAlert('success', data.message);
                if (data.details && data.details.errors && data.details.errors.length > 0) {
                    // Show detailed error information
                    const errorDetails = data.details.errors.slice(0, 5).join('\n');
                    const moreErrors = data.details.errors.length > 5 ? `\n... and ${data.details.errors.length - 5} more errors.` : '';
                    showAlert('warning', `Import completed with some issues:\n${errorDetails}${moreErrors}`);
                }
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred during import.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Import Contacts';
        });
    });
}

// Template Download
function downloadTemplate() {
    showAlert('info', 'Contacts template download started.');
    
    // Use window.location to trigger download (this preserves session cookies)
    window.location.href = `${baseUrl}/customers/contacts/template`;
}
