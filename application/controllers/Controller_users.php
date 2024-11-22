<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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

    }


    public function address()
    {
        $ArrStateOption = getStates(); /* CALL COMMON HELPER FUNCTION */
        $ArrUserData['ArrStateOption'] = $ArrStateOption;
        $this->load->view('address', $ArrUserData);
    }
    public function contact()
    {
        $this->load->view('contact');
    }
    public function forgot_password()
    {
        $this->load->view('forgot_password');
    }
    public function change_password()
    {
        $this->load->view('change_password');
    }
    public function profile()
    {
        $this->load->view('profile');
    }
    public function edit_account()
    {
        $this->load->view('edit_account');
    }

    public function validate_login(){
        $json_str = json_encode($_POST);
		$json_obj = json_decode($json_str);

		$oauth_key = $json_obj->oauth_key;
		$errors = $success_message = '';
		$ArrData = array();
		if (check_oauth_key($oauth_key)) {

			#check if email is exist or not
			$check_email = $this->users_model->check_email($json_obj->email);
			if (count($check_email) > 0) {
				$data = array(
					'user_name' => $json_obj->email,
					'password' => $json_obj->password,
					'user_role_id' => $json_obj->user_role_id
				);
				$result = $this->users_model->check_user($data);
				if (count($result) > 0) {
					$ArrData = $result;
					$ArrData['test']=json_decode($json_obj->cart_data,true);
					$ArrData['test1']=$_SESSION['cart_contents'];
					$success_message = 'Login Successfully';
				} else {
					$errors = 'Please enter valid login details.';
				}
			} else {
				$errors = 'Your account is not registered with Vraj Fresh. Please create an account.';
			}

			send_response_to_api($ArrData, $errors, $success_message);
		}
    }
    public function login_sucess()
    {
        print_r('test');
        exit;
		//GET CART IN DB TABLE START
		$customer_id = 0;
		if (isset($this->session->userdata['logged_in']['user_id'])) {
			$customer_id = $this->session->userdata['logged_in']['user_id'];
		}
		
		if($customer_id>0)
		{
			
			//--------------------------------------- ADD SESSION PRODUCT FIRST START ---------------------------------------
			if (count($this->cart->contents()) > 0)
			{
				foreach ($this->cart->contents() as $items)
				{
					$ArrCartItem = array(
					"customer_id" => $customer_id,
					"row_id" => $items['rowid'],
					"id" => $items['id'],
					"name" => $items['name'],
					"image" => $items['image'],
					"price" => $items['price'],
					"qty" => $items['qty'],
					"product_slug" => $items['product_slug'],
					"is_perisible" => $items['is_perisible'],
					"product_tax" => $items['product_tax'],
					"options_weight" => $items['options']['weight'],
					"options_variant_id" => $items['options']['variant_id'],
					);
					
					$url = API_URL . 'add-cart-item';
					$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrCartItem" => $ArrCartItem);
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
			}
			$this->cart->destroy();
			//--------------------------------------- ADD SESSION PRODUCT FIRST END ---------------------------------------
			
			$url = API_URL . 'get-cart-item';
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
			curl_close($curl);
			$response = json_decode($response);
			$ArrCartItems = $response->data;
			$this->load->library('cart');
			if(is_array($ArrCartItems) && count($ArrCartItems)>0)
			{
				foreach($ArrCartItems as $item)
				{					
					$ArrCartData = array(
						"id" => $item->id,
						"name" => $item->name,
						"image" => $item->image,
						"price" => $item->price,
						"qty" => $item->qty,
						"product_slug" => $item->product_slug,
						"is_perisible" => $item->is_perisible,
						"product_tax" => $item->product_tax,
						"options" => array(
							"weight" => $item->options_weight,
							"variant_id" => $item->options_variant_id
						)
					);
					$result = $this->cart->insert($ArrCartData);
					
					$ArrCartItem = array(
						"customer_id" => $customer_id,
						"row_id" => $result,
						"id" => $item->id,
						"name" => $item->name,
						"image" => $item->image,
						"price" => $item->price,
						"qty" => $item->qty,
						"product_slug" => $item->product_slug,
						"is_perisible" => $item->is_perisible,
						"product_tax" => $item->product_tax,
						"options_weight" => $item->options_weight,
						"options_variant_id" => $item->options_variant_id,
					);
					
					$url = API_URL . 'add-cart-item';
					$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrCartItem" => $ArrCartItem);
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
			}
			
		}
		//GET CART IN DB TABLE END
        return redirect(BASE_URL.'my-orders');
    }
    public function orders()
    {
        $this->load->view('orders');
    }
    public function orders_detail($order_id)
    {
        $data['order_id'] = $order_id;
        $this->load->view('order_view', $data);
    }
    public function change_password_process()
    {
        $oauth_key = $_POST['oauth_key'];
        $user_role_id = $_POST['user_role_id'];
        $user_id = $_POST['user_id'];
        $email = $_POST['user_name'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        if ($this->change_password_validation()) {
            $url = API_URL . 'change-password';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'oauth_key=' . $oauth_key . '&user_role_id=' . $user_role_id . '&user_name=' . $email . '&current_password=' . $current_password . '&new_password=' . $new_password . '&user_id=' . $user_id);
            $result = curl_exec($ch);
            $final_result = json_decode($result);
            if ($final_result->is_successful > 0) {
                $this->session->set_flashdata('success_message', $final_result->success_message);
                return redirect(BASE_URL . 'change-password?is_success=1');
            } else {
                $this->session->set_flashdata('error_message', $final_result->errors);
                return redirect(BASE_URL . 'change-password?is_error=1');
            }
        } else {
            $this->load->view('change_password');
        }

    }
    public function edit_profile()
    {

        $oauth_key = $_POST['oauth_key'];
        $user_role_id = $_POST['user_role_id'];
        $user_id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $display_name = $_POST['display_name'];

        if ($this->edit_user_validation()) {
            $url = API_URL . 'update-user';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'oauth_key=' . $oauth_key . '&user_role_id=' . $user_role_id . '&first_name=' . $first_name . '&last_name=' . $last_name . '&display_name=' . $display_name . '&user_id=' . $user_id);
            $result = curl_exec($ch);

            $final_result = json_decode($result);

            if ($final_result->is_successful > 0) {
                $session_data = array('first_name' => $first_name, 'last_name' => $last_name, 'display_name' => $display_name, );
                $this->session->set_userdata('logged_in', $session_data);

                setcookie('first_name', $first_name, time() + (86400 * 30), "/");
                setcookie('last_name', $last_name, time() + (86400 * 30), "/");
                setcookie('display_name', $display_name, time() + (86400 * 30), "/");

                $this->session->set_flashdata('success_message', $final_result->success_message);
                redirect(BASE_URL . 'my-account?is_success=1');
            } else {
                $this->session->set_flashdata('error_message', $final_result->errors);
                redirect(BASE_URL . 'my-account?is_error=1');
            }
        } else {
            $this->load->view('edit_account');
        }
    }
    public function change_password_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
        if ($this->form_validation->run()) {
            return true;
        } else {
            return false;
        }
    }
    public function edit_user_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('display_name', 'Display Name', 'required');
        if ($this->form_validation->run()) {
            return true;
        } else {
            return false;
        }
    }
    public function report_order()
    {
        if (!IsUserLogin()) {
            return redirect(BASE_URL);
        } else {
            $this->load->view('report_order');
        }
    }
    public function special_request()
    {
        if (!IsUserLogin()) {
            return redirect(BASE_URL);
        } else {
            $this->load->view('request_special');
        }
    }
    public function logout()
    {
        delete_cookie('user_id');
        delete_cookie('email');
        delete_cookie('first_name');
        delete_cookie('last_name');
        delete_cookie('display_name');
        delete_cookie('user_name');
        delete_cookie('address');
        delete_cookie('address2');
        delete_cookie('city');
        delete_cookie('user_role_id');
        delete_cookie('Is_login');
        $this->session->unset_userdata('logged_in');

        $this->session->sess_destroy();
        redirect(BASE_URL);
    }
}