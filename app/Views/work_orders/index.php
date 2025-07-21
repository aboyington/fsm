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
                    <li class="breadcrumb-item active" aria-current="page">Work Orders</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Work Orders</h1>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createWorkOrderModal">
                <i class="bi bi-plus-circle"></i> Create Work Order
            </button>
        </div>
    </div>

    <?php if (empty($workOrders)): ?>
    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <!-- Work Order Illustration -->
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4 shadow-sm" style="width: 200px; height: 140px;">
                        <i class="bi bi-clipboard-check display-1 text-primary"></i>
                    </div>
                </div>
                
                <!-- Content -->
                <h2 class="h4 mb-3">Work Orders</h2>
                <p class="text-muted mb-4">
                    The Work Order Module centralizes all work-related details, allowing for easy work assignment, progress tracking, and management of invoicing and payments. It simplifies the creation of both one-time and recurring work orders for effective operations.
                </p>
                
                <!-- Create Work Order Button -->
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createWorkOrderModal">
                    <i class="bi bi-plus-circle me-2"></i>Create Work Order
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Work Orders List -->
    <div class="row">
        <div class="col-12">
            <!-- Filter Bar -->
            <div class="card border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchWorkOrders" placeholder="Search work orders...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="new">New</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="cannot_complete">Cannot Complete</option>
                                <option value="completed">Completed</option>
                                <option value="closed">Closed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="scheduled_appointment">Scheduled Appointment</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="priorityFilter">
                                <option value="">All Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="text-muted">
                                Total: <?= $total_work_orders ?> | 
                                Pending: <?= $pending_work_orders ?> | 
                                In Progress: <?= $in_progress_work_orders ?> | 
                                Completed: <?= $completed_work_orders ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Orders Table -->
            <div class="card border-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Work Order #</th>
                                <th>Work Order Title</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created</th>
                                <th>Created By</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="workOrdersTableBody">
                            <?= $this->include('work_orders/_workOrdersList', ['workOrders' => $workOrders]) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->include('work_orders/_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Define baseUrl for JavaScript
const baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url('js/work_orders.js') ?>"></script>
<?= $this->endSection() ?>
