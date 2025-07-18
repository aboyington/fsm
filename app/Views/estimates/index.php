<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="background-color: #ffffff; min-height: 100vh; margin: 0; padding-top: 20px;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Work Order Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Estimates</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Estimates</h1>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createEstimateModal">
                <i class="bi bi-plus-circle"></i> Create Estimate
            </button>
        </div>
    </div>

    <?php if (empty($estimates)): ?>
    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <!-- Estimate Illustration -->
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4 shadow-sm" style="width: 200px; height: 140px;">
                        <i class="bi bi-calculator display-1 text-primary"></i>
                    </div>
                </div>
                
                <!-- Content -->
                <h2 class="h4 mb-3">Estimates</h2>
                <p class="text-muted mb-4">
                    Build detailed estimates with highly customisable templates and streamline estimate approvals through an online process. It helps you define a clear upfront price, and reduce time-consuming back-and-forth communication.
                </p>
                
                <!-- Create Estimate Button -->
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createEstimateModal">
                    <i class="bi bi-plus-circle me-2"></i>Create Estimate
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Estimates List -->
    <div class="row">
        <div class="col-12">
            <!-- Filter Bar -->
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchEstimates" placeholder="Search estimates...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="sent">Sent</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="companyFilter">
                                <option value="">All Companies</option>
                                <?php foreach ($companies as $company): ?>
                                <option value="<?= $company['id'] ?>"><?= esc($company['client_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5 text-end">
                            <span class="text-muted">
                                Total: <?= $total_estimates ?> | 
                                Draft: <?= $draft_estimates ?> | 
                                Sent: <?= $sent_estimates ?> | 
                                Accepted: <?= $accepted_estimates ?> | 
                                Rejected: <?= $rejected_estimates ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estimates Table -->
            <div class="card border-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Estimate #</th>
                                <th>Summary</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Expiry Date</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="estimatesTableBody">
                            <?= $this->include('estimates/_estimatesList', ['estimates' => $estimates]) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->include('estimates/_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/estimates.js') ?>"></script>
<?= $this->endSection() ?>