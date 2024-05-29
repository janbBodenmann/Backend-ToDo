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
service('auth')->routes($routes);
// app/Config/Routes.php
$routes->post('auth/jwt', '\App\Controllers\Auth\LoginController::jwtLogin');
$routes->group('api', ['filter' => 'jwt'], static function ($routes) {
    
});

$routes->get('users', 'UserController::list', ['filter' => 'jwt']);
