<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_filters extends CI_Controller
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
        $this->load->model('filters_model');
    }
    public function get_category_filters()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);

        $oauth_key = $json_obj->oauth_key;
        $errors = $success_message = '';
        $ArrData = array();
        $category_result = "";
        if (check_oauth_key($oauth_key)) {
            $data = array(
                'category_slug' => $json_obj->category_slug
            );
            $result = $this->filters_model->get_category_by_slug($data);


            if (count($result) > 0) {
                $ArrData = $result;
                $success_message = '';
            } else {
                $errors = 'No data available';
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }
    }
}