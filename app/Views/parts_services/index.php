<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-tools"></i> Parts & Services Management
                </h1>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal" onclick="setModalType('part')">
                        <i class="bi bi-plus-circle"></i> Add Part
                    </button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal" onclick="setModalType('service')">
                        <i class="bi bi-plus-circle"></i> Add Service
                    </button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download"></i> More
                        </button>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">Export</h6></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('parts')">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Export Parts
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('services')">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Export Services
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('all')">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Export All
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Import</h6></li>
                            <li><a class="dropdown-item" href="#" onclick="showImportModal('parts')">
                                <i class="bi bi-file-earmark-arrow-up"></i> Import Parts
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showImportModal('services')">
                                <i class="bi bi-file-earmark-arrow-up"></i> Import Services
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showImportModal('all')">
                                <i class="bi bi-file-earmark-arrow-up"></i> Import All
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="downloadTemplate('parts')">
                                <i class="bi bi-file-earmark-text"></i> Download Parts Template
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="downloadTemplate('services')">
                                <i class="bi bi-file-earmark-text"></i> Download Services Template
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Filters and Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Parts & Services Inventory</h5>
                </div>
                <div class="card-body">
                    <!-- Filter Controls -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="typeFilter" class="form-label">Filter by Type:</label>
                            <select class="form-select" id="typeFilter">
                                <option value="all">All Items</option>
                                <option value="parts">Parts Only</option>
                                <option value="services">Services Only</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="categoryFilter" class="form-label">Filter by Category:</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="all">All Categories</option>
                                <option value="CCTV">CCTV</option>
                                <option value="Alarm">Alarm</option>
                                <option value="Access Control">Access Control</option>
                                <option value="I.T">I.T</option>
                                <option value="Networking">Networking</option>
                                <option value="Security">Security</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Filter by Status:</label>
                            <select class="form-select" id="statusFilter">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchInput" class="form-label">Search:</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search by name or SKU...">
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="partsServicesTable">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock/Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Most Used Services</h5>
                </div>
                <div class="card-body">
                    <div id="mostUsedServices">
                        <!-- Will be populated via AJAX -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Most Used Parts</h5>
                </div>
                <div class="card-body">
                    <div id="mostUsedParts">
                        <!-- Will be populated via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Add New Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm">
                <div class="modal-body">
                    <input type="hidden" id="itemType" name="type" value="part">
                    <input type="hidden" id="itemId" name="id" value="">
                    
                    <!-- Common Fields -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemName" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="itemName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemSku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="itemSku" name="sku" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="itemCategory" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="CCTV">CCTV</option>
                                    <option value="Alarm">Alarm</option>
                                    <option value="Access Control">Access Control</option>
                                    <option value="I.T">I.T</option>
                                    <option value="Networking">Networking</option>
                                    <option value="Security">Security</option>
                                    <option value="General">General</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemUnitPrice" class="form-label">Unit Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="itemUnitPrice" name="unit_price" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemCostPrice" class="form-label">Cost Price</label>
                                <input type="number" class="form-control" id="itemCostPrice" name="cost_price" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="itemDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="itemDescription" name="description" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Part-specific Fields -->
                    <div id="partFields" style="display: none;">
                        <h6 class="mb-3 text-primary">Part Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantityOnHand" class="form-label">Quantity on Hand <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantityOnHand" name="quantity_on_hand">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimumStock" class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="minimumStock" name="minimum_stock">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <input type="text" class="form-control" id="supplier" name="supplier">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturer" class="form-label">Manufacturer</label>
                                    <input type="text" class="form-control" id="manufacturer" name="manufacturer">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="manufacturerPartNumber" class="form-label">Manufacturer Part Number</label>
                                    <input type="text" class="form-control" id="manufacturerPartNumber" name="manufacturer_part_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="warrantyPeriod" class="form-label">Warranty Period (months)</label>
                                    <input type="number" class="form-control" id="warrantyPeriod" name="warranty_period">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (lbs)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" step="0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dimensions" class="form-label">Dimensions</label>
                                    <input type="text" class="form-control" id="dimensions" name="dimensions" placeholder="L x W x H">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service-specific Fields -->
                    <div id="serviceFields" style="display: none;">
                        <h6 class="mb-3 text-success">Service Information</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="durationMinutes" class="form-label">Duration (minutes)</label>
                                    <input type="number" class="form-control" id="durationMinutes" name="duration_minutes">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="isTaxable" name="is_taxable" value="1">
                                        <label class="form-check-label" for="isTaxable">
                                            Taxable Service
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="isActive" name="is_active" value="1" checked>
                                    <label class="form-check-label" for="isActive">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="importType" name="type" value="">
                    
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Choose CSV File</label>
                        <input type="file" class="form-control" id="importFile" name="import_file" accept=".csv" required>
                        <div class="form-text">Please select a CSV file to import. Make sure the file follows the required format.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="overwriteExisting" name="overwrite_existing" value="1">
                            <label class="form-check-label" for="overwriteExisting">
                                Overwrite existing records with same SKU
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Import Instructions:</h6>
                        <ul class="mb-0">
                            <li>Download the template first to see the required format</li>
                            <li>Make sure your CSV file includes all required fields</li>
                            <li>SKU must be unique unless overwrite is enabled</li>
                            <li>Use the exact category names as shown in the template</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="importSubmitBtn">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/parts-services.js') ?>"></script>
<?= $this->endSection() ?>
