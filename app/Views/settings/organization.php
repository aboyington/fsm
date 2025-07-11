<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="#">Organization Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('settings/fiscal-year') ?>">Fiscal Year</a>
        </li>
    </ul>

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-2">Organization Profile <small class="text-muted"><i class="bi bi-question-circle" title="Help"></i></small></h4>
            <p class="text-muted mb-0">Summary of your Organization. You can set up your organization details, address and locale preferences over here, which will reflect in the documents like estimates, Service Reports and invoices.</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editCompanyModal">Edit</button>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <h6 class="text-muted mb-3"><i class="bi bi-caret-down-fill"></i> Basic Information</h6>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-muted" style="width: 200px;">Company Name</td>
                        <td><?= esc($organization['company_name'] ?? '--') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Industry</td>
                        <td><?= esc(($organization['industry_type'] ?? '') . ($organization['industry'] ? ' - ' . $organization['industry'] : '')) ?: '--' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Website</td>
                        <td><?= $organization['website'] ? '<a href="' . esc($organization['website']) . '" target="_blank">' . esc($organization['website']) . '</a>' : '--' ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td><?= esc($organization['phone'] ?? '--') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Mobile</td>
                        <td><?= esc($organization['mobile'] ?? '--') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fax</td>
                        <td><?= esc($organization['fax'] ?? '--') ?></td>
                    </tr>
                </tbody>
            </table>

            <h6 class="text-muted mb-3 mt-4"><i class="bi bi-caret-down-fill"></i> Location Information</h6>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-muted" style="width: 200px;">Business Location</td>
                        <td><?= esc($organization['business_location'] ?? '--') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Address</td>
                        <td>
                            <?php if ($organization && ($organization['street'] || $organization['city'] || $organization['state'] || $organization['zip_code'])): ?>
                                <?= esc($organization['street'] ?? '') ?><br>
                                <?= esc($organization['city'] ?? '') ?><?= $organization['state'] ? ', ' . esc($organization['state']) : '' ?> <?= esc($organization['zip_code'] ?? '') ?><br>
                                <?= esc($organization['country'] ?? '') ?>
                            <?php else: ?>
                                No Location Information
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h6 class="text-muted mb-3 mt-4"><i class="bi bi-caret-down-fill"></i> Preference</h6>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-muted" style="width: 200px;">Currency</td>
                        <td>Canadian Dollar - CAD</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Time Zone</td>
                        <td><?= esc($timezoneOptions[$organization['time_zone']] ?? $organization['time_zone'] ?? '--') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date Format</td>
                        <td><?= esc($organization['date_format'] ?? '--') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Time Format</td>
                        <td><?= esc($organization['time_format'] ?? '--') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Distance Unit</td>
                        <td><?= esc($organization['distance_unit'] ?? '--') ?></td>
                    </tr>
                </tbody>
            </table>

            <h6 class="text-muted mb-3 mt-4"><i class="bi bi-caret-down-fill"></i> Business Hours Details &nbsp;<a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#businessHoursModal">Configure</a></h6>
            <p class="mb-0"><?= esc($businessHoursFormatted ?? '24 Hours X 7 days') ?></p>
            
            <div class="mt-5">
                <button class="btn btn-danger">Delete FSM Organization</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Company Modal -->
