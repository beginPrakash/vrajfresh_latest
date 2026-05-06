<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_usernotifications extends CI_Controller
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
		$this->load->model('usernotification_model');
		$this->load->model('products_model');
		error_reporting(0);
	}

	public function create_notification()
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
		if (check_oauth_key($oauth_key)) {
    		$result = $this->db->get_where('tbl_users_token', ['access_token' => $oauth_key])->row();
            $user_id = $result->user_id;
            $product_id = $json_obj->product_id;
            $is_like = $json_obj->is_like;
    
    		$errors = $success_message = '';
    		$notification_data = array();
    		$notification_data = array(
    
                    "user_id" => $user_id,
    
                    "product_id" => $product_id,
    
                    "is_like" => $is_like
            );
    
			$result = $this->usernotification_model->add_usernotification($notification_data);
			$ArrData['id'] = $result;
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_notification_by_user_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		
		// Receive values from POST
		$page  = $json_obj->page;
		$limit = $json_obj->limit;

		// Default values
		$page  = (!empty($page)) ? (int)$page : 1;
		$limit = (!empty($limit)) ? (int)$limit : 10;

		$offset = ($page - 1) * $limit;

		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}
		$errors = $success_message = '';
		$ArrData = array();
		$result = [];
		if (check_oauth_key($oauth_key)) {
			$user_result = $this->db->get_where('tbl_users_token', ['access_token' => $oauth_key])->row();
            $user_id = $user_result->user_id;
			$total_products_c = $this->usernotification_model->get_notification_by_user_id_count($user_id);
			$total = count($total_products_c);

			$temp_result = $this->usernotification_model->get_notification_by_user_id($user_id,$limit, $offset);
			
			if (is_array($temp_result) && count($temp_result) > 0 && $temp_result != null) {
				$ArrData = $temp_result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

}