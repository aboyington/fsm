<?= $this->extend('dashboard/layout') ?>

<?= $this->section('dashboard-content') ?>
<!-- Request Management Stats Cards -->
<div class="row mb-4">
    <?= $this->include('components/cards/total-requests-stat') ?>
    <?= $this->include('components/cards/converted-requests-stat') ?>
    <?= $this->include('components/cards/completed-requests-stat') ?>
    <?= $this->include('components/cards/cancelled-requests-stat') ?>
</div>

<!-- Dashboard Cards Grid -->
<div class="row">
    <?= $this->include('components/cards/new-requests-card') ?>
    <?= $this->include('components/cards/new-estimates-card') ?>
    <?= $this->include('components/cards/completed-requests-card') ?>
    <?= $this->include('components/cards/cancelled-requests-card') ?>
    <?= $this->include('components/cards/approved-estimates-card') ?>
    <?= $this->include('components/cards/cancelled-estimates-card') ?>
</div>
<?= $this->endSection() ?>