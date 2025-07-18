<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="background-color: #ffffff; min-height: 100vh; margin: 0; padding-top: 20px;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Customers</li>
                    <li class="breadcrumb-item active" aria-current="page">Companies</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Companies</h1>
        </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
                    <i class="bi bi-plus-circle"></i> Add Company
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> More
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Export</h6></li>
                        <li><a class="dropdown-item" href="#" onclick="exportCompanies()">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Export Companies
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Import</h6></li>
                        <li><a class="dropdown-item" href="#" onclick="showImportModal()">
                            <i class="bi bi-file-earmark-arrow-up"></i> Import Companies
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="downloadTemplate()">
                            <i class="bi bi-file-earmark-text"></i> Download Companies Template
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($companies)): ?>
    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center py-5">
            <!-- Company Illustration -->
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4" style="width: 200px; height: 140px;">
                    <!-- Building/Company Icon -->
                    <div class="position-relative">
                        <!-- Main building -->
                        <div class="bg-success rounded-2 me-2" style="width: 60px; height: 80px; position: relative;">
                            <!-- Windows -->
                            <div class="bg-white rounded-1" style="width: 8px; height: 8px; position: absolute; top: 10px; left: 8px;"></div>
                            <div class="bg-white rounded-1" style="width: 8px; height: 8px; position: absolute; top: 10px; right: 8px;"></div>
                            <div class="bg-white rounded-1" style="width: 8px; height: 8px; position: absolute; top: 25px; left: 8px;"></div>
                            <div class="bg-white rounded-1" style="width: 8px; height: 8px; position: absolute; top: 25px; right: 8px;"></div>
                            <div class="bg-white rounded-1" style="width: 8px; height: 8px; position: absolute; top: 40px; left: 8px;"></div>
                            <div class="bg-white rounded-1" style="width: 8px; height: 8px; position: absolute; top: 40px; right: 8px;"></div>
                            <!-- Door -->
                            <div class="bg-primary rounded-1" style="width: 12px; height: 20px; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);"></div>
                        </div>
                        
                        <!-- Side building -->
                        <div class="bg-info rounded-2" style="width: 40px; height: 60px; position: absolute; top: 20px; right: -35px;">
                            <!-- Windows -->
                            <div class="bg-white rounded-1" style="width: 6px; height: 6px; position: absolute; top: 8px; left: 6px;"></div>
                            <div class="bg-white rounded-1" style="width: 6px; height: 6px; position: absolute; top: 8px; right: 6px;"></div>
                            <div class="bg-white rounded-1" style="width: 6px; height: 6px; position: absolute; top: 20px; left: 6px;"></div>
                            <div class="bg-white rounded-1" style="width: 6px; height: 6px; position: absolute; top: 20px; right: 6px;"></div>
                            <div class="bg-white rounded-1" style="width: 6px; height: 6px; position: absolute; top: 32px; left: 6px;"></div>
                            <div class="bg-white rounded-1" style="width: 6px; height: 6px; position: absolute; top: 32px; right: 6px;"></div>
                        </div>
                        
                        <!-- Documents/List -->
                        <div class="bg-white border rounded-2" style="width: 50px; height: 60px; position: absolute; top: 10px; left: -45px; padding: 6px;">
                            <!-- Document lines -->
                            <div class="bg-secondary rounded" style="width: 100%; height: 2px; margin-bottom: 3px;"></div>
                            <div class="bg-secondary rounded" style="width: 80%; height: 2px; margin-bottom: 3px;"></div>
                            <div class="bg-secondary rounded" style="width: 90%; height: 2px; margin-bottom: 3px;"></div>
                            <div class="bg-secondary rounded" style="width: 70%; height: 2px; margin-bottom: 3px;"></div>
                            <div class="bg-secondary rounded" style="width: 85%; height: 2px; margin-bottom: 3px;"></div>
                            <div class="bg-secondary rounded" style="width: 95%; height: 2px; margin-bottom: 3px;"></div>
                            <div class="bg-secondary rounded" style="width: 75%; height: 2px; margin-bottom: 3px;"></div>
                            <div class="bg-secondary rounded" style="width: 90%; height: 2px;"></div>
                            
                            <!-- Company info section -->
                            <div class="mt-2 pt-2 border-top">
                                <div class="bg-success rounded" style="width: 60%; height: 2px; margin-bottom: 2px;"></div>
                                <div class="bg-success rounded" style="width: 40%; height: 2px; margin-bottom: 2px;"></div>
                                <div class="bg-success rounded" style="width: 50%; height: 2px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <h2 class="h4 mb-3">Companies</h2>
            <p class="text-muted mb-4">
                Organize contact information with multiple service locations and billing addresses. Keep easy track on all the previous requests, estimates, work orders, and appointments while also staying on top of invoices and payments, ensuring easy management of overdue tasks and pending payments.
            </p>
            
            <!-- Create Company Button -->
            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
                <i class="bi bi-plus-circle me-2"></i>Create Company
            </button>
        </div>
    </div>
    <?php else: ?>
    <!-- Companies List -->
    <div class="row">
        <div class="col-12">
            <!-- Filter Bar -->
            <div class="mb-4 p-3 bg-light rounded">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchCompanies" placeholder="Search companies...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-5 text-end">
                        <span class="text-muted">
                            Total: <?= $total_companies ?> | 
                            Active: <?= $active_companies ?> | 
                            Inactive: <?= $inactive_companies ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Companies Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Company Name</th>
                            <th>Account Number</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="companiesTableBody">
                        <?php foreach ($companies as $company): ?>
                        <tr>
                            <td>
                                <h6 class="mb-1"><?= esc($company['client_name']) ?></h6>
                            </td>
                            <td><?= esc($company['account_number'] ?? '-') ?></td>
                            <td><?= esc($company['contact_person'] ?? '-') ?></td>
                            <td><?= esc($company['email'] ?? '-') ?></td>
                            <td><?= esc($company['phone'] ?? '-') ?></td>
                            <td>
                                <?php if ($company['city'] || $company['state']): ?>
                                    <?= esc($company['city']) ?><?= $company['city'] && $company['state'] ? ', ' : '' ?><?= esc($company['state']) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($company['status'] === 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M j, Y', strtotime($company['created_at'])) ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editCompany(<?= $company['id'] ?>)" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCompany(<?= $company['id'] ?>)" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Create/Edit Company Modal -->
<div class="modal fade" id="createCompanyModal" tabindex="-1" aria-labelledby="createCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCompanyModalLabel">Create Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="companyForm">
                <div class="modal-body">
                    <input type="hidden" id="companyId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="client_name" name="client_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="account_number" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="account_number" name="account_number" placeholder="Auto-generated if empty">
                                <div class="form-text">Leave empty to auto-generate account number</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <!-- Empty column for layout balance -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="company_type" class="form-label">Account Type</label>
                                <select class="form-select" id="company_type" name="company_type">
                                    <option value="">Select</option>
                                    <option value="Analyst">Analyst</option>
                                    <option value="Competitor">Competitor</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Distributor">Distributor</option>
                                    <option value="Integrator">Integrator</option>
                                    <option value="Investor">Investor</option>
                                    <option value="Other">Other</option>
                                    <option value="Partner">Partner</option>
                                    <option value="Press">Press</option>
                                    <option value="Prospect">Prospect</option>
                                    <option value="Reseller">Reseller</option>
                                    <option value="Supplier">Supplier</option>
                                    <option value="Vendor">Vendor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="zip_code" class="form-label">ZIP/Postal Code</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="Canada">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="territory_id" class="form-label">Territory</label>
                                <select class="form-select" id="territory_id" name="territory_id">
                                    <option value="">Search Territory</option>
                                    <?php foreach ($territories as $territory): ?>
                                    <option value="<?= $territory['id'] ?>"><?= esc($territory['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveCompanyBtn">Create Company</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Company management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const companyForm = document.getElementById('companyForm');
    const createModal = new bootstrap.Modal(document.getElementById('createCompanyModal'));
    
    // Form submission
    companyForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(companyForm);
        const companyId = document.getElementById('companyId').value;
        
        const url = companyId ? 
            `<?= base_url('customers/companies/update') ?>/${companyId}` : 
            `<?= base_url('customers/companies/create') ?>`;
        
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
            showAlert('danger', 'An error occurred while saving the company.');
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchCompanies');
    const statusFilter = document.getElementById('statusFilter');
    
    if (searchInput && statusFilter) {
        let searchTimeout;
        
        function performSearch() {
            const searchTerm = searchInput.value;
            const status = statusFilter.value;
            
            fetch(`<?= base_url('customers/companies/search') ?>?q=${encodeURIComponent(searchTerm)}&status=${status}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCompaniesTable(data.data);
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
    }
});

function editCompany(id) {
    fetch(`<?= base_url('customers/companies/get') ?>/${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const company = data.data;
            
            // Populate form fields
            document.getElementById('companyId').value = company.id;
            document.getElementById('client_name').value = company.client_name || '';
            document.getElementById('account_number').value = company.account_number || '';
            document.getElementById('contact_person').value = company.contact_person || '';
            document.getElementById('email').value = company.email || '';
            document.getElementById('website').value = company.website || '';
            document.getElementById('company_type').value = company.company_type || '';
            document.getElementById('phone').value = company.phone || '';
            document.getElementById('address').value = company.address || '';
            document.getElementById('city').value = company.city || '';
            document.getElementById('state').value = company.state || '';
            document.getElementById('zip_code').value = company.zip_code || '';
            document.getElementById('country').value = company.country || 'Canada';
            document.getElementById('territory_id').value = company.territory_id || '';
            document.getElementById('status').value = company.status || 'active';
            document.getElementById('notes').value = company.notes || '';
            
            // Update modal title and button
            document.getElementById('createCompanyModalLabel').textContent = 'Edit Company';
            document.getElementById('saveCompanyBtn').textContent = 'Update Company';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('createCompanyModal'));
            modal.show();
        } else {
            showAlert('danger', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Failed to load company data.');
    });
}

function deleteCompany(id) {
    if (confirm('Are you sure you want to delete this company? This action cannot be undone.')) {
        fetch(`<?= base_url('customers/companies/delete') ?>/${id}`, {
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
            showAlert('danger', 'Failed to delete company.');
        });
    }
}

function updateCompaniesTable(companies) {
    const tbody = document.getElementById('companiesTableBody');
    
    if (companies.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">No companies found</td></tr>';
        return;
    }
    
    tbody.innerHTML = companies.map(company => `
        <tr>
            <td>
                <h6 class="mb-1">${escapeHtml(company.client_name)}</h6>
            </td>
            <td>${company.account_number || '-'}</td>
            <td>${company.contact_person || '-'}</td>
            <td>${company.email || '-'}</td>
            <td>${company.phone || '-'}</td>
            <td>${(company.city || company.state) ? `${company.city || ''}${company.city && company.state ? ', ' : ''}${company.state || ''}` : '-'}</td>
            <td>
                ${company.status === 'active' ? 
                    '<span class="badge bg-success">Active</span>' : 
                    '<span class="badge bg-secondary">Inactive</span>'
                }
            </td>
            <td>
                <small class="text-muted">
                    ${new Date(company.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}
                </small>
            </td>
            <td class="text-center">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editCompany(${company.id})" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCompany(${company.id})" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function escapeHtml(text) {
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

// Export Companies function
function exportCompanies() {
    showAlert('info', 'Preparing companies export...');
    
    // Create a temporary form to trigger the export
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `<?= base_url('customers/companies/export') ?>`;
    form.style.display = 'none';
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Show Import Modal function
function showImportModal() {
    // Create and show import modal
    const importModalHtml = `
        <div class="modal fade" id="importCompaniesModal" tabindex="-1" aria-labelledby="importCompaniesModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importCompaniesModalLabel">Import Companies</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="importCompaniesForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="importFile" class="form-label">Choose CSV File</label>
                                <input type="file" class="form-control" id="importFile" name="import_file" accept=".csv" required>
                                <div class="form-text">Upload a CSV file with company data. <a href="#" onclick="downloadTemplate()">Download template</a> for the correct format.</div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing" value="1">
                                    <label class="form-check-label" for="updateExisting">
                                        Update existing companies (match by name)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Import Companies</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if present
    const existingModal = document.getElementById('importCompaniesModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', importModalHtml);
    
    // Show modal
    const importModal = new bootstrap.Modal(document.getElementById('importCompaniesModal'));
    importModal.show();
    
    // Handle form submission
    document.getElementById('importCompaniesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Importing...';
        
        fetch(`<?= base_url('customers/companies/import') ?>`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                importModal.hide();
                showAlert('success', data.message);
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
            submitBtn.textContent = 'Import Companies';
        });
    });
}

// Download Template function
function downloadTemplate() {
    const link = document.createElement('a');
    link.href = `<?= base_url('customers/companies/template') ?>`;
    link.download = 'companies_template.csv';
    link.style.display = 'none';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showAlert('info', 'Companies template download started.');
}

// Reset modal form when hidden
document.getElementById('createCompanyModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('companyForm').reset();
    document.getElementById('companyId').value = '';
    document.getElementById('createCompanyModalLabel').textContent = 'Create Company';
    document.getElementById('saveCompanyBtn').textContent = 'Create Company';
    
    // Clear form errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
});
</script>
<?= $this->endSection() ?>
