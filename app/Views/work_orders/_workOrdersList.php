<?php if (!empty($workOrders)): ?>
    <?php foreach ($workOrders as $workOrder): ?>
    <tr>
        <td>
            <div class="fw-medium"><?= esc($workOrder['work_order_number']) ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc($workOrder['summary']) ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc($workOrder['company_name'] ?? 'N/A') ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc(($workOrder['first_name'] ?? '') . ' ' . ($workOrder['last_name'] ?? '')) ?></div>
        </td>
        <td>
            <?php 
            $statusClass = '';
            $statusText = ucfirst(str_replace('_', ' ', $workOrder['status'] ?? 'new'));
            switch ($workOrder['status']) {
                case 'new':
                    $statusClass = 'bg-primary';
                    $statusText = 'New';
                    break;
                case 'pending':
                    $statusClass = 'bg-warning';
                    $statusText = 'Pending';
                    break;
                case 'in_progress':
                    $statusClass = 'bg-info';
                    $statusText = 'In Progress';
                    break;
                case 'cannot_complete':
                    $statusClass = 'bg-dark';
                    $statusText = 'Cannot Complete';
                    break;
                case 'completed':
                    $statusClass = 'bg-success';
                    $statusText = 'Completed';
                    break;
                case 'closed':
                    $statusClass = 'bg-secondary';
                    $statusText = 'Closed';
                    break;
                case 'cancelled':
                    $statusClass = 'bg-danger';
                    $statusText = 'Cancelled';
                    break;
                case 'scheduled_appointment':
                    $statusClass = 'bg-light text-dark';
                    $statusText = 'Scheduled Appointment';
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
            <div class="fw-medium"><?= date('M d, Y', strtotime($workOrder['created_at'])) ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc($workOrder['created_by_name'] ?? 'Unknown') ?></div>
        </td>
        <td class="text-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-info" onclick="viewWorkOrder(<?= $workOrder['id'] ?>)" title="View">
                    <i class="bi bi-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" onclick="editWorkOrder(<?= $workOrder['id'] ?>)" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteWorkOrder(<?= $workOrder['id'] ?>)" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
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