<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="p-4">
    <h4 class="mb-4">Holiday Management</h4>
    <p class="text-muted mb-4">Configure business holidays for scheduling and calendar purposes</p>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="card-title mb-0">Holiday Configuration</h6>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <label for="yearSelect" class="form-label mb-0">Year:</label>
                    <select class="form-select" id="yearSelect" style="width: auto;">
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025" selected>2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary" id="editHolidaysBtn">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
            </div>
        </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="holidaysTable">
                            <thead>
                                <tr>
                                    <th>Holiday Name</th>
                                    <th>Holiday Date</th>
                                    <th>Day</th>
                                </tr>
                            </thead>
                            <tbody id="holidaysTableBody">
                                <!-- Holidays will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div id="noHolidays" class="text-center py-4" style="display: none;">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-2">No holidays configured</h5>
                        <p class="text-muted">Click the "Edit" button to add holidays for this year.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Holidays Modal -->
<div class="modal fade" id="editHolidaysModal" tabindex="-1" aria-labelledby="editHolidaysModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHolidaysModalLabel">Edit Holidays List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="modalYearSelect" class="form-label">Year</label>
                        <input type="number" class="form-control" id="modalYearSelect" value="2025" min="2020" max="2030">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Holidays List</label>
                    <div id="holidaysList">
                        <!-- Holiday input rows will be added here -->
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addHolidayBtn">
                        <i class="bi bi-plus me-1"></i>New Line
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveHolidaysBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentYear = 2025;
    let holidaysData = [];
    
    // Initialize
    loadHolidays(currentYear);
    
    // Year selector change
    document.getElementById('yearSelect').addEventListener('change', function() {
        currentYear = parseInt(this.value);
        loadHolidays(currentYear);
    });
    
    // Edit button click
    document.getElementById('editHolidaysBtn').addEventListener('click', function() {
        openEditModal();
    });
    
    // Add holiday button
    document.getElementById('addHolidayBtn').addEventListener('click', function() {
        addHolidayRow();
    });
    
    // Save holidays
    document.getElementById('saveHolidaysBtn').addEventListener('click', function() {
        saveHolidays();
    });
    
    function loadHolidays(year) {
        // For now, we'll use mock data
        // TODO: Replace with actual API call to fetch holidays
        const mockHolidays = [
            { name: "New Year's Day", date: year + "-01-01" },
            { name: "Independence Day", date: year + "-07-04" },
            { name: "Christmas Day", date: year + "-12-25" }
        ];
        
        holidaysData = mockHolidays;
        displayHolidays(holidaysData);
    }
    
    function displayHolidays(holidays) {
        const tbody = document.getElementById('holidaysTableBody');
        const noHolidays = document.getElementById('noHolidays');
        const table = document.getElementById('holidaysTable');
        
        if (holidays.length === 0) {
            table.style.display = 'none';
            noHolidays.style.display = 'block';
            return;
        }
        
        table.style.display = 'table';
        noHolidays.style.display = 'none';
        
        tbody.innerHTML = '';
        
        holidays.forEach(holiday => {
            const date = new Date(holiday.date + 'T00:00:00');
            const dayName = date.toLocaleDateString('en-US', { weekday: 'long' });
            const formattedDate = date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            });
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${holiday.name}</td>
                <td>${formattedDate}</td>
                <td>${dayName}</td>
            `;
            tbody.appendChild(row);
        });
    }
    
    function openEditModal() {
        document.getElementById('modalYearSelect').value = currentYear;
        setupHolidaysModal(holidaysData);
        
        const modal = new bootstrap.Modal(document.getElementById('editHolidaysModal'));
        modal.show();
    }
    
    function setupHolidaysModal(holidays) {
        const container = document.getElementById('holidaysList');
        container.innerHTML = '';
        
        if (holidays.length === 0) {
            addHolidayRow();
        } else {
            holidays.forEach(holiday => {
                addHolidayRow(holiday.name, holiday.date);
            });
        }
    }
    
    function addHolidayRow(name = '', date = '') {
        const container = document.getElementById('holidaysList');
        const row = document.createElement('div');
        row.className = 'holiday-row row mb-2';
        
        row.innerHTML = `
            <div class="col-md-6">
                <input type="text" class="form-control holiday-name" placeholder="Holiday Name" value="${name}">
            </div>
            <div class="col-md-5">
                <input type="date" class="form-control holiday-date" value="${date}">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm remove-holiday">
                    <i class="bi bi-dash"></i>
                </button>
            </div>
        `;
        
        container.appendChild(row);
        
        // Add remove functionality
        row.querySelector('.remove-holiday').addEventListener('click', function() {
            row.remove();
        });
    }
    
    function saveHolidays() {
        const year = document.getElementById('modalYearSelect').value;
        const holidayRows = document.querySelectorAll('.holiday-row');
        const holidays = [];
        
        holidayRows.forEach(row => {
            const name = row.querySelector('.holiday-name').value.trim();
            const date = row.querySelector('.holiday-date').value;
            
            if (name && date) {
                holidays.push({ name, date });
            }
        });
        
        // TODO: Replace with actual API call to save holidays
        console.log('Saving holidays for year', year, ':', holidays);
        
        // Update current data and display
        currentYear = parseInt(year);
        holidaysData = holidays;
        document.getElementById('yearSelect').value = currentYear;
        displayHolidays(holidaysData);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editHolidaysModal'));
        modal.hide();
        
        // Show success message
        showAlert('Holidays updated successfully!', 'success');
    }
    
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }
});
</script>

<style>
.holiday-row {
    align-items: center;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

#holidaysList {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
}
</style>
<?= $this->endSection() ?>
