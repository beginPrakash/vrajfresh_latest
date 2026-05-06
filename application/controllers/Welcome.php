<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Welcome extends CI_Controller

{



	public function index()

	{

		$headerdata = array('meta_title' => "Fresh Indian Groceries Delivered | New Jersey & New York | VrajFresh",'meta_description' => "Shop fresh vegetables, fruits, dairy & Indian groceries online. VrajFresh delivers same-day to 100+ zip codes across New Jersey & New York. Free delivery available — order now!");
        
        $this->load->view('common/header', $headerdata);
		$this->load->view('home');

	}

	public function test()

	{

		$this->load->view('test_home');

	}

}