<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('settings/organization') ?>">Organization Details</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">Fiscal Year</a>
        </li>
    </ul>

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-2">Fiscal Year <small class="text-muted"><i class="bi bi-question-circle" title="Help"></i></small></h4>
            <p class="text-muted mb-0">Configure your organization's fiscal year settings. These settings will be used for financial reporting and accounting periods.</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editFiscalModal">Edit</button>
    </div>

    <div class="card border-0">
        <div class="card-body">
            <h6 class="text-muted mb-3"><i class="bi bi-caret-down-fill"></i> Fiscal Year Settings</h6>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="text-muted" style="width: 200px;">Fiscal Year Format</td>
                        <td><?= ucfirst(esc($fiscalYear['fiscal_year_format'] ?? 'calendar')) ?> Year</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fiscal Year Start</td>
                        <td>
                            <?php
                            if (isset($fiscalYear['fiscal_year_start'])) {
                                list($month, $day) = explode('-', $fiscalYear['fiscal_year_start']);
                                echo esc($monthOptions[$month] ?? '') . ' ' . intval($day);
                            } else {
                                echo 'January 1';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fiscal Year End</td>
                        <td>
                            <?php
                            if (isset($fiscalYear['fiscal_year_end'])) {
                                list($month, $day) = explode('-', $fiscalYear['fiscal_year_end']);
                                echo esc($monthOptions[$month] ?? '') . ' ' . intval($day);
                            } else {
                                echo 'December 31';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Current Fiscal Year</td>
                        <td><?= esc($fiscalYear['current_fiscal_year'] ?? date('Y')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Fiscal Year Modal -->
<div class="modal fade" id="editFiscalModal" tabindex="-1" aria-labelledby="editFiscalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFiscalModalLabel">Edit Fiscal Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFiscalForm" action="<?= base_url('settings/fiscal-year/update') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <h6 class="mb-3">Fiscal Year Configuration</h6>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Fiscal Year Format</label>
                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fiscal_year_format" id="calendar" value="calendar" <?= ($fiscalYear['fiscal_year_format'] ?? 'calendar') == 'calendar' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="calendar">
                                    Calendar Year (January 1 - December 31)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fiscal_year_format" id="custom" value="custom" <?= ($fiscalYear['fiscal_year_format'] ?? '') == 'custom' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="custom">
                                    Custom Fiscal Year
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="customFiscalFields" style="<?= ($fiscalYear['fiscal_year_format'] ?? 'calendar') == 'custom' ? '' : 'display: none;' ?>">
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label text-end">Fiscal Year Start</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-6">
                                        <?php 
                                        $startMonth = '01';
                                        $startDay = '01';
                                        if (isset($fiscalYear['fiscal_year_start'])) {
                                            list($startMonth, $startDay) = explode('-', $fiscalYear['fiscal_year_start']);
                                        }
                                        ?>
                                        <select class="form-select" name="fiscal_start_month" id="fiscal_start_month">
                                            <?php foreach ($monthOptions as $value => $label): ?>
                                                <option value="<?= esc($value) ?>" <?= $startMonth == $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select" name="fiscal_start_day" id="fiscal_start_day">
                                            <?php foreach ($dayOptions as $value => $label): ?>
                                                <option value="<?= esc($value) ?>" <?= $startDay == $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label text-end">Fiscal Year End</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-6">
                                        <?php 
                                        $endMonth = '12';
                                        $endDay = '31';
                                        if (isset($fiscalYear['fiscal_year_end'])) {
                                            list($endMonth, $endDay) = explode('-', $fiscalYear['fiscal_year_end']);
                                        }
                                        ?>
                                        <select class="form-select" name="fiscal_end_month" id="fiscal_end_month">
                                            <?php foreach ($monthOptions as $value => $label): ?>
                                                <option value="<?= esc($value) ?>" <?= $endMonth == $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select" name="fiscal_end_day" id="fiscal_end_day">
                                            <?php foreach ($dayOptions as $value => $label): ?>
                                                <option value="<?= esc($value) ?>" <?= $endDay == $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label text-end">Current Fiscal Year</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="current_fiscal_year">
                                <?php foreach ($yearOptions as $year): ?>
                                    <option value="<?= esc($year) ?>" <?= ($fiscalYear['current_fiscal_year'] ?? date('Y')) == $year ? 'selected' : '' ?>><?= esc($year) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Toggle custom fiscal year fields
    const formatRadios = document.querySelectorAll('input[name="fiscal_year_format"]');
    const customFields = document.getElementById('customFiscalFields');
    
    formatRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'custom') {
                customFields.style.display = '';
            } else {
                customFields.style.display = 'none';
            }
        });
    });
    
    // Handle form submission
    document.getElementById('editFiscalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Combine month and day for fiscal year start/end
        if (formData.get('fiscal_year_format') === 'custom') {
            const startMonth = formData.get('fiscal_start_month');
            const startDay = formData.get('fiscal_start_day');
            const endMonth = formData.get('fiscal_end_month');
            const endDay = formData.get('fiscal_end_day');
            
            formData.append('fiscal_year_start', startMonth + '-' + startDay);
            formData.append('fiscal_year_end', endMonth + '-' + endDay);
        } else {
            // Default to calendar year
            formData.append('fiscal_year_start', '01-01');
            formData.append('fiscal_year_end', '12-31');
        }
        
        // Get auth token if it exists
        const authToken = localStorage.getItem('authToken');
        const headers = {
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (authToken) {
            headers['Authorization'] = 'Bearer ' + authToken;
        }
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: headers
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Fiscal year settings updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to update fiscal year settings'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating fiscal year settings');
        });
    });
});
</script>
<?= $this->endSection() ?>
