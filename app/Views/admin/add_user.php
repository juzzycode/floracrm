<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Add New User</h2>
    
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= implode('<br>', session()->getFlashdata('errors')) ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= route_to('admin.users.save') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" 
                       value="<?= old('first_name') ?>" required>
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" 
                       value="<?= old('last_name') ?>" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?= old('email') ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        
        <div class="mb-3">
            <label for="password_confirm" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
        </div>
        
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="free" <?= old('role') == 'free' ? 'selected' : '' ?>>Free</option>
                <option value="paid" <?= old('role') == 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="active" <?= old('status') == 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= old('status') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="<?= route_to('admin.users') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>