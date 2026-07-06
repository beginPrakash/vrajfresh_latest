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
|	$route['V1/default_controller'] = 'V1/welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['V1/404_override'] = 'V1/errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['V1/translate_uri_dashes'] = FALSE;
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
$route['V1/default_controller'] = 'V1/welcome';
$route['V1/404_override'] = 'V1/';
$route['V1/translate_uri_dashes'] = FALSE;

$route['V1/get-banners'] = 'V1/Controller_banners/get_banners';
$route['V1/add-banner'] = 'V1/Controller_banners/add_banner';
$route['V1/get-banner-by-id'] = 'V1/Controller_banners/get_banner_by_id';
$route['V1/update-banner'] = 'V1/Controller_banners/update_banner';
$route['V1/delete-banner'] = 'V1/Controller_banners/delete_banner';

$route['V1/get-brands'] = 'V1/Controller_brands/get_brands';
$route['V1/add-brand'] = 'V1/Controller_brands/add_brand';
$route['V1/get-brand-by-id'] = 'V1/Controller_brands/get_brand_by_id';
$route['V1/update-brand'] = 'V1/Controller_brands/update_brand';
$route['V1/delete-brand'] = 'V1/Controller_brands/delete_brand';
$route['V1/get-brand-product-detail'] = 'V1/Controller_brands/get_brand_product_detail';
$route['V1/get-brand-product-search'] = 'V1/Controller_brands/get_brand_product_search';

$route['V1/get-cms'] = 'V1/Controller_cms/get_cms';
$route['V1/add-cms'] = 'V1/Controller_cms/add_cms';
$route['V1/get-cms-by-id'] = 'V1/Controller_cms/get_cms_by_id';
$route['V1/update-cms'] = 'V1/Controller_cms/update_cms';
$route['V1/delete-cms'] = 'V1/Controller_cms/delete_cms';
$route['V1/get-cms-by-slug'] = 'V1/Controller_cms/get_cms_by_slug';

$route['V1/get-configurations'] = 'V1/Controller_configurations/get_configurations';
$route['V1/add-configurations'] = 'V1/Controller_configurations/add_configurations';
$route['V1/get-configurations-by-id'] = 'V1/Controller_configurations/get_configuration_by_id';
$route['V1/get-configurations-by-key'] = 'V1/Controller_configurations/get_configuration_by_key';
$route['V1/update-configurations'] = 'V1/Controller_configurations/update_configuration';
$route['V1/delete-configurations'] = 'V1/Controller_configurations/delete_configuration';

$route['V1/get-categories'] = 'V1/Controller_categories/get_categories';
$route['V1/add-category'] = 'V1/Controller_categories/add_category';
$route['V1/get-category-by-id'] = 'V1/Controller_categories/get_category_by_id';
$route['V1/get-category-by-slug'] = 'V1/Controller_categories/get_category_by_slug';
$route['V1/update-category'] = 'V1/Controller_categories/update_category';
$route['V1/delete-category'] = 'V1/Controller_categories/delete_category';
$route['V1/get-home-categories-product'] = 'V1/Controller_categories/get_home_categories_product';
$route['V1/get-category-product-detail'] = 'V1/Controller_categories/get_category_product_detail';
$route['V1/get-category-product-search'] = 'V1/Controller_categories/get_category_product_search';
$route['V1/get-header-search-product'] = 'V1/Controller_categories/get_header_product_search';
$route['V1/get-filters'] = 'V1/Controller_categories/get_filters';
$route['V1/get-category-product-detail-id'] = 'V1/Controller_categories/get_category_product_detail_by_id';




$route['V1/get-products'] = 'V1/Controller_products/get_products';
$route['V1/get-product-by-slug'] = 'V1/Controller_products/get_product_by_slug';
$route['V1/add-product'] = 'V1/Controller_products/add_product';
$route['V1/get-product-by-id'] = 'V1/Controller_products/get_product_by_id';
$route['V1/update-product'] = 'V1/Controller_products/update_product';
$route['V1/delete-product'] = 'V1/Controller_products/delete_product';
$route['V1/add-special-products'] = 'V1/Controller_products/add_special_products';
$route['V1/get-special-products'] = 'V1/Controller_products/get_special_products';
$route['V1/get-home-category-product'] = 'V1/Controller_products/get_home_category_product';
$route['V1/get-product-by-category-slug'] = 'V1/Controller_products/get_product_by_category_slug';
$route['V1/get-related-product-by-category-slug'] = 'V1/Controller_products/get_related_product_by_category_slug';
$route['V1/add-to-cart/(:num)'] = 'V1/Controller_products/add_to_cart/$1';



