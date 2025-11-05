<?php
defined('BASEPATH') or exit('No direct script access allowed');
/* API ROUTES */
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

$route['get-banners'] = 'Controller_banners/get_banners';
$route['add-banner'] = 'Controller_banners/add_banner';
$route['get-banner-by-id'] = 'Controller_banners/get_banner_by_id';
$route['update-banner'] = 'Controller_banners/update_banner';
$route['delete-banner'] = 'Controller_banners/delete_banner';

$route['get-brands'] = 'Controller_brands/get_brands';
$route['add-brand'] = 'Controller_brands/add_brand';
$route['get-brand-by-id'] = 'Controller_brands/get_brand_by_id';
$route['update-brand'] = 'Controller_brands/update_brand';
$route['delete-brand'] = 'Controller_brands/delete_brand';
$route['get-brand-product-detail'] = 'Controller_brands/get_brand_product_detail';
$route['get-brand-product-search'] = 'Controller_brands/get_brand_product_search';

$route['get-cms'] = 'Controller_cms/get_cms';
$route['add-cms'] = 'Controller_cms/add_cms';
$route['get-cms-by-id'] = 'Controller_cms/get_cms_by_id';
$route['update-cms'] = 'Controller_cms/update_cms';
$route['delete-cms'] = 'Controller_cms/delete_cms';
$route['get-cms-by-slug'] = 'Controller_cms/get_cms_by_slug';

$route['get-configurations'] = 'Controller_configurations/get_configurations';
$route['add-configurations'] = 'Controller_configurations/add_configurations';
$route['get-configurations-by-id'] = 'Controller_configurations/get_configuration_by_id';
$route['get-configurations-by-key'] = 'Controller_configurations/get_configuration_by_key';
$route['update-configurations'] = 'Controller_configurations/update_configuration';
$route['delete-configurations'] = 'Controller_configurations/delete_configuration';

$route['get-categories'] = 'Controller_categories/get_categories';
$route['add-category'] = 'Controller_categories/add_category';
$route['get-category-by-id'] = 'Controller_categories/get_category_by_id';
$route['get-category-by-slug'] = 'Controller_categories/get_category_by_slug';
$route['update-category'] = 'Controller_categories/update_category';
$route['delete-category'] = 'Controller_categories/delete_category';
$route['get-home-categories-product'] = 'Controller_categories/get_home_categories_product';
$route['get-category-product-detail'] = 'Controller_categories/get_category_product_detail';
$route['get-category-product-search'] = 'Controller_categories/get_category_product_search';
$route['get-header-search-product'] = 'Controller_categories/get_header_product_search';
$route['get-filters'] = 'Controller_categories/get_filters';
$route['get-category-product-detail-id'] = 'Controller_categories/get_category_product_detail_by_id';




$route['get-products'] = 'Controller_products/get_products';
$route['get-product-by-slug'] = 'Controller_products/get_product_by_slug';
$route['add-product'] = 'Controller_products/add_product';
$route['get-product-by-id'] = 'Controller_products/get_product_by_id';
$route['update-product'] = 'Controller_products/update_product';
$route['delete-product'] = 'Controller_products/delete_product';
$route['add-special-products'] = 'Controller_products/add_special_products';
$route['get-special-products'] = 'Controller_products/get_special_products';
$route['get-home-category-product'] = 'Controller_products/get_home_category_product';
$route['get-product-by-category-slug'] = 'Controller_products/get_product_by_category_slug';
$route['get-related-product-by-category-slug'] = 'Controller_products/get_related_product_by_category_slug';
$route['add-to-cart/(:num)'] = 'Controller_products/add_to_cart/$1';



$route['get-states'] = 'Controller_state/get_states';
$route['add-state'] = 'Controller_state/add_state';
$route['get-state-by-id'] = 'Controller_state/get_state_by_id';
$route['update-state'] = 'Controller_state/update_state';
$route['delete-state'] = 'Controller_state/delete_state';
$route['get-state-by-country-id'] = 'Controller_state/get_state_by_country_id';

$route['get-zipcodes'] = 'Controller_zipcodes/get_zipcodes';
$route['get-zipcodes-autocomplete'] = 'Controller_zipcodes/get_zipcodes_autocomplete';
$route['add-zipcode'] = 'Controller_zipcodes/add_zipcode';
$route['get-zipcode-by-id'] = 'Controller_zipcodes/get_zipcode_by_id';
$route['update-zipcode'] = 'Controller_zipcodes/update_zipcode';
$route['delete-zipcode'] = 'Controller_zipcodes/delete_zipcode';
$route['get-zipcode-detail'] = 'Controller_zipcodes/get_zipcode_detail';
$route['remove-zipcode-products'] = 'Controller_zipcodes/remove_zipcode_products';

