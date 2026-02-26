<?php

error_reporting(0);

class Controller_blogs extends CI_Controller

{


    public function __construct() {
        parent::__construct();
        $this->load->model('blogs_model');
    }

    public function index() {
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