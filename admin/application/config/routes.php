<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = 'adminpanel/Controller_user/login';

$route['login'] = 'adminpanel/Controller_user/login';
$route['login-process'] = 'adminpanel/Controller_user/login_process';
$route['sign-out'] = 'adminpanel/Controller_user/logout';

$route['home'] = 'adminpanel/Controller_dashboard/index';
$route['forgot-password'] = 'adminpanel/Controller_user/forgot_password';

/* DASHBOARD */
$route['dashboard'] = 'adminpanel/Controller_dashboard/index';
$route['my-profile'] = 'adminpanel/Controller_user/profile';
$route['update-password'] = 'adminpanel/Controller_user/change_password';
$route['change-password-process'] = 'adminpanel/Controller_user/change_password_process';

/* USER */
$route['user'] = 'adminpanel/Controller_user';
$route['user-add'] = 'adminpanel/Controller_user/add';
$route['user-update/(:num)'] = 'adminpanel/Controller_user/add/$1';
$route['user-delete/(:num)'] = 'adminpanel/Controller_user/delete_ajax/$1';
$route['user-view/(:num)'] = 'adminpanel/Controller_user/view/$1';

/* Delivery USER */
$route['deliveryuser'] = 'adminpanel/Controller_delivery_user';
$route['deliveryuser-add'] = 'adminpanel/Controller_delivery_user/add';
$route['deliveryuser-update/(:num)'] = 'adminpanel/Controller_delivery_user/add/$1';
$route['deliveryuser-delete/(:num)'] = 'adminpanel/Controller_delivery_user/delete_ajax/$1';
$route['deliveryuser-view/(:num)'] = 'adminpanel/Controller_delivery_user/view_deliveryuser_ajax/$1';

/* CONFIGURATION */
$route['zipcode-configuration'] = 'adminpanel/Controller_zipcode/zipcode';
$route['save-zipcode'] = 'adminpanel/Controller_zipcode/save_zipcode';
$route['delete-zipcode'] = 'adminpanel/Controller_zipcode/delete_zipcode';
$route['website-configuration'] = 'adminpanel/Controller_configuration/website';
$route['save-configuration'] = 'adminpanel/Controller_configuration/save_website_configuration';

/* CASH CREDITS */
$route['cash-credits'] = 'adminpanel/Controller_credits/cashcredits';
$route['save-cashcredits'] = 'adminpanel/Controller_credits/save_cash_credits';

/* CMS */
$route['cms'] = 'adminpanel/controller_cms';
$route['cms-add'] = 'adminpanel/controller_cms/add';
$route['cms-update/(:num)'] = 'adminpanel/controller_cms/add/$1';
$route['cms-delete/(:num)'] = 'adminpanel/controller_cms/delete/$1';
$route['cms-view/(:num)'] = 'adminpanel/controller_cms/view/$1';

/* PRODUCT VARIANT */
$route['product_variant'] = 'adminpanel/controller_product_variant';
$route['product_variant-add'] = 'adminpanel/controller_product_variant/add';
$route['product_variant-update/(:num)'] = 'adminpanel/controller_product_variant/add/$1';
$route['product_variant-delete/(:num)'] = 'adminpanel/controller_product_variant/delete/$1';
$route['product_variant-view/(:num)'] = 'adminpanel/controller_product_variant/view/$1';

/* MENU */
$route['menu'] = 'adminpanel/controller_menu';
$route['menu-add'] = 'adminpanel/controller_menu/add';
$route['menu-update/(:num)'] = 'adminpanel/controller_menu/add/$1';
$route['menu-delete/(:num)'] = 'adminpanel/controller_menu/delete/$1';
$route['menu-view/(:num)'] = 'adminpanel/controller_menu/view/$1';

/* CATEGORY */
$route['category'] = 'adminpanel/Controller_category';
$route['category-delete/(:num)'] = 'adminpanel/Controller_category/delete_ajax/$1';
$route['category-add'] = 'adminpanel/Controller_category/add';
$route['category-update/(:num)'] = 'adminpanel/Controller_category/add/$1';

/* BRAND */
$route['brand'] = 'adminpanel/Controller_brand';
$route['brand-delete/(:num)'] = 'adminpanel/Controller_brand/delete_ajax/$1';
$route['brand-add'] = 'adminpanel/Controller_brand/add';
$route['brand-update/(:num)'] = 'adminpanel/Controller_brand/add/$1';

/* TAGS */
$route['tag'] = 'adminpanel/Controller_tag';
$route['tag-delete/(:num)'] = 'adminpanel/Controller_tag/delete_ajax/$1';
$route['tag-add'] = 'adminpanel/Controller_tag/add';
$route['tag-update/(:num)'] = 'adminpanel/Controller_tag/add/$1';

/* PRODUCT */
$route['product'] = 'adminpanel/controller_product';
$route['product-delete/(:num)'] = 'adminpanel/controller_product/delete_ajax/$1';
$route['product-add'] = 'adminpanel/controller_product/add';
$route['product-update/(:num)'] = 'adminpanel/controller_product/add/$1';
$route['product-image-delete/(:num)/(:num)'] = 'adminpanel/controller_product/image_remove/$1/$2';
$route['import-product'] = 'adminpanel/excel_import/index';

