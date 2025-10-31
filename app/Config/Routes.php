<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('auth', function($routes) {
    $routes->post('register', 'Auth::register');
    $routes->post('login', 'Auth::login');
});