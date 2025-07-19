<!-- Create/Edit Request Modal -->
<div class="modal fade" id="createRequestModal" tabindex="-1" aria-labelledby="createRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRequestModalLabel">Create Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="requestForm">
                <div class="modal-body">
                    <input type="hidden" id="requestId" name="id">
                    
                    <!-- Request Details -->
                    <h6 class="mb-3 text-primary">Request Details</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="request_name" class="form-label">Request Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="request_name" name="request_name" required placeholder="Enter request name" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" selected>Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" placeholder="MMM DD, YYYY">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Details -->
                    <h6 class="mb-3 text-primary">Contact Details</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_id" class="form-label">Company</label>
                                <div class="input-group">
                                    <select class="form-select" id="client_id" name="client_id">
                                        <option value="">Search Company</option>
                                        <?php foreach ($companies as $company): ?>
                                        <option value="<?= $company['id'] ?>"><?= esc($company['client_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" title="Search Company">
                                        <i class="bi bi-building"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_id" class="form-label">Contact</label>
                                <div class="input-group">
                                    <select class="form-select" id="contact_id" name="contact_id">
                                        <option value="">Search Contact</option>
                                        <?php foreach ($contacts as $contact): ?>
                                        <option value="<?= $contact['id'] ?>" data-company="<?= $contact['company_id'] ?>" data-email="<?= esc($contact['email']) ?>" data-phone="<?= esc($contact['phone']) ?>" data-mobile="<?= esc($contact['mobile']) ?>">
                                            <?= esc($contact['first_name'] . ' ' . $contact['last_name']) ?>
                                            <?php if ($contact['company_id']): ?>
                                                <?php 
                                                $companyName = '';
                                                foreach ($companies as $company) {
                                                    if ($company['id'] == $contact['company_id']) {
                                                        $companyName = $company['client_name'];
                                                        break;
                                                    }
                                                }
                                                ?>
                                                (<?= esc($companyName) ?>)
                                            <?php endif; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" title="Search Contact">
                                        <i class="bi bi-person"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email address">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone number">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile number">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Asset Details -->
                    <h6 class="mb-3 text-primary">Asset Details</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="asset_id" class="form-label">Asset</label>
                                <div class="input-group">
                                    <select class="form-select" id="asset_id" name="asset_id">
                                        <option value="">Search Asset</option>
                                        <?php if (isset($assets)): ?>
                                        <?php foreach ($assets as $asset): ?>
                                        <option value="<?= $asset['id'] ?>"><?= esc($asset['asset_name']) ?> - <?= esc($asset['model']) ?></option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" title="Search Asset">
                                        <i class="bi bi-gear"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address -->
                    <h6 class="mb-3 text-primary">Address</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="service_address" class="form-label">Service Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="service_address" name="service_address" placeholder="Service address">
                                    <button class="btn btn-outline-secondary" type="button" title="Location">
                                        <i class="bi bi-geo-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="billing_address" class="form-label">Billing Address</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="billing_address" name="billing_address" placeholder="Billing address">
                                    <button class="btn btn-outline-secondary" type="button" title="Location">
                                        <i class="bi bi-geo-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preference -->
                    <h6 class="mb-3 text-primary">Preference</h6>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="preferred_date_1" class="form-label">Preferred Date 1</label>
                                <input type="date" class="form-control" id="preferred_date_1" name="preferred_date_1" placeholder="MMM DD, YYYY">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="preferred_date_2" class="form-label">Preferred Date 2</label>
                                <input type="date" class="form-control" id="preferred_date_2" name="preferred_date_2" placeholder="MMM DD, YYYY">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="preferred_time" class="form-label">Preferred Time</label>
                                <select class="form-select" id="preferred_time" name="preferred_time">
                                    <option value="">Select</option>
                                    <option value="-none-">-None-</option>
                                    <option value="any">Any time</option>
                                    <option value="morning">Morning</option>
                                    <option value="afternoon">Afternoon</option>
                                    <option value="evening">Evening</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="preference_note" class="form-label">Preference Note</label>
                                <textarea class="form-control" id="preference_note" name="preference_note" rows="3" placeholder="Additional preference notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the service request details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveRequestBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
