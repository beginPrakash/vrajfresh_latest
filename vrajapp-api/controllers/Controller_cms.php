<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_Cms extends CI_Controller
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
		$this->load->model('cms_model');
    }
    public function get_cms()
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
				
				$result=$this->cms_model->get_cms($data);
			
			}
			else
			{
				$result=$this->cms_model->get_cms_by_search($data,$json_obj->search_keyword);
				
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
	public function add_cms()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'cms_title' => $json_obj->cms_title,
				'cms_description'  => $json_obj->cms_description,
				'created_by'   => $json_obj->created_by,
				'created_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);

			$result=$this->cms_model->add_cms($data);
			$ArrData = $result;
			if($result)
			{
				$success_message = 'CMS added successfully';
			}
			else
			{
				$errors = 'CMS Not Added Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_cms_by_id()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'cms_id'=>$json_obj->cms_id
			);
			$result=$this->cms_model->get_cms_by_id($data);
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
	public function update_cms()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$cms_id   = $json_obj->cms_id;
			$data=array(
				'cms_title' => $json_obj->cms_title,
				'cms_description'  => $json_obj->cms_description,
				'modified_by'   => $json_obj->modified_by,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'is_active' => $json_obj->is_active
			);
			$result=$this->cms_model->update_cms($data,$cms_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'CMS Updated Successfully';
			}
			else
			{
				$errors = 'CMS Not Updated Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function delete_cms()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$cms_id   = $json_obj->cms_id;
			$data=array(
				'is_active' => '0',
				'is_deleted' =>'1'
			);
			$result=$this->cms_model->update_cms($data,$cms_id);
			$ArrData = $result;
			if($result)
			{
				$ArrData=$result;
				$success_message = 'CMS Deleted Successfully';
			}
			else
			{
				$errors = 'CMS Not Deleted Successfully';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
		}
	}
	public function get_cms_by_slug()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
        $ArrData = array();
		if(check_oauth_key($oauth_key))
		{
			$data=array(
				'cms_slug'=>$json_obj->cms_url
			);
			$result=$this->cms_model->get_cms_by_slug($data);
		
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