<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->resource('api/v1/todos');

$routes->resource('api/v1/CategoryController');
$routes->cli('mail/sendEmail', 'Mail::sendEmail');

// $routes->get('api/v1/todos_status/(:segment)', 'api\v1\Todos::status/$1');

