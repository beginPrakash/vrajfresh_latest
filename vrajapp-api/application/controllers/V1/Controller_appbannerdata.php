<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_appbannerdata extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Headers: content-type,Content-Type,X-Custom-Header, Upgrade-Insecure-Requests,Accept,x-requested-with");
		header('Content-Type: application/json');
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 60');
		header('Access-Control-Allow-Headers: AccountKey,x-requested-with, Content-Type, content-type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2');
		header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		$this->load->model('appbannerdata_model');
		$this->load->model('appconfiguration_model');
		$this->load->model('Master_model', 'master');
	}

	public function get_app_banner_data()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
	
			
			$result_val = $this->appbannerdata_model->getBannerdata();
			$app_config_data = $this->appbannerdata_model->getConfigurationColor();
            $result['header_color'] = $app_config_data;
			$app_config_data_pro = $this->appbannerdata_model->getConfigurationColorPro();
            $result['product_header_color'] = $app_config_data_pro;
			for ($i = 0; $i < count($result_val); $i++) {
			
				//$result=array();
				foreach ($result_val[$i] as $key => $value) {
					if ($key == "banner_image" || $key == "category_logo") {
						$banner_result[$key] = FILE_UPLOAD_PATH . 'bannerdata/' . $value;
					} else {
						$banner_result[$key] = $value;
					}
					
				}
				$result['cat_data'][] = $banner_result;
			}

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		
	}

	public function get_app_banner_category_data_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$category_id = $json_obj->category_id;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
	
			
			$result_val = $this->appbannerdata_model->getBannerdatabyid($category_id);
			for ($i = 0; $i < count($result_val); $i++) {
			
				//$result=array();
				foreach ($result_val[$i] as $key => $value) {
					if ($key == "product_image") {
						$banner_result[$key] = FILE_UPLOAD_PATH . 'bannerdata/' . $value;
					} else {
						$banner_result[$key] = $value;
					}
					
				}
				$result[] = $banner_result;
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
