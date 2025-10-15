<?php
function send_response_to_api($ArrData, $errors = '', $success_message = '')
{
	//header("Access-Control-Allow-Origin: *");

	if ((is_array($ArrData) && count($ArrData) > 0) || $success_message != '') {
		$ArrResponse = array('is_successful' => '1', 'error_code' => -1, 'data' => $ArrData, 'errors' => '', 'success_message' => $success_message);
		$myJSON = json_encode($ArrResponse);
		header('Content-Type: application/json');
		echo $myJSON;
	} else {
		$ArrError = array('is_successful' => '0', 'error_code' => 400, 'data' => null, 'errors' => $errors, 'success_message' => '');
		$myJSON = json_encode($ArrError);
		header('Content-Type: application/json');
		echo $myJSON;
	}
}
function send_response_to_api_with_extra_parameters($ArrData, $extra_parameters = array(), $errors = '', $success_message = '')
{
	//header("Access-Control-Allow-Origin: *");

	if ((is_array($ArrData) && count($ArrData) > 0) || $success_message != '') {
		$ArrResponse = array('is_successful' => '1', 'error_code' => -1, 'data' => $ArrData, 'data2' => $extra_parameters, 'errors' => '', 'success_message' => $success_message);
		$myJSON = json_encode($ArrResponse);
		header('Content-Type: application/json');
		echo $myJSON;
	} else {
		$ArrError = array('is_successful' => '0', 'error_code' => 400, 'data' => null, 'data2' => $extra_parameters, 'errors' => $errors, 'success_message' => '');
		$myJSON = json_encode($ArrError);
		header('Content-Type: application/json');
		echo $myJSON;
	}
}
function check_oauth_key($oauth_key)
{

	if ($oauth_key != 'F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT') {
		$errors = "Error: The api oAuth key is not valid.";

		$ArrError = array('is_successful' => '0', 'error_code' => 400, 'data' => null, 'errors' => $errors);
		$myJSON = json_encode($ArrError);
		header('Content-Type: application/json');
		echo $myJSON;
	} else {
		return true;
	}
}

function check_user_token($user_token)
{
	return true;
	/*if($user_token!='')
	{
	$ArrUser = $this->user_login_model->getUserByAPIToken($user_token);

	if(is_array($ArrUser) && count($ArrUser)>0)
	{
	return true;
	}
	else
	{
	$errors = "Error: The user token is not valid.";

	$ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors);
	$myJSON = json_encode($ArrError);
	header('Content-Type: application/json');
	echo $myJSON;
	}
	}
	else
	{
	$errors = "Error: The user token is not valid.";

	$ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors);
	$myJSON = json_encode($ArrError);
	header('Content-Type: application/json');
	echo $myJSON;
	}*/
}
function get_fedex_token()
{
	$requestUrl = "https://apis-sandbox.fedex.com/oauth/token";
	$arrPostFields = ('grant_type=client_credentials&client_id=' . FEDEX_CLIENT_ID . '&client_secret=' . FEDEX_CLIENT_SECRET);
	$header = array();
	$header[] = 'Content-type: application/x-www-form-urlencoded';

	//$user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

	$options = array(
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_CUSTOMREQUEST => "POST",
		//set request type post or get
		// CURLOPT_POST           =>false,        //set to GET
		//  CURLOPT_USERAGENT      => $user_agent, //set user agent
		CURLOPT_COOKIEFILE => "cookie.txt",
		//set cookie file
		CURLOPT_COOKIEJAR => "cookie.txt",
		//set cookie jar
		CURLOPT_RETURNTRANSFER => true,
		// return web page
		CURLOPT_HEADER => false,
		// don't return headers
		CURLOPT_FOLLOWLOCATION => true,
		// follow redirects
		CURLOPT_ENCODING => "",
		// handle all encodings
		CURLOPT_AUTOREFERER => true,
		// set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,
		// timeout on connect
		CURLOPT_TIMEOUT => 120,
		// timeout on response
		CURLOPT_MAXREDIRS => 10,
		// stop after 10 redirects
		CURLOPT_POSTFIELDS => $arrPostFields,
		CURLOPT_SSL_VERIFYPEER => false
	);


	$ch = curl_init($requestUrl);
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	$err = curl_errno($ch);
	$errmsg = curl_error($ch);
	//  $header  = curl_getinfo( $ch );
	curl_close($ch);


	$result = json_decode($content);
	$token = $result->access_token;
	return $token;
}
function get_rate_by_weight($qty, $dimension, $weight, $shipper_city, $stateOrProvinceCode, $postalCode, $countryCode)
{
	$quantity = $qty;
	$dimension = $dimension;
	$weight = $weight;
	$token = get_fedex_token();
	$ship_payload = '{
	"rateRequestControlParameters": {
	"returnTransitTimes": true
	},
	"accountNumber": {
	"value": "740561073"
	},
	"freightRequestedShipment": {
	"serviceType": "FEDEX_FREIGHT_PRIORITY",
	"shipper": {
	"address": {
	"city": "HARRISON",
	"stateOrProvinceCode": "AR",
	"postalCode": "72601",
	"countryCode": "US"
	}
	},
	"recipient": {
	"address": {
	"city": "' . $shipper_city . '",
	"stateOrProvinceCode": "' . $stateOrProvinceCode . '",
	"postalCode": "' . $postalCode . '",
	"countryCode": "' . $countryCode . '"
	}
	},
	"shippingChargesPayment": {
	"paymentType": "SENDER",
	"payor": {
	"responsibleParty": {
	"accountNumber": {
	"value": "510087020"
	}
	}
	}
	},
	"freightShipmentDetail": {
	"role": "SHIPPER",
	"accountNumber": {
	"value": "510087020"
	},
	"fedExFreightBillingContactAndAddress": {
	"address": {
	"streetLines": [
	"1202 CHALET LN"
	],
	"city": "NEW YORK",
	"stateOrProvinceCode": "NY",
	"postalCode": "10001",
	"countryCode": "US"
	}
	},
	"lineItem": [
	{
	"freightClass": "CLASS_050",
	"handlingUnits": "' . $quantity . '",
	"pieces": 1,
	"subPackagingType": "BUNDLE",
	"id": "books",
	"weight": {
	"units": "KG",
	"value": "' . $weight . '"
	}
	}
	]
	},
	"rateRequestType": [
	"LIST"
	],
	"requestedPackageLineItems": [
	{
	"associatedFreightLineItems": [
	{
	"id": "books"
	}
	],
	"weight": {
	"units": "KG",
	"value": "' . $weight . '"
	},
	"subPackagingType": "BUNDLE"
	}
	]
	}
	}';
	file_put_contents('payload.txt', $ship_payload);
	$requestUrl = "https://apis-sandbox.fedex.com/rate/v1/freight/rates/quotes";
	$header = array();
	$header[] = 'Content-type: application/json';
	$header[] = 'Authorization:Bearer ' . $token;
	$options = array(
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_CUSTOMREQUEST => "POST",
		//set request type post or get
		// CURLOPT_POST           =>false,        //set to GET
		//  CURLOPT_USERAGENT      => $user_agent, //set user agent
		CURLOPT_COOKIEFILE => "cookie.txt",
		//set cookie file
		CURLOPT_COOKIEJAR => "cookie.txt",
		//set cookie jar
		CURLOPT_RETURNTRANSFER => true,
		// return web page
		CURLOPT_HEADER => false,
		// don't return headers
		CURLOPT_FOLLOWLOCATION => true,
		// follow redirects
		CURLOPT_ENCODING => "",
		// handle all encodings
		CURLOPT_AUTOREFERER => true,
		// set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,
		// timeout on connect
		CURLOPT_TIMEOUT => 120,
		// timeout on response
		CURLOPT_MAXREDIRS => 10,
		// stop after 10 redirects
		CURLOPT_POSTFIELDS => $ship_payload,
		CURLOPT_SSL_VERIFYPEER => false
	);


	$ch = curl_init($requestUrl);
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	$err = curl_errno($ch);
	$errmsg = curl_error($ch);
	//  $header  = curl_getinfo( $ch );
	curl_close($ch);


	$result = json_decode($content);

	return $result;
}


