<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_brands extends CI_Controller
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
		$this->load->model('brands_model');
		error_reporting(0);
	}
	public function get_brands()
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
				'is_home_display' => $json_obj->is_home_display,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
				'sort_column' => $json_obj->sort_column,
				'sort_order' => $json_obj->sort_order
			);
			//var_dump($json_obj->search_keyword);exit;
			if ($json_obj->search_keyword == "") {

				$result_val = $this->brands_model->get_brands($data);

				for ($i = 0; $i < count($result_val); $i++) {
					//$result=array();
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "brand_image") {
							$brand_result[$key] = FILE_UPLOAD_PATH . 'brand/' . $value;
						} else {
							$brand_result[$key] = $value;
						}

					}
					$result[] = $brand_result;
				}


			} else {
				$result_val = $this->brands_model->get_brands_by_search($data, $json_obj->search_keyword);
				for ($i = 0; $i < count($result_val); $i++) {
					//$result=array();
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "brand_image") {
							$brand_result[$key] = FILE_UPLOAD_PATH . 'brand/' . $value;
						} else {
							$brand_result[$key] = $value;
						}

					}
					$result[] = $brand_result;
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
	public function add_brand()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'brand_name' => $json_obj->brand_name,
				'brand_image' => $json_obj->brand_image,
				'is_home_display' => $json_obj->is_home_display,
				'created_by' => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result = $this->brands_model->add_brand($data);
			$ArrData = $result;
			if ($result) {
				$success_message = 'Brand added successfully';
			} else {
				$errors = 'Brand Not Added Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_brand_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'brand_id' => $json_obj->brand_id
			);
			$result = $this->brands_model->get_brand_by_id($data);
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
	public function update_brand()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$brand_id = $json_obj->brand_id;
			$data = array(
				'brand_image' => $json_obj->brand_image,
				'brand_name' => $json_obj->brand_name,
				'is_home_display' => $json_obj->is_home_display,
				'modified_by' => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result = $this->brands_model->update_brand($data, $brand_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Brand Updated Successfully';
			} else {
				$errors = 'Brand Not Updated Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_brand()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$brand_id = $json_obj->brand_id;
			$data = array(
				'is_active' => '0',
				'is_deleted' => '1'
			);
			$result = $this->brands_model->update_brand($data, $brand_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Brand Deleted Successfully';
			} else {
				$errors = 'Brand Not Deleted Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_brand_product_detail()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$brand_result = "";
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'brand_slug' => $json_obj->brand_slug
			);
			$brand_result = $this->brands_model->get_brand_by_slug($data);
			$brand_id = $brand_result[0]->brand_id;
			$temp_result = $this->brands_model->get_product_by_brand_id($brand_id);
			//echo $this->db->last_query();exit;
			$result["brand"] = $brand_result[0];
			$ArrFinal = array();
			$i = 0;
			$prev_product_id = 0;
			foreach ($temp_result as $arr) {

				if ($prev_product_id != $arr->product_id) {

					$ArrFinal[$i] = $arr;

					$tempArray = array();

					foreach ($temp_result as $arr1) {

						if ($arr->product_id == $arr1->product_id) {
							$t = array();
							if ($arr1->id > 0) {
								$t['size'] = $arr1->product_variant_size;
								$t['price'] = $arr1->variant_price;
								$t['variant_id'] = $arr1->id;
							}
							$tempArray[] = $t;
						}
					}
                    usort($tempArray, function($a, $b) {
						return $a['size'] <=> $b['size'];
					});
					$ArrFinal[$i] = $arr;

					$ArrFinal[$i]->product_size = $tempArray;

					$i++;

				}

				$prev_product_id = $arr->product_id;
			}
			$product_result = $ArrFinal;

			for ($i = 0; $i < count($product_result); $i++) {
				//$result=array();
				foreach ($product_result[$i] as $key => $value) {
					if ($key == "product_image") {
						$products[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
					} else {
						$products[$key] = $value;
					}

				}
				$result['products'][] = $products;
			}

			//$filter_result=$this->categories_model->get_category_filter($product_result[0]->product_id);
			foreach ($product_result as $products) {
				$product_id[] = $products->product_id;
			}
			$result['product_id'] = $product_id;
			//$ArrData = $result;
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