<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = 'adminpanel/Controller_user/login';

$route['login'] = 'adminpanel/Controller_user/login';
$route['login-process'] = 'adminpanel/Controller_user/login_process';
$route['sign-out'] = 'adminpanel/Controller_user/logout';

$route['home'] = 'adminpanel/Controller_order/index';
$route['forgot-password'] = 'adminpanel/Controller_user/forgot_password';

/* DASHBOARD */
$route['dashboard'] = 'adminpanel/Controller_order/index';
$route['my-profile'] = 'adminpanel/Controller_user/profile';
$route['update-password'] = 'adminpanel/Controller_user/change_password';
$route['change-password-process'] = 'adminpanel/Controller_user/change_password_process';


$route['orders'] = 'adminpanel/Controller_order/index';
$route['order-delete/(:num)'] = 'adminpanel/Controller_order/delete_ajax/$1';
$route['order-add'] = 'adminpanel/Controller_order/add';
$route['order-update/(:num)'] = 'adminpanel/Controller_order/add/$1';
$route['view-order/(:num)'] = 'adminpanel/Controller_order/update_order_page/$1';
$route['update-order-process'] = 'adminpanel/Controller_order/update_order_process';


?>