/**
 * Work Orders JavaScript functionality
 */

$(document).ready(function() {
    let currentWorkOrderId = null;
    let serviceRowIndex = 1;
    let partRowIndex = 1;
    let skillRowIndex = 1;
    
    // Initialize the page
    initializeWorkOrdersPage();
    
    function initializeWorkOrdersPage() {
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
        $('#workOrderForm').on('submit', handleWorkOrderSubmit);
        
        // Modal events
        $('#createWorkOrderModal').on('hidden.bs.modal', resetForm);
        $('#createWorkOrderModal').on('show.bs.modal', function() {
            if (currentWorkOrderId) {
                loadWorkOrderForEdit(currentWorkOrderId);
            }
        });
        
        // Service and part management
        $('#addServiceBtn').on('click', addServiceRow);
        $('#addPartBtn').on('click', addPartRow);
        $('#addSkillBtn').on('click', addSkillRow);
        
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
        $('#createWorkOrderModal').on('hidden.bs.modal', function() {
            resetForm();
        });
    }
    
    function initializeFilters() {
        // Search functionality
        $('#searchWorkOrders').on('input', debounce(function() {
            filterWorkOrders();
        }, 300));
        
        // Status filter
        $('#statusFilter').on('change', function() {
            filterWorkOrders();
        });
        
        // Priority filter
        $('#priorityFilter').on('change', function() {
            filterWorkOrders();
        });
        
        // Company filter
        $('#companyFilter').on('change', function() {
            filterWorkOrders();
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

        // Remove skill row
        $(document).on('click', '.remove-skill', function() {
            $(this).closest('tr').remove();
            updateRemoveButtons('#skillsTable');
        });
    }
    
    function handleWorkOrderSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = currentWorkOrderId ? 
            `/work-order-management/work-orders/update/${currentWorkOrderId}` : 
            '/work-order-management/work-orders/create';
        
        // Show loading state
        $('#saveWorkOrderBtn').prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#createWorkOrderModal').modal('hide');
                    location.reload(); // Refresh the page to show updated data
                } else {
                    showAlert('error', response.message || 'Failed to save work order');
                    
                    // Display validation errors
                    if (response.errors) {
                        displayValidationErrors(response.errors);
                    }
                }
            },
            error: function(xhr, status, error) {
                showAlert('error', 'An error occurred while saving the work order');
                console.error('Error:', error);
            },
            complete: function() {
                $('#saveWorkOrderBtn').prop('disabled', false).text('Save');
            }
        });
    }