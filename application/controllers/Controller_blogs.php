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
        $limit = 10;

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

     public function details($slug = ''){
        $blog = $this->blogs_model->get_blog_by_slug($slug);

        $data['blog'] = $blog;
        $headerdata = array('meta_title' => $blog['meta_title'] ?? '','meta_description' => $blog['meta_descriptions'] ?? '');
        
        $this->load->view('common/header', $headerdata);
        $this->load->view('blog-detail', $data);
    }



}