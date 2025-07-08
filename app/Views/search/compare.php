<?php
// app/Views/search/compare.php
echo $this->extend('layouts/main');
echo $this->section('content');
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Price Comparison</h1>
    <a href="/search" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Search
    </a>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>
                    <?= htmlspecialchars($product['name']) ?> 
                    <span class="badge bg-secondary"><?= htmlspecialchars($product['sku']) ?></span>
                </h5>
                <p class="mb-0 text-muted">
                    Category: <?= htmlspecialchars($product['category']) ?> | 
                    Quantity: <?= $quantity ?> <?= htmlspecialchars($product['unit']) ?>(s)
                </p>
            </div>
            <div class="card-body">
                <?php if (!empty($pricing)): ?>
                    <?php foreach ($pricing as $index => $p): ?>
                        <div class="price-comparison <?= $index === 0 ? 'best-price' : '' ?>">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h6 class="mb-1">
                                        <?= htmlspecialchars($p['vendor_name']) ?>
                                        <?php if ($index === 0): ?>
                                            <span class="badge bg-success">Best Price</span>
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-muted">
                                        Payment: <?= htmlspecialchars($p['payment_terms'] ?? 'N/A') ?>
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <strong class="text-primary">$<?= number_format($p['calculated_price'], 2) ?></strong>
                                    <br>
                                    <small class="text-muted">per <?= htmlspecialchars($product['unit']) ?></small>
                                </div>
                                <div class="col-md-2">
                                    <strong>$<?= number_format($p['total_cost'], 2) ?></strong>
                                    <br>
                                    <small class="text-muted">Total Cost</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-<?= $p['availability'] === 'In Stock' ? 'success' : ($p['availability'] === 'Limited' ? 'warning' : 'danger') ?>">
                                        <?= htmlspecialchars($p['availability'] ?? 'Unknown') ?>
                                    </span>
                                    <br>
                                    <small class="text-muted">
                                        Lead Time: <?= htmlspecialchars($p['lead_time'] ?? 'N/A') ?>
                                    </small>
                                </div>
                                <div class="col-md-3">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="addToCart(<?= $p['vendor_id'] ?>, <?= $product['id'] ?>, <?= $quantity ?>)">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="contactVendor(<?= $p['vendor_id'] ?>)">
                                            <i class="fas fa-envelope"></i> Contact
                                        </button>
                                    </div>
                                    <br>
                                    <small class="text-muted">
                                        Min Order: <?= htmlspecialchars($p['min_order'] ?? 'N/A') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php if ($index < count($pricing) - 1): ?>
                            <hr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No pricing information available for this product.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Additional Product Details -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6>Product Details</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Description:</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($product['description'] ?? 'N/A') ?></dd>
                    
                    <dt class="col-sm-4">Category:</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($product['category']) ?></dd>
                    
                    <dt class="col-sm-4">Unit:</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($product['unit']) ?></dd>
                    
                    <dt class="col-sm-4">Color:</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($product['color'] ?? 'N/A') ?></dd>
                    
                    <dt class="col-sm-4">Size:</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($product['size'] ?? 'N/A') ?></dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6>Price History</h6>
            </div>
            <div class="card-body">
                <canvas id="priceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
.price-comparison {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.price-comparison:hover {
    background-color: #f8f9fa;
}

.best-price {
    background-color: #d4edda;
    border: 2px solid #c3e6cb;
}

.best-price:hover {
    background-color: #c3e6cb;
}
</style>

<script>
function addToCart(vendorId, productId, quantity) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            vendor_id: vendorId,
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Product added to cart successfully!', 'success');
            updateCartCount();
        } else {
            showToast('Error adding product to cart: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error adding product to cart', 'error');
    });
}

function contactVendor(vendorId) {
    window.location.href = '/vendors/contact/' + vendorId;
}

function showToast(message, type) {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toast = new bootstrap.Toast(toastContainer.lastElementChild);
    toast.show();
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    document.body.appendChild(container);
    return container;
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge) {
                cartBadge.textContent = data.count;
            }
        });
}

// Initialize price chart if data is available
<?php if (!empty($price_history)): ?>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('priceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($price_history, 'date')) ?>,
            datasets: [{
                label: 'Average Price',
                data: <?= json_encode(array_column($price_history, 'avg_price')) ?>,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toFixed(2);
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
<?php endif; ?>
</script>

<?php echo $this->endSection(); ?>
