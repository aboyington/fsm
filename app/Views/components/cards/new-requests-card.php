<!-- New Requests Card -->
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
            <?php if (!empty($request_data['new_requests']) && count($request_data['new_requests']) > 0): ?>
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
                            <?php foreach ($request_data['new_requests'] as $request): ?>
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
                    <small class="text-muted">Total records: <?= count($request_data['new_requests']) ?></small>
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