<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
class Controller_orders extends CI_Controller
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
		$this->load->model('orders_model');
		$this->load->model('coupons_model');
		$this->load->model('users_model');
		$this->load->model('cashcredit_model');
		$this->load->model('credittransaction_model');
		$this->load->model('Master_model', 'master');
		//error_reporting(0);
	}
	
	public function get_orders()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
				$data = array(
					'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active_only))),
					'search_keyword' => $json_obj->search_keyword,
					'limit' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->limit))),
					'page_no' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->page_no))),
					'sort_column' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '_', $json_obj->sort_column))),
					'sort_order' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->sort_order)))
				);
				//var_dump($json_obj->search_keyword);exit;
				if ($json_obj->search_keyword == "") {

					$result = $this->orders_model->get_orders($data);
				} else {
					$result = $this->orders_model->get_orders_by_search($data, $json_obj->search_keyword);
				}
				if (count($result) > 0) {
					$ArrData = $result;
					$success_message = '';
				} else {
					$errors = 'No Data Available';
				}
			} catch (Exception $e) {
				$ArrData = "There is problem";
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function update_checkout_address()
	{
		$errors = $success_message = '';

		$json_str = $this->input->raw_input_stream;		
		$json_obj = json_decode($json_str, 1);
		$ArrData = [];
		$oauth_key = $json_obj['oauth_key'];
		$ArrAddress = $json_obj['ArrAddress'];
		$AddressType = $json_obj['AddressType'];
		$Address_id = $json_obj['Address_id'];
		
		if (check_oauth_key($oauth_key)) {
			if(!empty($ArrAddress) && $Address_id > 0){
				
				$this->master->update_detail('tbl_'.strtolower($AddressType).'_address', $ArrAddress, array(strtolower($AddressType).'_id' => $Address_id));
				$success_message = $AddressType.' address updated successfully.';
				$ArrData['updated_id'] = $Address_id;
			}
		}
		send_response_to_api($ArrData, $errors, $success_message);

	}

	public function add_checkout_address()
	{
		$errors = $success_message = '';

		$json_str = $this->input->raw_input_stream;		
		$json_obj = json_decode($json_str, 1);
		$ArrData = [];
		$oauth_key = $json_obj['oauth_key'];
		$ArrAddress = $json_obj['ArrAddress'];
		$AddressType = $json_obj['AddressType'];
		
		if (check_oauth_key($oauth_key)) {
			if(!empty($ArrAddress)){
				
				$ArrData['inserted_id'] = $this->master->insertData('tbl_'.strtolower($AddressType).'_address', $ArrAddress);exit;
				$success_message = $AddressType.' address added successfully.';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_checkout_details()
	{
		
		$errors = $success_message = '';

		$json_str = $this->input->raw_input_stream;		
		$json_obj = json_decode($json_str);
		

		$oauth_key = $json_obj->oauth_key;
		$user_id = $json_obj->user_id;
		
		$ArrData = array();
		
		if (check_oauth_key($oauth_key)) {
			
			$NewArrData['billing_address'] = array();
			$NewArrData['shipping_address'] = array();
			$NewArrData['cards'] = array();
			$NewArrData['shipping_id'] = 0;
			$NewArrData['billing_id'] = 0;
			$NewArrData['card_id'] = 0;
			$NewArrData['usercart_arr'] = $json_obj->cart_data;
			
			//get total credit sum
			$credit_total_date = $this->credittransaction_model->get_credit_sum($user_id);
			$credit_total = ($credit_total_date['amount'] > 0) ? $credit_total_date['amount'] : 0.00;
			$NewArrData['earned_credit'] = $credit_total;

			//get last credit per
			$find_last_credit =  $this->cashcredit_model->get_last_creditdetail();
			$last_credit_per = $find_last_credit['credit_per'] ?? 0;
			$NewArrData['last_credit_per'] = $last_credit_per;


			$UserDetails = $this->master->get_row_detail('tbl_users', array('user_id' => $user_id));
			if(!empty($user_id)){
				$newInsertArr = array(
					'user_id' => $user_id,
					'cart_arr' => $json_obj->cart_data,
					'created_datetime' => date('Y-m-d H:i:s'),
					'user_email'=>$UserDetails['email'] ?? '',
				);
				$this->master->insertData('tbl_cart_to_checkout_log', $newInsertArr);
				
			}
			
			$shiiping_address = $this->master->get_list_of_data('tbl_shipping_address', array('user_id' => $user_id, 'is_active' => 1));
			if(empty($shiiping_address)){
				if(!empty($UserDetails)){
					if($UserDetails['shipping_street_address'] != "" && $UserDetails['shipping_street_address'] != null && $UserDetails['shipping_state'] != "" && $UserDetails['shipping_state'] != null){
						$newInsertArr = array(
							'user_id' => $user_id,
							'first_name' => $UserDetails['first_name'],
							'last_name' => $UserDetails['last_name'],
							'shipping_street_address' => $UserDetails['shipping_street_address'],
							'shipping_apartment' => $UserDetails['shipping_apartment'],
							'shipping_city' => $UserDetails['shipping_city'],
							'shipping_state_id' => $UserDetails['shipping_state'],
							'shipping_country_id' => 235,
							'shipping_zipcode' => $UserDetails['shipping_zip_code'],
							'shipping_phone' => $UserDetails['shipping_phone'],
							'modify_at' => date('Y-m-d H:i:s'),
							'is_active' => 1,
						);
						//$this->master->insertData('tbl_shipping_address', $newInsertArr);
						$shiiping_address = $this->master->get_list_of_data('tbl_shipping_address', array('user_id' => $user_id, 'is_active' => 1));
					}
					
				}
			}
			if(!empty($shiiping_address)){
				for($i = 0; $i < count($shiiping_address); $i++){
					$shiiping_address[$i]->state_name = "";
					$shiiping_address[$i]->tax = 0.00;
					$state_details = $this->master->get_row_detail('state', array('state_id' => $shiiping_address[$i]->shipping_state_id));
					if(!empty($state_details)){
						$shiiping_address[$i]->state_name = $state_details['state'];
						$shiiping_address[$i]->tax = $state_details['tax'];
					}
					
					$last_index =  count($shiiping_address) - 1;
					if($last_index == $i){
						$NewArrData['shipping_id'] = $shiiping_address[$i]->shipping_id;
					}
				}
			}
			$NewArrData['shipping_address'] = $shiiping_address;

			$billing_address = $this->master->get_list_of_data('tbl_billing_address', array('user_id' => $user_id, 'is_active' => 1));
			if(empty($billing_address)){
				if($UserDetails['address'] != "" && $UserDetails['address'] != null && $UserDetails['state'] != "" && $UserDetails['state'] != null){
					
					$newInsertArr = array(
						'user_id' => $user_id,
						'first_name' => $UserDetails['first_name'],
						'last_name' => $UserDetails['last_name'],
						'billing_street_address' => $UserDetails['address'],
						'billing_apartment' => $UserDetails['address2'],
						'billing_city' => $UserDetails['city'],
						'billing_state_id' => $UserDetails['state'],
						'billing_country_id' => $UserDetails['country_id'],
						'billing_zipcode' => $UserDetails['zip'],
						'billing_phone' => $UserDetails['phone'],
						'modify_at' => date('Y-m-d H:i:s'),
						'is_active' => 1,
					);
					//$this->master->insertData('tbl_billing_address', $newInsertArr);
					$billing_address = $this->master->get_list_of_data('tbl_billing_address', array('user_id' => $user_id, 'is_active' => 1));
				}
			}
			if(!empty($billing_address)){
				for($i = 0; $i < count($billing_address); $i++){
					$billing_address[$i]->state_name = "";
					$state_details = $this->master->get_row_detail('state', array('state_id' => $billing_address[$i]->billing_state_id));
					if(!empty($state_details)){
						$billing_address[$i]->state_name = $state_details['state'];
					}
					$last_index =  count($billing_address) - 1;
					if($last_index == $i){
						$NewArrData['billing_id'] = $billing_address[$i]->billing_id;
					}
				}
			}
			$NewArrData['billing_address'] = $billing_address;
			$cards = $this->master->get_list_of_data('tbl_cards', array('user_id' => $user_id, 'is_active' => 1));
			if(!empty($cards)){
				for($i = 0; $i < count($cards); $i++){
					$last_index =  count($cards) - 1;
					if($last_index == $i){
						$NewArrData['card_id'] = $cards[$i]->card_id;
					}
				}
			}
			$NewArrData['cards'] = $cards;
			
			$ArrData = $NewArrData;
			$success_message = 'Checkout data';
		} else {
			$errors = "Data not found";
		}

		send_response_to_api($ArrData, $errors, $success_message);
	}

	public function get_checkout_address_details()
	{
		
		$errors = $success_message = '';

		$json_str = $this->input->raw_input_stream;		
		$json_obj = json_decode($json_str);
		

		$oauth_key = $json_obj->oauth_key;
		$user_id = $json_obj->user_id;
		$id = $json_obj->id;
		$type = $json_obj->type;

		$ArrData = array();

		if (check_oauth_key($oauth_key)) {

			if($type != "" && $id != "" && $user_id != ""){
				
				$details = $this->master->get_row_detail('tbl_'.$type.'_address', array($type.'_id' => $id));

				$success_message = $type.' address details';
				$ArrData = $details;

			} else {
				$errors = "Please enter proper parameters";
			}

		}
		send_response_to_api($ArrData, $errors, $success_message);
	}

	public function add_order()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();

		if (check_oauth_key($oauth_key)) {

			if ($json_obj->ArrCustomer->user_id == 0) {
				/* $this->load->model('users_model');
				$user_data = array(
					'email' => trim($json_obj->ArrCustomer->shipping_email),
					'user_role_id' => '4'
				);
				$user_exist = $this->users_model->user_exist($user_data);
				if ($user_exist > 0) {
					$user_detail = $this->users_model->get_user_by_email($user_data);
					$user_id = $user_detail[0]->user_id;

					$ArrUserData = array(
						'address' => trim($json_obj->ArrCustomer->billing_street_name),
						'address2' => trim($json_obj->ArrCustomer->billing_apartment_name),
						'city' => trim($json_obj->ArrCustomer->billing_city),
						'state' => trim($json_obj->ArrCustomer->billing_state_id),
						'country_id' => 235,
						'zip' => trim($json_obj->ArrCustomer->billing_zipcode),
						'phone' => trim($json_obj->ArrCustomer->billing_phone),
						'shipping_street_address' => trim($json_obj->ArrCustomer->shipping_street_name),
						'shipping_apartment' => trim($json_obj->ArrCustomer->shipping_apartment_name),
						'shipping_city' => trim($json_obj->ArrCustomer->shipping_city),
						'shipping_state' => trim($json_obj->ArrCustomer->shipping_state_id),
						'shipping_zip_code' => trim($json_obj->ArrCustomer->shipping_zipcode),
						'shipping_phone' => trim($json_obj->ArrCustomer->shipping_phone),
					);
					$this->users_model->update_user($ArrUserData, $user_id, 'tbl_users');


				} else {
					$new_password = rand(100000, 999999);
					$ArrUserData = array(
						'user_role_id' => '4',
						'user_name' => trim($json_obj->ArrCustomer->shipping_email),
						'first_name' => trim($json_obj->ArrCustomer->shipping_first_name),
						'last_name' => trim($json_obj->ArrCustomer->shipping_last_name),
						'display_name' => trim($json_obj->ArrCustomer->shipping_first_name . ' ' . $json_obj->ArrCustomer->shipping_last_name),
						'password' => md5($new_password),
						'email' => trim($json_obj->ArrCustomer->shipping_email),
						'address' => trim($json_obj->ArrCustomer->billing_street_name),
						'address2' => trim($json_obj->ArrCustomer->billing_apartment_name),
						'city' => trim($json_obj->ArrCustomer->billing_city),
						'state' => trim($json_obj->ArrCustomer->billing_state_id),
						'country_id' => 235,
						'zip' => trim($json_obj->ArrCustomer->billing_zipcode),
						'mobile_no' => trim($json_obj->ArrCustomer->billing_phone),
						'phone' => trim($json_obj->ArrCustomer->billing_phone),
						'shipping_street_address' => trim($json_obj->ArrCustomer->shipping_street_name),
						'shipping_apartment' => trim($json_obj->ArrCustomer->shipping_apartment_name),
						'shipping_city' => trim($json_obj->ArrCustomer->shipping_city),
						'shipping_state' => trim($json_obj->ArrCustomer->shipping_state_id),
						'shipping_zip_code' => trim($json_obj->ArrCustomer->shipping_zipcode),
						'shipping_phone' => trim($json_obj->ArrCustomer->shipping_phone),
						'created_datetime' => date('Y-m-d H:i:s'),
						'is_active' => 1
					);
					$user_id = $this->users_model->add_user($ArrUserData, 'tbl_users');
				} */

				$errors = 'Order Not Added Successfully';
				
			} else {
				$user_id = trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->ArrCustomer->user_id)));
				//send_response_to_api($ArrData, $errors, 'ttt'.$user_id);exit;
			}
			if(isset($json_obj->ArrCustomer->is_active) && $json_obj->ArrCustomer->is_active != ""){
				$is_active = trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->ArrCustomer->is_active)));
			} else {
				$is_active = 1;
			}

			$same_address = $json_obj->ArrCustomer->same_address;
			$billing_id = $json_obj->ArrCustomer->billing_id;
			$shipping_id = $json_obj->ArrCustomer->shipping_id;
			$card_id = $json_obj->ArrCustomer->card_id;
			$save_card = $json_obj->ArrCustomer->save_card;
			$CardToken = $json_obj->ArrCustomer->CardToken;
			$CardPaymentMethodId = $json_obj->ArrCustomer->CardPaymentMethod;
			$StripeCardID = $json_obj->ArrCustomer->StripeCardID;
			$substitution_product_ids = $json_obj->ArrCustomer->substitution_product_ids;

			//echo '<pre>';print_r($shipping_id);exit;
			$billing_details = $this->master->get_row_detail('tbl_billing_address', array('billing_id' => $billing_id));
			$shipping_details = $this->master->get_row_detail('tbl_shipping_address', array('shipping_id' => $shipping_id));
			$user_details = $this->master->get_row_detail('tbl_users', array('user_id' => $user_id));

			if($same_address == 1){
				$billing_first_name = ($shipping_details) ? $shipping_details['first_name'] : '';
				$billing_last_name = ($shipping_details) ? $shipping_details['last_name'] : '';
				$billing_street_name = ($shipping_details) ? $shipping_details['shipping_street_address'] : '';
				$billing_apartment_name = ($shipping_details) ? $shipping_details['shipping_apartment'] : '';
				$billing_city = ($shipping_details) ? $shipping_details['shipping_city'] : '';
				$billing_state_id = ($shipping_details) ? $shipping_details['shipping_state_id'] : 0;
				$billing_zipcode = ($shipping_details) ? $shipping_details['shipping_zipcode'] : '';
				$billing_country = ($shipping_details) ? $shipping_details['shipping_country_id'] : '';
				$billing_phone = ($shipping_details) ? $shipping_details['shipping_phone'] : '';
				$billing_email = ($user_details) ? $user_details['email'] : '';
			} else {
				$billing_first_name = ($billing_details) ? $billing_details['first_name'] : '';
				$billing_last_name = ($billing_details) ? $billing_details['last_name'] : '';
				$billing_street_name = ($billing_details) ? $billing_details['billing_street_address'] : '';
				$billing_apartment_name = ($billing_details) ? $billing_details['billing_apartment'] : '';
				$billing_city = ($billing_details) ? $billing_details['billing_city'] : '';
				$billing_state_id = ($billing_details) ? $billing_details['billing_state_id'] : 0;
				$billing_zipcode = ($billing_details) ? $billing_details['billing_zipcode'] : '';
				$billing_country = ($billing_details) ? $billing_details['billing_country_id'] : '';
				$billing_phone = ($billing_details) ? $billing_details['billing_phone'] : '';
				$billing_email = ($user_details) ? $user_details['email'] : '';
			}

			$shipping_first_name = ($shipping_details) ? $shipping_details['first_name'] : '';
			$shipping_last_name = ($shipping_details) ? $shipping_details['last_name'] : '';
			$shipping_street_name = ($shipping_details) ? $shipping_details['shipping_street_address'] : '';
			$shipping_apartment_name = ($shipping_details) ? $shipping_details['shipping_apartment'] : '';
			$shipping_city = ($shipping_details) ? $shipping_details['shipping_city'] : '';
			$shipping_state_id = ($shipping_details) ? $shipping_details['shipping_state_id'] : 0;
			$shipping_zipcode = ($shipping_details) ? $shipping_details['shipping_zipcode'] : '';
			$shipping_country = ($shipping_details) ? $shipping_details['shipping_country_id'] : '';
			$shipping_phone = ($shipping_details) ? $shipping_details['shipping_phone'] : '';
			$shipping_email = ($user_details) ? $user_details['email'] : '';

			$ArrUserData = array(
				'address' => trim($billing_street_name),
				'address2' => trim($billing_apartment_name),
				'city' => trim($billing_city),
				'state' => trim($billing_state_id),
				'country_id' => 235,
				'zip' => $billing_zipcode,
				'phone' => trim($billing_phone),
				'shipping_street_address' => trim($shipping_street_name),
				'shipping_apartment' => trim($shipping_apartment_name),
				'shipping_city' => trim($shipping_city),
				'shipping_state' => trim($shipping_state_id),
				'shipping_zip_code' => $shipping_zipcode,
				'shipping_phone' => trim($shipping_phone),
			);

			//echo '<pre>';print_r($ArrUserData);exit;
			$this->users_model->update_user($ArrUserData, $user_id, 'tbl_users');

			$data = array(
				'user_id' => $user_id,
				'coupon_id' => $json_obj->ArrCustomer->coupon_id,
				'order_datetime' => date('Y-m-d H:i:s'),
				'order_status' => trim('Pending Payment'),
				'order_notes' => $json_obj->ArrCustomer->order_notes,
				'delivery_comments' => $json_obj->ArrCustomer->delivery_comments,
				'discount_amount' => $json_obj->ArrCustomer->discount_amount,
				'billing_first_name' => $billing_first_name,
				'billing_last_name' => $billing_last_name,
				'billing_street_name' => $billing_street_name,
				'billing_apartment_name' => $billing_apartment_name,
				'billing_city' => $billing_city,
				'billing_state_id' => $billing_state_id,
				'billing_zipcode' => $billing_zipcode,
				'billing_country' => $billing_country,
				'billing_phone' => $billing_phone,
				'billing_email' => $billing_email,
				'shipping_first_name' => $shipping_first_name,
				'shipping_last_name' => $shipping_last_name,
				'shipping_street_name' => $shipping_street_name,
				'shipping_apartment_name' => $shipping_apartment_name,
				'shipping_city' => $shipping_city,
				'shipping_state_id' => $shipping_state_id,
				'shipping_zipcode' => $shipping_zipcode,
				'shipping_country' => $shipping_country,
				'shipping_phone' => $shipping_phone,
				'shipping_email' => $shipping_email,
				'delivery_type' => trim($json_obj->ArrCustomer->delivery_type),
				'payment_methodtype' => trim($json_obj->ArrCustomer->payment_methodtype ?? ''),
				'delivery_datetime' => date('Y-m-d', strtotime($json_obj->ArrCustomer->delivery_datetime)),
				'is_replace_item' => trim($json_obj->ArrCustomer->is_replace_item),
				'substitution_product_ids' => $substitution_product_ids,
				'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $user_id))),
				'is_flag' => 1,
				'billing_id' => $billing_id,
				'shipping_id' => $shipping_id,
				'same_address' => $same_address,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $is_active,
				'order_platform' => 'web'
			);
			$result = $this->orders_model->add_order($data, 'tbl_orders');

			if(!empty($user_details['user_id'])){
				$newInsertArr = array(
					'user_id' => $user_details['user_id'],
					'cart_arr' => $json_obj->cart_arr,
					'created_datetime' => date('Y-m-d H:i:s'),
					'user_email'=>$user_details['email'] ?? '',
				);
				$this->master->insertData('tbl_checkout_log', $newInsertArr);
				
			}

			$total_weight = 0;
			$total_order_amount = 0;
			foreach ($json_obj->ArrProduct as $product) {
				$total_weight += trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', floatval($product->product_weight_gms) * floatval($product->qty))));
				$order_product_data = array(
					"order_id" => $result,
					"product_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_id))),
					/*"product_name" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_name))),*/
					"product_name" => $product->product_name,
					"unit_price" => trim($product->unit_price),
					"product_tax_amount" => trim($product->product_tax_amount),
					"product_tax" => $product->product_tax,
					//"product_variant_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_variant_id))),
					"product_variant_id" => ($product->product_variant_id) ? $product->product_variant_id : 0,
					"qty" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->qty))),
					'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->created_by))),
					'created_datetime' => date('Y-m-d H:i:s'),
					'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->is_active))),
					'total_amount' => trim($product->total_amount)
				);
				$result_product_id = $this->orders_model->add_order($order_product_data, 'tbl_order_products');
				$total_order_amount += $product->total_amount;
			}

			$shipping_charge = 0;
			// $fedex_shipping_charge=get_rate_by_weight($json_obj->qty,$json_obj->dimension,$total_weight,$json_obj->shipping_city,$json_obj->stateOrProvinceCode,$json_obj->shipping_zipcode,'US');
			// if($fedex_shipping_charge->output->rateReplyDetails[0]->ratedShipmentDetails[0]->totalNetFedExCharge != null)
			// {
			// 	$shipping_charge = $fedex_shipping_charge->output->rateReplyDetails[0]->ratedShipmentDetails[0]->totalNetFedExCharge;
			// }
			// else
			// {
			// 	return $fedex_shipping_charge->errors[0]->message;
			// }
			//$order_total_amount=$total_order_amount+$shipping_charge+$json_obj->ArrCustomer->order_tip+$json_obj->order_total_tax-$json_obj->discount_amount;
			$order_total_amount = $json_obj->ArrCustomer->cart_total;
			$discount_amount = $json_obj->ArrCustomer->discount_amount;
			$preparation_cost = $json_obj->ArrCustomer->preparation_cost;
			$packaging_cost = $json_obj->ArrCustomer->packaging_cost;
			$state_tax = $json_obj->ArrCustomer->state_tax;
			$coupon_id = $json_obj->ArrCustomer->coupon_id;
			$fedex_tracking_id = 0;
			$earned_credit_checkbox = $json_obj->ArrCustomer->earned_credit_checkbox;
			/*$track_id = get_ship();
					 if ($track_id->output->transactionShipments[0]->masterTrackingNumber != null) {
						 $fedex_tracking_id = $track_id->output->transactionShipments[0]->masterTrackingNumber;
					 } else {
						 return $track_id->errors[0]->message;
					 }*/
			

			$order_data = array(
				'order_amount' => trim($total_order_amount),
				'fedex_tracking_id' => trim($fedex_tracking_id),
				'fedex_shipping_charge' => trim($shipping_charge),
				'order_tip' => trim($json_obj->ArrCustomer->order_tip),
				//'order_total_tax' =>trim($json_obj->order_total_tax),
				'coupon_id' => trim($coupon_id),
				'discount_amount' => trim($discount_amount),
				'order_total_amount' => trim($order_total_amount),
				'blocked_amount' => trim($order_total_amount),
				'preparation_cost' => trim($preparation_cost),
				'packaging_cost' => trim($packaging_cost),
				//'state_tax' => trim($state_tax),
				'state_tax' => $state_tax,
				//	'fedex_raw_response' =>$fedex_shipping_charge,
				//	'stripe_raw_response' =>''
			);
			$order_id = $this->orders_model->update_order($order_data, $result, 'tbl_orders');

			//find credits percentage
			$find_credit_per =  $this->cashcredit_model->get_last_creditdetail();
			$find_credit_id = $this->cashcredit_model->get_last_creditid();
			$earned_credit_val = 0;
			$used_credit_val = 0;
			//calculate credit percentage
			if(!empty($total_order_amount) && !empty($find_credit_per['credit_per'])){
				
				$earned_credit_val = ($find_credit_per['credit_per'] / 100) * $order_data['order_total_amount'];
				$earned_credit_val = number_format($earned_credit_val,2);
			}
			//print_r($find_credit_per);exit;

			if(!empty($user_details['user_id'])){
				$newInsertArr = array(
					'user_id' => $user_details['user_id'],
					'cart_arr' => $json_obj->cart_arr,
					'created_datetime' => date('Y-m-d H:i:s'),
					'user_email'=>$user_details['email'] ?? '',
					'blocked_amount'=>trim($order_total_amount),
				);
				$this->master->insertData('tbl_payment_blocked_log', $newInsertArr);
				
			}
			
			$billing_details = $this->master->get_row_detail('tbl_billing_address', array('billing_id' => $billing_id));
			$shipping_details = $this->master->get_row_detail('tbl_shipping_address', array('shipping_id' => $shipping_id));

			$billing_id = $json_obj->ArrCustomer->billing_id;
			$shipping_id = $json_obj->ArrCustomer->shipping_id;
			$card_id = $json_obj->ArrCustomer->card_id;
			$save_card = $json_obj->ArrCustomer->save_card;
			$CardToken = $json_obj->ArrCustomer->CardToken;
			$CardPaymentMethodId = $json_obj->ArrCustomer->CardPaymentMethod;
			$StripeCardID = $json_obj->ArrCustomer->StripeCardID;

			$ArrData = array(
				'order_id' => $result,
				'order_amount' => $order_data['order_total_amount'],
				'billing_details' => $billing_details,
				'shipping_details' => $shipping_details,
				'existData' => array(
					'billing_id' => $billing_id,
					'shipping_id' => $shipping_id,
					'card_id' => $card_id,
					'save_card' => $save_card,
					'CardToken' => $CardToken,
					'StripeCardID' => $StripeCardID,
					'CardPaymentMethodId' => $CardPaymentMethodId,
				)
			);

			//save earned credit value
			if(!empty($earned_credit_val)){
				$earn_cr_data = array(
					'user_id' => $user_id,
					'cash_credit_id' => $find_credit_id ?? 0,
					'order_id' => $result,
					'type' => 'earned',
					'amount' => $earned_credit_val,
					'updated_datetime' => date('Y-m-d H:i:s'),
				);
				$crt_id = $this->credittransaction_model->add_credittrans($earn_cr_data);
			}

			//save used and earned value
			if(!empty($earned_credit_checkbox)){
				$used_crval = $json_obj->ArrCustomer->earned_credit_val;
				$used_cr_data = array(
					'user_id' => $user_id,
					'order_id' => $result,
					'type' => 'used',
					'amount' => -$used_crval,
					'updated_datetime' => date('Y-m-d H:i:s'),
				);

				$crt_id = $this->credittransaction_model->add_credittrans($used_cr_data);
			}

			if ($result) {
				$success_message = 'Order added successfully';
				$users = $this->orders_model->get_users_data($result);
				$subject = "Order Place Successfully";
				/*$order_content = file_get_contents('templates/order_mail_template.html');
				$email = $users[0]->email;
				$order_content = str_replace('##user_name##', $json_obj->ArrCustomer->shipping_first_name . ' ' .$json_obj->ArrCustomer->shipping_last_name, $order_content);
				$order_content = str_replace('##firstname##', $json_obj->ArrCustomer->shipping_first_name . ' ' .$json_obj->ArrCustomer->shipping_last_name, $order_content);
				$order_content = str_replace('##order_id##', $result, $order_content);
				foreach ($json_obj->ArrProduct as $product) {
					$order_content = str_replace('##product_name##', $product->product_name, $order_content);
					$order_content = str_replace('##product_qty##', $product->qty, $order_content);
					$order_content = str_replace('##product_price##', $product->unit_price, $order_content);
					$order_content = str_replace('##product_total_price##', $product->total_amount, $order_content);
				}
				$order_content = str_replace('##address1##', $json_obj->ArrCustomer->billing_street_name, $order_content);
				$order_content = str_replace('##address2##', $json_obj->ArrCustomer->billing_apartment_name, $order_content);
				$order_content = str_replace('##city##', $json_obj->ArrCustomer->billing_city, $order_content);
				$order_content = str_replace('##state##', $json_obj->ArrCustomer->shiping_state, $order_content);
				$order_content = str_replace('##zip##', $json_obj->ArrCustomer->billing_zipcode, $order_content);
				$order_content = str_replace('##phone##', $json_obj->ArrCustomer->billing_phone, $order_content);
				$order_content = str_replace('##email##', $json_obj->ArrCustomer->shipping_email, $order_content);
				$order_content = str_replace('##link##', FRONT_URL, $order_content);*/

				// send_mail($email, $subject, $order_content);
				// $stripe = new \Stripe\StripeClient(
				// 	STRIPE_SECRET_KEY
				//   );
				//   $token=$stripe->tokens->create([
				// 	'card' => [
				// 	  'number' => '4242424242424242',
				// 	  'exp_month' => 7,
				// 	  'exp_year' => 2023,
				// 	  'cvc' => '314',
				// 	],
				//   ]);

				// $token=trim($token->id);
				// $user_details=array(
				// 	'name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '',$json_obj->first_name))),
				// 	'email' => trim($json_obj->email),
				// );

				// $card_details=array(
				// 	'card_num' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '',$json_obj->card_num))),
				// 	'card_cvc' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '',$json_obj->cvc))),
				// 	'card_exp_month' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '',$json_obj->exp_month))),
				// 	'card_exp_year' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '',$json_obj->exp_year))));
				// $items=array(
				// 	'itemPrice' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '',$order_total_amount))),
				// 	'currency' => "usd"
				// );	

				// $payment_details=stripe_payment_process($token,$user_details,$card_details,$items);
				// if($payment_details != false)
				// {
				// 	$payment_detail_data=array(
				// 		"payment_gateway_transaction_id"=>$payment_details['id'],
				// 		"transaction_status"=>$payment_details['status'],
				// 		"order_id"=>$result,
				// 		"transaction_amount"=>$payment_details['amount'],
				// 		"transaction_datetime"=>date("Y-m-d H:i:s"),
				// 		"created_datetime"=>date("Y-m-d H:i:s"),
				// 		"created_by"=>$json_obj->created_by
				// 	);

				// 	$this->orders_model->add_order($payment_detail_data,'tbl_transactions');
				// 	if($payment_details['status'] == 'successful')
				// 	{
				// 		$order_data=array(
				// 			'order_status'  => 'completed'
				// 		);
				// 		$this->orders_model->update_order($order_data,$result,'tbl_orders');
				// 		$order_content=file_get_contents('templates/order_payment_successful.html');
				// 	$order_content = str_replace('##user_name##',$json_obj->name,$order_content);
				// 	$order_content = str_replace('##amount##',$payment_details['amount'],$order_content);

				// 	send_mail($json_obj->email,$subject,$order_content);
				// 	}


				// }
				// else
				// {
				// 	$errors="There is problem in payment";
				// }
				//echo "<pre>";print_r($payment_details);
				//return $payment_details;
			} else {
				$errors = 'Order Not Added Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_order_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'order_id' => $json_obj->order_id
			);
			$temp_order_result = $this->orders_model->get_order_by_id($data);
			if (count($temp_order_result) > 0) {
				$result = $temp_order_result[0];
				$result['products'] = $this->orders_model->get_order_product_by_id_without_image($data);
				$result['coupon'] = $this->coupons_model->get_coupon_by_id($result['coupon_id']);
				$result['transactions'] = $this->orders_model->get_transaction_data($data['order_id']);
			}
			$ArrData = $result;
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function deliveryboy_order_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'order_id' => $json_obj->order_id
			);
			$temp_order_result = $this->orders_model->get_order_by_id($data);
			if (count($temp_order_result) > 0) {
				$result = $temp_order_result[0];
				$result['products'] = $this->orders_model->get_order_product_by_id($data);
				$result['coupon'] = $this->coupons_model->get_coupon_by_id($result['coupon_id']);
				$result['transactions'] = $this->orders_model->get_transaction_data($data['order_id']);
			}
			$ArrData = $result;
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function update_order()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$order_id = trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_id)));
			$data = array(
				'user_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->user_id))),
				'order_datetime' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', date('Y-m-d', strtotime($json_obj->order_datetime))))),
				'order_status' => trim('Pending Payment'),
				'order_notes' => $json_obj->order_notes,
				'billing_first_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_first_name))),
				'billing_last_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_last_name))),
				'billing_street_name' => $json_obj->billing_street_name,
				'billing_apartment_name' => $json_obj->billing_apartment_name,
				'billing_city' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_city))),
				'billing_state_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_state_id))),
				'billing_zipcode' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_zipcode))),
				'billing_country' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_country))),
				'billing_phone' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_phone))),
				'billing_email' => trim(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_email)),
				'shipping_first_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_first_name))),
				'shipping_last_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_last_name))),
				'shipping_street_name' => $json_obj->shipping_street_name,
				'shipping_apartment_name' => $json_obj->shipping_apartment_name,
				'shipping_city' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_city))),
				'shipping_state_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_state_id))),
				'shipping_zipcode' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_zipcode))),
				'shipping_country' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_country))),
				'shipping_phone' => trim(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_phone)),
				'shipping_email' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_email))),
				'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->created_by))),
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active)))
			);
			$result = $this->orders_model->update_order($data, $order_id, 'tbl_orders');

			$total_weight = 0;
			foreach ($json_obj->product_data as $product) {
				$this->orders_model->delete_order($order_id, 'tbl_order_products');
				$total_weight += trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_weight_gms * $product->qty)));
				$order_product_data = array(
					"order_id" => $order_id,
					"product_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_id))),
					"product_variant_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_variant_id))),
					"qty" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->qty))),
					'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->created_by))),
					'created_datetime' => date('Y-m-d H:i:s'),
					'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active)))
				);
				$result_product_id = $this->orders_model->add_order($order_product_data, 'tbl_order_products');
			}

			//$shipping_charge=0;
			$fedex_shipping_charge = get_rate_by_weight($json_obj->qty, $json_obj->dimension, $total_weight, $json_obj->shipping_city, $json_obj->stateOrProvinceCode, $json_obj->shipping_zipcode, 'US');
			if ($fedex_shipping_charge->output->rateReplyDetails[0]->ratedShipmentDetails[0]->totalNetFedExCharge != null) {
				$shipping_charge = $fedex_shipping_charge->output->rateReplyDetails[0]->ratedShipmentDetails[0]->totalNetFedExCharge;
			} else {
				return $fedex_shipping_charge->errors[0]->message;
			}
			$order_total_amount = $json_obj->order_price + $shipping_charge + $json_obj->order_tip + $json_obj->order_total_tax - $json_obj->discount_amount;
			//$fedex_tracking_id =0;
			$track_id = get_ship();
			if ($track_id->output->transactionShipments[0]->masterTrackingNumber != null) {
				$fedex_tracking_id = $track_id->output->transactionShipments[0]->masterTrackingNumber;
			} else {
				return $track_id->errors[0]->message;
			}


			$order_data = array(
				'order_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_price))),
				'fedex_tracking_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $fedex_tracking_id))),
				'fedex_shipping_charge' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $shipping_charge))),
				'order_tip' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_tip))),
				'order_total_tax' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_total_tax))),
				'coupon_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->coupon_id))),
				'discount_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->discount_amount))),
				'order_total_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $order_total_amount))),
				'is_active' => '1'
				//	'fedex_raw_response' =>$fedex_shipping_charge,
				//	'stripe_raw_response' =>''
			);
			$this->orders_model->update_order($order_data, $result, 'tbl_orders');
			$ArrData = $result;
			if ($result) {
				$success_message = 'Order added successfully';
				$users = $this->orders_model->get_users_data($result);
				$subject = "Order Place Successfully";
				$order_content = file_get_contents('templates/order_mail_template.html');
				$order_content = str_replace('##user_name##', $users[0]->user_name, $order_content);
				$order_content = str_replace('##firstname##', $users[0]->user_name, $order_content);
				foreach ($json_obj->ArrProduct as $product) {
					$order_content = str_replace('##product_name##', $product->product_name, $order_content);
					$order_content = str_replace('##product_qty##', $product->qty, $order_content);
					$order_content = str_replace('##product_price##', $product->unit_price, $order_content);
					$order_content = str_replace('##product_total_price##', $product->total_amount, $order_content);
				}
				$order_content = str_replace('##address1##', $json_obj->ArrCustomer->billing_street_name, $order_content);
				$order_content = str_replace('##address2##', $json_obj->ArrCustomer->billing_apartment_name, $order_content);
				$order_content = str_replace('##city##', $json_obj->ArrCustomer->billing_city, $order_content);
				$order_content = str_replace('##state##', $json_obj->ArrCustomer->billing_state, $order_content);
				$order_content = str_replace('##zip##', $json_obj->ArrCustomer->billing_zipcode, $order_content);
				$order_content = str_replace('##phone##', $json_obj->ArrCustomer->billing_phone, $order_content);
				$order_content = str_replace('##email##', $json_obj->ArrCustomer->shipping_email, $order_content);
				$order_content = str_replace('##link##', FRONT_URL, $order_content);
				// send_mail($users[0]->email, $subject, $order_content);
				$stripe = new \Stripe\StripeClient(
					STRIPE_SECRET_KEY
				);
				$token = $stripe->tokens->create([
					'card' => [
						'number' => '4242424242424242',
						'exp_month' => 7,
						'exp_year' => 2023,
						'cvc' => '314',
					],
				]);

				$token = trim($token->id);
				$user_details = array(
					'name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->name))),
					'email' => trim($json_obj->email),
				);

				$card_details = array(
					'card_num' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->card_num))),
					'card_cvc' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->cvc))),
					'card_exp_month' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->exp_month))),
					'card_exp_year' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->exp_year)))
				);
				$items = array(
					'itemPrice' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $order_total_amount))),
					'currency' => "usd"
				);

				$payment_details = stripe_payment_process($token, $user_details, $card_details, $items);
				if ($payment_details != false) {
					$payment_detail_data = array(
						"payment_gateway_transaction_id" => $payment_details['id'],
						"transaction_status" => $payment_details['status'],
						"order_id" => $result,
						"transaction_amount" => $payment_details['amount'],
						"transaction_datetime" => date("Y-m-d H:i:s"),
						"created_datetime" => date("Y-m-d H:i:s"),
						"created_by" => $json_obj->created_by
					);

					$this->orders_model->add_order($payment_detail_data, 'tbl_transactions');
					if ($payment_details['status'] == 'successful') {
						$order_data = array(
							'order_status' => 'Processing'
						);
						$this->orders_model->update_order($order_data, $order_id, 'tbl_orders');
						$order_content = file_get_contents('templates/order_mail_template.html');
						$order_content = str_replace('##user_name##', $json_obj->name, $order_content);
						$order_content = str_replace('##firstname##', $json_obj->name, $order_content);
						$order_content = str_replace('##amount##', $payment_details['amount'], $order_content);
						$order_content = str_replace('##order_id##', $result_product_id, $order_content);
						// Add a CSS style attribute to your table tag
						$table_style = 'style="border: 1px solid #ccc; border-collapse: collapse;"';
						$order_content = str_replace('##table_style##', $table_style, $order_content);
						$total_state_tax = 0 ;
						// Create table for displaying order details
						$table_content = '<table style="border-collapse: collapse; width: 100%;">';
						$table_content .= '<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Tax</th><th>Total Price</th></tr>';
						foreach ($json_obj->ArrProduct as $product) {
							$table_content .= '<tr>';
							$table_content .= '<td style="border: 1px solid grey; padding: 5px;">' . $product->product_name . '</td>';
							$table_content .= '<td style="border: 1px solid grey; padding: 5px;">' . $product->qty . '</td>';
							$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product->unit_price . '</td>';
							$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product->product_tax_amount . '</td>';
							$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product->total_amount . '</td>';
							$table_content .= '</tr>';
							$total_state_tax = $total_state_tax + $product->product_tax_amount;
						}
						$table_content .= '<tr><td colspan="3" style="border: 1px solid grey; padding: 5px;">Delivery:</td><td style="border: 1px solid grey; padding: 5px;">Free Delivery</td></tr>';
						$table_content .= '<tr><td colspan="3" style="border: 1px solid grey; padding: 5px;">Sales Tax:</td><td style="border: 1px solid grey; padding: 5px;">$' . $total_state_tax . '</td></tr>';
						$table_content .= '<tr><td colspan="3" style="border: 1px solid grey; padding: 5px;">Order Tip:</td><td style="border: 1px solid grey; padding: 5px;">$' . $json_obj->ArrCustomer->order_tip . '</td></tr>';
						$table_content .= '<tr><td colspan="3" style="border: 1px solid grey; padding: 5px;">Preparation Cost:</td><td style="border: 1px solid grey; padding: 5px;">$' . $json_obj->ArrCustomer->preparation_cost . '</td></tr>';
						$table_content .= '<tr><td colspan="3" style="border: 1px solid grey; padding: 5px;">Packaging Cost:</td><td style="border: 1px solid grey; padding: 5px;">$' . $json_obj->ArrCustomer->packaging_cost . '</td></tr>';
						$table_content .= '<tr><td colspan="3" style="border: 1px solid grey; padding: 5px;">Total:</td><td style="border: 1px solid grey; padding: 5px;">$' . $json_obj->ArrCustomer->cart_total . '</td></tr>';
						$table_content .= '<tr><td colspan="3" style="border: 1px solid grey; padding: 5px;">Notes:</td><td style="border: 1px solid grey; padding: 5px;">' . $json_obj->ArrCustomer->order_notes . '</td></tr>';
						$table_content .= '</table>';

						$order_content = str_replace('##product_table##', $table_content, $order_content);

						$order_content = str_replace('##address1##', $json_obj->ArrCustomer->billing_street_name, $order_content);
						$order_content = str_replace('##address2##', $json_obj->ArrCustomer->billing_apartment_name, $order_content);
						$order_content = str_replace('##city##', $json_obj->ArrCustomer->billing_city, $order_content);
						//$order_content = str_replace('##state##', $json_obj->ArrCustomer->shiping_state, $order_content);
						$order_content = str_replace('##zip##', $json_obj->ArrCustomer->billing_zipcode, $order_content);
						$order_content = str_replace('##state##', $json_obj->ArrCustomer->billing_state, $order_content);
						$order_content = str_replace('##phone##', $json_obj->ArrCustomer->billing_phone, $order_content);
						$order_content = str_replace('##email##', $json_obj->ArrCustomer->shipping_email, $order_content);

						$order_content = str_replace('##address_ship##', $json_obj->ArrCustomer->shipping_street_name, $order_content);
						$order_content = str_replace('##address2_ship##', $json_obj->ArrCustomer->shipping_apartment_name, $order_content);
						$order_content = str_replace('##city_ship##', $json_obj->ArrCustomer->shipping_city, $order_content);
						$order_content = str_replace('##state_ship##', $json_obj->ArrCustomer->shiping_state, $order_content);
						$order_content = str_replace('##zip_ship##', $json_obj->ArrCustomer->shipping_zipcode, $order_content);
						$order_content = str_replace('##phone_ship##', $json_obj->ArrCustomer->shipping_phone, $order_content);
						$order_content = str_replace('##link##', FRONT_URL, $order_content);
						send_mail($json_obj->email, $subject, $order_content);
					}
				} else {
					$order_data = array('order_status' => 'Failed');
					$this->orders_model->update_order($order_data, $order_id, 'tbl_orders');
					$errors = "There is problem in payment";
				}
				//echo "<pre>";print_r($payment_details);
				//return $payment_details;
			} else {
				$errors = 'Order Not Added Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_orders()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$order_id = $json_obj->order_id;
			$data = array(
				'is_active' => '0',
				'is_deleted' => '1'
			);
			$result = $this->orders_model->update_order($data, $order_id, 'tbl_orders');
			$this->orders_model->update_order($data, $order_id, 'tbl_order_products');
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Order Deleted Successfully';
			} else {
				$errors = 'Order Not Deleted Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_order_by_user_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$user_id = $json_obj->user_id;
			$result = $this->orders_model->get_order_by_user_id($user_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Order Found';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}


	public function deliveryboy_get_order_by_user_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$user_id = $json_obj->user_id;
			$order_status = $json_obj->order_status;
			if ($order_status['order_status'] == 'Processing') {
				// $user_id   = $json_obj->user_id;
				$data = array(
					"user_id" => $json_obj->user_id,
					"order_total_amount" => $json_obj->order_total_amount,
					"order_datetime" => $json_obj->order_datetime,
					"order_status" => 'Processing'
				);
			} else {
				$data = array(
					"user_id" => $json_obj->user_id,
					"order_total_amount" => $json_obj->order_total_amount,
					"order_datetime" => $json_obj->order_datetime,
					"order_status" => $json_obj->order_status
				);
			}
		}

		$result = $this->orders_model->get_order_by_user_id($user_id, $order_status, $data);
		$ArrData = $result;
		if ($result) {
			$ArrData = $result;
			$success_message = '';
		} else {
			$errors = 'No Order Found';
		}
		send_response_to_api($ArrData, $errors, $success_message);
	}

	public function report_order()
	{
		//$json_str = file_get_contents('php://input');
		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			if ($_FILES['report_images']['name'] != "") {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . "/vrajfresh/admin/uploads/reports/";
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 1024;
				$config['file_name'] = GUID();

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('report_images')) {
					$errors = array('error' => $this->upload->display_errors());
				} else {
					$file_data = array('upload_data' => $this->upload->data());
					$image1 = $file_data['upload_data']['file_name'];
				}
			}
			if ($_FILES['report_images1']['name'] != "") {
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . "/vrajfresh/admin/uploads/reports/";
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 1024;
				$config['file_name'] = GUID();

				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('report_images1')) {
					$errors = array('error1' => $this->upload->display_errors());
				} else {
					$file_data1 = array('upload_data' => $this->upload->data());
					$image2 = $file_data1['upload_data']['file_name'];
				}
			}
			if ($errors == "") {
				$data = array(
					'user_id' => $json_obj->user_id,
					'order_id' => $json_obj->order_no,
					'complain' => $json_obj->message,
					'image1' => $image1,
					'image2' => $image2,
					'created_by' => $json_obj->user_id,
					'created_datetime' => date('Y-m-d h:i:s'),
					'is_active' => '1',
					'is_read' => 1
				);

				$result = $this->orders_model->add_order($data, ' tbl_order_complains');

				$ArrData = $result;
				if ($result) {
					$ArrData = $result;
					$success_message = 'Order Complain has been submitted successfully';
				} else {
					$errors = 'Order Complain has been failed.';
				}
			} else {
				$errors = $errors['error'] . "<br/>" . $errors['error1'];
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function requested_product()
	{
		//$json_str = file_get_contents('php://input');
		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'user_id' => $json_obj->user_id,
				'product_name' => $json_obj->product_name,
				'product_detail' => $json_obj->message,
				'created_by' => $json_obj->user_id,
				'created_datetime' => date('Y-m-d h:i:s'),
				'is_active' => '1'
			);

			$result = $this->orders_model->add_order($data, ' tbl_requested_product');
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Product request has been submitted successfully.';
			} else {
				$errors = 'Product request has been failed.';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	
	public function send_test_email(){
		if(send_mail('hardikvd@vedikin.com', "Email From Vraj Fresh", " Hello!<br/> This is just a test email from Vraj Fresh Live.")){
			echo "Email Sent";
		}
		else {
			echo 'Error while sending email';
		}
		
		
	}

	public function delete_checkout_address_details()
	{

		$errors = $success_message = '';

		$json_str = $this->input->raw_input_stream;		
		$json_obj = json_decode($json_str);
		

		$oauth_key = $json_obj->oauth_key;
		$user_id = $json_obj->user_id;
		$id = $json_obj->id;
		$type = $json_obj->type;

		$ArrData = array();

		if (check_oauth_key($oauth_key)) {

			if($type != "" && $id != "" && $user_id != ""){
				
				$this->db->where($type.'_id',$id);

				$this->db->delete('tbl_'.$type.'_address');
				if ($this->db->affected_rows() > 0) {
					$success_message = $type.' address deleted successfully';
				}
				else{
					$errors = "Something went wrong";
				}

			} else {
				$errors = "Something went wrong";
			}

		}
		send_response_to_api($ArrData, $errors, $success_message);
	}


	public function createIntent() {
		$stripe = array(
			"secret_key" => STRIPE_SECRET_KEY,
			"publishable_key" => STRIPE_PUBLISHABLE_KEY
		);

		// retrieve JSON from POST body
		$jsonStr = file_get_contents('php://input');
		$jsonObj = json_decode($jsonStr);
		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		$stripe = new \Stripe\StripeClient($stripe['secret_key']);

		$amount = isset($jsonObj->ctoalamt) ? $jsonObj->ctoalamt : 0;
		$suser_id = isset($jsonObj->suser_id) ? $jsonObj->suser_id : 0;
		$szipcode = isset($jsonObj->szipcode) ? $jsonObj->szipcode : 0;
		//find user details
		$user_details = $this->master->get_row_detail('tbl_users', array('user_id' => $suser_id));

		if(!empty($user_details)){
			$stripeCustId = $user_details['stripe_cus_id'];
			$uemail = $user_details['email'];
		}

		// Convert to cents (Stripe requires smallest currency unit)
		$amountInCents = intval($amount * 100);
		
		$cus_id='';
		if(!empty($stripeCustId)):
			$cus_id = $stripeCustId;
		else:
		
			try{			
				$customer = \Stripe\Customer::create(
					array(
						'email' => $uemail,
						'address' => array('postal_code' => $szipcode),
					)
				);
				$cus_id = $customer->id;

				/* Add Cutomer Id In User Details */
				$updateData = array(
					'stripe_cus_id' => $cus_id
				);
				$this->master->update_detail('tbl_users', $updateData, array('user_id' => $suser_id));

			}  catch (\Stripe\Exception\ApiErrorException $e) {

				$msg = 'New Card & New Customer Error creating customer: ' . $e->getMessage();
				$returnArr['msg'] = $msg;
			}
		endif;
        try {

            // Create a PaymentIntent with amount and currency
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => 'usd',
				'customer' => $cus_id,          
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
				'setup_future_usage' => 'off_session',   
				
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
				'paymentId' => $paymentIntent->id
            ];
//print_r($paymentIntent);exit;
            echo json_encode($output);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }


    }

	public function updateIntent() {

		$stripe = array(
			"secret_key" => STRIPE_SECRET_KEY,
			"publishable_key" => STRIPE_PUBLISHABLE_KEY
		);
		// retrieve JSON from POST body
		$jsonStr = file_get_contents('php://input');
		$jsonObj = json_decode($jsonStr);
		$stripe = new \Stripe\StripeClient($stripe['secret_key']);
		$intentId = $jsonObj->intent_id;
		$amount = isset($jsonObj->amount) ? $jsonObj->amount : 0;

		// Convert to cents (Stripe requires smallest currency unit)
		$amountInCents = intval($amount * 100);
		try {

			// Create a PaymentIntent with amount and currency
			$paymentIntent = $stripe->paymentIntents->update($intentId,[
				'amount' => $amountInCents,
				//'currency' => 'usd',
				// In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
				// 'automatic_payment_methods' => [
				// 	'enabled' => true,
				// 		'allow_redirects' => 'never',
				// ],
				
			]);

			$output = [
				'clientSecret' => $paymentIntent->client_secret,
				'paymentId' => $paymentIntent->id
			];

			echo json_encode($output);
		} catch (Error $e) {
			http_response_code(500);
			echo json_encode(['error' => $e->getMessage()]);
		}

    }

}