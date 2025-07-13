<?= $this->extend('dashboard/layout') ?>

<?= $this->section('dashboard-content') ?>
<!-- Request Management Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Requests</h6>
                        <h2 class="text-primary mb-0"><?= $request_stats['total_requests'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-inbox fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $request_stats['total_requests'] ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Converted Requests</h6>
                        <h2 class="text-success mb-0"><?= $request_stats['converted_requests'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $request_stats['converted_requests'] ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Completed Requests</h6>
                        <h2 class="text-info mb-0"><?= $request_stats['completed_requests'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $request_stats['completed_requests'] ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Cancelled/Terminated Requests</h6>
                        <h2 class="text-warning mb-0"><?= $request_stats['cancelled_requests'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-calendar-check fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $request_stats['cancelled_requests'] ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Cards Grid -->
<div class="row">
    <!-- New Requests -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-inbox"></i> New Requests
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- New Estimates -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-calculator"></i> New Estimates
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-calculator display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Requests -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-check-circle"></i> Completed Requests
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-check-circle display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancelled Requests -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-x-circle"></i> Cancelled Requests
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-x-circle display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved Estimates -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-check-circle"></i> Approved Estimates
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-check-circle display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancelled Estimates -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-x-circle"></i> Cancelled Estimates
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-x-circle display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
