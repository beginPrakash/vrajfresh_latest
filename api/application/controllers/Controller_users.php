<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
		$this->load->model('contactus_model');
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

		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$guid = GUID();

			$user_data = array(
				'user_role_id' => $json_obj->user_role_id,
				'user_name' => $json_obj->email,
				'first_name' => $json_obj->first_name,
				'last_name' => $json_obj->last_name,
				'display_name' => $json_obj->first_name.' '.$json_obj->last_name,
				'password' => md5($json_obj->password),
				'email' => $json_obj->email,
				'phone' => $json_obj->phone_number,
				'mobile_no' => $json_obj->phone_number,
				// 'zipcode' => $json_obj->zipcode,
				'guid' => $guid,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => 1
			);
			$user_exist = $this->users_model->user_exist($user_data);
			if ($user_exist > 0) {
				$errors = 'Email Already Exist.Please try another Email';
			} else {
				
				$user_id = $this->users_model->add_user($user_data, 'tbl_users');
				if ($user_id) {

					$conddata['user_id'] = $user_id;
					$result = $this->users_model->get_user_by_id($conddata);
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
	}
	public function get_user_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
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
	public function login()
	{

		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			#check if email is exist or not
			$check_email = $this->users_model->check_email($json_obj->email);
			if (count($check_email) > 0) {
				$data = array(
					'user_name' => $json_obj->email,
					'password' => $json_obj->password,
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
					$ArrData = $result;
					
					if($json_obj->prev_url == 'cart-detail' || $json_obj->prev_f_url == 'checkout' || $json_obj->prev_f_url == 'my-address' || $json_obj->prev_f_url == 'my-orders' || $json_obj->prev_f_url == 'my-account' || $json_obj->prev_f_url == 'change-password'){
						$prev_url = $json_obj->prev_f_url;
					}else{
						$prev_url = '';
					}
					$ArrData['prev_url'] = $prev_url;
					$success_message = 'Login Successfully';
				} else {
					$errors = 'Please enter valid login details.';
				}
			} else {
				$errors = 'Your account is not registered with Vraj Fresh. Please create an account.';
			}

			send_response_to_api($ArrData, $errors, $success_message);
		}
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



		$oauth_key = $json_obj->oauth_key;
		$result = $errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				"user_id" => $json_obj->user_id,
				"user_role_id" => $json_obj->user_role_id
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
		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
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
		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'display_name' => $json_obj->display_name,
				'first_name' => $json_obj->first_name,
				'last_name' => $json_obj->last_name
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
				'email' => $json_obj->user_email,
				'first_name' => $json_obj->first_name,
				'last_name' => $json_obj->last_name,
				'phone_no' => $json_obj->mobile_no,
				'description' => $json_obj->description,
				'created_datetime' => date('Y-m-d H:i:s'),
				'modified_datetime' => date('Y-m-d H:i:s')

			);

			$contact_id = $this->contactus_model->add_contact($data, 'tbl_contactus');
			if ($contact_id) {
				$ArrData = [];

				$subject = "Contact Us";
				$email_content = file_get_contents('templates/contact_mail.html');
				$email_content = str_replace('##first_name##', $data['first_name'], $email_content);
				$email_content = str_replace('##last_name##', $data['last_name'], $email_content);
				$email_content = str_replace('##phone##', $data['phone_no'], $email_content);
				$email_content = str_replace('##message##', $data['description'], $email_content);

				send_mail($json_obj->user_email, $subject, $email_content);
				$success_message = 'Form Submitted Successfully.';
			} else {
				$errors = 'Form Not Submitted!';
			}

			send_response_to_api($ArrData,$errors,$success_message);
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