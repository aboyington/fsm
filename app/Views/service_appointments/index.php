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
                    <li class="breadcrumb-item active" aria-current="page">Service Appointments</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Service Appointments</h1>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createServiceAppointmentModal">
                <i class="bi bi-plus-circle"></i> Create Service Appointment
            </button>
        </div>
    </div>

    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center py-5">
                <!-- Service Appointment Illustration -->
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4 shadow-sm" style="width: 200px; height: 140px;">
                        <i class="bi bi-calendar-event display-1 text-primary"></i>
                    </div>
                </div>
                
                <!-- Content -->
                <h2 class="h4 mb-3">Service Appointments</h2>
                <p class="text-muted mb-4">
                    Create and manage service appointments effortlessly. Schedule field agents for various tasks and track progress in real-time.
                </p>
                
                <!-- Create Service Appointment Button -->
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createServiceAppointmentModal">
                    <i class="bi bi-plus-circle me-2"></i>Create Service Appointment
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('service_appointments/_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/service_appointments.js') ?>"></script>
<?= $this->endSection() ?>