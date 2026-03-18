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
			unset($Maincontain['cart_total'], $Maincontain['total_items']);
			foreach ($Maincontain as $key => $item){
				if($item['created_date'] < $date15DayAgo){
					unset($contain[$key]);
				}
			}
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
				$this->cart->destroy();
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

				
				
				if(!empty($products)){
					
					unset($products['cart_total'], $products['total_items']);
					
					if(!empty($products)){
						foreach ($products as $key => $value){
							
							$newCartItem = array(
								"id" => $value['id'],
								"name" => $value['name'],
								"image" => $value['image'],
								"price" => $value['price'],
								"qty" => $value['qty'],
								"product_slug" => $value['product_slug'],
								"is_perisible" => $value['is_perisible'],
								"product_tax" => $value['product_tax'],
								"created_date" => $value['created_date'],
								"options" => array(
									"weight" => $value['options']['weight'],
									"variant_id" => $value['options']['variant_id']
								),
								"rowid" => $key,
								"subtotal" => $value['subtotal'],
							);
							$this->cart->insert($newCartItem);
						}
					}
				}
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
					'qty'   => $quantity,
					'created_date' => date("Y-m-d")
				);
				$result = $this->cart->update($data);
				$quantity = $quantity + $qty;
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
		$cart_total = 0.00;
		$total_items = 0;
		if(!empty($cartdata)){
			unset($cartdata['cart_total'], $cartdata['total_items']);
		}
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

	public function login_cart_data()
    {
		
		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		$oauth_key = $_POST['oauth_key'];

		$newArr = $_SESSION['cart_contents'];
		$CARTDATA = $this->cart->contents();
		$cart_total_items = 0;
		$new_cart_total = 0.00;
		$CutomerCart = 0;
		if (check_oauth_key($oauth_key) && $customer_id > 0) {
			
			$query = $this->db->where('customer_id', $customer_id )->get('tbl_cart_items');
			
			if($query->num_rows() > 0)
			{
				$CutomerCart = 1;
				
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
						'created_date' => $cart_row['created_date'],
						"options" => array(
							"weight" => $weight,
							"variant_id" => $variant_id
						)
					);
					
					$CARTDATA[$rowid]['id'] = $product_id;
					$CARTDATA[$rowid]['name'] = $product_name;
					$CARTDATA[$rowid]['image'] = $image;
					$CARTDATA[$rowid]['price'] = $price;
					$CARTDATA[$rowid]['qty'] = $quantity;
					$CARTDATA[$rowid]['product_slug'] = $product_slug;
					$CARTDATA[$rowid]['is_perisible'] = $is_perisible;
					$CARTDATA[$rowid]['product_tax'] = $product_tax;
					$CARTDATA[$rowid]['created_date'] = $cart_row['created_date'];
					$CARTDATA[$rowid]['options']['weight'] = $weight;
					$CARTDATA[$rowid]['options']['variant_id'] = $variant_id;
					
					$cart_total_items += $quantity;
					$new_cart_total += $price;

					$product_id_found = false;
					$quantity_update = false;
					foreach ($CARTDATA as $items)
					{
						$cart_product_id = $items['id'];
						$cart_variant_id = $items['options']['variant_id'];
						if($cart_product_id==$product_id && $cart_variant_id==$variant_id && !$product_id_found)
						{
							$product_id_found = true;
							/* $qty = $items['qty'];
							
							if($qty !=  $quantity){
								$quantity_update = true;
							} */
							$rowid = $items['rowid'];
							$variant_id = $items['options']['variant_id'];
						}
					}

					if(!$product_id_found)
					{
						$this->cart->insert($data);
					}
					else
					{
						$data = array(
							'rowid' => $rowid,
							'qty'   => $quantity,
						);
						if($quantity_update == true){
							$data['created_date'] = date("Y-m-d");
						}
						$this->cart->update($data);
					}


				}
			}
		}
		if($CutomerCart > 0){
			
			$CARTDATA['total_items'] = $cart_total_items;
			$CARTDATA['cart_total'] = $new_cart_total;
			//$this->cart->destroy();

			$new_expiration_time = time() + CART_SESSION_EXPIRATION_TIME; // 15 days added
			$this->session->set_userdata('cart_contents', $CARTDATA);
			$this->CI->session->set_userdata('cart_contents' , $CARTDATA);
			$contain = $this->cart->contents();
		}
		
		$contain = $this->cart->contents();
		$Maincontain = $this->cart->contents();
		
		$current_date = date("Y-m-d");
		$date15DayAgo = date('Y-m-d', strtotime('-15 day', strtotime($current_date)));

		$zipcode = $_COOKIE['zipcode'];
		$CARTDATA = ""; 
		if(!empty($contain)){
			unset($Maincontain['cart_total'], $Maincontain['total_items']);
			foreach ($Maincontain as $key => $item){
				if($item['created_date'] < $date15DayAgo){
					unset($contain[$key]);
				}
			}
			$CARTDATA = addslashes(json_encode($contain));
		}
		
		$total_items = 0;
		$cart_total = 0.00;
		
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
			//echo "<pre>zipcode response:- <pre>";print_r($response);
			
			curl_close($curl);
			$response = json_decode($response, true);
			//echo "<pre>zipcode response2:- <pre>"; print_r($response);
			
			//echo $response['is_successful'];exit;
			if($response['is_successful'] == 1){
				
				$this->cart->destroy();
				
				$products = $response['data'];
				
				if(!empty($products)){
					
					unset($products['cart_total'], $products['total_items']);
					
					if(!empty($products)){

						foreach ($products as $key => $value){
							
							$newCartItem = array(
								"id" => $value['id'],
								"name" => $value['name'],
								"image" => $value['image'],
								"price" => $value['price'],
								"qty" => $value['qty'],
								"product_slug" => $value['product_slug'],
								"is_perisible" => $value['is_perisible'],
								"product_tax" => $value['product_tax'],
								"created_date" => $value['created_date'],
								"options" => array(
									"weight" => $value['options']['weight'],
									"variant_id" => $value['options']['variant_id']
								),
								"rowid" => $key,
								"subtotal" => $value['subtotal'],
							);
							$total_items += $value['qty'];
							$cart_total += $value['subtotal'];
							$this->cart->insert($newCartItem);
						}
					}
				}
			}
        } else {
			$contain = $this->cart->contents();
			foreach ($contain as $key => $item){
				
				$total_items += $item['qty'];
				$cart_total += $item['subtotal'];
			}
		}
		
		$contain = $this->cart->contents();
		if(!empty($contain)){
			if($total_items > 0){
				$contain['total_items'] = $total_items;
				$contain['cart_total'] = $cart_total;
			}
		}
		$success_message = "Product Setup";
		$errors = "";
		send_response_to_api($contain, $errors, $success_message);

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
        $data = array(
            'rowid' => $row_id,
            'qty' => 0
        );
		$this->cart->update($data);

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