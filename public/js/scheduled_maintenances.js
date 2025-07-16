// Scheduled Maintenances JavaScript
$(document).ready(function() {
    // Initialize DataTable if it exists
    if ($('#maintenancesTable').length) {
        $('#maintenancesTable').DataTable({
            "pageLength": 25,
            "ordering": true,
            "searching": true,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": -1 } // Disable sorting on Actions column
            ]
        });
    }

    // Update frequency unit text based on schedule type
    function updateFrequencyUnit(scheduleType, isEdit = false) {
        const prefix = isEdit ? 'edit-' : '';
        const unitElement = document.getElementById(prefix + 'frequency-unit');
        
        if (unitElement) {
            switch (scheduleType) {
                case 'daily':
                    unitElement.textContent = 'day(s)';
                    break;
                case 'weekly':
                    unitElement.textContent = 'week(s)';
                    break;
                case 'monthly':
                    unitElement.textContent = 'month(s)';
                    break;
                case 'yearly':
                    unitElement.textContent = 'year(s)';
                    break;
                case 'custom':
                    unitElement.textContent = 'custom';
                    break;
                default:
                    unitElement.textContent = 'month(s)';
            }
        }
    }

    // Handle schedule type change for create modal
    $('#schedule_type').on('change', function() {
        updateFrequencyUnit(this.value, false);
    });

    // Handle schedule type change for edit modal
    $('#edit_schedule_type').on('change', function() {
        updateFrequencyUnit(this.value, true);
    });

    // Handle create form submission
    $('#createScheduledMaintenanceForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '/scheduled-maintenances/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createScheduledMaintenanceModal').modal('hide');
                    showAlert('success', 'Scheduled maintenance created successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while creating the scheduled maintenance.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('error', errorMessage);
            }
        });
    });

    // Handle edit form submission
    $('#editScheduledMaintenanceForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const id = $('#edit_id').val();
        
        $.ajax({
            url: `/scheduled-maintenances/update/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#editScheduledMaintenanceModal').modal('hide');
                    showAlert('success', 'Scheduled maintenance updated successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while updating the scheduled maintenance.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('error', errorMessage);
            }
        });
    });

    // Clear form when create modal is closed
    $('#createScheduledMaintenanceModal').on('hidden.bs.modal', function() {
        $('#createScheduledMaintenanceForm')[0].reset();
        updateFrequencyUnit('monthly', false);
    });

    // Clear form when edit modal is closed
    $('#editScheduledMaintenanceModal').on('hidden.bs.modal', function() {
        $('#editScheduledMaintenanceForm')[0].reset();
        updateFrequencyUnit('monthly', true);
    });
});

// View scheduled maintenance details
function viewMaintenance(id) {
    window.location.href = `/scheduled-maintenances/view/${id}`;
}

// Edit scheduled maintenance
function editMaintenance(id) {
    $.ajax({
        url: `/scheduled-maintenances/edit/${id}`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const maintenance = response.data;
                
                // Populate edit form fields
                $('#edit_id').val(maintenance.id);
                $('#edit_name').val(maintenance.name);
                $('#edit_description').val(maintenance.description);
                $('#edit_schedule_type').val(maintenance.schedule_type);
                $('#edit_start_date').val(maintenance.start_date);
                $('#edit_end_date').val(maintenance.end_date);
                $('#edit_frequency').val(maintenance.frequency);
                $('#edit_priority').val(maintenance.priority);
                $('#edit_client_id').val(maintenance.client_id);
                $('#edit_asset_id').val(maintenance.asset_id);
                $('#edit_assigned_to').val(maintenance.assigned_to);
                $('#edit_territory_id').val(maintenance.territory_id);
                $('#edit_estimated_duration').val(maintenance.estimated_duration);
                $('#edit_notes').val(maintenance.notes);
                $('#edit_status').val(maintenance.status);
                
                // Update frequency unit
                updateFrequencyUnit(maintenance.schedule_type, true);
                
                // Show edit modal
                $('#editScheduledMaintenanceModal').modal('show');
            } else {
                showAlert('error', response.message || 'Failed to load scheduled maintenance data');
            }
        },
        error: function(xhr) {
            showAlert('error', 'Failed to load scheduled maintenance data');
        }
    });
}

// Delete scheduled maintenance
function deleteMaintenance(id) {
    if (confirm('Are you sure you want to delete this scheduled maintenance? This action cannot be undone.')) {
        $.ajax({
            url: `/scheduled-maintenances/delete/${id}`,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Scheduled maintenance deleted successfully!');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert('error', response.message || 'Failed to delete scheduled maintenance');
                }
            },
            error: function(xhr) {
                showAlert('error', 'Failed to delete scheduled maintenance');
            }
        });
    }
}

// Update frequency unit function (helper)
function updateFrequencyUnit(scheduleType, isEdit = false) {
    const prefix = isEdit ? 'edit-' : '';
    const unitElement = document.getElementById(prefix + 'frequency-unit');
    
    if (unitElement) {
        switch (scheduleType) {
            case 'daily':
                unitElement.textContent = 'day(s)';
                break;
            case 'weekly':
                unitElement.textContent = 'week(s)';
                break;
            case 'monthly':
                unitElement.textContent = 'month(s)';
                break;
            case 'yearly':
                unitElement.textContent = 'year(s)';
                break;
            case 'custom':
                unitElement.textContent = 'custom';
                break;
            default:
                unitElement.textContent = 'month(s)';
        }
    }
}

// Show alert function
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top of the page
    $('body').prepend(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
