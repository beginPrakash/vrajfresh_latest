<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

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
        $this->load->model('cart_model');
		$this->load->model('Master_model', 'master');
    }
    public function add()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);
        $success_message = $errors = "";
        $ArrData = array();
        $oauth_key = $json_obj->oauth_key;
        if (check_oauth_key($oauth_key)) {
            $product_id = $json_obj->product_id;
            $product_name = $json_obj->product_name;
            $price = $json_obj->price;
            $quantity = $json_obj->quantity;

            $this->load->library('cart');
            $data = array(
                "id" => $product_id,
                "name" => $product_name,
                "price" => $price,
                "qty" => $quantity


            );
            $result = $this->cart->insert($data);

            if ($result != "") {
                $ArrData[] = $result;
                $success_message = '';
            } else {
                $errors = 'No Data Available';
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }


    }

    public function total_items()
    {
        $this->load->library('cart');
        $result = $this->cart->total_items();
        $success_message = $errors = '';
        $ArrData = array();
        $ArrData[] = $result;
        send_response_to_api($ArrData, $errors, $success_message);
    }
	public function add_cart()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
			$ArrCartData = array(
				'customer_id' => trim($json_obj->ArrCartItem->customer_id),
				'row_id' => trim($json_obj->ArrCartItem->row_id),
				'id' => trim($json_obj->ArrCartItem->id),
				'name' => trim($json_obj->ArrCartItem->name),
				'image' => trim($json_obj->ArrCartItem->image),
				'price' => trim($json_obj->ArrCartItem->price),
				'qty' => trim($json_obj->ArrCartItem->qty),
				'product_slug' => trim($json_obj->ArrCartItem->product_slug),
				'created_date' => date("Y-m-d"),
				'is_perisible' => trim($json_obj->ArrCartItem->is_perisible),
				'product_tax' => trim($json_obj->ArrCartItem->product_tax),
				'options_weight' => trim($json_obj->ArrCartItem->options_weight),
				'options_variant_id' => trim($json_obj->ArrCartItem->options_variant_id),
			);
			$this->cart_model->add($ArrCartData);
			} catch (Exception $e) {
				$ArrData = array();
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_cart()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$row_id = $json_obj->row_id;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
		$this->cart_model->delete($row_id);
		} catch (Exception $e) {
				$ArrData = array();
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function delete_new_cart()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$row_id = $json_obj->row_id;
		$user_id = (isset($json_obj->user_id)) ? $json_obj->user_id : 0;
		$new_row_id = (isset($json_obj->new_row_id)) ? $json_obj->new_row_id : '';
		$errors = $success_message = '';
		$ArrData = array();
		
		if (check_oauth_key($oauth_key)) {
			try {
			
				if($user_id != "" && $user_id > 0){
					$this->master->deleteRow('tbl_cart_items', array('customer_id' => $user_id, 'id' => $new_row_id));
				} else {
					$this->cart_model->delete($row_id);
				}
				
			} catch (Exception $e) {
				$ArrData = array();
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	
	public function update_cart()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$row_id = $json_obj->row_id;
		$qty = $json_obj->qty;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
			$ArrCartData = array(
				'qty' => trim($qty),
				'created_date' => date("Y-m-d")
			);
			$this->cart_model->update($ArrCartData,$row_id);
			} catch (Exception $e) {
				$ArrData = array();
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function update_new_cart()
	{
		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$row_id = $json_obj->row_id;
		$user_id = (isset($json_obj->user_id)) ? $json_obj->user_id : 0;
		$new_row_id = (isset($json_obj->new_row_id)) ? $json_obj->new_row_id : array();
		$qty = $json_obj->qty;
		$errors = $success_message = '';
		$ArrData = array();
		/* $success_message = "SDFSDd";
		send_response_to_api($new_row_id, $errors, $success_message); */
		
		if (check_oauth_key($oauth_key)) {
		
			for ($i = 0; $i < count($row_id); $i++) {
				try {
					
					$ArrCartData = array(
						'qty' => trim($qty[$i]),
						'created_date' => date("Y-m-d")
					);
					//$datay = $this->cart_model->update($ArrCartData,$row_id);
					
					$this->master->update_detail('tbl_cart_items', $ArrCartData, array('customer_id' => $user_id, 'id' => $new_row_id[$i]));
				} catch (Exception $e) {
					$ArrData = array();
					$errors = 'No Data Available';
				}
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}

	public function clear_cart_data()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$customer_id = $json_obj->customer_id;
		
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
				$this->cart_model->delete_all($customer_id);
				$success_message = "Cart Clear";
			} catch (Exception $e) {
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	
	public function get_cart()
	{

		$json_str = file_get_contents('php://input');
		$json_obj = json_decode($json_str);
		$oauth_key = $json_obj->oauth_key;
		$customer_id = $json_obj->customer_id; 
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
				
			$ArrData = $this->cart_model->get_cart_by_customer($customer_id);
			} catch (Exception $e) {
				$ArrData = array();
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
}