<?= $this->extend('dashboard/layout') ?>

<?= $this->section('dashboard-content') ?>
<!-- Technician View Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Upcoming Service Appointments</h6>
                        <h2 class="text-primary mb-0"><?= $technician_stats['upcoming_appointments'] ?></h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last 6 Months</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">In Progress Service Appointments</h6>
                        <h2 class="text-success mb-0"><?= $technician_stats['in_progress_appointments'] ?></h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">This Month</small>
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
                        <h2 class="text-info mb-0"><?= $technician_stats['completed_appointments'] ?></h2>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">This Month</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Trips</h6>
                        <h2 class="text-warning mb-0"><?= $technician_stats['total_trips'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-truck fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $technician_stats['total_trips'] ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Cards Grid -->
<div class="row">
    <!-- My Dispatched Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-warning">
                    <i class="bi bi-truck"></i> My Dispatched Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-truck display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My In Progress Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-warning">
                    <i class="bi bi-clock-history"></i> My In Progress Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="bi bi-clock-history display-1 text-muted"></i>
                    <p class="text-muted mt-3">No Records Found</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My Completed Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-warning">
                    <i class="bi bi-check-circle"></i> My Completed Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
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

    <!-- My Cancelled Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-warning">
                    <i class="bi bi-x-circle"></i> My Cancelled Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
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
