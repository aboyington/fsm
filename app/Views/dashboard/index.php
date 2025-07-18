<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h6 class="h4">Dashboard</h6>
            <p class="text-muted">Welcome back, <span id="userName">User</span>!</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newWorkOrderModal">
                <i class="bi bi-plus-circle"></i> New Work Order
            </button>
            <button class="btn btn-secondary" onclick="window.location.href='<?= base_url('customers/new') ?>';">
                <i class="bi bi-person-plus"></i> New Customer
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Summary Statistics -->
        <div class="col-md-3">
            <h5 id="totalCustomers">0</h5>
            <p class="text-muted">Total Customers</p>
        </div>
        <div class="col-md-3">
            <h5 id="activeWorkOrders">0</h5>
            <p class="text-muted">Active Work Orders</p>
        </div>
        <div class="col-md-3">
            <h5 id="todaysAppointments">0</h5>
            <p class="text-muted">Today's Appointments</p>
        </div>
        <div class="col-md-3">
            <h5 id="totalTechnicians">0</h5>
            <p class="text-muted">Field Technicians</p>
        </div>
    </div>
    <!-- Recent Work Orders Table -->
    <div class="mb-4">
        <h6 class="mb-3">Recent Work Orders</h6>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Scheduled</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="recentWorkOrdersBody">
                    <!-- Dynamic rows here -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title">Today's Schedule</h6>
                </div>
                <div class="card-body">
                    <div id="todaysSchedule">
                        <p class="text-muted">No appointments scheduled for today.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Chart -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title">Work Order Status Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Work Order Modal -->
