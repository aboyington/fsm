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

<!-- Add Currency Modal -->
<div class="modal fade" id="addCurrencyModal" tabindex="-1" aria-labelledby="addCurrencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCurrencyModalLabel">Add Currency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCurrencyForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="currency_name" class="form-label">Currency Name</label>
                        <input type="text" class="form-control" id="currency_name" name="name" required>
                        <small class="text-muted">Example: US Dollar - USD</small>
                    </div>
                    <div class="mb-3">
                        <label for="currency_symbol" class="form-label">Symbol</label>
                        <input type="text" class="form-control" id="currency_symbol" name="symbol" required>
                        <small class="text-muted">Example: $</small>
                    </div>
                    <div class="mb-3">
                        <label for="currency_iso_code" class="form-label">ISO Code</label>
                        <input type="text" class="form-control" id="currency_iso_code" name="iso_code" maxlength="3" required>
                        <small class="text-muted">Example: USD</small>
                    </div>
                    <div class="mb-3">
                        <label for="exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="number" class="form-control" id="exchange_rate" name="exchange_rate" step="0.0000000001" required>
                        <small class="text-muted">Exchange rate relative to base currency</small>
                    </div>
                    <div class="mb-3">
                        <label for="thousand_separator" class="form-label">Thousand Separator</label>
                        <select class="form-select" id="thousand_separator" name="thousand_separator">
                            <option value=",">Comma (,)</option>
                            <option value=".">Period (.)</option>
                            <option value=" ">Space</option>
                            <option value="">None</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="decimal_spaces" class="form-label">Decimal Spaces</label>
                        <input type="number" class="form-control" id="decimal_spaces" name="decimal_spaces" min="0" max="10" value="2" required>
                    </div>
                    <div class="mb-3">
                        <label for="decimal_separator" class="form-label">Decimal Separator</label>
                        <select class="form-select" id="decimal_separator" name="decimal_separator">
                            <option value=".">Period (.)</option>
                            <option value=",">Comma (,)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Currency</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Currency Modal -->
<div class="modal fade" id="editCurrencyModal" tabindex="-1" aria-labelledby="editCurrencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCurrencyModalLabel">Edit Currency</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCurrencyForm">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_currency_id" name="id">
                <div class="modal-body">
                    <h6 class="mb-3">Basic Information</h6>
                    <div class="mb-3">
                        <label for="edit_exchange_rate" class="form-label">Exchange Rate</label>
                        <input type="number" class="form-control" id="edit_exchange_rate" name="exchange_rate" step="0.0000000001" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_thousand_separator" class="form-label">Thousand Separator</label>
                        <select class="form-select" id="edit_thousand_separator" name="thousand_separator">
                            <option value=",">Comma</option>
                            <option value=".">Period</option>
                            <option value=" ">Space</option>
                            <option value="">None</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_decimal_spaces" class="form-label">Decimal Spaces</label>
                        <input type="number" class="form-control" id="edit_decimal_spaces" name="decimal_spaces" min="0" max="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_decimal_separator" class="form-label">Decimal Separator</label>
                        <select class="form-select" id="edit_decimal_separator" name="decimal_separator">
                            <option value=".">Period</option>
                            <option value=",">Comma</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add Currency
document.getElementById('addCurrencyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const response = await fetch('<?= base_url('settings/currency/store') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('addCurrencyModal')).hide();
            // Reload page
            location.reload();
        } else {
            alert(result.message || 'Failed to add currency');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while adding the currency');
    }
});

// Edit Currency
async function editCurrency(id) {
    try {
        // Fetch currency data
        const response = await fetch(`<?= base_url('settings/currency/get') ?>/${id}`, {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            const currency = result.currency;
            document.getElementById('edit_currency_id').value = currency.id;
            document.getElementById('edit_exchange_rate').value = currency.exchange_rate;
            document.getElementById('edit_thousand_separator').value = currency.thousand_separator;
            document.getElementById('edit_decimal_spaces').value = currency.decimal_spaces;
            document.getElementById('edit_decimal_separator').value = currency.decimal_separator;
            
            // Show modal
            new bootstrap.Modal(document.getElementById('editCurrencyModal')).show();
        } else {
            alert('Failed to load currency data');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while loading the currency');
    }
}

// Update Currency
document.getElementById('editCurrencyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    const id = data.id;
    delete data.id;
    
    try {
        const response = await fetch(`<?= base_url('settings/currency/update') ?>/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('editCurrencyModal')).hide();
            // Reload page
            location.reload();
        } else {
            alert(result.message || 'Failed to update currency');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while updating the currency');
    }
});
</script>
<?= $this->endSection() ?>
