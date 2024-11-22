<?php

class Controller_cronjob extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('order_model');
		$this->load->model('order_product_model');
		$this->load->model('common_model');
		$this->load->model('product_model');
		$this->load->model('transactions_model');
		$this->load->model('user_model');

	}

	/* AUTO CAPTURE PAYMENT */
	public function capture_auto_payment()
	{
		$searchString = 'payment_intent_id IS NOT NULL AND  (amount_received_status!="succeeded" OR amount_received_status IS NULL) AND order_datetime > now() - interval 48 hour';
		$ArrOrders = $this->order_model->getOrderQueryString($searchString);
		
		foreach($ArrOrders as $arr)
		{
			$order_id = $arr['order_id'];
			$stripeToken = $arr['payment_intent_id'] . "-" . $arr['order_id'];
			$payment_intent_id = $arr['payment_intent_id'];
			
			$blocked_amount = $arr['blocked_amount'] * 100;

			$url = API_URL . 'stripe/payment_capture';

			$ArrPayment = array('stripeToken' => $stripeToken, 'payment_intent_id' => $payment_intent_id, 'amount_to_capture' => $blocked_amount);
			$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrPayment);
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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
			if ($response === false || $response == null) {
				$error = curl_error($curl);
				echo 'cURL error: ' . $error;
			} else {
				echo 'Response: ' . $response;
			}

			$response = json_decode($response);


			curl_close($curl);

			$ArrData = $response->data;
			$flag = false;


			if (isset($ArrData) && $ArrData->status == 'succeeded') {
				$capture_payment_stripe_raw_response = json_encode($ArrData);
				$amount_received = $ArrData->amount_received;
				$int_amount_received = (int) $amount_received;
				$order_data = array(
					'amount_received' => trim($int_amount_received) / 100,
					'amount_received_status' => 'succeeded',
					'order_status'  => 'Payment Processed',
					'capture_payment_stripe_raw_response' => $capture_payment_stripe_raw_response,
					'capture_payment_datetime' => date('Y-m-d H:i:s'),
				);

				$this->order_model->update($order_id, $order_data);

				//add log
				$ArrTransactionLog = array(
					"stripe_raw_response" => $capture_payment_stripe_raw_response,
					"payment_intent_id" => $ArrData->id,
					"payment_intent_status" => $ArrData->status,
					"order_id" => $order_id,
					"payment_process_type" => "Automatic",
					"transaction_amount" => $amount_received / 100,
					"transaction_datetime" => date("Y-m-d H:i:s"),
					"created_datetime" => date("Y-m-d H:i:s")
				);
				$this->transactions_model->add($ArrTransactionLog);

				$flag = true;




			} else {
				$amount_received = $ArrData->amount_received;
				$int_amount_received = (int) $amount_received;
				$order_data = array(
					'amount_received' => trim($int_amount_received) / 100,
					'amount_received_status' => 'failed',
				);

				$this->order_model->update($order_id, $order_data);
			}
		}
	}
}