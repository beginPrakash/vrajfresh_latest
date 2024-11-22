<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
class Controller_checkout extends CI_Controller
{

	public function index()
	{
		$url = API_URL . 'get-state-by-country-id';
		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "geo_id" => 'US');
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'X-RapidAPI-Host: kvstore.p.rapidapi.com',
			'X-RapidAPI-Key: test',
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		curl_close($curl);

		$response = json_decode($response);
		$ArrState = array();
		$ArrStateOption = array();
		$ArrStateOption['0'] = "--State--";

		if ($response->is_successful == 1) {
			$ArrState = $response->data;
			if (count($ArrState) > 0) {
				foreach ($ArrState as $row) {
					$ArrStateOption[$row->state_id] = $row->state;
				}
			}
		}


		$ArrUserData['ArrStateOption'] = $ArrStateOption;
		$this->load->view('checkout', $ArrUserData);
	}
	public function checkout_process()
	{
		$ArrCustomer = array();
		$ArrCustomer = $_POST;
		if (isset($_POST['ship_to_different_address']) && $_POST['ship_to_different_address'] == "Yes") {
		} else {
			$ArrCustomer['shiping_street_address'] = $ArrCustomer['street_address'];
			$ArrCustomer['shiping_apartment'] = $ArrCustomer['apartment'];
			$ArrCustomer['shiping_city'] = $ArrCustomer['city'];
			$ArrCustomer['shiping_state'] = $ArrCustomer['state'];
			$ArrCustomer['shiping_zip_code'] = $ArrCustomer['zip_code'];
			$ArrCustomer['shiping_phone'] = $ArrCustomer['phone'];
		}
		if (isset($_POST['tip']) && $_POST['tip'] == "on") {
			$ArrCustomer['tip_amount'] = $_POST['tip_amount'];
		} else {
			$ArrCustomer['tip_amount'] = 0;
		}
		$ArrCustomer['user_id'] = 1;
		$ArrCustomer['created_by'] = 1;
		$ArrCustomer['billing_country'] = 1;
		$ArrCustomer['shipping_country'] = 1;
		$ArrProduct = array();
		foreach ($this->cart->contents() as $items) {
			$Arr = array();
			$Arr['product_id'] = $items["id"];
			$Arr['product_variant_id'] = 0; /*$items["product_variant_id"];*/
			$Arr['product_weight_gms'] = 0; /*$items["product_variant_id"];*/
			$Arr['qty'] = $items["qty"];
			$Arr['total_amount'] = $items["qty"] * $items["price"];
			$Arr['unit_price'] = $items["price"];
			$Arr['product_name'] = $items["name"];
			$Arr['created_by'] = 1;
			$Arr['is_active'] = 0;
			$ArrProduct[] = $Arr;
		}

		$url = API_URL . 'controller_orders/add_order';
		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrCustomer" => $ArrCustomer, "ArrProduct" => $ArrProduct);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'X-RapidAPI-Host: kvstore.p.rapidapi.com',
			'X-RapidAPI-Key: test',
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);
		curl_close($curl);

		$response = json_decode($response);
		if ($response->is_successful == 1) {
			$order_id = $response->data;

			/*edirect payment page*/
			$this->payment($order_id);
		} else {
			$this->order_failed();
		}
	}
	function payment($order_id)
	{
		$Arrdata['order_id'] = $order_id;
		$this->load->view('order_payment_form', $Arrdata);
	}
	function payment_process()
	{
		$order_id = $_POST['order_id'];
		$ArrPayment = $_POST;
		$url = API_URL . 'stripe/payment_process';
		$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrPayment);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'X-RapidAPI-Host: kvstore.p.rapidapi.com',
			'X-RapidAPI-Key: test',
			'Content-Type: application/json'
		]);
		$response = curl_exec($curl);

		curl_close($curl);
		$response = json_decode($response);
		if (isset($response->is_successful) && $response->is_successful == 1) {
			$original_response = json_encode($response);
			$ArrPayment['transaction_notes'] = $original_response;
			$ArrPayment['payment_gateway_transaction_id'] = $ArrPayment['data']->id;
			$ArrPayment['transaction_status'] = $ArrPayment['data']->status;
			$ArrPayment['transaction_amount'] = $ArrPayment['data']->amount;
			$ArrPayment['order_id'] = $order_id;

			$url = API_URL . 'stripe/add_payment_details';
			$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrPayment);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'X-RapidAPI-Host: kvstore.p.rapidapi.com',
				'X-RapidAPI-Key: test',
				'Content-Type: application/json'
			]);
			$response = curl_exec($curl);

			curl_close($curl);


			/*edirect to thank you page*/
			$this->order_success($order_id);
		} else {
			$this->order_failed();
		}
	}
	function order_success($order_id)
	{
		$Arrdata['order_id'] = $order_id;
		$this->load->view('order_success', $Arrdata);
	}
	function order_failed()
	{
		$Arrdata = array();
		$this->load->view('order_failed', $Arrdata);
	}
}