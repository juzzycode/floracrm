<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Vendors</h2>
        <a href="/vendors/add" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Vendor
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Lead Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td><?= $vendor['name'] ?></td>
                        <td><?= $vendor['contact_person'] ?></td>
                        <td><?= $vendor['email'] ?></td>
                        <td><?= $vendor['phone'] ?></td>
                        <td><?= $vendor['lead_time_days'] ?> days</td>
                        <td>
                            <a href="/vendors/edit/<?= $vendor['id'] ?>" class="btn btn-sm btn-outline-primary">
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