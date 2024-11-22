<?php
error_reporting(0);
class Controller_brands extends CI_Controller
{
    public function products_list($slug)
    {
        $data = array('url' => $slug);
        $this->load->view('brand-list', $data);
    }
    public function brand_list()
    {
        $this->load->view('brands');
    }

}