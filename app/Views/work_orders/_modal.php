<!-- Create/Edit Work Order Modal -->
<div class="modal fade" id="createWorkOrderModal" tabindex="-1" aria-labelledby="createWorkOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWorkOrderModalLabel">Create Work Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="workOrderForm">
                <div class="modal-body">
                    <input type="hidden" id="workOrderId" name="id">
                    
                    <!-- Work Order Summary -->
                    <h6 class="mb-3 text-primary">Work Order Summary</h6>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="summary" class="form-label">Summary <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="summary" name="summary" rows="3" required placeholder="Enter work order summary"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="">Select</option>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="">Select</option>
                                    <option value="service" selected>Service</option>
                                    <option value="corrective">Corrective</option>
                                    <option value="preventive">Preventive</option>
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
                                <label for="company_id" class="form-label">Company</label>
                                <div class="input-group">
                                    <select class="form-select" id="company_id" name="company_id">
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
                                        <option value="">Search Contact</option>                                        <?php foreach ($contacts as $contact): ?>
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
                    
                    <!-- Asset -->
                    <h6 class="mb-3 text-primary">Asset</h6>
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
                    
                    <!-- Services -->
                    <h6 class="mb-3 text-primary">Services</h6>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="servicesTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40%">Service</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Rate</th>
                                            <th width="15%">Amount</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select service-select" name="services[0][service_id]">
                                                    <option value="">Select Service</option>
                                                    <?php if (isset($services)): ?>
                                                    <?php foreach ($services as $service): ?>
                                                    <option value="<?= $service['id'] ?>" data-rate="<?= $service['rate'] ?? 0 ?>"><?= esc($service['service_name']) ?></option>
                                                    <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity-input" name="services[0][quantity]" value="1" min="1" step="0.01">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control rate-input" name="services[0][rate]" value="0.00" min="0" step="0.01">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control amount-input" name="services[0][amount]" value="0.00" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-service" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addServiceBtn">
                                <i class="bi bi-plus-circle"></i> New Line
                            </button>
                        </div>
                    </div>                    
                    <!-- Parts -->
                    <h6 class="mb-3 text-primary">Parts</h6>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="partsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40%">Part</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Rate</th>
                                            <th width="15%">Amount</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="parts[0][part_name]" placeholder="Part name">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity-input" name="parts[0][quantity]" value="1" min="1" step="0.01">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control rate-input" name="parts[0][rate]" value="0.00" min="0" step="0.01">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control amount-input" name="parts[0][amount]" value="0.00" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-part" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addPartBtn">
                                <i class="bi bi-plus-circle"></i> New Line
                            </button>
                        </div>
                    </div>
                    
                    <!-- Totals -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <!-- Left side can be used for additional fields if needed -->
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <strong>Sub Total</strong>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span id="subTotalDisplay">CA$ 0.00</span>
                                            <input type="hidden" name="sub_total" id="subTotal" value="0.00">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <strong>Tax Amount</strong>
                                        </div>
                                        <div class="col-6 text-end">
                                            <span id="taxAmountDisplay">CA$ 0.00</span>
                                            <input type="hidden" name="tax_amount" id="taxAmount" value="0.00">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label for="discount" class="form-label">Discount</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control" id="discount" name="discount" value="0" min="0" step="0.01">
                                                <select class="form-select" id="discountType" style="max-width: 80px;">
                                                    <option value="amount">CA$</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label for="adjustment" class="form-label">Adjustment</label>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-sm">
                                                <input type="number" class="form-control" id="adjustment" name="adjustment" value="0" step="0.01">
                                                <span class="input-group-text">CA$</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Grand Total</strong>
                                        </div>
                                        <div class="col-6 text-end">
                                            <strong id="grandTotalDisplay">CA$ 0.00</strong>
                                            <input type="hidden" name="grand_total" id="grandTotal" value="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <!-- Service Tasks -->
                    <h6 class="mb-3 text-primary">Service Tasks</h6>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="text-muted text-center py-3">
                                <p>No Records Found</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Skills -->
                    <h6 class="mb-3 text-primary">Skills</h6>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="skillsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="70%">Skill</th>
                                            <th width="30%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="skills[0][skill_name]" placeholder="Skill name">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-skill" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addSkillBtn">
                                <i class="bi bi-plus-circle"></i> New Line
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveWorkOrderBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>