$route['V1/get-states'] = 'V1/Controller_state/get_states';
$route['V1/add-state'] = 'V1/Controller_state/add_state';
$route['V1/get-state-by-id'] = 'V1/Controller_state/get_state_by_id';
$route['V1/update-state'] = 'V1/Controller_state/update_state';
$route['V1/delete-state'] = 'V1/Controller_state/delete_state';
$route['V1/get-state-by-country-id'] = 'V1/Controller_state/get_state_by_country_id';

$route['V1/get-zipcodes'] = 'V1/Controller_zipcodes/get_zipcodes';
$route['V1/get-zipcodes-autocomplete'] = 'V1/Controller_zipcodes/get_zipcodes_autocomplete';
$route['V1/add-zipcode'] = 'V1/Controller_zipcodes/add_zipcode';
$route['V1/get-zipcode-by-id'] = 'V1/Controller_zipcodes/get_zipcode_by_id';
$route['V1/update-zipcode'] = 'V1/Controller_zipcodes/update_zipcode';
$route['V1/delete-zipcode'] = 'V1/Controller_zipcodes/delete_zipcode';
$route['V1/get-zipcode-detail'] = 'V1/Controller_zipcodes/get_zipcode_detail';
$route['V1/remove-zipcode-products'] = 'V1/Controller_zipcodes/remove_zipcode_products';

$route['V1/get-tags'] = 'V1/Controller_tags/get_tags';
$route['V1/add-tag'] = 'V1/Controller_tags/add_tag';
$route['V1/get-tag-by-id'] = 'V1/Controller_tags/get_tag_by_id';
$route['V1/update-tag'] = 'V1/Controller_tags/update_tag';
$route['V1/delete-tag'] = 'V1/Controller_tags/delete_tag';
$route['V1/get-product-by-tag-id'] = 'V1/Controller_tags/get_product_by_tag_id';

$route['V1/get-orders'] = 'V1/Controller_orders/get_orders';
$route['V1/add-order'] = 'V1/Controller_orders/add_order';
$route['V1/add-checkout-address'] = 'V1/Controller_orders/add_checkout_address';
$route['V1/update-checkout-address'] = 'V1/Controller_orders/update_checkout_address';
$route['V1/get-checkout-address-details'] = 'V1/Controller_orders/get_checkout_address_details';
$route['V1/delete-checkout-address-details'] = 'V1/Controller_orders/delete_checkout_address_details';
$route['V1/get-checkout-details'] = 'V1/Controller_orders/get_checkout_details';
$route['V1/get-user-billing-address-list'] = 'V1/Controller_orders/get_user_billing_address_list';
$route['V1/get-user-shipping-address-list'] = 'V1/Controller_orders/get_user_shipping_address_list';
$route['V1/get-order-by-id'] = 'V1/Controller_orders/get_order_by_id';
$route['V1/deliveryboy-order-by-id'] = 'V1/Controller_orders/deliveryboy_order_by_id';
$route['V1/update-order'] = 'V1/Controller_orders/update_order';
$route['V1/get-order-by-user-id'] = 'V1/Controller_orders/get_order_by_user_id';
$route['V1/get-credit-transaction-by-user-id'] = 'V1/Controller_credittransaction/get_credit_transaction_by_user_id';
$route['V1/get-order-dropdownlist-by-user'] = 'V1/Controller_orders/get_order_dropdown_user_id';
$route['V1/deliveryboy-get-order-by-user-id'] = 'V1/Controller_orders/deliveryboy_get_order_by_user_id';
$route['V1/report-order'] = 'V1/Controller_orders/report_order';
$route['V1/requested-product'] = 'V1/Controller_orders/requested_product';
$route['V1/get-state-list'] = 'V1/Controller_orders/get_state_list';
$route['V1/get-substitution-preferences'] = 'V1/Controller_orders/get_substitution_preferences';