<div class="modal fade" id="newWorkOrderModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Create Work Order</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newWorkOrderForm">
                    <!-- Work Order Summary -->
                    <h6 class="mb-3 text-primary">Work Order Summary</h6>
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label for="summary" class="form-label">Summary <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="summary" name="summary" rows="3" required placeholder="Enter work order summary"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority">
                                <option value="">Select</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="critical">Critical</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type">
                                <option value="">Select</option>
                                <option value="service">Service</option>
                                <option value="inspection">Inspection</option>
                                <option value="installation">Installation</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="emergency">Emergency</option>
                                <option value="scheduled">Scheduled Maintenance</option>
                                <option value="standard">Standard</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="dueDate" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="dueDate">
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <h6 class="mb-3 text-primary">Contact Details</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="company" class="form-label">Company</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="company" placeholder="Search Company">
                                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="contact" required placeholder="Search Contact">
                                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-person-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email address">
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Phone number">
                        </div>
                        <div class="col-md-4">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="tel" class="form-control" id="mobile" placeholder="Mobile number">
                        </div>
                    </div>

                    <!-- Asset -->
                    <h6 class="mb-3 text-primary">Asset</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="asset" class="form-label">Asset</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="asset" placeholder="Search Asset">
                                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <h6 class="mb-3 text-primary">Address</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="serviceAddress" class="form-label">Service Address</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="serviceAddress" placeholder="Service address">
                                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-geo-alt"></i></button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="billingAddress" class="form-label">Billing Address</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="billingAddress" placeholder="Billing address">
                                <button class="btn btn-outline-secondary" type="button"><i class="bi bi-geo-alt"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Preference -->
                    <h6 class="mb-3 text-primary">Preference</h6>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="preferredDate1" class="form-label">Preferred Date 1</label>
                            <input type="date" class="form-control" id="preferredDate1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="preferredDate2" class="form-label">Preferred Date 2</label>
                            <input type="date" class="form-control" id="preferredDate2">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="preferredTime" class="form-label">Preferred Time</label>
                            <select class="form-select" id="preferredTime">
                                <option value="">Select</option>
                                <option value="-none-">-None-</option>
                                <option value="any">Any time</option>
                                <option value="morning">Morning</option>
                                <option value="afternoon">Afternoon</option>
                                <option value="evening">Evening</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="preferenceNote" class="form-label">Preference Note</label>
                            <textarea class="form-control" id="preferenceNote" rows="2" placeholder="Enter any preferences"></textarea>
                        </div>
                    </div>

                    <!-- Services -->
                    <h6 class="text-primary mb-3">Services</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%">Service</th>
                                    <th style="width: 15%">Quantity</th>
                                    <th style="width: 15%">List Price</th>
                                    <th style="width: 20%">Tax Name</th>
                                    <th style="width: 15%">Line Item Amount</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody id="servicesTableBody">
                                <!-- Dynamic service rows will be added here -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-link btn-sm" onclick="addServiceLine()">
                            <i class="bi bi-plus-circle"></i> New Line
                        </button>
                    </div>

                    <!-- Parts -->
                    <h6 class="text-primary mb-3">Parts</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 30%">Part</th>
                                    <th style="width: 15%">Quantity</th>
                                    <th style="width: 15%">List Price</th>
                                    <th style="width: 20%">Tax Name</th>
                                    <th style="width: 15%">Line Item Amount</th>
                                    <th style="width: 5%"></th>
                                </tr>
                            </thead>
                            <tbody id="partsTableBody">
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control form-control-sm me-2" placeholder="Search Part" name="parts[0][name]">
                                            <button class="btn btn-sm btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                                        </div>
                                        <div class="mt-1">
                                            <textarea class="form-control form-control-sm" rows="2" placeholder="Add a Description" name="parts[0][description]"></textarea>
                                        </div>
                                        <div class="mt-1">
                                            <a href="#" class="text-danger small">Select Service Line Item</a>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" value="0" min="0" name="parts[0][quantity]" onchange="calculateLineTotal(this)">
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">CA$</span>
                                            <input type="number" class="form-control" value="0.00" min="0" step="0.01" name="parts[0][price]" onchange="calculateLineTotal(this)">
                                        </div>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" name="parts[0][tax]" onchange="calculateLineTotal(this)">
                                            <option value="0.05">GST [5%]</option>
                                            <option value="0.13">HST [13%]</option>
                                            <option value="0">No Tax</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">CA$</span>
                                            <input type="text" class="form-control line-total" value="0.00" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePartLine(this)">
                                            <i class="bi bi-dash-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-link btn-sm" onclick="addPartLine()">
                            <i class="bi bi-plus-circle"></i> New Line
                        </button>
                    </div>

                    <!-- Summary totals -->
                    <div class="row mt-4">
                        <div class="col-md-5 offset-md-7">
                            <table class="table table-sm">
                                <tr>
                                    <td class="text-end">Sub Total:</td>
                                    <td class="text-end" style="width: 150px">
                                        <span class="text-muted">CA$</span> <span id="subTotal">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">Tax Amount:</td>
                                    <td class="text-end">
                                        <span class="text-muted">CA$</span> <span id="taxAmount">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">Discount:</td>
                                    <td class="text-end">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" id="discountAmount" value="0" min="0" step="0.01">
                                            <select class="form-select" id="discountType" style="max-width: 80px">
                                                <option value="fixed">CA$</option>
                                                <option value="percent">%</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">Adjustment:</td>
                                    <td class="text-end">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">CA$</span>
                                            <input type="number" class="form-control" id="adjustmentAmount" value="0" step="0.01">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="text-end">Grand Total:</td>
                                    <td class="text-end">
                                        <span class="text-muted">CA$</span> <span id="grandTotal">0.00</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Service Tasks -->
                    <h6 class="text-primary mb-3 mt-4">Service Tasks</h6>
                    <div class="mb-4">
                        <div class="border rounded p-3 text-center text-muted">
                            <p class="mb-0">No service tasks added yet</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="createWorkOrder()">Save</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Update user name
    const currentUser = sessionStorage.getItem('currentUser');
    if (currentUser) {
        const user = JSON.parse(currentUser);
        document.getElementById('userName').textContent = `${user.first_name} ${user.last_name}`;
    }

    // Load dashboard data
    loadDashboardData();
    loadCustomersForSelect();
});

