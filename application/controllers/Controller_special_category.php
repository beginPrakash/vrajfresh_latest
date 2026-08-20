<?php
error_reporting(0);
class Controller_special_category extends CI_Controller
{

    public function get_special_category_list($slug)
    {
        $data = array('special_category_slug' => $slug);
        $this->load->model('Product_model');
        $find_category_meta = $this->Product_model->getCategoryMeta($slug);
        if(!empty($find_category_meta)){
            $headerdata = array('meta_title' => $find_category_meta[0]['meta_title'] ?? '','meta_description' => $find_category_meta[0]['meta_description'] ?? '');
            $this->load->view('common/header', $headerdata);
        }
        $this->load->view('special-category-list', $data);
    }
}