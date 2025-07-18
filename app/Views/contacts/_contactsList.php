<?php foreach ($contacts as $contact): ?>
<tr>
    <td>
        <div class="d-flex align-items-center">
            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                <i class="bi bi-person"></i>
            </div>
            <div>
                <h6 class="mb-1"><?= esc($contact['first_name'] . ' ' . $contact['last_name']) ?></h6>
                <small class="text-muted">ID: <?= $contact['id'] ?></small>
            </div>
        </div>
    </td>
    <td>
        <?php if (!empty($contact['company_id'])): ?>
            <span class="text-muted">Company Account</span>
        <?php else: ?>
            <?= esc($contact['account_number'] ?? '-') ?>
        <?php endif; ?>
    </td>
    <td>
        <?php if (!empty($contact['company_name'])): ?>
            <span class="badge bg-light text-dark"><?= esc($contact['company_name']) ?></span>
        <?php else: ?>
            <span class="text-muted">-</span>
        <?php endif; ?>
    </td>
    <td><?= esc($contact['job_title'] ?? '-') ?></td>
    <td>
        <?php if (!empty($contact['email'])): ?>
            <a href="mailto:<?= esc($contact['email']) ?>" class="text-decoration-none">
                <?= esc($contact['email']) ?>
            </a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td>
        <?php if (!empty($contact['phone'])): ?>
            <a href="tel:<?= esc($contact['phone']) ?>" class="text-decoration-none">
                <?= esc($contact['phone']) ?>
            </a>
        <?php elseif (!empty($contact['mobile'])): ?>
            <a href="tel:<?= esc($contact['mobile']) ?>" class="text-decoration-none">
                <?= esc($contact['mobile']) ?> <small class="text-muted">(mobile)</small>
            </a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td>
        <?php if ($contact['status'] === 'active'): ?>
            <span class="badge bg-success">Active</span>
        <?php else: ?>
            <span class="badge bg-secondary">Inactive</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if ($contact['is_primary']): ?>
            <span class="badge bg-warning text-dark">
                <i class="bi bi-star-fill"></i> Primary
            </span>
        <?php else: ?>
            <span class="text-muted">-</span>
        <?php endif; ?>
    </td>
    <td>
        <small class="text-muted">
            <?= date('M j, Y', strtotime($contact['created_at'])) ?>
        </small>
    </td>
    <td class="text-center">
        <div class="btn-group" role="group">
            <?php if (!$contact['is_primary'] && !empty($contact['company_id'])): ?>
            <button type="button" class="btn btn-sm btn-outline-warning" onclick="setPrimaryContact(<?= $contact['id'] ?>)" title="Set as Primary">
                <i class="bi bi-star"></i>
            </button>
            <?php endif; ?>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editContact(<?= $contact['id'] ?>)" title="Edit">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteContact(<?= $contact['id'] ?>)" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
</tr>
<?php endforeach; ?>
