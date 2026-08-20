<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Controller_cms extends CI_Controller

{

    public function terms_condition()

    {

        $this->load->view('terms-conditions');

    }

    public function privacy_policy()

    {

        $this->load->view('privacy-policy');

    }

    public function refund_policy()

    {

       $data = array('url' => 'refund-and-return-policy');
        $this->load->view('cms', $data);
    }

    public function new_products()

    {
        $data = array('url' => 'new-products');
        $this->load->view('cms', $data);
    }

    public function shipping_policy()

    {
        $data = array('url' => 'shipping');
        $this->load->view('cms', $data);
    }

    public function about_us()

    {
        $data = array('url' => 'about-us');
        $this->load->view('cms', $data);
    }

    public function privacy_statement()

    {
        $data = array('url' => 'privacy-statement');
        $this->load->view('cms', $data);
    }

    public function promotions()

    {
        $data = array('url' => 'promotions');
        $this->load->view('cms', $data);
    }

    public function terms_conditions()

    {
        $data = array('url' => 'terms-conditions');
        $this->load->view('cms', $data);
    }

    public function get_cms_by_slug($url)

    {

        $data = array('url' => $url);

        $this->load->view('cms', $data);

    }

    public function contact()
    {
        $headerdata = array('meta_title' => "Contact VrajFresh | Indian Grocery Store Saddle Brook, NJ | 201-688-7887",'meta_description' => "Get in touch with VrajFresh. Visit us at 449 Market Street, Saddle Brook, NJ or call 201-688-7887. We're open Mon–Sun, 10AM–9PM. Online orders & delivery across NJ & NY.");
        
        $this->load->view('common/header', $headerdata);
        $this->load->view('contact');
    }

}