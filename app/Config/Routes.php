<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::loginPage');
$routes->get('login', 'Auth::loginPage');
$routes->post('login', 'Auth::login');

$routes->get('home', 'Home::index');
