<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_zipcodes extends CI_Controller
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
		$this->load->model('zipcodes_model');
		$this->load->model('Products_model');
		$this->load->model('Master_model', 'master');
		$this->load->library('session');
	}
	public function get_zipcodes()
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

				$result = $this->zipcodes_model->get_zipcodes($data);

			} else {
				$result = $this->zipcodes_model->get_zipcodes_by_search($data, $json_obj->search_keyword);

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

	public function get_zipcodes_autocomplete()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$errors = $success_message = '';
		$ArrData = array();
			
			$result = $this->zipcodes_model->select_zipcodes_autocomplete($json_obj->term);

			if (count($result) > 0) {
				$ArrData = $result;
				$success_message = '';
			} else {
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);

	}

	public function add_zipcode()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'zipcode' => $json_obj->zipcode,
				'state_id' => $json_obj->state_id,
				'minimum_order_value' => $json_obj->minimum_order_value,
				'can_deliver_perishable_products' => $json_obj->can_deliver_perishable_products,
				'delivery_types' => $json_obj->delivery_types,
				'created_by' => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result = $this->zipcodes_model->add_zipcode($data);
			$ArrData = $result;
			if ($result) {
				$success_message = 'Zipcode added successfully';
			} else {
				$errors = 'Zipcode Not Added Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function get_zipcode_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'zipcode_id' => $json_obj->zipcode_id
			);
			$result = $this->zipcodes_model->get_zipcode_by_id($data);
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
	public function update_zipcode()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$zipcode_id = $json_obj->zipcode_id;
			$data = array(
				'zipcode' => $json_obj->zipcode,
				'state_id' => $json_obj->state_id,
				'minimum_order_value' => $json_obj->minimum_order_value,
				'can_deliver_perishable_products' => $json_obj->can_deliver_perishable_products,
				'delivery_types' => $json_obj->delivery_types,
				'modified_by' => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result = $this->zipcodes_model->update_zipcode($data, $zipcode_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Zipcode Updated Successfully';
			} else {
				$errors = 'Zipcode Not Updated Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_zipcode()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$zipcode_id = $json_obj->zipcode_id;
			$data = array(
				'is_active' => '0',
				'is_deleted' => '1'
			);
			$result = $this->zipcodes_model->update_zipcode($data, $zipcode_id);
			$ArrData = $result;
			if ($result) {
				$ArrData = $result;
				$success_message = 'Zipcode Deleted Successfully';
			} else {
				$errors = 'Zipcode Not Deleted Successfully';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function remove_zipcode_products()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		
		$oauth_key = $json_obj->oauth_key;
		$user_id = (isset($json_obj->user_id)) ? $json_obj->user_id : 0;
		$cartData = ($json_obj->cart != "") ? json_decode(stripslashes($json_obj->cart), true) : "";
		
		$errors = $success_message = '';
		$ArrData = array();
		$ArrData2 = array('remove_product' => 0, 'zipcodeData' => array());
		if (check_oauth_key($oauth_key)) {
			
			$data = array(
				'zipcode' => $json_obj->zipcode
			);
			$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
			//echo '<pre>';print_r($zipcodeData);
			if (count($zipcodeData) > 0) {
				$ArrData2['zipcodeData'] = $zipcodeData[0];
				$success_message = '';
			} else {
				$errors = 'No data available';
			}

			if (count($zipcodeData) > 0) {

				/* $data['can_deliver_perishable_products'] = $zipcodeData[0]->can_deliver_perishable_products;
					$data['can_deliver_liker_products'] = $zipcodeData[0]->can_deliver_liker_products;
					$data['can_deliver_cook_food_products'] = $zipcodeData[0]->can_deliver_cook_food_products;
				} */
				
				//if($zipcodeData[0]->can_deliver_perishable_products == "No" || $zipcodeData[0]->can_deliver_liker_products == "No" || $zipcodeData[0]->can_deliver_cook_food_products == "No"){

					$Z_perisible = $zipcodeData[0]->can_deliver_perishable_products;
					$Z_liker = $zipcodeData[0]->can_deliver_liker_products;
					$Z_cook_food = $zipcodeData[0]->can_deliver_cook_food_products;

					$MainCartData = $cartData;
					$CartData = $cartData;

					$newCartArr = [];
					$total_items = 0;
					$cart_total = 0;
					$current_date = date("Y-m-d");
					$date15DayAgo = date('Y-m-d', strtotime('-15 day', strtotime($current_date)));

					//echo '<pre>';print_r($CartData);
					if(!empty($CartData)){
						
						unset($CartData['cart_total'], $CartData['total_items']);
						
						foreach ($CartData as $key => $item){
							
							$ItsValid = 1;
							$p_slug = $item['product_slug'];
							$p_id = $item['id'];
							$p_row_id = $key;
							$p_variant_id = ($item['options']['variant_id'] != "" && $item['options']['variant_id'] > 0) ? $item['options']['variant_id'] : 0;

							$P_Data = $this->Products_model->get_product_with_multi_category(array('product_slug' => $p_slug));
							//echo '<pre>';print_r($P_Data);exit;

							if(!empty($P_Data) && $date15DayAgo <= $item['created_date']){
								
								for($i = 0; $i < count($P_Data); $i++)
								{
									$C_perisible = $P_Data[$i]->is_perisible_category;
									$C_liker = $P_Data[$i]->is_liker_category;
									$C_cook_food = $P_Data[$i]->is_cook_food_category;

									$P_perisible = $P_Data[$i]->is_perisible_products;
									$P_liker = $P_Data[$i]->is_liker_products;
									$P_cook_food = $P_Data[$i]->is_cook_food_products;
									
									if($P_Data[$i]->is_out_of_stock == 0){
										$ItsValid = 0;
										break;
									}
									if($Z_perisible == 'No' && $C_perisible != 0){
										if($C_perisible == 1){
											$ItsValid = 0;
											break;
										} else {
											if($P_perisible == 0){
												$ItsValid = 0;
												break;
											}
										}
									}
									if($ItsValid == 1){
										if($Z_liker == 'No' && $C_liker != 0){
											if($C_liker == 1){
												$ItsValid = 0;
												break;
											} else {
												if($P_liker == 0){
													$ItsValid = 0;
													break;
												}
											}
										}
									}
									if($ItsValid == 1){
										if($Z_cook_food == 'No' && $C_cook_food != 0){
											if($C_cook_food == 1){
												$ItsValid = 0;
												break;
											} else {
												if($P_cook_food == 0){
													$ItsValid = 0;
													break;
												}
											}
										}
									}
								}
							} else {
								$ItsValid = 0;
							}
							//echo "ItsValid ".$key." => ".$ItsValid."</br>";

							if($ItsValid == 0)
							{
								$ArrData2['remove_product'] = 1;
								unset($MainCartData[$key]);
								if($user_id != "" && $user_id > 0){
									
									$cond = array('customer_id' => $user_id, 'id' => $p_id);
									if($p_variant_id != "" && $p_variant_id > 0){
										$cond['options_variant_id'] = $p_variant_id;
									}
									$this->master->deleteRow('tbl_cart_items', $cond);
								} else {
									
									$cond = array('row_id' => $p_row_id);
									if($p_variant_id != "" && $p_variant_id > 0){
										$cond['options_variant_id'] = $p_variant_id;
									}
									$details = $this->master->get_row_detail('tbl_cart_items', $cond);
									if(!empty($details)){
										$this->master->deleteRow('tbl_cart_items', $cond);
									} else {
										$cond = array('id' => $p_id);
										if($p_variant_id != "" && $p_variant_id > 0){
											$cond['options_variant_id'] = $p_variant_id;
										}
										$this->master->deleteRow('tbl_cart_items', $cond);
									}
								}
							} else {
								$total_items += $item['qty'];
								$cart_total += $item['subtotal'];
							}
						}
						
						$MainCartData['total_items'] = $total_items;
						$MainCartData['cart_total'] = $cart_total;
						
						$new_expiration_time = time() + CART_SESSION_EXPIRATION_TIME; // 15 days added
						$this->session->set_userdata(array('cart_contents' => $MainCartData), $new_expiration_time);
						

						$ArrData = $MainCartData;
						$success_message = "Product Setup";
						$errors = "";
					} else {
						$MainCartData = [];
						$MainCartData['total_items'] = 0;
						$MainCartData['cart_total'] = 0;

						$new_expiration_time = time() + CART_SESSION_EXPIRATION_TIME; // 15 days added
						$this->session->set_userdata(array('cart_contents' => $CartData), $new_expiration_time);
						$ArrData = $MainCartData;
						$success_message = "Product Setup";
						$errors = "";
					}
				//}
			}

			send_response_to_api_with_extra_parameters($ArrData, $ArrData2, $errors, $success_message);
		}
	}

	public function get_zipcode_detail_old()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			$data = array(
				'zipcode' => $json_obj->zipcode
			);
			$result = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
			//echo "<pre>";print_r($result);exit;
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

	public function get_zipcode_detail()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$user_id = (isset($json_obj->user_id)) ? $json_obj->user_id : 0;
		$errors = $success_message = '';
		$ArrData = array();
			$data = array(
				'zipcode' => $json_obj->zipcode
			);
			$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
			//echo "<pre>";print_r($zipcodeData);exit;
			$ArrData = $zipcodeData;
			if (count($zipcodeData) > 0) {
				$ArrData = $zipcodeData;
				$success_message = '';
			} else {
				$errors = 'No data available';
			}

			if (count($zipcodeData) > 0) {

				/* $data['can_deliver_perishable_products'] = $zipcodeData[0]->can_deliver_perishable_products;
					$data['can_deliver_liker_products'] = $zipcodeData[0]->can_deliver_liker_products;
					$data['can_deliver_cook_food_products'] = $zipcodeData[0]->can_deliver_cook_food_products;
				} */
				
				//if($zipcodeData[0]->can_deliver_perishable_products == "No" || $zipcodeData[0]->can_deliver_liker_products == "No" || $zipcodeData[0]->can_deliver_cook_food_products == "No"){

					$Z_perisible = $zipcodeData[0]->can_deliver_perishable_products;
					$Z_liker = $zipcodeData[0]->can_deliver_liker_products;
					$Z_cook_food = $zipcodeData[0]->can_deliver_cook_food_products;

					$CartData = $this->session->userdata('cart_contents');
					$MainCartData = $this->session->userdata('cart_contents');
					$newCartArr = [];
					$total_items = 0;
					$cart_total = 0;
					$current_date = date("Y-m-d");
					$date15DayAgo = date('Y-m-d', strtotime('-15 day', strtotime($current_date)));
					
					if(!empty($CartData)){
						
						unset($CartData['cart_total'], $CartData['total_items']);
						
						foreach ($CartData as $key => $item){
							
							$ItsValid = 1;
							$p_slug = $item['product_slug'];
							$P_Data = $this->Products_model->get_product_with_multi_category(array('product_slug' => $p_slug));
							
							if(!empty($P_Data) && $date15DayAgo <= $item['created_date']){
								
								for($i = 0; $i < count($P_Data); $i++)
								{
									$C_perisible = $P_Data[$i]->is_perisible_category;
									$C_liker = $P_Data[$i]->is_liker_category;
									$C_cook_food = $P_Data[$i]->is_cook_food_category;

									$P_perisible = $P_Data[$i]->is_perisible_products;
									$P_liker = $P_Data[$i]->is_liker_products;
									$P_cook_food = $P_Data[$i]->is_cook_food_products;
									
									if($P_Data[$i]->is_out_of_stock == 0){
										$ItsValid = 0;
										break;
									}

									if($Z_perisible == 'No' && $C_perisible != 0){
										if($C_perisible == 1){
											$ItsValid = 0;
											break;
										} else {
											if($P_perisible == 0){
												$ItsValid = 0;
												break;
											}
										}
									}
									if($ItsValid == 1){
										if($Z_liker == 'No' && $C_liker != 0){
											if($C_liker == 1){
												$ItsValid = 0;
												break;
											} else {
												if($P_liker == 0){
													$ItsValid = 0;
													break;
												}
											}
										}
									}
									if($ItsValid == 1){
										if($Z_cook_food == 'No' && $C_cook_food != 0){
											if($C_cook_food == 1){
												$ItsValid = 0;
												break;
											} else {
												if($P_cook_food == 0){
													$ItsValid = 0;
													break;
												}
											}
										}
									}
								}
							} else {
								$ItsValid = 0;
							}

							if($ItsValid == 0)
							{
								unset($MainCartData[$key]);
							} else {
								$total_items += $item['qty'];
								$cart_total += $item['subtotal'];
							}
						}
						$MainCartData['total_items'] = $total_items;
						$MainCartData['cart_total'] = $cart_total;
						$new_expiration_time = time() + CART_SESSION_EXPIRATION_TIME; // 15 days added
						$this->session->set_userdata(array('cart_contents' => $MainCartData), $new_expiration_time);
					}
				//}
			}

			send_response_to_api($ArrData, $errors, $success_message);
	
	}
}