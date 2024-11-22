<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_orders extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Headers: content-type,Content-Type,X-Custom-Header, Upgrade-Insecure-Requests,Accept,x-requested-with");
        header('Content-Type: application/json');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 60');
        header('Access-Control-Allow-Headers: AccountKey,x-requested-with, Content-Type, content-type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        $this->load->model('orders_model');
    }
    public function get_orders()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);
        $oauth_key = $json_obj->oauth_key;
        $errors = $success_message = '';
        $ArrData = array();
        if (check_oauth_key($oauth_key))
        {
            try
            {
                $data = array(
                    'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active_only))) ,
                    'search_keyword' => $json_obj->search_keyword,
                    'limit' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->limit))) ,
                    'page_no' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->page_no))) ,
                    'sort_column' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '_', $json_obj->sort_column))) ,
                    'sort_order' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->sort_order)))
                );
                if ($json_obj->search_keyword == "")
                {
                    $result = $this
                        ->orders_model
                        ->get_orders($data);
                }
                else
                {
                    $result = $this
                        ->orders_model
                        ->get_orders_by_search($data, $json_obj->search_keyword);
                }
                if (count($result) > 0)
                {
                    $ArrData = $result;
                    $success_message = '';
                }
                else
                {
                    $errors = 'No Data Available';
                }
            }
            catch(Exception $e)
            {
                $ArrData = "There is problem";
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }
    }
    public function add_order()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);
		echo "<pre>";print_r($json_obj);exit;
        $oauth_key = $json_obj->oauth_key;
		
        $errors = $success_message = '';
        $ArrData = array();
        if (check_oauth_key($oauth_key))
        {
            $data = array(
                'user_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->user_id))) ,
                'order_datetime' => date('Y-m-d H:i:s') ,
                'order_status' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', 'processing'))) ,
                'order_notes' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_notes))) ,
                'billing_first_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_first_name))) ,
                'billing_last_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_last_name))) ,
                'billing_street_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_street_name))) ,
                'billing_apartment_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_apartment_name))) ,
                'billing_city' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_city))) ,
                'billing_state_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_state_id))) ,
                'billing_zipcode' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_zipcode))) ,
                'billing_country' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_country))) ,
                'billing_phone' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_phone))) ,
                'billing_email' => trim(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_email)) ,
                'shipping_first_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_first_name))) ,
                'shipping_last_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_last_name))) ,
                'shipping_street_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_street_name))) ,
                'shipping_apartment_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_apartment_name))) ,
                'shipping_city' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_city))) ,
                'shipping_state_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_state_id))) ,
                'shipping_zipcode' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_zipcode))) ,
                'shipping_country' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_country))) ,
                'shipping_phone' => trim(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_phone)) ,
                'shipping_email' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_email))) ,
                'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->created_by))) ,
                'created_datetime' => date('Y-m-d H:i:s') ,
                'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active)))
            );
            $result = $this
                ->orders_model
                ->add_order($data, 'tbl_orders');
            $total_weight = 0;
            foreach ($json_obj->product_data as $product)
            {
                $total_weight += trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_weight_gms * $product->qty)));
                $order_product_data = array(
                    "order_id" => $result,
                    "product_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_id))) ,
                    "product_variant_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_variant_id))) ,
                    "qty" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->qty))) ,
                    'product_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->product_name))) ,
                    'unit_price' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->unit_price))) ,
                    'total_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->total_amount))) ,
                    'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->created_by))) ,
                    'created_datetime' => date('Y-m-d H:i:s') ,
                    'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active)))
                );
                $result_product_id = $this
                    ->orders_model
                    ->add_order($order_product_data, 'tbl_order_products');
            }
			
            $fedex_shipping_charge = get_rate_by_weight($json_obj->qty, $json_obj->dimension, $total_weight, $json_obj->shipping_city, $json_obj->stateOrProvinceCode, $json_obj->shipping_zipcode, 'US');
            if ($fedex_shipping_charge
                ->output
                ->rateReplyDetails[0]
                ->ratedShipmentDetails[0]->totalNetFedExCharge != null)
            {
                $shipping_charge = $fedex_shipping_charge
                    ->output
                    ->rateReplyDetails[0]
                    ->ratedShipmentDetails[0]->totalNetFedExCharge;
            }
            else
            {
                return $fedex_shipping_charge->errors[0]->message;
            }
            $order_total_amount = $json_obj->order_price + $shipping_charge + $json_obj->order_tip + $json_obj->order_total_tax - $json_obj->discount_amount;
			
            $track_id = get_ship();
            if ($track_id
                ->output
                ->transactionShipments[0]->masterTrackingNumber != null)
            {
                $fedex_tracking_id = $track_id
                    ->output
                    ->transactionShipments[0]->masterTrackingNumber;
            }
            else
            {
                return $track_id->errors[0]->message;
            }
            $order_data = array(
                'order_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_price))) ,
                'fedex_tracking_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $fedex_tracking_id))) ,
                'fedex_shipping_charge' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $shipping_charge))) ,
                'order_tip' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_tip))) ,
                'order_total_tax' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_total_tax))) ,
                'coupon_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->coupon_id))) ,
                'discount_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->discount_amount))) ,
                'order_total_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $order_total_amount))) ,
            );
            $this
                ->orders_model
                ->update_order($order_data, $result, 'tbl_orders');
            $ArrData = $result;
            if ($result)
            {
                $success_message = 'Order added successfully';
                $users = $this
                    ->orders_model
                    ->get_users_data($result);
                $subject = "Order Place Successfully";
                $order_content = file_get_contents('templates/order_successful.html');
                $order_content = str_replace('##user_name##', $users[0]->user_name, $order_content);
                send_mail($users[0]->email, $subject, $order_content);
                $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
                $token = $stripe
                    ->tokens
                    ->create(['card' => ['number' => '4242424242424242', 'exp_month' => 7, 'exp_year' => 2023, 'cvc' => '314', ], ]);
                $token = trim($token->id);
                $user_details = array(
                    'name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->name))) ,
                    'email' => trim($json_obj->email) ,
                );
                $card_details = array(
                    'card_num' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->card_num))) ,
                    'card_cvc' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->cvc))) ,
                    'card_exp_month' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->exp_month))) ,
                    'card_exp_year' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->exp_year)))
                );
                $items = array(
                    'itemPrice' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $order_total_amount))) ,
                    'currency' => "usd"
                );
                $payment_details = stripe_payment_process($token, $user_details, $card_details, $items);
                if ($payment_details != false)
                {
                    $payment_detail_data = array(
                        "payment_gateway_transaction_id" => $payment_details['id'],
                        "transaction_status" => $payment_details['status'],
                        "order_id" => $result,
                        "transaction_amount" => $payment_details['amount'],
                        "transaction_datetime" => date("Y-m-d H:i:s") ,
                        "created_datetime" => date("Y-m-d H:i:s") ,
                        "created_by" => $json_obj->created_by
                    );
                    $this
                        ->orders_model
                        ->add_order($payment_detail_data, 'tbl_transactions');
                    if ($payment_details['status'] == 'successful')
                    {
                        $order_data = array(
                            'order_status' => 'completed'
                        );
                        $this
                            ->orders_model
                            ->update_order($order_data, $result, 'tbl_orders');
                        $order_content = file_get_contents('templates/order_payment_successful.html');
                        $order_content = str_replace('##user_name##', $json_obj->name, $order_content);
                        $order_content = str_replace('##amount##', $payment_details['amount'], $order_content);
                        send_mail($json_obj->email, $subject, $order_content);
                    }
                }
                else
                {
                    $errors = "There is problem in payment";
                }
            }
            else
            {
                $errors = 'Order Not Added Successfully';
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }
    }
   /* public function get_order_by_id()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);
        $oauth_key = $json_obj->oauth_key;
        $errors = $success_message = '';
        $ArrData = array();
        if (check_oauth_key($oauth_key))
        {
            $data = array(
                'order_id' => $json_obj->order_id
            );
            $temp_order_result = $this
                ->orders_model
                ->get_order_by_id($data);
            if (count($temp_order_result) > 0)
            {
                $result = $temp_order_result[0];
                $result['products'] = $this
                    ->orders_model
                    ->get_order_product_by_id($data);
            }
            $ArrData = $result;
            if (count($result) > 0)
            {
                $ArrData = $result;
                $success_message = '';
            }
            else
            {
                $errors = 'No data available';
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }
    }
    public function update_order()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);
        $oauth_key = $json_obj->oauth_key;
        $errors = $success_message = '';
        $ArrData = array();
        if (check_oauth_key($oauth_key))
        {
            $order_id = trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_id)));
            $data = array(
                'user_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->user_id))) ,
                'order_datetime' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', date('Y-m-d', strtotime($json_obj->order_datetime))))) ,
                'order_status' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', 'processing'))) ,
                'order_notes' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_notes))) ,
                'billing_first_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_first_name))) ,
                'billing_last_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_last_name))) ,
                'billing_street_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_street_name))) ,
                'billing_apartment_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_apartment_name))) ,
                'billing_city' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_city))) ,
                'billing_state_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_state_id))) ,
                'billing_zipcode' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_zipcode))) ,
                'billing_country' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_country))) ,
                'billing_phone' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_phone))) ,
                'billing_email' => trim(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->billing_email)) ,
                'shipping_first_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_first_name))) ,
                'shipping_last_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_last_name))) ,
                'shipping_street_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_street_name))) ,
                'shipping_apartment_name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_apartment_name))) ,
                'shipping_city' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_city))) ,
                'shipping_state_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_state_id))) ,
                'shipping_zipcode' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_zipcode))) ,
                'shipping_country' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_country))) ,
                'shipping_phone' => trim(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_phone)) ,
                'shipping_email' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->shipping_email))) ,
                'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->created_by))) ,
                'created_datetime' => date('Y-m-d H:i:s') ,
                'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active)))
            );
            $result = $this
                ->orders_model
                ->update_order($data, $order_id, 'tbl_orders');
            $total_weight = 0;
            foreach ($json_obj->product_data as $product)
            {
                $this
                    ->orders_model
                    ->delete_order($order_id, 'tbl_order_products');
                $total_weight += trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_weight_gms * $product->qty)));
                $order_product_data = array(
                    "order_id" => $order_id,
                    "product_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_id))) ,
                    "product_variant_id" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_variant_id))) ,
                    "qty" => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $product->qty))) ,
                    'created_by' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->created_by))) ,
                    'created_datetime' => date('Y-m-d H:i:s') ,
                    'is_active' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->is_active)))
                );
                $result_product_id = $this
                    ->orders_model
                    ->add_order($order_product_data, 'tbl_order_products');
            }
            $fedex_shipping_charge = get_rate_by_weight($json_obj->qty, $json_obj->dimension, $total_weight, $json_obj->shipping_city, $json_obj->stateOrProvinceCode, $json_obj->shipping_zipcode, 'US');
            if ($fedex_shipping_charge
                ->output
                ->rateReplyDetails[0]
                ->ratedShipmentDetails[0]->totalNetFedExCharge != null)
            {
                $shipping_charge = $fedex_shipping_charge
                    ->output
                    ->rateReplyDetails[0]
                    ->ratedShipmentDetails[0]->totalNetFedExCharge;
            }
            else
            {
                return $fedex_shipping_charge->errors[0]->message;
            }
            $order_total_amount = $json_obj->order_price + $shipping_charge + $json_obj->order_tip + $json_obj->order_total_tax - $json_obj->discount_amount;
			
            $track_id = get_ship();
            if ($track_id
                ->output
                ->transactionShipments[0]->masterTrackingNumber != null)
            {
                $fedex_tracking_id = $track_id
                    ->output
                    ->transactionShipments[0]->masterTrackingNumber;
            }
            else
            {
                return $track_id->errors[0]->message;
            }
            $order_data = array(
                'order_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_price))) ,
                'fedex_tracking_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $fedex_tracking_id))) ,
                'fedex_shipping_charge' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $shipping_charge))) ,
                'order_tip' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_tip))) ,
                'order_total_tax' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->order_total_tax))) ,
                'coupon_id' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->coupon_id))) ,
                'discount_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->discount_amount))) ,
                'order_total_amount' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $order_total_amount))) ,
            );
            $this
                ->orders_model
                ->update_order($order_data, $result, 'tbl_orders');
            $ArrData = $result;
            if ($result)
            {
                $success_message = 'Order added successfully';
                $users = $this
                    ->orders_model
                    ->get_users_data($result);
                $subject = "Order Place Successfully";
                $order_content = file_get_contents('templates/order_successful.html');
                $order_content = str_replace('##user_name##', $users[0]->user_name, $order_content);
                send_mail($users[0]->email, $subject, $order_content);
                $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
                $token = $stripe
                    ->tokens
                    ->create(['card' => ['number' => '4242424242424242', 'exp_month' => 7, 'exp_year' => 2023, 'cvc' => '314', ], ]);
                $token = trim($token->id);
                $user_details = array(
                    'name' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->name))) ,
                    'email' => trim($json_obj->email) ,
                );
                $card_details = array(
                    'card_num' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->card_num))) ,
                    'card_cvc' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->cvc))) ,
                    'card_exp_month' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->exp_month))) ,
                    'card_exp_year' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $json_obj->exp_year)))
                );
                $items = array(
                    'itemPrice' => trim(htmlspecialchars(preg_replace('/[^A-Za-z0-9\-]/', '', $order_total_amount))) ,
                    'currency' => "usd"
                );
                $payment_details = stripe_payment_process($token, $user_details, $card_details, $items);
                if ($payment_details != false)
                {
                    $payment_detail_data = array(
                        "payment_gateway_transaction_id" => $payment_details['id'],
                        "transaction_status" => $payment_details['status'],
                        "order_id" => $result,
                        "transaction_amount" => $payment_details['amount'],
                        "transaction_datetime" => date("Y-m-d H:i:s") ,
                        "created_datetime" => date("Y-m-d H:i:s") ,
                        "created_by" => $json_obj->created_by
                    );
                    $this
                        ->orders_model
                        ->add_order($payment_detail_data, 'tbl_transactions');
                    if ($payment_details['status'] == 'successful')
                    {
                        $order_data = array(
                            'order_status' => 'completed'
                        );
                        $this
                            ->orders_model
                            ->update_order($order_data, $order_id, 'tbl_orders');
                        $order_content = file_get_contents('templates/order_payment_successful.html');
                        $order_content = str_replace('##user_name##', $json_obj->name, $order_content);
                        $order_content = str_replace('##amount##', $payment_details['amount'], $order_content);
                        send_mail($json_obj->email, $subject, $order_content);
                    }
                }
                else
                {
                    $errors = "There is problem in payment";
                }
            }
            else
            {
                $errors = 'Order Not Added Successfully';
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }
    }
    public function delete_orders()
    {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);
        $oauth_key = $json_obj->oauth_key;
        $errors = $success_message = '';
        $ArrData = array();
        if (check_oauth_key($oauth_key))
        {
            $order_id = $json_obj->order_id;
            $data = array(
                'is_active' => '0',
                'is_deleted' => '1'
            );
            $result = $this
                ->orders_model
                ->update_order($data, $order_id, 'tbl_orders');
            $this
                ->orders_model
                ->update_order($data, $order_id, 'tbl_order_products');
            $ArrData = $result;
            if ($result)
            {
                $ArrData = $result;
                $success_message = 'Order Deleted Successfully';
            }
            else
            {
                $errors = 'Order Not Deleted Successfully';
            }
            send_response_to_api($ArrData, $errors, $success_message);
        }
    }*/
}
