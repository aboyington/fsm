<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Audit Log</h4>
    <p class="text-muted">All actions and events to this organization are recorded in a chronological order</p>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="card-title mb-1">System Activity</h5>
                    <p class="text-muted mb-0">Monitor all system activities and changes</p>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="auditTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="audit-log-tab" data-bs-toggle="tab" data-bs-target="#audit-log" type="button" role="tab" aria-controls="audit-log" aria-selected="true">
                        Audit Log
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="entity-log-tab" data-bs-toggle="tab" data-bs-target="#entity-log" type="button" role="tab" aria-controls="entity-log" aria-selected="false">
                        Entity Log
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="auditTabContent">
                <!-- Audit Log Tab -->
                <div class="tab-pane fade show active" id="audit-log" role="tabpanel" aria-labelledby="audit-log-tab">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="dateFilter" class="form-label">Date</label>
                            <select id="dateFilter" class="form-select">
                                <option value="last-30-days" <?= $filters['date'] == 'last-30-days' ? 'selected' : '' ?>>Last 30 days</option>
                                <option value="today" <?= $filters['date'] == 'today' ? 'selected' : '' ?>>Today</option>
                                <option value="yesterday" <?= $filters['date'] == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                                <option value="last-7-days" <?= $filters['date'] == 'last-7-days' ? 'selected' : '' ?>>Last 7 days</option>
                                <option value="last-90-days" <?= $filters['date'] == 'last-90-days' ? 'selected' : '' ?>>Last 90 days</option>
                                <option value="last-year" <?= $filters['date'] == 'last-year' ? 'selected' : '' ?>>Last year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="userFilter" class="form-label">User</label>
                            <select id="userFilter" class="form-select">
                                <option value="">Select User</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= $filters['user'] == $user['id'] ? 'selected' : '' ?>>
                                        <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="subTypeFilter" class="form-label">Sub Type</label>
                            <select id="subTypeFilter" class="form-select">
                                <option value="">Select Sub Type</option>
                                <?php foreach ($subTypes as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $filters['sub_type'] == $key ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="actionFilter" class="form-label">Action</label>
                            <select id="actionFilter" class="form-select">
                                <option value="">Select Action</option>
                                <?php foreach ($actions as $key => $label): ?>
                                    <option value="<?= $key ?>" <?= $filters['action'] == $key ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Audit Log Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 160px;">Log Time</th>
                                    <th style="width: 150px;">Done By</th>
                                    <th style="width: 120px;">Sub Type</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($auditLogs)): ?>
                                    <?php foreach ($auditLogs as $log): ?>
                                        <tr>
                                            <td><?= esc($log['formatted_date']) ?></td>
                                            <td><?= esc($log['user_name']) ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <?= esc($log['sub_type_display']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $log['action_display'] == 'CREATE' ? 'success' : ($log['action_display'] == 'UPDATE' ? 'warning' : ($log['action_display'] == 'DELETE' ? 'danger' : 'secondary')) ?>">
                                                    <?= esc($log['action_display']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                No audit log entries found for the selected criteria.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="text-muted">1</span>
                        <span class="text-muted"><?= count($auditLogs) ?> Records per page</span>
                    </div>
                </div>

                <!-- Entity Log Tab -->
                <div class="tab-pane fade" id="entity-log" role="tabpanel" aria-labelledby="entity-log-tab">
                    <!-- Entity Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="entityDateFilter" class="form-label">Date</label>
                            <select id="entityDateFilter" class="form-select">
                                <option value="last-30-days" <?= $filters['date'] == 'last-30-days' ? 'selected' : '' ?>>Last 30 days</option>
                                <option value="today" <?= $filters['date'] == 'today' ? 'selected' : '' ?>>Today</option>
                                <option value="yesterday" <?= $filters['date'] == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                                <option value="last-7-days" <?= $filters['date'] == 'last-7-days' ? 'selected' : '' ?>>Last 7 days</option>
                                <option value="last-90-days" <?= $filters['date'] == 'last-90-days' ? 'selected' : '' ?>>Last 90 days</option>
                                <option value="last-year" <?= $filters['date'] == 'last-year' ? 'selected' : '' ?>>Last year</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="entityTypeFilter" class="form-label">Entity Type</label>
                            <select id="entityTypeFilter" class="form-select">
                                <option value="">All Entities</option>
                                <option value="user">User</option>
                                <option value="customer">Customer</option>
                                <option value="work_order">Work Order</option>
                                <option value="holiday">Holiday</option>
                                <option value="organization">Organization</option>
                                <option value="skill">Skill</option>
                                <option value="territory">Territory</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="entityActionFilter" class="form-label">Action</label>
                            <select id="entityActionFilter" class="form-select">
                                <option value="">All Actions</option>
                                <option value="create">Create</option>
                                <option value="update">Update</option>
                                <option value="delete">Delete</option>
                                <option value="view">View</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="entityUserFilter" class="form-label">User</label>
                            <select id="entityUserFilter" class="form-select">
                                <option value="">All Users</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>">
                                        <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Entity Log Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 140px;">Timestamp</th>
                                    <th style="width: 120px;">Entity Type</th>
                                    <th style="width: 150px;">Entity ID</th>
                                    <th style="width: 100px;">Action</th>
                                    <th style="width: 120px;">User</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody id="entityLogTableBody">
                                <?php if (!empty($entityLogs)): ?>
                                    <?php foreach ($entityLogs as $log): ?>
                                        <tr>
                                            <td><?= esc($log['formatted_date']) ?></td>
                                            <td>
                                                <span class="badge bg-info text-white">
                                                    <?= esc(ucfirst($log['entity_type'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-monospace"><?= esc($log['entity_id']) ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $log['action'] == 'create' ? 'success' : ($log['action'] == 'update' ? 'warning' : ($log['action'] == 'delete' ? 'danger' : 'secondary')) ?>">
                                                    <?= esc(strtoupper($log['action'])) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($log['user_name']) ?></td>
                                            <td>
                                                <small class="text-muted"><?= esc($log['details']) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-database fs-1 d-block mb-2"></i>
                                                No entity log entries found for the selected criteria.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Entity Log Pagination Footer -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="text-muted">1</span>
                        <span class="text-muted"><?= isset($entityLogs) ? count($entityLogs) : 0 ?> Records per page</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let filterTimeout;
    
    // Function to apply audit log filters
    function applyAuditFilters() {
        const dateFilter = document.getElementById('dateFilter').value;
        const userFilter = document.getElementById('userFilter').value;
        const subTypeFilter = document.getElementById('subTypeFilter').value;
        const actionFilter = document.getElementById('actionFilter').value;
        
        // Build query string
        const params = new URLSearchParams();
        if (dateFilter) params.append('date', dateFilter);
        if (userFilter) params.append('user', userFilter);
        if (subTypeFilter) params.append('sub_type', subTypeFilter);
        if (actionFilter) params.append('action', actionFilter);
        
        // Redirect with filters
        const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }
    
    // Function to apply entity log filters
    function applyEntityFilters() {
        const entityDateFilter = document.getElementById('entityDateFilter').value;
        const entityTypeFilter = document.getElementById('entityTypeFilter').value;
        const entityActionFilter = document.getElementById('entityActionFilter').value;
        const entityUserFilter = document.getElementById('entityUserFilter').value;
        
        // Build query string for entity log
        const params = new URLSearchParams();
        params.append('tab', 'entity'); // Add tab parameter
        if (entityDateFilter) params.append('date', entityDateFilter);
        if (entityTypeFilter) params.append('entity_type', entityTypeFilter);
        if (entityActionFilter) params.append('entity_action', entityActionFilter);
        if (entityUserFilter) params.append('user', entityUserFilter);
        
        // Redirect with filters
        const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }
    
    // Function to handle filter changes with debounce
    function handleFilterChange(filterFunction) {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(filterFunction, 500);
    }
    
    // Audit Log filter event listeners
    const auditFilters = ['dateFilter', 'userFilter', 'subTypeFilter', 'actionFilter'];
    auditFilters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', function() {
                handleFilterChange(applyAuditFilters);
            });
        }
    });
    
    // Entity Log filter event listeners
    const entityFilters = ['entityDateFilter', 'entityTypeFilter', 'entityActionFilter', 'entityUserFilter'];
    entityFilters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', function() {
                handleFilterChange(applyEntityFilters);
            });
        }
    });
    
    // Check if we should show Entity Log tab based on URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'entity') {
        // Switch to entity log tab
        const entityTab = document.getElementById('entity-log-tab');
        const auditTab = document.getElementById('audit-log-tab');
        const entityPane = document.getElementById('entity-log');
        const auditPane = document.getElementById('audit-log');
        
        // Remove active from audit log
        auditTab.classList.remove('active');
        auditTab.setAttribute('aria-selected', 'false');
        auditPane.classList.remove('show', 'active');
        
        // Add active to entity log
        entityTab.classList.add('active');
        entityTab.setAttribute('aria-selected', 'true');
        entityPane.classList.add('show', 'active');
    }
});
</script>
<?= $this->endSection() ?>
