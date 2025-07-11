<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Add Vendor</h2>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', session()->getFlashdata('errors')) ?>
        </div>
    <?php endif; ?>
    
    <form action="/vendors/save" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-3">
            <label for="name" class="form-label">Vendor Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="mb-3">
            <label for="contact_person" class="form-label">Contact Person</label>
            <input type="text" class="form-control" id="contact_person" name="contact_person">
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone">
        </div>
        
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="lead_time_days" class="form-label">Lead Time (Days)</label>
            <input type="number" class="form-control" id="lead_time_days" name="lead_time_days" value="1" min="1">
        </div>
        
        <button type="submit" class="btn btn-primary">Save Vendor</button>
    </form>
</div>
<?= $this->endSection() ?>