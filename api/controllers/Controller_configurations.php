<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_configurations extends CI_Controller
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
		$this->load->model('configurations_model');
    }
    public function get_configurations()
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
				
				$result=$this->configurations_model->get_configurations($data);
			
			}
			else
			{
				$result=$this->configurations_model->get_configurations_by_search($data,$json_obj->search_keyword);
				
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
	public function add_configurations()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'configuration_type' => $json_obj->configuration_type,
				'configuration_key'  => $json_obj->configuration_key,
                'configuration_value'=> $json_obj->configuration_value,
                'additional_configuration'=>$json_obj->additional_configurations, 
				'created_by'   => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result=$this->configurations_model->add_configuration($data);
			$ArrData = $result;
			if($result)
			{
				$success_message = 'Configuration added successfully';
			}
			else
			{
				$errors = 'Configuration Not Added Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_configuration_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'configuration_id'=>$json_obj->configuration_id
			);
			$result=$this->configurations_model->get_configuration_by_id($data);
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
	public function update_configuration()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$configuration_id   = $json_obj->configuration_id;
			$data=array(
				'configuration_type' => $json_obj->configuration_type,
				'configuration_key'  => $json_obj->configuration_key,
                'configuration_value'=> $json_obj->configuration_value,
                'additional_configuration'=>$json_obj->additional_configurations,
				'modified_by'   => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result=$this->configurations_model->update_configuration($data,$configuration_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'Configuration Updated Successfully';
			}
			else
			{
				$errors = 'Configuration Not Updated Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function delete_configuration()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$configuration_id   = $json_obj->configuration_id;
			$data=array(
				'is_active' => '0',
				'is_deleted' =>'1'
			);
			$result=$this->configurations_model->update_configuration($data,$configuration_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'Configuration Deleted Successfully';
			}
			else
			{
				$errors = 'Configuration Not Deleted Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_configuration_by_key()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'configuration_key'=>$json_obj->configuration_key
			);
			$result=$this->configurations_model->get_configuration_by_key($data);
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