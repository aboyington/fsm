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
                    <li class="breadcrumb-item active" aria-current="page">Requests</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Requests</h1>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createRequestModal">
                <i class="bi bi-plus-circle"></i> Create Request
            </button>
        </div>
    </div>

    <?php if (empty($requests)): ?>
    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <!-- Request Illustration -->
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4 shadow-sm" style="width: 200px; height: 140px;">
                        <i class="bi bi-clipboard-check display-1 text-primary"></i>
                    </div>
                </div>
                
                <!-- Content -->
                <h2 class="h4 mb-3">Service Requests</h2>
                <p class="text-muted mb-4">
                    Create and manage service requests from your customers. Track request status, priority, and assign to the appropriate team members.
                </p>
                
                <!-- Create Request Button -->
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createRequestModal">
                    <i class="bi bi-plus-circle me-2"></i>Create Request
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Requests List -->
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
                            <input type="text" class="form-control" id="searchRequests" placeholder="Search requests...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="priorityFilter">
                            <option value="">All Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
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
                    <div class="col-md-3 text-end">
                        <span class="text-muted">
                            Total: <?= $total_requests ?> | 
                            Pending: <?= $pending_requests ?> | 
                            In Progress: <?= $in_progress_requests ?> | 
                            On Hold: <?= $on_hold_requests ?> | 
                            Completed: <?= $completed_requests ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Request Number</th>
                            <th>Request Name</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Created</th>
                            <th>Created By</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="requestsTableBody">
                        <?= $this->include('requests/_requestsList', ['requests' => $requests]) ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->include('requests/_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Define baseUrl for JavaScript
const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('js/requests.js') ?>"></script>
<?= $this->endSection() ?>
