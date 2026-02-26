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
        $this->load->model('Product_model');
        $product_meta = $this->Product_model->getProductDetail($slug);
        //echo '<pre>';print_r($this->cart->contents());exit;
        $is_out_of_stock = $product_meta[0]['is_out_of_stock'] ?? '';
        $data = [];
        $data['url'] = $slug;
        $data['meta_title'] = $product_meta[0]['meta_title'] ?? '';
        $data['meta_description'] = $product_meta[0]['meta_description'] ?? '';
        $data['health_benefits'] = $product_meta[0]['health_benefits'] ?? '';
        $data['ingredients'] = $product_meta[0]['ingredients'] ?? '';
        $data['usage_instructions'] = $product_meta[0]['usage_instructions'] ?? '';
        $data['storage_information'] = $product_meta[0]['storage_information'] ?? '';
        $data['faqs'] = $product_meta[0]['faqs'] ?? '';
        $data['product_description'] = $product_meta[0]['product_description'] ?? '';
        $data['product_image'] = $product_meta[0]['product_image'] ?? '';
        $data['product_name'] = $product_meta[0]['product_name'] ?? '';
        $data['rating_value'] = $product_meta[0]['rating_value'] ?? '';
        $data['review_count'] = $product_meta[0]['review_count'] ?? '';
        $data['product_sku'] = $product_meta[0]['product_sku'] ?? '';
        $data['product_price'] = $product_meta[0]['sale_price'] ?? '';
        $data['is_stock'] = ($is_out_of_stock == 1) ? 'InStock' : 'OutStock';
        
        $data['brand_name'] = $this->Product_model->getBrandname($product_meta[0]['brand_id']) ?? '';
        
        $headerdata = array('meta_title' => $product_meta[0]['meta_title'] ?? '','meta_description' => $product_meta[0]['meta_description'] ?? '');
        
        $this->load->view('common/header', $headerdata);
        $this->load->view('product-detail', $data);
        

    }

}