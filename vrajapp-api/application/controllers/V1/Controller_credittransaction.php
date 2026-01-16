<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
class Controller_credittransaction extends CI_Controller
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
		$this->load->model('credittransaction_model');
		$this->load->model('Master_model', 'master');
		//error_reporting(0);
	}

	public function get_credit_transaction_by_user_id()
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
		if (check_oauth_key($oauth_key)) {
			$user_id = $json_obj->user_id;
			$total_products_c = $this->credittransaction_model->get_crtran_by_user_id_count($user_id);
			$total = count($total_products_c);
			$result = $this->credittransaction_model->get_crtran_by_user_id($user_id,$limit, $offset);
            $total_cr_bal = $this->credittransaction_model->get_credit_sum($user_id);
			$total_bal = ($total_cr_bal['amount'] > 0) ? $total_cr_bal['amount'] : 0;
			
			if ($result) {
				$ArrData['trans_data'] = $result;
				$ArrData['total_credit'] = number_format($total_bal,2);
				$ArrData['current_page'] = $page;
				$ArrData['per_page'] = $limit;
				$ArrData['total'] = $total;
				$ArrData['total_pages'] = ceil($total / $limit);
				$success_message = '';
			} else {
				$errors = 'No Transaction Found';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

}