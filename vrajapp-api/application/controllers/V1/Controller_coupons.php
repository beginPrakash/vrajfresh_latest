<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Controller_coupons extends CI_Controller

{

	public function __construct()

	{

		parent::__construct();

		header("Access-Control-Allow-Headers: content-type,Content-Type,X-Custom-Header, Upgrade-Insecure-Requests,Accept,x-requested-with");

		header('Content-Type: application/json');

		header('Access-Control-Allow-Credentials: true');

		header('Access-Control-Max-Age: 60');

		header('Access-Control-Allow-Headers: AccountKey,x-requested-with, Content-Type, content-type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2');

		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

		$this->load->model('coupons_model');

		$this->load->model('products_model');

	}

	public function get_coupon_amount()

	{

		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

 		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}
		$errors = $success_message = '';

		$ArrData = array();

		$applied_flag = '';

		if (check_oauth_key($oauth_key)) {

			if ($json_obj->coupon_code != '') {

				$customer_id = $json_obj->customer_id;

				$product_ids = $json_obj->product_ids;

				$order_amount = (float) $json_obj->order_amount;

				$data = array(

					'coupon_code' => $json_obj->coupon_code,

					'order_amount' => $order_amount,

				);

				$result = $this->coupons_model->get_coupon_by_code($data);

				//echo "customer_id".$customer_id;exit;

				//echo $order_amount;echo "<pre>";print_r($result);exit;

				if (count($result) > 0) {

					$today = strtotime(date("Y-m-d"));

					$start_date = strtotime($result[0]->start_from);

					$end_date = strtotime($result[0]->valid_upto);



					if ($start_date <= $today && $end_date >= $today) {

						$applied_flag = 0;



						#----------CHECK MINIMUM ORDER VALUE

						if ($result[0]->minimum_order_value > 0) {

							if ($result[0]->minimum_order_value <= $order_amount) {

								$applied_flag = 0;

							} else {

								$applied_flag = 1;

								$errors = 'Sorry..! Cart amount should be more than $' . $result[0]->minimum_order_value;

							}

						}



						#---------------------CHECK PROMO TYPE

						if ($result[0]->promotional_type == 'M' && $applied_flag == 0) // multiple use

						{

							$applied_flag = 0;

						} else if ($result[0]->promotional_type == 'S' && $applied_flag == 0) //single

						{

							#check current customer eligible or not for this coupon code

							if (isset($customer_id) && $customer_id > 0) {

								if ($this->coupons_model->alReadyUsed($customer_id, $result[0]->promotional_code_id)) {

									$applied_flag = 1;

									$errors = 'Sorry..! you already used this code.';

									send_response_to_api($ArrData, $errors, $success_message);

									return false;

								}

							} else {

								$applied_flag = 1;

								$errors = 'Sorry..! please login and apply code again.';

							}

						} else if ($result[0]->promotional_type == 'OT' && $applied_flag == 0) //one time

						{

							#check current customer eligible or not for this coupon code

							if ($customer_id > 0) {

								if ($this->coupons_model->alReadyUsed($customer_id, $result[0]->promotional_code_id)) {

									$applied_flag = 1;

									$errors = 'Sorry..! you already used this code.';

									send_response_to_api($ArrData, $errors, $success_message);

									return false;

								}

							} else {

								$applied_flag = 1;

								$errors = 'Sorry..! please login and apply code again.';

							}

						}



						#---------------------CHECK APPLY TO

						if ($result[0]->apply_to == 'AC' && $applied_flag == 0) // for all customer

						{

							$applied_flag = 0;

						} elseif ($result[0]->apply_to == 'SG' && $applied_flag == 0) // for specific customer

						{

							if ($customer_id > 0) {

								/*get code's selected client group*/

								$ArrSelectedClientGroup = $this->coupons_model->getPromotionalCodeClientGroup($result[0]->promotional_code_id);

								#make selected client group ids array

								$ArrSelectedClientGroupId = array();

								if (is_array($ArrSelectedClientGroup) && count($ArrSelectedClientGroup) > 0) {

									foreach ($ArrSelectedClientGroup as $clientgroup) {

										$ArrSelectedClientGroupId[] = $clientgroup['clientgroup_id'];

									}

								}

								//echo "<pre>";print_r($ArrSelectedClientGroupId);exit;

								/*get client group of code*/

								$ArrGroupCustomerIds = $this->coupons_model->getGroupCustomerIds($ArrSelectedClientGroupId);

								//echo "<pre>";print_r($ArrGroupCustomerIds);exit;



								/*check customer exits in group or not*/

								if (is_array($ArrGroupCustomerIds) && count($ArrGroupCustomerIds) > 0) {

									if (in_array($customer_id, $ArrGroupCustomerIds)) {

										$applied_flag = 0;

									} else {

										$applied_flag = 1;

										$errors = 'Sorry. Entered coupon code is not valid.';

									}

								}

							} else {

								$applied_flag = 1;

								$errors = 'Sorry..! please login and apply code again.';

							}

						} elseif ($result[0]->apply_to == 'NC' && $applied_flag == 0) // for all customer
						{

							$ord_cnt = $this->coupons_model->cust_order_count($customer_id);
							if((int) $ord_cnt < 1){
								$applied_flag = 0;
							}else{
								$applied_flag = 1;

								$errors = 'Sorry. Entered coupon code is not valid.';
							}
							
						} elseif ($result[0]->apply_to == 'RC' && $applied_flag == 0) // for all customer
						{

							$ord_cnt = $this->coupons_model->cust_order_count($customer_id);
							
							if((int) $ord_cnt > 1){
								$applied_flag = 0;
							}else{
								$applied_flag = 1;

								$errors = 'Sorry. Entered coupon code is not valid.';
							}
							
						}




						if ($applied_flag == 0) {

							#-------------------------CHECK BRAND AND EXCLUDE CATEGORY

							$total_applicapble_order_amount = 0;

							$ArrPromoBrand = $ArrPromoCategory = array();

							if ($result[0]->brand_ids != '') {

								$ArrPromoBrand = explode(",", $result[0]->brand_ids);

							}

							if ($result[0]->exclude_category != '') {

								$ArrPromoCategory = explode(",", $result[0]->exclude_category);

							}

							$product_ids = json_decode(json_encode($product_ids), true);

							foreach ($product_ids as $product_id => $price) {

								$brand = 'y';

								$category = 'y';

								if ($result[0]->brand_ids != '') {

									$ArrProductBrandData = $this->products_model->get_product_brand($product_id);

									if (is_array($ArrProductBrandData) && count($ArrProductBrandData) > 0) {

										foreach ($ArrProductBrandData as $a) {

											$brand_id = $a['brand_id'];

											if (in_array($brand_id, $ArrPromoBrand)) {

												$brand = 'y';

											} else {

												$brand = 'n';

											}

										}

									}

								}



								if ($result[0]->exclude_category != '') {

									$ArrProductCategoryData = $this->products_model->get_product_category($product_id);

									if (is_array($ArrProductCategoryData) && count($ArrProductCategoryData) > 0) {

										foreach ($ArrProductCategoryData as $a) {

											$category_id = $a['category_id'];

											if (in_array($category_id, $ArrPromoCategory)) {

												$category = 'y';

											} else {

												$category = 'n';

											}

										}

									}

								}



								//echo "<br>".$price.":".$brand.":".$category."=".$total_applicapble_order_amount;

								//echo "<br>".$price.":".$brand.":".$category."=".$total_applicapble_order_amount;
								if ($result[0]->brand_ids != '' && $result[0]->exclude_category != '') {
									if ($brand == 'y' && $category == 'n') {

										$total_applicapble_order_amount = $total_applicapble_order_amount + $price;

									}
								}else if ($result[0]->brand_ids != '' && $result[0]->exclude_category == '') {
									if ($brand == 'y') {
										$total_applicapble_order_amount = $total_applicapble_order_amount + $price;

									}
								}else if ($result[0]->brand_ids == '' && $result[0]->exclude_category != '') {
									if ($category == 'n') {
										$total_applicapble_order_amount = $total_applicapble_order_amount + $price;

									}
								}else if ($brand == 'y' && $category == 'y') {
									$total_applicapble_order_amount = $total_applicapble_order_amount + $price;
								}

							}

						}



						if ($applied_flag == 0) {

							if ($result[0]->discount_type == "$") {

								$discount_amount = (float) $result[0]->discount_value;

								if ($result[0]->maximum_order_discount > 0 && $discount_amount > $result[0]->maximum_order_discount) {

									$discount_amount = $result[0]->maximum_order_discount;

								}

								$total_order_amount = (float) ($order_amount - $discount_amount);

								$ArrData = array('discount_amount' => number_format($discount_amount, 2), 'order_amount' => number_format($total_order_amount, 2), 'coupon_id' => $result[0]->promotional_code_id);

								$success_message = 'Congratulations! Coupon has been applied successfully';

							} else {

								// 	print_r($total_applicapble_order_amount);

								$discount_amount = ((float) $result[0]->discount_value / 100) * $total_applicapble_order_amount;

								if ($result[0]->maximum_order_discount > 0 && $discount_amount > $result[0]->maximum_order_discount) {

									$discount_amount = $result[0]->maximum_order_discount;

								}

								$total_order_amount = (float) ($order_amount - $discount_amount);

								$ArrData = array('discount_amount' => number_format($discount_amount, 2), 'order_amount' => number_format($total_order_amount, 2), 'coupon_id' => $result[0]->promotional_code_id);

								$success_message = 'Congratulations! Coupon has been applied successfully';

							}

						}



						if ($applied_flag == 1) {

							$discount_amount = 0;

							$total_order_amount = (float) ($order_amount - $discount_amount);

							$ArrData = array('discount_amount' => number_format($discount_amount, 2), 'order_amount' => number_format($total_order_amount, 2), 'coupon_id' => $result[0]->promotional_code_id);

						}



					} else {

						$errors = "Sorry. Entered coupon code is not valid.";

					}

				} else {

					$errors = 'Sorry. Entered coupon code is not valid.';

				}

			} else {

				$errors = 'Please enter coupon code.';

			}

			if ($applied_flag == 1) {

				$ArrData = array();

			}

			send_response_to_api($ArrData, $errors, $success_message);
		}

	}

}