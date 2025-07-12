<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-2">Transaction Settings <small class="text-muted"><i class="bi bi-question-circle" title="Help"></i></small></h4>
            <p class="text-muted mb-0">Configure transaction-related settings including invoicing and appointment scheduling preferences.</p>
        </div>
    </div>

    <div class="card border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0">Common</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['allow_roundoff_transactions'] ? 'checked' : '' ?> id="allowRoundoff">
                <label class="form-check-label" for="allowRoundoff">
                    Allow roundoff for transactions
                </label>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['password_protect_exported_files'] ? 'checked' : '' ?> id="passwordProtect">
                <label class="form-check-label" for="passwordProtect">
                    Password protect exported files
                </label>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['mobile_checkin_preference'] ? 'checked' : '' ?> id="mobileCheckin">
                <label class="form-check-label" for="mobileCheckin">
                    Mobile App Check-In Preference
                </label>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Mobile Technician Enablement</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['allow_pricing_field_agent'] ? 'checked' : '' ?> id="allowPricing">
                <label class="form-check-label" for="allowPricing">
                    Allow Field Agent to see pricing
                </label>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['allow_technicians_raise_invoices'] ? 'checked' : '' ?> id="allowInvoicing">
                <label class="form-check-label" for="allowInvoicing">
                    Allow technicians to raise invoices
                </label>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Appointment Scheduling Preferences</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['field_agent_appointment_confirmation'] ? 'checked' : '' ?> id="appointmentConfirmation">
                <label class="form-check-label" for="appointmentConfirmation">
                    Field Agent Appointment Confirmation
                </label>
            </div>
            <div class="mt-3 row">
                <div class="col-auto">
                    <label for="appointmentInterval" class="form-label">Minimum Interval for Next Appointment (Ongoing)</label>
                </div>
                <div class="col-auto">
                    <input type="number" class="form-control" id="appointmentInterval" value="<?= esc($settings['minimum_interval_next_appointment']) ?>">
                </div>
                <div class="col-auto">
                    <p class="form-text">Hours</p>
                </div>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['allow_overlapping_appointments'] ? 'checked' : '' ?> id="allowOverlapping">
                <label class="form-check-label" for="allowOverlapping">
                    Allow Overlapping Appointments
                </label>
                <div class="form-text">Appointments can be scheduled in overlapping slots with a warning.</div>
            </div>
            <div class="alert alert-warning mt-3" role="alert">
                <strong>Note:</strong> Territory Restriction for Appointment Scheduling is enabled.
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Work Orders</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['auto_complete_work_order'] ? 'checked' : '' ?> id="autoCompleteWorkOrder">
                <label class="form-check-label" for="autoCompleteWorkOrder">
                    Automatically complete a work order
                </label>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['prompt_complete_work_order'] ? 'checked' : '' ?> id="promptWorkOrder">
                <label class="form-check-label" for="promptWorkOrder">
                    Prompt to complete work order
                </label>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Service Appointments</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['service_report_required_sa_completion'] ? 'checked' : '' ?> id="serviceReportRequired">
                <label class="form-check-label" for="serviceReportRequired">
                    Service Report required for SA completion
                </label>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['jobsheets_completion_required_sa'] ? 'checked' : '' ?> id="jobsheetCompletionRequired">
                <label class="form-check-label" for="jobsheetCompletionRequired">
                    Jobsheets completion required for SA completion
                </label>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Time Sheet</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['auto_pause_timesheet'] ? 'checked' : '' ?> id="autoPauseTimesheet">
                <label class="form-check-label" for="autoPauseTimesheet">
                    Auto Pause
                </label>
            </div>
            <div class="mt-3">
                <label for="autoPauseTime" class="form-label">Auto Pause Time</label>
                <input type="time" class="form-control" id="autoPauseTime" value="<?= esc($settings['auto_pause_time']) ?>">
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['allow_overlapping_timesheet_entries'] ? 'checked' : '' ?> id="allowOverlappingTimesheet">
                <label class="form-check-label" for="allowOverlappingTimesheet">
                    Allow Overlapping or Concurrent Timesheet Entries
                </label>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Service Reports</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['hide_attachments_service_reports'] ? 'checked' : '' ?> id="hideAttachments">
                <label class="form-check-label" for="hideAttachments">
                    Hide Attachments
                </label>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" <?= $settings['remove_customer_signature'] ? 'checked' : '' ?> id="removeCustomerSignature">
                <label class="form-check-label" for="removeCustomerSignature">
                    Remove Customer Signature
                </label>
            </div>
        </div>
    </div>

    <div class="card border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Estimates</h5>
        </div>
        <div class="card-body">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" <?= $settings['estimate_email_approval'] ? 'checked' : '' ?> id="estimateEmailApproval">
                <label class="form-check-label" for="estimateEmailApproval">
                    Estimate - Email Approval
                </label>
            </div>
            <div class="mt-3">
                <label for="emailApprovalExpiry" class="form-label">Expiry Time for Email Approval Link</label>
                <input type="number" class="form-control" id="emailApprovalExpiry" value="<?= esc($settings['email_approval_expiry_days']) ?>">
                <div class="form-text">Days</div>
            </div>
            <div class="mt-3">
                <label for="termsConditions" class="form-label">Terms & Conditions</label>
                <textarea class="form-control" id="termsConditions" rows="3"><?= esc($settings['terms_conditions_estimate']) ?></textarea>
            </div>
            <div class="mt-3">
                <label for="customerNotes" class="form-label">Customer Notes</label>
                <textarea class="form-control" id="customerNotes" name="customer_notes_estimate" rows="3"><?= esc($settings['customer_notes_estimate']) ?></textarea>
            </div>
        </div>
    </div>
    
    <!-- Save Button -->
    <div class="mt-4 text-end">
        <button type="button" class="btn btn-success" id="saveSettings">Save Changes</button>
    </div>
