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
		//echo "<pre>";print_r($_POST);exit;
		//$json_str = file_get_contents('php://input');
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
				'first_name' => $json_obj->user,
				'display_name' => $json_obj->user,
				'password' => md5($json_obj->password),
				'email' => $json_obj->email,
				// 'mobile_no' => $json_obj->mobile_no,
				// 'zipcode' => $json_obj->zipcode,
				'guid' => $guid,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => 0
			);
			$user_exist=$this->users_model->user_exist($user_data);
			if($user_exist > 0)
			{
				$errors = 'Email Already Exist.Please try another Email';
			}
			else
			{
				$result = $this->users_model->add_user($user_data, 'tbl_users');
			
				$ArrData = $result;
				if ($result) {
	
					$subject = "Vrajfresh Registration Successfully";
					$email_content = file_get_contents('templates/user_registration.html');
					$email_content = str_replace('##user_name##', $_POST["user_name"], $email_content);
					$email_content = str_replace('##link##', FRONT_URL . 'user-activate/' . $guid, $email_content);
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
			$user_id   = $json_obj->user_id;
			$data = array(
				'user_role_id' => $json_obj->user_role_id,
				'user_name' => $json_obj->user_name,
				'zipcode' => $json_obj->zipcode,
				'password' => $json_obj->password,
				'email' => $json_obj->email,
				'mobile_no' => $json_obj->mobile_no,
				'zipcode' => $json_obj->zipcode,
				'created_by'   => $json_obj->created_by,
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
			$check_email = $this->users_model->check_email($json_obj->email);
			if (count($check_email) > 0) {
			$data = array(
				'user_name'   => $json_obj->email,
				'password'    => $json_obj->password,
				'user_role_id' => $json_obj->user_role_id
			);
			$result = $this->users_model->check_user($data);
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = 'Login Successfully';
			} else {
				$errors = 'Please enter valid login details.';
			}else {
				$errors = 'Your account is not register. Please register first.';
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
				'user_name'   => $json_obj->email,
				'password'    => $json_obj->password,
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
			$user_id   = $json_obj->user_id;
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
		echo "reached 1";
		$json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'email'   => $json_obj->email,
				'user_role_id' => $json_obj->user_role_id
			);
			$user_exist = $this->users_model->user_exist($data);
			if ($user_exist > 0) {
				$result = $this->users_model->get_user_by_email($data);
				if (count($result) > 0) {
					$ArrData = $result;
					$new_password = rand(1000, 9999);
					$subject = "Your New Vrajfresh Password";
					$email_content = file_get_contents('templates/user_new_password.html');
					$email_content = str_replace('##user_name##', $result[0]->user_name, $email_content);
					$email_content = str_replace('##new_password##', $new_password, $email_content);
					$email_content = str_replace('##link##', FRONT_URL, $email_content);
					/* Update Password */
					$new_data = array(
						'password' => md5($new_password)
					);
					$this->users_model->update_user($new_data, $result[0]->user_id, 'tbl_users');
					if(send_mail($_POST["email"], $subject, $email_content))
					{
						$success_message = 'You got your new password.Please check your email';
					}
					else
					{
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
				'email'   => $json_obj->user_email,
				'user_role_id' => $json_obj->user_role_id
			);
			$user_exist = $this->users_model->user_exist($data);
			if ($user_exist > 0) {
				$result = $this->users_model->get_user_by_email($data);
				if (count($result) > 0) {
					$ArrData = $result;
					/* Update Password */
					$new_data = array(
						'password' => md5($json_obj->password)
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
				'display_name' => $json_obj->user_name,
				'phone' => $json_obj->phone,
				'address' => $json_obj->address,
			);
			$user_id=$json_obj->user_id;
			$result=$this->users_model->update_user($data,$user_id,'tbl_users');
			if($result)
			{
				$success_message = 'Your address detail has bee updated successfully';
			}
			else
			{
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
				'last_name' => $json_obj->last_name,
				'email' => $json_obj->email
			);
			$user_id=$json_obj->user_id;
			$result=$this->users_model->update_user($data,$user_id,'tbl_users');
			if($result)
			{
				$success_message = 'Your profile has been updated successfully';
			}
			else
			{
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
				'email'   => $json_obj->email,
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
		$guid= $json_obj->guid;
		$errors = $success_message = '';
		$ArrData = array();
		$data=array(
			'is_active'=>'1'
		);
		$result= $this->users_model->update_user_by_guid($data,$guid);
		if($result)
		{
			$success_message = 'Activate successfully';
			
		}
		else
		{
			$error = 'User Not Activate successfully';
		}
		send_response_to_api($ArrData,$errors,$success_message);
	}
}
