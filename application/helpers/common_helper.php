<?php



if (!function_exists('sendSMS')) {

    function sendSMS($to, $body)

    {

		// return true;

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



if (!function_exists('facebook_login')) {

	function facebook_login()

	{

		require_once 'vendor/autoload.php';



		if (!session_id()) {

			session_start();

		}



		// Call Facebook API



		$facebook = new \Facebook\Facebook([

			'app_id' => FACEBOOK_APP_ID,

			'app_secret' => FACEBOOK_APP_SECRET,

			'default_graph_version' => 'v2.10'

		]);



		$facebook_output = '';



		$facebook_helper = $facebook->getRedirectLoginHelper();

		// echo "<pre>";print_r($facebook_helper->getAccessToken());exit;

		if (isset($_GET['code'])) {



			if (isset($_SESSION['access_token'])) {

				$access_token = $_SESSION['access_token'];

			} else {

				$access_token = $facebook_helper->getAccessToken();



				$_SESSION['access_token'] = $access_token;



				$facebook->setDefaultAccessToken($_SESSION['access_token']);

			}



			$_SESSION['user_id'] = '';

			$_SESSION['user_name'] = '';

			$_SESSION['user_email_address'] = '';

			$_SESSION['user_image'] = '';



			$graph_response = $facebook->get("/me?fields=first_name,last_name,name,email", $access_token);



			$facebook_user_info = $graph_response->getGraphUser();



			// if(!empty($facebook_user_info['id']))

			// {

			//     $_SESSION['user_image'] = 'http://localhost/facebook-login/index.php'.$facebook_user_info['id'].'/picture';

			// }



			if (!empty($facebook_user_info['name'])) {

				$_SESSION['user_name'] = $facebook_user_info['name'];

			}



			if (!empty($facebook_user_info['email'])) {

				$_SESSION['user_email_address'] = $facebook_user_info['email'];

			}

			if (isset($_SESSION['user_image']) && $_SESSION['user_image'] != "" && isset($_SESSION['user_name']) && $_SESSION['user_name'] != "" && isset($_SESSION['user_email_address']) && $_SESSION['user_email_address'] != "") {

				return "true";

			} else {

				return "false";

			}

		} else {

			// Get login url

			$facebook_permissions = ['email']; // Optional permissions



			$facebook_login_url = $facebook_helper->getLoginUrl(FACEBOOK_URL, $facebook_permissions);



			// Render Facebook login button

			// $facebook_login_url = '<div align="center"><a href="'.$facebook_login_url.'"><img src="php-login-with-facebook.gif" /></a></div>';

			return $facebook_login_url;

		}



	}



}

/* IS User Login*/

if (!function_exists('IsUserLogin')) {

    function IsUserLogin()
    {
        $ci = &get_instance();

        // Restore session from cookie if session expired
        if (
            !isset($ci->session->userdata['logged_in']) &&
            $ci->input->cookie('user_id')
        ) {

            $session_data = array(
                'user_id'      => $ci->input->cookie('user_id'),
                'email'        => $ci->input->cookie('email'),
                'user_name'    => $ci->input->cookie('user_name'),
                'user_role_id' => $ci->input->cookie('user_role_id'),
                'Is_login'     => true
            );

            $ci->session->set_userdata('logged_in', $session_data);
        }

        // Final login check
        if (
            isset($ci->session->userdata['logged_in']) &&
            !empty($ci->session->userdata['logged_in']['Is_login'])
        ) {
            return true;
        }

        return false;
    }
}

/* Get States with Tax Code */

if (!function_exists('getStatesWithTaxCode')) {

	function getStatesWithTaxCode($include_tax = 'yes')

	{

		$url = API_URL . 'get-state-by-country-id';

		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "geo_id" => 'US');

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

		$ArrState = array();

		$ArrStateOption = array();

		$ArrStateOption[''] = "--State--";



		if ($response->is_successful == 1) {

			$ArrState = $response->data;

			if (count($ArrState) > 0) {

				foreach ($response->data as $row) {

					if ($include_tax == 'yes')

						$optionValue = $row->tax . '|' . $row->state_id;

					else

						$optionValue = $row->state_id;

					$ArrStateOption[$optionValue] = $row->state;

				}

			}

		}

		return $ArrStateOption;

	}

}

/* Get States */

if (!function_exists('getStates')) {

	function getStates()

	{

		$url = API_URL . 'get-state-by-country-id';

		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "geo_id" => 'US');

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

		$ArrState = array();

		$ArrStateOption = array();

		$ArrStateOption[''] = "--State--";



		if ($response->is_successful == 1) {

			$ArrState = $response->data;

			if (count($ArrState) > 0) {

				foreach ($response->data as $row) {

					$optionValue = $row->state_id;

					$ArrStateOption[$optionValue] = $row->state;

				}

			}

		}

		return $ArrStateOption;

	}

}

if (!function_exists('getStateName')) {

	function getStateName($id)

	{

		$url = API_URL . 'get-state-by-country-id';

		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "geo_id" => 'US');

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

		$ArrState = array();

		$state_name = '';



		if ($response->is_successful == 1) {

			$ArrState = $response->data;

			if (count($ArrState) > 0) {

				foreach ($response->data as $row) {

					if ($row->state_id == $id)

						$state_name = $row->state;

				}

			}

		}

		return $state_name;

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

	}

	// else if(getallheaders()['Content-Type'] != "application/json")

	// {

	// 	$errors = "Request Must Be In JSON Format ";



	// 	$ArrError = array('is_successful' => '0','error_code' => 400,'data' =>null,'errors' =>$errors);

	// 	$myJSON = json_encode($ArrError);

	// 	header('Content-Type: application/json');

	// 	echo $myJSON;

	// }

	else {

		return true;

	}

}

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

function remove_special_characters($string)

{

	$new_string = str_replace(

		array(

			"'",

			'"',

			'%',

			'!',

			'`',

			'^',

			'&',

			" ' ",

			' " ',

			' % ',

			' ! ',

			' ` ',

			' ^ ',

			' & '

		), '-', $string);

	return strtolower(trim(str_replace(' ', '', $new_string)));

}
function get_ci_rowid($db_rowid) {
    $CI =& get_instance();

    foreach ($CI->cart->contents() as $item) {
        if (isset($item['options']['db_rowid']) && $item['options']['db_rowid'] == $db_rowid) {
            return $item['rowid']; // ✅ return CI rowid
        }
    }

    return false;
}