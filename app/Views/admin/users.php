<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>User Management</h2>
        <a href="/admin/users/add" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add User
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['first_name'] ?> <?= $user['last_name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= ucfirst($user['role']) ?></td>
                        <td><?= $user['last_login'] ? date('M d, Y h:i A', strtotime($user['last_login'])) : 'Never' ?></td>
                        <td>
                            <a href="/admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-outline-primary">
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