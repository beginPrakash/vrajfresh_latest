<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);

class Controller_cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // header("Access-Control-Allow-Headers: content-type,Content-Type,X-Custom-Header, Upgrade-Insecure-Requests,Accept,x-requested-with");
        // header('Content-Type: application/json');
        // header('Access-Control-Allow-Credentials: true');
        // header('Access-Control-Max-Age: 60');
        // header('Access-Control-Allow-Headers: AccountKey,x-requested-with, Content-Type, content-type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2');
        // header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
		$this->load->helper('cookie');
        $this->load->model('users_model');
        if ($this->input->cookie('user_id') != null && $this->input->cookie('user_id') != "") {

            $session_data = array(
                'user_id' => $this->input->cookie('user_id'),
                'email' => $this->input->cookie('email'),
                'user_name' => $this->input->cookie('user_name'),
                'first_name' => $this->input->cookie('first_name'),
                'last_name' => $this->input->cookie('last_name'),
                'display_name' => $this->input->cookie('display_name'),
                'user_role_id' => $this->input->cookie('user_role_id'),
                'Is_login' => true
            );
            $this->session->set_userdata('logged_in', $session_data);
            if (!IsUserLogin()) {
                // return redirect(BASE_URL);
            }
        } else {
            // return redirect(BASE_URL);
        }
		$this->load->library('cart');
		$this->CI =& get_instance();
		$config = is_array($params) ? $params : array();
		$this->CI->load->driver('session', $config);
        error_reporting(0);

    }
    
	public function cart_detail_old()
    {
		$cartdata = $this->cart->contents();
        $this->load->view('cart');
    }

	public function cart_detail()
    {
		
		
		$contain = $this->cart->contents();
		//echo '<pre>';print_r($contain);exit;
		$Maincontain = $this->cart->contents();

		$zipcode = $_COOKIE['zipcode'];
		$CARTDATA = ""; 

		$current_date = date("Y-m-d");
		$date15DayAgo = date('Y-m-d', strtotime('-15 day', strtotime($current_date)));

		if(!empty($contain)){
			
			$CARTDATA = addslashes(json_encode($contain));
		}
		
		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		$SetCookieData = array();
		
		if($zipcode != ""){
			$url = API_URL . 'remove-zipcode-products';

			$data = array(
				"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
				"user_id" => $customer_id,
				"zipcode" => $zipcode,
				"cart" => $CARTDATA
			);

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'X-RapidAPI-Host: kvstore.p.rapidapi.com',
				'X-RapidAPI-Key: test',
				'Content-Type: application/json'
			]);
			$response = curl_exec($curl);
			//echo "<pre>zipcode response:- <pre>";print_r($response);exit;
			curl_close($curl);
			$response = json_decode($response, true);
			
			if($response['is_successful'] == 1){
				$products = $response['data'];
				$ZipcodeDetails = (isset($response['data2']['zipcodeData']) && !empty($response['data2']['zipcodeData'])) ? $response['data2']['zipcodeData'] : array();
				
				$SetCookieData = array(
					'minimum_order_value' => $ZipcodeDetails['minimum_order_value'],
					'is_deliver_perishable_products' => $ZipcodeDetails['can_deliver_perishable_products'],
					'delivery_type' => $ZipcodeDetails['delivery_types'],
					'delivery_days' => $ZipcodeDetails['delivery_days'],
					'delivery_area_name' => $ZipcodeDetails['area_name'],
					'delivery_state_id' => $ZipcodeDetails['state_id'],
					'zipcode_success_message' => 'Yes, We deliver in your area!',
					'zipcode_error_message' => '',
					'valid_zipcode' => 'TRUE',
				);

			} else {
				
				$SetCookieData = array(
					'minimum_order_value' => '',
					'is_deliver_perishable_products' => '',
					'delivery_type' => '',
					'delivery_days' => '',
					'delivery_area_name' => '',
					'delivery_state_id' => '',
					'zipcode_success_message' => '',
					'zipcode_error_message' => 'Sorry We do not deliver in your area.',
					'valid_zipcode' => 'FALSE',
				);
			}

			if(!empty($SetCookieData))
			{
				foreach($SetCookieData as $key => $value){
					setcookie($key, $value);
				}
			}

			$contain = $this->cart->contents();
		}
		//echo '<pre>';print_r($_COOKIE);exit;
		
		//echo '<pre>';print_r($contain);exit;
		$this->data['SetCookieData'] = $SetCookieData;
		$headerdata = array('meta_title' => "Your Cart | VrajFresh — Fresh Grocery Delivery NJ & NY",'meta_description' => "Review your cart and place your order for fresh Indian groceries, vegetables, fruits & dairy. Same-day delivery available across New Jersey and New York at VrajFresh.");
        
        $this->load->view('common/header', $headerdata);
		$this->load->view('cart', $this->data);
    }

	public function render_cart_detail()
	{
		$html = $this->load->view('render_cart', $data, true);
		
		$response = array(
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($response);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);
	}
    
	public function add()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);
		//echo "<pre>";print_r($json_obj);exit;
        $success_message = $errors = "";
        $ArrData = array();
        $oauth_key = $json_obj->oauth_key;
        if (check_oauth_key($oauth_key)) {
            $product_id = $json_obj->product_id;

            $product_name = str_replace("(", " - ", $json_obj->product_name);
            $product_name = str_replace(")", " - ", $product_name);


            $price = $json_obj->price;
            $quantity = $json_obj->quantity;
            $image = $json_obj->product_image;
            $weight = $json_obj->weight;
            $variant_id = $json_obj->variant_id;
            $product_slug = $json_obj->product_slug;
            $is_perisible = $json_obj->is_perisible;
            $product_tax = $json_obj->product_tax;
			
            
            $data = array(
                "id" => $product_id,
                "name" => $product_name,
                "image" => $image,
                "price" => $price,
                "qty" => $quantity,
                "product_slug" => $product_slug,
                "is_perisible" => $is_perisible,
                "product_tax" => $product_tax,
				'created_date' => date("Y-m-d"),
                "options" => array(
                    "weight" => $weight,
                    "variant_id" => $variant_id
                )
            );
			$product_id_found = false;
			foreach ($this->cart->contents() as $items)
			{
				$db_rowid = !empty($items['options']['db_rowid']) ? $items['options']['db_rowid'] : $items['rowid'];
				$cart_product_id = $items['id'];
				$cart_variant_id = $items['options']['variant_id'];
				if($cart_product_id==$product_id && $cart_variant_id==$variant_id && !$product_id_found)
				{
					$product_id_found = true;
					$qty = $items['qty'];
					$rowid = $db_rowid;
					$variant_id = $items['options']['variant_id'];
				}
			}
			//echo "<pre>";print_r($data);exit;
			if(!$product_id_found)
			{
				$result = $this->cart->insert($data);
			}
			else
			{
				$data = array(
					'rowid' => $rowid,
					'qty'   => $quantity,
					'created_date' => date("Y-m-d")
				);

				$result = $this->cart->update($data);
				$quantity = $quantity;
			}
			//$result = $this->cart->insert($data);
			
			//ADD CART IN DB TABLE START
			$customer_id = 0;
			if (isset($this->session->userdata['logged_in']['user_id'])) {
				$customer_id = $this->session->userdata['logged_in']['user_id'];
			}

			if($customer_id>0)
			{
				if(!$product_id_found)
				{
					$ArrCartItem = array(
					"customer_id" => $customer_id,
					"row_id" => $result,
					"id" => $product_id,
					"name" => $product_name,
					"image" => $image,
					"price" => $price,
					"qty" => $quantity,
					"product_slug" => $product_slug,
					"is_perisible" => $is_perisible,
					"product_tax" => $product_tax,
					"options_weight" => $weight,
					"options_variant_id" => $variant_id,
					);
					$url = API_URL . 'add-cart-item';
					$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrCartItem" => $ArrCartItem);
				}
				else
				{
					$url = API_URL . 'update-cart-item';
					$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "row_id" => $rowid, "qty" => $quantity);
				}
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => json_encode($data),
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					),
				));

				$response = curl_exec($curl);

				if(curl_errno($curl)){
					//echo 'Curl error: ' . curl_error($curl);
				}

				curl_close($curl);
				$response = json_decode($response);
			}
			//ADD CART IN DB TABLE END
			
            if ($result != "") {
                $ArrData[] = $result;
                $success_message = '';
            } else {
                $errors = 'No Data Available';
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }


    }

	public function get_cart_session_data()
	{
		$cartdata = $this->cart->contents();
		
		$errors = "";
		$success_message = "Cart Data";
		send_response_to_api($cartdata, $errors, $success_message);
	}

	public function login_cart_data_old()
    {
		$cart_data=$_SESSION['cart_contents'];
		if(!empty($cart_data)){
			$cart_total=$cart_data['cart_total'];
			$total_items=$cart_data['total_items'];
			unset($cart_data['cart_total'], $cart_data['total_items']);
			foreach($cart_data as $cart_id=>$cart_row){
				$row_id = $cart_id;
				$data = array(
					'rowid' => $row_id,
					'qty' => 0
				);
				$this->cart->update($data);
			}
		}
		

        $ArrData = array();
        $errors = array();
        $success_message = '';
        $oauth_key = $_POST['oauth_key'];
        $user_id = $_POST['user_id'];
        if (check_oauth_key($oauth_key)) {

			$query=$this->db->where('customer_id', $user_id )->get('tbl_cart_items');
			if($query->num_rows() > 0 )
			{
				foreach($query->result_array() as $cart_row){
					
					$product_id = $cart_row['id'];
					$product_name = $cart_row['name'];
					$price = $cart_row['price'];
					$quantity = $cart_row['qty'];
					$image = $cart_row['image'];
					$weight = $cart_row['options_weight'];
					$variant_id = $cart_row['variant_id'];
					$product_slug = $cart_row['options_variant_id'];
					$is_perisible = $cart_row['is_perisible'];
					$product_tax = $cart_row['product_tax'];
					$rowid = $cart_row['row_id'];
		
					
					$data = array(
						"id" => $product_id,
						"rowid" => $rowid,
						"name" => $product_name,
						"image" => $image,
						"price" => $price,
						"qty" => $quantity,
						"product_slug" => $product_slug,
						"is_perisible" => $is_perisible,
						"product_tax" => $product_tax,
						"options" => array(
							"weight" => $weight,
							"variant_id" => $variant_id
						)
					);
					$product_id_found = false;
					foreach ($this->cart->contents() as $items)
					{
						$cart_product_id = $items['id'];
						$cart_variant_id = $items['options']['variant_id'];
						if($cart_product_id==$product_id && $cart_variant_id==$variant_id && !$product_id_found)
						{
							$product_id_found = true;
							$qty = $items['qty'];
							$rowid = $items['rowid'];
							$variant_id = $items['options']['variant_id'];
						}
					}
					//echo "<pre>";print_r($data);exit;
					if(!$product_id_found)
					{
						$result = $this->cart->insert($data);
					}
					else
					{
						$data = array(
						'rowid' => $rowid,
						'qty'   => $qty + 1
						);
						$result = $this->cart->update($data);
						$quantity = $quantity + 1;
					}
					//$result = $this->cart->insert($data);
					$ArrData[] = $result;
				}
			}
			
            send_response_to_api($ArrData, $errors, $success_message);
        }


    }

	public function save_user_cart_data(){
		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		$oauth_key = $_POST['oauth_key'];

		$newArr = $this->cart->contents();
		$user_cart_data = $this->cart->contents();
		//save user data in cart
		$url = API_URL . 'add-ucart-item';

		$payload = json_encode([
			"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"customer_id"=>$customer_id,
			"cart_data" => $user_cart_data // IMPORTANT: not empty
		]);

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => [
				'Content-Type: application/json'
			],
		]);

		$response = curl_exec($curl);
		curl_close($curl);
		$success_message = "Product Setup";
		$errors = "";
		send_response_to_api($response, $errors, $success_message);
	}

	public function login_cart_data()
	{
		$customer_id = 0;

		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}

		$oauth_key = $_POST['oauth_key'];

		if (check_oauth_key($oauth_key) && $customer_id > 0) {

			// ✅ DO NOT unset session manually ❌
			// $this->session->unset_userdata('cart_contents');

			// ✅ CLEAR cart properly
			$this->cart->destroy();

			// ✅ Fetch DB cart
			$query = $this->db->where('customer_id', $customer_id)
							->get('tbl_cart_items');

			if ($query->num_rows() > 0) {

				foreach ($query->result_array() as $cart_row) {

					$product_id = $cart_row['id'];
					$variant_id = $cart_row['options_variant_id'];
					$quantity   = (int)$cart_row['qty'];

					$found = false;
					$rowid = '';

					// ✅ Check existing cart item
					foreach ($this->cart->contents() as $item) {
						if (
							$item['id'] == $product_id &&
							isset($item['options']['variant_id']) &&
							$item['options']['variant_id'] == $variant_id
						) {
							$found = true;
							$rowid = $item['rowid'];
							break;
						}
					}

					if ($found) {

						// ✅ UPDATE
						$this->cart->update([
							'rowid' => $rowid,
							'qty'   => $quantity
						]);

					} else {

						// ✅ INSERT
						$this->cart->insert([
							'id'    => $product_id,
							'qty'   => $quantity,
							'price' => (float)$cart_row['price'],
							'name'  => $cart_row['name'],
							'product_tax'  => $cart_row['product_tax'],
							'is_perisible' => $cart_row['is_perisible'],
							'options' => [
								'variant_id' => $variant_id,
								'weight'     => $cart_row['options_weight'],
								'image'      => $cart_row['image'],
								'product_slug' => $cart_row['product_slug'],
								'is_perisible' => $cart_row['is_perisible'],
								'product_tax'  => $cart_row['product_tax'],
								'created_date' => $cart_row['created_date'],
								'db_rowid' => $cart_row['row_id']
							]
						]);
					}
				}
			}
		}

		$contain = $this->cart->contents();
		$new_contain = [];

		foreach ($contain as $key => $item) {

		
			// get db_rowid if available
			if (isset($item['options']['db_rowid']) && !empty($item['options']['db_rowid'])) {
				$db_rowid = $item['options']['db_rowid'];
			} else {
				$db_rowid = $key; // fallback
			}

			// ✅ replace rowid for output only
			$item['rowid'] = $db_rowid;

			// 🔥 IMPORTANT: use DB rowid as ARRAY KEY
			$new_contain[$db_rowid] = $item;
		}

		$new_contain['total_items'] = $this->cart->total_items();
		$new_contain['cart_total']  = $this->cart->total();


		$success_message = "Product Setup";
		$errors = "";

		send_response_to_api($new_contain, $errors, $success_message);
	}

	public function load_cart_data()
	{
		
		$html = $this->load->view('render_cart', $data, true);

		$newresponse = array(
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($newresponse);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);
		
        return true;

	}

    public function total_items()
    {
        $result = $this->cart->total_items();
        $success_message = $errors = '';
        $ArrData = array();
        $ArrData[] = $result;
        send_response_to_api($ArrData, $errors, $success_message);
    }

    public function remove()
    {
        $row_id = $_POST["row_id"];
        $data = array(
            'rowid' => $row_id,
            'qty' => 0
        );
		$this->cart->update($data);
		
		//DELETE ITEM FROM CART IN DB TABLE START
		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		
		if($customer_id>0)
		{
			$url = API_URL . 'delete-cart-item';
			$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "row_id" => $row_id);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'X-RapidAPI-Host: kvstore.p.rapidapi.com',
				'X-RapidAPI-Key: test',
				'Content-Type: application/json'
			]);
			$response = curl_exec($curl);
			curl_close($curl);
			$response = json_decode($response);
		}
		//DELETE ITEM FROM CART IN DB TABLE END
		$html = $this->load->view('render_cart', $data, true);
		
		$newresponse = array(
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($newresponse);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);
		
        return true;

    }

	public function remove_new()
    {
        $row_id = $_POST["row_id"];
		$new_row_id = $_POST['new_row_id'];
        $cartData = array(
            'rowid' => $row_id,
            'qty' => 0
        );
		$this->cart->update($cartData);

		/* $data = $this->cart->contents();
		echo '<pre>';print_r($data);exit; */
		
		//DELETE ITEM FROM CART IN DB TABLE START
		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		
		if($customer_id>0)
		{
			$url = API_URL . 'delete-new_cart-item';
			$data = array(
				"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
				"user_id" => $customer_id,
				"new_row_id" => $new_row_id,
				"row_id" => $row_id
			);
			$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		// if ($response === false) {
		//     echo 'Curl error: ' . curl_error($curl);
		// } else {
		//     echo $response;
		// }

		curl_close($curl);

			$response = json_decode($response);
		}
		//DELETE ITEM FROM CART IN DB TABLE END
		$html = $this->load->view('render_cart', $data, true);

		$newresponse = array(
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($newresponse);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);
		
        return true;

    }

    public function update()
    {
        $data = array();
        for ($i = 0; $i < count($_POST['row_id']); $i++) {
            $row_id = $_POST['row_id'][$i];
            $qty = $_POST['qty'][$i];
            $data['rowid'] = $_POST['row_id'][$i];
            $data['qty'] = $_POST['qty'][$i];
			$data['created_date'] = date("Y-m-d");
            $this->cart->update($data);
			
			//DELETE ITEM FROM CART IN DB TABLE START
			$customer_id = 0;
			if (isset($this->session->userdata['logged_in']['user_id'])) {
				$customer_id = $this->session->userdata['logged_in']['user_id'];
			}
			if($customer_id>0)
			{
				$url = API_URL . 'update-cart-item';
				$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "row_id" => $row_id, "qty" => $qty);
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($curl, CURLOPT_HTTPHEADER, [
					'X-RapidAPI-Host: kvstore.p.rapidapi.com',
					'X-RapidAPI-Key: test',
					'Content-Type: application/json'
				]);
				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response);
			}
			//DELETE ITEM FROM CART IN DB TABLE END
        }
		
		$html = $this->load->view('render_cart', $data, true);
		
		$response = array(
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($response);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);
		
        return true;

    }

	public function new_update()
    {
        $data = array();
        for ($i = 0; $i < count($_POST['row_id']); $i++) {
			
			$data['rowid'] = $_POST['row_id'][$i];
            $data['qty'] = $_POST['qty'][$i];
			$data['created_date'] = date("Y-m-d");
            $this->cart->update($data);
		}
		$data = array();
		$row_id = $_POST['row_id'];
		$new_row_id = $_POST['new_row_id'];
		$qty = $_POST['qty'];
		$data['rowid'] = $_POST['row_id'];
		$data['qty'] = $_POST['qty'];
		
		//DELETE ITEM FROM CART IN DB TABLE START
		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		if($customer_id>0)
		{
			$url = API_URL . 'update-new-cart-item';
			$data = array(
				"oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
				"user_id" => $customer_id,
				"new_row_id" => $new_row_id,
				"row_id" => $row_id,
				"qty" => $qty
			);
			$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => json_encode($data),
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json'
					),
				));

				$response = curl_exec($curl);

				if(curl_errno($curl)){
					//echo 'Curl error: ' . curl_error($curl);
				}

				curl_close($curl);
			$response = json_decode($response);
		}
		//DELETE ITEM FROM CART IN DB TABLE END        
		
		$html = $this->load->view('render_cart', $data, true);
		
		$response = array(
			'html' => $html
		);
	
		// Encode the array to JSON
		$json_response = json_encode($response);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);
		
        return true;

    }

	public function clear_cart_data()
	{
		$this->cart->destroy();
		$html = $this->load->view('render_cart', $data, true);
		
		$response = array(
			'html' => $html
		);

		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		if($customer_id > 0)
		{
			$url = API_URL . 'clear-cart-data';
			$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "customer_id" => $customer_id);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'X-RapidAPI-Host: kvstore.p.rapidapi.com',
				'X-RapidAPI-Key: test',
				'Content-Type: application/json'
			]);
			$response = curl_exec($curl);
			//print_r($response);exit;
			
			curl_close($curl);
			$response = json_decode($response);	
		}
	
		// Encode the array to JSON
		$json_response = json_encode($response);
	
		// Set the content type to JSON
		$this->output->set_content_type('application/json');
	
		// Output the JSON response
		$this->output->set_output($json_response);
		
        return true;
	}
}