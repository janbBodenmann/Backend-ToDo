<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('api/v1/todos');

$routes->resource('api/v1/CategoryController');
$routes->cli('mail/sendEmail', 'Mail::sendEmail');
$routes->resource('api/v1/cars', ['filter' => 'check_api_key']);
