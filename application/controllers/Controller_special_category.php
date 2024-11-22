<?php
error_reporting(0);
class Controller_special_category extends CI_Controller
{

    public function get_special_category_list($slug)
    {
        $data = array('special_category_slug' => $slug);
        $this->load->view('special-category-list', $data);
    }
}