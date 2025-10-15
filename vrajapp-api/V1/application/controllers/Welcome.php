<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	
	public function test()
	{
		$stripeToken = 'tok_1NbeZQLcxTbE8GoYP4pChMKU';
		$paymentIntentId = 'pi_3NbeZQLcxTbE8GoY1ifbG8DN';
		$amount = 2518;
		/* Call method in Helper */
		$payment_details = stripe_payment_capture($stripeToken, $paymentIntentId, $amount);
		echo "<pre>";print_r($payment_details);exit;
		$capture_payment_stripe_raw_response = json_encode($payment_details);
	}
	public function index()
	{
		// echo date('Y-m-d H:i:s').' '.date_default_timezone_get();
		$this->load->view('welcome_message');
	}
}