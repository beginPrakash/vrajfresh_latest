<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_cms extends CI_Controller
{
    public function terms_condition()
    {
        $this->load->view('terms-conditions');
    }
    public function privacy_policy()
    {
        $this->load->view('privacy-policy');
    }
    public function refund_policy()
    {
        $this->load->view('refund-policy');
    }
    public function get_cms_by_slug($url)
    {
        $data = array('url' => $url);
        $this->load->view('cms', $data);
    }
}