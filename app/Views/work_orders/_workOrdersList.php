<?php if (!empty($workOrders)): ?>
    <?php foreach ($workOrders as $workOrder): ?>
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-clipboard-check text-primary"></i>
                </div>
                <div>
                    <div class="fw-medium"><?= esc($workOrder['work_order_number']) ?></div>
                    <small class="text-muted">by <?= esc($workOrder['created_by_name'] ?? 'Unknown') ?></small>
                </div>
            </div>
        </td>
        <td>
            <div class="fw-medium"><?= esc($workOrder['summary']) ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc($workOrder['company_name'] ?? 'N/A') ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc(($workOrder['first_name'] ?? '') . ' ' . ($workOrder['last_name'] ?? '')) ?></div>
            <?php if ($workOrder['contact_email']): ?>
            <small class="text-muted"><?= esc($workOrder['contact_email']) ?></small>
            <?php endif; ?>
        </td>
        <td>
            <?php 
            $statusClass = '';
            $statusText = ucfirst($workOrder['status'] ?? 'pending');
            switch ($workOrder['status']) {
                case 'pending':
                    $statusClass = 'bg-warning';
                    break;
                case 'in_progress':
                    $statusClass = 'bg-info';
                    break;
                case 'completed':
                    $statusClass = 'bg-success';
                    break;
                case 'cancelled':
                    $statusClass = 'bg-danger';
                    break;
                default:
                    $statusClass = 'bg-secondary';
            }
            ?>
            <span class="badge <?= $statusClass ?> text-white"><?= str_replace('_', ' ', $statusText) ?></span>
        </td>
        <td>
            <?php 
            $priorityClass = '';
            $priorityText = ucfirst($workOrder['priority'] ?? 'medium');
            switch ($workOrder['priority']) {
                case 'low':
                    $priorityClass = 'bg-secondary';
                    break;
                case 'medium':
                    $priorityClass = 'bg-warning';
                    break;
                case 'high':
                    $priorityClass = 'bg-danger';
                    break;
                default:
                    $priorityClass = 'bg-secondary';
            }
            ?>
            <span class="badge <?= $priorityClass ?> text-white"><?= $priorityText ?></span>
        </td>
        <td>
            <?php if ($workOrder['due_date']): ?>
            <div class="fw-medium"><?= date('M d, Y', strtotime($workOrder['due_date'])) ?></div>
            <?php else: ?>
            <span class="text-muted">No due date</span>
            <?php endif; ?>
        </td>
        <td>
            <div class="fw-medium"><?= date('M d, Y', strtotime($workOrder['created_at'])) ?></div>
            <small class="text-muted"><?= date('H:i', strtotime($workOrder['created_at'])) ?></small>
        </td>
        <td class="text-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewWorkOrder(<?= $workOrder['id'] ?>)" title="View">
                    <i class="bi bi-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editWorkOrder(<?= $workOrder['id'] ?>)" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="duplicateWorkOrder(<?= $workOrder['id'] ?>)"><i class="bi bi-copy me-2"></i>Duplicate</a></li>
                        <li><a class="dropdown-item" href="#" onclick="convertToInvoice(<?= $workOrder['id'] ?>)"><i class="bi bi-receipt me-2"></i>Convert to Invoice</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteWorkOrder(<?= $workOrder['id'] ?>)"><i class="bi bi-trash me-2"></i>Delete</a></li>
                    </ul>
                </div>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="9" class="text-center py-5">
            <div class="text-muted">
                <i class="bi bi-clipboard-check display-4 d-block mb-3"></i>
                <p>No work orders found</p>
            </div>
        </td>
    </tr>
<?php endif; ?>