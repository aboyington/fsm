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
                <h6 class="mb-0 text-body">
                    <i class="bi bi-inbox"></i> New Requests
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($overview_stats['new_requests']) && count($overview_stats['new_requests']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Request Number</th>
                                    <th>Request Name</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($overview_stats['new_requests'] as $request): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/request/view/' . $request['id']) ?>" class="text-decoration-none">
                                            <strong><?= esc($request['request_number'] ?? 'REQ-' . $request['id']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= esc($request['request_name'] ?? 'N/A') ?></td>
                                    <td><span class="badge bg-warning">New</span></td>
                                    <td>
                                        <?php 
                                        $priorityClass = match($request['priority'] ?? 'low') {
                                            'high' => 'danger',
                                            'medium' => 'warning', 
                                            'low' => 'success',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $priorityClass ?>"><?= ucfirst($request['priority'] ?? 'low') ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($overview_stats['new_requests']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- New Work Orders -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-clipboard-data"></i> New Work Orders
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($overview_stats['new_work_orders']) && count($overview_stats['new_work_orders']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Work Order Number</th>
                                    <th>Work Order Name</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($overview_stats['new_work_orders'] as $workOrder): ?>
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
                        <small class="text-muted">Total records: <?= count($overview_stats['new_work_orders']) ?></small>
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

    <!-- Approved Estimates -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-check-circle"></i> Approved Estimates
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($overview_stats['approved_estimates']) && count($overview_stats['approved_estimates']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Estimate #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($overview_stats['approved_estimates'] as $estimate): ?>
                                <tr>
                                    <td><strong>#<?= esc($estimate['id']) ?></strong></td>
                                    <td><?= esc($estimate['customer_name'] ?? 'N/A') ?></td>
                                    <td>$<?= number_format($estimate['total_amount'] ?? 0, 2) ?></td>
                                    <td><span class="badge bg-success">Accepted</span></td>
                                    <td><?= date('M j', strtotime($estimate['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($overview_stats['approved_estimates']) ?></small>
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

    <!-- Estimates Waiting for Approval -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-hourglass-split"></i> Estimates Waiting for Approval
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($overview_stats['estimates_waiting']) && count($overview_stats['estimates_waiting']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Estimate #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date Sent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($overview_stats['estimates_waiting'] as $estimate): ?>
                                <tr>
                                    <td><strong>#<?= esc($estimate['id']) ?></strong></td>
                                    <td><?= esc($estimate['customer_name'] ?? 'N/A') ?></td>
                                    <td>$<?= number_format($estimate['total_amount'] ?? 0, 2) ?></td>
                                    <td><span class="badge bg-warning">Sent</span></td>
                                    <td><?= date('M j', strtotime($estimate['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($overview_stats['estimates_waiting']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-hourglass-split display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
