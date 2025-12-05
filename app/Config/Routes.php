<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Public Pages
$routes->get('create-account', 'Home::create_account');
$routes->get('forgot-password', 'Home::forgot_password');
$routes->get('reset-password/(:any)', 'Home::reset_password/$1');
$routes->get('login', 'Home::login');
$routes->get('logout', 'Home::logout');
$routes->get('/', 'Home::login');
// Public APIs
$routes->post('create-account', 'Home::create_account_post');
$routes->post('forgot-password', 'Home::forgot_password_post');
$routes->post('reset-password', 'Home::reset_password_post');
$routes->post('login', 'Home::login_post');
// Internal System
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::index');
    $routes->get('profile', 'Admin::profile');
    $routes->get('my-businesses', 'Admin::my_businesses');
    $routes->post('switch-business', 'Admin::switch_business');
    $routes->get('about', 'Admin::about');
});
// Booking APIs
$routes->group('api/v1.0/', function ($routes) {

});