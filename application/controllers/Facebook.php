<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Facebook extends CI_Controller
{
    public function index()
    {
        require_once 'vendor/autoload.php';
        $facebook_url = facebook_login();

        header("location:" . $facebook_url);
    }
}