function get_ship()
{

	$token = get_fedex_token();
	$ship_payload = '{
	"labelResponseOptions": "URL_ONLY",
	"requestedShipment": {
	"shipper": {
	"contact": {
	"personName": "SHIPPER NAME",
	"phoneNumber": 9100025783,
	"companyName": "Shipper Company Name"
	},
	"address": {
	"streetLines": [
	"SHIPPER STREET LINE 1"
	],
	"city": "HARRISON",
	"stateOrProvinceCode": "AR",
	"postalCode": 72601,
	"countryCode": "US"
	}
	},
	"recipients": [
	{
	"contact": {
	"personName": "RECIPIENT NAME",
	"phoneNumber": 1234567890,
	"companyName": "Recipient Company Name"
	},
	"address": {
	"streetLines": [
	"RECIPIENT STREET LINE 1",
	"RECIPIENT STREET LINE 2"
	],
	"city": "Collierville",
	"stateOrProvinceCode": "TN",
	"postalCode": 38017,
	"countryCode": "US"
	}
	}
	],
	"shipDatestamp": "2022-06-03",
	"serviceType": "STANDARD_OVERNIGHT",
	"packagingType": "FEDEX_SMALL_BOX",
	"pickupType": "USE_SCHEDULED_PICKUP",
	"blockInsightVisibility": false,
	"shippingChargesPayment": {
	"paymentType": "SENDER"
	},
	"shipmentSpecialServices": {
	"specialServiceTypes": [
	"FEDEX_ONE_RATE"
	]
	},
	"labelSpecification": {
	"imageType": "PDF",
	"labelStockType": "PAPER_85X11_TOP_HALF_LABEL"
	},
	"requestedPackageLineItems": [
	{}
	]
	},
	"accountNumber": {
	"value": "801472842"
	}
	}';
	$requestUrl = "https://apis-sandbox.fedex.com/ship/v1/shipments";
	$header = array();
	$header[] = 'Content-type: application/json';
	$header[] = 'Authorization:Bearer ' . $token;
	$options = array(
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_CUSTOMREQUEST => "POST",
		//set request type post or get
		// CURLOPT_POST           =>false,        //set to GET
		//  CURLOPT_USERAGENT      => $user_agent, //set user agent
		CURLOPT_COOKIEFILE => "cookie.txt",
		//set cookie file
		CURLOPT_COOKIEJAR => "cookie.txt",
		//set cookie jar
		CURLOPT_RETURNTRANSFER => true,
		// return web page
		CURLOPT_HEADER => false,
		// don't return headers
		CURLOPT_FOLLOWLOCATION => true,
		// follow redirects
		CURLOPT_ENCODING => "",
		// handle all encodings
		CURLOPT_AUTOREFERER => true,
		// set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,
		// timeout on connect
		CURLOPT_TIMEOUT => 120,
		// timeout on response
		CURLOPT_MAXREDIRS => 10,
		// stop after 10 redirects
		CURLOPT_POSTFIELDS => $ship_payload,
		CURLOPT_SSL_VERIFYPEER => false
	);


	$ch = curl_init($requestUrl);
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	$err = curl_errno($ch);
	$errmsg = curl_error($ch);
	//  $header  = curl_getinfo( $ch );
	curl_close($ch);


	$result = json_decode($content);

	// $tracking_number=$result->output->transactionShipments[0]->masterTrackingNumber;
	// echo $tracking_number;
	return $result;
}
function cancel_ship()
{
	$token = get_fedex_token();
	$ship_cancel_payload = '{
	"accountNumber": {
	"value": "801472842"
	},
	"trackingNumber": "794934944694"
	}';
	$result = get_shipment_cancellation_detail($token, $ship_cancel_payload);
	echo "<pre>";
	print_r($result);
}
function get_track_detail($token, $input_payload)
{
	$requestUrl = "https://apis-sandbox.fedex.com/track/v1/trackingdocuments";
	$header = array();
	$header[] = 'Content-type: application/json';
	$header[] = 'Authorization:Bearer ' . $token;
	$options = array(
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_CUSTOMREQUEST => "POST",
		//set request type post or get
		// CURLOPT_POST           =>false,        //set to GET
		//  CURLOPT_USERAGENT      => $user_agent, //set user agent
		CURLOPT_COOKIEFILE => "cookie.txt",
		//set cookie file
		CURLOPT_COOKIEJAR => "cookie.txt",
		//set cookie jar
		CURLOPT_RETURNTRANSFER => true,
		// return web page
		CURLOPT_HEADER => false,
		// don't return headers
		CURLOPT_FOLLOWLOCATION => true,
		// follow redirects
		CURLOPT_ENCODING => "",
		// handle all encodings
		CURLOPT_AUTOREFERER => true,
		// set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,
		// timeout on connect
		CURLOPT_TIMEOUT => 120,
		// timeout on response
		CURLOPT_MAXREDIRS => 10,
		// stop after 10 redirects
		CURLOPT_POSTFIELDS => $input_payload,
		CURLOPT_SSL_VERIFYPEER => false
	);


	$ch = curl_init($requestUrl);
	curl_setopt_array($ch, $options);
	$content = curl_exec($ch);
	$err = curl_errno($ch);
	$errmsg = curl_error($ch);
	//  $header  = curl_getinfo( $ch );
	curl_close($ch);


	$result = json_decode($content);


	return $result;
}
function track_api()
{
	$token = get_fedex_token();
	$track_payload = '{
	"trackDocumentSpecification": [
	{
	"trackingNumberInfo": {
	"trackingNumber": "794934944694"
	}
	}
	],
	"trackDocumentDetail": {
	"documentType": "SIGNATURE_PROOF_OF_DELIVERY"
	}
	}';

	$result = get_track_detail($token, $track_payload);
	return $result;
}
function stripe_payment_capture($token, $payment_intent_id, $amount_to_capture)
{
	if (!empty($token)) {
		require_once 'vendor/autoload.php';

		$stripe = array("secret_key" => STRIPE_SECRET_KEY, "publishable_key" => STRIPE_PUBLISHABLE_KEY);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		$intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

		$response = $intent->capture(['amount_to_capture' => $amount_to_capture]);
		$response = $response->jsonSerialize();
		return $response;
	} else {
		return false;
	}
}
function stripe_payment_cancel($token, $payment_intent_id)
{
	if (!empty($token)) {
		require_once 'vendor/autoload.php';

		$stripe = array("secret_key" => STRIPE_SECRET_KEY, "publishable_key" => STRIPE_PUBLISHABLE_KEY);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		$intent = \Stripe\PaymentIntent::cancel($payment_intent_id);

		$response = $intent->capture(['amount_to_capture' => $amount_to_capture]);
		$response = $response->jsonSerialize();
		return $response;
	} else {
		return false;
	}
}
function stripe_payment_process($token, $user_details, $card_details, $items,$order_id)
{
	require_once 'vendor/autoload.php';

	if (!empty($token)) {
		//get token, card and user info from the form
		$name = $user_details['name'];
		$email = $user_details['email'];

		$zip_code = $user_details['zip_code'];
		$card_num = $card_details['card_num'];
		$card_cvc = $card_details['cvc'];
		$card_exp_month = $card_details['exp_month'];
		$card_exp_year = $card_details['exp_year'];


		//set api key
		$stripe = array(
			"secret_key" => STRIPE_SECRET_KEY,
			"publishable_key" => STRIPE_PUBLISHABLE_KEY
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);

		//add customer to stripe
		$customer = \Stripe\Customer::create(
			array(
				'email' => $user_details['email'],
				'address' => array('postal_code' => $user_details['zip_code']),
				'source' => $token,
			)
		);

		//item information
		$itemPrice = $items['itemPrice'];
		$currency = $items['currency'];
		
		/*Added By BBN*/
		$charge = \Stripe\PaymentIntent::create(
			array('customer' => $customer->id, 'amount' => $itemPrice, 'currency' => $currency, 'confirm' => true, 'capture_method' => 'manual','metadata'=>array('order_id'=>$order_id,'description'=>"Order ".$order_id." from Vraj Fresh"),'description'=>"Order ".$order_id." from Vraj Fresh")
		);
		//retrieve charge details
		$chargeJson = $charge->jsonSerialize();
		/*Added By BBN*/

		if ($chargeJson['id'] != '') {
			return $chargeJson;
		} else {
			return "false";
		}
	}

}

