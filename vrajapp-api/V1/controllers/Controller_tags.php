<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_tags extends CI_Controller
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
		$this->load->model('tags_model');
    }
    public function get_tags()
    {
        $json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

        $oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'is_active' => $json_obj->is_active_only,
				'search_keyword' => $json_obj->search_keyword,
				'limit' => $json_obj->limit,
				'page_no' => $json_obj->page_no,
				'sort_column' => $json_obj->sort_column,
				'sort_order' => $json_obj->sort_order
			);
			//var_dump($json_obj->search_keyword);exit;
			if($json_obj->search_keyword == "")
			{
				
				$result=$this->tags_model->get_tags($data);
			
			}
			else
			{
				$result=$this->tags_model->get_tags_by_search($data,$json_obj->search_keyword);
				
			}
			if(count($result) > 0)
			{
					$ArrData=$result;
					$success_message = '';
			}
			else
			{
					$errors = 'No Data Available';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}

    }
	public function add_tag()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'tag' => $json_obj->tag,
				'created_by'   => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result=$this->tags_model->add_tag($data);
			$ArrData = $result;
			if($result)
			{
				$success_message = 'Tag added successfully';
			}
			else
			{
				$errors = 'Tag Not Added Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_tag_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'tag_id'=>$json_obj->tag_id
			);
			$result=$this->tags_model->get_tag_by_id($data);
			$ArrData = $result;
			if(count($result) > 0)
			{
				$ArrData=$result;
				$success_message = '';
			}
			else
			{
				$errors = 'No data available';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function update_tag()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$tag_id   = $json_obj->tag_id;
			$data=array(
				'tag' => $json_obj->tag,
				'modified_by'   => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result=$this->tags_model->update_tag($data,$tag_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'Tag Updated Successfully';
			}
			else
			{
				$errors = 'Tag Not Updated Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function delete_tag()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$tag_id   = $json_obj->tag_id;
			$data=array(
				'is_active' => '0',
				'is_deleted' =>'1'
			);
			$result=$this->tags_model->update_tag($data,$tag_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'Tag Deleted Successfully';
			}
			else
			{
				$errors = 'Tag Not Deleted Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_product_by_tag_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		$brand_result="";
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'tag_id'=>$json_obj->tag_id
			);
			$tag_result=$this->tags_model->get_tag_by_id($data);
			
			$result["tag"]=$tag_result[0];
			$temp_result=$this->tags_model->get_product_by_tag_id($data['tag_id']);
			
			$ArrFinal = array();
						$i = 0;
						$prev_product_id = 0;
						foreach ($temp_result as $arr) {
							
							if ($prev_product_id != $arr->product_id) {

								$ArrFinal[$i] = $arr;

								$tempArray = array();
                                $previous_variant_id="";
								foreach ($temp_result as $arr1) {

									if ($arr->product_id == $arr1->product_id && $previous_variant_id !=$arr1->id)								
									{
										$t = array();
										if($arr1->id > 0)
										{
											$t['size'] = $arr1->product_variant_size;
											$t['price'] = $arr1->variant_price;
											$t['variant_id'] = $arr1->id;
										}
										$tempArray[] = $t;
										$previous_variant_id=$arr1->id;
									}
								}

								$ArrFinal[$i] = $arr;

								$ArrFinal[$i]->product_size = $tempArray;

								$i++;

							}

							$prev_product_id = $arr->product_id;
						}
						$product_result = $ArrFinal;
			
			for($i=0;$i < count($product_result);$i++)
				{
					//$result=array();
					foreach($product_result[$i] as $key=>$value)
					{
						 if($key == "product_image")
						 {
							$products[$key] = FILE_UPLOAD_PATH.'products/'.$value;
						 }
						 else 
						 {
							$products[$key] = $value;
						 }
						 
					}
					$result['products'][]=$products;
				}
			
			//$filter_result=$this->categories_model->get_category_filter($product_result[0]->product_id);
			 foreach($product_result as $products)
			 {
			  	$product_id[]=$products->product_id;
			 }
			$result['product_id']=$product_id;
			//$ArrData = $result;
			if(count($result) > 0)
			{
				$ArrData=$result;
				$success_message = '';
			}
			else
			{
				$errors = 'No data available';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
}