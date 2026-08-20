<?php

error_reporting(0);

class Controller_brands extends CI_Controller

{

    public function products_list($slug)

    {

        $data = array('url' => $slug);
        $this->load->model('Product_model');
        $find_category_meta = $this->Product_model->getCategoryMeta($slug);
        if(!empty($find_category_meta)){
            $headerdata = array('meta_title' => $find_category_meta[0]['meta_title'] ?? '','meta_description' => $find_category_meta[0]['meta_description'] ?? '');
            $this->load->view('common/header', $headerdata);
        }

        $this->load->view('brand-list', $data);

    }

    public function brand_list()

    {

        $this->load->view('brands');

    }



}