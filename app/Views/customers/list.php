<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customers</h2>
        <a href="/customers/add" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Customer
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['first_name'] ?> <?= $customer['last_name'] ?></td>
                        <td><?= $customer['email'] ?></td>
                        <td><?= $customer['phone'] ?></td>
                        <td><?= $customer['address'] ?></td>
                        <td>
                            <a href="/customers/edit/<?= $customer['id'] ?>" class="btn btn-sm btn-outline-primary">
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