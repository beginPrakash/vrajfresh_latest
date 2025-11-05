<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . '../vendor/autoload.php');

class StripeController extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		
		//error_reporting(0);
	}

    // Create PaymentIntent (called via AJAX)
    public function createIntent() {

        $stripe = new \Stripe\StripeClient('sk_test_51HwVYoBuuwY7F4Wu04xdyEiBlOfjlPA3IuInEyodvTDJhmw2D4JihC37hK1fgN6OFwbJJ3oEXeogGRiF86eQlW5H00lAffHJXO');


        try {
            // retrieve JSON from POST body
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            // Create a PaymentIntent with amount and currency
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => calculateOrderAmount($jsonObj->items),
                'currency' => 'usd',
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'capture_method' => 'automatic', // or 'manual'
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }


    }
}
