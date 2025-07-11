<?= $this->extend('settings/layout') ?>

<?= $this->section('settings-content') ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Currency</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Manage your global business operations by setting up multiple currencies and their exchange rates over here. Currencies set up over here can be applied to your customer estimates and invoices.</p>
        
        <div class="text-end mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCurrencyModal">
                <i class="bi bi-plus-circle"></i> Add Currency
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Symbol</th>
                        <th>Exchange Rate</th>
                        <th>ISO CODE</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($currencies)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-currency-exchange fs-1 d-block mb-2"></i>
                                No currencies configured yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($currencies as $currency): ?>
                        <tr>
                            <td>
                                <?= esc($currency['name']) ?>
                                <?php if ($currency['is_base']): ?>
                                    <span class="badge bg-primary ms-2">Base Currency</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($currency['symbol']) ?></td>
                            <td><?= number_format($currency['exchange_rate'], 10) ?></td>
                            <td><?= esc($currency['iso_code']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editCurrency(<?= $currency['id'] ?>)">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Currency Modal -->
<div class="modal" id="addCurrencyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Currency</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="/currency/edit" method="post">
                    <input type="hidden" name="id" value="" />
                    <div class="form-group">
                        <label for="exchange_rate">Exchange Rate</label>
                        <input type="text" class="form-control" name="exchange_rate" required />
                    </div>
                    <div class="form-group">
                        <label for="thousand_separator">Thousand Separator</label>
                        <select class="form-control" name="thousand_separator">
                            <option value=",">Comma</option>
                            <option value=".">Period</option>
                            <option value="">None</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="decimal_spaces">Decimal Spaces</label>
                        <input type="text" class="form-control" name="decimal_spaces" required />
                    </div>
                    <div class="form-group">
                        <label for="decimal_separator">Decimal Separator</label>
                        <select class="form-control" name="decimal_separator">
                            <option value=".">Period</option>
                            <option value=",">Comma</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

