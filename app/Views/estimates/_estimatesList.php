<?php if (!empty($estimates)): ?>
    <?php foreach ($estimates as $estimate): ?>
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-calculator text-primary"></i>
                </div>
                <div>
                    <div class="fw-medium"><?= esc($estimate['estimate_number']) ?></div>
                    <small class="text-muted">by <?= esc($estimate['created_by_name'] ?? 'Unknown') ?></small>
                </div>
            </div>
        </td>
        <td>
            <div class="fw-medium"><?= esc($estimate['summary']) ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc($estimate['company_name'] ?? 'N/A') ?></div>
        </td>
        <td>
            <div class="fw-medium"><?= esc(($estimate['first_name'] ?? '') . ' ' . ($estimate['last_name'] ?? '')) ?></div>
            <?php if ($estimate['contact_email']): ?>
            <small class="text-muted"><?= esc($estimate['contact_email']) ?></small>
            <?php endif; ?>
        </td>
        <td>
            <?php 
            $statusClass = '';
            $statusText = ucfirst($estimate['status'] ?? 'draft');
            switch ($estimate['status']) {
                case 'draft':
                    $statusClass = 'bg-secondary';
                    break;
                case 'sent':
                    $statusClass = 'bg-info';
                    break;
                case 'accepted':
                    $statusClass = 'bg-success';
                    break;
                case 'rejected':
                    $statusClass = 'bg-danger';
                    break;
                default:
                    $statusClass = 'bg-secondary';
            }
            ?>
            <span class="badge <?= $statusClass ?> text-white"><?= $statusText ?></span>
        </td>
        <td>
            <div class="fw-medium">CA$ <?= number_format($estimate['grand_total'] ?? 0, 2) ?></div>
        </td>
        <td>
            <?php if ($estimate['expiry_date']): ?>
            <div class="fw-medium"><?= date('M d, Y', strtotime($estimate['expiry_date'])) ?></div>
            <?php else: ?>
            <span class="text-muted">No expiry</span>
            <?php endif; ?>
        </td>
        <td>
            <div class="fw-medium"><?= date('M d, Y', strtotime($estimate['created_at'])) ?></div>
            <small class="text-muted"><?= date('H:i', strtotime($estimate['created_at'])) ?></small>
        </td>
        <td class="text-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewEstimate(<?= $estimate['id'] ?>)" title="View">
                    <i class="bi bi-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editEstimate(<?= $estimate['id'] ?>)" title="Edit">
                    <i class="bi bi-pencil"></i>
                </button>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="duplicateEstimate(<?= $estimate['id'] ?>)"><i class="bi bi-copy me-2"></i>Duplicate</a></li>
                        <li><a class="dropdown-item" href="#" onclick="convertEstimate(<?= $estimate['id'] ?>)"><i class="bi bi-arrow-right me-2"></i>Convert to Work Order</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteEstimate(<?= $estimate['id'] ?>)"><i class="bi bi-trash me-2"></i>Delete</a></li>
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
                <i class="bi bi-calculator display-4 d-block mb-3"></i>
                <p>No estimates found</p>
            </div>
        </td>
    </tr>
<?php endif; ?>