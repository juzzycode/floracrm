<?php
// app/Config/Routes.php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Main routes
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');

// Authentication routes
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::authenticate');
$routes->get('/logout', 'Auth::logout');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::create');

// Dashboard (requires authentication)
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'Dashboard::index');
    
    // Search routes
    $routes->get('/search', 'Search::index');
    $routes->post('/search', 'Search::results');
    $routes->get('/search/compare/(:num)/(:num)', 'Search::compare/$1/$2');
    
    // Cart routes
    $routes->get('/cart', 'Cart::index');
    $routes->post('/cart/add', 'Cart::add');
    $routes->post('/cart/update', 'Cart::update');
    $routes->post('/cart/remove', 'Cart::remove');
    $routes->get('/cart/count', 'Cart::count');
    
    // Vendor routes
    $routes->get('/vendors', 'Vendors::index');
    $routes->get('/vendors/view/(:num)', 'Vendors::view/$1');
    $routes->get('/vendors/contact/(:num)', 'Vendors::contact/$1');
    $routes->post('/vendors/contact/(:num)', 'Vendors::sendMessage/$1');
    
    // Product routes
    $routes->get('/products', 'Products::index');
    $routes->get('/products/view/(:num)', 'Products::view/$1');
    
    // Order routes
    $routes->get('/orders', 'Orders::index');
    $routes->get('/orders/view/(:num)', 'Orders::view/$1');
    $routes->post('/orders/create', 'Orders::create');
    
    // Customer routes
    $routes->get('/customers', 'Customers::index');
    $routes->get('/customers/view/(:num)', 'Customers::view/$1');
    
    // Reports routes
    $routes->get('/reports', 'Reports::index');
    $routes->get('/reports/sales', 'Reports::sales');
    $routes->get('/reports/inventory', 'Reports::inventory');
});

// API routes
$routes->group('api', function($routes) {
    $routes->post('/products/search', 'Api\Products::search');
    $routes->get('/vendors/(:num)/products', 'Api\Vendors::products/$1');
    $routes->get('/pricing/(:num)', 'Api\Pricing::getProductPricing/$1');
});

// Admin routes (requires admin authentication)
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('/users', 'Admin\Users::index');
    $routes->get('/settings', 'Admin\Settings::index');
});
