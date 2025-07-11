<?= $this->extend('templates/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Sales Reports</h2>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Sales by Month</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesByMonthChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Top Selling Items</h5>
                </div>
                <div class="card-body">
                    <canvas id="topItemsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Sales</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $recentOrders = model('OrderModel')
                                ->where('company_id', session()->get('company_id'))
                                ->orderBy('order_date', 'DESC')
                                ->findAll(5);
                            
                            foreach ($recentOrders as $order): 
                                $customer = model('CustomerModel')->find($order['customer_id']);
                            ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                <td><?= $order['order_number'] ?></td>
                                <td><?= $customer ? $customer['first_name'] . ' ' . $customer['last_name'] : 'Unknown' ?></td>
                                <td>$<?= number_format($order['total_amount'], 2) ?></td>
                                <td><?= ucfirst($order['status']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Sales by Month Chart
    var ctx1 = document.getElementById('salesByMonthChart').getContext('2d');
    var salesByMonthChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Sales ($)',
                data: [1200, 1900, 1500, 2000, 2200, 3000, 2800, 2500, 2100, 3000, 3500, 4000],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Top Items Chart
    var ctx2 = document.getElementById('topItemsChart').getContext('2d');
    var topItemsChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Roses', 'Tulips', 'Lilies', 'Orchids', 'Sunflowers'],
            datasets: [{
                data: [300, 150, 100, 80, 70],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>