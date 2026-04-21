<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::loginPage');
$routes->get('login', 'Auth::loginPage');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout', ['filter' => 'authGuard']);

$routes->get('home', 'Home::index');

$routes->group('admin', ['filter' => 'adminOnly'], static function (RouteCollection $routes): void {
	$routes->get('dashboard', 'AdminDashboard::index');
	$routes->get('manage-users', 'AdminDashboard::manageUsers');
	$routes->get('view-users', 'AdminDashboard::viewUsers');
	$routes->get('audit-logs', 'AdminDashboard::auditLogs');
	$routes->post('users', 'AdminDashboard::createUser');
	$routes->post('users/(:num)/update', 'AdminDashboard::updateUser/$1');
	$routes->post('users/(:num)/delete', 'AdminDashboard::deleteUser/$1');
});

$routes->group('user', ['filter' => 'userOnly'], static function (RouteCollection $routes): void {
	$routes->get('dashboard', 'UserDashboard::index');
	$routes->get('compute-bill', 'UserDashboard::computeBillPage');
	$routes->get('billing-history', 'UserDashboard::billingHistory');
	$routes->get('action-trail', 'UserDashboard::actionTrail');
	$routes->get('bills/preview', 'UserDashboard::previewBill');
	$routes->post('bills/compute', 'UserDashboard::computeBill');
});
