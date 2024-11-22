<?php

defined('BASEPATH') or exit('No direct script access allowed');

error_reporting(0);

class Controller_checkout extends CI_Controller

{

	public function __construct()
	{

		parent::__construct();
		$this->load->config('config');
	}





	public function index_old()
	{



		$ArrStateOption = getStatesWithTaxCode(); /* CALL COMMON HELPER FUNCTION */

		$ArrBillingStateOption = getStatesWithTaxCode('no'); /* CALL COMMON HELPER FUNCTION */

		$ArrUserData['ArrStateOption'] = $ArrStateOption;

		$ArrUserData['ArrBillingStateOption'] = $ArrBillingStateOption;

		$this->load->view('checkout', $ArrUserData);



	}

	public function index()
	{
		if (!IsUserLogin()) {
			
			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}
		
		$contain = $this->cart->contents();
		
		$zipcode = $_COOKIE['zipcode'];
		$CARTDATA = ""; 
		if(!empty($contain)){
			$CARTDATA = addslashes(json_encode($contain));
		}
		$user_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$user_id = $this->session->userdata['logged_in']['user_id'];
		}

		$total_items = 0;
		$cart_total = 0.00;
		
		// if($zipcode != ""){
		// 	$url = API_URL . 'remove-zipcode-products';

		// 	$data = array(
		// 		"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
		// 		"user_id" => $user_id,
		// 		"zipcode" => $zipcode,
		// 		"cart" => $CARTDATA
		// 	);

		// 	$curl = curl_init();
		// 	curl_setopt($curl, CURLOPT_URL, $url);
		// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($curl, CURLOPT_POST, true);
		// 	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		// 	curl_setopt($curl, CURLOPT_HTTPHEADER, [
		// 		'X-RapidAPI-Host: kvstore.p.rapidapi.com',
		// 		'X-RapidAPI-Key: test',
		// 		'Content-Type: application/json'
		// 	]);
		// 	$response = curl_exec($curl);
		// 	//echo "<pre>zipcode response:- <pre>";print_r($response);
		// 	curl_close($curl);
		// 	$response = json_decode($response, true);
			
		// 	if($response['is_successful'] == 1){
		// 		$this->cart->destroy();
		// 		$products = $response['data'];
				
		// 		if(!empty($products)){
					
		// 			unset($products['cart_total'], $products['total_items']);
					
		// 			if(!empty($products)){
		// 				foreach ($products as $key => $value){
							
		// 					$newCartItem = array(
		// 						"id" => $value['id'],
		// 						"name" => $value['name'],
		// 						"image" => $value['image'],
		// 						"price" => $value['price'],
		// 						"qty" => $value['qty'],
		// 						"product_slug" => $value['product_slug'],
		// 						"is_perisible" => $value['is_perisible'],
		// 						"product_tax" => $value['product_tax'],
		// 						"created_date" => date("Y-m-d"),
		// 						"options" => array(
		// 							"weight" => $value['options']['weight'],
		// 							"variant_id" => $value['options']['variant_id']
		// 						),
		// 						"rowid" => $key,
		// 						"subtotal" => $value['subtotal'],
		// 					);
		// 					$total_items += $value['qty'];
		// 					$cart_total += $value['subtotal'];
		// 					$this->cart->insert($newCartItem);
		// 				}
		// 			}
		// 		}
		// 	}

		// 	$contain = $this->cart->contents();
		// 	if(!empty($contain)){
		// 		if(!isset($contain['total_items']) || !isset($contain['cart_total'])){
					
		// 			$contain['total_items'] = $total_items;
		// 			$contain['cart_total'] = $cart_total;
		// 		} else {
		// 			if($contain['total_items'] == 0 && $contain['cart_total'] < $_COOKIE['minimum_order_value']){
		// 				$this->session->set_flashdata('error', 'A minimum $'.$_COOKIE['minimum_order_value'].' Order Required.');
		// 				redirect(base_url('cart-detail'));
		// 				exit(0);
		// 			}
		// 		}
				
