<?php

error_reporting(0);

class Controller_blogs extends CI_Controller

{


    public function __construct() {
        parent::__construct();
        $this->load->model('blogs_model');
    }

    public function index()
    {
        $limit = 2;

        // Get current page from URL
        $page = $this->input->get('page');
        $page = ($page) ? (int)$page : 1;

        $offset = ($page - 1) * $limit;

        // Total records
        $total = $this->blogs_model->count_all();

        // Fetch blogs
        $data['blogs'] = $this->blogs_model->get_blogs($limit, $offset);

        // Pagination calculation
        $data['total_pages'] = ceil($total / $limit);
        $data['current_page'] = $page;

        $this->load->view('blog-list', $data);
    }

    public function fetch_blogs() {
        $limit = 6;
        $page = $this->input->post('page');
        $start = ($page - 1) * $limit;

        $data['blogs'] = $this->blogs_model->get_blogs($limit, $start);
        echo json_encode($data);
    }



}