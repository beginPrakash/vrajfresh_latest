<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'third_party/twilio_loader.php';

class Controller_users extends CI_Controller
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
		$this->load->model('users_model');
		$this->load->model('otpverification_model');
		$this->load->model('usertoken_model');
		$this->load->library('cart');
		error_reporting(0);
	}
	public function get_users()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
				'sort_column' => $json_obj->sort_column,
				'sort_order' => $json_obj->sort_order
			);
			//var_dump($json_obj->search_keyword);exit;
			if ($json_obj->search_keyword == "") {

				$result = $this->users_model->get_users($data);
			} else {
				$result = $this->users_model->get_users_by_search($data, $json_obj->search_keyword);
			}
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function add_user()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		
		$errors = $success_message = '';
		$ArrData = array();
			$guid = GUID();

			$user_data = array(
				'user_role_id' => $json_obj->user_role_id,
				'user_name' => $json_obj->email,
				'first_name' => $json_obj->first_name,
				'last_name' => $json_obj->last_name,
				'display_name' => $json_obj->first_name.' '.$json_obj->last_name,
				//'password' => md5($json_obj->password),
				'email' => $json_obj->email,
				'phone' => $json_obj->phone_number,
				'mobile_no' => $json_obj->phone_number,
				'user_type' => 'app',
				// 'zipcode' => $json_obj->zipcode,
				'guid' => $guid,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => 1
			);
			$user_exist = $this->users_model->user_exist($user_data);
			if ($user_exist > 0) {
			    $user_rdata = $this->users_model->get_user_by_email($json_obj->email);
				if (count($user_rdata) > 0) {
				    $errors = 'Email ID Already Exist.Please try another Email ID';
				}else{
				    $errors = 'Mobile number Already Exist.Please try another Mobile number';
				}
			} else {
				
				$user_id = $this->users_model->add_user($user_data, 'tbl_users');
				if ($user_id) {
					$gen_otp = rand(1000, 9999);
					$otp_data = array(
						'user_id' => $user_id,
						'otp' => $gen_otp,
						'created_datetime' => date('Y-m-d H:i:s'),
						'modified_datetime' => date('Y-m-d H:i:s')
					);
					$otp_code = $this->otpverification_model->add_otpcode($otp_data);
					$conddata['user_id'] = $user_id;
					$result['userdata'] = $this->users_model->get_user_by_id($conddata);
					$result['gen_otp'] = $gen_otp;
					$ArrData = $result;

					$subject = "Vrajfresh Registration Successfully";
					$email_content = file_get_contents('templates/user_registration.html');
					$email_content = str_replace('##user_name##', $json_obj->first_name.' '.$json_obj->last_name, $email_content);
					// $email_content = str_replace('##link##', FRONT_URL . 'user-activate/' . $guid, $email_content);
					$email_content = str_replace('##wslink##', FRONT_URL, $email_content);

					send_mail($json_obj->email, $subject, $email_content);
					$success_message = 'User added successfully.';
				} else {
					$errors = 'User Not Added Successfully';
				}
			}

			send_response_to_api($ArrData, $errors, $success_message);
	}
	public function get_user_by_id()
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
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'user_id' => $json_obj->user_id
			);
			$result = $this->users_model->get_user_by_id($data);
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
	public function update_user()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$user_id = $json_obj->user_id;
			$data = array(
				'user_role_id' => $json_obj->user_role_id,
				'user_name' => $json_obj->user_name,
				'zipcode' => $json_obj->zipcode,
				'password' => $json_obj->password,
				'email' => $json_obj->email,
				'mobile_no' => $json_obj->mobile_no,
				'zipcode' => $json_obj->zipcode,
				'created_by' => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result = $this->users_model->update_user($data, $user_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'User Updated Successfully';
			} else {
				$errors = 'User Not Updated Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function verify_otp()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
			$user_otp = $json_obj->otp;
			$check_email = $this->users_model->check_email($json_obj->email);
			if(count($check_email) > 0){
				$data = array(
					'is_verify' => '1'
				);
				$verifyotp_result = $this->otpverification_model->verify_otp($data, $check_email[0]->user_id, $user_otp);
				$data = array(
					'user_name' => $json_obj->email,
					'user_role_id' => $json_obj->user_role_id
				);

				$result = $this->users_model->check_user($data);
				
				//generate and save token
					

				$accessToken = bin2hex(random_bytes(32));
				$refreshToken = bin2hex(random_bytes(32));
				$utoken_data = array(
					'user_id' => $result[0]->user_id,
					'access_token' => $accessToken,
					'refresh_token' => $refreshToken,
					'access_expiry' => date("Y-m-d H:i:s", strtotime("+24 hours")),
					'refresh_expiry' => date("Y-m-d H:i:s", strtotime("+30 days"))
				);
			
				$usertoken_code = $this->usertoken_model->add_usetoken($utoken_data);
				if ($verifyotp_result) {
					$ArrData['user_data'] = $result;
					$ArrData['access_token'] = $accessToken;
        			$ArrData['refresh_token'] = $refreshToken;
        			$ArrData['expires_in'] = 86400;
					$success_message = 'OTP Verified Successfully';
					send_response_to_api($ArrData, $errors, $success_message);
				} else {
					$errors = 'OTP Not Verified Successfully';
					$ArrError = array('is_successful' => '0', 'error_code' => 401, 'data' => null, 'errors' => $errors);
					$myJSON = json_encode($ArrError);
					header('HTTP/1.1 401 Unauthorized');
					echo $myJSON;
				}
			}else{
				$errors = 'Wrong Mobile Number';
				send_response_to_api($ArrData, $errors, $success_message);
			}
		
			
	}

	public function regenerate_token()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$refreshToken = $json_obj->refresh_token;

    	$record = $this->db->get_where('tbl_users_token', ['refresh_token' => $refreshToken])->row();

		if (!$record || strtotime($record->refresh_expiry) < time()) {
			$errors = 'Refesh Token is Expired';
			send_response_to_api($ArrData, $errors, $success_message);
			return false;
		}

    	$newAccessToken = bin2hex(random_bytes(32));

		$this->db->where('id', $record->id)->update('tbl_users_token', [
			'access_token' => $newAccessToken,
			'access_expiry' => date("Y-m-d H:i:s", strtotime("+24 hours"))
		]);
		
		$ArrData['status'] = true;
        $ArrData['access_token'] = $newAccessToken;
        $ArrData['expires_in'] = 86400;
		$success_message = 'Token generated Successfully';
		send_response_to_api($ArrData, $errors, $success_message);
	}
	public function login()
	{

		//$json_str = json_encode($_POST);
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();

			#check if email is exist or not
			$check_email = $this->users_model->check_email($json_obj->email);
			if (count($check_email) > 0) {
				$data = array(
					'user_name' => $json_obj->email,
					'user_role_id' => $json_obj->user_role_id
				);
				$result = $this->users_model->check_user($data);
				if (count($result) > 0) {
					
					$cartContents = isset($json_obj->cart_data) ? $json_obj->cart_data : '';
					$unescapedCartContents = stripslashes($cartContents);
					$cart_data = json_decode($unescapedCartContents,true);
					
					if(!empty($cart_data)){
						
						unset($cart_data['cart_total'], $cart_data['total_items']);
						
						$query = $this->db->where('customer_id', $result[0]->user_id )->get('tbl_cart_items');

						if($query->num_rows() > 0)
						{
							foreach($query->result_array() as $cart_row){
								
								$product_id = $cart_row['id'];
								$product_name = $cart_row['name'];
								$price = $cart_row['price'];
								$quantity = $cart_row['qty'];
								$image = $cart_row['image'];
								$weight = $cart_row['options_weight'];
								$variant_id = $cart_row['variant_id'];
								$product_slug = $cart_row['options_variant_id'];
								$is_perisible = $cart_row['is_perisible'];
								$product_tax = $cart_row['product_tax'];
								$rowid = $cart_row['row_id'];				
								
								$data = array(
									"id" => $product_id,
									"rowid" => $rowid,
									'customer_id' => $result[0]->user_id,
									"name" => $product_name,
									"image" => $image,
									"price" => $price,
									"qty" => $quantity,
									"product_slug" => $product_slug,
									"is_perisible" => $is_perisible,
									"product_tax" => $product_tax,
									'created_date' => date("Y-m-d"),
									"options" => array(
										"weight" => $weight,
										"variant_id" => $variant_id
									)
								);
								
								$product_id_found = false;
								$quantity_update = false;

								foreach ($cart_data as $items)
								{
									$cart_product_id = $items['id'];
									$cart_variant_id = $items['options']['variant_id'];
									if($cart_product_id==$product_id && $cart_variant_id==$variant_id && !$product_id_found)
									{
										$product_id_found = true;
										$qty = $quantity + $items['qty'];
										$rowid = $items['rowid'];
										$variant_id = $items['options']['variant_id'];
									}
								}

								if(!$product_id_found)
								{
									$this->cart->insert($data);
								}
								else
								{
									$data = array(
										'rowid' => $rowid,
										'qty'   => $qty
									);
									if($quantity_update == true){
										$data['created_date'] = date("Y-m-d");
									}
									$this->cart->update($data);
								}
							}
						}

						
					} else {
						
						
						$cart_total = $cart_data['cart_total'];
						$total_items = $cart_data['total_items'];
						
						unset($cart_data['cart_total'], $cart_data['total_items']);
						foreach($cart_data as $cart_id => $cart_row){

							$options_weight=$cart_row['options']['variant_id'];
							$options_variant_id=$cart_row['options']['weight'];
							$cart_item = array(
								'row_id' => $cart_row['rowid'],
								'customer_id' => $result[0]->user_id,
								'id' => $cart_row['id'],
								'name' => $cart_row['name'],
								'image' => $cart_row['image'],
								'price' => $cart_row['price'],
								'qty' => $cart_row['qty'],
								'product_slug' => $cart_row['product_slug'],
								'is_perisible' => $cart_row['is_perisible'],
								'product_tax' => $cart_row['product_tax'],
								'options_weight' => $options_weight?$options_weight:0,
								'options_variant_id' => $options_variant_id ? $options_variant_id : 0,
							);
							$this->db->insert('tbl_cart_items', $cart_item);
						}
					}
					$gen_otp = rand(1000, 9999);
					$otp_data = array(
						'user_id' => $result[0]->user_id,
						'otp' => $gen_otp,
						'created_datetime' => date('Y-m-d H:i:s'),
						'modified_datetime' => date('Y-m-d H:i:s')
					);
				
					$otp_code = $this->otpverification_model->add_otpcode($otp_data);

					// Remove spaces
					$valid_emil = trim($json_obj->email);
					// Check email
					if (filter_var($valid_emil, FILTER_VALIDATE_EMAIL)) {
						//send email
						$user_rdata = $this->users_model->get_user_by_email($valid_emil);
						if (count($user_rdata) > 0) {
							$user_name = $user_rdata[0]->first_name.' '.$user_rdata[0]->last_name;
							$subject = "Your New Vrajfresh Login OTP";
							$email_content = file_get_contents('templates/user_new_otp.html');
							$email_content = str_replace('##user_name##', $user_name, $email_content);
							$email_content = str_replace('##otp##', $gen_otp, $email_content);
							$email_content = str_replace('##link##', FRONT_URL, $email_content);
						
							if ($valid_emil != '') {
								send_mail($_POST["email"], $subject, $email_content);
								$success_message = 'You got your new OTP.Please check your email';
							} else {
								$errors = "Sorry,Something going wrong";
							}
						}
					}else{

// 				$to = '+12012708378';
// 						try {
//             $client = new \Twilio\Rest\Client($this->sid, $this->token);
// $message = 'THis is test message';
//             $sms = $client->messages->create(
//                 $to,
//                 [
//                     'from' => $this->from,
//                     'body' => $message
//                 ]
//             );
// print_r($sms);exit;
//             return $sms->sid; // success
//         } catch (Exception $e) {
// 			print_r($e);exit;
//             return $e->getMessage(); // error
//         }exit;



// $twilio->verify->v2->services("VAc58339b11abb0c4de5ddeb842c987a77")
//                                    ->verifications
//                                    ->create("+919537234387", "sms");
// echo $gen_otp;
// $check = $twilio->verify->v2->services("VAc58339b11abb0c4de5ddeb842c987a77")
//           ->verificationChecks
//           ->create([
//               "to" => "+919537234387",
//               "code" => $gen_otp
//           ]);
// print_r($check);exit;
						$success_message = 'Login Successfully';
					}

					

					$ArrData['userdata'] = $result;
					$ArrData['gen_otp'] = $gen_otp;
				} else {
					$errors = 'Please enter valid login details.';
				}
			} else {
				$errors = 'Your account is not registered with Vraj Fresh. Please create an account.';
			}

			send_response_to_api($ArrData, $errors, $success_message);

	}

	public function login_deliveryboy()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			$data = array(
				'user_name' => $json_obj->email,
				'password' => $json_obj->password,
				'user_role_id' => $json_obj->user_role_id
			);
			$result = $this->users_model->check_user($data);
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = 'Login Successfully';
			} else {
				$errors = 'Please enter valid login details.';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function delete_user()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);



		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$user_id = $json_obj->user_id;
			$data = array(
				'is_active' => '0',
				'is_deleted' => '1'
			);
			$result = $this->users_model->update_user($data, $user_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'User Deleted Successfully';
			} else {
				$errors = 'User Not Deleted Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function forgot_password()
	{

		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			$data = array(
				'email' => $json_obj->email,
				'user_role_id' => $json_obj->user_role_id
			);
			$user_exist = $this->users_model->user_exist($data);
			if ($user_exist > 0) {
				$result = $this->users_model->get_user_by_email($data);
				if (count($result) > 0) {
					$ArrData = $result;
					$new_password = rand(10000000, 999999999);
					/* Update Password */
					$new_data = array(
						'password' => md5($new_password)
					);
					$this->users_model->update_user($new_data, $result[0]->user_id, 'tbl_users');
					$subject = "Your New Vrajfresh Password";
					$email_content = file_get_contents('templates/user_new_password.html');
					$email_content = str_replace('##user_name##', $result[0]->user_name, $email_content);
					$email_content = str_replace('##new_password##', $new_password, $email_content);
					$email_content = str_replace('##link##', FRONT_URL, $email_content);
					if ($_POST["email"] != '') {
						send_mail($_POST["email"], $subject, $email_content);
						$success_message = 'You got your new password.Please check your email';
					} else {
						$errors = "Sorry,Something going wrong";
					}
				}
			} else {
				$errors = 'No User Found';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function change_password()
	{

		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			$data = array(
				'email' => $json_obj->user_name,
				'user_role_id' => $json_obj->user_role_id
			);
			$user_exist = $this->users_model->user_exist($data);
			if ($user_exist > 0) {
				$result = $this->users_model->get_user_by_email($data);
				if (count($result) > 0) {
					$ArrData = $result;

					/* Update Password */
					$new_data = array(
						'password' => md5($json_obj->new_password)
					);
					$this->users_model->update_user($new_data, $result[0]->user_id, 'tbl_users');

					$success_message = 'Your account password has been changed successfully.';
				}
			} else {
				$errors = 'Please enter valid old password.';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_user_address()
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


		$result = $errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				"user_id" => $json_obj->user_id,
				//"user_role_id" => $json_obj->user_role_id
			);


			$result = $this->users_model->get_user_address($data);

			if (count($result) > 0 && $result != "" && $result != null) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Address Found';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function edit_user_address()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str,true);

		$errors = $success_message = '';

		$json_str = $this->input->raw_input_stream;		
		$json_obj = json_decode($json_str);
		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}

		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'display_name' => $json_obj->display_name,
				'phone' => $json_obj->phone,
				'address' => $json_obj->address,
				'address2' => $json_obj->address2,
				'city' => $json_obj->city,
				'state' => $json_obj->state,
				'zip' => $json_obj->zip,
				'shipping_street_address' => $json_obj->shipping_street_address,
				'shipping_apartment' => $json_obj->shipping_apartment,
				'shipping_city' => $json_obj->shipping_city,
				'shipping_state' => $json_obj->shipping_state,
				'shipping_zip_code' => $json_obj->shipping_zip_code,
				'shipping_phone' => $json_obj->shipping_phone,

			);
			$user_id = $json_obj->user_id;

			$result = $this->users_model->update_user($data, $user_id, 'tbl_users');
			if ($result) {
				$success_message = 'Your address detail has been updated successfully';
			} else {
				$errors = 'Something went wrong.Your detail is not updated';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function edit_user()
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
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'display_name' => $json_obj->display_name,
				'first_name' => $json_obj->first_name,
				'last_name' => $json_obj->last_name,
				'phone' => $json_obj->mobile_no
			);
			if($json_obj->email!='')
			{
				$data['email'] = $json_obj->email;
			}
			$user_id = $json_obj->user_id;

			$result = $this->users_model->update_user($data, $user_id, 'tbl_users');

			if ($result) {
				$success_message = 'Your profile has been updated successfully';
			} else {
				$errors = 'Something went wrong. Please try again.';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function contact_mail()
	{
		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			$data = array(
				'to_email' => $json_obj->to_email,
				'email' => $json_obj->email,
				'first_name' => $json_obj->first_name,
				'last_name' => $json_obj->last_name,
				'phone' => $json_obj->phone,
				'message' => $json_obj->message,

			);


			$subject = "New Enquiry Received";
			$email_content = file_get_contents('templates/contact_mail.html');
			$email_content = str_replace('##first_name##', $data['first_name'], $email_content);
			$email_content = str_replace('##last_name##', $data['last_name'], $email_content);
			$email_content = str_replace('##phone##', $data['phone'], $email_content);
			$email_content = str_replace('##message##', $data['message'], $email_content);

			if (send_contact_mail($data["to_email"], $data["email"], $subject, $email_content)) {

				$success_message = 'Mail Sent successfully';
			} else {
				$error = 'Mail Not Sent successfully';
			}



			//send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function user_activate()
	{
		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$guid = $json_obj->guid;
		$errors = $success_message = '';
		$ArrData = array();
		$data = array(
			'is_active' => '1'
		);
		$result = $this->users_model->update_user_by_guid($data, $guid);
		if ($result) {
			$success_message = 'Activate successfully';
		} else {
			$error = 'User Not Activate successfully';
		}
		send_response_to_api($ArrData, $errors, $success_message);
	}
}