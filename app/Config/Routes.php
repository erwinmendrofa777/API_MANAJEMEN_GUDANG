<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Grouping route dengan prefix 'api'
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    
    // Resource Route otomatis membuat route untuk: index, show, create, update, delete
    $routes->resource('items', ['controller' => 'ItemController']);

    // Route Custom untuk Manajemen Stok
    $routes->post('items/stock/(:num)', 'ItemController::stock/$1');
});