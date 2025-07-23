<?= $this->extend('dashboard/layout') ?>

<?= $this->section('dashboard-content') ?>
<!-- Debug: Request Stats Data -->
<div class="alert alert-info mb-4">
    <h6>Debug - Request Stats Data:</h6>
    <pre><?php print_r($request_stats); ?></pre>
</div>

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

<!-- Test New Requests Card -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-inbox"></i> New Requests (Debug)
                </h6>
            </div>
            <div class="card-body">
                <p><strong>New Requests Count:</strong> <?= count($request_stats['new_requests'] ?? []) ?></p>
                <?php if (!empty($request_stats['new_requests'])): ?>
                    <ul>
                        <?php foreach ($request_stats['new_requests'] as $request): ?>
                            <li><?= esc($request['request_name']) ?> (<?= esc($request['status']) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No new requests found</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>