function stripe_payment_process_new($stripeCustId, $card_id, $save_card, $cardToken, $StripeCardID, $CardPaymentMethodId, $user_details, $items, $order_id, $payment_methodtype='',$gpay_arr='')
{
	$returnArr = array(
		'status' => false,
		'data' => array(),
		'msg' => '',
		'CardPaymentMethodId' => ''
	);

	try{
		require_once 'vendor/autoload.php';

		$name = $user_details['name'];
		$email = $user_details['email'];
		$zip_code = $user_details['zip_code'];
		$user_id = $user_details['user_id'];


		/* Load Master Model */
		$CI =& get_instance();		
		$CI->load->model('master_model', 'master');
		
		if($payment_methodtype == 'stripe_paymethod'){		
			if($card_id > 0){ // When use existing card
				
				$card_details = $CI->master->get_row_detail('tbl_cards', array('card_id' => $card_id));
				if(!empty($card_details)){
					
					if($stripeCustId != ""){ // When use existing card & existing customer

						$stripe = array(
							"secret_key" => STRIPE_SECRET_KEY,
							"publishable_key" => STRIPE_PUBLISHABLE_KEY
						);
					
						\Stripe\Stripe::setApiKey($stripe['secret_key']);

						/* Existing Card - Retrieve Card Payment Method Details */
						try{
							$paymentMethod = \Stripe\PaymentMethod::retrieve($card_details['CardPaymentMethodId']);
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'Existing Card Error retrieve PaymentMethod  : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}

						/* Existing Card - Update the PaymentMethod to associate it with the customer */
						try{
							$paymentMethod->attach(['customer' => $stripeCustId]);
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'Existing Card Error attach PaymentMethod : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}

						$itemPrice = $items['itemPrice'];
						$currency = $items['currency'];
						try{

							/* Create Charge */
							$charge = \Stripe\PaymentIntent::create([
								'amount' => $itemPrice,
								'currency' => $currency,
								'customer' => $stripeCustId,
								'payment_method' => $card_details['CardPaymentMethodId'],
								'capture_method' => 'automatic',
								'confirm' => true,
								'metadata' => array(
									'order_id'=>$order_id,
									'description'=>"Order ".$order_id." from Vraj Fresh"
								),
								'description'=>"Order ".$order_id." from Vraj Fresh"
							]);

						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'Existing Card Error creating paymentIntent : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}

						$chargeJson = $charge->jsonSerialize();
						if ($chargeJson['id'] != '') {
							
							$returnArr['status'] = true;
							$returnArr['data'] = $chargeJson;
							$returnArr['CardPaymentMethodId'] = $card_details['CardPaymentMethodId'];
							$returnArr['msg'] = 'Payment is processing by existing card & customer.';
						}

					} else {
						$returnArr['msg'] = "Customer Id Not Found In Database When Existing Card Use";
					}
				} else {
					$returnArr['msg'] = "Card Details Not Found";
				}
			} else {			
				
				if($stripeCustId != ""){ // When Customer Exist & Card Is New

					$stripe = array(
						"secret_key" => STRIPE_SECRET_KEY,
						"publishable_key" => STRIPE_PUBLISHABLE_KEY
					);
				
					\Stripe\Stripe::setApiKey($stripe['secret_key']);

					/* Retrieve Card Payment Method Details */
					try{
						$paymentMethod = \Stripe\PaymentMethod::retrieve($CardPaymentMethodId);
					}  catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'New Card & Exist Customer Error retrieve PaymentMethod  : ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}
					
					/* Update the PaymentMethod to associate it with the customer */
					try{
						$paymentMethod->attach(['customer' => $stripeCustId]);
					}  catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'New Card & Exist Customer Error attach PaymentMethod : ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}

					$itemPrice = $items['itemPrice'];
					$currency = $items['currency'];
					try{

						/* Create Charge */
						$charge = \Stripe\PaymentIntent::create([
							'amount' => $itemPrice,
							'currency' => $currency,
							'customer' => $stripeCustId,
							'payment_method' => $CardPaymentMethodId,
							'capture_method' => 'automatic',
							'confirm' => true,
							'metadata' => array(
								'order_id'=>$order_id,
								'description'=>"Order ".$order_id." from Vraj Fresh"
							),
							'description'=>"Order ".$order_id." from Vraj Fresh"
						]);

					}  catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'New Card & Exist Customer Error creating paymentIntent : ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}

					if($save_card == 1){ // When user have selected card save option
						
						/* Fetch Card Details By Payment Method */
						try{
							$StripeCardDetails = $paymentMethod->card;
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'New Card & Exist Customer Error retrieve card details For Card Save From PaymentMethod : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}
						try{
						
							$ExistCard_details = $CI->master->get_row_detail('tbl_cards', array('user_id' => $user_id, 'card_uniqe_id' => $StripeCardID, 'is_active' => 1));
							if(empty($ExistCard_details)){
								/* Card Details Save For User */
								$CardInsertArr = array(
									'user_id' => $user_id,
									'card_holder' => $name,
									'card_uniqe_id' => $StripeCardID,
									'CardPaymentMethodId' => $CardPaymentMethodId,
									'card_no' => $StripeCardDetails->last4,
									'card_brand' => $StripeCardDetails->brand,
									'created_date' => date("Y-m-d H:i:s"),
									'is_active' => 1
								);
								$CI->master->insertData('tbl_cards', $CardInsertArr);
							} 

						} catch (Exception $e) {
							$returnArr['msg'] = "New Card & Exist Customer Card Insert In DB Problem : " . $e->getMessage();
						}
					}

					$chargeJson = $charge->jsonSerialize();
					if ($chargeJson['id'] != '') {
						$returnArr['status'] = true;
						$returnArr['data'] = $chargeJson;
						$returnArr['CardPaymentMethodId'] = $CardPaymentMethodId;
						$returnArr['msg'] = 'Payment is processing by new card with existing customer.';
					}

				} else { // When Customer Not Exist & New Card
								
					$stripe = array(
						"secret_key" => STRIPE_SECRET_KEY,
						"publishable_key" => STRIPE_PUBLISHABLE_KEY
					);
				
					\Stripe\Stripe::setApiKey($stripe['secret_key']);

					/* Create New Customer */
					try{			
						$customer = \Stripe\Customer::create(
							array(
								'email' => $user_details['email'],
								'address' => array('postal_code' => $user_details['zip_code']),
							)
						);
					}  catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'New Card & New Customer Error creating customer: ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}
					
					/* Retrieve Card Payment Method Details */
					try{
						$paymentMethod = \Stripe\PaymentMethod::retrieve($CardPaymentMethodId);
					}  catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'New Card & New Customer Error retrieve PaymentMethod  : ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}	
					
					/* Update the PaymentMethod to associate it with the customer */
					try{
						$paymentMethod->attach(['customer' => $customer->id]);
					}  catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'New Card & New Customer Error attach PaymentMethod : ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}

					$itemPrice = $items['itemPrice'];
					$currency = $items['currency'];
					try{

						/* Create Charge */
						$charge = \Stripe\PaymentIntent::create([
							'amount' => $itemPrice,
							'currency' => $currency,
							'customer' => $customer->id,
							'payment_method' => $CardPaymentMethodId,
							'capture_method' => 'automatic',
							'confirm' => true,
							'metadata' => array(
								'order_id'=>$order_id,
								'description'=>"Order ".$order_id." from Vraj Fresh"
							),
							'description'=>"Order ".$order_id." from Vraj Fresh"
						]);

					}  catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'New Card & New Customer Error creating paymentIntent : ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}

					/* Add Cutomer Id In User Details */
					$updateData = array(
						'stripe_cus_id' => $customer->id
					);
					$CI->master->update_detail('tbl_users', $updateData, array('user_id' => $user_id));

					if($save_card == 1){ // When user have selected card save option
						
						/* Fetch Card Details By Payment Method */
						try{
							$StripeCardDetails = $paymentMethod->card;
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'New Card & New Customer Error retrieve card details For Card Save From PaymentMethod : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}
						try{
							$ExistCard_details = $CI->master->get_row_detail('tbl_cards', array('user_id' => $user_id, 'card_uniqe_id' => $StripeCardID, 'is_active' => 1));
							if(empty($ExistCard_details)){
								/* Card Details Save For User */
								$CardInsertArr = array(
									'user_id' => $user_id,
									'card_holder' => $name,
									'card_uniqe_id' => $StripeCardID,
									'CardPaymentMethodId' => $CardPaymentMethodId,
									'card_no' => $StripeCardDetails->last4,
									'card_brand' => $StripeCardDetails->brand,
									'created_date' => date("Y-m-d H:i:s"),
									'is_active' => 1
								);
								$CI->master->insertData('tbl_cards', $CardInsertArr);
							}
						} catch (Exception $e) {
							$returnArr['msg'] = "New Card & New Customer Card Insert In DB Problem : " . $e->getMessage();
						}
					}
					
					$chargeJson = $charge->jsonSerialize();
					if ($chargeJson['id'] != '') {
						$returnArr['status'] = true;
						$returnArr['data'] = $chargeJson;
						$returnArr['CardPaymentMethodId'] = $CardPaymentMethodId;
						$returnArr['msg'] = 'Payment is processing by new card & customer.';
					}
				}
			}
		}elseif($payment_methodtype == 'gpay_paymethod'){
		
					$gpay_encode = json_decode($gpay_arr, true);
			
					$stripe = array(
						"secret_key" => STRIPE_SECRET_KEY,
						"publishable_key" => STRIPE_PUBLISHABLE_KEY
					);
				
					\Stripe\Stripe::setApiKey($stripe['secret_key']);

					$token = $gpay_encode['id'] ?? null;
					if (!$token) {
						echo json_encode(['error' => 'No token provided']);
						exit;
					}

					// Create a PaymentMethod using the token from Google Pay
					try{
						$gen_paymentMethod = \Stripe\PaymentMethod::create([
							'type' => 'card',
							'card' => [
								'token' => $token
							]
						]);
					}catch (\Stripe\Exception\ApiErrorException $e) {
						$msg = 'Customer Error retrieve PaymentMethod  : ' . $e->getMessage();
						$returnArr['msg'] = $msg;
					}
					
					if($stripeCustId != ""){
					
						/* Retrieve Card Payment Method Details */
						try{
							$paymentMethod = \Stripe\PaymentMethod::retrieve($gen_paymentMethod->id);
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'Customer Error retrieve PaymentMethod  : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}

						/* Update the PaymentMethod to associate it with the customer */
						try{
							$paymentMethod->attach(['customer' => $stripeCustId]);
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'Customer Error attach PaymentMethod : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}

						$itemPrice = $items['itemPrice'];
						$currency = $items['currency'];
						try{
							// /* Create Charge */
							// $charge = \Stripe\PaymentIntent::create([
							// 	'amount' => $itemPrice,
							// 	'currency' => $currency,
							// 	'payment_method' => $paymentMethod->id,
							// 	'confirm' => true,
							// 	'customer' => $customer->id,
							// 	'automatic_payment_methods' => [
							// 		'enabled' => true,
							// 		'allow_redirects' => 'never', // block redirect-based methods
							// 	],
							// 	'metadata' => array(
							// 		'order_id'=>$order_id,
							// 		'description'=>"Order ".$order_id." from Vraj Fresh"
							// 	),
							// 	'description'=>"Order ".$order_id." from Vraj Fresh"
							// ]);

							$charge = \Stripe\PaymentIntent::create([
								'amount' => $itemPrice,
								'currency' => $currency,
								'customer' => $stripeCustId,
								'payment_method' => $paymentMethod->id,
								'capture_method' => 'automatic',
								'confirm' => true,
								'metadata' => array(
									'order_id'=>$order_id,
									'description'=>"Order ".$order_id." from Vraj Fresh"
								),
								'description'=>"Order ".$order_id." from Vraj Fresh"
							]);

						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'Google Pay Error creating paymentIntent : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}

					}else{
						/* Create New Customer */
						try{			
							$customer = \Stripe\Customer::create(
								array(
									'email' => $user_details['email'],
									'address' => array('postal_code' => $user_details['zip_code']),
								)
							);
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'New Card & New Customer Error creating customer: ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}
						
						/* Retrieve Card Payment Method Details */
						try{
							$paymentMethod = \Stripe\PaymentMethod::retrieve($gen_paymentMethod->id);
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'New Card & New Customer Error retrieve PaymentMethod  : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}	
						
						/* Update the PaymentMethod to associate it with the customer */
						try{
							$paymentMethod->attach(['customer' => $customer->id]);
						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'New Card & New Customer Error attach PaymentMethod : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}


						
						$itemPrice = $items['itemPrice'];
						$currency = $items['currency'];
						try{
							// /* Create Charge */
							// $charge = \Stripe\PaymentIntent::create([
							// 	'amount' => $itemPrice,
							// 	'currency' => $currency,
							// 	'payment_method' => $paymentMethod->id,
							// 	'confirm' => true,
							// 	'customer' => $customer->id,
							// 	'automatic_payment_methods' => [
							// 		'enabled' => true,
							// 		'allow_redirects' => 'never', // block redirect-based methods
							// 	],
							// 	'metadata' => array(
							// 		'order_id'=>$order_id,
							// 		'description'=>"Order ".$order_id." from Vraj Fresh"
							// 	),
							// 	'description'=>"Order ".$order_id." from Vraj Fresh"
							// ]);

							$charge = \Stripe\PaymentIntent::create([
								'amount' => $itemPrice,
								'currency' => $currency,
								'customer' => $customer->id,
								'payment_method' => $paymentMethod->id,
								'capture_method' => 'automatic',
								'confirm' => true,
								'metadata' => array(
									'order_id'=>$order_id,
									'description'=>"Order ".$order_id." from Vraj Fresh"
								),
								'description'=>"Order ".$order_id." from Vraj Fresh"
							]);

						}  catch (\Stripe\Exception\ApiErrorException $e) {
							$msg = 'Google Pay Error creating paymentIntent : ' . $e->getMessage();
							$returnArr['msg'] = $msg;
						}

						/* Add Cutomer Id In User Details */
						$updateData = array(
							'stripe_cus_id' => $customer->id
						);
						$CI->master->update_detail('tbl_users', $updateData, array('user_id' => $user_id));

					}


					//print_r($charge);exit;
					$chargeJson = $charge->jsonSerialize();
					if ($chargeJson['id'] != '') {
						$returnArr['status'] = true;
						$returnArr['data'] = $chargeJson;
						$returnArr['CardPaymentMethodId'] = $charge->payment_method;
						$returnArr['msg'] = 'Payment is processing by gpay card.';
					}
			
		}

		$updateErrorData = array(
			'payment_log' => $returnArr['msg']
		);
		$CI->master->update_detail('tbl_orders', $updateErrorData, array('order_id' => $order_id));
		return $returnArr;

	} catch (Exception $e) {

		$updateErrorData = array(
			'payment_log' => "MAIN ERROR - " . $e->getMessage()
		);
		$CI->master->update_detail('tbl_orders', $updateErrorData, array('order_id' => $order_id));
		return $returnArr;
	}

	

}

function stripe_capture_extra_payment($stripeCustId, $user_details, $payment_intent_id, $CardPaymentMethodId, $items, $order_id)
{

	$returnArr = array(
		'status' => false,
		'data' => array(),
		'msg' => '',
	);

	try{
		require_once 'vendor/autoload.php';

		$name = $user_details['name'];
		$email = $user_details['email'];
		$zip_code = $user_details['zip_code'];
		$user_id = $user_details['user_id'];


		/* Load Master Model */
		$CI =& get_instance();		
		$CI->load->model('master_model', 'master');


		$itemPrice = $items['itemPrice'];
		$currency = $items['currency'];
		try{

			$stripe = array(
				"secret_key" => STRIPE_SECRET_KEY,
				"publishable_key" => STRIPE_PUBLISHABLE_KEY
			);
		
			\Stripe\Stripe::setApiKey($stripe['secret_key']);
			

			/* Create Charge */
			$charge = \Stripe\PaymentIntent::create([
				'amount' => $itemPrice,
				'currency' => $currency,
				'customer' => $stripeCustId,
				'payment_method' => $CardPaymentMethodId,
				'capture_method' => 'automatic',
				'confirm' => true,
				'metadata' => array(
					'order_id'=>$order_id,
					'description'=>"Order Update ".$order_id." from Vraj Fresh"
				),
				'description'=>"Order Update ".$order_id." from Vraj Fresh"
			]);


		}  catch (\Stripe\Exception\ApiErrorException $e) {
			$returnArr['msg'] = 'Error creating paymentIntent : ' . $e->getMessage();
		}

		$chargeJson = $charge->jsonSerialize();
		if ($chargeJson['id'] != '') {
			$returnArr['status'] = true;
			$returnArr['data'] = $chargeJson;
			$returnArr['CardPaymentMethodId'] = $CardPaymentMethodId;
			$returnArr['msg'] = 'Payment is processing by new card with existing customer.';
		}
		return $returnArr;

	} catch (Exception $e) {
		return $returnArr;
	}
	return $returnArr;
}

function stripe_refund_extra_payment($stripeCustId, $user_details, $payment_intent_id, $CardPaymentMethodId, $items, $order_id)
{

	$returnArr = array(
		'status' => false,
		'data' => array(),
		'msg' => '',
	);

	try{
		require_once 'vendor/autoload.php';

		$name = $user_details['name'];
		$email = $user_details['email'];
		$zip_code = $user_details['zip_code'];
		$user_id = $user_details['user_id'];


		/* Load Master Model */
		$CI =& get_instance();		
		$CI->load->model('master_model', 'master');


		$itemPrice = $items['itemPrice'];
		$currency = $items['currency'];
		try{

			$stripe = array(
				"secret_key" => STRIPE_SECRET_KEY,
				"publishable_key" => STRIPE_PUBLISHABLE_KEY
			);
		
			\Stripe\Stripe::setApiKey($stripe['secret_key']);

			$refund = \Stripe\Refund::create([
				'payment_intent' => $payment_intent_id,
				'amount' => $itemPrice,
			]);


		} catch (\Stripe\Exception\ApiErrorException $e) {
			$returnArr['msg'] = 'Error creating refund : ' . $e->getMessage();
		}

		$refundJson = $refund->jsonSerialize();
		
		if ($refundJson['id'] != '') {
			$returnArr['status'] = true;
			$returnArr['data'] = $refundJson;
			$returnArr['msg'] = 'Payment is refund by admin.';
		}
		return $returnArr;

	} catch (Exception $e) {
		return $returnArr;
	}
	return $returnArr;
}
/* GET EXTRA PAYMENT FROM CARD - ADMIN PROCESS NOT IN USSSSSSSSSSSSSSSSSSSSSSSSE*/
/* 
function get_stripe_payment($token, $user_details, $card_details, $items,$order_id)
{
	require_once 'vendor/autoload.php';

	if (!empty($token)) {
		//get token, card and user info from the form
		$name = $user_details['name'];
		$email = $user_details['email'];

		$zip_code = $user_details['zip_code'];
		$card_num = $card_details['card_num'];
		$card_cvc = $card_details['cvc'];
		$card_exp_month = $card_details['exp_month'];
		$card_exp_year = $card_details['exp_year'];


		//set api key
		$stripe = array(
			"secret_key" => STRIPE_SECRET_KEY,
			"publishable_key" => STRIPE_PUBLISHABLE_KEY
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);

		//add customer to stripe
		$customer = \Stripe\Customer::create(
			array(
				'email' => $user_details['email'],
				'address' => array('postal_code' => $user_details['zip_code']),
				'source' => $token,
			)
		);

		//item information
		$itemPrice = $items['itemPrice'];
		$currency = $items['currency'];
		//removed 
		$charge = \Stripe\PaymentIntent::create(
			array('customer' => $customer->id, 'amount' => $itemPrice, 'currency' => $currency, 'confirm' => true, 'capture_method' => 'automatic','metadata'=>array('order_id'=>$order_id,'description'=>"Order ".$order_id." from Vraj Fresh"),'automatic_payment_methods'=>array('enabled'=>true),'description'=>"Order ".$order_id." from Vraj Fresh")
		);
		//retrieve charge details
		$chargeJson = $charge->jsonSerialize();
		//echo "<pre>bbn";print_r($chargeJson);
		if ($chargeJson['id'] != '') {
			return $chargeJson;
		} else {
			return "false";
		}
	}

}*/

function generateStripeToken($cardNumber, $expMonth, $expYear, $cvc) {
    try {
		require_once 'vendor/autoload.php';
		//set api key
		$stripe = array(
			"secret_key" => STRIPE_SECRET_KEY,
			"publishable_key" => STRIPE_PUBLISHABLE_KEY
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		
        $token = \Stripe\Token::create([
            'card' => [
                'number' => $cardNumber,
                'exp_month' => $expMonth,
                'exp_year' => $expYear,
                'cvc' => $cvc,
            ],
        ]);
        return $token->id;
    } catch (\Stripe\Exception\CardException $e) {
        // Handle card errors
        return false;
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        // Handle other Stripe API errors
        return false;
    }
}

function createPaymentIntent($amount, $currency, $description) {
    try {
		
		require_once 'vendor/autoload.php';
		//set api key
		$stripe = array(
			"secret_key" => STRIPE_SECRET_KEY,
			"publishable_key" => STRIPE_PUBLISHABLE_KEY
		);

		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,'capture_method' => 'automatic'
        ]);
		
        return $intent;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Handle API errors
        return false;
    }
}





function send_mail_old($email, $subject, $message)
{
	$ci = &get_instance();
	$config = array();
	$config['protocol'] = 'smtp';
	$config['smtp_host'] = SMTP_HOST;
	$config['smtp_user'] = SMTP_USER;
	$config['smtp_pass'] = SMTP_PASSWORD;
	$config['smtp_port'] = SMTP_PORT;
	$config['mailtype'] = 'html';
	$config['charset'] = 'utf-8';
	$config['wordwrap'] = true;
	// $config['smtp_crypto'] = 'ssl';

	$ci->email->initialize($config);
	$ci->email->set_mailtype("html");
	$ci->email->from(SMTP_USER, "Vraj Fresh Admin");
	$ci->email->to($email);
	$ci->email->subject($subject);
	$ci->email->message($message);
	if ($ci->email->send()) {
		return true;
	} else {
		// echo $ci->email->print_debugger();
		$myfile = fopen("logs.txt", "w") or die("Unable to open file!");
		$txt = $ci->email->print_debugger();
		fwrite($myfile, $txt);
		fclose($myfile);

		return false;
	}

}

function send_mail_old2($email, $subject, $message)
{
    $ci = &get_instance();
    
    // Include PHPMailer library
    // require 'application/libraries/phpmailer/src/PHPMailerAutoload.php';
	
	// require getcwd() .'/application/libraries/phpmailer/src/Exception.php';
	// require getcwd() .'/application/libraries/phpmailer/src/PHPMailer.php';
	// require getcwd() .'/application/libraries/phpmailer/src/SMTP.php';
    
    // use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\Exception;

	
	// Create a new PHPMailer instance
    // $mail = new PHPMailer();
	// Load PHPMailer library
	$ci->load->library('phpmailer_lib');
    
	// PHPMailer object
	$mail = $ci->phpmailer_lib->load();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = 'tls'; // Use 'tls' instead of 'ssl'
    $mail->Port = SMTP_PORT;
    
    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    // Set 'From' address
    $mail->setFrom(SMTP_USER, "Vraj Fresh Admin");
    
    // Add recipient
    $mail->addAddress($email);
    
    // Disable peer verification for self-signed certificates (similar to your previous code)
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ),
    );
    
    // Set CRLF (Carriage Return and Line Feed) to "\r\n" for Windows compatibility
    // $mail->setCRLF("\r\n");

    // Attempt to send the email
    if ($mail->send()) {
        return true;
    } else {
        // Handle errors (You can log or return false as needed)
        $error_message = $mail->ErrorInfo;
        
        // Log the error message or handle it as needed
        $myfile = fopen("logs2.txt", "w");
        fwrite($myfile, $error_message);
        fclose($myfile);
        
        return false;
    }
}

function send_mail($email, $subject, $message) {
	$ci = &get_instance();
	
	// Include PHPMailer library
	
	// Google API credentials
	$clientID = '17611857669-17auiif79mg37o0m7o5q5vkhcn9571h8.apps.googleusercontent.com';
	$clientSecret = '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';
	// $refreshToken = 'YOUR_REFRESH_TOKEN';

	// Sender's email address (Google Workspace)
	$senderEmail = 'orders@vrajfresh.com';
	$senderName = 'Vraj Fresh';

	// Recipient's email address
	$recipientEmail = $email;
	$recipientName = $email;

	// Email content
	// $subject = 'Subject of your email';
	$body = $message;

	// Load PHPMailer
	$ci->load->library('phpmailer_lib');

	$mail = $ci->phpmailer_lib->load();

	try {
		// Server settings
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = $senderEmail;
		// $mail->Password = 'uwttjgpqsmbigcei'; // Your email password or app password if 2-Step Verification is enabled
		$mail->Password = 'dsgvjyolexvianvm'; // Your email password or app password if 2-Step Verification is enabled
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		// $mail->SMTPDebug = 4;

		// Set OAuth2 authentication
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true,
			),
		);

		// $mail->AuthType = 'OAUTHBEARER'; //'XOAUTH2';
		$mail->oauthUserEmail = $senderEmail;
		$mail->password = 'orders5899';
		// $mail->oauthClientId = $clientID;
		// $mail->oauthClientSecret = $clientSecret;
		// $mail->oauthRefreshToken = $refreshToken;

		// Recipient
		$mail->setFrom($senderEmail, $senderName);
		$mail->addAddress($recipientEmail, $recipientName);

		// Content
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $body;

		// Send email
		if ($mail->send()) {

			$logMessage = date('Y-m-d H:i:s') . " | To: $recipientEmail | Subject: $subject | Status: Success \n";
			file_put_contents('email_logs.txt', $logMessage, FILE_APPEND);

			return true;
			// echo 'Email sent successfully';
		} else {
			// Handle errors (You can log or return false as needed)
			// echo $mail->ErrorInfo;

			$logMessage = date('Y-m-d H:i:s') . " | To: $recipientEmail | Subject: $subject | Status: Failed | Error: " . $mail->ErrorInfo . "\n";
			file_put_contents('email_logs.txt', $logMessage, FILE_APPEND);

			$myfile = fopen("logs2.txt", "w");
			fwrite($myfile, $mail->ErrorInfo);
			fclose($myfile);
			return false;
		}
		
	} catch (Exception $e) {

		$logMessage = date('Y-m-d H:i:s') . " | To: $recipientEmail | Subject: $subject | Status: Failed | Error: " . $mail->ErrorInfo . "\n";
		file_put_contents('email_logs.txt', $logMessage, FILE_APPEND);

		// echo 'Email could not be sent. Error: ', $mail->ErrorInfo;
		$myfile = fopen("logs2.txt", "w");
		fwrite($myfile, $mail->ErrorInfo);
		fclose($myfile);
		
		return false;
	}
}

function send_contact_mail($to_email, $from_email, $subject, $message)
{
	$ci = &get_instance();
	$config = array();
	$config['protocol'] = 'smtp';
	$config['smtp_host'] = SMTP_HOST;
	$config['smtp_user'] = SMTP_USER;
	$config['smtp_pass'] = SMTP_PASSWORD;
	$config['smtp_port'] = SMTP_PORT;
	$config['mailtype'] = 'html';
	$config['charset'] = 'utf-8';
	$config['wordwrap'] = true;

	$ci->email->initialize($config);
	$ci->email->set_mailtype("html");
	$ci->email->from($from_email);
	$ci->email->to($to_email);
	$ci->email->subject($subject);
	$ci->email->message($message);
	if ($ci->email->send()) {
		return true;
	} else {
		return false;
	}
}

function GUID()
{
	if (function_exists('com_create_guid') === true) {
		return trim(com_create_guid(), '{}');
	}

	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function remove_special_characters($string)
{
	$new_string = str_replace(array("'", '"', '%', '!', '`', '^', '&', " ' ", ' " ', ' % ', ' ! ', ' ` ', ' ^ ', ' & '), '-', $string);
	return strtolower(trim(str_replace(' ', '', $new_string)));
}


if (!function_exists('sendSMS')) {
    function sendSMS($to, $body)
    {
		if(MODE=="DEV")
		{
			return true;
		}
		if($to!='')
		{
			$id = "AC7d6f6f9b2c442e6e50c7929ba3cd05dd";
			$token = "197a07dc872735f1d26ed9f72b832b60";
			$from = '+14178052045';
			
			$COUNTRY = "US";
			if($COUNTRY=="US")
			{
				$pre_fix = "1"; //US: 1 IND: 91
				$pre_fix_char_count = "1"; //US: 1 IND: 2
			}
			else
			{
				$pre_fix = "91"; //US: 1 IND: 91
				$pre_fix_char_count = "2"; //US: 1 IND: 2
			}
			//CHECK AND ADD COUNTRY CODE
			if(substr($to,0,1)!="+")
			{
				if(substr($to,0,$pre_fix_char_count)!=$pre_fix)
				{
					$to = "+".$pre_fix.$to;
				}
				else
				{
					$to = "+".$to;
				}
			}
			$url = "https://api.twilio.com/2010-04-01/Accounts/$id/Messages.json";
			$data = array(
				'From' => $from,
				'To' => $to,
				'Body' => $body,
			);
			$post = http_build_query($data);
			$x = curl_init($url);
			curl_setopt($x, CURLOPT_POST, true);
			curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
			curl_setopt($x, CURLOPT_POSTFIELDS, $post);
			$y = curl_exec($x);
			curl_close($x);
			//var_dump($post);
			//var_dump($y);
		}
    }
}