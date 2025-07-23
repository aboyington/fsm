<!-- Total Converted Requests Stat Card -->
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