<?php
error_reporting(0);
class Controller_tags extends CI_Controller
{
    public function products_list($id)
    {
        $data = array('id' => $id);
        $this->load->view('tag-products', $data);
    }


}