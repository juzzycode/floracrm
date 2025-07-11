<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Edit Discount Group</h2>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', session()->getFlashdata('errors')) ?>
        </div>
    <?php endif; ?>
    
    <form action="/admin/discount-groups/update/<?= $group['id'] ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="name" class="form-label">Group Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $group['name'] ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="discount_percent" class="form-label">Discount Percentage</label>
            <input type="number" step="0.01" class="form-control" id="discount_percent" name="discount_percent" value="<?= $group['discount_percent'] ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Discount Group</button>
    </form>
</div>
<?= $this->endSection() ?>