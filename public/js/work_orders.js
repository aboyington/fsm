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
    
    if (searchInput && statusFilter && priorityFilter) {
        let searchTimeout;
        
        function performSearch() {
            const searchTerm = searchInput.value;
            const status = statusFilter.value;
            const priority = priorityFilter.value;
            
            const params = new URLSearchParams();
            if (searchTerm) params.append('q', searchTerm);
            if (status) params.append('status', status);
            if (priority) params.append('priority', priority);
            
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
            document.getElementById('status').value = 'new';
            
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
            case 'new':
                statusClass = 'bg-primary';
                statusText = 'New';
                break;
            case 'pending':
                statusClass = 'bg-warning';
                statusText = 'Pending';
                break;
            case 'in_progress':
                statusClass = 'bg-info';
                statusText = 'In Progress';
                break;
            case 'cannot_complete':
                statusClass = 'bg-dark';
                statusText = 'Cannot Complete';
                break;
            case 'completed':
                statusClass = 'bg-success';
                statusText = 'Completed';
                break;
            case 'closed':
                statusClass = 'bg-secondary';
                statusText = 'Closed';
                break;
            case 'cancelled':
                statusClass = 'bg-danger';
                statusText = 'Cancelled';
                break;
            case 'scheduled_appointment':
                statusClass = 'bg-light text-dark';
                statusText = 'Scheduled Appointment';
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
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="editWorkOrder(${workOrder.id})" title="Edit">
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
            document.getElementById('status').value = workOrder.status || 'new';
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
            document.getElementById('description').value = workOrder.description || '';
            
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

// Timeline functionality for Work Order view page
function changeTimelineFilter() {
    const filterValue = document.getElementById('timelineFilter').value;
    console.log('Changing timeline filter to:', filterValue);
    // Get work order ID from the current page context
    const workOrderId = window.location.pathname.split('/').pop();
    loadTimeline(workOrderId);
}

function loadTimeline(workOrderID) {
    console.log('Loading timeline for work order:', workOrderID);
    const timelineContainer = document.getElementById('timelineContainer');
    
    if (!timelineContainer) {
        console.warn('Timeline container not found');
        return;
    }
    
    // Show loading state
    timelineContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading timeline...</span>
            </div>
            <p class="text-muted mt-2">Loading timeline events...</p>
        </div>
    `;
    
    // Get current filter
    const filter = document.getElementById('timelineFilter')?.value || 'all';
    
    // Call the actual API endpoint
    fetch(`${baseUrl}/api/work-orders/${workOrderID}/timeline?filter=${filter}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderTimeline(data.timeline);
        } else {
            timelineContainer.innerHTML = `
                <div class="py-4 text-center">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error loading timeline: ${data.message}
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Timeline loading error:', error);
        timelineContainer.innerHTML = `
            <div class="py-4 text-center">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Failed to load timeline. Please try again.
                </div>
            </div>
        `;
    });
}

// Render timeline events in the container
function renderTimeline(timelineEvents) {
    const timelineContainer = document.getElementById('timelineContainer');
    
    if (!timelineEvents || timelineEvents.length === 0) {
        timelineContainer.innerHTML = `
            <div class="py-4 text-center">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No timeline events found for this work order.
                </div>
            </div>
        `;
        return;
    }
    
    let timelineHTML = '<div class="timeline-items">';
    
    timelineEvents.forEach((event, index) => {
        const isLast = index === timelineEvents.length - 1;
        const eventIcon = getEventIcon(event.event_type);
        const eventColor = getEventColor(event.event_type);
        
        timelineHTML += `
            <div class="timeline-item ${isLast ? '' : 'timeline-item-connected'}">
                <div class="d-flex align-items-start mb-3">
                    <div class="${eventColor} rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; position: relative; z-index: 2;">
                        <i class="${eventIcon} text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-1 fw-medium">${escapeHtml(event.title)}</h6>
                            <small class="text-muted">${event.formatted_date || new Date(event.created_at).toLocaleString()}</small>
                        </div>
                        <p class="text-muted mb-1">${escapeHtml(event.description || '')}</p>
                        <small class="text-muted">
                            <i class="bi bi-person me-1"></i>${escapeHtml(event.user_name || 'System')}
                        </small>
                    </div>
                </div>
            </div>
        `;
    });
    
    timelineHTML += '</div>';
    timelineContainer.innerHTML = timelineHTML;
}

// Get icon for timeline event type
function getEventIcon(eventType) {
    const icons = {
        'work_order_created': 'bi bi-plus-circle',
        'work_order_updated': 'bi bi-pencil',
        'work_order_status_changed': 'bi bi-arrow-repeat',
        'work_order_priority_changed': 'bi bi-exclamation-triangle',
        'work_order_assigned': 'bi bi-person-check',
        'work_order_completed': 'bi bi-check-circle',
        'work_order_cancelled': 'bi bi-x-circle',
        'work_order_deleted': 'bi bi-trash',
        'work_order_note_added': 'bi bi-journal-text',
        'work_order_attachment_added': 'bi bi-paperclip',
        'work_order_service_appointment_scheduled': 'bi bi-calendar-event',
        'work_order_invoice_generated': 'bi bi-receipt'
    };
    return icons[eventType] || 'bi bi-info-circle';
}

// Get color for timeline event type
function getEventColor(eventType) {
    const colors = {
        'work_order_created': 'bg-success',
        'work_order_updated': 'bg-info',
        'work_order_status_changed': 'bg-primary',
        'work_order_priority_changed': 'bg-warning',
        'work_order_assigned': 'bg-info',
        'work_order_completed': 'bg-success',
        'work_order_cancelled': 'bg-danger',
        'work_order_deleted': 'bg-danger',
        'work_order_note_added': 'bg-secondary',
        'work_order_attachment_added': 'bg-secondary',
        'work_order_service_appointment_scheduled': 'bg-primary',
        'work_order_invoice_generated': 'bg-warning'
    };
    return colors[eventType] || 'bg-secondary';
}

// Notes functionality
function showAddNoteForm() {
    const notesEmptyState = document.getElementById('notesEmptyState');
    const notesList = document.getElementById('notesList');
    const addNoteForm = document.getElementById('addNoteForm');
    
    if (notesEmptyState) notesEmptyState.classList.add('d-none');
    if (notesList) notesList.classList.add('d-none');
    if (addNoteForm) {
        addNoteForm.classList.remove('d-none');
        const noteContent = document.getElementById('noteContent');
        if (noteContent) noteContent.focus();
    }
}

function hideAddNoteForm() {
    const notesEmptyState = document.getElementById('notesEmptyState');
    const notesList = document.getElementById('notesList');
    const addNoteForm = document.getElementById('addNoteForm');
    
    if (addNoteForm) addNoteForm.classList.add('d-none');
    
    // Show notes list if there are notes, otherwise show empty state
    if (notesList && notesList.querySelector('.card')) {
        notesList.classList.remove('d-none');
    } else if (notesEmptyState) {
        notesEmptyState.classList.remove('d-none');
    }
    
    // Clear the form
    const noteContent = document.getElementById('noteContent');
    if (noteContent) noteContent.value = '';
}

function loadNotes(workOrderId) {
    console.log('Loading notes for work order:', workOrderId);
    
    // Show loading state
    const notesEmptyState = document.getElementById('notesEmptyState');
    const notesList = document.getElementById('notesList');
    const addNoteForm = document.getElementById('addNoteForm');
    
    if (notesEmptyState) notesEmptyState.classList.add('d-none');
    if (addNoteForm) addNoteForm.classList.add('d-none');
    if (notesList) {
        notesList.classList.remove('d-none');
        notesList.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading notes...</span>
                </div>
                <p class="text-muted mt-2">Loading notes...</p>
            </div>
        `;
    }
    
    fetch(`${baseUrl}/api/work-orders/${workOrderId}/notes`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Notes API response:', data);
        if (data.success === true) {
            renderNotes(data.notes || []);
        } else {
            showNotesError('Error loading notes: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Notes loading error:', error);
        showNotesError('Failed to load notes. Please try again.');
    });
}

function renderNotes(notes) {
    console.log('Rendering notes:', notes);
    
    const notesEmptyState = document.getElementById('notesEmptyState');
    const notesList = document.getElementById('notesList');
    const addNoteForm = document.getElementById('addNoteForm');
    
    if (!notes || notes.length === 0) {
        // Show empty state
        if (notesList) notesList.classList.add('d-none');
        if (addNoteForm) addNoteForm.classList.add('d-none');
        if (notesEmptyState) notesEmptyState.classList.remove('d-none');
        return;
    }
    
    // Show notes list
    if (notesEmptyState) notesEmptyState.classList.add('d-none');
    if (addNoteForm) addNoteForm.classList.add('d-none');
    if (notesList) {
        notesList.classList.remove('d-none');
        
        // Add header with "Add Notes" button
        let notesHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Notes (${notes.length})</h5>
                <button type="button" class="btn btn-success" onclick="showAddNoteForm()">
                    <i class="bi bi-plus"></i> Add Note
                </button>
            </div>
        `;
        
        // Sort notes - pinned first, then by creation date descending
        notes.sort((a, b) => {
            if (a.is_pinned && !b.is_pinned) return -1;
            if (!a.is_pinned && b.is_pinned) return 1;
            return new Date(b.created_at) - new Date(a.created_at);
        });
        
        notes.forEach(note => {
            const createdDate = new Date(note.created_at).toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            notesHTML += `
                <div class="card mb-3 ${note.is_pinned ? 'border-warning' : ''}" id="note-${note.id}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center">
                                ${note.is_pinned ? '<i class="bi bi-pin-angle-fill text-warning me-2" title="Pinned Note"></i>' : ''}
                                <strong class="text-primary">${escapeHtml(note.created_by_name || 'Unknown')}</strong>
                                ${note.is_pinned ? '<span class="badge bg-warning text-dark ms-2">Pinned</span>' : ''}
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-3">${createdDate}</small>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editNote(${note.id})" title="Edit Note">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm ${note.is_pinned ? 'btn-warning' : 'btn-outline-warning'}" onclick="togglePinNote(${note.id}, ${note.is_pinned ? 1 : 0})" title="${note.is_pinned ? 'Unpin' : 'Pin'} Note">
                                        <i class="bi bi-pin${note.is_pinned ? '-fill' : ''}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteNote(${note.id})" title="Delete Note">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="note-content" id="note-content-${note.id}">
                            ${escapeHtml(note.content || '').replace(/\n/g, '<br>')}
                        </div>
                        
                        <!-- Hidden edit form -->
                        <div id="edit-form-${note.id}" class="d-none mt-3">
                            <div class="mb-3">
                                <textarea class="form-control" id="edit-content-${note.id}" rows="4">${escapeHtml(note.content || '')}</textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-success" onclick="saveEditedNote(${note.id})">
                                    <i class="bi bi-check"></i> Save
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="cancelEditNote(${note.id})">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        notesList.innerHTML = notesHTML;
    }
}

function showNotesError(message) {
    const notesList = document.getElementById('notesList');
    if (notesList) {
        notesList.classList.remove('d-none');
        notesList.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
    }
}

// Handle note form submission
function saveNote() {
    const noteContent = document.getElementById('noteContent');
    if (!noteContent) return;
    
    const content = noteContent.value.trim();
    if (!content) {
        showAlert('warning', 'Please enter note content.');
        return;
    }
    
    // Get work order ID from the current page context
    const workOrderId = window.location.pathname.split('/').pop();
    
    // Disable form during save
    const form = document.getElementById('noteForm');
    const saveBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    fetch(`${baseUrl}/api/work-orders/${workOrderId}/notes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            // Clear the form
            noteContent.value = '';
            
            // Hide add form and reload notes
            hideAddNoteForm();
            loadNotes(workOrderId);
            
            showAlert('success', 'Note saved successfully!');
        } else {
            showAlert('danger', data.message || 'Failed to save note.');
        }
    })
    .catch(error => {
        console.error('Error saving note:', error);
        showAlert('danger', 'Failed to save note. Please try again.');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = originalBtnText;
    });
}

function editNote(noteId) {
    const noteContent = document.getElementById(`note-content-${noteId}`);
    const editForm = document.getElementById(`edit-form-${noteId}`);
    
    if (noteContent) noteContent.classList.add('d-none');
    if (editForm) {
        editForm.classList.remove('d-none');
        const textarea = document.getElementById(`edit-content-${noteId}`);
        if (textarea) textarea.focus();
    }
}

function cancelEditNote(noteId) {
    const noteContent = document.getElementById(`note-content-${noteId}`);
    const editForm = document.getElementById(`edit-form-${noteId}`);
    
    if (editForm) editForm.classList.add('d-none');
    if (noteContent) noteContent.classList.remove('d-none');
}

function saveEditedNote(noteId) {
    const textarea = document.getElementById(`edit-content-${noteId}`);
    if (!textarea) return;
    
    const content = textarea.value.trim();
    if (!content) {
        showAlert('warning', 'Please enter note content.');
        return;
    }
    
    // Disable form during save
    const saveBtn = document.querySelector(`#edit-form-${noteId} .btn-success`);
    const originalBtnText = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    fetch(`${baseUrl}/api/work-order-notes/${noteId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            // Get work order ID and reload notes
            const workOrderId = window.location.pathname.split('/').pop();
            loadNotes(workOrderId);
            
            showAlert('success', 'Note updated successfully!');
        } else {
            showAlert('danger', data.message || 'Failed to update note.');
        }
    })
    .catch(error => {
        console.error('Error updating note:', error);
        showAlert('danger', 'Failed to update note. Please try again.');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = originalBtnText;
    });
}

function togglePinNote(noteId, currentPinStatus) {
    fetch(`${baseUrl}/api/work-order-notes/${noteId}/toggle-pin`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            // Get work order ID and reload notes
            const workOrderId = window.location.pathname.split('/').pop();
            loadNotes(workOrderId);
            
            showAlert('success', data.message || 'Pin status updated successfully!');
        } else {
            showAlert('danger', data.message || 'Failed to update pin status.');
        }
    })
    .catch(error => {
        console.error('Error toggling pin:', error);
        showAlert('danger', 'Failed to update pin status. Please try again.');
    });
}

function deleteNote(noteId) {
    if (!confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
        return;
    }
    
    fetch(`${baseUrl}/api/work-order-notes/${noteId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            // Get work order ID and reload notes
            const workOrderId = window.location.pathname.split('/').pop();
            loadNotes(workOrderId);
            
            showAlert('success', 'Note deleted successfully!');
        } else {
            showAlert('danger', data.message || 'Failed to delete note.');
        }
    })
    .catch(error => {
        console.error('Error deleting note:', error);
        showAlert('danger', 'Failed to delete note. Please try again.');
    });
}

// Attachments functionality
function loadAttachments(workOrderId) {
    console.log('Loading attachments for work order:', workOrderId);
    
    const attachmentsEmptyState = document.getElementById('attachmentsEmptyState');
    const uploadedFilesList = document.getElementById('uploadedFilesList');
    
    // Show loading state
    if (uploadedFilesList) {
        uploadedFilesList.classList.remove('d-none');
        uploadedFilesList.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading attachments...</span>
                </div>
                <p class="text-muted mt-2">Loading attachments...</p>
            </div>
        `;
    }
    
    fetch(`${baseUrl}/api/work-orders/${workOrderId}/attachments`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Attachments API response:', data);
        if (data.success === true) {
            renderAttachments(data.attachments || []);
        } else {
            showAttachmentsError('Error loading attachments: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Attachments loading error:', error);
        showAttachmentsError('Failed to load attachments. Please try again.');
    });
}

function renderAttachments(attachments) {
    console.log('Rendering attachments:', attachments);
    
    const attachmentsEmptyState = document.getElementById('attachmentsEmptyState');
    const uploadedFilesList = document.getElementById('uploadedFilesList');
    
    if (!attachments || attachments.length === 0) {
        // Show empty state
        if (uploadedFilesList) uploadedFilesList.classList.add('d-none');
        if (attachmentsEmptyState) attachmentsEmptyState.classList.remove('d-none');
        return;
    }
    
    // Show attachments list
    if (attachmentsEmptyState) attachmentsEmptyState.classList.add('d-none');
    if (uploadedFilesList) {
        uploadedFilesList.classList.remove('d-none');
        
        // Add header with "Add Attachment" button
        let attachmentsHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Attachments (${attachments.length})</h5>
                <button type="button" class="btn btn-success" onclick="triggerFileUpload()">
                    <i class="bi bi-plus"></i> Add Attachment
                </button>
            </div>
        `;
        
        // Sort attachments by creation date descending
        attachments.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        
        attachments.forEach(attachment => {
            const createdDate = new Date(attachment.created_at).toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            const fileSize = formatFileSize(attachment.file_size);
            const fileIcon = getFileIcon(attachment.original_name);
            const isPreviewable = isPreviewableFile(attachment.mime_type);
            
            attachmentsHTML += `
                <div class="list-group-item list-group-item-action d-flex align-items-center p-3" id="attachment-${attachment.id}">
                    <div class="me-3">
                        <i class="${fileIcon}" style="font-size: 1.5rem; color: #6c757d;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 text-truncate" title="${escapeHtml(attachment.original_name)}">
                                ${escapeHtml(attachment.original_name)}
                            </h6>
                            <div class="btn-group ms-2" role="group">
                                ${isPreviewable ? `
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="previewAttachment(${attachment.id})" title="Preview">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                ` : ''}
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadAttachment(${attachment.id})" title="Download">
                                    <i class="bi bi-download"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAttachment(${attachment.id})" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-hdd me-1"></i> ${fileSize}
                            <span class="mx-2">•</span>
                            <i class="bi bi-calendar me-1"></i> ${createdDate}
                            <span class="mx-2">•</span>
                            <i class="bi bi-person me-1"></i> ${escapeHtml(attachment.uploaded_by_name || 'Unknown')}
                        </div>
                    </div>
                </div>
            `;
        });
        
        // Add hidden file input
        attachmentsHTML += `<input type="file" id="fileUpload" multiple style="display: none;" onchange="handleFileUpload()"/>`;
        
        uploadedFilesList.innerHTML = `<div class="list-group">${attachmentsHTML}</div>`;
    }
}

function showAttachmentsError(message) {
    const uploadedFilesList = document.getElementById('uploadedFilesList');
    if (uploadedFilesList) {
        uploadedFilesList.classList.remove('d-none');
        uploadedFilesList.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
    }
}

function triggerFileUpload() {
    document.getElementById('fileUpload').click();
}

function handleFileUpload() {
    const fileInput = document.getElementById('fileUpload');
    const files = fileInput.files;
    
    if (files.length === 0) {
        return;
    }
    
    const workOrderId = window.location.pathname.split('/').pop();
    const workOrderNumber = document.querySelector('h1')?.textContent?.match(/WO-\d+/)?.[0] || 'WO-000';
    
    // Create FormData for file upload
    const formData = new FormData();
    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }
    formData.append('work_order_id', workOrderId);
    formData.append('work_order_number', workOrderNumber);
    
    // Show upload progress
    showUploadProgress();
    
    fetch(`${baseUrl}/api/work-orders/${workOrderId}/attachments/upload`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideUploadProgress();
        if (data.success === true) {
            loadAttachments(workOrderId);
            showAlert('success', 'Files uploaded successfully!');
            fileInput.value = ''; // Clear file input
        } else {
            showAlert('danger', 'Error uploading files: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        hideUploadProgress();
        console.error('Error:', error);
        showAlert('danger', 'Error uploading files');
    });
}

function downloadAttachment(attachmentId) {
    const workOrderId = window.location.pathname.split('/').pop();
    window.open(`${baseUrl}/api/work-orders/${workOrderId}/attachments/${attachmentId}/download`, '_blank');
}

function previewAttachment(attachmentId) {
    const workOrderId = window.location.pathname.split('/').pop();
    const previewUrl = `${baseUrl}/api/work-orders/${workOrderId}/attachments/${attachmentId}/preview`;
    
    // Create modal for preview
    const modalHTML = `
        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="previewModalLabel">File Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <iframe src="${previewUrl}" style="width: 100%; height: 70vh; border: none;"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="${baseUrl}/api/work-orders/${workOrderId}/attachments/${attachmentId}/download" class="btn btn-primary" target="_blank">
                            <i class="bi bi-download"></i> Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('previewModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
    
    // Clean up modal when hidden
    document.getElementById('previewModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

function deleteAttachment(attachmentId) {
    if (!confirm('Are you sure you want to delete this attachment? This action cannot be undone.')) {
        return;
    }
    
    const workOrderId = window.location.pathname.split('/').pop();
    
    fetch(`${baseUrl}/api/work-orders/${workOrderId}/attachments/${attachmentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success === true) {
            loadAttachments(workOrderId);
            showAlert('success', 'Attachment deleted successfully!');
        } else {
            showAlert('danger', data.message || 'Failed to delete attachment.');
        }
    })
    .catch(error => {
        console.error('Error deleting attachment:', error);
        showAlert('danger', 'Failed to delete attachment. Please try again.');
    });
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function getFileIcon(fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    const iconMap = {
        // Images
        'jpg': 'bi bi-file-earmark-image',
        'jpeg': 'bi bi-file-earmark-image',
        'png': 'bi bi-file-earmark-image',
        'gif': 'bi bi-file-earmark-image',
        'bmp': 'bi bi-file-earmark-image',
        'webp': 'bi bi-file-earmark-image',
        'svg': 'bi bi-file-earmark-image',
        // Documents
        'pdf': 'bi bi-file-earmark-pdf',
        'doc': 'bi bi-file-earmark-word',
        'docx': 'bi bi-file-earmark-word',
        'xls': 'bi bi-file-earmark-excel',
        'xlsx': 'bi bi-file-earmark-excel',
        'ppt': 'bi bi-file-earmark-ppt',
        'pptx': 'bi bi-file-earmark-ppt',
        'txt': 'bi bi-file-earmark-text',
        'rtf': 'bi bi-file-earmark-text',
        // Archives
        'zip': 'bi bi-file-earmark-zip',
        'rar': 'bi bi-file-earmark-zip',
        '7z': 'bi bi-file-earmark-zip',
        // Media
        'mp4': 'bi bi-file-earmark-play',
        'avi': 'bi bi-file-earmark-play',
        'mov': 'bi bi-file-earmark-play',
        'wmv': 'bi bi-file-earmark-play',
        'mp3': 'bi bi-file-earmark-music',
        'wav': 'bi bi-file-earmark-music',
        'aac': 'bi bi-file-earmark-music'
    };
    
    return iconMap[extension] || 'bi bi-file-earmark';
}

