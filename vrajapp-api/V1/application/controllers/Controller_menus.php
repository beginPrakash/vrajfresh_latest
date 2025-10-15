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
		$this->load->model('zipcodes_model');

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

					'menu_id' => $json_obj->menu_id,
					'zipcode' => $json_obj->zipcode
				);

				//var_dump($json_obj->search_keyword);exit;


				if($json_obj->zipcode != ""){
					$result = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
					if (count($result) > 0) {
						
						$data['is_perisible_zipcode'] = $result[0]->can_deliver_perishable_products;
						$data['is_liker_zipcode'] = $result[0]->can_deliver_liker_products;
						$data['is_cook_food_zipcode'] = $result[0]->can_deliver_cook_food_products;

						$temp_result = $this->menus_model->get_menus($data);

					} else {
						$temp_result = $this->menus_model->get_menus($data);
					}
				
				} else {
					$temp_result = $this->menus_model->get_menus($data);
				}

				



				// $ArrFinal = array();



				// $ArrFinal['menus'] = $temp_result;



				$ArrFinal = array();



				$i = 0;



				$prev_menu_id = 0;



				foreach ($temp_result as $arr) {

					if ($arr->parent_menu_item_id == 0) {

						// if ($prev_menu_id != $arr->parent_menu_item_id) {



							//	$ArrFinal[$i] = $arr;

							$tempArrary = array();

							$tempChildRecordsArray = array();



							foreach ($temp_result as $arr1) {



								if ($arr->menu_item_id == $arr1->parent_menu_item_id) {

									$t = array();

									if ($arr1->menu_item_id > 0) {

										$t['child_menu_title'] = $arr1->menu_title;

										$t['child_menu_link'] = $arr1->menu_link;

										$t['child_menu_id'] = $arr1->menu_item_id;
										
										$t['child_category_name'] = $arr1->category_name;
										
										$t['child_category_slug'] = $arr1->category_slug;

									}

									$tempChildRecordsArray[] = $t;

								}

							}



							$tempArrary = $arr;

							if(count($tempChildRecordsArray) >0)

								$tempArrary->child_menus = $tempChildRecordsArray;



							$ArrFinal[] = $tempArrary;



							// $i++;

						// }



						// $prev_menu_id = $arr->parent_menu_item_id;

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