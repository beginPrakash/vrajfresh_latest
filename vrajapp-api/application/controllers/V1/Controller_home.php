<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_home extends CI_Controller
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
		$this->load->model('home_model');
		$this->load->model('products_model');
		$this->load->model('zipcodes_model');
		$this->load->model('appconfiguration_model');
		$this->load->model('Master_model', 'master');
	}

	public function get_top_banner()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		if (check_oauth_key($oauth_key)) {
			
			$result_val = $this->home_model->get_top_banners_data();

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

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_featured_category()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		$title = "";

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_featured_category_model($data);
			$title = $this->home_model->get_title_by_id('f_categories_title');
			for ($i = 0; $i < count($result_val); $i++) {
				$banner_result=array();
				foreach ($result_val[$i] as $key => $value) {

					if ($key == "cat_image") {
						$banner_result[$key] = FILE_UPLOAD_PATH . 'feature_categories/' . $value;
					}elseif ($key == "cat_slug") {
						$cslug = substr($result_val[$i]->cat_link, strrpos($result_val[$i]->cat_link, '/') + 1);
						$banner_result[$key] = $cslug;
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
			send_response_to_api_with_extra_parameters($ArrData, array('section_title' => $title), $errors, $success_message); 
	}

	public function get_stockup_your_frozen()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		$title = "";

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_stockup_your_frozen_model($data);
			$title = $this->home_model->get_title_by_id('stockup_title');
			for ($i = 0; $i < count($result_val); $i++) {
				$banner_result=array();
				foreach ($result_val[$i] as $key => $value) {
					if ($key == "stockup_image") {
						$banner_result[$key] = FILE_UPLOAD_PATH . 'stockup/' . $value;
					}elseif ($key == "stockup_slug") {
						$cslug = substr($result_val[$i]->stockup_link, strrpos($result_val[$i]->stockup_link, '/') + 1);
						$banner_result[$key] = $cslug;
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
			send_response_to_api_with_extra_parameters($ArrData, array('section_title' => $title), $errors, $success_message);
	}

	public function get_refill_pantry()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_refill_pantry_model($data);
			$title = $this->home_model->get_title_by_id('refill_title');
			for ($i = 0; $i < count($result_val); $i++) {
				$banner_result=array();
				foreach ($result_val[$i] as $key => $value) {
					if ($key == "pantry_image") {
						$banner_result[$key] = FILE_UPLOAD_PATH . 'pantry/' . $value;
					}elseif ($key == "pantry_slug") {
						$cslug = substr($result_val[$i]->pantry_link, strrpos($result_val[$i]->pantry_link, '/') + 1);
						$banner_result[$key] = $cslug;
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
			send_response_to_api_with_extra_parameters($ArrData, array('section_title' => $title), $errors, $success_message);
	}

	public function get_home_product_slider()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();

		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}
		$w_productlist = '';
		if (!empty($oauth_key)) {
    		$token_result = $this->db->get_where('tbl_users_token', ['access_token' => $oauth_key])->row();
            $suser_id = $token_result->user_id ?? '';
			$w_productlist = $this->home_model->get_wishlist_product_by_user($suser_id);

		}	
		
			$get_home_product_slider_data = $this->home_model->get_home_product_slider();
			if(!empty($get_home_product_slider_data)){
				foreach($get_home_product_slider_data as $get_home_product_slider_row){
					$slider_data=[
						'home_product_slider_id'=>$get_home_product_slider_row->home_product_slider_id,
						'title'=>$get_home_product_slider_row->title,
						'slug'=>$get_home_product_slider_row->slug,
						'display_order'=>$get_home_product_slider_row->display_order,
						'created_datetime'=>$get_home_product_slider_row->created_datetime,
					];

					$product_ids=[];
					$get_home_product_slider_item_data = $this->home_model->get_home_product_slider_item($get_home_product_slider_row->home_product_slider_id);
					for ($i = 0; $i < count($get_home_product_slider_item_data); $i++) {
						$product_ids[] =$get_home_product_slider_item_data[$i]->product_id;
					}
					
					$product_data['product_id'] = implode(',',$product_ids);
					$temp_result = $this->products_model->get_special_products($product_data);

					$ArrFinal = array();
					$i = 0;
					$prev_product_id = 0;
					foreach ($temp_result as $arr) {
						if ($prev_product_id != $arr->product_id) {
							$ArrFinal[$i] = $arr;
							$tempArray = array();
							$t = array();

							foreach ($temp_result as $arr1) {
								if ($arr->product_id == $arr1->product_id)
									//$tempArray[$arr1->product_variant_size] = $arr1->variant_price;
									
									if(!empty($arr1->product_variant_size)):
										
										$t['size'] = $arr1->product_variant_size;
										$t['price'] = $arr1->variant_price;
										$t['product_variant_id'] = $arr1->product_variant_id;
										$t['is_out_of_stock'] = $arr1->varaint_is_out_of_stock;
										$tempArray[] = $t;
									endif;
									
									
								}

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
					$result_val = $ArrFinal;

					$product_result_data=[];
					
					for ($i = 0; $i < count($result_val); $i++) {

						foreach ($result_val[$i] as $key => $value) {
							if ($key == "product_id" && !empty($w_productlist)) {
								$is_like = in_array($value, explode(',', $w_productlist)) ? 1 : 0;
							}
							$product_result['is_like'] = $is_like ?? 0;
							if ($key == "image" || $key == "product_image") {
								$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
							} else {
								$product_result[$key] = $value;
							}

						}
						
						// $result[] = $product_result;
						$product_result_data[]=$product_result;
					}
					
					$slider_data['product_slider_items']=$product_result_data;
					$result[]=$slider_data;
				}
			}
			

			$ArrData["product_slider_data"] = $result;

			if (count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = '';
			}
			send_response_to_api($ArrData, $errors, $success_message);
	}
	/*
	public function get_new_savings()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		if (check_oauth_key($oauth_key)) {
			
			$new_savings_result_val = $this->home_model->get_new_savings_model();
			$product_ids=[];
			for ($i = 0; $i < count($new_savings_result_val); $i++) {
				$product_ids[] =$new_savings_result_val[$i]->savings_product_id;
			}
			
			$product_data['product_id'] = implode(',',$product_ids);
			$temp_result = $this->products_model->get_special_products($product_data);

			$ArrFinal = array();
			$i = 0;
			$prev_product_id = 0;
			foreach ($temp_result as $arr) {
				if ($prev_product_id != $arr->product_id) {
					$ArrFinal[$i] = $arr;
					$tempArray = array();
					foreach ($temp_result as $arr1) {
						if ($arr->product_id == $arr1->product_id)
							$tempArray[$arr1->product_variant_size] = $arr1->variant_price;
					}
					$ArrFinal[$i] = $arr;
					$ArrFinal[$i]->product_size = $tempArray;
					$i++;
				}
				$prev_product_id = $arr->product_id;
			}
			$result_val = $ArrFinal;

			for ($i = 0; $i < count($result_val); $i++) {

				foreach ($result_val[$i] as $key => $value) {
					if ($key == "image" || $key == "product_image") {
						$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
					} else {
						$product_result[$key] = $value;
					}
				}
				$result[] = $product_result;
			}

			$ArrData["product_detail"] = $result;

			if (count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = '';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_fresh_veg()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		if (check_oauth_key($oauth_key)) {
			
			$fresh_veg_result_val = $this->home_model->get_fresh_veg_model();
			$product_ids=[];
			for ($i = 0; $i < count($fresh_veg_result_val); $i++) {
				$product_ids[] =$fresh_veg_result_val[$i]->fresh_product_id;
			}
			
			$product_data['product_id'] = implode(',',$product_ids);
			$temp_result = $this->products_model->get_special_products($product_data);

			$ArrFinal = array();
			$i = 0;
			$prev_product_id = 0;
			foreach ($temp_result as $arr) {
				if ($prev_product_id != $arr->product_id) {
					$ArrFinal[$i] = $arr;
					$tempArray = array();
					foreach ($temp_result as $arr1) {
						if ($arr->product_id == $arr1->product_id)
							$tempArray[$arr1->product_variant_size] = $arr1->variant_price;
					}
					$ArrFinal[$i] = $arr;
					$ArrFinal[$i]->product_size = $tempArray;
					$i++;
				}
				$prev_product_id = $arr->product_id;
			}
			$result_val = $ArrFinal;

			for ($i = 0; $i < count($result_val); $i++) {

				foreach ($result_val[$i] as $key => $value) {
					if ($key == "image" || $key == "product_image") {
						$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
					} else {
						$product_result[$key] = $value;
					}
				}
				$result[] = $product_result;
			}

			$ArrData["product_detail"] = $result;

			if (count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = '';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_vraj_backery()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		if (check_oauth_key($oauth_key)) {
			
			$vraj_backery_result_val = $this->home_model->get_vraj_backery_model();
			$product_ids=[];
			for ($i = 0; $i < count($vraj_backery_result_val); $i++) {
				$product_ids[] =$vraj_backery_result_val[$i]->bakery_product_id;
			}
			
			$product_data['product_id'] = implode(',',$product_ids);
			$temp_result = $this->products_model->get_special_products($product_data);

			$ArrFinal = array();
			$i = 0;
			$prev_product_id = 0;
			foreach ($temp_result as $arr) {
				if ($prev_product_id != $arr->product_id) {
					$ArrFinal[$i] = $arr;
					$tempArray = array();
					foreach ($temp_result as $arr1) {
						if ($arr->product_id == $arr1->product_id)
							$tempArray[$arr1->product_variant_size] = $arr1->variant_price;
					}
					$ArrFinal[$i] = $arr;
					$ArrFinal[$i]->product_size = $tempArray;
					$i++;
				}
				$prev_product_id = $arr->product_id;
			}
			$result_val = $ArrFinal;

			for ($i = 0; $i < count($result_val); $i++) {

				foreach ($result_val[$i] as $key => $value) {
					if ($key == "image" || $key == "product_image") {
						$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
					} else {
						$product_result[$key] = $value;
					}
				}
				$result[] = $product_result;
			}

			$ArrData["product_detail"] = $result;

			if (count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = '';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_shop_ayurvedic()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		if (check_oauth_key($oauth_key)) {
			
			$shop_ayurvedic_result_val = $this->home_model->get_shop_ayurvedic_model();
			$product_ids=[];
			for ($i = 0; $i < count($shop_ayurvedic_result_val); $i++) {
				$product_ids[] =$shop_ayurvedic_result_val[$i]->shop_product_id;
			}
			
			$product_data['product_id'] = implode(',',$product_ids);
			$temp_result = $this->products_model->get_special_products($product_data);

			$ArrFinal = array();
			$i = 0;
			$prev_product_id = 0;
			foreach ($temp_result as $arr) {
				if ($prev_product_id != $arr->product_id) {
					$ArrFinal[$i] = $arr;
					$tempArray = array();
					foreach ($temp_result as $arr1) {
						if ($arr->product_id == $arr1->product_id)
							$tempArray[$arr1->product_variant_size] = $arr1->variant_price;
					}
					$ArrFinal[$i] = $arr;
					$ArrFinal[$i]->product_size = $tempArray;
					$i++;
				}
				$prev_product_id = $arr->product_id;
			}
			$result_val = $ArrFinal;

			for ($i = 0; $i < count($result_val); $i++) {

				foreach ($result_val[$i] as $key => $value) {
					if ($key == "image" || $key == "product_image") {
						$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
					} else {
						$product_result[$key] = $value;
					}
				}
				$result[] = $product_result;
			}

			$ArrData["product_detail"] = $result;

			if (count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = '';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	*/

	public function getLastValueFromUrl($url)
	{
		$query = parse_url($url, PHP_URL_QUERY);

		// If query string exists
		if (!empty($query)) {
			parse_str($query, $params);
			return !empty($params) ? end($params) : '';
		}

		// If path exists
		$path = parse_url($url, PHP_URL_PATH);

		if (!empty($path) && $path != '/') {
			$segments = explode('/', trim($path, '/'));
			return end($segments);
		}

		return '';
	}

	public function get_home_banner()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_home_banner_model($data);
			$home_banner = [];
			$home_banner_mobile = [];
			$upload_path = FILE_UPLOAD_PATH . 'home_banner/';
			foreach ($result_val as $row) {

				// Banner Desktop
				if (!empty($row->banner_image)) {
					$home_banner[] = [
						'banner_image' => $upload_path . $row->banner_image,
						'banner_link'  => $row->banner_link,
						'banner_type'  => $row->banner_type,
						'banner_category'  => $row->banner_category,
						'banner_slug' => $this->getLastValueFromUrl($row->banner_link),
					];
				}

				// Banner Mobile
				if (!empty($row->banner_mobapp_image)) {
					$home_banner_mobile[] = [
						'banner_image' => $upload_path . $row->banner_mobapp_image,
						'banner_link'  => $row->banner_link,
						'banner_type'  => $row->banner_type,
					];
				}
			}

			$result = [
				'home_banner'        => $home_banner,
				'home_banner_mobile' => $home_banner_mobile,
			];

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
	}
	public function get_advertise_top()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_advertise_top_model($data);
			$ad_banner=array();
			$ad_banner_mobile=array();
			for ($i = 0; $i < count($result_val); $i++) {
				if(!empty($result_val[$i]->adv_image)){
					$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
					$ad_banner[] = [
						'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_image,
						'ad_link'=>$result_val[$i]->adv_link,
						'ad_slug'=>$ad_slug,
						'ad_url_category'  => $result_val[$i]->advt_url_category,
						'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
						
					];
				}
				if(!empty($result_val[$i]->adv_mobapp_image)){
					$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
					$ad_banner_mobile[] = [
						'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_mobapp_image,
						'ad_link'=>$result_val[$i]->adv_link,
						'ad_slug'=>$ad_slug,
						'ad_url_category'  => $result_val[$i]->advt_url_category,
						'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
					];
				}
			}
			$result['ad_banner']=$ad_banner;
			$result['ad_banner_mobile']=$ad_banner_mobile;

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
	}

	public function get_advertise_top_single()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_advertise_top_model_single($data);
			$ad_banner=array();
			$ad_banner_mobile=array();
			for ($i = 0; $i < count($result_val); $i++) {
				if(!empty($result_val[$i]->adv_image)){
					$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
					$ad_banner[] = [
						'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_image,
						'ad_link'=>$result_val[$i]->adv_link,
						'ad_slug'=>$ad_slug,
						'ad_url_category'  => $result_val[$i]->advt_url_category,
						'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
						
					];
				}
				if(!empty($result_val[$i]->adv_mobapp_image)){
					$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
					$ad_banner_mobile[] = [
						'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_mobapp_image,
						'ad_link'=>$result_val[$i]->adv_link,
						'ad_slug'=>$ad_slug,
						'ad_url_category'  => $result_val[$i]->advt_url_category,
						'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
					];
				}
			}
			$result['ad_banner']=$ad_banner;
			$result['ad_banner_mobile']=$ad_banner_mobile;

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
	}

	public function get_advertise_bottom()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$zipcode = (isset($json_obj->zipcode) && $json_obj->zipcode != "") ? $json_obj->zipcode : '';
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		$Z_liker = '';
		$Z_perisible = '';
		$Z_cook_food = '';
		
			if($zipcode != ""){
				$newData['zipcode'] = $zipcode;
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($newData['zipcode']);
				if (count($zipcodeData) > 0) {
					
					$Z_perisible = $zipcodeData[0]->can_deliver_perishable_products;
					$Z_liker = $zipcodeData[0]->can_deliver_liker_products;
					$Z_cook_food = $zipcodeData[0]->can_deliver_cook_food_products;

				}
			}

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_advertise_bottom_model($data);
			$ad_banner=array();
			$ad_banner_mobile=array();
			for ($i = 0; $i < count($result_val); $i++) {
				
				$alternate_image = 0;
				
				if($result_val[$i]->category_id > 0){
					
					$CategoryDetails = $this->master->get_row_detail('tbl_categories', array('category_id' => $result_val[$i]->category_id));
					if(!empty($CategoryDetails)){
						
						$C_perisible = $CategoryDetails['is_perisible_products'];
						$C_liker = $CategoryDetails['is_liker_category'];
						$C_cook_food = $CategoryDetails['is_cook_food_category'];

						if($Z_perisible == 'No' && $C_perisible != 0){
							if($C_perisible == 1){
								$alternate_image = 1;
							} else {
								if($P_perisible == 0){
									$alternate_image = 1;
								}
							}
						}
						if($alternate_image == 0){
							if($Z_liker == 'No' && $C_liker != 0){
								if($C_liker == 1){
									$alternate_image = 1;
								} else {
									if($P_liker == 0){
										$alternate_image = 1;
									}
								}
							}
						}
						if($alternate_image == 0){
							if($Z_cook_food == 'No' && $C_cook_food != 0){
								if($C_cook_food == 1){
									$alternate_image = 1;
								} else {
									if($P_cook_food == 0){
										$alternate_image = 1;
									}
								}
							}
						}
					}

				}
				if($alternate_image == 0){
					if(!empty($result_val[$i]->adv_image)){
						$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
						$ad_banner[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_image,
							'ad_link'=>$result_val[$i]->adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
						];
					}
					if(!empty($result_val[$i]->adv_mobapp_image)){
						$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
						$ad_banner_mobile[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_mobapp_image,
							'ad_link'=>$result_val[$i]->adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
						];
					}
				} else {
					
					if(!empty($result_val[$i]->alt_adv_image)){
						$ad_banner[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->alt_adv_image,
							'ad_link'=>$result_val[$i]->alt_adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->alt_adv_link),
						];
					}

					if(!empty($result_val[$i]->alt_adv_mobapp_image)){
						$ad_banner_mobile[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->alt_adv_mobapp_image,
							'ad_link'=>$result_val[$i]->alt_adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->alt_adv_link),
						];
					}
				}
			}
			$result['ad_banner']=$ad_banner;
			$result['ad_banner_mobile']=$ad_banner_mobile;

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
	}

	public function get_advertise_bottom_last()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$zipcode = (isset($json_obj->zipcode) && $json_obj->zipcode != "") ? $json_obj->zipcode : '';
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		$Z_liker = '';
		$Z_perisible = '';
		$Z_cook_food = '';
			if($zipcode != ""){
				$newData['zipcode'] = $zipcode;
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($newData['zipcode']);
				if (count($zipcodeData) > 0) {
					
					$Z_perisible = $zipcodeData[0]->can_deliver_perishable_products;
					$Z_liker = $zipcodeData[0]->can_deliver_liker_products;
					$Z_cook_food = $zipcodeData[0]->can_deliver_cook_food_products;

				}
			}

			$data = array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
			);
			$result_val = $this->home_model->get_advertise_bottom_model_last($data);
			$ad_banner=array();
			$ad_banner_mobile=array();
			for ($i = 0; $i < count($result_val); $i++) {
				
				$alternate_image = 0;
				
				if($result_val[$i]->category_id > 0){
					
					$CategoryDetails = $this->master->get_row_detail('tbl_categories', array('category_id' => $result_val[$i]->category_id));
					if(!empty($CategoryDetails)){
						
						$C_perisible = $CategoryDetails['is_perisible_products'];
						$C_liker = $CategoryDetails['is_liker_category'];
						$C_cook_food = $CategoryDetails['is_cook_food_category'];

						if($Z_perisible == 'No' && $C_perisible != 0){
							if($C_perisible == 1){
								$alternate_image = 1;
							} else {
								if($P_perisible == 0){
									$alternate_image = 1;
								}
							}
						}
						if($alternate_image == 0){
							if($Z_liker == 'No' && $C_liker != 0){
								if($C_liker == 1){
									$alternate_image = 1;
								} else {
									if($P_liker == 0){
										$alternate_image = 1;
									}
								}
							}
						}
						if($alternate_image == 0){
							if($Z_cook_food == 'No' && $C_cook_food != 0){
								if($C_cook_food == 1){
									$alternate_image = 1;
								} else {
									if($P_cook_food == 0){
										$alternate_image = 1;
									}
								}
							}
						}
					}

				}
				if($alternate_image == 0){
					if(!empty($result_val[$i]->adv_image)){
						$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
						$ad_banner[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_image,
							'ad_link'=>$result_val[$i]->adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
						];
					}
					if(!empty($result_val[$i]->adv_mobapp_image)){
						$ad_slug = substr($result_val[$i]->adv_link, strrpos($result_val[$i]->adv_link, '/') + 1);
						$ad_banner_mobile[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->adv_mobapp_image,
							'ad_link'=>$result_val[$i]->adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->adv_link),
						];
					}
				} else {
					
					if(!empty($result_val[$i]->alt_adv_image)){
						$ad_banner[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->alt_adv_image,
							'ad_link'=>$result_val[$i]->alt_adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->alt_adv_link),
						];
					}

					if(!empty($result_val[$i]->alt_adv_mobapp_image)){
						$ad_banner_mobile[] = [
							'ad_image'=>FILE_UPLOAD_PATH . 'advertise/' . $result_val[$i]->alt_adv_mobapp_image,
							'ad_link'=>$result_val[$i]->alt_adv_link,
							'ad_url_category'  => $result_val[$i]->advt_url_category,
							'ad_slug' => $this->getLastValueFromUrl($result_val[$i]->alt_adv_link),
						];
					}
				}
			}
			$result['ad_banner']=$ad_banner;
			$result['ad_banner_mobile']=$ad_banner_mobile;

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
	}

	public function get_special_category_product()
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
		$w_productlist = '';
		if (!empty($oauth_key)) {
    		$token_result = $this->db->get_where('tbl_users_token', ['access_token' => $oauth_key])->row();
            $suser_id = $token_result->user_id ?? '';
			$w_productlist = $this->home_model->get_wishlist_product_by_user($suser_id);

		}

		// Receive values from POST
		$page  = $json_obj->page;
		$limit = $json_obj->limit;

		// Default values
		$page  = (!empty($page)) ? (int)$page : 1;
		$limit = (!empty($limit)) ? (int)$limit : 10;

		$offset = ($page - 1) * $limit;

		$special_category_slug=$json_obj->special_category_slug;

		$errors = $success_message = '';
		$ArrData = array();
		$result = array();
		
			$data['special_category_slug']=$special_category_slug;
			$special_category_product = $this->home_model->get_special_category_product_model($data);
			$home_product_slider_data = $special_category_product['home_product_slider_data'];
			$product_ids = $special_category_product['product_ids'];
			
			$product_data['product_id'] = implode(',',$product_ids);
			$total_products_c = $this->products_model->get_special_products_det_count($product_data);
			$total = count($total_products_c);
			$temp_result = $this->products_model->get_special_products_det($product_data,$limit, $offset);

			$ArrFinal = array();
			$i = 0;
			$prev_product_id = 0;
			foreach ($temp_result as $arr) {
				if ($prev_product_id != $arr->product_id) {
					$ArrFinal[$i] = $arr;
					$tempArray = array();
					$t = array();
					foreach ($temp_result as $key => $arr1) {
						if ($arr->product_id == $arr1->product_id) {
							
							if(!empty($arr1->product_variant_size)):
								$t['size'] = $arr1->product_variant_size;
								$t['price'] = $arr1->variant_price;
								$t['product_variant_id'] = $arr1->product_variant_id;
								$t['is_out_of_stock'] = $arr1->varaint_is_out_of_stock;
							$tempArray[] = $t;
							endif;
						}
							}
					$ArrFinal[$i] = $arr;
					$unique = array_map("unserialize", array_unique(array_map("serialize", $tempArray)));
					usort($unique, function($a, $b) {
						return $a['price'] - $b['price']; // Ascending sort by 'price'
					});
					$ArrFinal[$i]->product_size = $unique;
					$i++;
				}
				$prev_product_id = $arr->product_id;
			}
			$result_val = $ArrFinal;

			for ($i = 0; $i < count($result_val); $i++) {

				foreach ($result_val[$i] as $key => $value) {
					if ($key == "product_id" && !empty($w_productlist)) {
						$is_like = in_array($value, explode(',', $w_productlist)) ? 1 : 0;
					}
					$product_result['is_like'] = $is_like ?? 0;

					if ($key == "image" || $key == "product_image") {
						$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
					} else {
						$product_result[$key] = $value;
					}
				}
				$result['data'][] = $product_result;
				$result['current_page'] = $page;
			$result['per_page'] = $limit;
			$result['total'] = $total;
			$result['total_pages'] = ceil($total / $limit);
			}

			$ArrData["product_detail"] = $result;
			$ArrData["slider_detail"] = $home_product_slider_data;
			

			if (count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = '';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		
    }

	public function get_app_version()
	{
		
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str,true);

		$errors = $success_message = '';

		$json_str = $this->input->raw_input_stream;		
		$json_obj = json_decode($json_str);
		
		$ArrData = array();
		$NewArrData = array();
		
			$app_configuration_data = $this->appconfiguration_model->getConfiguration();
			if(count($app_configuration_data ) > 0){
				foreach($app_configuration_data as $key => $val){
					$ckey = $val['configuration_key'];
					$NewArrData[$ckey] = $val['configuration_value'];
				}
			}

			$ArrData = $NewArrData;
			$success_message = 'Data listed successfully';
		

		send_response_to_api($ArrData, $errors, $success_message);
	}
	
}
