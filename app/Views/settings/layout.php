<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 settings-sidebar">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Setup</h5>
                        <div class="mt-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Search">
                        </div>
                    </div>
                    
                    <!-- General Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#generalSection">
                            <i class="bi bi-chevron-down"></i> General
                        </div>
                        <div id="generalSection" class="collapse show">
                            <a href="<?= base_url('settings/organization') ?>" class="settings-link <?= $activeTab == 'organization' ? 'active' : '' ?>">
                                Organization Details
                            </a>
                            <a href="<?= base_url('settings/currency') ?>" class="settings-link <?= $activeTab == 'currency' ? 'active' : '' ?>">
                                Currency
                            </a>
                            <a href="<?= base_url('settings/account-registry') ?>" class="settings-link <?= $activeTab == 'account-registry' ? 'active' : '' ?>">
                                Account Registry
                            </a>
                        </div>
                    </div>

                    <!-- Workforce Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#workforceSection">
                            <i class="bi bi-chevron-down"></i> Workforce
                        </div>
                        <div id="workforceSection" class="collapse show">
                            <a href="<?= base_url('settings/users') ?>" class="settings-link <?= $activeTab == 'users' ? 'active' : '' ?>">
                                Users
                            </a>
                            <a href="<?= base_url('settings/territories') ?>" class="settings-link <?= $activeTab == 'territories' ? 'active' : '' ?>">
                                Territories
                            </a>
                            <a href="<?= base_url('settings/skills') ?>" class="settings-link <?= $activeTab == 'skills' ? 'active' : '' ?>">
                                Skills
                            </a>
                            <a href="<?= base_url('settings/holiday') ?>" class="settings-link <?= $activeTab == 'holiday' ? 'active' : '' ?>">
                                Holiday
                            </a>
                        </div>
                    </div>

                    <!-- Security Control Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#securitySection">
                            <i class="bi bi-chevron-down"></i> Security Control
                        </div>
                        <div id="securitySection" class="collapse show">
                            <a href="<?= base_url('settings/profiles') ?>" class="settings-link <?= $activeTab == 'profiles' ? 'active' : '' ?>">
                                Profiles
                            </a>
                            <a href="<?= base_url('settings/audit-log') ?>" class="settings-link <?= $activeTab == 'audit-log' ? 'active' : '' ?>">
                                Audit Log
                            </a>
                            <a href="<?= base_url('settings/pii-fields') ?>" class="settings-link <?= $activeTab == 'pii-fields' ? 'active' : '' ?>">
                                PII Fields
                            </a>
                        </div>
                    </div>

                    <!-- Field Service Settings Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#fieldServiceSection">
                            <i class="bi bi-chevron-down"></i> Field Service Settings
                        </div>
                        <div id="fieldServiceSection" class="collapse show">
                            <a href="<?= base_url('settings/transaction-settings') ?>" class="settings-link <?= $activeTab == 'transaction-settings' ? 'active' : '' ?>">
                                Transaction Settings
                            </a>
                            <a href="<?= base_url('settings/record-templates') ?>" class="settings-link <?= $activeTab == 'record-templates' ? 'active' : '' ?>">
                                Record Templates
                            </a>
                        </div>
                    </div>

                    <!-- Channels Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#channelsSection">
                            <i class="bi bi-chevron-down"></i> Channels
                        </div>
                        <div id="channelsSection" class="collapse show">
                            <a href="<?= base_url('settings/email') ?>" class="settings-link <?= $activeTab == 'email' ? 'active' : '' ?>">
                                Email
                            </a>
                            <a href="<?= base_url('settings/notifications') ?>" class="settings-link <?= $activeTab == 'notifications' ? 'active' : '' ?>">
                                Notifications
                            </a>
                            <a href="<?= base_url('settings/whatsapp') ?>" class="settings-link <?= $activeTab == 'whatsapp' ? 'active' : '' ?>">
                                Whatsapp <i class="bi bi-telephone-fill"></i>
                            </a>
                            <a href="<?= base_url('settings/webforms') ?>" class="settings-link <?= $activeTab == 'webforms' ? 'active' : '' ?>">
                                Webforms
                            </a>
                        </div>
                    </div>

                    <!-- Maintenance Plans Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#maintenanceSection">
                            <i class="bi bi-chevron-down"></i> Maintenance Plans
                        </div>
                        <div id="maintenanceSection" class="collapse show">
                            <a href="<?= base_url('settings/scheduled-maintenances') ?>" class="settings-link <?= $activeTab == 'scheduled-maintenances' ? 'active' : '' ?>">
                                Scheduled Maintenances
                            </a>
                        </div>
                    </div>

                    <!-- Billing Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#billingSection">
                            <i class="bi bi-chevron-down"></i> Billing
                        </div>
                        <div id="billingSection" class="collapse show">
                            <a href="<?= base_url('settings/billing-setup') ?>" class="settings-link <?= $activeTab == 'billing-setup' ? 'active' : '' ?>">
                                Billing Setup
                            </a>
                            <a href="<?= base_url('settings/tax-settings') ?>" class="settings-link <?= $activeTab == 'tax-settings' ? 'active' : '' ?>">
                                Tax Setting
                            </a>
                            <a href="<?= base_url('settings/sync-logs') ?>" class="settings-link <?= $activeTab == 'sync-logs' ? 'active' : '' ?>">
                                Sync Logs
                            </a>
                        </div>
                    </div>

                    <!-- Customization Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#customizationSection">
                            <i class="bi bi-chevron-down"></i> Customization
                        </div>
                        <div id="customizationSection" class="collapse show">
                            <a href="<?= base_url('settings/modules-fields') ?>" class="settings-link <?= $activeTab == 'modules-fields' ? 'active' : '' ?>">
                                Modules and Fields
                            </a>
                            <a href="<?= base_url('settings/module-mapping') ?>" class="settings-link <?= $activeTab == 'module-mapping' ? 'active' : '' ?>">
                                Module Mapping
                            </a>
                            <a href="<?= base_url('settings/job-sheets') ?>" class="settings-link <?= $activeTab == 'job-sheets' ? 'active' : '' ?>">
                                Job Sheets
                            </a>
                            <a href="<?= base_url('settings/dispatch-console') ?>" class="settings-link <?= $activeTab == 'dispatch-console' ? 'active' : '' ?>">
                                Dispatch Console
                            </a>
                            <a href="<?= base_url('settings/templates') ?>" class="settings-link <?= $activeTab == 'templates' ? 'active' : '' ?>">
                                Templates
                            </a>
                        </div>
                    </div>

                    <!-- Automation Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#automationSection">
                            <i class="bi bi-chevron-down"></i> Automation
                        </div>
                        <div id="automationSection" class="collapse show">
                            <a href="<?= base_url('settings/workflow-rules') ?>" class="settings-link <?= $activeTab == 'workflow-rules' ? 'active' : '' ?>">
                                Workflow Rules
                            </a>
                            <a href="<?= base_url('settings/time-based-rules') ?>" class="settings-link <?= $activeTab == 'time-based-rules' ? 'active' : '' ?>">
                                Time Based Rules
                            </a>
                            <a href="<?= base_url('settings/actions') ?>" class="settings-link <?= $activeTab == 'actions' ? 'active' : '' ?>">
                                Actions
                            </a>
                        </div>
                    </div>

                    <!-- Data Administration Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#dataSection">
                            <i class="bi bi-chevron-down"></i> Data Administration
                        </div>
                        <div id="dataSection" class="collapse show">
                            <a href="<?= base_url('settings/data-export') ?>" class="settings-link <?= $activeTab == 'data-export' ? 'active' : '' ?>">
                                Data Export
                            </a>
                            <a href="<?= base_url('settings/file-storage') ?>" class="settings-link <?= $activeTab == 'file-storage' ? 'active' : '' ?>">
                                File Storage
                            </a>
                        </div>
                    </div>

                    <!-- Reports Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#reportsSection">
                            <i class="bi bi-chevron-down"></i> Reports
                        </div>
                        <div id="reportsSection" class="collapse show">
                            <a href="<?= base_url('reports') ?>" class="settings-link <?= $activeTab == 'reports' ? 'active' : '' ?>">
                                Reports Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Developer Space Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#developerSection">
                            <i class="bi bi-chevron-down"></i> Developer Space
                        </div>
                        <div id="developerSection" class="collapse show">
                            <a href="<?= base_url('settings/apis') ?>" class="settings-link <?= $activeTab == 'apis' ? 'active' : '' ?>">
                                APIs
                            </a>
                            <a href="<?= base_url('settings/connections') ?>" class="settings-link <?= $activeTab == 'connections' ? 'active' : '' ?>">
                                Connections
                            </a>
                            <a href="<?= base_url('settings/standalone-functions') ?>" class="settings-link <?= $activeTab == 'standalone-functions' ? 'active' : '' ?>">
                                Standalone Functions
                            </a>
                        </div>
                    </div>

                    <!-- Integration Section -->
                    <div class="settings-section">
                        <div class="settings-section-header" data-bs-toggle="collapse" data-bs-target="#integrationSection">
                            <i class="bi bi-chevron-down"></i> Integration
                        </div>
                        <div id="integrationSection" class="collapse show">
                            <!-- Integration items here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <?= $this->renderSection('settings-content') ?>
        </div>
    </div>
</div>

<style>
.settings-sidebar {
    height: calc(100vh - 60px);
    overflow-y: auto;
    position: sticky;
    top: 60px;
}

.settings-section {
    border-bottom: 1px solid #e9ecef;
}

.settings-section-header {
    padding: 12px 20px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.2s;
}

.settings-section-header:hover {
    background-color: #f8f9fa;
}

.settings-section-header i {
    font-size: 0.75rem;
}

.settings-section-header[aria-expanded="true"] i {
    transform: rotate(180deg);
}

.settings-link {
    display: block;
    padding: 10px 20px 10px 40px;
    color: #495057;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s;
    position: relative;
}

.settings-link:hover {
    background-color: #f8f9fa;
    color: #212529;
    text-decoration: none;
}

.settings-link.active {
    background-color: #e7f1ff;
    color: #0d6efd;
    font-weight: 500;
}

.settings-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background-color: #0d6efd;
}

.settings-link i {
    margin-left: 5px;
    font-size: 0.8rem;
}

/* Custom scrollbar for sidebar */
.settings-sidebar::-webkit-scrollbar {
    width: 6px;
}

.settings-sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.settings-sidebar::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.settings-sidebar::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
<?= $this->endSection() ?>
