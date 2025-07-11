<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Orders</h2>
        <a href="/orders/new" class="btn btn-primary">
            <i class="bi bi-plus"></i> New Order
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['order_number'] ?></td>
                            <td>
                                <?php 
                                $customer = model('CustomerModel')->find($order['customer_id']);
                                echo $customer ? $customer['first_name'] . ' ' . $customer['last_name'] : 'Unknown';
                                ?>
                            </td>
                            <td><?= date('M d, Y h:i A', strtotime($order['order_date'])) ?></td>
                            <td><?= ucfirst($order['delivery_type']) ?></td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $order['status'] === 'completed' ? 'success' : 
                                    ($order['status'] === 'processing' ? 'primary' : 
                                    ($order['status'] === 'cancelled' ? 'danger' : 'warning'))
                                ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/orders/view/<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>