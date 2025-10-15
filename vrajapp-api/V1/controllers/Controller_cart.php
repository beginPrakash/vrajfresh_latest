<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_cart extends CI_Controller
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
    public function add()
    {
        $json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
        $success_message=$errors="";
        $ArrData=array();
		$oauth_key = $json_obj->oauth_key;
        if(check_oauth_key($oauth_key))
		{
            $product_id = $json_obj->product_id;
            $product_name = $json_obj->product_name;
            $price = $json_obj->price;
            $quantity=$json_obj->quantity;

            $this->load->library('cart');
            $data=array(
                "id" =>  $product_id,
                "name" =>  $product_name,
                "price" =>$price,
                "qty" => $quantity


            );
           $result= $this->cart->insert($data);
            
            if($result != "")
			{
					$ArrData[]=$result;
					$success_message = '';
			}
			else
			{
					$errors = 'No Data Available';	
			}
			send_response_to_api($ArrData,$errors,$success_message);
        }


    }
   
    public function total_items()
    {
        $this->load->library('cart');
        $result=$this->cart->total_items();
        $success_message = $errors='';
        $ArrData=array();
        $ArrData[]=$result;
        send_response_to_api($ArrData,$errors,$success_message);
    }
}