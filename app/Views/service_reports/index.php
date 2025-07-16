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
                    <li class="breadcrumb-item active" aria-current="page">Service Reports</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Service Reports</h1>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createServiceReportModal">
                <i class="bi bi-plus-circle"></i> Create Service Report
            </button>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Service Reports Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Reports Management</h6>
        </div>
        <div class="card-body">
            <!-- Search and Filter Controls -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="searchReports">Search Reports:</label>
                        <input type="text" class="form-control" id="searchReports" placeholder="Search by work order, technician, or report details...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="statusFilter">Filter by Status:</label>
                        <select class="form-control" id="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="completed">Completed</option>
                            <option value="submitted">Submitted</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="dateFilter">Filter by Date:</label>
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                </div>
            </div>

            <!-- Service Reports Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="reportsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Work Order</th>
                            <th>Service Appointment</th>
                            <th>Technician</th>
                            <th>Report Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reports)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No service reports found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= $report['id'] ?></td>
                                    <td><?= $report['work_order_title'] ?? 'N/A' ?></td>
                                    <td><?= $report['service_appointment_id'] ?? 'N/A' ?></td>
                                    <td><?= $report['technician_name'] ?? 'Unassigned' ?></td>
                                    <td><?= date('M d, Y', strtotime($report['report_date'])) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        switch ($report['status']) {
                                            case 'draft':
                                                $statusClass = 'badge-secondary';
                                                break;
                                            case 'completed':
                                                $statusClass = 'badge-primary';
                                                break;
                                            case 'submitted':
                                                $statusClass = 'badge-warning';
                                                break;
                                            case 'approved':
                                                $statusClass = 'badge-success';
                                                break;
                                            default:
                                                $statusClass = 'badge-secondary';
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst($report['status']) ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewReport(<?= $report['id'] ?>)" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editReport(<?= $report['id'] ?>)" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($report['status'] === 'completed'): ?>
                                                <button class="btn btn-sm btn-outline-warning" onclick="updateReportStatus(<?= $report['id'] ?>, 'submitted')" title="Submit">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($report['status'] === 'submitted'): ?>
                                                <button class="btn btn-sm btn-outline-success" onclick="updateReportStatus(<?= $report['id'] ?>, 'approved')" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteReport(<?= $report['id'] ?>)" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Empty State (when no reports exist) -->
    <?php if (empty($reports)): ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 bg-light">
                    <div class="card-body text-center py-5">
                        <!-- Service Reports Illustration -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4 shadow-sm" style="width: 200px; height: 140px;">
                                <i class="bi bi-file-earmark-text display-1 text-primary"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <h2 class="h4 mb-3">No Service Reports Yet</h2>
                        <p class="text-muted mb-4">
                            The Service Report module enables field technicians to capture critical work progress and job completion information directly from the field. With customizable templates, businesses can provide their customers with transparent, detailed, and timely updates about the service performed, improving customer satisfaction and engagement.
                        </p>

                        <!-- Create Service Report Button -->
                        <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createServiceReportModal">
                            <i class="bi bi-plus-circle me-2"></i>Create Service Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->include('service_reports/_modal') ?>

<script src="<?= base_url('js/service_reports.js') ?>"></script>
<?= $this->endSection() ?>