		// 	}
		// }
		$ArrUserData['billing_address'] = [];
		$ArrUserData['shipping_address'] = [];
		$ArrUserData['cards'] = [];
		$ArrUserData['billing_id'] = 0;
		$ArrUserData['shipping_id'] = 0;
		$ArrUserData['card_id'] = 0;
		$ArrUserData['shipping_address_count'] = 0;
		$ArrUserData['billing_address_count'] = 0;
		$ArrUserData['card_count'] = 0;
		
		
		$url = API_URL . 'get-checkout-details';
		$data = array(
			"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"user_id" => $user_id,
			"cart_data" => json_encode($contain),
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		
		
		curl_close($curl);
		$response = json_decode($response, 1);
		//echo '<pre>';print_r($response);exit;
		

		if($response != null){
		
			if(isset($response['is_successful']) && $response['is_successful'] == 1){
				$ArrUserData['billing_address'] 		= $response['data']['billing_address'];
				$ArrUserData['shipping_address'] 		= $response['data']['shipping_address'];
				$ArrUserData['cards'] 					= $response['data']['cards'];
				$ArrUserData['billing_id'] 				= $response['data']['billing_id'];
				$ArrUserData['shipping_id'] 			= $response['data']['shipping_id'];
				$ArrUserData['shipping_address_count'] 	= count($response['data']['shipping_address']);
				$ArrUserData['billing_address_count'] 	= count($response['data']['billing_address']);
				$ArrUserData['card_count'] 				= count($response['data']['cards']);
				$ArrUserData['card_id'] 				= $response['data']['card_id'];
			}
		}

		$ArrStateOption = getStatesWithTaxCode(); /* CALL COMMON HELPER FUNCTION */

		$ArrBillingStateOption = getStatesWithTaxCode('no'); /* CALL COMMON HELPER FUNCTION */

		$ArrUserData['ArrStateOption'] = $ArrStateOption;

		$ArrUserData['ArrBillingStateOption'] = $ArrBillingStateOption;
		
		$this->load->view('checkout', $ArrUserData);



	}

	public function add_checkout_address()
	{

		$AddressType = strtolower($_POST['AddressType']);
		$user_role_id = $_POST['user_role_id'];
		$user_id = $_POST['user_id'];
		$oauth_key = $_POST['oauth_key'];

		$newArr = [
			'user_id' => $user_id,
			'modify_at' => date('Y-m-d H:i:s'),
			'is_active' => 1,
		];

		$newArr['first_name'] = $_POST[$AddressType.'_first_name'];
		$newArr['last_name'] = $_POST[$AddressType.'_last_name'];
		$newArr[$AddressType . '_country_id'] = 235;
		$newArr[$AddressType . '_street_address'] = $_POST[$AddressType.'_street_address'];
		$newArr[$AddressType . '_apartment'] = $_POST[$AddressType.'_apartment'];
		$newArr[$AddressType . '_city'] = $_POST[$AddressType.'_city'];
		//$newArr[$AddressType . '_state_id'] = $_POST[$AddressType.'_state_id'];
		$newArr[$AddressType . '_zipcode'] = $_POST[$AddressType.'_zipcode'];
		$newArr[$AddressType . '_phone'] = $_POST[$AddressType.'_phone'];

		if($AddressType == "shipping"){
			if($_POST['check_'.$AddressType.'_state_id'] != ""){
				$StateData = explode('|', $_POST['check_'.$AddressType.'_state_id']);
				$newArr[$AddressType . '_state_id'] = $StateData[1];
			}
		} else {
			if($_POST[$AddressType.'_state_id'] != ""){
				$StateData = explode('|', $_POST[$AddressType.'_state_id']);
				$newArr[$AddressType . '_state_id'] = $StateData[1];
			}
		}
		
		$url = API_URL . 'add-checkout-address';	
		$data = array(
			"oauth_key" => $oauth_key,
			"ArrAddress" => $newArr,
			"AddressType" => $AddressType,
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, 1);
		
		$url = API_URL . 'get-checkout-details';
		$data = array(
			"oauth_key" => $oauth_key,
			"user_id" => $user_id
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, 1);
		
		$data = [
			'billing_address' => [],
			'shipping_address' => [],
			'billing_id' => 0,
			'shipping_id' => 0,
			'shipping_address_count' => 0,
			'billing_address_count' => 0,
		];
		if(isset($response['is_successful']) && $response['is_successful'] == 1){
			
			$data['billing_address'] = $response['data']['billing_address'];
			$data['shipping_address'] = $response['data']['shipping_address'];
			$data['billing_id'] = $response['data']['billing_id'];
			$data['shipping_id'] = $response['data']['shipping_id'];
			$data['shipping_address_count'] = count($response['data']['shipping_address']);
			$data['billing_address_count'] = count($response['data']['billing_address']);
		}

		if($AddressType == "shipping"){
			
			$html = $this->load->view('render_checkout_shipping_address', $data, true);

		} else {
			$html = $this->load->view('render_checkout_billing_address', $data, true);
		}

		$response = array(
			'status' => 1,
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($response);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);

	}

