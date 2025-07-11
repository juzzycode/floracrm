<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Inventory</h2>
        <a href="/inventory/add" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Item
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Description</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $item): ?>
                    <tr>
                        <td><?= $item['sku'] ?></td>
                        <td><?= $item['description'] ?></td>
                        <td>
                            <?php 
                            $vendorModel = model('VendorModel');
                            $vendor = $vendorModel->find($item['vendor_id']);
                            echo $vendor ? $vendor['name'] : 'Unknown';
                            ?>
                        </td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['quantity_on_hand'] ?></td>
                        <td>
                            <a href="/inventory/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>