<!-- Cancelled Requests Card -->
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
            <?php if (!empty($request_data['cancelled_requests']) && count($request_data['cancelled_requests']) > 0): ?>
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
                            <?php foreach ($request_data['cancelled_requests'] as $request): ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('work-order-management/request/view/' . $request['id']) ?>" class="text-decoration-none">
                                        <strong><?= esc($request['request_number'] ?? 'REQ-' . $request['id']) ?></strong>
                                    </a>
                                </td>
                                <td><?= esc($request['request_name'] ?? 'N/A') ?></td>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td><?= date('M j, Y', strtotime($request['cancelled_date'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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