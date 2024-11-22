<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_login extends CI_Controller
{

    public function __construct()
	{
		parent::__construct();
		$this->module_name = 'login';
		if (IsUserLogin()) {
			redirect('/');
		}
	}
    
    public function login()
    {
        $this->load->view('login_form');
    }
    
    public function signup()
    {
        $this->load->view('signup_form');
    }

    public function index()
    {
        error_reporting(0);
        $oauth_key = $_POST['oauth_key'];
        $user_role_id = $_POST['user_role_id'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $url = API_URL . "login";
        // echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'oauth_key=' . $oauth_key . '&user_role_id=' . $user_role_id . '&user_name=' . $email . '&password=' . $password);
        // echo 'oauth_key='.$oauth_key.'&user_role_id='.$user_role_id.'&user_name='.$email.'&password='.$password;

        $result = curl_exec($ch);
        $final_result = json_decode($result);
        // echo "<pre>";print_r($result);exit;
        // echo "<pre>";print_r($final_result);exit;
        // exit;
        if ($final_result->is_successful > 0) {
            $session_data = array(
                'user_id' => $final_result->data[0][0]->user_id,
                'email' => $final_result->data[0][0]->email,
                'user_name' => $final_result->data[0][0]->user_name,
                'first_name' => $final_result->data[0][0]->first_name,
                'last_name' => $final_result->data[0][0]->last_name,
                'display_name' => $final_result->data[0][0]->display_name,
                'user_role_id' => $final_result->data[0][0]->user_role_id,
                'Is_login' => true
            );
            $this->session->set_userdata('logged_in', $session_data);
			
			//GET CART ITEM FROM THE CART
			echo "hello";exit;
			//GET CART ITEM FROM THE END
			
            return redirect(BASE_URL . 'my-address');
        } else {
            return redirect(BASE_URL);
        }
        curl_close($ch);
    }
    public function user_activate($guid)
    {
        $url = API_URL . "user-activate";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'guid=' . $guid);
        $result = curl_exec($ch);
        $final_result = json_decode($result);
        //echo "<pre>";print_r($final_result);exit;

        if ($final_result->is_successful > 0) {
            $this->session->set_flashdata('success_message', 'Account Activated');
            return redirect(BASE_URL);
        }

        curl_close($ch);
    }
    public function contact()
    {
        $this->load->view('contact');
    }
}