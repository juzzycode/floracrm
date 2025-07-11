<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Protected routes
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('/dashboard', 'Orders::index');
    
    // Orders
    $routes->get('/orders', 'Orders::index');
    $routes->get('/orders/new', 'Orders::new');
    $routes->post('/orders/new', 'Orders::new');
    $routes->get('/orders/searchInventory', 'Orders::searchInventory');
    
    // Customers
    $routes->get('/customers', 'Customers::index');
    $routes->get('/customers/add', 'Customers::add');
    $routes->post('/customers/add', 'Customers::add');
    $routes->get('/customers/edit/(:num)', 'Customers::edit/$1');
    $routes->post('/customers/edit/(:num)', 'Customers::edit/$1');
    $routes->get('/customers/delete/(:num)', 'Customers::delete/$1');
    $routes->post('/customers/update/(:num)', 'Customers::update/$1');
    
    // Inventory
    $routes->get('/inventory', 'Inventory::index');
    $routes->get('/inventory/add', 'Inventory::add');
    $routes->post('/inventory/add', 'Inventory::add');
    $routes->get('/inventory/edit/(:num)', 'Inventory::edit/$1');
    $routes->post('/inventory/edit/(:num)', 'Inventory::edit/$1');
    $routes->get('/inventory/delete/(:num)', 'Inventory::delete/$1');
    $routes->post('/inventory/update/(:num)', 'Inventory::update/$1');
    
    // Vendors
    $routes->get('/vendors', 'Vendors::index');
    $routes->get('/vendors/add', 'Vendors::add');
    $routes->post('/vendors/add', 'Vendors::add');
    $routes->get('/vendors/edit/(:num)', 'Vendors::edit/$1');
    $routes->post('/vendors/edit/(:num)', 'Vendors::edit/$1');
    $routes->get('/vendors/delete/(:num)', 'Vendors::delete/$1');
    $routes->post('/vendors/update/(:num)', 'Vendors::update/$1');
    
    // Reports
    $routes->get('/reports', 'Reports::index');
    
    // Admin routes
    $routes->group('', ['filter' => 'admin'], function($routes) {/*
        $routes->get('/admin', 'Admin::index');
        $routes->get('/admin/users', 'Admin::users');
        $routes->get('/admin/users/add', 'Admin::addUser');
        $routes->post('/admin/users/add', 'Admin::addUser');
        $routes->get('/admin/users/edit/(:num)', 'Admin::editUser/$1');
        $routes->post('/admin/users/edit/(:num)', 'Admin::editUser/$1');
        $routes->get('/admin/users/delete/(:num)', 'Admin::deleteUser/$1');
        $routes->post('/admin/users/update/(:num)', 'Admin::updateUser/$1');
        $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
        */
        $routes->get('/', 'Admin::index');
        $routes->get('users', 'Admin::users');
        $routes->get('users/add', 'Admin::addUser');
        $routes->post('users/add', 'Admin::addUser');
        $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
        $routes->post('users/update/(:num)', 'Admin::updateUser/$1'); // Changed from edit to update
        $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');

        $routes->get('/admin/discount-groups', 'Admin::discountGroups');
        $routes->get('/admin/discount-groups/add', 'Admin::addDiscountGroup');
        $routes->post('/admin/discount-groups/add', 'Admin::addDiscountGroup');
        $routes->get('/admin/discount-groups/edit/(:num)', 'Admin::editDiscountGroup/$1');
        $routes->post('/admin/discount-groups/edit/(:num)', 'Admin::editDiscountGroup/$1');
        $routes->get('/admin/discount-groups/delete/(:num)', 'Admin::deleteDiscountGroup/$1');


    });
});