$route['V1/get-user-detail'] = 'V1/Controller_users/get_user_by_id';
$route['V1/get-users'] = 'V1/Controller_users/get_users';
$route['V1/add-user'] = 'V1/Controller_users/add_user';
$route['V1/login'] = 'V1/Controller_users/login';
$route['V1/logout'] = 'V1/Controller_users/logout';
$route['V1/verify_otp'] = 'V1/Controller_users/verify_otp';
$route['V1/regenerate_token'] = 'V1/Controller_users/regenerate_token';
$route['V1/login-deliveryboy'] = 'V1/Controller_users/login_deliveryboy';
$route['V1/forgot-password'] = 'V1/Controller_users/forgot_password';
$route['V1/change-password'] = 'V1/Controller_users/change_password';
$route['V1/get-user-address'] = 'V1/Controller_users/get_user_address';
$route['V1/edit-user-address'] = 'V1/Controller_users/edit_user_address';

$route['V1/contact-mail'] = 'V1/Controller_users/contact_mail';
$route['V1/delete-user-account'] = 'V1/Controller_users/delete_user_account';
$route['V1/update-user'] = 'V1/Controller_users/edit_user';
$route['V1/user-activate'] = 'V1/Controller_users/user_activate';

$route['V1/get-notifications'] = 'V1/Controller_notifications/get_notifications';
$route['V1/mark-notification-read'] = 'V1/Controller_notifications/mark_notification_read';


$route['V1/cart/add'] = 'V1/Controller_cart/add';
$route['V1/cart/total-items'] = 'V1/Controller_cart/total_items';

$route['V1/get-menus'] = 'V1/controller_menus/get_menu';

$route['V1/get-coupon-amount'] = 'V1/controller_coupons/get_coupon_amount';



$route['V1/add-cart-item'] = 'V1/Controller_cart/add_cart';
$route['V1/update-cart-item'] = 'V1/Controller_cart/update_cart';
$route['V1/update-new-cart-item'] = 'V1/Controller_cart/update_new_cart';
$route['V1/clear-cart-data'] = 'V1/Controller_cart/clear_cart_data';
$route['V1/delete-cart-item'] = 'V1/Controller_cart/delete_cart';
$route['V1/delete-new_cart-item'] = 'V1/Controller_cart/delete_new_cart';
$route['V1/get-cart-item'] = 'V1/Controller_cart/get_cart';

$route['V1/send-test-email'] = 'V1/Controller_orders/send_test_email'; 


$route['V1/get-top-banner'] = 'V1/Controller_home/get_top_banner';
$route['V1/get-featured_category'] = 'V1/Controller_home/get_featured_category';
$route['V1/get-stockup_your_frozen'] = 'V1/Controller_home/get_stockup_your_frozen';
$route['V1/get-refill_pantry'] = 'V1/Controller_home/get_refill_pantry';
$route['V1/get_home_product_slider'] = 'V1/Controller_home/get_home_product_slider';
// $route['V1/get-new_savings'] = 'V1/Controller_home/get_new_savings';
// $route['V1/get-fresh_veg'] = 'V1/Controller_home/get_fresh_veg';
// $route['V1/get-vraj_backery'] = 'V1/Controller_home/get_vraj_backery';
// $route['V1/get-shop_ayurvedic'] = 'V1/Controller_home/get_shop_ayurvedic';
$route['V1/get-home_banner'] = 'V1/Controller_home/get_home_banner';
$route['V1/get-advertise_top'] = 'V1/Controller_home/get_advertise_top';
$route['V1/get-advertise_bottom'] = 'V1/Controller_home/get_advertise_bottom';

$route['V1/get_special_category_product'] = 'V1/Controller_home/get_special_category_product';
$route['V1/get_app_version'] = 'V1/Controller_home/get_app_version';

$route['V1/create-intent'] = 'V1/Controller_orders/createIntent';
$route['V1/update-intent'] = 'V1/Controller_orders/updateIntent';

$route['V1/create-wishlist'] = 'V1/Controller_userwishlist/create_wishlist';
$route['V1/user-wishlist'] = 'V1/Controller_userwishlist/get_wishlist_by_user_id';

