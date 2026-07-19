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
$routes->get('account-activation', 'Home::account_activation');
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
    $routes->get('phpinfo', 'Admin::phpinfo');
    $routes->get('profile', 'Admin::profile');
    $routes->post('profile', 'Admin::profile_post');
    $routes->get('my-businesses', 'Admin::my_businesses');
    $routes->post('switch-business', 'Admin::switch_business');
    $routes->get('about', 'Admin::about');
    // BUSINESS
    $routes->get('business', 'Admin::business');
    $routes->post('business', 'Admin::business_post');
    $routes->get('business/contract-renewal', 'Admin::business_contract_renewal'); // maybe deprecating
    $routes->post('business/contract-renewal', 'Admin::business_contract_renewal_post'); // maybe deprecating
    $routes->get('business/tag', 'Admin::business_tag');
    $routes->post('business/tag', 'Admin::business_tag_post/retrieve'); // retrieve
    $routes->post('business/tag/(:any)', 'Admin::business_tag_post/$1'); // new or delete
    $routes->get('business/plan', 'Admin::business_plan');
    $routes->get('business/plan/(:any)/(:any)', 'Admin::business_plan_init/$1/$2'); // new plan
    $routes->get('business/branch', 'Admin::business_branch');
    $routes->post('business/branch', 'Admin::business_branch_post');
    $routes->get('business/branch/new-branch', 'Admin::business_branch_manage/new-branch');
    $routes->get('business/branch/(:any)', 'Admin::business_branch_manage/$1');
    $routes->post('business/branch-manage', 'Admin::business_branch_manage_post');
    $routes->get('business/user', 'Admin::business_user');
    $routes->post('business/user', 'Admin::business_user_post');
    $routes->get('business/user/(:num)', 'Admin::business_user_manage/$1');
    $routes->post('business/user-manage', 'Admin::business_user_manage_post');
    $routes->get('business/user-attribute', 'Admin::business_user_attribute');
    $routes->post('business/user-attribute-manage', 'Admin::business_user_attribute_post');
    $routes->get('business/customer', 'Admin::business_customer');
    $routes->post('business/customer', 'Admin::business_customer_post');
    $routes->get('business/policy', 'Admin::business_policy_document'); // all policies
    $routes->get('business/policy/(:num)', 'Admin::business_policy_document_edit/$1/existed/existed'); // edit
    $routes->get('business/policy/(:any)/(:any)/new', 'Admin::business_policy_document_edit/0/$1/$2'); // new
    $routes->post('business/policy/manage', 'Admin::business_policy_document_edit_post'); // save
    $routes->get('business/about-us-page', 'Admin::business_about_us_page'); // only for premium plan
    $routes->get('business/management', 'Admin::business_management');
    $routes->post('business/management', 'Admin::business_management_post');
    // PAYMENT
    $routes->get('business/payment-method', 'Admin::business_payment_method');
    $routes->post('business/payment-method', 'Admin::business_payment_method_post');
    // RESOURCE
    $routes->get('resource/type', 'Admin::resource_type');
    $routes->post('resource/type', 'Admin::resource_type_post');
    $routes->get('resource/type/(:num)', 'Admin::resource_type_manage/$1');
    $routes->post('resource/type-manage', 'Admin::resource_type_manage_post');
    $routes->get('resource', 'Admin::resource');
    $routes->post('resource', 'Admin::resource_post');
    $routes->get('resource/(:num)', 'Admin::resource_manage/$1');
    $routes->post('resource/manage', 'Admin::resource_manage_post');
    // SHIPPING
    $routes->get('shipping-fee', 'Admin::shipping_fee');
    $routes->post('shipping-fee', 'Admin::shipping_fee_post');
    // ORDER
    $routes->get('order', 'Admin::order');
    $routes->post('order', 'Admin::order_post');
    $routes->get('order/(:num)', 'Admin::order_info/$1');
    $routes->post('order/manage', 'Admin::order_info_post');
    // SERVICE
    $routes->get('service', 'Admin::service');
    $routes->post('service', 'Admin::service_post');
    $routes->get('service/(:num)', 'Admin::service_manage/$1');
    $routes->post('service/manage', 'Admin::service_manage_post');
    $routes->get('service/variant/(:num)/(:num)', 'Admin::service_variant_manage/$1/$2');
    $routes->post('service/user/manage', 'Admin::service_user_manage_post');
    $routes->post('service/variant/manage', 'Admin::service_variant_manage_post');
    $routes->get('service/variant/session/(:num)/(:num)', 'Admin::service_variant_session/$1/$2');
    $routes->post('service/variant/session', 'Admin::service_variant_session_post');
    $routes->get('service/variant/session/(:num)/(:num)/(:num)', 'Admin::service_variant_session_manage/$1/$2/$3');
    $routes->post('service/variant/session/manage', 'Admin::service_variant_session_manage_post');
    // PRODUCT
    $routes->get('product', 'Admin::product');
    $routes->post('product', 'Admin::product_post');
    $routes->get('product/(:num)', 'Admin::product_manage/$1');
    $routes->post('product/manage', 'Admin::product_manage_post');
    $routes->get('product/variant/(:num)/(:num)', 'Admin::product_variant_manage/$1/$2');
    $routes->post('product/variant/manage', 'Admin::product_variant_manage_post');
    $routes->get('product/variant/inventory/(:num)/(:num)', 'Admin::product_variant_inventory/$1/$2');
    $routes->post('product/variant/inventory/(:num)/(:num)', 'Admin::product_variant_inventory_post/$1/$2');
    $routes->get('product/category', 'Admin::product_category');
    $routes->post('product/category', 'Admin::product_category_post');
    $routes->get('product/category/(:num)', 'Admin::product_category_manage/$1');
    $routes->post('product/category/manage', 'Admin::product_category_manage_post');
    // REVIEW
    $routes->get('review', 'Admin::review');
    // ALLOCATION
    $routes->get('allocation/staff', 'Admin::allocation_staff');
    $routes->get('allocation/resource', 'Admin::allocation_resource');
    $routes->get('allocation/by-service', 'Admin::allocation_by_service');
    // DISCOUNT
    $routes->get('discount', 'Admin::discount');
    // BLOG
    $routes->get('blog', 'Admin::blog');
    $routes->get('blog/category', 'Admin::blog_category');
    // NOTIFICATION
    $routes->get('notifications', 'Notification::index');
    $routes->get('notifications/fetch', 'Notification::fetch');
    $routes->post('notifications/mark-read/(:num)', 'Notification::markAsRead/$1');
});
// Booking APIs
$routes->group('api/v1.0', function ($routes) {
    $routes->get('(:segment)/(:segment)/business/search', 'Api::business_search/$1/$2');
    $routes->get('(:segment)/(:segment)/business/retrieve', 'Api::business_retrieve/$1/$2');
    $routes->get('(:segment)/(:segment)/service/session-retrieve/(:segment)/(:segment)/(:segment)', 'Api::get_sessions/$1/$2/$3/$4/$5');
    $routes->get('(:segment)/(:segment)/service/slot-retrieve/(:segment)/(:segment)/(:segment)', 'Api::get_slots/$1/$2/$3/$4/$5');
    $routes->post('(:segment)/(:segment)/business/checkout', 'Api::business_checkout/$1/$2');
    $routes->get('(:segment)/(:segment)/order/(:segment)', 'Api::order_search/$1/$2/$3');
    $routes->get('(:segment)/(:segment)/review/(:segment)/(:segment)/(:segment)/(:segment)', 'Api::review_retrieve/$1/$2/$3/$4/$5/$6');
});
// Helper
$routes->group('helper', function ($routes) {
    $routes->post('format-phone-number', 'Helper::format_phone_number');
    $routes->post('generate-slug', 'Helper::generate_slug');
});

$routes->set404Override('App\Controllers\Admin::show404');