/* PROMOTIONAL CODE */
$route['promotional-code'] = 'adminpanel/Controller_promotional_code';
$route['promotional-code-delete/(:num)'] = 'adminpanel/Controller_promotional_code/delete_ajax/$1';
$route['promotional-code-add'] = 'adminpanel/Controller_promotional_code/add';
$route['get-user-email'] = 'adminpanel/Controller_promotional_code/get_user_email';
$route['promotional-code-update/(:num)'] = 'adminpanel/Controller_promotional_code/add/$1';

/* Client Group */
$route['clientgroup'] = 'adminpanel/Controller_clientgroup';
$route['clientgroup-delete/(:num)'] = 'adminpanel/Controller_clientgroup/delete_ajax/$1';
$route['clientgroup-step1'] = 'adminpanel/Controller_clientgroup/step1';
$route['clientgroup-step2'] = 'adminpanel/Controller_clientgroup/step2';
$route['clientgroup-update/(:num)'] = 'adminpanel/Controller_clientgroup/add/$1';

/* TESTIMONIAL */
$route['testimonial'] = 'adminpanel/Controller_testimonial';
$route['testimonial-delete/(:num)'] = 'adminpanel/Controller_testimonial/delete_ajax/$1';
$route['testimonial-add'] = 'adminpanel/Controller_testimonial/add';
$route['testimonial-update/(:num)'] = 'adminpanel/Controller_testimonial/add/$1';

/* BANNER */
$route['banner'] = 'adminpanel/Controller_banner';
$route['banner-delete/(:num)'] = 'adminpanel/Controller_banner/delete_ajax/$1';
$route['banner-add'] = 'adminpanel/Controller_banner/add';
$route['banner-update/(:num)'] = 'adminpanel/Controller_banner/add/$1';

/* INQUIRY */
$route['contact-inquiry'] = 'adminpanel/Controller_contact_inquiry';
$route['contact-inquiry-delete/(:num)'] = 'adminpanel/Controller_contact_inquiry/delete_ajax/$1';

$route['report-about-order'] = 'adminpanel/Controller_report_about_order';
$route['report-about-order-delete/(:num)'] = 'adminpanel/Controller_report_about_order/delete_ajax/$1';

$route['product-facility-request'] = 'adminpanel/Controller_product_facility_request';
$route['product-facility-request-delete/(:num)'] = 'adminpanel/Controller_product_facility_request/delete_ajax/$1';

/* BLOG */
$route['blog'] = 'adminpanel/controller_blog';
$route['blog-add'] = 'adminpanel/controller_blog/add';
$route['blog-update/(:num)'] = 'adminpanel/controller_blog/add/$1';
$route['blog-delete/(:num)'] = 'adminpanel/controller_blog/delete/$1';
$route['blog-view/(:num)'] = 'adminpanel/controller_blog/view/$1';

/* ORDER */
$route['historic-orders'] = 'adminpanel/Controller_historicorder/index';

$route['orders'] = 'adminpanel/Controller_order/index';
$route['order-delete/(:num)'] = 'adminpanel/Controller_order/delete_ajax/$1';
$route['order-add'] = 'adminpanel/Controller_order/add';
$route['order-update/(:num)'] = 'adminpanel/Controller_order/add/$1';

/* CUSTOMER */
$route['customers'] = 'adminpanel/Controller_customer/index';
$route['customer-delete/(:num)'] = 'adminpanel/Controller_customer/delete_ajax/$1';
$route['customer-add'] = 'adminpanel/Controller_customer/add';
$route['customer-update/(:num)'] = 'adminpanel/Controller_customer/add/$1';

/* UPDATE ORDER */
$route['getCartProductRow/(:num)'] = 'adminpanel/Controller_order/getCartProductRow/$1';
$route['update-order-process'] = 'adminpanel/Controller_order/update_order_process';
$route['remove-order-process/(:num)'] = 'adminpanel/Controller_order/remove_order_product/$1';
$route['order-payment-capture'] = 'adminpanel/Controller_order/order_payment_capture';
$route['send-test-email'] = 'adminpanel/Controller_order/send_test_email';
$route['order-packlist-pdf'] = 'adminpanel/Controller_order/order_packlist_pdf';
$route['update-order/(:num)'] = 'adminpanel/Controller_order/update_order_page/$1';
$route['order/diffrence/payment/(:num)'] = 'adminpanel/Controller_order/order_diffrence_payment/$1';
$route['order_label_pdf'] = 'adminpanel/Controller_order/order_label_pdf';

$route['state-wise-tax'] = 'adminpanel/Controller_state_wise_tax/zipcode';
$route['save-state'] = 'adminpanel/Controller_state_wise_tax/save_state';
$route['delete-state'] = 'adminpanel/Controller_state_wise_tax/delete_state';

$route['capture-auto-payment'] = 'adminpanel/controller_cronjob/capture_auto_payment';

