<!-- Create Scheduled Maintenance Modal -->
<div class="modal fade" id="createScheduledMaintenanceModal" tabindex="-1" aria-labelledby="createScheduledMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createScheduledMaintenanceModalLabel">Create Scheduled Maintenance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createScheduledMaintenanceForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="schedule_type" class="form-label">Schedule Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="schedule_type" name="schedule_type" required>
                                    <option value="">Select Schedule Type</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="frequency" class="form-label">Frequency</label>
                                <div class="input-group">
                                    <span class="input-group-text">Every</span>
                                    <input type="number" class="form-control" id="frequency" name="frequency" value="1" min="1">
                                    <span class="input-group-text" id="frequency-unit">month(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_id" class="form-label">Client</label>
                                <select class="form-select" id="client_id" name="client_id">
                                    <option value="">Select Client</option>
                                    <?php if (isset($clients) && is_array($clients)): ?>
                                        <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['id'] ?>"><?= esc($client['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="asset_id" class="form-label">Asset</label>
                                <select class="form-select" id="asset_id" name="asset_id">
                                    <option value="">Select Asset</option>
                                    <?php if (isset($assets) && is_array($assets)): ?>
                                        <?php foreach ($assets as $asset): ?>
                                            <option value="<?= $asset['id'] ?>"><?= esc($asset['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assigned To</label>
                                <select class="form-select" id="assigned_to" name="assigned_to">
                                    <option value="">Select Technician</option>
                                    <?php if (isset($technicians) && is_array($technicians)): ?>
                                        <?php foreach ($technicians as $technician): ?>
                                            <option value="<?= $technician['id'] ?>"><?= esc($technician['first_name'] . ' ' . $technician['last_name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="territory_id" class="form-label">Territory</label>
                                <select class="form-select" id="territory_id" name="territory_id">
                                    <option value="">Select Territory</option>
                                    <?php if (isset($territories) && is_array($territories)): ?>
                                        <?php foreach ($territories as $territory): ?>
                                            <option value="<?= $territory['id'] ?>"><?= esc($territory['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estimated_duration" class="form-label">Estimated Duration (minutes)</label>
                        <input type="number" class="form-control" id="estimated_duration" name="estimated_duration" min="1">
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" selected>Draft</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="paused">Paused</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Scheduled Maintenance</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Scheduled Maintenance Modal -->
<div class="modal fade" id="editScheduledMaintenanceModal" tabindex="-1" aria-labelledby="editScheduledMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editScheduledMaintenanceModalLabel">Edit Scheduled Maintenance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editScheduledMaintenanceForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <!-- Same fields as create modal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_schedule_type" class="form-label">Schedule Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_schedule_type" name="schedule_type" required>
                                    <option value="">Select Schedule Type</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_frequency" class="form-label">Frequency</label>
                                <div class="input-group">
                                    <span class="input-group-text">Every</span>
                                    <input type="number" class="form-control" id="edit_frequency" name="frequency" value="1" min="1">
                                    <span class="input-group-text" id="edit-frequency-unit">month(s)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_priority" class="form-label">Priority</label>
                                <select class="form-select" id="edit_priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_client_id" class="form-label">Client</label>
                                <select class="form-select" id="edit_client_id" name="client_id">
                                    <option value="">Select Client</option>
                                    <?php if (isset($clients) && is_array($clients)): ?>
                                        <?php foreach ($clients as $client): ?>
                                            <option value="<?= $client['id'] ?>"><?= esc($client['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_asset_id" class="form-label">Asset</label>
                                <select class="form-select" id="edit_asset_id" name="asset_id">
                                    <option value="">Select Asset</option>
                                    <?php if (isset($assets) && is_array($assets)): ?>
                                        <?php foreach ($assets as $asset): ?>
                                            <option value="<?= $asset['id'] ?>"><?= esc($asset['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_assigned_to" class="form-label">Assigned To</label>
                                <select class="form-select" id="edit_assigned_to" name="assigned_to">
                                    <option value="">Select Technician</option>
                                    <?php if (isset($technicians) && is_array($technicians)): ?>
                                        <?php foreach ($technicians as $technician): ?>
                                            <option value="<?= $technician['id'] ?>"><?= esc($technician['first_name'] . ' ' . $technician['last_name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_territory_id" class="form-label">Territory</label>
                                <select class="form-select" id="edit_territory_id" name="territory_id">
                                    <option value="">Select Territory</option>
                                    <?php if (isset($territories) && is_array($territories)): ?>
                                        <?php foreach ($territories as $territory): ?>
                                            <option value="<?= $territory['id'] ?>"><?= esc($territory['name']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_estimated_duration" class="form-label">Estimated Duration (minutes)</label>
                        <input type="number" class="form-control" id="edit_estimated_duration" name="estimated_duration" min="1">
                    </div>

                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="draft">Draft</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="paused">Paused</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Scheduled Maintenance</button>
                </div>
            </form>
        </div>
    </div>
</div>
