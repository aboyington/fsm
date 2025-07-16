<!-- Create Service Report Modal -->
<div class="modal fade" id="createServiceReportModal" tabindex="-1" aria-labelledby="createServiceReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createServiceReportModalLabel">
                    <i class="bi bi-file-earmark-text"></i> Create Service Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createServiceReportForm">
                <div class="modal-body">
                    <div class="row">
                        <!-- Service Appointment Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serviceAppointmentId">Choose Service Appointment <span class="text-danger">*</span></label>
                                <select class="form-control" id="serviceAppointmentId" name="service_appointment_id" required>
                                    <option value="">Select Service Appointment</option>
                                    <?php if (!empty($service_appointments)): ?>
                                        <?php foreach ($service_appointments as $appointment): ?>
                                            <option value="<?= $appointment['id'] ?>"
                                                    data-work-order="<?= $appointment['work_order_id'] ?>"
                                                    data-technician="<?= $appointment['technician_id'] ?>">
                                                <?= $appointment['work_order_title'] ?> - <?= $appointment['appointment_date'] ?> <?= $appointment['appointment_time'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Work Order (Auto-filled) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="workOrderId">Work Order</label>
                                <select class="form-control" id="workOrderId" name="work_order_id" readonly>
                                    <option value="">Select from Service Appointment</option>
                                    <?php if (!empty($work_orders)): ?>
                                        <?php foreach ($work_orders as $order): ?>
                                            <option value="<?= $order['id'] ?>"><?= $order['work_order_number'] ?> - <?= $order['summary'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Technician (Auto-filled) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="technicianId">Technician</label>
                                <select class="form-control" id="technicianId" name="technician_id" readonly>
                                    <option value="">Select from Service Appointment</option>
                                    <?php if (!empty($technicians)): ?>
                                        <?php foreach ($technicians as $technician): ?>
                                            <option value="<?= $technician['id'] ?>"><?= $technician['first_name'] ?> <?= $technician['last_name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Report Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reportDate">Report Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="reportDate" name="report_date" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="completed">Completed</option>
                                    <option value="submitted">Submitted</option>
                                    <option value="approved">Approved</option>
                                </select>
                            </div>
                        </div>

                        <!-- Service Type -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serviceType">Service Type</label>
                                <select class="form-control" id="serviceType" name="service_type">
                                    <option value="">Select Service Type</option>
                                    <option value="installation">Installation</option>
                                    <option value="repair">Repair</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="inspection">Inspection</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Work Summary -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="workSummary">Work Summary <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="workSummary" name="work_summary" rows="4" 
                                          placeholder="Describe the work performed, issues encountered, and solutions implemented..." required></textarea>
                            </div>
                        </div>

                        <!-- Parts Used -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="partsUsed">Parts Used</label>
                                <textarea class="form-control" id="partsUsed" name="parts_used" rows="3" 
                                          placeholder="List all parts, materials, and quantities used during the service..."></textarea>
                            </div>
                        </div>

                        <!-- Time Spent -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="timeSpent">Time Spent (hours)</label>
                                <input type="number" class="form-control" id="timeSpent" name="time_spent" min="0" step="0.5" placeholder="e.g., 2.5">
                            </div>
                        </div>

                        <!-- Labor Cost -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="laborCost">Labor Cost</label>
                                <input type="number" class="form-control" id="laborCost" name="labor_cost" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Material Cost -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="materialCost">Material Cost</label>
                                <input type="number" class="form-control" id="materialCost" name="material_cost" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Total Cost -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="totalCost">Total Cost</label>
                                <input type="number" class="form-control" id="totalCost" name="total_cost" min="0" step="0.01" placeholder="0.00" readonly>
                            </div>
                        </div>

                        <!-- Customer Feedback -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="customerFeedback">Customer Feedback</label>
                                <textarea class="form-control" id="customerFeedback" name="customer_feedback" rows="3" 
                                          placeholder="Record any feedback, comments, or concerns expressed by the customer..."></textarea>
                            </div>
                        </div>

                        <!-- Recommendations -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="recommendations">Recommendations</label>
                                <textarea class="form-control" id="recommendations" name="recommendations" rows="3" 
                                          placeholder="Suggest future maintenance, repairs, or improvements..."></textarea>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="additionalNotes">Additional Notes</label>
                                <textarea class="form-control" id="additionalNotes" name="additional_notes" rows="3" 
                                          placeholder="Any other relevant information or observations..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Create Service Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Report Modal -->
<div class="modal fade" id="editServiceReportModal" tabindex="-1" aria-labelledby="editServiceReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServiceReportModalLabel">
                    <i class="bi bi-pencil-square"></i> Edit Service Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editServiceReportForm">
                <input type="hidden" id="editServiceReportId" name="id">
                <div class="modal-body">
                    <div class="row">
                        <!-- Service Appointment Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editServiceAppointmentId">Choose Service Appointment <span class="text-danger">*</span></label>
                                <select class="form-control" id="editServiceAppointmentId" name="service_appointment_id" required>
                                    <option value="">Select Service Appointment</option>
                                    <?php if (!empty($service_appointments)): ?>
                                        <?php foreach ($service_appointments as $appointment): ?>
                                            <option value="<?= $appointment['id'] ?>"
                                                    data-work-order="<?= $appointment['work_order_id'] ?>"
                                                    data-technician="<?= $appointment['technician_id'] ?>">
                                                <?= $appointment['work_order_title'] ?> - <?= $appointment['appointment_date'] ?> <?= $appointment['appointment_time'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Work Order (Auto-filled) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editWorkOrderId">Work Order</label>
                                <select class="form-control" id="editWorkOrderId" name="work_order_id" readonly>
                                    <option value="">Select from Service Appointment</option>
                                    <?php if (!empty($work_orders)): ?>
                                        <?php foreach ($work_orders as $order): ?>
                                            <option value="<?= $order['id'] ?>"><?= $order['work_order_number'] ?> - <?= $order['summary'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Technician (Auto-filled) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editTechnicianId">Technician</label>
                                <select class="form-control" id="editTechnicianId" name="technician_id" readonly>
                                    <option value="">Select from Service Appointment</option>
                                    <?php if (!empty($technicians)): ?>
                                        <?php foreach ($technicians as $technician): ?>
                                            <option value="<?= $technician['id'] ?>"><?= $technician['first_name'] ?> <?= $technician['last_name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Report Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editReportDate">Report Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editReportDate" name="report_date" required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editStatus">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="editStatus" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="completed">Completed</option>
                                    <option value="submitted">Submitted</option>
                                    <option value="approved">Approved</option>
                                </select>
                            </div>
                        </div>

                        <!-- Service Type -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editServiceType">Service Type</label>
                                <select class="form-control" id="editServiceType" name="service_type">
                                    <option value="">Select Service Type</option>
                                    <option value="installation">Installation</option>
                                    <option value="repair">Repair</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="inspection">Inspection</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Work Summary -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editWorkSummary">Work Summary <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="editWorkSummary" name="work_summary" rows="4" 
                                          placeholder="Describe the work performed, issues encountered, and solutions implemented..." required></textarea>
                            </div>
                        </div>

                        <!-- Parts Used -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editPartsUsed">Parts Used</label>
                                <textarea class="form-control" id="editPartsUsed" name="parts_used" rows="3" 
                                          placeholder="List all parts, materials, and quantities used during the service..."></textarea>
                            </div>
                        </div>

                        <!-- Time Spent -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editTimeSpent">Time Spent (hours)</label>
                                <input type="number" class="form-control" id="editTimeSpent" name="time_spent" min="0" step="0.5" placeholder="e.g., 2.5">
                            </div>
                        </div>

                        <!-- Labor Cost -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editLaborCost">Labor Cost</label>
                                <input type="number" class="form-control" id="editLaborCost" name="labor_cost" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Material Cost -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editMaterialCost">Material Cost</label>
                                <input type="number" class="form-control" id="editMaterialCost" name="material_cost" min="0" step="0.01" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Total Cost -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editTotalCost">Total Cost</label>
                                <input type="number" class="form-control" id="editTotalCost" name="total_cost" min="0" step="0.01" placeholder="0.00" readonly>
                            </div>
                        </div>

                        <!-- Customer Feedback -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editCustomerFeedback">Customer Feedback</label>
                                <textarea class="form-control" id="editCustomerFeedback" name="customer_feedback" rows="3" 
                                          placeholder="Record any feedback, comments, or concerns expressed by the customer..."></textarea>
                            </div>
                        </div>

                        <!-- Recommendations -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editRecommendations">Recommendations</label>
                                <textarea class="form-control" id="editRecommendations" name="recommendations" rows="3" 
                                          placeholder="Suggest future maintenance, repairs, or improvements..."></textarea>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editAdditionalNotes">Additional Notes</label>
                                <textarea class="form-control" id="editAdditionalNotes" name="additional_notes" rows="3" 
                                          placeholder="Any other relevant information or observations..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Service Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