// Fetch and populate dashboard data
async function loadDashboardData() {
    try {
        showLoading(true);
        
        // Fetch customer data
        const customerData = await apiClient.getCustomers({ limit: 1 });
        document.getElementById('totalCustomers').textContent = customerData.pagination.total || 0;

        // Since we don't have work orders API yet, let's show sample data
        // In production, this would be: const workOrderData = await apiClient.get('work-orders');
        const sampleWorkOrders = [
            {
                id: 'WO-001',
                customer_name: 'John Doe',
                work_order_type: 'installation',
                status: 'new',
                priority: 'high',
                scheduled_date: new Date().toISOString().split('T')[0]
            },
            {
                id: 'WO-002',
                customer_name: 'Jane Smith',
                work_order_type: 'repair',
                status: 'in_progress',
                priority: 'normal',
                scheduled_date: new Date().toISOString().split('T')[0]
            }
        ];

        // Update statistics
        document.getElementById('activeWorkOrders').textContent = sampleWorkOrders.length;
        document.getElementById('todaysAppointments').textContent = sampleWorkOrders.filter(wo => 
            wo.scheduled_date === new Date().toISOString().split('T')[0]
        ).length;
        
        // Count technicians
        const techCount = await countTechnicians();
        document.getElementById('totalTechnicians').textContent = techCount;

        // Populate recent work orders
        populateWorkOrdersTable(sampleWorkOrders);
        
        // Populate today's schedule
        populateTodaysSchedule(sampleWorkOrders);
        
        // Create status chart
        createStatusChart(sampleWorkOrders);

    } catch (error) {
        console.error('Failed to load dashboard data:', error);
        showAlert('Failed to load dashboard data', 'danger');
    } finally {
        showLoading(false);
    }
}

// Count field technicians
async function countTechnicians() {
    try {
        // In production, this would fetch from users API with role filter
        return 3; // Placeholder
    } catch (error) {
        return 0;
    }
}

// Populate work orders table
function populateWorkOrdersTable(workOrders) {
    const workOrdersBody = document.getElementById('recentWorkOrdersBody');
    workOrdersBody.innerHTML = '';

    if (workOrders.length === 0) {
        workOrdersBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No work orders found</td></tr>';
        return;
    }

    workOrders.forEach(order => {
        const row = document.createElement('tr');
        const statusBadge = getStatusBadge(order.status);
        const priorityBadge = getPriorityBadge(order.priority);
        
        row.innerHTML = `
            <td>${order.id}</td>
            <td>${order.customer_name}</td>
            <td>${formatWorkOrderType(order.work_order_type)}</td>
            <td>${statusBadge}</td>
            <td>${priorityBadge}</td>
            <td>${formatDate(order.scheduled_date)}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="viewWorkOrder('${order.id}')">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-secondary" onclick="editWorkOrder('${order.id}')">
                    <i class="bi bi-pencil"></i>
                </button>
            </td>
        `;
        workOrdersBody.appendChild(row);
    });
}

// Populate today's schedule
function populateTodaysSchedule(workOrders) {
    const todaysSchedule = document.getElementById('todaysSchedule');
    const today = new Date().toISOString().split('T')[0];
    const todaysOrders = workOrders.filter(wo => wo.scheduled_date === today);
    
    if (todaysOrders.length === 0) {
        todaysSchedule.innerHTML = '<p class="text-muted">No appointments scheduled for today.</p>';
        return;
    }
    
    let scheduleHtml = '<div class="list-group">';
    todaysOrders.forEach(order => {
        scheduleHtml += `
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${order.customer_name}</h6>
                    <small>${getPriorityBadge(order.priority)}</small>
                </div>
                <p class="mb-1">${formatWorkOrderType(order.work_order_type)}</p>
                <small>${getStatusBadge(order.status)}</small>
            </div>
        `;
    });
    scheduleHtml += '</div>';
    todaysSchedule.innerHTML = scheduleHtml;
}

