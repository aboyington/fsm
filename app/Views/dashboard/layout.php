<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h6 class="h4">Welcome <span id="userName">Anthony Boyington</span></h6>
        </div>
        <div class="col-auto">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= ucwords(str_replace('-', ' ', $current_view)) ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item <?= $current_view === 'overview' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">Overview</a></li>
                    <li><a class="dropdown-item <?= $current_view === 'request-management' ? 'active' : '' ?>" href="<?= base_url('dashboard/request-management') ?>">Request Management</a></li>
                    <li><a class="dropdown-item <?= $current_view === 'service-appointment-management' ? 'active' : '' ?>" href="<?= base_url('dashboard/service-appointment-management') ?>">Service Appointment Management</a></li>
                    <li><a class="dropdown-item <?= $current_view === 'technician-view' ? 'active' : '' ?>" href="<?= base_url('dashboard/technician-view') ?>">Technician View</a></li>
                </ul>
            </div>
        </div>
    </div>

    <?= $this->renderSection('dashboard-content') ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Update user name
    const currentUser = sessionStorage.getItem('currentUser');
    if (currentUser) {
        const user = JSON.parse(currentUser);
        document.getElementById('userName').textContent = `${user.first_name} ${user.last_name}`;
    }
});
</script>
<?= $this->endSection() ?>
