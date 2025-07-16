<!-- Create/Edit Service Appointment Modal -->
<div class="modal fade" id="createServiceAppointmentModal" tabindex="-1" aria-labelledby="createServiceAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createServiceAppointmentModalLabel">Create Service Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="serviceAppointmentForm">
                <div class="modal-body">
                    <input type="hidden" id="appointmentId" name="id">
                    
                    <!-- Choose Work Order -->
                    <h6 class="mb-3 text-primary">Choose Work Order</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="work_order_id" class="form-label">Choose Work Order <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" id="work_order_id" name="work_order_id" required>
                                        <option value="">Select Work Order</option>
                                        <?php if (isset($work_orders)): ?>
                                        <?php foreach ($work_orders as $work_order): ?>
                                        <option value="<?= $work_order['id'] ?>"><?= esc($work_order['work_order_number']) ?> - <?= esc($work_order['summary']) ?></option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" title="Search Work Order">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Appointment Details -->
                    <h6 class="mb-3 text-primary">Appointment Details</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control" id="duration" name="duration" min="15" step="15" value="60" placeholder="60">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="scheduled" selected>Scheduled</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Technician Assignment -->
                    <h6 class="mb-3 text-primary">Technician Assignment</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="technician_id" class="form-label">Assign Technician</label>
                                <select class="form-select" id="technician_id" name="technician_id">
                                    <option value="">Select Technician</option>
                                    <?php if (isset($technicians)): ?>
                                    <?php foreach ($technicians as $technician): ?>
                                    <option value="<?= $technician['id'] ?>"><?= esc($technician['first_name'] . ' ' . $technician['last_name']) ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes -->
                    <h6 class="mb-3 text-primary">Notes</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Appointment Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Enter any additional notes for this appointment..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveAppointmentBtn">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>