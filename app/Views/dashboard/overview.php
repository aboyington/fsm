<?= $this->extend('dashboard/layout') ?>

<?= $this->section('dashboard-content') ?>
<!-- Overview Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Work Orders</h6>
                        <h2 class="text-primary mb-0"><?= $overview_stats['total_work_orders'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-clipboard-data fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $overview_stats['total_work_orders'] ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Completed Work Orders</h6>
                        <h2 class="text-success mb-0"><?= $overview_stats['completed_work_orders'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $overview_stats['completed_work_orders'] ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Service Appointments</h6>
                        <h2 class="text-info mb-0"><?= $overview_stats['total_service_appointments'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $overview_stats['total_service_appointments'] ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Completed Service Appointments</h6>
                        <h2 class="text-warning mb-0"><?= $overview_stats['completed_service_appointments'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-calendar-check fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $overview_stats['completed_service_appointments'] ?></small>
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

    <!-- New Work Orders -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-clipboard-data"></i> New Work Orders
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-data display-1 text-muted"></i>
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

    <!-- Estimates Waiting for Approval -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-hourglass-split"></i> Estimates Waiting for Approval
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-hourglass-split display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
