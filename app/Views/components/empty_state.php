<?php
/**
 * Empty State Component
 * 
 * A reusable component for displaying empty states with consistent styling
 * 
 * @param string $icon - Bootstrap icon class (e.g., 'bi-calculator')
 * @param string $title - Main title for empty state
 * @param string $description - Description text
 * @param array $actions - Array of action buttons with 'text', 'class', 'onclick' keys
 * @param string $size - Size of empty state: 'small', 'medium', 'large' (default: 'medium')
 */

$icon = $icon ?? 'bi-info-circle';
$title = $title ?? 'No Records Found';
$description = $description ?? 'No records available at this time.';
$actions = $actions ?? [];
$size = $size ?? 'medium';

// Set size-based classes
$sizeClasses = [
    'small' => ['py-3', 'display-6', 'mb-2'],
    'medium' => ['py-4', 'display-4', 'mb-3'], 
    'large' => ['py-5', 'display-3', 'mb-4']
];

$paddingClass = $sizeClasses[$size][0] ?? 'py-4';
$iconClass = $sizeClasses[$size][1] ?? 'display-4';
$marginClass = $sizeClasses[$size][2] ?? 'mb-3';
?>

<div class="card border-0 bg-light">
    <div class="card-body text-center <?= $paddingClass ?>">
        <i class="<?= $icon ?> <?= $iconClass ?> text-muted <?= $marginClass ?>"></i>
        <h6 class="text-muted mb-2"><?= esc($title) ?></h6>
        <?php if ($description): ?>
        <p class="text-muted small mb-<?= count($actions) > 0 ? '3' : '0' ?>"><?= esc($description) ?></p>
        <?php endif; ?>
        
        <?php if (!empty($actions)): ?>
        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
            <?php foreach ($actions as $action): ?>
            <button type="button" class="<?= $action['class'] ?? 'btn btn-sm btn-primary' ?>" 
                    <?= isset($action['onclick']) ? 'onclick="' . esc($action['onclick']) . '"' : '' ?>>
                <?= $action['text'] ?? 'Action' ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>