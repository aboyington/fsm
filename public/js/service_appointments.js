$(document).ready(function() {
    let appointmentsTable;
    
    // Initialize DataTable
    if ($.fn.DataTable) {
        appointmentsTable = $('#appointmentsTable').DataTable({
            responsive: true,
            columnDefs: [
                { width: "10%", targets: 0 },
                { width: "15%", targets: 1 },
                { width: "15%", targets: 2 },
                { width: "15%", targets: 3 },
                { width: "10%", targets: 4 },
                { width: "15%", targets: 5 },
                { width: "10%", targets: 6 },
                { width: "10%", targets: 7 }
            ],
            order: [[2, 'desc']]
        });
    }
    
    // Create appointment form submission
    $('#createAppointmentForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '/work-order-management/service-appointments/create',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#createAppointmentModal').modal('hide');
                    $('#createAppointmentForm')[0].reset();
                    location.reload();
                } else {
                    if (response.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        for (let field in response.errors) {
                            errorMessage += '• ' + response.errors[field] + '\n';
                        }
                        showAlert('error', errorMessage);
                    } else {
                        showAlert('error', response.message);
                    }
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while creating the appointment.');
            }
        });
    });
    
    // Edit appointment
    window.editAppointment = function(id) {
        $.ajax({
            url: '/work-order-management/service-appointments/get/' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const appointment = response.data;
                    
                    // Populate edit form
                    $('#editAppointmentId').val(appointment.id);
                    $('#editWorkOrderId').val(appointment.work_order_id);
                    $('#editAppointmentDate').val(appointment.appointment_date);
                    $('#editAppointmentTime').val(appointment.appointment_time);
                    $('#editDuration').val(appointment.duration);
                    $('#editStatus').val(appointment.status);
                    $('#editTechnicianId').val(appointment.technician_id);
                    $('#editNotes').val(appointment.notes);
                    
                    $('#editAppointmentModal').modal('show');
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while fetching appointment details.');
            }
        });
    };
    
    // Update appointment form submission
    $('#editAppointmentForm').on('submit', function(e) {
        e.preventDefault();
        
        const appointmentId = $('#editAppointmentId').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: '/work-order-management/service-appointments/update/' + appointmentId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#editAppointmentModal').modal('hide');
                    location.reload();
                } else {
                    if (response.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        for (let field in response.errors) {
                            errorMessage += '• ' + response.errors[field] + '\n';
                        }
                        showAlert('error', errorMessage);
                    } else {
                        showAlert('error', response.message);
                    }
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while updating the appointment.');
            }
        });
    });
    
    // Delete appointment
    window.deleteAppointment = function(id) {
        if (confirm('Are you sure you want to delete this appointment?')) {
            $.ajax({
                url: '/work-order-management/service-appointments/delete/' + id,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        location.reload();
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'An error occurred while deleting the appointment.');
                }
            });
        }
    };
    
    // Update appointment status
    window.updateAppointmentStatus = function(id, status) {
        $.ajax({
            url: '/work-order-management/service-appointments/status/' + id,
            type: 'POST',
            data: { status: status },
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    location.reload();
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while updating the appointment status.');
            }
        });
    };
    
    // Search appointments
    $('#searchAppointments').on('input', function() {
        const searchTerm = $(this).val();
        
        if (searchTerm.length > 2 || searchTerm.length === 0) {
            $.ajax({
                url: '/work-order-management/service-appointments/search',
                type: 'GET',
                data: { q: searchTerm },
                success: function(response) {
                    if (response.success) {
                        updateAppointmentsTable(response.data);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'An error occurred while searching appointments.');
                }
            });
        }
    });
    
    // Filter by status
    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        
        $.ajax({
            url: '/work-order-management/service-appointments/search',
            type: 'GET',
            data: { status: status },
            success: function(response) {
                if (response.success) {
                    updateAppointmentsTable(response.data);
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while filtering appointments.');
            }
        });
    });
    
    // Update appointments table
    function updateAppointmentsTable(appointments) {
        if (appointmentsTable) {
            appointmentsTable.clear();
            
            appointments.forEach(function(appointment) {
                const statusBadge = getStatusBadge(appointment.status);
                const actions = getActionButtons(appointment.id, appointment.status);
                
                appointmentsTable.row.add([
                    appointment.id,
                    appointment.work_order_title || 'N/A',
                    appointment.appointment_date,
                    appointment.appointment_time,
                    appointment.duration + ' mins',
                    statusBadge,
                    appointment.technician_name || 'Unassigned',
                    actions
                ]);
            });
            
            appointmentsTable.draw();
        }
    }
    
    // Get status badge HTML
    function getStatusBadge(status) {
        const badges = {
            'scheduled': '<span class="badge badge-primary">Scheduled</span>',
            'in_progress': '<span class="badge badge-warning">In Progress</span>',
            'completed': '<span class="badge badge-success">Completed</span>',
            'cancelled': '<span class="badge badge-danger">Cancelled</span>'
        };
        return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
    }
    
    // Get action buttons HTML
    function getActionButtons(id, status) {
        let actions = '<div class="btn-group" role="group">';
        
        actions += '<button class="btn btn-sm btn-outline-primary" onclick="editAppointment(' + id + ')" title="Edit">';
        actions += '<i class="fas fa-edit"></i></button>';
        
        if (status === 'scheduled') {
            actions += '<button class="btn btn-sm btn-outline-warning" onclick="updateAppointmentStatus(' + id + ', \'in_progress\')" title="Start">';
            actions += '<i class="fas fa-play"></i></button>';
        }
        
        if (status === 'in_progress') {
            actions += '<button class="btn btn-sm btn-outline-success" onclick="updateAppointmentStatus(' + id + ', \'completed\')" title="Complete">';
            actions += '<i class="fas fa-check"></i></button>';
        }
        
        if (status !== 'completed') {
            actions += '<button class="btn btn-sm btn-outline-danger" onclick="updateAppointmentStatus(' + id + ', \'cancelled\')" title="Cancel">';
            actions += '<i class="fas fa-times"></i></button>';
        }
        
        actions += '<button class="btn btn-sm btn-outline-danger" onclick="deleteAppointment(' + id + ')" title="Delete">';
        actions += '<i class="fas fa-trash"></i></button>';
        
        actions += '</div>';
        return actions;
    }
    
    // Show alert message
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertIcon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${alertIcon}"></i> ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }
});
