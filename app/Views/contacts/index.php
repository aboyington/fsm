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
                    <li class="breadcrumb-item active" aria-current="page">Contacts</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Contacts</h1>
        </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createContactModal">
                    <i class="bi bi-plus-circle"></i> Add Contact
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> More
                    </button>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">Export</h6></li>
                        <li><a class="dropdown-item" href="#" onclick="exportContacts()">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Export Contacts
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Import</h6></li>
                        <li><a class="dropdown-item" href="#" onclick="showImportModal()">
                            <i class="bi bi-file-earmark-arrow-up"></i> Import Contacts
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="downloadTemplate()">
                            <i class="bi bi-file-earmark-text"></i> Download Contacts Template
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($contacts)): ?>
    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center py-5">
            <!-- Contact Illustration -->
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4" style="width: 200px; height: 140px;">
                    <p>No contacts found.</p>
                </div>
            </div>
            
            <!-- Content -->
            <h2 class="h4 mb-3">Contacts</h2>
            <p class="text-muted mb-4">
                Organize contact information with multiple service locations and billing addresses. Keep easy track on all the previous requests, estimates, work orders, and appointments while also staying on top of invoices and payments, ensuring easy management of overdue tasks and pending payments.
            </p>
            
            <!-- Add Contact Button -->
            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createContactModal">
                <i class="bi bi-plus-circle me-2"></i>Create Contact
            </button>
        </div>
    </div>
    <?php else: ?>
    <!-- Contacts List -->
    <div class="row">
        <div class="col-12">
            <!-- Filter Bar -->
            <div class="mb-4 p-3 bg-light rounded">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchContacts" placeholder="Search contacts...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="companyFilter">
                            <option value="">All Companies</option>
                            <?php foreach ($companies as $company): ?>
                            <option value="<?= $company['id'] ?>"><?= esc($company['client_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="text-muted">
                            Total: <?= $total_contacts ?> | 
                            Active: <?= $active_contacts ?> | 
                            Inactive: <?= $inactive_contacts ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contacts Table -->
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Account Number</th>
                            <th>Company</th>
                            <th>Job Title</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Primary</th>
                            <th>Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="contactsTableBody">
                        <?= $this->include('contacts/_contactsList', ['contacts' => $contacts]) ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->include('contacts/_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Set base URL for external JS file
    window.FSM_BASE_URL = '<?= base_url() ?>';
</script>
<script src="<?= base_url('js/contacts.js') ?>"></script>
<?= $this->endSection() ?>