<div class="modal fade" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyModalLabel">Edit Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCompanyForm" action="<?= base_url('settings/organization/update') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <!-- Basic Information -->
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Company Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="company_name" value="<?= esc($organization['company_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Industry</label>
                        <div class="col-sm-9">
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="industry_type" id="residential" value="Residential" <?= ($organization['industry_type'] ?? '') == 'Residential' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="residential">Residential</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="industry_type" id="commercial" value="Commercial" <?= ($organization['industry_type'] ?? '') == 'Commercial' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="commercial">Commercial</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="industry_type" id="industrial" value="Industrial" <?= ($organization['industry_type'] ?? '') == 'Industrial' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="industrial">Industrial</label>
                                </div>
                            </div>
                            <select class="form-select" name="industry" id="industry">
                                <option value="">Select Industry</option>
                                <?php foreach ($industryOptions as $type => $industries): ?>
                                    <optgroup label="<?= esc($type) ?>">
                                        <?php foreach ($industries as $industry): ?>
                                            <option value="<?= esc($industry) ?>" <?= ($organization['industry'] ?? '') == $industry ? 'selected' : '' ?>><?= esc($industry) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Website</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" name="website" value="<?= esc($organization['website'] ?? '') ?>" placeholder="https://www.example.com">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Phone</label>
                        <div class="col-sm-9">
                            <input type="tel" class="form-control" name="phone" value="<?= esc($organization['phone'] ?? '') ?>" placeholder="(123) 456-7890">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Mobile</label>
                        <div class="col-sm-9">
                            <input type="tel" class="form-control" name="mobile" value="<?= esc($organization['mobile'] ?? '') ?>" placeholder="(123) 456-7890">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label text-end">Fax</label>
                        <div class="col-sm-9">
                            <input type="tel" class="form-control" name="fax" value="<?= esc($organization['fax'] ?? '') ?>" placeholder="(123) 456-7890">
                        </div>
                    </div>
                    
                    <!-- Location Information -->
                    <h6 class="mb-3">Location Information</h6>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Business Location</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="business_location">
                                <option value="">Select</option>
                                <option value="Main Office" <?= ($organization['business_location'] ?? '') == 'Main Office' ? 'selected' : '' ?>>Main Office</option>
                                <option value="Branch 1" <?= ($organization['business_location'] ?? '') == 'Branch 1' ? 'selected' : '' ?>>Branch 1</option>
                                <option value="Branch 2" <?= ($organization['business_location'] ?? '') == 'Branch 2' ? 'selected' : '' ?>>Branch 2</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Street</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="street" value="<?= esc($organization['street'] ?? '') ?>" placeholder="123 Main Street">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">City</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="city" value="<?= esc($organization['city'] ?? '') ?>" placeholder="Toronto">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">State</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="state" value="<?= esc($organization['state'] ?? '') ?>" placeholder="Ontario">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Zip Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="zip_code" value="<?= esc($organization['zip_code'] ?? '') ?>" placeholder="M5V 3A8">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <label class="col-sm-3 col-form-label text-end">Country</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="country" value="<?= esc($organization['country'] ?? '') ?>" placeholder="Canada">
                        </div>
                    </div>
                    
                    <!-- Preference -->
                    <h6 class="mb-3">Preference</h6>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Time Zone</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="time_zone">
                                <?php foreach ($timezoneOptions as $value => $label): ?>
                                    <option value="<?= esc($value) ?>" <?= ($organization['time_zone'] ?? '') == $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Date Format</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="date_format">
                                <option value="MM/DD/YYYY" <?= ($organization['date_format'] ?? '') == 'MM/DD/YYYY' ? 'selected' : '' ?>>MM/DD/YYYY</option>
                                <option value="DD/MM/YYYY" <?= ($organization['date_format'] ?? '') == 'DD/MM/YYYY' ? 'selected' : '' ?>>DD/MM/YYYY</option>
                                <option value="YYYY-MM-DD" <?= ($organization['date_format'] ?? '') == 'YYYY-MM-DD' ? 'selected' : '' ?>>YYYY-MM-DD</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Time Format</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="time_format">
                                <option value="12 Hour" <?= ($organization['time_format'] ?? '') == '12 Hour' ? 'selected' : '' ?>>12 Hour</option>
                                <option value="24 Hour" <?= ($organization['time_format'] ?? '') == '24 Hour' ? 'selected' : '' ?>>24 Hour</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Distance Unit</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="distance_unit">
                                <option value="Miles" <?= ($organization['distance_unit'] ?? '') == 'Miles' ? 'selected' : '' ?>>Miles</option>
                                <option value="Kilometers" <?= ($organization['distance_unit'] ?? '') == 'Kilometers' ? 'selected' : '' ?>>Kilometers</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Business Hours Modal -->
<div class="modal fade" id="businessHoursModal" tabindex="-1" aria-labelledby="businessHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="businessHoursModalLabel">Business Hours</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="businessHoursForm" action="<?= base_url('settings/business-hours/update') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <h6 class="mb-3">Business Hours Details</h6>
                    
                    <div class="mb-4">
                        <label class="form-label">Business Hours</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="business_hours_type" id="hours24x7" value="24x7" <?= ($businessHours['business_hours_type'] ?? '24x7') == '24x7' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="hours24x7">
                                24 Hours X 7 days
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="business_hours_type" id="hours24x5" value="24x5" <?= ($businessHours['business_hours_type'] ?? '') == '24x5' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="hours24x5">
                                24 Hours X 5 days
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="business_hours_type" id="hoursCustom" value="custom" <?= ($businessHours['business_hours_type'] ?? '') == 'custom' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="hoursCustom">
                                Custom Hours
                            </label>
                        </div>
                    </div>
                    
                    <div id="customHoursSection" style="<?= ($businessHours['business_hours_type'] ?? '24x7') == 'custom' ? '' : 'display: none;' ?>">
                        <label class="form-label">Week Starts On</label>
                        <select class="form-select mb-3" name="week_starts_on">
                            <option value="">Select Weekday</option>
                            <?php foreach ($weekdays as $key => $day): ?>
                                <option value="<?= esc($key) ?>"><?= esc($day) ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <label class="form-label mb-2">Working Hours</label>
                        <?php foreach ($weekdays as $key => $day): ?>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label class="form-label small"><?= esc($day) ?></label>
                                </div>
                                <div class="col-4">
                                    <select class="form-select form-select-sm" name="<?= $key ?>_start">
                                        <option value="">Start Time</option>
                                        <?php foreach ($timeOptions as $value => $label): ?>
                                            <option value="<?= esc($value) ?>" <?= ($businessHours[$key . '_start'] ?? '') == $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-select form-select-sm" name="<?= $key ?>_end">
                                        <option value="">End Time</option>
                                        <?php foreach ($timeOptions as $value => $label): ?>
                                            <option value="<?= esc($value) ?>" <?= ($businessHours[$key . '_end'] ?? '') == $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Industry options data