function isPreviewableFile(mimeType) {
    const previewableTypes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
        'application/pdf'
    ];
    return previewableTypes.includes(mimeType);
}

function showUploadProgress() {
    // Add a simple progress indicator
    const progressHtml = `
        <div id="uploadProgress" class="text-center py-3">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Uploading...</span>
            </div>
            <p class="mt-2 text-muted">Uploading files...</p>
        </div>
    `;
    
    const attachmentsTab = document.getElementById('attachments');
    const existingProgress = document.getElementById('uploadProgress');
    if (existingProgress) {
        existingProgress.remove();
    }
    attachmentsTab.insertAdjacentHTML('afterbegin', progressHtml);
}

function hideUploadProgress() {
    const progressDiv = document.getElementById('uploadProgress');
    if (progressDiv) {
        progressDiv.remove();
    }
}

// Initialize notes and attachments functionality when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the work order view page
    const isViewPage = window.location.pathname.includes('/view/');
    if (isViewPage) {
        const workOrderId = window.location.pathname.split('/').pop();
        
        // Load notes when Notes tab is shown
        const notesTab = document.querySelector('[data-bs-target="#notes"]');
        if (notesTab) {
            notesTab.addEventListener('shown.bs.tab', function() {
                loadNotes(workOrderId);
            });
            
            // Load notes if Notes tab is active on page load
            if (notesTab.classList.contains('active')) {
                loadNotes(workOrderId);
            }
        }
        
        // Load attachments when Attachments tab is shown
        const attachmentsTab = document.querySelector('[data-bs-target="#attachments"]');
        if (attachmentsTab) {
            attachmentsTab.addEventListener('shown.bs.tab', function() {
                loadAttachments(workOrderId);
            });
            
            // Load attachments if Attachments tab is active on page load
            if (attachmentsTab.classList.contains('active')) {
                loadAttachments(workOrderId);
            }
        }
        
        // Handle inline note form submission
        const noteForm = document.getElementById('noteForm');
        if (noteForm) {
            noteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveNote();
            });
        }
    }
});
