<?php
// app/Views/layouts/main.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Florist CRM' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .main-content {
            min-height: 100vh;
        }
        .navbar-brand {
            font-weight: bold;
            color: #28a745 !important;
        }
        .nav-link {
            color: #495057 !important;
        }
        .nav-link:hover {
            color: #28a745 !important;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .price-comparison {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .best-price {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-seedling"></i> Florist CRM
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, <?= session()->get('username') ?>
                </span>
                <a class="nav-link" href="/logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/search">
                                <i class="fas fa-search"></i> Search Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/vendors">
                                <i class="fas fa-truck"></i> Vendors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/inventory">
                                <i class="fas fa-boxes"></i> Inventory
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-10 ms-sm-auto px-md-4 main-content">
                <div class="pt-3">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?= $this->renderSection('content') ?>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// app/Views/auth/login.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Florist CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: #28a745;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #6c757d;
        }
        .btn-login {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-card">
                    <div class="login-header">
                        <h1><i class="fas fa-seedling"></i> Florist CRM</h1>
                        <p>Sign in to your account</p>
                    </div>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="/login">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-login w-100">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            Demo credentials: admin1 / password
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// app/Views/dashboard/index.php
echo $this->extend('layouts/main');
echo $this->section('content');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">
                <i class="fas fa-truck"></i> Vendors
            </div>
            <div class="card-body">
                <h4 class="card-title"><?= $totalVendors ?></h4>
                <p class="card-text">Active vendors</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">
                <i class="fas fa-boxes"></i> Products
            </div>
            <div class="card-body">
                <h4 class="card-title"><?= $totalProducts ?></h4>
                <p class="card-text">In inventory</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">
                <i class="fas fa-tags"></i> Price Points
            </div>
            <div class="card-body">
                <h4 class="card-title"><?= $totalPricing ?></h4>
                <p class="card-text">Active pricing</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">
                <i class="fas fa-layer-group"></i> Categories
            </div>
            <div class="card-body">
                <h4 class="card-title"><?= count($categories) ?></h4>
                <p class="card-text">Product categories</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/search" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search Products
                    </a>
                    <a href="/vendors/create" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add Vendor
                    </a>
                    <a href="/inventory/create" class="btn btn-info">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Product Categories</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($categories)): ?>
                    <div class="list-group">
                        <?php foreach ($categories as $category): ?>
                            <a href="/search?category=<?= urlencode($category['category']) ?>" 
                               class="list-group-item list-group-item-action">
                                <i class="fas fa-folder"></i> <?= htmlspecialchars($category['category']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No categories found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->endSection();
?>

<?php
// app/Views/search/index.php
echo $this->extend('layouts/main');
echo $this->section('content');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Search Products</h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Search Criteria</h5>
            </div>
            <div class="card-body">
                <form method="get" action="/search">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="search" class="form-label">Search Term</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?= htmlspecialchars($searchTerm) ?>" 
                                       placeholder="Product name, SKU, or description">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat['category']) ?>" 
                                                <?= $category === $cat['category'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['category']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" 
                                       value="<?= $quantity ?>" min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($results)): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Search Results (<?= count($results) ?> items)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Vendor</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Lead Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($result['product_name']) ?></strong>
                                        <br><small class="text-muted">per <?= htmlspecialchars($result['unit']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($result['sku']) ?></td>
                                    <td><?= htmlspecialchars($result['category']) ?></td>
                                    <td><?= htmlspecialchars($result['vendor_name']) ?></td>
                                    <td>$<?= number_format($result['calculated_price'], 2) ?></td>
                                    <td>
                                        <strong>$<?= number_format($result['total_price'], 2) ?></strong>
                                        <br><small class="text-muted">for <?= $quantity ?> <?= htmlspecialchars($result['unit']) ?>(s)</small>
                                    </td>
                                    <td><?= $result['lead_time_days'] ?> days</td>
                                    <td>
                                        <a href="/search/compare?product_id=<?= $result['inventory_id'] ?>&quantity=<?= $quantity ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-balance-scale"></i> Compare
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
</div>
<?php elseif ($searchTerm || $category): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No products found matching your search criteria.
        </div>
    </div>
</div>
<?php endif; ?>

<?php
echo $this->endSection();
?>
