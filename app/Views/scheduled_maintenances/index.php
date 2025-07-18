<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="background-color: #ffffff; min-height: 100vh; margin: 0; padding-top: 20px;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Work Order Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Scheduled Maintenances</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Scheduled Maintenances</h1>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createScheduledMaintenanceModal">
                <i class="bi bi-plus-circle"></i> Create Scheduled Maintenance
            </button>
        </div>
    </div>

    <!-- Empty State (when no maintenances exist) -->
    <?php if (empty($maintenances)): ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-calendar-check display-1 text-success"></i>
            </div>
            <h2 class="h4 mb-3">No Scheduled Maintenances</h2>
            <p class="text-muted mb-4">
                Automate your recurring service tasks by scheduling work orders and appointments to proactively address potential issues before they escalate into major problems or downtime. Maintain clear visibility of upcoming appointments with the flexibility to pause maintenance plans when necessary.
            </p>
            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createScheduledMaintenanceModal">
                <i class="bi bi-plus-circle me-2"></i>Create Scheduled Maintenance
            </button>
        </div>
    <?php else: ?>
        <!-- Table view for existing maintenances -->
        <div class="table-responsive">
            <table class="table table-hover" id="maintenancesTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Schedule Type</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($maintenances as $maintenance): ?>
                        <tr>
                            <td><?= $maintenance['id'] ?></td>
                            <td><?= esc($maintenance['name']) ?></td>
                            <td><?= ucfirst($maintenance['schedule_type']) ?></td>
                            <td><?= date('M d, Y', strtotime($maintenance['start_date'])) ?></td>
                            <td>
                                <?php
                                $statusClass = '';
                                switch ($maintenance['status']) {
                                    case 'active':
                                        $statusClass = 'badge-success';
                                        break;
                                    case 'inactive':
                                        $statusClass = 'badge-secondary';
                                        break;
                                    case 'draft':
                                        $statusClass = 'badge-warning';
                                        break;
                                    default:
                                        $statusClass = 'badge-secondary';
                                }
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst($maintenance['status']) ?></span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewMaintenance(<?= $maintenance['id'] ?>)" title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="editMaintenance(<?= $maintenance['id'] ?>)" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteMaintenance(<?= $maintenance['id'] ?>)" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('scheduled_maintenances/_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/scheduled_maintenances.js') ?>"></script>
<?= $this->endSection() ?>
