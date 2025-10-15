<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_banners extends CI_Controller
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
		$this->load->model('banners_model');
	}


	public function get_banners()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'is_active' => $json_obj->is_active_only,
				'banner_type' => $json_obj->banner_type,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
				'sort_column' => $json_obj->sort_column,
				'sort_order' => $json_obj->sort_order
			);
			//var_dump($json_obj->search_keyword);exit;
			if ($json_obj->search_keyword == "") {

				$result_val = $this->banners_model->get_banners($data);

				for ($i = 0; $i < count($result_val); $i++) {
					//$result=array();
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "banner_image") {
							$banner_result[$key] = FILE_UPLOAD_PATH . 'banner/' . $value;
						} else {
							$banner_result[$key] = $value;
						}

					}
					$result[] = $banner_result;
				}



			} else {
				$result_val = $this->banners_model->get_banners_by_search($data, $json_obj->search_keyword);
				for ($i = 0; $i < count($result_val); $i++) {
					//$result=array();
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "banner_image") {
							$banner_result[$key] = FILE_UPLOAD_PATH . 'banner/' . $value;
						} else {
							$banner_result[$key] = $value;
						}

					}
					$result[] = $banner_result;
				}

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
	public function add_banner()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'banner_image' => $json_obj->banner_image,
				'banner_text' => $json_obj->banner_text,
				'banner_type' => $json_obj->banner_type,
				'banner_link' => $json_obj->banner_link,
				'banner_srno' => $json_obj->banner_srno,
				'created_by' => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result = $this->banners_model->add_banner($data);
			$ArrData = $result;
			if ($result) {
				$success_message = 'Banner added successfully';
			} else {
				$errors = 'Banner Not Added Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_banner_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'banner_id' => $json_obj->banner_id
			);
			$result = $this->banners_model->get_banner_by_id($data);
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
	public function update_banner()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$banner_id = $json_obj->banner_id;
			$data = array(
				'banner_image' => $json_obj->banner_image,
				'banner_text' => $json_obj->banner_text,
				'banner_type' => $json_obj->banner_type,
				'banner_link' => $json_obj->banner_link,
				'banner_srno' => $json_obj->banner_srno,
				'modified_by' => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result = $this->banners_model->update_banner($data, $banner_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Banner Updated Successfully';
			} else {
				$errors = 'Banner Not Updated Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_banner()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$banner_id = $json_obj->banner_id;
			$data = array(
				'is_active' => '0',
				'is_deleted' => '1'
			);
			$result = $this->banners_model->update_banner($data, $banner_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Banner Deleted Successfully';
			} else {
				$errors = 'Banner Not Deleted Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
}