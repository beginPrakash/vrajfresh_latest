<?php
error_reporting(0);
class Controller_search_products extends CI_Controller
{
    public function products_list()
    {
        $search_term["product_search"] = $_GET["search"];
        $this->load->view('product-list', $search_term);
    }

}