$route['get-tags'] = 'Controller_tags/get_tags';
$route['add-tag'] = 'Controller_tags/add_tag';
$route['get-tag-by-id'] = 'Controller_tags/get_tag_by_id';
$route['update-tag'] = 'Controller_tags/update_tag';
$route['delete-tag'] = 'Controller_tags/delete_tag';
$route['get-product-by-tag-id'] = 'Controller_tags/get_product_by_tag_id';

$route['get-orders'] = 'Controller_orders/get_orders';
$route['add-order'] = 'Controller_orders/add_order';
$route['add-checkout-address'] = 'Controller_orders/add_checkout_address';
$route['update-checkout-address'] = 'Controller_orders/update_checkout_address';
$route['get-checkout-address-details'] = 'Controller_orders/get_checkout_address_details';
$route['delete-checkout-address-details'] = 'Controller_orders/delete_checkout_address_details';
$route['get-checkout-details'] = 'Controller_orders/get_checkout_details';
$route['get-order-by-id'] = 'Controller_orders/get_order_by_id';
$route['deliveryboy-order-by-id'] = 'Controller_orders/deliveryboy_order_by_id';
$route['update-order'] = 'Controller_orders/update_order';
$route['get-order-by-user-id'] = 'Controller_orders/get_order_by_user_id';
$route['get-credit-transaction-by-user-id'] = 'Controller_credittransaction/get_credit_transaction_by_user_id';
$route['deliveryboy-get-order-by-user-id'] = 'Controller_orders/deliveryboy_get_order_by_user_id';
$route['report-order'] = 'Controller_orders/report_order';
$route['requested-product'] = 'Controller_orders/requested_product';



$route['get-user-detail'] = 'Controller_users/get_user_by_id';
$route['get-users'] = 'Controller_users/get_users';
$route['add-user'] = 'Controller_users/add_user';
$route['login'] = 'Controller_users/login';
$route['login-deliveryboy'] = 'Controller_users/login_deliveryboy';
$route['forgot-password'] = 'Controller_users/forgot_password';
$route['change-password'] = 'Controller_users/change_password';
$route['get-user-address'] = 'Controller_users/get_user_address';
$route['edit-user-address'] = 'Controller_users/edit_user_address';

$route['contact-mail'] = 'Controller_users/contact_mail';
$route['update-user'] = 'Controller_users/edit_user';
$route['user-activate'] = 'Controller_users/user_activate';


$route['cart/add'] = 'Controller_cart/add';
$route['cart/total-items'] = 'Controller_cart/total_items';

$route['get-menus'] = 'controller_menus/get_menu';

$route['get-coupon-amount'] = 'controller_coupons/get_coupon_amount';



$route['add-cart-item'] = 'Controller_cart/add_cart';
$route['update-cart-item'] = 'Controller_cart/update_cart';
$route['update-new-cart-item'] = 'Controller_cart/update_new_cart';
$route['clear-cart-data'] = 'Controller_cart/clear_cart_data';
$route['delete-cart-item'] = 'Controller_cart/delete_cart';
$route['delete-new_cart-item'] = 'Controller_cart/delete_new_cart';
$route['get-cart-item'] = 'Controller_cart/get_cart';

$route['send-test-email'] = 'Controller_orders/send_test_email'; 


$route['get-top-banner'] = 'Controller_home/get_top_banner';
$route['get-featured_category'] = 'Controller_home/get_featured_category';
$route['get-stockup_your_frozen'] = 'Controller_home/get_stockup_your_frozen';
$route['get-refill_pantry'] = 'Controller_home/get_refill_pantry';
$route['get_home_product_slider'] = 'Controller_home/get_home_product_slider';
// $route['get-new_savings'] = 'Controller_home/get_new_savings';
// $route['get-fresh_veg'] = 'Controller_home/get_fresh_veg';
// $route['get-vraj_backery'] = 'Controller_home/get_vraj_backery';
// $route['get-shop_ayurvedic'] = 'Controller_home/get_shop_ayurvedic';
$route['get-home_banner'] = 'Controller_home/get_home_banner';
$route['get-advertise_top'] = 'Controller_home/get_advertise_top';
$route['get-advertise_bottom'] = 'Controller_home/get_advertise_bottom';

$route['get_special_category_product'] = 'Controller_home/get_special_category_product';


$route['stripe/create-intent'] = 'Controller_orders/createIntent';
$route['stripe/update-intent'] = 'Controller_orders/updateIntent';

