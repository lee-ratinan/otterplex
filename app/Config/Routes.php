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
// Files
$routes->get('file/(:any)', 'File::index/$1/0');
$routes->get('download/(:any)', 'File::index/$1/1');
// Internal System
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::index');
    $routes->get('profile', 'Admin::profile');
    $routes->post('profile', 'Admin::profile_post');
    $routes->get('my-businesses', 'Admin::my_businesses');
    $routes->post('switch-business', 'Admin::switch_business');
    $routes->get('about', 'Admin::about');
    // BUSINESS
    $routes->get('business', 'Admin::business');
    $routes->get('business/branch', 'Admin::business_branch');
    $routes->get('business/user', 'Admin::business_user');
    $routes->get('business/customer', 'Admin::business_customer');
    // RESOURCE
    $routes->get('resource/type', 'Admin::resource_type');
    $routes->get('resource', 'Admin::resource');
    // ORDER
    $routes->get('order', 'Admin::order');
    // SERVICE
    $routes->get('service', 'Admin::service');
    // PRODUCT
    $routes->get('product', 'Admin::product');
    $routes->get('product/category', 'Admin::product_category');
    // REVIEW
    $routes->get('review', 'Admin::review');
    // ALLOCATION
    $routes->get('allocation/staff', 'Admin::allocation_staff');
    $routes->get('allocation/resource', 'Admin::allocation_resource');
    // BLOG
    $routes->get('blog', 'Admin::blog');
    $routes->get('blog/category', 'Admin::blog_category');
});
// Booking APIs
$routes->group('api/v1.0', function ($routes) {

});
// Helper
$routes->group('helper', function ($routes) {
    $routes->post('format-phone-number', 'Helper::format_phone_number');
});