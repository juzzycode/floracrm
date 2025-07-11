<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Register Your Florist Business</h4>
            </div>
            <div class="card-body">
                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                <?php endif; ?>
                
                <form method="post" action="<?= site_url('register') ?>">
                    <?= csrf_field() ?>
                    <h5>Company Information</h5>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="company_address" class="form-label">Address</label>
                        <textarea class="form-control" id="company_address" name="company_address" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="company_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="company_phone" name="company_phone">
                    </div>
                    
                    <h5 class="mt-4">Your Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
                
                <div class="mt-3">
                    Already have an account? <a href="<?= site_url('login') ?>">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>