	public function update_checkout_address()
	{
		
		$AddressType = strtolower($_POST['AddressType']);
		$Address_id = strtolower($_POST['edit_' . $AddressType.'_id']);
		$user_id = $_POST['user_id'];
		$user_role_id = $_POST['user_role_id'];
		$oauth_key = $_POST['oauth_key'];

		$newArr = [
			'modify_at' => date('Y-m-d H:i:s'),
		];
		$prefix = "edit_" . $AddressType;
		$newArr['first_name'] = $_POST[$prefix.'_first_name'];
		$newArr['last_name'] = $_POST[$prefix.'_last_name'];
		$newArr[$AddressType . '_street_address'] = $_POST[$prefix.'_street_address'];
		$newArr[$AddressType . '_apartment'] = $_POST[$prefix.'_apartment'];
		$newArr[$AddressType . '_city'] = $_POST[$prefix.'_city'];
		//$newArr[$AddressType . '_state_id'] = $_POST[$prefix.'_state_id'];
		$newArr[$AddressType . '_zipcode'] = $_POST[$prefix.'_zipcode'];
		$newArr[$AddressType . '_phone'] = $_POST[$prefix.'_phone'];

		if($_POST[$prefix.'_state_id'] != ""){
			$StateData = explode('|', $_POST[$prefix.'_state_id']);
			$newArr[$AddressType . '_state_id'] = $StateData[1];
		}

		$url = API_URL . 'update-checkout-address';	
		$data = array(
			"oauth_key" => $oauth_key,
			"ArrAddress" => $newArr,
			"AddressType" => $AddressType,
			"Address_id" => $Address_id,
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, 1);
		
		$url = API_URL . 'get-checkout-details';
		$data = array(
			"oauth_key" => $oauth_key,
			"user_id" => $user_id
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, 1);
		$data = [
			'billing_address' => [],
			'shipping_address' => [],
			'billing_id' => 0,
			'shipping_id' => 0,
			'shipping_address_count' => 0,
			'billing_address_count' => 0,
		];
		if(isset($response['is_successful']) && $response['is_successful'] == 1){
			
			$data['billing_address'] = $response['data']['billing_address'];
			$data['shipping_address'] = $response['data']['shipping_address'];
			$data['billing_id'] = $response['data']['billing_id'];
			$data['shipping_id'] = $response['data']['shipping_id'];
			$data['shipping_address_count'] = count($response['data']['shipping_address']);
			$data['billing_address_count'] = count($response['data']['billing_address']);
		}

		if($AddressType == "shipping"){
			
			$html = $this->load->view('render_checkout_shipping_address', $data, true);

		} else {
			$html = $this->load->view('render_checkout_billing_address', $data, true);
		}

		$response = array(
			'status' => 1,
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($response);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);

	}

