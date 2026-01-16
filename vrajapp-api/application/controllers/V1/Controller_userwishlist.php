<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_userwishlist extends CI_Controller
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
		$this->load->model('userwishlist_model');
		$this->load->model('products_model');
		error_reporting(0);
	}

	public function create_wishlist()
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
    		$wishlist_data = array();
    		$wishlist_data = array(
    
                    "user_id" => $user_id,
    
                    "product_id" => $product_id,
    
                    "is_like" => $is_like
            );
    
			$result = $this->userwishlist_model->add_userwishlist($wishlist_data);
			$ArrData['id'] = $result;
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_wishlist_by_user_id()
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
			$total_products_c = $this->userwishlist_model->get_wishlist_by_user_id_count($user_id);
			$total = count($total_products_c);

			$temp_result = $this->userwishlist_model->get_wishlist_by_user_id($user_id,$limit, $offset);
			if (count($temp_result) > 0) {
				$ArrFinal = array();
				$i = 0;
				$prev_product_id = 0;
				//print_r($temp_result);exit;
				foreach ($temp_result as $arr) {

				
						$ArrFinal[$i] = $arr;

						$tempArray = array();

						//foreach ($temp_result as $arr1) {
							//if ($arr->product_id == $arr1->product_id) {
								$prod_var_arr = $this->products_model->get_variant_by_product_id($arr->product_id);
								if(count($prod_var_arr) > 0){
									foreach($prod_var_arr as $key => $val){
										if(!empty($val->id)){
											$t = array();
												$t['size'] = $val->product_variant_size;
												$t['price'] = $val->variant_price;
												$t['variant_id'] = $val->id;
												$t['is_out_of_stock'] = $val->is_out_of_stock;
											$tempArray[] = $t;
										}
									}
								}
								
							//}
						//}

						$ArrFinal[$i] = $arr;

						$unique = array_map("unserialize", array_unique(array_map("serialize", $tempArray)));
						usort($unique, function($a, $b) {
							return $a['price'] - $b['price']; // Ascending sort by 'price'
						});

						$ArrFinal[$i]->product_size = $unique;

						$i++;
					

					$prev_product_id = $arr->product_id;
				}
				$product_result = $ArrFinal;

				//$result["category"]=$category_result[0];
				for ($i = 0; $i < count($product_result); $i++) {
					//$result=array();
					foreach ($product_result[$i] as $key => $value) {

						if ($key == "product_image") {
							$products[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
						} else {
							$products[$key] = $value;
						}
					}
				
					$result['wishlist_data'][] = $products;
					$result['current_page'] = $page;
					$result['per_page'] = $limit;
					$result['total'] = $total;
					$result['total_pages'] = ceil($total / $limit);
				}
			}
			if (is_array($result) && count($result) > 0 && $result != null) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

}