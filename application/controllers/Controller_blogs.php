<?php

error_reporting(0);

class Controller_blogs extends CI_Controller

{


    public function __construct() {
        parent::__construct();
        $this->load->model('blogs_model');
    }

    public function index() {
    $products = $this->db
    ->select('product_slug, modified_datetime')
    ->where('is_active', 1)
    ->order_by('modified_datetime', 'DESC')
    ->get('tbl_products')
    ->result();

$phtml = '';

foreach ($products as $product) {

    $lastmod = date('Y-m-d', strtotime($product->modified_datetime));
    $days_old = (time() - strtotime($product->modified_datetime)) / (60 * 60 * 24);

    if ($days_old <= 7) {
        $priority = "0.9";
        $changefreq = "daily";
    } elseif ($days_old <= 30) {
        $priority = "0.8";
        $changefreq = "weekly";
    } else {
        $priority = "0.6";
        $changefreq = "monthly";
    }

    $phtml .= '
    <url>
        <loc>'.base_url('product/'.$product->product_slug).'</loc>
        <lastmod>'.$lastmod.'</lastmod>
        <changefreq>'.$changefreq.'</changefreq>
        <priority>'.$priority.'</priority>
    </url>';
}

echo $phtml;
        exit;
        $this->load->view('blog-list');
    }

    public function fetch_blogs() {
        $limit = 6;
        $page = $this->input->post('page');
        $start = ($page - 1) * $limit;

        $data['blogs'] = $this->Blog_model->get_blogs($limit, $start);
        echo json_encode($data);
    }



}