	public function checkout_process_old()
	{

		//echo "<pre>";print_r($this->cart->contents());exit;

		//echo "<pre>";print_r($_POST);exit;

		$ArrCustomer = array();

		

		

		$billing_state_id = $_POST['shiping_state'];

		$ArrStateData = explode('|', $_POST['state']);

		$shipping_state_id = $ArrStateData[1];

		

		if (isset($_POST['ship_to_different_address']) && $_POST['ship_to_different_address'] == "Yes")

		{

			$ArrCustomer['shipping_first_name'] = $_POST['first_name'];

			$ArrCustomer['shipping_last_name'] = $_POST['last_name'];

			

			$ArrCustomer['billing_street_name'] = $_POST['shiping_street_address'];

			$ArrCustomer['billing_apartment_name'] = $_POST['shiping_apartment'];

			$ArrCustomer['billing_city'] = $_POST['shiping_city'];

			$ArrCustomer['billing_state_id'] = $billing_state_id;

			$ArrCustomer['billing_zipcode'] = $_POST['shiping_zip_code'];

			$ArrCustomer['billing_phone'] = $_POST['shiping_phone'];

			$ArrCustomer['billing_country'] = 235;

			

			$ArrCustomer['shipping_street_name'] = $_POST['street_address'];

			$ArrCustomer['shipping_apartment_name'] = $_POST['apartment'];

			$ArrCustomer['shipping_city'] = $_POST['city'];

			$ArrCustomer['shipping_state_id'] = $shipping_state_id;

			$ArrCustomer['shipping_zipcode'] = $_POST['zip_code'];

			$ArrCustomer['shipping_phone'] = $_POST['phone'];

			$ArrCustomer['shipping_email'] = $_POST['email'];

			$ArrCustomer['shipping_country'] = 235;

		} else {

			$ArrCustomer['shipping_first_name'] = $_POST['first_name'];

			$ArrCustomer['shipping_last_name'] = $_POST['last_name'];

			

			$ArrCustomer['billing_street_name'] = $_POST['shiping_street_address'];

			$ArrCustomer['billing_apartment_name'] = $_POST['shiping_apartment'];

			$ArrCustomer['billing_city'] = $_POST['shiping_city'];

			$ArrCustomer['billing_state_id'] = $billing_state_id;

			$ArrCustomer['billing_zipcode'] = $_POST['shiping_zip_code'];

			$ArrCustomer['billing_phone'] = $_POST['shiping_phone'];

			$ArrCustomer['billing_country'] = 235;

			

			$ArrCustomer['shipping_street_name'] = $_POST['street_address'];

			$ArrCustomer['shipping_apartment_name'] = $_POST['apartment'];

			$ArrCustomer['shipping_city'] = $_POST['city'];

			$ArrCustomer['shipping_state_id'] = $shipping_state_id;

			$ArrCustomer['shipping_zipcode'] = $_POST['zip_code'];

			$ArrCustomer['shipping_phone'] = $_POST['phone'];

			$ArrCustomer['shipping_email'] = $_POST['email'];

			$ArrCustomer['shipping_country'] = 235;

		}

		$ArrCustomer['order_tip'] = $_POST['hdn_tip_amount']; 



		if (isset($this->session->userdata['logged_in']['user_id'])) {

			$ArrCustomer['user_id'] = $this->session->userdata['logged_in']['user_id'];

			$ArrCustomer['created_by'] = $this->session->userdata['logged_in']['user_id'];

		} else {

			$ArrCustomer['user_id'] = 0;



		}

		$ArrCustomer['order_notes'] = $_POST['order_comments'];

		$ArrCustomer['is_replace_item'] = $_POST['refund_for_unavailable'];

		$ArrCustomer['delivery_type'] = $_POST['delivery_type'];

		$ArrCustomer['order_final_amount'] = $_POST['cart_total'];

		$ArrCustomer['cart_total'] = $_POST['cart_total'];

		$ArrCustomer['delivery_datetime'] = date("Y-m-d H:i:s", strtotime("+1 hours"));

		$ArrCustomer['preparation_cost'] = $_POST['preparation_cost'];

		$ArrCustomer['packaging_cost'] = $_POST['packaging_cost'];

		$ArrCustomer['state_tax'] = $_POST['state_tax'];

		$ArrCustomer['discount_amount'] = $_POST['discount_amount'];

		$ArrCustomer['coupon_id'] = $_POST['discount_id'];

		

		if ($_POST['delivery_type'] == 'two_hour') {

			$delivery_date_time = date("Y-m-d H:i:s", strtotime("+1 hours"));



			$ArrCustomer['delivery_datetime'] = $delivery_date_time;

		}

		if ($_POST['delivery_type'] == 'one_day') {

			$delivery_date_time = date("Y-m-d H:i:s", strtotime("+1 days"));



			$ArrCustomer['delivery_datetime'] = $delivery_date_time;

		}

		if ($_POST['delivery_type'] == 'Twise in a week') {



			$t = date('d-m-Y');

			if (date("l", strtotime($t)) == "Tuesday" || date("l", strtotime($t)) == "Wednesday" || date("l", strtotime($t)) == "Thursday") {

				$date = new DateTime();

				$ArrCustomer['delivery_datetime'] = $date->modify('next thursday')->format('Y-m-d H:i:s');

			}

			if (date("l", strtotime($t)) == "Friday" || date("l", strtotime($t)) == "Saturday" || date("l", strtotime($t)) == "Sunday" || date("l", strtotime($t)) == "Monday") {

				$date = new DateTime();

				$ArrCustomer['delivery_datetime'] = $date->modify('next monday')->format('Y-m-d H:i:s');

			}



		}

		//echo "<pre>";print_r($ArrCustomer);exit;

		$ArrProduct = array();

		foreach ($this->cart->contents() as $items) {

			$Arr = array();

			$Arr['product_id'] = $items["id"];

			$Arr['product_variant_id'] = $items["options"]["variant_id"]; /*$items["product_variant_id"];*/

			$Arr['product_weight_gms'] = $items["options"]["weight"]; /*$items["product_variant_id"];*/

			$Arr['qty'] = $items["qty"];

			$Arr['total_amount'] = $items["qty"] * $items["price"];

			$Arr['unit_price'] = $items["price"];

			$Arr['product_name'] = $items["name"];

			

			$Arr['product_tax_amount'] = 0;

			if($items["product_tax"]==1)

			{

				$Arr['product_tax_amount'] = number_format( (($ArrCustomer["state_tax"] * $Arr['total_amount']) / 100),2);

			}

			$Arr['created_by'] = 1;

			$Arr['is_active'] = 1;

			$ArrProduct[] = $Arr;

		}



		//echo "<pre>";print_r($ArrProduct);exit;



		$url = API_URL . 'add-order';

		

		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrCustomer" => $ArrCustomer, "ArrProduct" => $ArrProduct);

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

		curl_setopt($curl, CURLOPT_HTTPHEADER, [

			'X-RapidAPI-Host: kvstore.p.rapidapi.com',

			'X-RapidAPI-Key: test',

			'Content-Type: application/json'

		]);

		$response = curl_exec($curl);

		curl_close($curl);



		$response = json_decode($response);

		//echo "<pre>";print_r($response);exit;





		if ($response->is_successful == 1) {

			$order_id = $response->data->order_id;

			/* check minimum order amount to block */



			$url = API_URL . 'get-configurations-by-key';

			$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "configuration_key" => "'block_minimum_amount','block_maximum_amount','block_percentage'");

			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, $url);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($curl, CURLOPT_POST, true);

			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

