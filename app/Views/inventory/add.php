<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Add Inventory Item</h2>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', session()->getFlashdata('errors')) ?>
        </div>
    <?php endif; ?>
    
    <form action="/inventory/save" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="vendor_id" class="form-label">Vendor</label>
            <select class="form-control" id="vendor_id" name="vendor_id" required>
                <option value="">Select Vendor</option>
                <?php foreach ($vendors as $vendor): ?>
                    <option value="<?= $vendor['id'] ?>"><?= $vendor['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control" id="sku" name="sku" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" required>
        </div>
        
        <!-- Add other fields similarly -->
        
        <button type="submit" class="btn btn-primary">Save Item</button>
    </form>
</div>
<?= $this->endSection() ?>