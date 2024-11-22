<?php

error_reporting(0);

class Controller_products extends CI_Controller

{

    public function products_list($slug)

    {

        $data = array('url' => $slug);

        $this->load->view('product-list', $data);

    }

    public function product_details($slug)

    {

        //echo '<pre>';print_r($this->cart->contents());exit;
        $data = array('url' => $slug);

        $this->load->view('product-detail', $data);

    }

}