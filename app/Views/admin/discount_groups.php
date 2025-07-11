<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Discount Groups</h2>
        <a href="/admin/discount-groups/add" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Discount Group
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Discount %</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($discountGroups as $group): ?>
                    <tr>
                        <td><?= $group['name'] ?></td>
                        <td><?= $group['discount_percent'] ?>%</td>
                        <td>
                            <a href="/admin/discount-groups/edit/<?= $group['id'] ?>" class="btn btn-sm btn-outline-primary">
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