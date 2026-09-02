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
		header('HTTP/1.1 400 Bad Request');
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

	
	$CI =& get_instance();		
	$CI->load->model('master_model', 'master');
		
	if(!empty($oauth_key)){			
		$is_token_expired = $CI->master->check_user_token_expired($oauth_key);
		if ($is_token_expired) {
			return true;
		} else {
			$errors = "Token is expired.";

			$ArrError = array('is_successful' => '0', 'error_code' => 401, 'data' => null, 'errors' => $errors);
			$myJSON = json_encode($ArrError);
			header('HTTP/1.1 401 Unauthorized');
			echo $myJSON;
		}
	}else{
		$success_message = 'Token is required';
		$ArrResponse = array('is_successful' => '1', 'error_code' => -1, 'data' => [], 'errors' => '', 'success_message' => $success_message);
		$myJSON = json_encode($ArrResponse);
		header('Content-Type: application/json');
		echo $myJSON;
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

		
								
		$stripe = array(
			"secret_key" => STRIPE_SECRET_KEY,
			"publishable_key" => STRIPE_PUBLISHABLE_KEY
		);
	
		\Stripe\Stripe::setApiKey($stripe['secret_key']);

		
		$stripe = new \Stripe\StripeClient($stripe['secret_key']);
		try {
		

			// Retrieve the intent first
			$intent = \Stripe\PaymentIntent::retrieve($cardToken);
			$charge = $intent->charges->data[0];
			$details = $charge->payment_method_details;

			$paymentTypem = 'unknown';

			if ($details->type === 'card' && isset($details->card->wallet->type)) {
				$paymentTypem = $details->card->wallet->type; // apple_pay or google_pay
			}elseif ($details->type === 'card') {
				$paymentTypem = 'stripe card';
			}

		} catch (\Stripe\Exception\ApiErrorException $e) {
			echo $e;
		$msg = 'Error PaymentMethod : ' . $e->getMessage();
		$returnArr['msg'] = $msg;
		}


					$itemPrice = $items['itemPrice'];
					$currency = $items['currency'];
					
					$chargeJson = $charge->jsonSerialize();

					if ($chargeJson['id'] != '') {
						$returnArr['status'] = true;
						$returnArr['data'] = $chargeJson;
						$returnArr['payment_i_id'] = $cardToken;
						$returnArr['CardPaymentMethodId'] = $CardPaymentMethodId;
						$returnArr['msg'] = 'Payment is processing by new card & customer.';
					}


		
		$updateErrorData = array(
			'payment_log' => $returnArr['msg'],
			'payment_methodtype' => $paymentTypem,
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

function send_mail($email, $subject, $message,$attachment='') {
	// return true;
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
		// $mail->Password = 'dsgvjyolexvianvm'; // Your email password or app password if 2-Step Verification is enabled
		$mail->Password = 'jcfp mqgx xhcg dcbw'; // Updated on 22 Feb, 2025
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
        if(!empty($attachment)){
            $mail->addAttachment($attachment);
        }

		// Send email
		if ($mail->send()) {
			return true;
			// echo 'Email sent successfully';
		} else {
			// Handle errors (You can log or return false as needed)
			 //echo $mail->ErrorInfo;
			$myfile = fopen("logs2.txt", "w");
			fwrite($myfile, $mail->ErrorInfo);
			fclose($myfile);
			return false;
		}
		
	} catch (Exception $e) {
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

function getHolidayDatearr($from_date,$to_date,$zone_id)
{
	
    $ci =& get_instance();
    $ci->db->select('holiday_date');
    $ci->db->from('tbl_zones');
    $ci->db->where('zone_id', $zone_id);

    $query = $ci->db->get();

    $result = $query->row();

    $zone_holiday_date = $result->holiday_date ?? '';
	$dates = [];

	// Get all dates between start and end date
	if(!empty($from_date) && !empty($to_date)){
		$start = new DateTime($from_date);
		$end   = new DateTime($to_date);

		while ($start <= $end) {
			$dates[] = $start->format('Y-m-d');
			$start->modify('+1 day');
		}
	}

	// Add another single date
	if(!empty($zone_holiday_date)){
		$dates[] = $zone_holiday_date;
	}

	// Remove duplicate dates and reset array keys
	$dates = array_values(array_unique($dates));

	return $dates;
}