const industryOptions = <?= json_encode($industryOptions) ?>;

// Handle industry type change
function updateIndustryDropdown() {
    const selectedType = document.querySelector('input[name="industry_type"]:checked')?.value;
    const industrySelect = document.getElementById('industry');
    const currentValue = industrySelect.value;
    
    // Clear current options
    industrySelect.innerHTML = '<option value="">Select Industry</option>';
    
    if (selectedType && industryOptions[selectedType]) {
        const optgroup = document.createElement('optgroup');
        optgroup.label = selectedType;
        
        industryOptions[selectedType].forEach(industry => {
            const option = document.createElement('option');
            option.value = industry;
            option.textContent = industry;
            if (industry === currentValue) {
                option.selected = true;
            }
            optgroup.appendChild(option);
        });
        
        industrySelect.appendChild(optgroup);
    } else {
        // Show all options if no type selected
        Object.keys(industryOptions).forEach(type => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = type;
            
            industryOptions[type].forEach(industry => {
                const option = document.createElement('option');
                option.value = industry;
                option.textContent = industry;
                if (industry === currentValue) {
                    option.selected = true;
                }
                optgroup.appendChild(option);
            });
            
            industrySelect.appendChild(optgroup);
        });
    }
}

// Add event listeners to radio buttons
document.querySelectorAll('input[name="industry_type"]').forEach(radio => {
    radio.addEventListener('change', updateIndustryDropdown);
});

// Get auth token from localStorage or sessionStorage
function getAuthToken() {
    // First try localStorage (from API client)
    let token = localStorage.getItem('authToken');
    if (token) return token;
    
    // Try to get from PHP session via a meta tag or data attribute
    const tokenMeta = document.querySelector('meta[name="auth-token"]');
    if (tokenMeta) return tokenMeta.content;
    
    return null;
}

// Handle form submission
document.getElementById('editCompanyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const authToken = getAuthToken();
    
    const headers = {
        'X-Requested-With': 'XMLHttpRequest'
    };
    
    // Add auth token if available
    if (authToken) {
        headers['Authorization'] = `Bearer ${authToken}`;
        headers['X-API-Token'] = authToken;
    }
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: headers
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editCompanyModal'));
            modal.hide();
            
            // Show success message
            showAlert(data.message || 'Organization details updated successfully', 'success');
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'Failed to update organization details', 'danger');
            
            // Show validation errors if any
            if (data.errors) {
                let errorMessage = 'Validation errors:\n';
                Object.keys(data.errors).forEach(field => {
                    errorMessage += '- ' + data.errors[field] + '\n';
                });
                console.error(errorMessage);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('An error occurred while updating', 'danger');
    }
});

// Business Hours Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Toggle custom hours section based on business hours type
    const businessHoursRadios = document.querySelectorAll('input[name="business_hours_type"]');
    const customHoursSection = document.getElementById('customHoursSection');
    
    businessHoursRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'custom') {
                customHoursSection.style.display = '';
            } else {
                customHoursSection.style.display = 'none';
            }
        });
    });
    
    // Handle business hours form submission
    const businessHoursForm = document.getElementById('businessHoursForm');
    if (businessHoursForm) {
        businessHoursForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const authToken = getAuthToken();
            
            const headers = {
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            if (authToken) {
                headers['Authorization'] = `Bearer ${authToken}`;
                headers['X-API-Token'] = authToken;
            }
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: headers
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('businessHoursModal'));
                    modal.hide();
                    
                    // Show success message
                    showAlert(data.message || 'Business hours updated successfully', 'success');
                    
                    // Reload page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Failed to update business hours', 'danger');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('An error occurred while updating business hours', 'danger');
            }
        });
    }
});
</script>
<?= $this->endSection() ?>

