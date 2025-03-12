<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['test-home'] = 'Welcome/test';
$route['privacy-statement'] = 'Controller_cms/privacy_policy';
$route['terms-conditions'] = 'Controller_cms/terms_condition';
$route['refund-and-return-policy'] = 'Controller_cms/refund_policy';
$route['get-cms-by-slug/(:any)'] = 'Controller_cms/get_cms_by_slug/$1';

$route['category/(:any)'] = 'Controller_products/products_list/$1';
$route['product/(:any)'] = 'Controller_products/product_details/$1';
$route['products/search'] = 'Controller_search_products/products_list';

$route['cart-detail'] = 'Controller_cart/cart_detail';
$route['render-cart-detail'] = 'Controller_cart/render_cart_detail';
$route['item-update'] = 'Controller_cart/update';
$route['item-new-update'] = 'Controller_cart/new_update';
$route['load-cart-data'] = 'Controller_cart/load_cart_data';
$route['item-remove'] = 'Controller_cart/remove';
$route['item-new-remove'] = 'Controller_cart/remove_new';
$route['cart-clear'] = 'Controller_cart/clear';
$route['cart-clear-data'] = 'Controller_cart/clear_cart_data';
$route['cart-session-data'] = 'Controller_cart/get_cart_session_data';

$route['brand/(:any)'] = 'Controller_brands/products_list/$1';
$route['brands'] = 'Controller_brands/brand_list';

$route['tag/(:any)'] = 'Controller_tags/products_list/$1';

#$route['signup'] = 'Controller_users/signup';
#$route['login'] = 'Controller_users/login';
$route['validate-login'] = 'Controller_users/validate_login';
$route['facebook_login'] = 'Controller_users/facebook_login';
$route['google_login'] = 'Controller_users/google_login';
$route['forgot-password'] = 'Controller_users/forgot_password';
$route['change-password'] = 'Controller_users/change_password';
$route['user-activate/(:any)'] = 'Controller_login/user_activate/$1';


$route['checkout'] = 'Controller_checkout/index';
$route['checkout-process'] = 'Controller_checkout/checkout_process';
$route['order-success/(:any)'] = 'Controller_checkout/order_success_new/$1';
$route['order-falied'] = 'Controller_checkout/order_failed_new';
$route['add-checkout-address'] = 'Controller_checkout/add_checkout_address';
$route['update-checkout-address'] = 'Controller_checkout/update_checkout_address';


// $route['checkout'] = 'Controller_checkout/config_packaging_data';
// $route['checkout'] = 'Controller_checkout/config_preparation_data';
$route['payment/(:any)'] = 'Controller_checkout/payment/$1';
$route['payment-process'] = 'Controller_checkout/payment_process';


$route['my-address'] = 'Controller_users/address';
$route['my-account'] = 'Controller_users/edit_account';
$route['sucess-login'] = 'Controller_users/login_sucess';
$route['my-orders'] = 'Controller_users/orders';
$route['credit-transactions'] = 'Controller_users/credit_transactions';
$route['contact'] = 'Controller_login/contact';
$route['change_password_process'] = 'Controller_users/change_password_process';
$route['edit-profile'] = 'Controller_users/edit_profile';
$route['special-request'] = 'Controller_users/special_request';
$route['report-order'] = 'Controller_users/report_order';
$route['logout'] = 'Controller_users/logout';

#$route['login'] = 'Controller_login/index';
$route['login'] = 'Controller_login/login';
$route['signup'] = 'Controller_login/signup';

$route['cart/add'] = 'Controller_cart/add';
$route['cart/logincartdata'] = 'Controller_cart/login_cart_data';
$route['cart/total-items'] = 'Controller_cart/total_items';

$route['/(:any)'] = 'Controller_products/products_list/$1';
$route['special-category/(:any)'] = 'Controller_special_category/get_special_category_list/$1';