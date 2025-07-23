<?= $this->extend('dashboard/layout') ?>

<?= $this->section('dashboard-content') ?>
<!-- Service Appointment Management Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Service Appointments</h6>
                        <h2 class="text-primary mb-0"><?= $appointment_stats['total_appointments'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $appointment_stats['total_appointments'] ?></small>
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
                        <h2 class="text-success mb-0"><?= $appointment_stats['in_progress_appointments'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $appointment_stats['in_progress_appointments'] ?></small>
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
                        <h2 class="text-info mb-0"><?= $appointment_stats['completed_appointments'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $appointment_stats['completed_appointments'] ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted mb-1">Cancelled/Terminated Service Ap...</h6>
                        <h2 class="text-warning mb-0"><?= $appointment_stats['cancelled_appointments'] ?></h2>
                        <small class="text-success"><span class="badge bg-success">0%</span></small>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-x-circle fs-1"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Last Month <?= $appointment_stats['cancelled_appointments'] ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Cards Grid -->
<div class="row">
    <!-- New Work Orders -->
    <div class="col-md-12 mb-4">
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
                <?php if (!empty($appointment_stats['new_work_orders']) && count($appointment_stats['new_work_orders']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order Number</th>
                                    <th>Summary</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointment_stats['new_work_orders'] as $workOrder): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/work-orders/view/' . $workOrder['id']) ?>" class="text-decoration-none">
                                            <strong><?= esc($workOrder['work_order_number'] ?? 'WRK-' . $workOrder['id']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= esc($workOrder['summary'] ?? 'N/A') ?></td>
                                    <td><span class="badge bg-warning">New</span></td>
                                    <td>
                                        <?php 
                                        $priorityClass = match($workOrder['priority'] ?? 'low') {
                                            'high' => 'danger',
                                            'medium' => 'warning', 
                                            'low' => 'success',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $priorityClass ?>"><?= ucfirst($workOrder['priority'] ?? 'low') ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($appointment_stats['new_work_orders']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-data display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Scheduled Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-calendar-week"></i> Scheduled Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($appointment_stats['scheduled_appointments']) && count($appointment_stats['scheduled_appointments']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Duration</th>
                                    <th>Technician</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointment_stats['scheduled_appointments'] as $appointment): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/work-orders/view/' . $appointment['work_order_id']) ?>" class="text-decoration-none">
                                            <strong>WRK-<?= str_pad($appointment['work_order_id'], 3, '0', STR_PAD_LEFT) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></td>
                                    <td><?= $appointment['duration'] ?> min</td>
                                    <td>Tech <?= $appointment['technician_id'] ?? 'TBD' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($appointment_stats['scheduled_appointments']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-week display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Dispatched Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-truck"></i> Dispatched Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($appointment_stats['scheduled_appointments']) && count($appointment_stats['scheduled_appointments']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointment_stats['scheduled_appointments'] as $appointment): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/work-orders/view/' . $appointment['work_order_id']) ?>" class="text-decoration-none">
                                            <strong>WRK-<?= str_pad($appointment['work_order_id'], 3, '0', STR_PAD_LEFT) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></td>
                                    <td><?= $appointment['duration'] ?> min</td>
                                    <td><span class="badge bg-info">Dispatched</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($appointment_stats['scheduled_appointments']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-truck display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- In Progress Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-clock-history"></i> In Progress Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($appointment_stats['in_progress_appointments_list']) && count($appointment_stats['in_progress_appointments_list']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Technician</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointment_stats['in_progress_appointments_list'] as $appointment): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/work-orders/view/' . $appointment['work_order_id']) ?>" class="text-decoration-none">
                                            <strong>WRK-<?= str_pad($appointment['work_order_id'], 3, '0', STR_PAD_LEFT) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= date('g:i A', strtotime($appointment['appointment_time'])) ?></td>
                                    <td>Tech <?= $appointment['technician_id'] ?? 'TBD' ?></td>
                                    <td><span class="badge bg-primary">In Progress</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($appointment_stats['in_progress_appointments_list']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Completed Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-check-circle"></i> Completed Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($appointment_stats['completed_appointments_list']) && count($appointment_stats['completed_appointments_list']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                    <th>Technician</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointment_stats['completed_appointments_list'] as $appointment): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/work-orders/view/' . $appointment['work_order_id']) ?>" class="text-decoration-none">
                                            <strong>WRK-<?= str_pad($appointment['work_order_id'], 3, '0', STR_PAD_LEFT) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= $appointment['duration'] ?> min</td>
                                    <td>Tech <?= $appointment['technician_id'] ?? 'TBD' ?></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($appointment_stats['completed_appointments_list']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cancelled Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-x-circle"></i> Cancelled Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($appointment_stats['cancelled_appointments_list']) && count($appointment_stats['cancelled_appointments_list']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Technician</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointment_stats['cancelled_appointments_list'] as $appointment): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/work-orders/view/' . $appointment['work_order_id']) ?>" class="text-decoration-none">
                                            <strong>WRK-<?= str_pad($appointment['work_order_id'], 3, '0', STR_PAD_LEFT) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= esc(substr($appointment['notes'] ?? 'No reason provided', 0, 30)) ?><?= strlen($appointment['notes'] ?? '') > 30 ? '...' : '' ?></td>
                                    <td>Tech <?= $appointment['technician_id'] ?? 'TBD' ?></td>
                                    <td><span class="badge bg-danger">Cancelled</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($appointment_stats['cancelled_appointments_list']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-x-circle display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Terminated Service Appointments -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-stop-circle"></i> Terminated Service Appointments
                </h6>
                <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($appointment_stats['terminated_appointments']) && count($appointment_stats['terminated_appointments']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Technician</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointment_stats['terminated_appointments'] as $appointment): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/work-orders/view/' . $appointment['work_order_id']) ?>" class="text-decoration-none">
                                            <strong>WRK-<?= str_pad($appointment['work_order_id'], 3, '0', STR_PAD_LEFT) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= esc(substr($appointment['notes'] ?? 'No reason provided', 0, 30)) ?><?= strlen($appointment['notes'] ?? '') > 30 ? '...' : '' ?></td>
                                    <td>Tech <?= $appointment['technician_id'] ?? 'TBD' ?></td>
                                    <td><span class="badge bg-secondary">Terminated</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($appointment_stats['terminated_appointments']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-stop-circle display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
