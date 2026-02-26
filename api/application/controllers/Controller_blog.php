<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_blog extends CI_Controller
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
		$this->load->model('blogs_model');
		error_reporting(0);
	}
	public function get_blogs()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'is_active' => $json_obj->is_active_only,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);

				$result_val = $this->blogs_model->get_blogs_by_search($data);
				for ($i = 0; $i < count($result_val); $i++) {
					//$result=array();
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "blog_image") {
							$blog_result[$key] = FILE_UPLOAD_PATH . 'blog/' . $value;
						} else {
							$blog_result[$key] = $value;
						}

					}
					$result[] = $blog_result;
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

	public function get_blog_by_id()
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
			$result = $this->blogs_model->get_brand_by_id($data);
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

	public function get_brand_product_detail()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$blog_result = "";
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'brand_slug' => $json_obj->brand_slug
			);
			$blog_result = $this->blogs_model->get_brand_by_slug($data);
			$brand_id = $blog_result[0]->brand_id;
			$temp_result = $this->blogs_model->get_product_by_brand_id($brand_id);
			//echo $this->db->last_query();exit;
			$result["brand"] = $blog_result[0];
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
                    // usort($tempArray, function($a, $b) {
					// 	return $a['size'] <=> $b['size'];
					// });

					$unique = array_map("unserialize", array_unique(array_map("serialize", $tempArray)));
					usort($unique, function($a, $b) {
						return $a['price'] - $b['price']; // Ascending sort by 'price'
					});

					$ArrFinal[$i] = $arr;

					$ArrFinal[$i]->product_size = $unique;

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