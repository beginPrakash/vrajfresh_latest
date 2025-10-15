<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
    }
    public function get_zipcodes()
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
				
				$result=$this->zipcodes_model->get_zipcodes($data);
			
			}
			else
			{
				$result=$this->zipcodes_model->get_zipcodes_by_search($data,$json_obj->search_keyword);
				
			}
           // echo $this->db->last_query();exit;
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
	public function add_zipcode()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
                'zipcode' => $json_obj->zipcode,
				'state_id' => $json_obj->state_id,
				'minimum_order_value' => $json_obj->minimum_order_value,
				'can_deliver_perishable_products' => $json_obj->can_deliver_perishable_products,
				'delivery_types' => $json_obj->delivery_types,
				'created_by'   => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result=$this->zipcodes_model->add_zipcode($data);
			$ArrData = $result;
			if($result)
			{
				$success_message = 'Zipcode added successfully';
			}
			else
			{
				$errors = 'Zipcode Not Added Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_zipcode_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'zipcode_id'=>$json_obj->zipcode_id
			);
			$result=$this->zipcodes_model->get_zipcode_by_id($data);
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
	public function update_zipcode()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$zipcode_id   = $json_obj->zipcode_id;
			$data=array(
                'zipcode'=> $json_obj->zipcode,
				'state_id' => $json_obj->state_id,
                'minimum_order_value' => $json_obj->minimum_order_value,
				'can_deliver_perishable_products' => $json_obj->can_deliver_perishable_products,
				'delivery_types' => $json_obj->delivery_types,
				'modified_by'   => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result=$this->zipcodes_model->update_zipcode($data,$zipcode_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'Zipcode Updated Successfully';
			}
			else
			{
				$errors = 'Zipcode Not Updated Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function delete_zipcode()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$zipcode_id   = $json_obj->zipcode_id;
			$data=array(        
				'is_active' => '0',
				'is_deleted' =>'1'
			);
			$result=$this->zipcodes_model->update_zipcode($data,$zipcode_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'Zipcode Deleted Successfully';
			}
			else
			{
				$errors = 'Zipcode Not Deleted Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_zipcode_detail()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'zipcode'=>$json_obj->zipcode
			);
			$result=$this->zipcodes_model->get_zipcode_by_data($data['zipcode']);
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
}