// Create status distribution chart
function createStatusChart(workOrders) {
    const ctx = document.getElementById('statusChart').getContext('2d');
    
    // Count statuses
    const statusCounts = {
        new: 0,
        assigned: 0,
        in_progress: 0,
        completed: 0,
        cancelled: 0
    };
    
    workOrders.forEach(order => {
        if (statusCounts.hasOwnProperty(order.status)) {
            statusCounts[order.status]++;
        }
    });
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['New', 'Assigned', 'In Progress', 'Completed', 'Cancelled'],
            datasets: [{
                data: Object.values(statusCounts),
                backgroundColor: [
                    '#0dcaf0', // info
                    '#ffc107', // warning
                    '#0d6efd', // primary
                    '#198754', // success
                    '#dc3545'  // danger
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// Load customers for select dropdown
async function loadCustomersForSelect() {
    try {
        const response = await apiClient.getCustomers({ limit: 100 });
        const select = document.getElementById('customerSelect');
        
        response.data.forEach(customer => {
            const option = document.createElement('option');
            option.value = customer.id;
            option.textContent = `${customer.first_name} ${customer.last_name} - ${customer.address}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Failed to load customers:', error);
    }
}

// Create new work order
async function createWorkOrder() {
    const form = document.getElementById('newWorkOrderForm');
    
    if (!validateForm(form)) {
        return;
    }
    
    const data = {
        customer_id: document.getElementById('customerSelect').value,
        work_order_type: document.getElementById('workOrderType').value,
        priority: document.getElementById('priority').value,
        scheduled_date: document.getElementById('scheduledDate').value,
        title: document.getElementById('title').value,
        description: document.getElementById('description').value,
        status: 'new'
    };
    
    try {
        showLoading(true);
        // In production: await apiClient.post('work-orders', data);
        
        // For now, just show success and reload
        showAlert('Work order created successfully!', 'success');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('newWorkOrderModal'));
        modal.hide();
        
        // Reset form
        form.reset();
        
        // Reload dashboard
        loadDashboardData();
    } catch (error) {
        showAlert('Failed to create work order', 'danger');
    } finally {
        showLoading(false);
    }
}

// Helper functions
function getStatusBadge(status) {
    const badges = {
        new: '<span class="badge bg-info">New</span>',
        assigned: '<span class="badge bg-warning">Assigned</span>',
        in_progress: '<span class="badge bg-primary">In Progress</span>',
        completed: '<span class="badge bg-success">Completed</span>',
        cancelled: '<span class="badge bg-danger">Cancelled</span>'
    };
    return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
}

function getPriorityBadge(priority) {
    const badges = {
        low: '<span class="badge bg-secondary">Low</span>',
        normal: '<span class="badge bg-info">Normal</span>',
        high: '<span class="badge bg-warning">High</span>',
        urgent: '<span class="badge bg-danger">Urgent</span>'
    };
    return badges[priority] || `<span class="badge bg-secondary">${priority}</span>`;
}

function formatWorkOrderType(type) {
    return type.charAt(0).toUpperCase() + type.slice(1).replace('_', ' ');
}

function viewWorkOrder(id) {
    window.location.href = `<?= base_url('work-orders/') ?>${id}`;
}

function editWorkOrder(id) {
    window.location.href = `<?= base_url('work-orders/') ?>${id}/edit`;
}

// Add service line to the table
function addServiceLine() {
    const tbody = document.getElementById('servicesTableBody');
    const rowCount = tbody.children.length;
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <input type="text" class="form-control form-control-sm me-2" placeholder="Search Service" name="services[${rowCount}][name]">
                <button class="btn btn-sm btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
            </div>
            <div class="mt-1">
                <textarea class="form-control form-control-sm" rows="2" placeholder="Add a Description" name="services[${rowCount}][description]"></textarea>
            </div>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" value="0" min="0" name="services[${rowCount}][quantity]" onchange="calculateLineTotal(this)">
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">CA$</span>
                <input type="number" class="form-control" value="0.00" min="0" step="0.01" name="services[${rowCount}][price]" onchange="calculateLineTotal(this)">
            </div>
        </td>
        <td>
            <select class="form-select form-select-sm" name="services[${rowCount}][tax]" onchange="calculateLineTotal(this)">
                <option value="0.05">GST [5%]</option>
                <option value="0.13">HST [13%]</option>
                <option value="0">No Tax</option>
            </select>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">CA$</span>
                <input type="text" class="form-control line-total" value="0.00" readonly>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeServiceLine(this)">
                <i class="bi bi-dash-circle"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

// Add part line to the table
function addPartLine() {
    const tbody = document.getElementById('partsTableBody');
    const rowCount = tbody.children.length;
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <input type="text" class="form-control form-control-sm me-2" placeholder="Search Part" name="parts[${rowCount}][name]">
                <button class="btn btn-sm btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
            </div>
            <div class="mt-1">
                <textarea class="form-control form-control-sm" rows="2" placeholder="Add a Description" name="parts[${rowCount}][description]"></textarea>
            </div>
            <div class="mt-1">
                <a href="#" class="text-danger small">Select Service Line Item</a>
            </div>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" value="0" min="0" name="parts[${rowCount}][quantity]" onchange="calculateLineTotal(this)">
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">CA$</span>
                <input type="number" class="form-control" value="0.00" min="0" step="0.01" name="parts[${rowCount}][price]" onchange="calculateLineTotal(this)">
            </div>
        </td>
        <td>
            <select class="form-select form-select-sm" name="parts[${rowCount}][tax]" onchange="calculateLineTotal(this)">
                <option value="0.05">GST [5%]</option>
                <option value="0.13">HST [13%]</option>
                <option value="0">No Tax</option>
            </select>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">CA$</span>
                <input type="text" class="form-control line-total" value="0.00" readonly>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePartLine(this)">
                <i class="bi bi-dash-circle"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

// Remove service line
function removeServiceLine(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotals();
}

// Remove part line
function removePartLine(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotals();
}

// Calculate line total
function calculateLineTotal(element) {
    const row = element.closest('tr');
    const quantity = parseFloat(row.querySelector('input[type="number"][name*="quantity"]').value) || 0;
    const price = parseFloat(row.querySelector('input[type="number"][name*="price"]').value) || 0;
    const taxRate = parseFloat(row.querySelector('select[name*="tax"]').value) || 0;
    
    const subtotal = quantity * price;
    const tax = subtotal * taxRate;
    const total = subtotal + tax;
    
    row.querySelector('.line-total').value = total.toFixed(2);
    
    calculateTotals();
}

// Calculate all totals
function calculateTotals() {
    let subTotal = 0;
    let taxAmount = 0;
    
    // Calculate service totals
    document.querySelectorAll('#servicesTableBody tr').forEach(row => {
        const quantity = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name*="price"]').value) || 0;
        const taxRate = parseFloat(row.querySelector('select[name*="tax"]').value) || 0;
        
        const lineSubtotal = quantity * price;
        const lineTax = lineSubtotal * taxRate;
        
        subTotal += lineSubtotal;
        taxAmount += lineTax;
    });
    
    // Calculate parts totals
    document.querySelectorAll('#partsTableBody tr').forEach(row => {
        const quantity = parseFloat(row.querySelector('input[name*="quantity"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name*="price"]').value) || 0;
        const taxRate = parseFloat(row.querySelector('select[name*="tax"]').value) || 0;
        
        const lineSubtotal = quantity * price;
        const lineTax = lineSubtotal * taxRate;
        
        subTotal += lineSubtotal;
        taxAmount += lineTax;
    });
    
    // Get discount and adjustment
    const discountAmount = parseFloat(document.getElementById('discountAmount').value) || 0;
    const discountType = document.getElementById('discountType').value;
    const adjustmentAmount = parseFloat(document.getElementById('adjustmentAmount').value) || 0;
    
    // Calculate discount
    let discountValue = 0;
    if (discountType === 'percent') {
        discountValue = subTotal * (discountAmount / 100);
    } else {
        discountValue = discountAmount;
    }
    
    // Calculate grand total
    const grandTotal = subTotal + taxAmount - discountValue + adjustmentAmount;
    
    // Update display
    document.getElementById('subTotal').textContent = subTotal.toFixed(2);
    document.getElementById('taxAmount').textContent = taxAmount.toFixed(2);
    document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
}

// Add event listeners for discount and adjustment changes
document.addEventListener('DOMContentLoaded', function() {
    const discountAmount = document.getElementById('discountAmount');
    const discountType = document.getElementById('discountType');
    const adjustmentAmount = document.getElementById('adjustmentAmount');
    
    if (discountAmount) {
        discountAmount.addEventListener('change', calculateTotals);
    }
    if (discountType) {
        discountType.addEventListener('change', calculateTotals);
    }
    if (adjustmentAmount) {
        adjustmentAmount.addEventListener('change', calculateTotals);
    }
});
</script>
<?= $this->endSection() ?>

