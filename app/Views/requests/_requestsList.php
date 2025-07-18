<?php foreach ($requests as $request): ?>
<tr>
    <td>
        <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div>
                <h6 class="mb-1"><?= esc($request['request_name']) ?></h6>
                <small class="text-muted">ID: <?= $request['id'] ?></small>
                <?php if (!empty($request['description'])): ?>
                <br><small class="text-muted"><?= esc(substr($request['description'], 0, 50)) ?><?= strlen($request['description']) > 50 ? '...' : '' ?></small>
                <?php endif; ?>
            </div>
        </div>
    </td>
    <td>
        <?php if (!empty($request['client_name'])): ?>
            <span class="badge bg-light text-dark"><?= esc($request['client_name']) ?></span>
        <?php else: ?>
            <span class="text-muted">-</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if (!empty($request['contact_first_name']) && !empty($request['contact_last_name'])): ?>
            <?= esc($request['contact_first_name'] . ' ' . $request['contact_last_name']) ?>
        <?php else: ?>
            <span class="text-muted">-</span>
        <?php endif; ?>
    </td>
    <td>
        <?php 
        $statusClass = '';
        $statusText = '';
        switch ($request['status']) {
            case 'pending':
                $statusClass = 'bg-warning';
                $statusText = 'Pending';
                break;
            case 'in_progress':
                $statusClass = 'bg-info';
                $statusText = 'In Progress';
                break;
            case 'completed':
                $statusClass = 'bg-success';
                $statusText = 'Completed';
                break;
            default:
                $statusClass = 'bg-secondary';
                $statusText = 'Unknown';
        }
        ?>
        <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
    </td>
    <td>
        <?php 
        $priorityClass = '';
        $priorityText = '';
        switch ($request['priority'] ?? 'medium') {
            case 'low':
                $priorityClass = 'bg-secondary';
                $priorityText = 'Low';
                break;
            case 'medium':
                $priorityClass = 'bg-warning';
                $priorityText = 'Medium';
                break;
            case 'high':
                $priorityClass = 'bg-danger';
                $priorityText = 'High';
                break;
            default:
                $priorityClass = 'bg-secondary';
                $priorityText = 'Medium';
        }
        ?>
        <span class="badge <?= $priorityClass ?>"><?= $priorityText ?></span>
    </td>
    <td>
        <small class="text-muted">
            <?= date('M j, Y', strtotime($request['created_at'])) ?>
        </small>
    </td>
    <td class="text-center">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editRequest(<?= $request['id'] ?>)" title="Edit">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRequest(<?= $request['id'] ?>)" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
</tr>
<?php endforeach; ?>