/* FEATURE CATEGORIES */
$route['fcategories'] = 'adminpanel/Controller_feature_categories';
$route['fcategories-delete/(:num)'] = 'adminpanel/Controller_feature_categories/delete_ajax/$1';
$route['fcategories-add'] = 'adminpanel/Controller_feature_categories/add';
$route['fcategories-update/(:num)'] = 'adminpanel/Controller_feature_categories/add/$1';

/* STOCKUP YOUR FROZEN */
$route['stockup'] = 'adminpanel/Controller_stockup';
$route['stockup-delete/(:num)'] = 'adminpanel/Controller_stockup/delete_ajax/$1';
$route['stockup-add'] = 'adminpanel/Controller_stockup/add';
$route['stockup-update/(:num)'] = 'adminpanel/Controller_stockup/add/$1';

/* REFILL YOUR PANTRY */
$route['pantry'] = 'adminpanel/Controller_pantry';
$route['pantry-delete/(:num)'] = 'adminpanel/Controller_pantry/delete_ajax/$1';
$route['pantry-add'] = 'adminpanel/Controller_pantry/add';
$route['pantry-update/(:num)'] = 'adminpanel/Controller_pantry/add/$1';


/* ADVERTISEMENT */
$route['advertises'] = 'adminpanel/Controller_adver_top';
$route['advertises-delete/(:num)'] = 'adminpanel/Controller_adver_top/delete_ajax/$1';
$route['advertises-add'] = 'adminpanel/Controller_adver_top/add';
$route['advertises-update/(:num)'] = 'adminpanel/Controller_adver_top/add/$1';


/* HOME BANNER */
$route['homebanners'] = 'adminpanel/Controller_home_banner';
$route['homebanners-delete/(:num)'] = 'adminpanel/Controller_home_banner/delete_ajax/$1';
$route['homebanners-add'] = 'adminpanel/Controller_home_banner/add';
$route['homebanners-update/(:num)'] = 'adminpanel/Controller_home_banner/add/$1';

/* NEW SAVINGS */
$route['new_savings'] = 'adminpanel/Controller_new_savings';
$route['new_savings-delete/(:num)'] = 'adminpanel/Controller_new_savings/delete_ajax/$1';
$route['new_savings-add'] = 'adminpanel/Controller_new_savings/add';
$route['new_savings-update/(:num)'] = 'adminpanel/Controller_new_savings/add/$1';

/* FRESH VEGETABLES & FRUITS */
$route['fresh_veg'] = 'adminpanel/Controller_fresh_veg';
$route['fresh_veg-delete/(:num)'] = 'adminpanel/Controller_fresh_veg/delete_ajax/$1';
$route['fresh_veg-add'] = 'adminpanel/Controller_fresh_veg/add';
$route['fresh_veg-update/(:num)'] = 'adminpanel/Controller_fresh_veg/add/$1';

/* VRAJ BAKERY */
$route['vraj_bakery'] = 'adminpanel/Controller_vraj_bakery';
$route['vraj_bakery-delete/(:num)'] = 'adminpanel/Controller_vraj_bakery/delete_ajax/$1';
$route['vraj_bakery-add'] = 'adminpanel/Controller_vraj_bakery/add';
$route['vraj_bakery-update/(:num)'] = 'adminpanel/Controller_vraj_bakery/add/$1';

/* SHOP AYURVEDIC */
$route['shop_ayurvedic'] = 'adminpanel/Controller_shop_ayurvedic';
$route['shop_ayurvedic-delete/(:num)'] = 'adminpanel/Controller_shop_ayurvedic/delete_ajax/$1';
$route['shop_ayurvedic-add'] = 'adminpanel/Controller_shop_ayurvedic/add';
$route['shop_ayurvedic-update/(:num)'] = 'adminpanel/Controller_shop_ayurvedic/add/$1';


/* Home Product Slider */
$route['homep_slider'] = 'adminpanel/Controller_home_product_slider';
$route['homep_slider-delete/(:num)'] = 'adminpanel/Controller_home_product_slider/delete_ajax/$1';
$route['homep_slider-add'] = 'adminpanel/Controller_home_product_slider/add';
$route['homep_slider-update/(:num)'] = 'adminpanel/Controller_home_product_slider/add/$1';

/* Home Product Slider Item*/
$route['homep_slider_item'] = 'adminpanel/Controller_product_slider_item';
$route['homep_slider_item-delete/(:num)'] = 'adminpanel/Controller_product_slider_item/delete_ajax/$1';
$route['homep_slider_item-add'] = 'adminpanel/Controller_product_slider_item/add';
$route['homep_slider_item-update/(:num)'] = 'adminpanel/Controller_product_slider_item/add/$1';

/* Banner Top Section */
$route['banner-top'] = 'adminpanel/Controller_banner_top/top_banner_data';

/* Order Reports */
$route['order_reports'] = 'adminpanel/Controller_orderreport/index';
$route['cat_reports'] = 'adminpanel/Controller_catreport/index';
$route['prod_reports'] = 'adminpanel/Controller_prodreport/index';
$route['brand_reports'] = 'adminpanel/Controller_brandreport/index';
$route['custom_reports'] = 'adminpanel/Controller_customreport/index';
$route['tax_reports'] = 'adminpanel/Controller_taxreport/index';
?>