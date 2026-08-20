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
		$json_obj = json_decode($json_str,true);
		$errors = $success_message = '';
		$ArrData = array();
		
		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}
		$user_cart_data = $json_obj['cart_data'];

		if (check_oauth_key($oauth_key)) {
			if(count($user_cart_data) > 0){
				foreach($user_cart_data as $key => $val){
					$row_id = bin2hex(random_bytes(16));
					$is_product_addtocart = $this->cart_model->is_product_addtocart($val['product_id'],$json_obj['customer_id'],$val['variant_id'] ?? '');
					if(empty($is_product_addtocart)){
						$ArrCartData = array(
							'customer_id' => trim($json_obj['customer_id']),
							'row_id' => trim($row_id),
							'id' => trim($val['product_id']),
							'name' => trim($val['product_name']),
							'image' => trim($val['product_image']),
							'price' => trim($val['price']),
							'qty' => trim($val['quantity']),
							'product_slug' => trim($val['product_slug']),
							//'created_date' => date("Y-m-d"),
							'is_perisible' => trim($val['is_perisible']),
							'product_tax' => trim($val['product_tax']),
							'options_weight' => trim($val['weight'] ?? ''),
							'options_variant_id' => trim($val['variant_id'] ?? ''),
						);
						$this->cart_model->add($ArrCartData);
					}else{
						$ArrCartData = array(
							'customer_id' => trim($json_obj['customer_id']),
							'row_id' => trim($row_id),
							'id' => trim($val['product_id']),
							'name' => trim($val['product_name']),
							'image' => trim($val['product_image']),
							'price' => trim($val['price']),
							'qty' => trim($val['quantity']),
							'product_slug' => trim($val['product_slug']),
							//'created_date' => date("Y-m-d"),
							'is_perisible' => trim($val['is_perisible']),
							'product_tax' => trim($val['product_tax']),
							'options_weight' => trim($val['weight'] ?? ''),
							'options_variant_id' => trim($val['variant_id'] ?? ''),
						);
						$this->cart_model->update_usercart_item($ArrCartData,$is_product_addtocart);
					}
				}
			}
			$ArrData['cart_data'] = $user_cart_data;
			$success_message = 'Cart item added successfully.';
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
	public function delete_cart()
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
		$cart_item_id = $json_obj->cart_item_id;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
		$this->cart_model->delete($cart_item_id);
		$success_message = 'Cart Item deleted successfully';
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
		
		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}

		$cart_item_id = $json_obj->cart_item_id;
		$quantity = $json_obj->quantity;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
			$ArrCartData = array(
				'qty' => trim($quantity)
			);
			$this->cart_model->update($ArrCartData,$cart_item_id);
			$success_message = 'Cart Item updated successfully';
			} catch (Exception $e) {
				$ArrData = array();
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}



	public function clear_cart_data()
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

		// Get Bearer Token
		$authHeader = $this->input->get_request_header('Authorization', TRUE);
		if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
			$oauth_key = $matches[1];
		} else {
			$oauth_key = '';
		}

		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {
			try {
			$customer_id = $this->cart_model->userid_by_token($oauth_key);	
			//delete sold out product start
			$this->db->query("DELETE c FROM tbl_cart_items c
			LEFT JOIN tbl_products p ON p.product_id = c.id
			LEFT JOIN tblproduct_variant v ON v.id = c.options_variant_id
			WHERE c.customer_id = $customer_id
			AND ( c.qty <= 0
				OR (
				c.options_variant_id IS NOT NULL
				AND c.options_variant_id != 0
				AND (
					v.id IS NULL
					OR v.is_out_of_stock = 0
				)
			)
			OR (
				(c.options_variant_id IS NULL OR c.options_variant_id = 0)
				AND (
					p.product_id IS NULL
					OR p.is_out_of_stock = 0
				)
			)
			)");
			//delete sold out product end
			
			$ArrData = $this->cart_model->get_cart_by_customer($customer_id);
			$success_message = 'Data listed successfully';
			} catch (Exception $e) {
				$ArrData = array();
				$errors = 'No Data Available';
			}
			send_response_to_api($ArrData, $errors, $success_message);
		}
	}
}