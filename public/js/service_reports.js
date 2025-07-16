$(document).ready(function() {
    let reportsTable;
    
    // Initialize DataTable
    if ($.fn.DataTable) {
        reportsTable = $('#reportsTable').DataTable({
            responsive: true,
            columnDefs: [
                { width: "10%", targets: 0 },
                { width: "20%", targets: 1 },
                { width: "15%", targets: 2 },
                { width: "15%", targets: 3 },
                { width: "12%", targets: 4 },
                { width: "10%", targets: 5 },
                { width: "18%", targets: 6 }
            ],
            order: [[4, 'desc']]
        });
    }
    
    // Auto-fill work order and technician when service appointment is selected
    $('#serviceAppointmentId').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const workOrderId = selectedOption.data('work-order');
        const technicianId = selectedOption.data('technician');
        
        $('#workOrderId').val(workOrderId);
        $('#technicianId').val(technicianId);
    });
    
    // Auto-fill for edit modal
    $('#editServiceAppointmentId').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const workOrderId = selectedOption.data('work-order');
        const technicianId = selectedOption.data('technician');
        
        $('#editWorkOrderId').val(workOrderId);
        $('#editTechnicianId').val(technicianId);
    });
    
    // Auto-calculate total cost
    function calculateTotalCost(prefix = '') {
        const laborCost = parseFloat($('#' + prefix + 'laborCost').val()) || 0;
        const materialCost = parseFloat($('#' + prefix + 'materialCost').val()) || 0;
        const totalCost = laborCost + materialCost;
        
        $('#' + prefix + 'totalCost').val(totalCost.toFixed(2));
    }
    
    // Calculate total cost on input change
    $('#laborCost, #materialCost').on('input', function() {
        calculateTotalCost();
    });
    
    $('#editLaborCost, #editMaterialCost').on('input', function() {
        calculateTotalCost('edit');
    });
    
    // Create service report form submission
    $('#createServiceReportForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '/work-order-management/service-reports/create',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#createServiceReportModal').modal('hide');
                    $('#createServiceReportForm')[0].reset();
                    location.reload();
                } else {
                    if (response.errors) {
                        let errorMessage = 'Please fix the following errors:\\n';
                        for (let field in response.errors) {
                            errorMessage += '• ' + response.errors[field] + '\\n';
                        }
                        showAlert('error', errorMessage);
                    } else {
                        showAlert('error', response.message);
                    }
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while creating the service report.');
            }
        });
    });
    
    // View service report
    window.viewReport = function(id) {
        $.ajax({
            url: '/work-order-management/service-reports/get/' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const report = response.data;
                    
                    // Create view modal or redirect to view page
                    // For now, we'll show an alert with basic info
                    let reportInfo = `Report ID: ${report.id}\\n`;
                    reportInfo += `Work Order: ${report.work_order_title || 'N/A'}\\n`;
                    reportInfo += `Service Type: ${report.service_type || 'N/A'}\\n`;
                    reportInfo += `Status: ${report.status}\\n`;
                    reportInfo += `Report Date: ${report.report_date}\\n`;
                    reportInfo += `Work Summary: ${report.work_summary || 'N/A'}`;
                    
                    alert(reportInfo);
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while fetching report details.');
            }
        });
    };
    
    // Edit service report
    window.editReport = function(id) {
        $.ajax({
            url: '/work-order-management/service-reports/get/' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const report = response.data;
                    
                    // Populate edit form
                    $('#editServiceReportId').val(report.id);
                    $('#editServiceAppointmentId').val(report.service_appointment_id);
                    $('#editWorkOrderId').val(report.work_order_id);
                    $('#editTechnicianId').val(report.technician_id);
                    $('#editReportDate').val(report.report_date);
                    $('#editStatus').val(report.status);
                    $('#editServiceType').val(report.service_type);
                    $('#editWorkSummary').val(report.work_summary);
                    $('#editPartsUsed').val(report.parts_used);
                    $('#editTimeSpent').val(report.time_spent);
                    $('#editLaborCost').val(report.labor_cost);
                    $('#editMaterialCost').val(report.material_cost);
                    $('#editTotalCost').val(report.total_cost);
                    $('#editCustomerFeedback').val(report.customer_feedback);
                    $('#editRecommendations').val(report.recommendations);
                    $('#editAdditionalNotes').val(report.additional_notes);
                    
                    $('#editServiceReportModal').modal('show');
                } else {
                    showAlert('error', response.message);
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while fetching report details.');
            }
        });
    };
    
    // Update service report form submission
    $('#editServiceReportForm').on('submit', function(e) {
        e.preventDefault();
        
        const reportId = $('#editServiceReportId').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: '/work-order-management/service-reports/update/' + reportId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#editServiceReportModal').modal('hide');
                    location.reload();
                } else {
                    if (response.errors) {
                        let errorMessage = 'Please fix the following errors:\\n';
                        for (let field in response.errors) {
                            errorMessage += '• ' + response.errors[field] + '\\n';
                        }
                        showAlert('error', errorMessage);
                    } else {
                        showAlert('error', response.message);
                    }
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while updating the service report.');
            }
        });
    });
    
    // Delete service report
    window.deleteReport = function(id) {
        if (confirm('Are you sure you want to delete this service report?')) {
            $.ajax({
                url: '/work-order-management/service-reports/delete/' + id,
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
                    showAlert('error', 'An error occurred while deleting the service report.');
                }
            });
        }
    };
    
    // Update service report status
    window.updateReportStatus = function(id, status) {
        $.ajax({
            url: '/work-order-management/service-reports/status/' + id,
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
                showAlert('error', 'An error occurred while updating the report status.');
            }
        });
    };
    
    // Search service reports
    $('#searchReports').on('input', function() {
        const searchTerm = $(this).val();
        
        if (searchTerm.length > 2 || searchTerm.length === 0) {
            $.ajax({
                url: '/work-order-management/service-reports/search',
                type: 'GET',
                data: { q: searchTerm },
                success: function(response) {
                    if (response.success) {
                        updateReportsTable(response.data);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'An error occurred while searching service reports.');
                }
            });
        }
    });
    
    // Filter by status
    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        
        $.ajax({
            url: '/work-order-management/service-reports/search',
            type: 'GET',
            data: { status: status },
            success: function(response) {
                if (response.success) {
                    updateReportsTable(response.data);
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while filtering service reports.');
            }
        });
    });
    
    // Filter by date
    $('#dateFilter').on('change', function() {
        const date = $(this).val();
        
        $.ajax({
            url: '/work-order-management/service-reports/search',
            type: 'GET',
            data: { date: date },
            success: function(response) {
                if (response.success) {
                    updateReportsTable(response.data);
                }
            },
            error: function(xhr) {
                showAlert('error', 'An error occurred while filtering service reports.');
            }
        });
    });
    
    // Update reports table
    function updateReportsTable(reports) {
        if (reportsTable) {
            reportsTable.clear();
            
            reports.forEach(function(report) {
                const statusBadge = getStatusBadge(report.status);
                const actions = getActionButtons(report.id, report.status);
                
                reportsTable.row.add([
                    report.id,
                    report.work_order_title || 'N/A',
                    report.service_appointment_id || 'N/A',
                    report.technician_name || 'Unassigned',
                    new Date(report.report_date).toLocaleDateString(),
                    statusBadge,
                    actions
                ]);
            });
            
            reportsTable.draw();
        }
    }
    
    // Get status badge HTML
    function getStatusBadge(status) {
        const badges = {
            'draft': '<span class="badge badge-secondary">Draft</span>',
            'completed': '<span class="badge badge-primary">Completed</span>',
            'submitted': '<span class="badge badge-warning">Submitted</span>',
            'approved': '<span class="badge badge-success">Approved</span>'
        };
        return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
    }
    
    // Get action buttons HTML
    function getActionButtons(id, status) {
        let actions = '<div class="btn-group" role="group">';
        
        actions += '<button class="btn btn-sm btn-outline-primary" onclick="viewReport(' + id + ')" title="View">';
        actions += '<i class="fas fa-eye"></i></button>';
        
        actions += '<button class="btn btn-sm btn-outline-primary" onclick="editReport(' + id + ')" title="Edit">';
        actions += '<i class="fas fa-edit"></i></button>';
        
        if (status === 'completed') {
            actions += '<button class="btn btn-sm btn-outline-warning" onclick="updateReportStatus(' + id + ', \\'submitted\\')" title="Submit">';
            actions += '<i class="fas fa-paper-plane"></i></button>';
        }
        
        if (status === 'submitted') {
            actions += '<button class="btn btn-sm btn-outline-success" onclick="updateReportStatus(' + id + ', \\'approved\\')" title="Approve">';
            actions += '<i class="fas fa-check"></i></button>';
        }
        
        actions += '<button class="btn btn-sm btn-outline-danger" onclick="deleteReport(' + id + ')" title="Delete">';
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
