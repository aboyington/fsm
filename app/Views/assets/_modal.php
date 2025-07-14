<!-- Create/Edit Asset Modal -->
<div class="modal fade" id="createAssetModal" tabindex="-1" aria-labelledby="createAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAssetModalLabel">Create Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assetForm">
                <div class="modal-body">
                    <input type="hidden" id="assetId" name="id">
                    
                    <!-- Asset Details Section -->
                    <h6 class="mb-3">Asset Details</h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="asset_name" class="form-label">Asset Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="asset_name" name="asset_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="asset_number" class="form-label">Asset Number</label>
                                <input type="text" class="form-control" id="asset_number" name="asset_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product" class="form-label">Product</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="product" name="product" placeholder="Search Product">
                                    <button class="btn btn-outline-secondary" type="button" id="productSearchBtn">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="parent_asset" class="form-label">Parent Asset</label>
                                <div class="input-group">
                                    <select class="form-select" id="parent_asset" name="parent_asset">
                                        <option value="">Search Parent Asset</option>
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" id="parentAssetSearchBtn">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="giai" class="form-label">GIAI</label>
                                <input type="text" class="form-control" id="giai" name="giai">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date Fields -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="ordered_date" class="form-label">Ordered Date</label>
                                <input type="date" class="form-control" id="ordered_date" name="ordered_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="installation_date" class="form-label">Installation Date</label>
                                <input type="date" class="form-control" id="installation_date" name="installation_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="purchased_date" class="form-label">Purchased Date</label>
                                <input type="date" class="form-control" id="purchased_date" name="purchased_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="warranty_expiration" class="form-label">Warranty Expiration</label>
                                <input type="date" class="form-control" id="warranty_expiration" name="warranty_expiration">
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Section -->
                    <hr>
                    <h6 class="mb-3">Contact Details</h6>
                    
                    <div class="row">
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
                                    <button class="btn btn-outline-secondary" type="button" id="companySearchBtn">
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
                                        <option value="<?= $contact['id'] ?>" data-company="<?= $contact['company_id'] ?>">
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
                                    <button class="btn btn-outline-secondary" type="button" id="contactSearchBtn">
                                        <i class="bi bi-person"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <hr>
                    <h6 class="mb-3">Address</h6>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <div class="input-group">
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter address details"></textarea>
                            <button class="btn btn-outline-secondary" type="button" id="addressSearchBtn">
                                <i class="bi bi-geo-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="retired">Retired</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="saveAssetBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
