<!-- New Estimates Card -->
<div class="col-md-6 mb-4">
    <div class="card border-0 h-100">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-calculator"></i> New Estimates
            </h6>
            <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        <div class="card-body">
            <?php if (!empty($request_data['new_estimates']) && count($request_data['new_estimates']) > 0): ?>
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
                            <?php foreach ($request_data['new_estimates'] as $estimate): ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('work-order-management/estimates/view/' . $estimate['id']) ?>" class="text-decoration-none">
                                        <strong>#<?= esc($estimate['id']) ?></strong>
                                    </a>
                                </td>
                                <td><?= esc($estimate['customer_name'] ?? 'N/A') ?></td>
                                <td>$<?= number_format($estimate['total_amount'] ?? 0, 2) ?></td>
                                <td><span class="badge bg-warning">New</span></td>
                                <td><?= date('M j, Y', strtotime($estimate['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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