</div>

<script>
// Global alert function for showing messages
function showAlert(message, type = 'success') {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert-notification');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Create new alert
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show alert-notification" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 1055; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Add to body
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert-notification');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 5000);
}

// Set proper names for form elements
document.addEventListener('DOMContentLoaded', function() {
    // Common settings
    document.getElementById('allowRoundoff').name = 'allow_roundoff_transactions';
    document.getElementById('passwordProtect').name = 'password_protect_exported_files';
    document.getElementById('mobileCheckin').name = 'mobile_checkin_preference';
    
    // Mobile Technician Enablement
    document.getElementById('allowPricing').name = 'allow_pricing_field_agent';
    document.getElementById('allowInvoicing').name = 'allow_technicians_raise_invoices';
    
    // Appointment Scheduling Preferences
    document.getElementById('appointmentConfirmation').name = 'field_agent_appointment_confirmation';
    document.getElementById('appointmentInterval').name = 'minimum_interval_next_appointment';
    document.getElementById('allowOverlapping').name = 'allow_overlapping_appointments';
    
    // Work Orders
    document.getElementById('autoCompleteWorkOrder').name = 'auto_complete_work_order';
    document.getElementById('promptWorkOrder').name = 'prompt_complete_work_order';
    
    // Service Appointments
    document.getElementById('serviceReportRequired').name = 'service_report_required_sa_completion';
    document.getElementById('jobsheetCompletionRequired').name = 'jobsheets_completion_required_sa';
    
    // Time Sheet
    document.getElementById('autoPauseTimesheet').name = 'auto_pause_timesheet';
    document.getElementById('autoPauseTime').name = 'auto_pause_time';
    document.getElementById('allowOverlappingTimesheet').name = 'allow_overlapping_timesheet_entries';
    
    // Service Reports
    document.getElementById('hideAttachments').name = 'hide_attachments_service_reports';
    document.getElementById('removeCustomerSignature').name = 'remove_customer_signature';
    
    // Estimates
    document.getElementById('estimateEmailApproval').name = 'estimate_email_approval';
    document.getElementById('emailApprovalExpiry').name = 'email_approval_expiry_days';
    document.getElementById('termsConditions').name = 'terms_conditions_estimate';
    
    // Save button functionality
    document.getElementById('saveSettings').addEventListener('click', saveSettings);
});

// Save settings function
function saveSettings() {
    const saveButton = document.getElementById('saveSettings');
    const originalText = saveButton.textContent;
    
    // Disable button and show loading state
    saveButton.disabled = true;
    saveButton.textContent = 'Saving...';
    
    // Collect all form data
    const formData = new FormData();
    
    // Add CSRF token
    formData.append('csrf_test_name', '<?= csrf_token() ?>');
    
    // Add checkbox values (only if checked)
    const checkboxes = [
        'allow_roundoff_transactions',
        'password_protect_exported_files',
        'mobile_checkin_preference',
        'allow_pricing_field_agent',
        'allow_technicians_raise_invoices',
        'field_agent_appointment_confirmation',
        'allow_overlapping_appointments',
        'auto_complete_work_order',
        'prompt_complete_work_order',
        'service_report_required_sa_completion',
        'jobsheets_completion_required_sa',
        'auto_pause_timesheet',
        'allow_overlapping_timesheet_entries',
        'hide_attachments_service_reports',
        'remove_customer_signature',
        'estimate_email_approval'
    ];
    
    checkboxes.forEach(name => {
        const checkbox = document.querySelector(`[name="${name}"]`);
        if (checkbox && checkbox.checked) {
            formData.append(name, '1');
        }
    });
    
    // Add text/number inputs
    const inputs = [
        'minimum_interval_next_appointment',
        'auto_pause_time',
        'email_approval_expiry_days',
        'terms_conditions_estimate',
        'customer_notes_estimate'
    ];
    
    inputs.forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        if (input) {
            formData.append(name, input.value);
        }
    });
    
    // Send AJAX request
    fetch('<?= base_url("settings/transaction-settings/update") ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Settings saved successfully', 'success');
        } else {
            showAlert(data.message || 'Failed to save settings', 'danger');
            if (data.errors) {
                console.error('Validation errors:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while saving settings', 'danger');
    })
    .finally(() => {
        // Re-enable button and restore text
        saveButton.disabled = false;
        saveButton.textContent = originalText;
    });
}
</script>

<?= $this->endSection() ?>

