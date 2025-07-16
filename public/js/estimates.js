/**
 * Estimates JavaScript functionality
 */

$(document).ready(function() {
    let currentEstimateId = null;
    let serviceRowIndex = 1;
    let partRowIndex = 1;
    
    // Initialize the page
    initializeEstimatesPage();
    
    function initializeEstimatesPage() {
        // Initialize event listeners
        initializeEventListeners();
        
        // Initialize modal form
        initializeModalForm();
        
        // Initialize search and filters
        initializeFilters();
        
        // Initialize calculation functions
        initializeCalculations();
    }
    
    function initializeEventListeners() {
        // Form submission
        $('#estimateForm').on('submit', handleEstimateSubmit);
        
        // Modal events
        $('#createEstimateModal').on('hidden.bs.modal', resetForm);
        $('#createEstimateModal').on('show.bs.modal', function() {
            if (currentEstimateId) {
                loadEstimateForEdit(currentEstimateId);
            }
        });
        
        // Service and part management
        $('#addServiceBtn').on('click', addServiceRow);
        $('#addPartBtn').on('click', addPartRow);
        
        // Contact selection
        $('#contact_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.val()) {
                $('#email').val(selectedOption.data('email') || '');
                $('#phone').val(selectedOption.data('phone') || '');
                $('#mobile').val(selectedOption.data('mobile') || '');
                
                // Auto-select company if available
                const companyId = selectedOption.data('company');
                if (companyId) {
                    $('#company_id').val(companyId);
                }
            } else {
                $('#email').val('');
                $('#phone').val('');
                $('#mobile').val('');
            }
        });
        
        // Company selection
        $('#company_id').on('change', function() {
            const companyId = $(this).val();
            if (companyId) {
                // Filter contacts by company
                filterContactsByCompany(companyId);
            } else {
                // Show all contacts
                showAllContacts();
            }
        });
    }
    
    function initializeModalForm() {
        // Reset form when modal is closed
        $('#createEstimateModal').on('hidden.bs.modal', function() {
            resetForm();
        });
    }
    
    function initializeFilters() {
        // Search functionality
        $('#searchEstimates').on('input', debounce(function() {
            filterEstimates();
        }, 300));
        
        // Status filter
        $('#statusFilter').on('change', function() {
            filterEstimates();
        });
        
        // Company filter
        $('#companyFilter').on('change', function() {
            filterEstimates();
        });
    }
    
    function initializeCalculations() {
        // Service table calculations
        $(document).on('input', '#servicesTable .quantity-input, #servicesTable .rate-input', function() {
            calculateServiceAmount($(this).closest('tr'));
            calculateTotals();
        });
        
        // Part table calculations
        $(document).on('input', '#partsTable .quantity-input, #partsTable .rate-input', function() {
            calculatePartAmount($(this).closest('tr'));
            calculateTotals();
        });
        
        // Service selection
        $(document).on('change', '.service-select', function() {
            const rate = $(this).find('option:selected').data('rate') || 0;
            const row = $(this).closest('tr');
            row.find('.rate-input').val(rate);
            calculateServiceAmount(row);
            calculateTotals();
        });
        
        // Discount and adjustment
        $('#discount, #adjustment').on('input', function() {
            calculateTotals();
        });
        
        // Remove service row
        $(document).on('click', '.remove-service', function() {
            $(this).closest('tr').remove();
            updateRemoveButtons('#servicesTable');
            calculateTotals();
        });
        
        // Remove part row
        $(document).on('click', '.remove-part', function() {
            $(this).closest('tr').remove();
            updateRemoveButtons('#partsTable');
            calculateTotals();
        });
    }
    
    function handleEstimateSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = currentEstimateId ? 
            `/work-order-management/estimates/update/${currentEstimateId}` : 
            '/work-order-management/estimates/create';
        
        // Show loading state
        $('#saveEstimateBtn').prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#createEstimateModal').modal('hide');
                    location.reload(); // Refresh the page to show updated data
                } else {
                    showAlert('error', response.message || 'Failed to save estimate');
                    
                    // Display validation errors
                    if (response.errors) {
                        displayValidationErrors(response.errors);
                    }
                }
            },
            error: function(xhr, status, error) {
                showAlert('error', 'An error occurred while saving the estimate');
                console.error('Error:', error);
            },
            complete: function() {
                $('#saveEstimateBtn').prop('disabled', false).text('Save');
            }
        });
    }