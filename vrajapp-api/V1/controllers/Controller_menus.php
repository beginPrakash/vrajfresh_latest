<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_Menus extends CI_Controller
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
		$this->load->model('menus_model');
		//error_reporting(0);
	}
	public function get_menu()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		//	$result = array();
		if (check_oauth_key($oauth_key)) {
			try {
				$data = array(
					'menu_id' => $json_obj->menu_id
				);
				//var_dump($json_obj->search_keyword);exit;


				$temp_result = $this->menus_model->get_menus($data);
				$ArrFinal = array();
				$ArrFinal['menus'] = $temp_result;
				$ArrFinal = array();
				$i = 0;
				$prev_menu_id = 0;
				foreach ($temp_result as $arr) {
					if ($arr->parent_menu_item_id > 0) {
						if ($prev_menu_id != $arr->parent_menu_item_id) {

						//	$ArrFinal[$i] = $arr;

							$tempArray = array();

							foreach ($temp_result as $arr1) {

								if ($arr->parent_menu_item_id == $arr1->parent_menu_item_id) {
									$t = array();
									if ($arr1->menu_item_id > 0) {
										$t['child_menu_title'] = $arr1->menu_title;
										$t['child_menu_link'] = $arr1->menu_link;
										$t['child_menu_id'] = $arr1->menu_item_id;
									}
									$tempArray[] = $t;
								}
							}

							$ArrFinal[$i] = $arr;

							$ArrFinal[$i]->child_menus = $tempArray;

							$i++;
						 }

						$prev_menu_id = $arr->parent_menu_item_id;
					} else {
						$ArrFinal[] = $arr;
					}
				}
				$menu_result = $ArrFinal;


				if (count($menu_result) > 0) {
					$ArrData = $ArrFinal;
					$success_message = '';
				} else {
					$errors = 'No Data Available';
				}
			} catch (Exception $e) {
				$ArrData = "There is problem";
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
}
