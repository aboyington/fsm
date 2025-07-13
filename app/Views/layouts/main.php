<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FSM - Field Service Management' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <?php if (session()->get('auth_token')): ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-tools" style="font-size: 1em;"></i> Udora FSM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto d-flex align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom h5 mb-0 dropdown-toggle" href="#" id="dashboardDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dashboard
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dashboardDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('dashboard') ?>">Overview</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('dashboard/request-management') ?>">Request Management</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('dashboard/service-appointment-management') ?>">Service Appointment Management</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('dashboard/technician-view') ?>">Technician View</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom h5 mb-0 dropdown-toggle" href="#" id="customersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Customers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="customersDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('customers/contacts') ?>">Contacts</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('customers/companies') ?>">Companies</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('customers/assets') ?>">Assets</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom h5 mb-0 dropdown-toggle" href="#" id="workOrderDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Work Order Management
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="workOrderDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('work-order-management/request') ?>">Request</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('work-order-management/estimates') ?>">Estimates</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('work-order-management/work-orders') ?>">Work Orders</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('work-order-management/service-appointments') ?>">Service Appointments</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('work-order-management/service-reports') ?>">Service Reports</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('work-order-management/scheduled-maintenances') ?>">Scheduled Maintenances</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom h5 mb-0" href="<?= base_url('dispatch') ?>">
                            Dispatch
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom h5 mb-0 dropdown-toggle" href="#" id="partsServiceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Parts And Service
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="partsServiceDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('parts-and-service/parts') ?>">Parts</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('parts-and-service/service') ?>">Service</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom h5 mb-0 dropdown-toggle" href="#" id="workforceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Workforce
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="workforceDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('workforce/users') ?>">Users</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('workforce/crew') ?>">Crew</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('workforce/equipments') ?>">Equipments</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('workforce/trips') ?>">Trips</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('workforce/auto-log') ?>">Auto Log</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('workforce/time-off') ?>">Time Off</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom h5 mb-0 dropdown-toggle" href="#" id="billingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Billing
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="billingDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('billing/invoices') ?>">Invoices</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('billing/payments') ?>">Payments</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav d-flex align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <span id="currentUserName">User</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('settings') ?>">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="<?= session()->get('auth_token') ? 'container-fluid mt-4' : '' ?>">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-center text-md-start">
                    <span class="text-muted">FSM by Anthony Boyington &copy; 2025 - Integrated with Canvass Global</span>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <span class="text-muted">v1.0.0</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery (required for AJAX and other functionalities) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/api-client.js') ?>"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