			curl_setopt($curl, CURLOPT_HTTPHEADER, [

				'X-RapidAPI-Host: kvstore.p.rapidapi.com',

				'X-RapidAPI-Key: test',

				'Content-Type: application/json'

			]);

			$config_response = curl_exec($curl);

			curl_close($curl);

			$config_response = json_decode($config_response);

			//echo "<pre>";		print_r($config_response); 		print_r($response); 		echo "</pre>";exit;

			if (isset($config_response->is_successful) && $config_response->is_successful == 1) {

				if ($config_response->data[0]->configuration_value > $response->data->order_amount) {

					$order_amount = $config_response->data[0]->configuration_value;

				} elseif ($config_response->data[1]->configuration_value < $response->data->order_amount) {

					$order_amount = $config_response->data[1]->configuration_value;

				} else {

					$order_amount = $response->data->order_amount + ($response->data->order_amount * $config_response->data[2]->configuration_value / 100);

				}

			}

			

			/*edirect payment page*/

			$zip_code = $ArrCustomer['zip_code'];

			$sms_shipping_phone = $ArrCustomer['shipping_phone'];

			

			$this->payment($order_id, $order_amount, $zip_code,$sms_shipping_phone);

		} else {

			$this->order_failed();

		}

	}

	public function checkout_process()
	{
		//echo "<pre>";print_r($this->cart->contents());exit;
		//echo "<pre>";print_r($_POST);exit;
		$remove_product_from_cart = 0;
		$cookiezipcode = $_COOKIE['zipcode'];
		$contain = $this->cart->contents();
		$CARTDATA = ""; 
		if(!empty($contain)){
			$CARTDATA = addslashes(json_encode($contain));
		}
		$user_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$user_id = $this->session->userdata['logged_in']['user_id'];
		}

		$total_items = 0;
		$cart_total = 0.00;
		
		// if($cookiezipcode != ""){

		// 	$url = API_URL . 'remove-zipcode-products';

		// 	$data = array(
		// 		"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
		// 		"user_id" => $user_id,
		// 		"zipcode" => $cookiezipcode,
		// 		"cart" => $CARTDATA
		// 	);

		// 	$curl = curl_init();
		// 	curl_setopt($curl, CURLOPT_URL, $url);
		// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// 	curl_setopt($curl, CURLOPT_POST, true);
		// 	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		// 	curl_setopt($curl, CURLOPT_HTTPHEADER, [
		// 		'X-RapidAPI-Host: kvstore.p.rapidapi.com',
		// 		'X-RapidAPI-Key: test',
		// 		'Content-Type: application/json'
		// 	]);
		// 	$response = curl_exec($curl);
		// 	//echo "<pre>zipcode response:- <pre>";print_r($response);
		// 	curl_close($curl);
		// 	$response = json_decode($response, true);
		// 	//echo "<pre>";print_r($response);exit;
			
		// 	if($response['is_successful'] == 1){
		// 		$this->cart->destroy();
		// 		$products = $response['data'];
		// 		$remove_product_from_cart = $response['data2']['remove_product'];
				
		// 		if(!empty($products)){
					
		// 			unset($products['cart_total'], $products['total_items']);
					
		// 			if(!empty($products)){

		// 				foreach ($products as $key => $value){
							
		// 					$newCartItem = array(
		// 						"id" => $value['id'],
		// 						"name" => $value['name'],
		// 						"image" => $value['image'],
		// 						"price" => number_format($value['price'],2),
		// 						"qty" => $value['qty'],
		// 						"product_slug" => $value['product_slug'],
		// 						"is_perisible" => $value['is_perisible'],
		// 						"product_tax" => number_format($value['product_tax']),
		// 						"created_date" => date("Y-m-d"),
		// 						"options" => array(
		// 							"weight" => $value['options']['weight'],
		// 							"variant_id" => $value['options']['variant_id']
		// 						),
		// 						"rowid" => $key,
		// 						"subtotal" => $value['subtotal'],
		// 					);
		// 					$total_items += $value['qty'];
		// 					$cart_total += $value['subtotal'];
		// 					$this->cart->insert($newCartItem);
		// 				}
		// 			}
		// 		}
		// 	}

		// 	$contain = $this->cart->contents();
		// 	if(!empty($contain)){
		// 		if(!isset($contain['total_items']) || !isset($contain['cart_total'])){
		// 			if($total_items > 0 && $cart_total >= $_COOKIE['minimum_order_value']){
						
		// 				$contain['total_items'] = $total_items;
		// 				$contain['cart_total'] = $cart_total;

		// 				if($remove_product_from_cart == 1){
		// 					$this->session->set_flashdata('success', 'We have remove some items due to not valid. Please process again.');
		// 					redirect(base_url('cart-detail'));
		// 					exit(0);		
		// 				}
						
		// 			} else {
		// 				$this->session->set_flashdata('error', 'A minimum $'.$_COOKIE['minimum_order_value'].' Order Required.');
		// 				redirect(base_url('cart-detail'));
		// 				exit(0);		
		// 			}
		// 		} else {
		// 			if($contain['total_items'] == 0 && $contain['cart_total'] < $_COOKIE['minimum_order_value']){
		// 				$this->session->set_flashdata('error', 'A minimum $'.$_COOKIE['minimum_order_value'].' Order Required.');
		// 				redirect(base_url('cart-detail'));
		// 				exit(0);
		// 			}
		// 		}
				
		// 	}
		// }

		$ArrCustomer = array();

		$_POST['save_card'] = (isset($_POST['save_card'])) ? $_POST['save_card'] : 0 ;
		$billing_id = $_POST['billing_id'];
		$shipping_id = $_POST['shipping_id'];
		$same_address = 0;

		if (is_string($billing_id)) {
			
			if($billing_id == "same_address"){
				$same_address = 1;
				$billing_id = 0;
			}
		}

		$_POST['billing_id'] = $billing_id;
		$_POST['same_address'] = $same_address;

		$billing_state_id = $_POST['shiping_state'];
		$ArrStateData = explode('|', $_POST['state']);
		$shipping_state_id = $ArrStateData[1];

		$ArrCustomer['substitution_product_ids'] = "";
		if(isset($_POST['substitution_product_ids']) && !empty($_POST['substitution_product_ids'])){
			$ArrCustomer['substitution_product_ids'] = implode(',', $_POST['substitution_product_ids']);
		}
		$ArrCustomer['same_address'] = $same_address;
		$ArrCustomer['billing_id'] = $billing_id;
		$ArrCustomer['shipping_id'] = $shipping_id;
		$ArrCustomer['card_id'] = $_POST['card_id'];
		$ArrCustomer['save_card'] = (isset($_POST['save_card'])) ? $_POST['save_card'] : 0;
		$ArrCustomer['CardToken'] = ($_POST['CardToken']) ? $_POST['CardToken'] : '';
		$ArrCustomer['CardPaymentMethod'] = $_POST['CardPaymentMethod'];
		$ArrCustomer['StripeCardID'] = $_POST['StripeCardID'];
		
		
		$ArrCustomer['order_tip'] = $_POST['hdn_tip_amount']; 
		$ArrCustomer['user_id'] = $this->session->userdata['logged_in']['user_id'];

		$ArrCustomer['order_notes'] = $_POST['order_comments'];
		$ArrCustomer['delivery_comments'] = $_POST['delivery_comments'];
		$ArrCustomer['is_replace_item'] = $_POST['refund_for_unavailable'];
		$ArrCustomer['delivery_type'] = $_POST['delivery_type'];
		
		$ArrCustomer['order_final_amount'] = $_POST['cart_total'];
		$ArrCustomer['cart_total'] = $_POST['cart_total'];
		$ArrCustomer['delivery_datetime'] = date("Y-m-d H:i:s", strtotime("+1 hours"));
		$ArrCustomer['preparation_cost'] = $_POST['preparation_cost'];
		$ArrCustomer['packaging_cost'] = $_POST['packaging_cost'];
		$ArrCustomer['state_tax'] = $_POST['state_tax'];
		$ArrCustomer['discount_amount'] = $_POST['discount_amount'];
		$ArrCustomer['coupon_id'] = $_POST['discount_id'];
		

		if ($_POST['delivery_type'] == 'two_hour') {
			$delivery_date_time = date("Y-m-d H:i:s", strtotime("+1 hours"));
			$ArrCustomer['delivery_datetime'] = $delivery_date_time;
		}
		if ($_POST['delivery_type'] == 'one_day') {
			$delivery_date_time = date("Y-m-d H:i:s", strtotime($_POST['delivery_one_day_date']));
			$ArrCustomer['delivery_datetime'] = $delivery_date_time;
		}
		if ($_POST['delivery_type'] == 'Twise in a week') {

			$t = date('d-m-Y');
			if (date("l", strtotime($t)) == "Tuesday" || date("l", strtotime($t)) == "Wednesday" || date("l", strtotime($t)) == "Thursday") {
				$date = new DateTime();
				$ArrCustomer['delivery_datetime'] = $date->modify('next thursday')->format('Y-m-d H:i:s');
			}
			if (date("l", strtotime($t)) == "Friday" || date("l", strtotime($t)) == "Saturday" || date("l", strtotime($t)) == "Sunday" || date("l", strtotime($t)) == "Monday") {
				$date = new DateTime();
				$ArrCustomer['delivery_datetime'] = $date->modify('next monday')->format('Y-m-d H:i:s');
			}
		}
		//echo "<pre>";print_r($ArrCustomer);exit;
		$ArrProduct = array();
		foreach ($this->cart->contents() as $items) {
			$Arr = array();
			$Arr['product_id'] = $items["id"];
			$Arr['product_variant_id'] = $items["options"]["variant_id"]; /*$items["product_variant_id"];*/
			$Arr['product_weight_gms'] = $items["options"]["weight"]; /*$items["product_variant_id"];*/
			$Arr['qty'] = $items["qty"];
			$Arr['total_amount'] = $items["qty"] * $items["price"];
			$Arr['unit_price'] = $items["price"];
			$Arr['product_name'] = $items["name"];		
			$Arr['product_tax_amount'] = 0;
			if($items["product_tax"]==1)
			{
				$Arr['product_tax_amount'] = number_format( (($ArrCustomer["state_tax"] * $Arr['total_amount']) / 100),2);
			}
			$Arr['created_by'] = 1;
			$Arr['is_active'] = 1;
			$ArrProduct[] = $Arr;
		}
		//echo "<pre>";print_r($ArrProduct);exit;
		$url = API_URL . 'add-order';
		$data = array(
			"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"ArrCustomer" => $ArrCustomer,
			"ArrProduct" => $ArrProduct,
			"cart_arr"=> json_encode($this->cart->contents()),
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'X-RapidAPI-Host: kvstore.p.rapidapi.com',
			'X-RapidAPI-Key: test',
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		// echo "<pre>";print_r($response);exit;
		curl_close($curl);
		$response = json_decode($response);
		

		if ($response->is_successful == 1) {
			$order_id = $response->data->order_id;
			$shipping_details = $response->data->shipping_details;
			$billing_details = $response->data->billing_details;
			/* check minimum order amount to block */
			$url = API_URL . 'get-configurations-by-key';
			$data = array(
				"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
				"configuration_key" => "'block_minimum_amount',
				'block_maximum_amount',
				'block_percentage'"
			);

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'X-RapidAPI-Host: kvstore.p.rapidapi.com',
				'X-RapidAPI-Key: test',
				'Content-Type: application/json'
			]);
			$config_response = curl_exec($curl);
			curl_close($curl);
			$config_response = json_decode($config_response);
			//echo "<pre>";		print_r($config_response); 		print_r($response); 		echo "</pre>";exit;

			if (isset($config_response->is_successful) && $config_response->is_successful == 1) {
				if ($config_response->data[0]->configuration_value > $response->data->order_amount) {
					$order_amount = $config_response->data[0]->configuration_value;
				} elseif ($config_response->data[1]->configuration_value < $response->data->order_amount) {
					$order_amount = $config_response->data[1]->configuration_value;
				} else {
					$order_amount = $response->data->order_amount + ($response->data->order_amount * $config_response->data[2]->configuration_value / 100);
				}
			}

			/*edirect payment page*/

			//$zip_code = $ArrCustomer['zip_code'];
			//$sms_shipping_phone = $ArrCustomer['shipping_phone'];

			
			$order_id = $response->data->order_id;
			$shipping_details = $response->data->shipping_details;
			$billing_details = $response->data->billing_details;

			
			//$this->payment($order_id, $order_amount, $zip_code,$sms_shipping_phone);
			
			$order_amount = $order_amount * 100;
			$this->new_payment_process($order_id, $order_amount, $shipping_details, $billing_details, $_POST);

		} else {
			$this->order_failed();
		}
	}

	function payment($order_id, $order_amount, $zip_code,$sms_shipping_phone)
	{

		$Arrdata['order_id'] = $order_id;

		$Arrdata['order_amount'] = $order_amount;

		$Arrdata['zip_code'] = $zip_code;

		$Arrdata['sms_shipping_phone'] = $sms_shipping_phone;



		$this->load->view('order_payment_form', $Arrdata);

	}

	function new_payment_process($order_id, $order_amount, $shipping_details, $billing_details, $POST)
	{
		$zip_code = $shipping_details->shipping_zipcode;
		$sms_shipping_phone = $shipping_details->shipping_phone;

		$POST['order_amount'] = intval($order_amount);
		$POST['order_id'] = $order_id;
		$ArrPayment = $POST;

		$same_address = $POST['same_address'];

		/*Payment Process Start*/

		$url = API_URL . 'stripe/payment_process';

		$data = array(
			"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"ArrPayment" => $ArrPayment,
			"billing_details" => $billing_details,
			"shipping_details" => $shipping_details,
			'user_id' => $this->session->userdata['logged_in']['user_id'],
			"same_address" => $same_address
		);

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

		curl_setopt($curl, CURLOPT_HTTPHEADER, [

			'X-RapidAPI-Host: kvstore.p.rapidapi.com',

			'X-RapidAPI-Key: test',

			'Content-Type: application/json'

		]);

		$response = curl_exec($curl);

		// echo "<pre>checkout response:-";print_r($response);exit;

		curl_close($curl);

		$response = json_decode($response);

		if (isset($response->is_successful) && $response->is_successful == 1) {

			

			

			//send sms

			$SMSbody = "Your Vraj Fresh order ".$order_id." has been placed. It will be delivered soon. We will send you an update when your order is shipped. Thank you for shopping.";

			sendSMS($sms_shipping_phone, $SMSbody);

			

			

			/*commented by BBN $original_response = json_encode($response);

					 $ArrPayment['transaction_notes'] = $original_response;

					 $ArrPayment['payment_gateway_transaction_id'] = $ArrPayment['data']->id;

					 $ArrPayment['transaction_status'] = $ArrPayment['data']->status;

					 $ArrPayment['transaction_amount'] = $ArrPayment['data']->amount;

					 $ArrPayment['order_id'] = $order_id;



					 $url = API_URL.'stripe/add_payment_details';

					 $data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrPayment);

					 $curl = curl_init();

					 curl_setopt($curl, CURLOPT_URL, $url);

					 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

					 curl_setopt($curl, CURLOPT_POST, true);

					 curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));

					 curl_setopt($curl, CURLOPT_HTTPHEADER, [

						 'X-RapidAPI-Host: kvstore.p.rapidapi.com',

						 'X-RapidAPI-Key: test',

						 'Content-Type: application/json'

					 ]);

					 $response = curl_exec($curl);



					 curl_close($curl);

				 */



			/*edirect to thank you page*/

			//$this->order_success($order_id);
			
			redirect(base_url('order-success/' . base64_encode($order_id)));

		} else {

			redirect(base_url('order-falied'));
			//$this->order_failed();

		}

	}

	public function order_success_new($order_id)
	{
		$this->cart->destroy();

		if (base64_decode($order_id)) {

			$Arrdata['order_id'] = base64_decode($order_id);
			$this->load->view('order_success', $Arrdata);

		} else {
			
			$Arrdata = array();
			$this->load->view('order_failed', $Arrdata);
		}

		

	}

	public function order_failed_new()
	{

		$Arrdata = array();

		$this->load->view('order_failed', $Arrdata);

	}

	function payment_process()
	{

		$_POST['card_num'] = str_replace(' ', '', trim($_POST['card_num']));

		//echo $card_num;

		// echo "<pre>";print_r($_POST);exit;

		$order_id = $_POST['order_id'];

		$zip_code = $_POST['zip_code'];

		$sms_shipping_phone = $_POST['sms_shipping_phone'];

		$_POST['order_amount'] = intval($_POST['order_amount']);

		//$order_amount =  $_POST['order_amount'];

		$ArrPayment = $_POST;



		/*Payment Process Start*/

		$url = API_URL . 'stripe/payment_process';

		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrPayment);

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

		curl_setopt($curl, CURLOPT_HTTPHEADER, [

			'X-RapidAPI-Host: kvstore.p.rapidapi.com',

			'X-RapidAPI-Key: test',

			'Content-Type: application/json'

		]);

		$response = curl_exec($curl);

		//echo "<pre>checkout response:-";print_r($response);exit;

		curl_close($curl);

		$response = json_decode($response);

		if (isset($response->is_successful) && $response->is_successful == 1) {

			

			

			//send sms

			$SMSbody = "Your Vraj Fresh order ".$order_id." has been placed. It will be delivered soon. We will send you an update when your order is shipped. Thank you for shopping.";

			sendSMS($sms_shipping_phone, $SMSbody);

			

			

			/*commented by BBN $original_response = json_encode($response);

					 $ArrPayment['transaction_notes'] = $original_response;

					 $ArrPayment['payment_gateway_transaction_id'] = $ArrPayment['data']->id;

					 $ArrPayment['transaction_status'] = $ArrPayment['data']->status;

					 $ArrPayment['transaction_amount'] = $ArrPayment['data']->amount;

					 $ArrPayment['order_id'] = $order_id;



					 $url = API_URL.'stripe/add_payment_details';

					 $data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrPayment);

					 $curl = curl_init();

					 curl_setopt($curl, CURLOPT_URL, $url);

					 curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

					 curl_setopt($curl, CURLOPT_POST, true);

					 curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));

					 curl_setopt($curl, CURLOPT_HTTPHEADER, [

						 'X-RapidAPI-Host: kvstore.p.rapidapi.com',

						 'X-RapidAPI-Key: test',

						 'Content-Type: application/json'

					 ]);

					 $response = curl_exec($curl);



					 curl_close($curl);

				 */



			/*edirect to thank you page*/

			$this->order_success($order_id);

		} else {

			$this->order_failed();

		}

	}

	function order_success($order_id)
	{

		$this->cart->destroy();

		$Arrdata['order_id'] = $order_id;

		$this->load->view('order_success', $Arrdata);

	}

	function order_failed()
	{

		$Arrdata = array();

		$this->load->view('order_failed', $Arrdata);

	}

}