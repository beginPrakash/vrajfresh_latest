<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_state extends CI_Controller
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
		$this->load->model('states_model');
	}
	public function get_states()
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

				$result = $this->states_model->get_states($data);

			} else {
				$result = $this->states_model->get_states_by_search($data, $json_obj->search_keyword);

			}
			// echo $this->db->last_query();exit;
			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}

	}
	public function add_state()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'state' => $json_obj->state,
				'created_by' => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result = $this->states_model->add_state($data);
			$ArrData = $result;
			if ($result) {
				$success_message = 'State added successfully';
			} else {
				$errors = 'State Not Added Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_state_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'state_id' => $json_obj->state_id
			);
			$result = $this->states_model->get_state_by_id($data);
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
	public function update_state()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$state_id = $json_obj->state_id;
			$data = array(
				'state' => $json_obj->state,
				'modified_by' => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result = $this->states_model->update_state($data, $state_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'State Updated Successfully';
			} else {
				$errors = 'State Not Updated Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_state()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$state_id = $json_obj->state_id;
			$data = array(
				'is_active' => '0',
				'is_deleted' => '1'
			);
			$result = $this->states_model->update_state($data, $state_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'State Deleted Successfully';
			} else {
				$errors = 'State Not Deleted Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_state_by_country_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'geo_id' => $json_obj->geo_id
			);
			$result = $this->states_model->get_state_by_country_id($data['geo_id']);
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
}