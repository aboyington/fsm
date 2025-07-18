<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid" style="background-color: #ffffff; min-height: 100vh; margin: 0; padding-top: 20px;">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item">Customers</li>
                    <li class="breadcrumb-item active" aria-current="page">Assets</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0">Assets</h1>
        </div>
        <div class="col-auto">
            <?php if (!empty($assets)): ?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAssetModal">
                <i class="bi bi-plus-circle"></i> Create Asset
            </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($assets)): ?>
    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
<div class="text-center py-5">
                    <!-- Asset Illustration -->
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 p-4 shadow-sm" style="width: 200px; height: 140px;">
                            <!-- Asset management illustration -->
                            <div class="position-relative">
                                <!-- Main asset/equipment -->
                                <div class="bg-primary rounded-2 me-2" style="width: 60px; height: 80px; position: relative;">
                                    <!-- Control panel -->
                                    <div class="bg-light rounded-1" style="width: 40px; height: 20px; position: absolute; top: 10px; left: 10px;">
                                        <div class="bg-success rounded-circle" style="width: 4px; height: 4px; position: absolute; top: 3px; left: 3px;"></div>
                                        <div class="bg-warning rounded-circle" style="width: 4px; height: 4px; position: absolute; top: 3px; left: 10px;"></div>
                                        <div class="bg-danger rounded-circle" style="width: 4px; height: 4px; position: absolute; top: 3px; left: 17px;"></div>
                                        <div class="bg-secondary" style="width: 30px; height: 2px; position: absolute; bottom: 4px; left: 5px;"></div>
                                    </div>
                                    <!-- Base -->
                                    <div class="bg-secondary rounded-1" style="width: 60px; height: 8px; position: absolute; bottom: 0; left: 0;"></div>
                                </div>
                                
                                <!-- Documents/tracking -->
                                <div class="bg-white border rounded-2 shadow-sm" style="width: 50px; height: 60px; position: absolute; top: 10px; left: -45px; padding: 6px;">
                                    <!-- Document lines -->
                                    <div class="bg-secondary rounded" style="width: 100%; height: 2px; margin-bottom: 3px;"></div>
                                    <div class="bg-secondary rounded" style="width: 80%; height: 2px; margin-bottom: 3px;"></div>
                                    <div class="bg-secondary rounded" style="width: 90%; height: 2px; margin-bottom: 3px;"></div>
                                    <div class="bg-secondary rounded" style="width: 70%; height: 2px; margin-bottom: 3px;"></div>
                                    
                                    <!-- Asset tracking section -->
                                    <div class="mt-2 pt-2 border-top">
                                        <div class="bg-primary rounded" style="width: 60%; height: 2px; margin-bottom: 2px;"></div>
                                        <div class="bg-primary rounded" style="width: 40%; height: 2px; margin-bottom: 2px;"></div>
                                        <div class="bg-primary rounded" style="width: 50%; height: 2px;"></div>
                                    </div>
                                </div>
                                
                                <!-- Additional asset -->
                                <div class="bg-info rounded-2" style="width: 40px; height: 60px; position: absolute; top: 20px; right: -35px;">
                                    <!-- Screen -->
                                    <div class="bg-dark rounded-1" style="width: 25px; height: 15px; position: absolute; top: 8px; left: 7px;"></div>
                                    <!-- Keyboard -->
                                    <div class="bg-light rounded-1" style="width: 30px; height: 8px; position: absolute; bottom: 10px; left: 5px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <h2 class="h4 mb-3">Assets</h2>
                    <p class="text-muted mb-4">
                        Asset Module is designed to help businesses manage and track customer-owned products or equipment. It helps with tracking the service and repair history of each item, maintaining a complete record of all services performed. This module provides valuable insights into how frequently an asset breaks down or requires maintenance, improving service planning.
                    </p>
                    
                    <!-- Create Asset Button -->
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#createAssetModal">
                        <i class="bi bi-plus-circle me-2"></i>Create Asset
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Assets List -->
    <div class="row">
        <div class="col-12">
            <!-- Filter Bar -->
<div class="bg-light p-3 rounded mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="searchAssets" placeholder="Search assets...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="retired">Retired</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="companyFilter">
                                <option value="">All Companies</option>
                                <?php foreach ($companies as $company): ?>
                                <option value="<?= $company['id'] ?>"><?= esc($company['client_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="text-muted">
                                Total: <?= $total_assets ?> | 
                                Active: <?= $active_assets ?> | 
                                Inactive: <?= $inactive_assets ?>
                            </span>
                </div>
            </div>
        </div>
    </div>
            <!-- Assets Table -->
<div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Asset Name</th>
                                <th>Asset Number</th>
                                <th>Product</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Warranty</th>
                                <th>Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assetsTableBody">
                            <?php foreach ($assets as $asset): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-gear"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= esc($asset['asset_name']) ?></h6>
                                            <small class="text-muted">ID: <?= $asset['id'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($asset['asset_number'] ?? '-') ?></td>
                                <td><?= esc($asset['product'] ?? '-') ?></td>
                                <td>
                                    <?php if ($asset['company_name']): ?>
                                        <span class="badge bg-light text-dark"><?= esc($asset['company_name']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($asset['contact_name']): ?>
                                        <?= esc($asset['contact_name']) ?>
                                        <?php if ($asset['contact_phone']): ?>
                                            <br><small class="text-muted"><?= esc($asset['contact_phone']) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $statusClass = [
                                        'active' => 'bg-success',
                                        'inactive' => 'bg-secondary', 
                                        'maintenance' => 'bg-warning text-dark',
                                        'retired' => 'bg-danger'
                                    ];
                                    ?>
                                    <span class="badge <?= $statusClass[$asset['status']] ?? 'bg-secondary' ?>">
                                        <?= ucfirst($asset['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($asset['warranty_expiration']): ?>
                                        <?php 
                                        $warrantyDate = strtotime($asset['warranty_expiration']);
                                        $today = time();
                                        $isExpired = $warrantyDate < $today;
                                        $isExpiringSoon = ($warrantyDate - $today) < (30 * 24 * 60 * 60);
                                        ?>
                                        <span class="<?= $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : 'text-muted') ?>">
                                            <?= date('M j, Y', $warrantyDate) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('M j, Y', strtotime($asset['created_at'])) ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="editAsset(<?= $asset['id'] ?>)" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteAsset(<?= $asset['id'] ?>)" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->include('assets/_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/assets.js') ?>"></script>
<?= $this->endSection() ?>
