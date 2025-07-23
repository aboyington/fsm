<!-- Total Requests Stat Card -->
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