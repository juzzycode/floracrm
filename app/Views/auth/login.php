<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Login</h4>
            </div>
            <div class="card-body">
                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
                <?php endif; ?>
                
                <form method="post" action="<?= site_url('login') ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                
                <div class="mt-3">
                    Don't have an account? <a href="<?= site_url('register') ?>">Register here</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>