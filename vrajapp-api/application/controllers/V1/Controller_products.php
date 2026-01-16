<?php



defined('BASEPATH') or exit('No direct script access allowed');







class Controller_products extends CI_Controller

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



		error_reporting(0);



		$this->load->model('Products_model');

		$this->load->model('zipcodes_model');

		$this->load->model('home_model');



	}



	public function get_products()

	{



		$json_str = file_get_contents('php://input');



		$json_obj = json_decode($json_str);







		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';



		$ArrData = array();



		$result = array();



		if (check_oauth_key($oauth_key)) {



			try {



				$data = array(



					'is_active' => $json_obj->is_active_only,



					'search_keyword' => $json_obj->search_keyword,



					'limit' => $json_obj->limit,



					'is_home_display' => $json_obj->is_home_display,



					'product_style' => $json_obj->product_style,



					'page_no' => $json_obj->page_no,



					'sort_column' => $json_obj->sort_column,



					'sort_order' => $json_obj->sort_order



				);





				if ($json_obj->search_keyword == "") {







					$result_val = $this->Products_model->get_products($data);





					for ($i = 0; $i < count($result_val); $i++) {





						foreach ($result_val[$i] as $key => $value) {



							if ($key == "image") {



								$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;



							} else {



								$product_result[$key] = $value;



							}



						}



						$result[] = $product_result;



					}



				} else {



					$result_val = $this->Products_model->get_products_by_search($data, $json_obj->search_keyword);



					for ($i = 0; $i < count($result_val); $i++) {





						foreach ($result_val[$i] as $key => $value) {



							if ($key == "image") {



								$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;



							} else {



								$product_result[$key] = $value;



							}



						}



						$result[] = $product_result;



					}



				}



				if (count($result) > 0) {



					$ArrData = $result;



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



	public function get_product_by_slug()

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
		if (!empty($oauth_key) && (check_oauth_key($oauth_key))) {
    		$token_result = $this->db->get_where('tbl_users_token', ['access_token' => $oauth_key])->row();
            $suser_id = $token_result->user_id;
			$w_productlist = $this->home_model->get_wishlist_product_by_user($suser_id);

		}	





		$errors = $success_message = '';



		$ArrData = array();





			try {



				$data = array(



					'product_slug' => $json_obj->product_slug



				);

				
				$ItsValid = 0;
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($json_obj->zipcode);
				if (count($zipcodeData) > 0) {
					
					if($zipcodeData[0]->can_deliver_perishable_products == "No" || $zipcodeData[0]->can_deliver_liker_products == "No" || $zipcodeData[0]->can_deliver_cook_food_products == "No"){

						$Z_perisible = $zipcodeData[0]->can_deliver_perishable_products;
						$Z_liker = $zipcodeData[0]->can_deliver_liker_products;
						$Z_cook_food = $zipcodeData[0]->can_deliver_cook_food_products;
						
						$P_Data = $this->Products_model->get_product_with_multi_category(array('product_slug' => $json_obj->product_slug));
						if(!empty($P_Data)){
							
							
							for($i = 0; $i < count($P_Data); $i++)
							{
								$C_perisible = $P_Data[$i]->is_perisible_category;
								$C_liker = $P_Data[$i]->is_liker_category;
								$C_cook_food = $P_Data[$i]->is_cook_food_category;

								$P_perisible = $P_Data[$i]->is_perisible_products;
								$P_liker = $P_Data[$i]->is_liker_products;
								$P_cook_food = $P_Data[$i]->is_cook_food_products;
								
								if($Z_perisible == 'No' && ($C_perisible == 0 || $C_perisible == 2)){
									if($C_perisible == 0){
										$ItsValid = 1;
										break;
									} else {
										if($P_perisible == 1){
											$ItsValid = 1;
											$total_items++;
											$cart_total += $item['subtotal'];
											break;
										}
									}
								}
								if($Z_liker == 'No' && ($C_liker == 0 || $C_liker == 2)){
									if($C_liker == 0){
										$ItsValid = 1;
										break;
									} else {
										if($P_liker == 1){
											$ItsValid = 1;
											$total_items++;
											$cart_total += $item['subtotal'];
											break;
										}
									}
								}
								if($Z_cook_food == 'No' && ($C_cook_food == 0 || $C_cook_food == 2)){
									if($C_cook_food == 0){
										$ItsValid = 1;
										break;
									} else {
										if($P_cook_food == 1){
											$ItsValid = 1;
											$total_items++;
											$cart_total += $item['subtotal'];
											break;
										}
									}
								}
							}
						} else {
							$ItsValid = 1;
						}
					} else {
						$ItsValid = 1;
					}
				} else {
					$ItsValid = 1;
				}
				//echo $ItsValid;exit;
				if($ItsValid == 1){
					$temp_result = $this->Products_model->get_product_by_slug($data);

				} else {
					$temp_result = array();
				}
				$ArrFinal = array();

				if(is_array($temp_result) && count($temp_result)>0)

				{

					$temp_image_result = $this->Products_model->get_image_by_product_id($temp_result[0]->product_id);

					$temp_variant_result = $this->Products_model->get_variant_by_product_id($temp_result[0]->product_id);

					$temp_category = $this->Products_model->get_category_by_product_id($temp_result[0]->product_id);

					$temp_tag = $this->Products_model->get_tag_by_product_id($temp_result[0]->product_id);







					$ArrFinal['products'] = $temp_result[0];

					

					$ArrFinal['products']->category_slug = $temp_category[0]->category_slug;

					for ($i = 0; $i < count($temp_variant_result); $i++) {

						$ArrFinal['products']->variants[] = $temp_variant_result[$i];

						foreach ($temp_variant_result[$i] as $image_key => $image_value) {

							if ($image_key == 'variant_image' || $image_key == 'product_image') {

								$ArrFinal['products']->variants[count($ArrFinal['products']->variants) - 1]->variant_image = FILE_UPLOAD_PATH . 'products/' . $image_value;

							}

						}

					}

					for ($j = 0; $j < count($temp_image_result); $j++) {

						$ArrFinal['products']->images[] = $temp_image_result[$j];

					}

					$previous_category = "";

					for ($k = 0; $k < count($temp_category); $k++) {

						if ($previous_category != $temp_category[$k]->category_id) {

							$ArrFinal['products']->categories[] = $temp_category[$k];

						}



						$previous_category = $temp_category[$k]->category_id;

					}

					$previous_tag = "";

					for ($l = 0; $l < count($temp_tag); $l++) {

						if ($previous_tag != $temp_tag[$l]->tag_id) {

							$ArrFinal['products']->tags[] = $temp_tag[$l];

						}

						$previous_tag = $temp_tag[$l]->tag_id;

					}

				}

				// if(!in_array($temp_variant_result,$ArrFinal))

				// {



				// }

				// if(!in_array($temp_image_result,$ArrFinal))

				// {

				// 	$ArrFinal[$temp_result->product_id]->images=$temp_image_result;

				// }





				// $ArrFinal = array();

				// $arVariants = array();

				// $arProductImages = array();



				// foreach ($temp_result as $arr) {

				// 	if(is_array($arVariants[$arr->product_id])) {

				// 		$tempArray = array();

				// 		 $tempArray['product_variant_size'] =$arr->product_variant_size;

				// 		 $tempArray["variant_price"]=$arr->variant_price;

				// 		$arVariants[$arr->product_id][] = $tempArray;

				// 	}

				// 	else {

				// 		$arVariants[$arr->product_id] = array();

				// 		$tempArray = array();

				// 		$tempArray['product_variant_size'] =$arr->product_variant_size;

				// 		$tempArray["variant_price"]=$arr->variant_price;

				// 		$arVariants[$arr->product_id][] = $tempArray;

				// 	}



				// 	if(is_array($arProductImages[$arr->product_id])) {

				// 		$tempArray = array();

				// 		$tempArray['image'] =$arr->image;

				// 		$tempArray["product_image_id"]=$arr->product_image_id;

				// 		$arProductImages[$arr->product_id][] = $tempArray;

				// 	}

				// 	else {

				// 		$arProductImages[$arr->product_id] = array();

				// 		$tempArray = array();

				// 		$tempArray['image'] =$arr->image;

				// 		$tempArray["product_image_id"]=$arr->product_image_id;

				// 		$arProductImages[$arr->product_id][] = $tempArray;

				// 	}

				// }



				// $temp_product_ids = array();

				// foreach ($temp_result as $arr) {

				// 	if(!in_array($arr->product_id, $temp_product_ids)) {

				// 		$tempArr = array();

				// 		$tempArr[$arr->product_id] = $arr;

				// 		$tempArr[$arr->product_id]['variants'] = $arVariants[$arr->product_id];

				// 		$tempArr[$arr->product_id]['product_images'] = $arProductImages[$arr->product_id];

				// 		$ArrFinal[] = $tempArr;

				// 	}



				// }



				// $i = 0;

				// $prev_product_id = 0;

				// foreach ($temp_result as $arr) {



				// 	if ($prev_product_id != $arr->product_id) {



				// 		$ArrFinal[$i] = $arr;



				// 		$tempArray = array();



				// 		foreach ($temp_result as $arr1) {



				// 			if ($arr->product_id == $arr1->product_id)								

				// 			{

				// 				$t = array();

				// 				if($arr1->id > 0)

				// 				{

				// 					$t['size'] = $arr1->product_variant_size;

				// 					$t['price'] = $arr1->variant_price;

				// 					$t['variant_id'] = $arr1->id;

				// 				}

				// 				$tempArray[] = $t;



				// 				$j =array();

				// 				if($arr1->product_image_id > 0)

				// 				{

				// 					$j['product_image_id'] = $arr1->product_image_id;

				// 					$j['image'] = ASSET_URL . 'images/' .$arr1->image;

				// 				}

				// 				$tempImageArray[] = $j;

				// 			}

				// 		}



				// 		//$ArrFinal[$i] = $arr;



				// 		$ArrFinal[$i]->product_size = $tempArray;

				// 		$ArrFinal[$i]->product_images = $tempImageArray;



				// 		$i++;



				// 	}



				// 	$prev_product_id = $arr->product_id;

				// }











				$result_val = $ArrFinal;



				if(count($result_val)>0)

				{

					for ($i = 0; $i < count($result_val); $i++) {



						foreach ($result_val['products'] as $key => $value) {



							if ($key == "images") {

								for ($k = 0; $k < count($result_val['products']->images); $k++) {

									foreach ($result_val['products']->images[$k] as $image_key => $image_value) {

										if ($image_key == 'image' || $image_key == 'product_image') {

											$product_result['images'][][$image_key] = FILE_UPLOAD_PATH . 'products/' . $image_value;



										}

									}

								}

							} else {

							if ($key == "product_id") {
								$is_like = in_array($value, explode(',', $w_productlist)) ? 1 : 0;
							}
							$product_result['is_like'] = $is_like ?? 0;


								if ($key == "product_image") {



									$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;



								} else {

									$product_result[$key] = $value;

								}





							}



						}



						$ArrFinal = $product_result;



					}

				}







				if (count($ArrFinal) > 0) {



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



	public function add_product()

	{



		$json_str = file_get_contents('php://input');



		$json_obj = json_decode($json_str);







		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';



		$ArrData = array();



		$variant_result = "";



		if (check_oauth_key($oauth_key)) {



			try {



				$data = array(



					'brand_id' => $json_obj->brand_id,



					'product_name' => $json_obj->product_name,



					'product_slug' => $json_obj->product_slug,



					'product_price' => $json_obj->product_price,



					'product_style' => $json_obj->product_style,



					'product_type' => $json_obj->product_type,



					'product_description' => $json_obj->product_description,



					'badge_text' => $json_obj->badge_text,



					'badge_text_color' => $json_obj->badge_text_color,



					'badge_background_color' => $json_obj->badge_background_color,



					'product_sku' => $json_obj->product_sku,



					'is_home_display' => $json_obj->is_home_display,



					'product_weight_gms' => $json_obj->product_weight_gms,



					'created_by' => $json_obj->created_by,



					'created_datetime' => date('Y-m-d H:i:s'),



					'is_active' => $json_obj->is_active



				);







				$result = $this->Products_model->add_product($data, 'tbl_products');



				if ($json_obj->tags != "") {



					foreach ($json_obj->tags as $tags) {



						$tag_data = array(



							'tag_id' => $tags,



							'product_id' => $result,



							'created_by' => $json_obj->created_by,



							'created_datetime' => date('Y-m-d H:i:s'),



							'is_active' => $json_obj->is_active



						);



					}



					$tag_result = $this->Products_model->add_product($tag_data, 'tbl_product_tags_mapping');



				}



				if ($json_obj->categories != "") {



					foreach ($json_obj->categories as $categories) {



						$category_data = array(



							'category_id' => $categories,



							'product_id' => $result,



							'created_by' => $json_obj->created_by,



							'created_datetime' => date('Y-m-d H:i:s'),



							'is_active' => $json_obj->is_active



						);



						$category_result = $this->products_model->add_product($category_data, 'tbl_categories_products_mapping');



					}



				}







				if ($json_obj->variants != "") {



					foreach ($json_obj->variants as $variants) {



						$variant_data = array(



							'product_id' => $result,



							'variant_master_1_id' => $variants->variant_master_1_id,



							'variant_1_value' => $variants->variant_1_value,



							'variant_master_2_id' => $variants->variant_master_2_id,



							'variant_2_value' => $variants->variant_2_value,



							'variant_master_3_id' => $variants->variant_master_3_id,



							'variant_3_value' => $variants->variant_3_value,



							'variant_price' => $variants->variant_price,



							'created_by' => $json_obj->created_by,



							'created_datetime' => date('Y-m-d H:i:s'),







							'is_active' => $json_obj->is_active



						);



					}



					$variant_result = $this->Products_model->add_product($variant_data, 'tbl_variant_products');



				}



				if ($json_obj->product_images != "") {



					foreach ($json_obj->product_images as $images) {



						if ($variant_result != "") {



							$variant_result = $variant_result;



						} else {



							$variant_result = "";



						}



						$image_data = array(



							'product_id' => $result,



							'variant_id' => $variant_result,



							'image' => $images,



							'created_by' => $json_obj->created_by,



							'created_datetime' => date('Y-m-d H:i:s'),



							'is_active' => $json_obj->is_active



						);



						$image_result = $this->Products_model->add_product($image_data, 'tbl_product_images');



					}



				}



				$ArrData = $result;



				if ($result) {



					$success_message = 'Product added successfully';



				} else {



					$errors = 'Product Not Added Successfully';



				}



			} catch (Exception $e) {



				$ArrData = "There is problem";



			}



			send_response_to_api($ArrData, $errors, $success_message);



		}



	}



	public function get_product_by_id()

	{



		$json_str = file_get_contents('php://input');



		$json_obj = json_decode($json_str);







		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';



		$ArrData = array();



		if (check_oauth_key($oauth_key)) {



			try {



				$data = array(



					'product_id' => $json_obj->product_id



				);



				$result = array();







				$temp_product_result = $this->Products_model->get_product_by_id($data);



				$temp_product_image = $this->Products_model->get_image_by_product_id($data);



				if (count($temp_product_result) > 0) {



					$result = $temp_product_result[0];



					$result['categories'] = $this->Products_model->get_category_by_product_id($data);







					if (count($temp_product_image) > 0) {



						foreach ($temp_product_image as $product_image) {



							if ($product_image->variant_id == 0) {



								$result['product_images'] = $product_image->product_image;



							} else {



								$result['product_images'] = $product_image->product_image;



							}



						}



					}







					$result['variants'] = $this->Products_model->get_variant_by_product_id($data);











				} else {







					$result = "No Product Found";



				}



				$ArrData = $result;



				if ($result) {



					$success_message = '';



				} else {



					$errors = '';



				}



			} catch (Exception $e) {



				$ArrData = "There is problem";



			}



		}







		send_response_to_api($ArrData, $errors, $success_message);



	}



	public function update_product()

	{



		$json_str = file_get_contents('php://input');



		$json_obj = json_decode($json_str);







		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';



		$ArrData = array();



		if (check_oauth_key($oauth_key)) {



			try {



				$product_id = $json_obj->product_id;



				$data = array(



					'brand_id' => $json_obj->brand_id,



					'product_name' => $json_obj->product_name,



					'product_slug' => $json_obj->product_slug,



					'product_price' => $json_obj->product_price,



					'product_type' => $json_obj->product_type,



					'product_description' => $json_obj->product_description,



					'badge_text' => $json_obj->badge_text,



					'badge_text_color' => $json_obj->badge_text_color,



					'badge_background_color' => $json_obj->badge_background_color,



					'product_sku' => $json_obj->product_sku,



					'product_weight_gms' => $json_obj->product_weight_gms,



					'modified_by' => $json_obj->modified_by,



					'modified_datetime' => date('Y-m-d H:i:s'),



					'is_active' => $json_obj->is_active



				);







				$result = $this->Products_model->update_product($data, $product_id, 'tbl_products');



				foreach ($json_obj->categories as $categories) {



					$category_data = array(



						'category_id' => $categories,



						'product_id' => $json_obj->product_id,



						'modified_by' => $json_obj->modified_by,



						'modified_datetime' => date('Y-m-d H:i:s'),



						'is_active' => $json_obj->is_active



					);



				}



				$category_result = $this->Products_model->update_product($category_data, $product_id, 'tbl_categories_products_mapping');



				foreach ($json_obj->variants as $variants) {



					$variant_data = array(



						'product_id' => $json_obj->product_id,



						'variant_master_1_id' => $variants->variant_master_1_id,



						'variant_1_value' => $variants->variant_1_value,



						'variant_master_2_id' => $variants->variant_master_2_id,



						'variant_2_value' => $variants->variant_2_value,



						'variant_master_3_id' => $variants->variant_master_3_id,



						'variant_3_value' => $variants->variant_3_value,



						'variant_price' => $variants->variant_price,



						'modified_by' => $json_obj->modified_by,



						'modified_datetime' => date('Y-m-d H:i:s'),



						'is_active' => $json_obj->is_active



					);



				}



				$variant_result = $this->Products_model->update_product($variant_data, $product_id, 'tbl_variant_products');







				foreach ($json_obj->product_images as $images) {



					$image_data = array(



						'product_id' => $json_obj->product_id,



						'variant_id' => $variant_result,



						'image' => $images,



						'modified_by' => $json_obj->modified_by,



						'modified_datetime' => date('Y-m-d H:i:s'),



						'is_active' => $json_obj->is_active



					);



					$image_result = $this->Products_model->update_product($image_data, $product_id, 'tbl_product_images');



				}



				$ArrData = $result;



				if ($result) {



					$success_message = 'Product Update successfully';



				} else {



					$errors = 'Product Not Update Successfully';



				}



			} catch (Exception $e) {



				$ArrData = "There is problem";



			}



			send_response_to_api($ArrData, $errors, $success_message);



		}



	}



	public function delete_product()

	{



		$json_str = file_get_contents('php://input');



		$json_obj = json_decode($json_str);







		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';



		$ArrData = array();



		if (check_oauth_key($oauth_key)) {



			$product_id = $json_obj->product_id;



			$data = array(



				'is_deleted' => '1'



			);







			$result = $this->Products_model->update_product($data, $product_id, 'tbl_products');







			$category_data = array(



				'is_deleted' => '1'



			);







			$category_result = $this->Products_model->update_product($category_data, $product_id, 'tbl_categories_products_mapping');







			$variant_data = array(



				'is_deleted' => '1'



			);







			$variant_result = $this->Products_model->update_product($variant_data, $product_id, 'tbl_variant_products');











			$image_data = array(



				'is_deleted' => '1'



			);



			$image_result = $this->Products_model->update_product($image_data, $product_id, 'tbl_product_images');







			$ArrData = $result;



			if ($result) {



				$success_message = 'Product Delete successfully';



			} else {



				$errors = 'Product Not Delete Successfully';



			}



			send_response_to_api($ArrData, $errors, $success_message);



		}



	}



	public function add_special_products()

	{



		$json_str = file_get_contents('php://input');



		$json_obj = json_decode($json_str);







		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';



		$ArrData = array();



		if (check_oauth_key($oauth_key)) {



			//$special_product_name = $json_obj->special_product_name;



			$data = array(



				'special_product_name' => $json_obj->special_product_name,



				'product_ids' => $json_obj->product_ids,



				'created_by' => $json_obj->created_by,



				'created_datetime' => date('Y-m-d H:i:s'),



				'is_active' => '1'



			);







			$result = $this->Products_model->add_product($data, 'tbl_special_products');











			$ArrData = $result;



			if ($result) {



				$success_message = 'Special Product Added Successfully';



			} else {



				$errors = 'Special Product Not Added Successfully';



			}



			send_response_to_api($ArrData, $errors, $success_message);



		}



	}



	public function get_special_products()

	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		$result = array();

		if (check_oauth_key($oauth_key)) {

			$data = array(
				'special_product_slug' => $json_obj->special_product_slug,
				'zipcode' => $json_obj->zipcode
			);

			$result_product_id = $this->Products_model->get_special_products_ids($data);
			$data['product_id'] = $result_product_id[0]['product_ids'];

			if ($json_obj->zipcode != "") {

				$zipcode_result = $this->zipcodes_model->get_zipcode_by_data($json_obj->zipcode);
				

				if (count($zipcode_result) > 0) {
					
					$data['can_deliver_perishable_products'] = $zipcode_result[0]->can_deliver_perishable_products;
					$data['can_deliver_liker_products'] = $zipcode_result[0]->can_deliver_liker_products;
					$data['can_deliver_cook_food_products'] = $zipcode_result[0]->can_deliver_cook_food_products;

					$temp_result = $this->Products_model->get_special_products($data);

				} else {
					$temp_result = $this->Products_model->get_special_products($data);
				}

			} else {
				$temp_result = $this->Products_model->get_special_products($data);
			}

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

					if ($key == "image" || $key == "product_image") {
						$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
					} else {
						$product_result[$key] = $value;
					}
				}
				$result[] = $product_result;
			}
			$ArrData["product_detail"] = $result;
			$ArrData["special_product_name"] = $result_product_id[0]['special_product_name'];

			if (count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = '';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}



	public function get_home_category_product_old()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$data = array(
			'zipcode' => $json_obj->zipcode,
		);
		$this->load->model('zipcodes_model');
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			if ($data['zipcode'] != "") {
				$result_zipcode = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);

				if (count($result_zipcode) > 0) {

					if ($result_zipcode[0]->can_deliver_perishable_products != "" && $result_zipcode[0]->can_deliver_perishable_products == "No") {

						$data['is_perisible_products'] = "0";
						$temp_result = $this->Products_model->get_home_category_product($result_zipcode);

						$ArrFinal = array();

						$i = 0;
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
								$ArrFinal[$i] = $arr;
								$ArrFinal[$i]->product_size = $tempArray;
								$i++;
							}
							$prev_product_id = $arr->product_id;
						}
						$result_val = $ArrFinal;
					} else {
						$temp_result = $this->Products_model->get_home_category_product();
						
						$ArrFinal = array();

						$i = 0;
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

								$ArrFinal[$i] = $arr;
								$ArrFinal[$i]->product_size = $tempArray;
								$i++;
							}
							$prev_product_id = $arr->product_id;
						}
						$result_val = $ArrFinal;
					}
				} else {
					$result = "No Zipcode found";
				}
			} else {
				$temp_result = $this->Products_model->get_home_category_product();
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
						$ArrFinal[$i] = $arr;
						$ArrFinal[$i]->product_size = $tempArray;
						$i++;
					}
					$prev_product_id = $arr->product_id;
				}
				$result_val = $ArrFinal;
			}

			if (is_array($result_val) && count($result_val) > 0) {

				for ($i = 0; $i < count($result_val); $i++) {
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "image") {

							$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
						} else {
							$product_result[$key] = $value;
						}
					}
					$result[] = $product_result;
				}
			}
			$ArrData = $result;
			if (is_array($ArrData) && count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = $result;
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function get_home_category_product()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$data = array();
		$this->load->model('zipcodes_model');
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			if ($json_obj->zipcode != "") {

				$result_zipcode = $this->zipcodes_model->get_zipcode_by_data($json_obj->zipcode);

				if (count($result_zipcode) > 0) {

					$data = array(
						'zipcode' => $json_obj->zipcode,
					);
					$data['is_perisible_zipcode'] = $result[0]->can_deliver_perishable_products;
					$data['is_liker_zipcode'] = $result[0]->can_deliver_liker_products;
					$data['is_cook_food_zipcode'] = $result[0]->can_deliver_cook_food_products;

					$temp_result = $this->Products_model->get_home_category_product();					
				} else {
					$temp_result = $this->Products_model->get_home_category_product();
				}
			} else {
				$temp_result = $this->Products_model->get_home_category_product();
			}

			$ArrFinal = array();

			$i = 0;
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

			if (is_array($result_val) && count($result_val) > 0) {

				for ($i = 0; $i < count($result_val); $i++) {
					foreach ($result_val[$i] as $key => $value) {
						if ($key == "image") {

							$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;
						} else {
							$product_result[$key] = $value;
						}
					}
					$result[] = $product_result;
				}
			}
			$ArrData = $result;
			if (is_array($ArrData) && count($ArrData) > 0) {
				$success_message = '';
			} else {
				$errors = $result;
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}



	public function add_to_cart($id)

	{



		$data['product_id'] = $id;



		$data = $this->Products_model->get_product_by_id($data);



	}



	public function get_product_by_category_slug()

	{



		$json_str = file_get_contents('php://input');



		$json_obj = json_decode($json_str);







		$oauth_key = $json_obj->oauth_key;



		$errors = $success_message = '';



		$ArrData = array();



		if (check_oauth_key($oauth_key)) {





			$data = array();
			if ($json_obj->zipcode != "") {
				$result = $this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
				
				if (count($result) > 0) {
					
					$data = array(
						'zipcode' => $json_obj->zipcode
					);
					$data['is_perisible_zipcode'] = $result[0]->can_deliver_perishable_products;
					$data['is_liker_zipcode'] = $result[0]->can_deliver_liker_products;
					$data['is_cook_food_zipcode'] = $result[0]->can_deliver_cook_food_products;

					

					$result_val = $this->Products_model->get_product_by_category_slug($json_obj->category_slug, $data);

				} else {
					$result_val = $this->Products_model->get_product_by_category_slug($json_obj->category_slug, $data);
				}
			} else {
				$result_val = $this->Products_model->get_product_by_category_slug($json_obj->category_slug, $data);
			}





			if (count($result_val) > 0) {



				for ($i = 0; $i < count($result_val); $i++) {





					foreach ($result_val[$i] as $key => $value) {



						if ($key == "product_image") {



							$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;



						} else {



							$product_result[$key] = $value;



						}



					}



					$temp_result[] = $product_result;



				}



				$ArrFinal = array();



				$i = 0;



				$prev_product_id = 0;





				foreach ($temp_result as $arr) {



					if ($prev_product_id != $arr['product_id']) {



						$ArrFinal[$i] = $arr;



						$tempArray = array();



						foreach ($temp_result as $arr1) {



							if ($arr['product_id'] == $arr1['product_id']) {

								$t = array();

								if ($arr1['id'] > 0) {

									$t['size'] = $arr1['product_variant_size'];

									$t['price'] = $arr1['variant_price'];

									$t['variant_id'] = $arr1['id'];

									$t['is_out_of_stock'] = $arr1['variant_is_out_of_stock'];

								}

								$tempArray[] = $t;

							}



						}



						$ArrFinal[$i] = $arr;

						if (count($tempArray[0]) > 0) {

							$ArrFinal[$i]['product_size'] = $tempArray;

						} else {

							$ArrFinal[$i]['product_size'] = array();

						}



						$i++;



					}



					$prev_product_id = $arr['product_id'];



				}



				$result = $ArrFinal;

				//	echo "<pre>cc";print_r($result);



			} else {



				$result[] = "";



			}







			if (count($result) > 0) {



				$ArrData = $result;



				$success_message = '';



			} else {



				$errors = '';



			}



			send_response_to_api($ArrData, $errors, $success_message);



		}



	}



	public function get_related_product_by_category_slug()

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
		if (!empty($oauth_key) && (check_oauth_key($oauth_key))) {
    		$token_result = $this->db->get_where('tbl_users_token', ['access_token' => $oauth_key])->row();
            $suser_id = $token_result->user_id;
			$w_productlist = $this->home_model->get_wishlist_product_by_user($suser_id);

		}

		$errors = $success_message = '';



		$ArrData = array();


			$ExtraCondData = array();
			if($json_obj->zipcode != ""){
				
				$zipcodeData = $this->zipcodes_model->get_zipcode_by_data($json_obj->zipcode);
				if (count($zipcodeData) > 0) {
					if($zipcodeData[0]->can_deliver_perishable_products == "No" || $zipcodeData[0]->can_deliver_liker_products == "No" || $zipcodeData[0]->can_deliver_cook_food_products == "No"){

						$ExtraCondData['can_deliver_perishable_products'] = $zipcodeData[0]->can_deliver_perishable_products;
						$ExtraCondData['can_deliver_liker_products'] = $zipcodeData[0]->can_deliver_liker_products;
						$ExtraCondData['can_deliver_cook_food_products'] = $zipcodeData[0]->can_deliver_cook_food_products;
					}
				}
			}

			$result_val = $this->Products_model->get_related_product_by_category_slug($json_obj->category_slug, $json_obj->product_id, $ExtraCondData);
			//echo '<pre>';print_r($result_val);exit;

			if (count($result_val) > 0) {



				for ($i = 0; $i < count($result_val); $i++) {





					foreach ($result_val[$i] as $key => $value) {

						if ($key == "product_id") {
							$is_like = in_array($value, explode(',', $w_productlist)) ? 1 : 0;
						}
						$product_result['is_like'] = $is_like ?? 0;

						if ($key == "product_image") {



							$product_result[$key] = FILE_UPLOAD_PATH . 'products/' . $value;



						} else {



							$product_result[$key] = $value;



						}



					}



					$temp_result[] = $product_result;



				}



				$ArrFinal = array();



				$i = 0;



				$prev_product_id = 0;





				foreach ($temp_result as $arr) {



					if ($prev_product_id != $arr['product_id']) {



						$ArrFinal[$i] = $arr;



						$tempArray = array();

						$previous_variant_id = "";

						foreach ($temp_result as $arr1) {



							if ($arr['product_id'] == $arr1['product_id'] && $previous_variant_id != $arr1['id']) {

								$t = array();

								if ($arr1['id'] > 0) {

									$t['size'] = $arr1['product_variant_size'];

									$t['price'] = $arr1['variant_price'];

									$t['variant_id'] = $arr1['id'];

								}

								$tempArray[] = $t;

							}

							$previous_variant_id = $arr1['id'];

						}



						$ArrFinal[$i] = $arr;



						$ArrFinal[$i]['product_size'] = $tempArray;





						$i++;



					}



					$prev_product_id = $arr['product_id'];



				}



				$result = $ArrFinal;

				//	echo "<pre>cc";print_r($result);



			} else {



				$result[] = "";



			}







			if (count($result) > 0) {



				$ArrData = $result;



				$success_message = '';



			} else {



				$errors = '';



			}



			send_response_to_api($ArrData, $errors, $success_message);




	}



}