<?php

defined('BASEPATH') or exit('No direct script access allowed');

error_reporting(0);

class Stripe extends CI_Controller

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
		$this->load->model('master_model', 'master');
		$this->load->model('users_model');

	}

	public function payment_capture()

	{



		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

		$ArrPayment = (array) $json_obj->ArrPayment;

		$oauth_key = $json_obj->oauth_key;

		$errors = $success_message = '';

		$ArrData = array();

		$token = $ArrPayment['stripeToken'];

		$payment_intent_id = $ArrPayment['payment_intent_id'];

		$amount_to_capture = $ArrPayment['amount_to_capture'];



		$payment_details = stripe_payment_capture($token, $payment_intent_id, $amount_to_capture);

		send_response_to_api($payment_details, $errors, $success_message);

	}

	public function payment_cancel()

	{



		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

		$ArrPayment = (array) $json_obj->ArrPayment;

		$oauth_key = $json_obj->oauth_key;

		$errors = $success_message = '';

		$ArrData = array();

		$token = $ArrPayment['stripeToken'];

		$payment_intent_id = $ArrPayment['payment_intent_id'];

		//call function in common helper

		$payment_details = stripe_payment_cancel($token, $payment_intent_id);

		send_response_to_api($payment_details, $errors, $success_message);

	}

	public function payment_refund_track()

	{



		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

		$ArrPayment = (array) $json_obj->ArrPayment;

		$oauth_key = $json_obj->oauth_key;

		$errors = $success_message = '';

		$ArrData = array();

		$refund_id = $ArrPayment['refund_id'];

		//call function in common helper

		$payment_details = stripe_payment_refund_track($refund_id);

		send_response_to_api($payment_details, $errors, $success_message);

	}


	/* GET BLOCKED PAYMENT - ADMIN PROCESS */

	public function payment_process_old()
	{

		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

		$ArrPayment = (array) $json_obj->ArrPayment;



		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';

		$ArrData = array();



		$token = $ArrPayment['stripeToken'];

		$user_details = array('name' => $ArrPayment['name'], 'email' => $ArrPayment['email'], 'zip_code' => $ArrPayment['zip_code']);



		$card_details = array(

			'card_num' => str_replace(' ', '', trim($ArrPayment['card_num'])),

			'card_cvc' => $ArrPayment['cvc'],

			'card_exp_month' => $ArrPayment['exp_month'],

			'card_exp_year' => $ArrPayment['exp_year']

		);



		$Arrorderdetail = $this->orders_model->get_order_details($ArrPayment['order_id']);

		

		$Arrorderproductdetail = $this->orders_model->get_orderproduct_details_without_image($ArrPayment['order_id']);

		

		#selcet user_id

		$ArrUser = $this->orders_model->get_user_id($ArrPayment['order_id']);



		# Stored card details in customer table 

		$card_details_stored = $this->orders_model->get_stored_card_details($ArrUser[0]['user_id'], $card_details);



		$items = array(



			'itemPrice' => $ArrPayment['order_amount'],

			'currency' => "usd"

		);

		/* Call method in Helper */

		$payment_details = stripe_payment_process($token, $user_details, $card_details, $items,$ArrPayment['order_id']);

		

		//echo "payment_details";

		//print_r($payment_details);exit;

		/*if($payment_details !="false")*/

		if ($payment_details['id'] != '') {

			$errors = "";

			$success_message = 'The order payment made successfuly';

			$stripe_raw_response = json_encode($payment_details);

			$payment_detail_data = array(

				"stripe_raw_response" => $stripe_raw_response,

				"payment_intent_id" => $payment_details['id'],

				"payment_intent_status" => $payment_details['status'],

				"order_id" => $ArrPayment['order_id'],

				"transaction_amount" => $ArrPayment['order_amount'] / 100,

				"transaction_datetime" => date("Y-m-d H:i:s"),

				"created_datetime" => date("Y-m-d H:i:s")

			);

			/* ADD RECORD IN TRANSACTION TABLE */

			$this->orders_model->add_order($payment_detail_data, 'tbl_transactions');



			if ($payment_details['status'] == 'requires_capture') {

				$order_data = array(

					'order_status' => 'Processing',

					'payment_intent_id' => $payment_details['id']

				);

				$this->orders_model->update_order($order_data, $ArrPayment['order_id'], 'tbl_orders');

				$order_details = $this->orders_model->get_order_details($ArrPayment['order_id']);

				$subject = "VrajFresh Order Place Successful";

				$order_content = file_get_contents('templates/order_mail_template.html');

				$order_content = str_replace('##user_name##', $Arrorderdetail[0]['shipping_first_name']." ".$Arrorderdetail[0]['shipping_last_name'], $order_content);

				$order_content = str_replace('##firstname##', $Arrorderdetail[0]['shipping_first_name']." ".$Arrorderdetail[0]['shipping_last_name'], $order_content);

				$order_content = str_replace('##amount##', $Arrorderdetail[0]['order_total_amount'], $order_content);

				$order_content = str_replace('##order_id##', $ArrPayment['order_id'], $order_content);

				// Add a CSS style attribute to your table tag

				$table_style = 'style="border: 1px solid #ccc; border-collapse: collapse;"';

				$order_content = str_replace('##table_style##', $table_style, $order_content);



				// Create table for displaying order details

				$table_content = '<table style="border-collapse: collapse; width: 100%;">';

				$table_content .= '<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Tax</th><th>Total Price</th></tr>';

				$total_state_tax = 0 ;

				foreach ($Arrorderproductdetail as $product) {

					$table_content .= '<tr>';

					$table_content .= '<td style="border: 1px solid grey; padding: 5px;">' . $product['product_name'] . '(' . $product['product_variant_size'] . ')</td>';

					$table_content .= '<td style="border: 1px solid grey; padding: 5px;">' . $product['qty'] . '</td>';

					$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product['unit_price'] . '</td>';

					$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product['product_tax_amount'] . '</td>';

					$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product['total_amount'] . '</td>';

					$table_content .= '</tr>';

					$total_state_tax = $total_state_tax + $product['product_tax_amount'];

				}

				$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Delivery:</td><td style="border: 1px solid grey; padding: 5px;">Free Delivery</td></tr>';

				$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">State Tax:</td><td style="border: 1px solid grey; padding: 5px;">$' . $total_state_tax . '</td></tr>';

				$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Order Discount:</td><td style="border: 1px solid grey; padding: 5px;">$' . $Arrorderdetail[0]['discount_amount'] . '</td></tr>';

				$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Order Tip:</td><td style="border: 1px solid grey; padding: 5px;">$' . $Arrorderdetail[0]['order_tip'] . '</td></tr>';

				$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Total:</td><td style="border: 1px solid grey; padding: 5px;">$' . $Arrorderdetail[0]['order_total_amount'] . '</td></tr>';

				$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Notes:</td><td style="border: 1px solid grey; padding: 5px;">' . $Arrorderdetail[0]['order_notes'] . '</td></tr>';

				$table_content .= '</table>';



				$order_content = str_replace('##product_table##', $table_content, $order_content);



				$order_content = str_replace('##address1##', $Arrorderdetail[0]['billing_street_name'], $order_content);

				$order_content = str_replace('##address2##', $Arrorderdetail[0]['billing_apartment_name'], $order_content);

				$order_content = str_replace('##city##', $Arrorderdetail[0]['billing_city'], $order_content);

				$order_content = str_replace('##state##', $Arrorderdetail[0]['billing_state'], $order_content);

				$order_content = str_replace('##zip##', $Arrorderdetail[0]['billing_zipcode'], $order_content);

				$order_content = str_replace('##phone##', $Arrorderdetail[0]['billing_phone'], $order_content);

				$order_content = str_replace('##email##', $Arrorderdetail[0]['shipping_email'], $order_content);



				$order_content = str_replace('##address_ship##', $Arrorderdetail[0]['shipping_street_name'], $order_content);

				$order_content = str_replace('##address2_ship##', $Arrorderdetail[0]['shipping_apartment_name'], $order_content);

				$order_content = str_replace('##city_ship##', $Arrorderdetail[0]['shipping_city'], $order_content);

				$order_content = str_replace('##state_ship##', $Arrorderdetail[0]['state'], $order_content);

				$order_content = str_replace('##zip_ship##', $Arrorderdetail[0]['shipping_zipcode'], $order_content);

				$order_content = str_replace('##phone_ship##', $Arrorderdetail[0]['shipping_phone'], $order_content);

				$order_content = str_replace('##link##', FRONT_URL, $order_content);



				send_mail($ArrPayment['email'], $subject, $order_content);



			}



		} else {

			$ArrPayment = array();

			$errors = 'The payment failed';

			$success_message = '';



		}

		send_response_to_api($ArrPayment, $errors, $success_message);

	}

	public function payment_process()
	{
		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

		$ArrPayment = (array) $json_obj->ArrPayment;
		$shipping_details = (array) $json_obj->shipping_details;
		$billing_details = (array) $json_obj->billing_details;
		$user_id = $json_obj->user_id;
		$same_address = $json_obj->same_address;

		/* $success_message = 'sd';
		$errors = '';
		send_response_to_api($billing_details, $errors, $success_message);
		exit; */

		//if($ArrPayment[''])
		
		$user_details = $this->master->get_row_detail('tbl_users', array('user_id' => $user_id));

		$billing_id = $ArrPayment['billing_id'];
		$shipping_id = $ArrPayment['shipping_id'];
		$card_id = $ArrPayment['card_id'];
		$save_card = $ArrPayment['save_card'];
		$CardToken = $ArrPayment['CardToken'];
		$StripeCardID = $ArrPayment['StripeCardID'];
		$CardPaymentMethodId = $ArrPayment['CardPaymentMethod'];
		$stripeCustId = "";
		if(!empty($user_details)){
			$stripeCustId = $user_details['stripe_cus_id'];
			$ArrPayment['email'] = $user_details['email'];
		}

		

		/* $success_message = 'sd';
		send_response_to_api($ArrPayment, $errors, $success_message);
		exit; */


		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';

		$ArrData = array();



		$token = $ArrPayment['CardToken'];
		
		if($same_address == 1){
			$billing_name = $shipping_details['first_name'] . ' ' .$shipping_details['last_name'];
			$billing_zipcode = $shipping_details['shipping_zipcode'];
		} else {
			$billing_name = $billing_details['first_name'] . ' ' .$billing_details['last_name'];
			$billing_zipcode = $billing_details['billing_zipcode'];
		}

		$user_details = array(
			'user_id' => $user_id,
			'name' => $billing_name,
			'email' => $user_details['email'],
			'zip_code' => $billing_zipcode
		);



		/* $card_details = array(

			'card_num' => str_replace(' ', '', trim($ArrPayment['card_num'])),

			'card_cvc' => $ArrPayment['cvc'],

			'card_exp_month' => $ArrPayment['exp_month'],

			'card_exp_year' => $ArrPayment['exp_year']

		); */



		$Arrorderdetail = $this->orders_model->get_order_details($ArrPayment['order_id']);

		

		$Arrorderproductdetail = $this->orders_model->get_orderproduct_details_without_image($ArrPayment['order_id']);

		

		#selcet user_id

		$ArrUser = $this->orders_model->get_user_id($ArrPayment['order_id']);



		# Stored card details in customer table 

		//$card_details_stored = $this->orders_model->get_stored_card_details($ArrUser[0]['user_id'], $card_details);



		$items = array(



			'itemPrice' => $ArrPayment['order_amount'],

			'currency' => "usd"

		);

		/* Call method in Helper */

		//$payment_details = stripe_payment_process($token, $user_details, $card_details, $items,$ArrPayment['order_id']);
		$payment_response = stripe_payment_process_new($stripeCustId, $card_id, $save_card, $CardToken, $StripeCardID, $CardPaymentMethodId, $user_details, $items, $ArrPayment['order_id'],$ArrPayment['payment_methodtype'],$ArrPayment['gpay_token_serialize']);

		//send_response_to_api($payment_response, "", "Äsd");

		/*if($payment_details !="false")*/
		//echo '<pre>';print_r($payment_response);exit;
		if($payment_response['status'] == true){

			$payment_details = $payment_response['data'];
			
			if ($payment_details['status'] == 'succeeded') {

				$errors = "";

				$success_message = 'The order payment made successfuly';
				
				$stripe_raw_response = json_encode($payment_details);

				$payment_detail_data = array(

					"stripe_raw_response" => $stripe_raw_response,

					"payment_intent_id" => $payment_response['payment_i_id'],

					"payment_process_type" => 'Automatic',
					
					"transaction_status" => $payment_details['status'],

					"payment_intent_status" => $payment_details['status'],

					"order_id" => $ArrPayment['order_id'],

					"transaction_amount" => $ArrPayment['order_amount'] / 100,
					
					"CardPaymentMethodId" => $payment_response['CardPaymentMethodId'],

					"payment_type" => 'capture',

					"transaction_datetime" => date("Y-m-d H:i:s"),

					"created_datetime" => date("Y-m-d H:i:s")

				);

				/* ADD RECORD IN TRANSACTION TABLE */

				$this->orders_model->add_order($payment_detail_data, 'tbl_transactions');

				//if ($payment_details['status'] == 'requires_capture') {
				if ($payment_details['status'] == 'succeeded') {

					$this->master->deleteRow('tbl_cart_items', array('customer_id' => $user_id));

					$order_data = array(

						'order_status' => 'Processing',

						'amount_received' => $ArrPayment['order_amount'] / 100,

						'amount_received_status' => $payment_details['status'],

						'payment_intent_id' => $payment_response['payment_i_id'],

						"CardPaymentMethodId" => $payment_response['CardPaymentMethodId'],

					);

					$this->orders_model->update_order($order_data, $ArrPayment['order_id'], 'tbl_orders');

					$order_details = $this->orders_model->get_order_details($ArrPayment['order_id']);

					$subject = "VrajFresh Order Place Successful";

					$order_content = file_get_contents('templates/order_mail_template.html');

					$order_content = str_replace('##user_name##', $Arrorderdetail[0]['shipping_first_name']." ".$Arrorderdetail[0]['shipping_last_name'], $order_content);

					$order_content = str_replace('##firstname##', $Arrorderdetail[0]['shipping_first_name']." ".$Arrorderdetail[0]['shipping_last_name'], $order_content);

					$order_content = str_replace('##amount##', $Arrorderdetail[0]['order_total_amount'], $order_content);

					$order_content = str_replace('##order_id##', $ArrPayment['order_id'], $order_content);

					// Add a CSS style attribute to your table tag

					$table_style = 'style="border: 1px solid #ccc; border-collapse: collapse;"';

					$order_content = str_replace('##table_style##', $table_style, $order_content);



					// Create table for displaying order details

					$table_content = '<table style="border-collapse: collapse; width: 100%;">';

					$table_content .= '<tr><th>Product Name</th><th>Quantity</th><th>Price</th><th>Tax</th><th>Total Price</th></tr>';

					$total_state_tax = 0 ;

					foreach ($Arrorderproductdetail as $product) {

						$table_content .= '<tr>';

						if(!empty($product['product_variant_size'])){
							$table_content .= '<td style="border: 1px solid grey; padding: 5px;">' . $product['product_name'] . '(' . $product['product_variant_size'] . ')</td>';
						}else{
							$table_content .= '<td style="border: 1px solid grey; padding: 5px;">' . $product['product_name']. '</td>';
						}

						$table_content .= '<td style="border: 1px solid grey; padding: 5px;">' . $product['qty'] . '</td>';

						$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product['unit_price'] . '</td>';

						$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product['product_tax_amount'] . '</td>';

						$table_content .= '<td style="border: 1px solid grey; padding: 5px;">$' . $product['total_amount'] . '</td>';

						$table_content .= '</tr>';

						$total_state_tax = $total_state_tax + $product['product_tax_amount'];

					}

					$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Delivery:</td><td style="border: 1px solid grey; padding: 5px;">Free Delivery</td></tr>';

					$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">State Tax:</td><td style="border: 1px solid grey; padding: 5px;">$' . $total_state_tax . '</td></tr>';

					$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Order Discount:</td><td style="border: 1px solid grey; padding: 5px;">$' . $Arrorderdetail[0]['discount_amount'] . '</td></tr>';

					$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Order Tip:</td><td style="border: 1px solid grey; padding: 5px;">$' . $Arrorderdetail[0]['order_tip'] . '</td></tr>';

					$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Total:</td><td style="border: 1px solid grey; padding: 5px;">$' . $Arrorderdetail[0]['order_total_amount'] . '</td></tr>';

					$table_content .= '<tr><td colspan="4" style="border: 1px solid grey; padding: 5px;">Notes:</td><td style="border: 1px solid grey; padding: 5px;">' . $Arrorderdetail[0]['order_notes'] . '</td></tr>';

					$table_content .= '</table>';



					$order_content = str_replace('##product_table##', $table_content, $order_content);



					$order_content = str_replace('##address1##', $Arrorderdetail[0]['billing_street_name'], $order_content);

					$order_content = str_replace('##address2##', $Arrorderdetail[0]['billing_apartment_name'], $order_content);

					$order_content = str_replace('##city##', $Arrorderdetail[0]['billing_city'], $order_content);

					$order_content = str_replace('##state##', $Arrorderdetail[0]['billing_state'], $order_content);

					$order_content = str_replace('##zip##', $Arrorderdetail[0]['billing_zipcode'], $order_content);

					$order_content = str_replace('##phone##', $Arrorderdetail[0]['billing_phone'], $order_content);

					$order_content = str_replace('##email##', $Arrorderdetail[0]['shipping_email'], $order_content);



					$order_content = str_replace('##address_ship##', $Arrorderdetail[0]['shipping_street_name'], $order_content);

					$order_content = str_replace('##address2_ship##', $Arrorderdetail[0]['shipping_apartment_name'], $order_content);

					$order_content = str_replace('##city_ship##', $Arrorderdetail[0]['shipping_city'], $order_content);

					$order_content = str_replace('##state_ship##', $Arrorderdetail[0]['state'], $order_content);

					$order_content = str_replace('##zip_ship##', $Arrorderdetail[0]['shipping_zipcode'], $order_content);

					$order_content = str_replace('##phone_ship##', $Arrorderdetail[0]['shipping_phone'], $order_content);

					$order_content = str_replace('##link##', FRONT_URL, $order_content);



					send_mail($ArrPayment['email'], $subject, $order_content);



				}



			} else {
				$ArrPayment = array();
				$errors = 'The payment failed';
				$success_message = '';

			}
		} else {
			$ArrPayment = array();
			$errors = 'The payment failed';
			$success_message = '';
		}
		send_response_to_api($ArrPayment, $errors, $success_message);

	}

	/* ORDER - DIFFRENCE PAYMENT */
	public function order_diffrence_payment()
	{
		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

		$ArrPayment = (array) $json_obj->ArrPayment;
		
		$order_id = $ArrPayment['order_id'];
		$difftype = $ArrPayment['difftype'];
		$diffamount = $ArrPayment['diffamount'];
		
		$OrderDetails = $this->master->get_row_detail('tbl_orders', array('order_id' => $order_id, 'is_deleted' => 0));
		
		if(!empty($OrderDetails)){
			
			$UserDetails = $this->master->get_row_detail('tbl_users', array('user_id' => $OrderDetails['user_id']));
			if(!empty($OrderDetails)){

				$stripeCustId = $UserDetails['stripe_cus_id'];
				if($stripeCustId != ""){
					

					$user_details = array(
						'user_id' => $OrderDetails['user_id'],
						'name' => $OrderDetails['billing_first_name'] . ' ' .$OrderDetails['billing_last_name'],
						'email' => $UserDetails['email'],
						'zip_code' => $OrderDetails['billing_zipcode'],
					);

					$items = array(
						'itemPrice' => $diffamount,
						'currency' => "usd"
					);

					$cond = array(
						'order_id' => $order_id,
						'payment_type' => 'capture',
						'payment_intent_status' => 'succeeded',
					);
					$TransactionDetails = $this->master->get_transaction_detail('tbl_transactions', $cond, 'transaction_amount');
					if(!empty($TransactionDetails)){
					
						$payment_intent_id = $TransactionDetails['payment_intent_id'];
						$CardPaymentMethodId = $TransactionDetails['CardPaymentMethodId'];

						if($difftype == "revert"){
							
							$refund_response = stripe_refund_extra_payment($stripeCustId, $user_details, $payment_intent_id, $CardPaymentMethodId, $items, $order_id);
							
							if($refund_response['status'] == true){

								$refund_details = $refund_response['data'];
								
								if ($refund_details['status'] == 'succeeded') {
					
									
									$stripe_raw_response = json_encode($refund_details);
					
									$newOrderAmount = $diffamount / 100;

									$payment_detail_data = array(
					
										"stripe_raw_response" => $stripe_raw_response,
					
										"payment_intent_id" => $refund_details['payment_intent'],

										"payment_refund_id" => $refund_details['id'],
					
										"payment_process_type" => 'Automatic',
										
										"transaction_status" => $refund_details['status'],
					
										"payment_intent_status" => $refund_details['status'],
					
										"order_id" => $ArrPayment['order_id'],
					
										"transaction_amount" => $newOrderAmount,
										
										"payment_type" => 'refund',
					
										"transaction_datetime" => date("Y-m-d H:i:s"),
					
										"created_datetime" => date("Y-m-d H:i:s")
					
									);
					
									/* ADD RECORD IN TRANSACTION TABLE */
					
									$this->orders_model->add_order($payment_detail_data, 'tbl_transactions');
					
									$errors = "";
									$success_message = 'Payment refunded successfully!!!!';
									$ArrPayment = $refund_details;
					
					
								} else {
									$ArrPayment = array();
									$errors = 'The payment refund failed';
									$success_message = '';
								}
							} else {
								$ArrPayment = array();
								$errors = 'The payment refund failed';
								$success_message = '';
							}
						
							$success_message = "Order payment refunded successfully!!!!";
							$errors = '';
						} else if($difftype == "capture"){
							
							$payment_response = stripe_capture_extra_payment($stripeCustId, $user_details, $payment_intent_id, $CardPaymentMethodId, $items, $order_id);

							if($payment_response['status'] == true){

								$payment_details = $payment_response['data'];
								
								if ($payment_details['status'] == 'succeeded') {
					
									
									$stripe_raw_response = json_encode($payment_details);
					
									$newOrderAmount = $diffamount / 100;
									$oldAmountReceived = $OrderDetails['amount_received'];
									$TotalReceivedAmount = $newOrderAmount + $oldAmountReceived;

									$payment_detail_data = array(
					
										"stripe_raw_response" => $stripe_raw_response,
					
										"payment_intent_id" => $payment_details['id'],
					
										"payment_process_type" => 'Automatic',
										
										"transaction_status" => $payment_details['status'],
					
										"payment_intent_status" => $payment_details['status'],
					
										"order_id" => $ArrPayment['order_id'],
					
										"transaction_amount" => $diffamount / 100,
										
										"CardPaymentMethodId" => $payment_response['CardPaymentMethodId'],
										
										"payment_type" => 'capture',
					
										"transaction_datetime" => date("Y-m-d H:i:s"),
					
										"created_datetime" => date("Y-m-d H:i:s")
					
									);
					
									/* ADD RECORD IN TRANSACTION TABLE */
					
									$this->orders_model->add_order($payment_detail_data, 'tbl_transactions');
					
									//if ($payment_details['status'] == 'requires_capture') {
									if ($payment_details['status'] == 'succeeded') {
					
										$order_data = array(
					
											'order_status' => 'Processing'
					
										);					
										$this->orders_model->update_order($order_data, $ArrPayment['order_id'], 'tbl_orders');					
									}
					
									$errors = "";
									$success_message = 'Order payment captured successfully!!!!';
									$ArrPayment = $payment_details;
					
					
								} else {
									$ArrPayment = array();
									$errors = 'The payment failed';
									$success_message = '';
								}
							} else {
								$ArrPayment = array();
								$errors = 'The payment failed';
								$success_message = '';
							}
						} else {
							$ArrPayment = array();
							$success_message = "";
							$errors = 'Type is invalid';		
						}

					} else {
						$ArrPayment = array();
						$success_message = "";
						$errors = 'Transaction details not found.';	
					}
				} else {
					$ArrPayment = array();
					$success_message = "";
					$errors = 'Stripe customer id not found.';	
				}
			} else {
				$ArrPayment = array();
				$success_message = "";
				$errors = 'User details not found.';	
			}
		} else {
			$ArrPayment = array();
			$success_message = "";
			$errors = 'Order details not found.';	
		}
		send_response_to_api($ArrPayment, $errors, $success_message);

	}

	/********************************************* GET EXTRA PAYMENT FROM CARD - ADMIN PROCESS *********************************************/

	public function get_stripe_payment()

	{

		$json_str = file_get_contents('php://input');

		$json_obj = json_decode($json_str);

		$ArrPayment = (array) $json_obj->ArrPayment;

		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';

		$ArrData = array();



		/*--------------------------------------------------------------------

		$token = $ArrPayment['stripeTempToken'];

		

		$user_details = array('name' => $ArrPayment['name'], 'email' => $ArrPayment['email'], 'zip_code' => $ArrPayment['zip_code']);



		$card_details = array(

			'card_num' => str_replace(' ', '', trim($ArrPayment['card_num'])),

			'card_cvc' => $ArrPayment['cvc'],

			'card_exp_month' => $ArrPayment['exp_month'],

			'card_exp_year' => $ArrPayment['exp_year']

		);

		//bbn send_response_to_api($card_details, $oauth_key, $success_message);

		/*$Arrorderdetail = $this->orders_model->get_order_details($ArrPayment['order_id']);



		$Arrorderproductdetail = $this->orders_model->get_orderproduct_details($ArrPayment['order_id']);

		

		#selcet user_id

		$ArrUser = $this->orders_model->get_user_id($ArrPayment['order_id']);



		# Stored card details in customer table 

		$card_details_stored = $this->orders_model->get_stored_card_details($ArrUser[0]['user_id'], $card_details);



		$items = array(

			'itemPrice' => $ArrPayment['order_amount'],

			'currency' => "usd"

		);

		//echo "bbn payment_details:";print_r($payment_details);

		/* Call method in Helper *

		$payment_details = get_stripe_payment($token, $user_details, $card_details, $items,$ArrPayment['order_id']);

		//echo "bbn payment_details:";print_r($payment_details);

		----------------------------------*/

				

		// Assuming you have received form data from the user

		$cardNumber = str_replace(' ', '', trim($ArrPayment['card_num']));

		$expMonth = $ArrPayment['exp_month']; 

		$expYear = $ArrPayment['exp_year']; 

		$cvc = $ArrPayment['cvc']; 

		$amount = $ArrPayment['order_amount'];

		$currency = 'usd'; 

		$description = 'Additional Order products cost of order # '.$ArrPayment['order_id'];

		// Generate the Stripe token - call function in helper

		$stripeToken = generateStripeToken($cardNumber, $expMonth, $expYear, $cvc);

		$paymentIntentId = 0;

		if ($stripeToken) {

			// Token generated successfully, proceed to create Payment Intent - call function in helper

			$objPayemntIntent = createPaymentIntent($amount, $currency, $description);



			$capture_payment_stripe_raw_response = json_encode($objPayemntIntent);

			$paymentIntentId = $objPayemntIntent->id;

			if ($paymentIntentId) {

				 

				// Payment Intent created successfully, you can now use $paymentIntentId for further processing

				

				$payment_detail_data = array(

				"stripe_raw_response" => $capture_payment_stripe_raw_response,

				"payment_intent_id" => $paymentIntentId,

				"payment_intent_status" => $stripeToken,

				"order_id" => $ArrPayment['order_id'],

				"transaction_amount" => $ArrPayment['order_amount'] / 100,

				"transaction_datetime" => date("Y-m-d H:i:s"),

				"created_datetime" => date("Y-m-d H:i:s")

				);

				$this->orders_model->add_order($payment_detail_data, 'tbl_transactions');

				

					

				/* Call method in Helper */

				$payment_details = stripe_payment_capture($stripeToken, $paymentIntentId, $amount);

				

				$capture_payment_stripe_raw_response = json_encode($payment_details);

				$errors = "";

				$success_message = 'The order payment made successfuly';

				

				$payment_detail_data = array(

				"stripe_raw_response" => "Extra Payment".$capture_payment_stripe_raw_response,

				"payment_intent_id" => $paymentIntentId,

				"payment_intent_status" => 'BBN',

				"order_id" => $ArrPayment['order_id'],

				"transaction_amount" => $ArrPayment['order_amount'] / 100,

				"transaction_datetime" => date("Y-m-d H:i:s"),

				"created_datetime" => date("Y-m-d H:i:s")

				);

				/* ADD RECORD IN TRANSACTION TABLE */

				$this->orders_model->add_order($payment_detail_data, 'tbl_transactions');

				

			}

			

		} else {

			// Handle token generation failure

			$paymentIntentId = 0;

		}

		

		

		send_response_to_api($ArrPayment, $errors, $success_message);

	}





	public function refund($payment_id)

	{

		$refund = stripe_payment_refund($payment_id);

		echo "<pre>";

		print_r($refund);



	}

	public function payment_success()

	{

		$this->load->view('payment_success');

	}



	public function payment_error()

	{

		$this->load->view('payment_error');

	}



	public function help()

	{

		$this->load->view('help');

	}



}