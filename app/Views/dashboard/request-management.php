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
                        <i class="bi bi-x-circle fs-1"></i>
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
                <h6 class="mb-0 text-body">
                    <i class="bi bi-inbox"></i> New Requests
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($request_stats['new_requests']) && count($request_stats['new_requests']) > 0): ?>
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
                                <?php foreach ($request_stats['new_requests'] as $request): ?>
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
                        <small class="text-muted">Total records: <?= count($request_stats['new_requests']) ?></small>
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

    <!-- New Estimates -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-calculator"></i> New Estimates
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($request_stats['new_estimates']) && count($request_stats['new_estimates']) > 0): ?>
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
                                <?php foreach ($request_stats['new_estimates'] as $estimate): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/estimates/view/' . $estimate['id']) ?>" class="text-decoration-none">
                                            <strong>#<?= esc($estimate['id']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= esc($estimate['email'] ?? 'N/A') ?></td>
                                    <td>$<?= number_format($estimate['grand_total'] ?? 0, 2) ?></td>
                                    <td><span class="badge bg-warning">Draft</span></td>
                                    <td><?= date('M j, Y', strtotime($estimate['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($request_stats['new_estimates']) ?></small>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calculator display-1 text-muted"></i>
                        <p class="text-muted mt-3">No Records Found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Completed Requests -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-check-circle"></i> Completed Requests
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($request_stats['completed_requests_list']) && count($request_stats['completed_requests_list']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Request Number</th>
                                    <th>Request Name</th>
                                    <th>Status</th>
                                    <th>Completed Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($request_stats['completed_requests_list'] as $request): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/request/view/' . $request['id']) ?>" class="text-decoration-none">
                                            <strong><?= esc($request['request_number'] ?? 'REQ-' . $request['id']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= esc($request['request_name'] ?? 'N/A') ?></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td><?= date('M j, Y', strtotime($request['updated_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($request_stats['completed_requests_list']) ?></small>
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

    <!-- Cancelled Requests -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-x-circle"></i> Cancelled Requests
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($request_stats['cancelled_requests_list']) && count($request_stats['cancelled_requests_list']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Request Number</th>
                                    <th>Request Name</th>
                                    <th>Status</th>
                                    <th>Cancelled Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($request_stats['cancelled_requests_list'] as $request): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/request/view/' . $request['id']) ?>" class="text-decoration-none">
                                            <strong><?= esc($request['request_number'] ?? 'REQ-' . $request['id']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= esc($request['request_name'] ?? 'N/A') ?></td>
                                    <td><span class="badge bg-danger">Cancelled</span></td>
                                    <td><?= date('M j, Y', strtotime($request['updated_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($request_stats['cancelled_requests_list']) ?></small>
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
                <?php if (!empty($request_stats['approved_estimates']) && count($request_stats['approved_estimates']) > 0): ?>
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
                                <?php foreach ($request_stats['approved_estimates'] as $estimate): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/estimates/view/' . $estimate['id']) ?>" class="text-decoration-none">
                                            <strong>#<?= esc($estimate['id']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= esc($estimate['email'] ?? 'N/A') ?></td>
                                    <td>$<?= number_format($estimate['grand_total'] ?? 0, 2) ?></td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                    <td><?= date('M j, Y', strtotime($estimate['updated_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($request_stats['approved_estimates']) ?></small>
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

    <!-- Cancelled Estimates -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-body">
                    <i class="bi bi-x-circle"></i> Cancelled Estimates
                </h6>
                <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($request_stats['cancelled_estimates']) && count($request_stats['cancelled_estimates']) > 0): ?>
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
                                <?php foreach ($request_stats['cancelled_estimates'] as $estimate): ?>
                                <tr>
                                    <td>
                                        <a href="<?= base_url('work-order-management/estimates/view/' . $estimate['id']) ?>" class="text-decoration-none">
                                            <strong>#<?= esc($estimate['id']) ?></strong>
                                        </a>
                                    </td>
                                    <td><?= esc($estimate['email'] ?? 'N/A') ?></td>
                                    <td>$<?= number_format($estimate['grand_total'] ?? 0, 2) ?></td>
                                    <td><span class="badge bg-danger">Rejected</span></td>
                                    <td><?= date('M j, Y', strtotime($estimate['updated_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Total records: <?= count($request_stats['cancelled_estimates']) ?></small>
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
</div>
<?= $this->endSection() ?>
