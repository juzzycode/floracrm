<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Users</h5>
                            <p class="card-text">Manage system users</p>
                        </div>
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                    </div>
                    <a href="/admin/users" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Discount Groups</h5>
                            <p class="card-text">Manage pricing discounts</p>
                        </div>
                        <i class="bi bi-percent" style="font-size: 2rem;"></i>
                    </div>
                    <a href="/admin/discount-groups" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">System Settings</h5>
                            <p class="card-text">Configure application</p>
                        </div>
                        <i class="bi bi-gear" style="font-size: 2rem;"></i>
                    </div>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5>Recent Activity</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    New order #ORD-12345 created
                    <span class="badge bg-secondary">2 mins ago</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    User John Doe registered
                    <span class="badge bg-secondary">1 hour ago</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Inventory item "Red Roses" updated
                    <span class="badge bg-secondary">3 hours ago</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    New vendor "Flower Wholesale" added
                    <span class="badge bg-secondary">5 hours ago</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>