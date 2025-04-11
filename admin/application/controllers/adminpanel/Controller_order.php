<?php

class Controller_order extends CI_Controller
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
		$this->load->model('credittransaction_model');
		$this->load->model('Master_model', 'master');
		$this->module_name = 'order';

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	public function index()
	{
		$ArrData = $this->order_model->getOrderListData($_POST);

		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());


			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();

				$base_url = base_url();
				$order_id = $aRow['order_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $order_id . '" /><div class="btn-group">';
				//$actions .='<a rel="'.$base_url.'adminpanel/controller_order/view_order_ajax/" id="'.$order_id.'" title="Order Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				//$actions .= '<a rel="' . $base_url . 'adminpanel/controller_order/update_order/" id="' . $order_id . '" title="Order Edit" class="edit_popup btn btn-default btn-sm" ><i class="fa fa-pencil"></i></a>';

				$actions .= '<a href="' . $base_url . 'update-order/' . $order_id . '" title="Order Edit" class=" btn btn-default btn-sm" ><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'order-delete/' . $order_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a>';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_order/show_order/?o=' . base64_encode($order_id) . '" id="' . $order_id . '" title="Packing List" class="packingList btn btn-default btn-sm" target="_blank"><i class="fa fa-download"></i></a></div>';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_order/invoice_order/?o=' . base64_encode($order_id) . '" id="' . $order_id . '" title="invoice_' . $order_id . '" class="invoiceOrder btn btn-default btn-sm"><i class="fa fa-download"></i></a></div>';

				$row[] = $actions;
				$row[] = $aRow['order_id'];
				$row[] = $aRow['created_datetime'];
				$row[] = $aRow['display_name'];
				$row[] = $aRow['mobile_no'];
				$row[] = $aRow['order_total_amount'];
				$row[] = $aRow['order_status'];


				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Order List';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_order_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}


	public function view_order_ajax()
	{
		$id = $this->input->get('id');
		$data['ArrFieldData'] = $this->order_model->getOrderById($id);
		$data['ArrOrderProduct'] = $this->order_product_model->getOrderProductByOrderId($id);
		$this->load->view('admin_panel/quickview/view_order_details_popup', $data);
	}

	public function invoice_order()
	{
		$id = base64_decode($_GET['o']);
		$data['ArrFieldDatashow'] = $this->order_model->getOrderById($id);
		$data['ArrOrderProductshow'] = $this->order_product_model->getOrderProductByOrderId($id);
		$data['ArrOrderStatusshow'] = getOrderStatus();
		$earned_trans_credit = $this->credittransaction_model->getCredittransdetails($id,'earned');
		$used_trans_credit = $this->credittransaction_model->getCredittransdetails($id,'used');
		$data['earned_cr_val'] = $earned_trans_credit ?? 0;
		$data['used_cr_val'] = $used_trans_credit ?? 0;
		$this->load->view('admin_panel/quickview/invoice_orders.php', $data);
	}

	public function show_order()
	{
		$id = base64_decode($_GET['o']);
		$data['ArrFieldDatashow'] = $this->order_model->getOrderById($id);
		$data['ArrOrderProductshow'] = $this->order_product_model->getOrderProductByOrderId($id);

		$data['ArrOrderStatusshow'] = getOrderStatus();
		$earned_trans_credit = $this->credittransaction_model->getCredittransdetails($id,'earned');
		$used_trans_credit = $this->credittransaction_model->getCredittransdetails($id,'used');
		$data['earned_cr_val'] = $earned_trans_credit ?? 0;
		$data['used_cr_val'] = $used_trans_credit ?? 0;
		$this->load->view('admin_panel/quickview/table.php', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->order_model->delete($id);
		if ($result == true) {
			echo 'Yes';
		} else {
			echo 'No';
		}
	}

	public function delete_multiple_order_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->order_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/* CAPTURE PAYMENT */
	public function order_payment_capture()
	{
		
		$order_id = $_POST['order_id'];
		$stripeToken = $_POST['stripeToken'];
		$payment_intent_id = $_POST['payment_intent_id'];
		$amount_to_capture = $_POST['amount_to_capture'];
		$blocked_amount = $_POST['blocked_amount'];
		$ArrExtraPayment = $_POST;
		
		//echo "<pre>";print_r($ArrExtraPayment);exit;
		if ($amount_to_capture < $blocked_amount)
		{
			$new_amount_to_capture = $amount_to_capture;
			//echo "less";
		}
		else
		{
			$new_amount_to_capture = $blocked_amount;
			//echo "more";
		}
		//echo "<pre>";print_r($_POST);exit;
		$new_amount_to_capture = $new_amount_to_capture * 100;

		/* ---------------------CAPTURE PAYMENT START--------------------- */
		$url = API_URL . 'stripe/payment_capture';

		$ArrPayment = array('stripeToken' => $stripeToken, 'payment_intent_id' => $payment_intent_id, 'amount_to_capture' => $new_amount_to_capture);

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
			//echo 'cURL error: ' . $error;
		} else {
			//echo 'amount_to_capture Response: ' . $response;
		}

		$response = json_decode($response);


		curl_close($curl);

		$ArrData = $response->data;

		$flag = false;
		//echo "<pre>";print_r($ArrData);exit;
		/* ---------------------CAPTURE PAYMENT END--------------------- */

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
				"transaction_amount" => $amount_received / 100,
				"transaction_datetime" => date("Y-m-d H:i:s"),
				"created_datetime" => date("Y-m-d H:i:s")
			);
			$this->transactions_model->add($ArrTransactionLog);

			$flag = true;



			$ArrOrderDetails = $this->order_model->getOrderById($order_id);
			if ($amount_to_capture < $blocked_amount) {
				/* Perform a check and compare the captured amount, and if it is less, send a credit note. */
				/* ---------------------SEND CREDIT NOTE START--------------------- */
				$shipping_method = $ArrOrderDetails['delivery_type'];
				$billing_email = $ArrOrderDetails['billing_email'];
				$billing_phone = $ArrOrderDetails['billing_phone'];
				$order_date = $ArrOrderDetails['order_datetime'];
				$order_id = $ArrOrderDetails['order_id'];
				$billing_name = $ArrOrderDetails['billing_first_name'] . " " . $ArrOrderDetails['billing_last_name'];
				$billing_address = $ArrOrderDetails['billing_street_name'] . " " . $ArrOrderDetails['billing_apartment_name'] . "<br>" . $ArrOrderDetails['billing_city'] . " " . $ArrOrderDetails['billing_zipcode'] . getStateName($ArrOrderDetails['billing_state_id']);
				$shipping_name = $ArrOrderDetails['shipping_first_name'] . " " . $ArrOrderDetails['shipping_last_name'];
				$shipping_address = $ArrOrderDetails['shipping_street_name'] . " " . $ArrOrderDetails['shipping_apartment_name'] . "<br>" . $ArrOrderDetails['shipping_city'] . " " . $ArrOrderDetails['shipping_zipcode'] . getStateName($ArrOrderDetails['shipping_state_id']);


				$order_item_details = '';
				$ArrUpdatedProducts = $this->order_product_model->getOrderProductByOrderId($order_id, '*', 'old_qty IS NOT NULL');
				if (is_array($ArrUpdatedProducts) && count($ArrUpdatedProducts) > 0) {
					foreach ($ArrUpdatedProducts as $data) {
						$order_item_details .= '<tr>';
						$order_item_details .= '<td></td>';
						$order_item_details .= '<td>' . $data['product_sku'] . '</td>';
						$order_item_details .= '<td>' . $data['product_name'] . '</td>';
						$order_item_details .= '<td>' . $data['qty'] . '&nbsp;&nbsp;<del>' . $data['old_qty'] . '</del></td>';
						$order_item_details .= '<td>' . $data['product_weight_gms'] . '</td>';
						$order_item_details .= '<td>' . $data['total_amount'] . '</td>';
						$order_item_details .= '</tr>';
					}
				}

				$subject = $billing_name . ", your order credit note";
				$email_content = file_get_contents('templates/creditnote.html');

				$arr_replace = array('##billing_email##', '##billing_phone##', '##shipping_method##', '##billing_name##', '##billing_address##', '##shipping_name##', '##shipping_address##', '##order_id##', '##order_date##', '##order_item_details##');
				$arr_replace_with = array($billing_email, $billing_phone, $shipping_method, $billing_name, $billing_address, $shipping_name, $shipping_address, $order_id, $order_date, $order_item_details);
				$email_content = str_replace($arr_replace, $arr_replace_with, $email_content);

				send_mail($email, $subject, $email_content);

				/* ---------------------SEND CREDIT NOTE END--------------------- */
			} else if ($amount_to_capture > $blocked_amount) {
				//echo "else if";
				/* Perform a check and compare the captured amount, and if it is greter, create a new transaction and capture payment. */
				
				/* ---------------------CAPTURE PAYMENT START--------------------- */
				$order_amount = $amount_to_capture-$blocked_amount;

				$ArrExtraPayment = $_POST;
				$ArrExtraPayment['order_amount'] = $order_amount * 100;
				
				/*Payment Process Start*/
				$url = API_URL . 'stripe/get_stripe_payment';
				$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrExtraPayment);
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
				if ($response === false || $response == null) {
					$error = curl_error($curl);
					echo 'cURL error: ' . $error;
				} else {
					echo 'Response: ' . $response;
				}
				curl_close($curl);
				$response = json_decode($response);
				
				
			} /* END amount_to_capture IF */



		} else {
			$amount_received = $ArrData->amount_received;
			$int_amount_received = (int) $amount_received;
			//print_r($int_amount_received);
			$order_data = array(
				'amount_received' => trim($int_amount_received) / 100,
				'amount_received_status' => 'failed',
			);

			$this->order_model->update($order_id, $order_data);
		}
		if ($flag) {
			$this->session->set_flashdata('success_message', 'Order payment captured successfully.');
		} else {
			$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
		}
		redirect('orders');
	}

	/* UPDATE ORDER PROCESS START */
	public function update_order()
	{
		$id = $this->input->post('id');
		$orderdetails = $this->order_model->getOrderById($id);
		$data['ArrFieldData'] = array();

		$is_revert = 0;
		$is_capture = 0;
		$DiffAmount = 0;
		$TotalCaptureAmount = 0;
		$TotalReturnAmount = 0;
		$TotalReceivedAmount = 0;
		if(!empty($orderdetails)){
			
			$cond = array('
				payment_intent_status' => 'succeeded',
				'order_id' => $id,
				'payment_type' => 'capture',
				'is_deleted' => 0,
			);
			$TotalCaptureAmountArr = $this->master->get_total_from_transctions('transaction_amount', $cond);
			if(!empty($TotalCaptureAmountArr)){
				if($TotalCaptureAmountArr->transaction_amount != "" && $TotalCaptureAmountArr->transaction_amount > 0){
					$TotalCaptureAmount = $TotalCaptureAmountArr->transaction_amount;
				}
			}
			$cond = array('
				payment_intent_status' => 'succeeded',
				'order_id' => $id,
				'payment_type' => 'refund',
				'is_deleted' => 0,
			);
			$TotalReturnAmountArr = $this->master->get_total_from_transctions('transaction_amount', $cond);

			if(!empty($TotalReturnAmountArr)){
				if($TotalReturnAmountArr->transaction_amount != "" && $TotalReturnAmountArr->transaction_amount > 0){
					$TotalReturnAmount = $TotalReturnAmountArr->transaction_amount;
				}
			}		
			
			$TotalReceivedAmount = $TotalCaptureAmount - $TotalReturnAmount;
			$order_total_amount = $orderdetails['order_total_amount'];
			//echo intval($order_total_amount) .">". intval($TotalReceivedAmount);exit;
			if(intval($order_total_amount) > intval($TotalReceivedAmount)){
				$is_capture = 1;
				$DiffAmount = $order_total_amount - $TotalReceivedAmount;
			} else if(intval($order_total_amount) < intval($TotalReceivedAmount)){
				$is_revert = 1;
				$DiffAmount = $TotalReceivedAmount - $order_total_amount;
			}
		}
		$orderdetails['is_revert'] = $is_revert;
		$orderdetails['is_capture'] = $is_capture;
		$orderdetails['DiffAmount'] = $DiffAmount;
		//echo '<pre>';print_r($orderdetails);exit;
		$data['ArrFieldData'] = $orderdetails;

		if($data['ArrFieldData']['is_flag'] == 1 && ($data['ArrFieldData']['is_replace_item'] == "2" || $data['ArrFieldData']['is_replace_item'] == "3"))
		{
			if($data['ArrFieldData']['substitution_product_ids'] != ""){
				$substitution_product_ids = explode(',', $data['ArrFieldData']['substitution_product_ids']);
				if(is_array($substitution_product_ids) && !empty($substitution_product_ids)){
					$data['ArrFieldData']['substitution_products'] = $this->order_model->getProductsByIds($substitution_product_ids);
				}
			}
		}
		$data['ArrOrderProduct'] = $this->order_product_model->getOrderProductByOrderId($id);
		$data['ArrOrderTransactions'] = $this->transactions_model->getTransactionsBYOrderId($id);
		
		$data['ArrUser'] = $this->user_model->getUserByID($data['ArrFieldData']['user_id']);
		
		$data['ArrOrderStatus'] = getOrderStatus();

		$this->load->view('admin_panel/quickview/view_order_update_popup', $data);
	}

	/* UPDATE ORDER START */
	public function update_order_page($order_id)
	{
		$id = $order_id;
		$orderdetails = $this->order_model->getOrderById($id);
		$ArrPageData['ArrFieldData'] = array();

		$is_revert = 0;
		$is_capture = 0;
		$DiffAmount = 0;
		$TotalCaptureAmount = 0;
		$TotalReturnAmount = 0;
		$TotalReceivedAmount = 0;
		if(!empty($orderdetails)){
			
			$cond = array('
				payment_intent_status' => 'succeeded',
				'order_id' => $id,
				'payment_type' => 'capture',
				'is_deleted' => 0,
			);
			$TotalCaptureAmountArr = $this->master->get_total_from_transctions('transaction_amount', $cond);
			if(!empty($TotalCaptureAmountArr)){
				if($TotalCaptureAmountArr->transaction_amount != "" && $TotalCaptureAmountArr->transaction_amount > 0){
					$TotalCaptureAmount = $TotalCaptureAmountArr->transaction_amount;
				}
			}
			$cond = array('
				payment_intent_status' => 'succeeded',
				'order_id' => $id,
				'payment_type' => 'refund',
				'is_deleted' => 0,
			);
			$TotalReturnAmountArr = $this->master->get_total_from_transctions('transaction_amount', $cond);

			if(!empty($TotalReturnAmountArr)){
				if($TotalReturnAmountArr->transaction_amount != "" && $TotalReturnAmountArr->transaction_amount > 0){
					$TotalReturnAmount = $TotalReturnAmountArr->transaction_amount;
				}
			}		
			
			$TotalReceivedAmount = $TotalCaptureAmount - $TotalReturnAmount;
			$order_total_amount = $orderdetails['order_total_amount'];
			if(floatval($order_total_amount) > floatval($TotalReceivedAmount)){
				$is_capture = 1;
				$DiffAmount = ($order_total_amount - $TotalReceivedAmount);
			} else if(floatval($order_total_amount) < floatval($TotalReceivedAmount)){
				$is_revert = 1;
				$DiffAmount = $TotalReceivedAmount - $order_total_amount;
			}
		}

		if (abs($DiffAmount) < 0.00001) {
			$DiffAmount = 0;
		}

		$orderdetails['is_revert'] = $is_revert;
		$orderdetails['is_capture'] = $is_capture;
		$orderdetails['DiffAmount'] = ($DiffAmount > 0) ? number_format($DiffAmount,2) : $DiffAmount;
		$orderdetails['TotalCaptureAmount'] = $TotalCaptureAmount;
		//echo '<pre>';print_r($orderdetails);exit;
		$ArrPageData['ArrFieldData'] = $orderdetails;
		

		if($ArrPageData['ArrFieldData']['is_flag'] == 1 && ($ArrPageData['ArrFieldData']['is_replace_item'] == "2" || $ArrPageData['ArrFieldData']['is_replace_item'] == "3"))
		{
			if($ArrPageData['ArrFieldData']['substitution_product_ids'] != ""){
				$substitution_product_ids = explode(',', $ArrPageData['ArrFieldData']['substitution_product_ids']);
				if(is_array($substitution_product_ids) && !empty($substitution_product_ids)){
					$ArrPageData['ArrFieldData']['substitution_products'] = $this->order_model->getProductsByIds($substitution_product_ids);
				}
			}
		}
		$ArrPageData['ArrOrderProduct'] = $this->order_product_model->getOrderProductByOrderId($id);
		$ArrPageData['ArrOrderTransactions'] = $this->transactions_model->getTransactionsBYOrderId($id);
		
		$ArrPageData['ArrUser'] = $this->user_model->getUserByID($ArrPageData['ArrFieldData']['user_id']);
		
		$ArrPageData['ArrOrderStatus'] = getOrderStatus();

		$earned_trans_credit = $this->credittransaction_model->getCredittransdetails($id,'earned');
		$used_trans_credit = $this->credittransaction_model->getCredittransdetails($id,'used');
		$order_credit_per = $this->credittransaction_model->getCreditperbycreditid($id);

		$ArrPageData['ArrFieldData']['earned_trans_credit'] = $earned_trans_credit ?? 0;
		$ArrPageData['ArrFieldData']['used_trans_credit'] = $used_trans_credit ?? 0;
		$ArrPageData['ArrFieldData']['order_credit_per'] = $order_credit_per ?? 0;
		$ArrPageData['view_name'] = 'view_' . $this->module_name . '_details.php';

		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/' . $this->module_name . '-script.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);

		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function order_diffrence_payment($order_id)
	{
		$errors = "";
		$success_message = "";
		$ArrData = [];
		$difftype = $this->input->post('difftype');
		$diffamount = $this->input->post('diffamount');
		$orderdetails = $this->order_model->getOrderById($order_id);
		$errors = "Opps! Something want wrong!!!";
		
		if($order_id > 0 && !empty($orderdetails)){
			

			$totalCaptureAmmount = 0;
			$totalReturnAmmount = 0;

			$tempAmount = 0;
			if($difftype == "revert"){	
				$tempAmount = $orderdetails['amount_received'] - $orderdetails['order_total_amount'];
			} else if($difftype == "capture"){
				$tempAmount = $orderdetails['order_total_amount'] - $orderdetails['amount_received'];				
			}

			$ArrExtraPayment['diffamount'] = $diffamount * 100;
			$ArrExtraPayment['difftype'] = $difftype;
			$ArrExtraPayment['order_id'] = $order_id;
			

			/*Payment Process Start*/

			//$url = API_URL . 'stripe/get_stripe_payment';
			$url = API_URL . 'stripe/order_diffrence_payment';
			$data = array("oauth_key" => "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT", "ArrPayment" => $ArrExtraPayment);

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
			
			if ($response === false || $response == null) {
				$error = curl_error($curl);
			}

			curl_close($curl);

			$response = json_decode($response);
			
			if(isset($response->is_successful) && $response->is_successful == 1){

				$ArrData = $response;
				$ArrData = array('status' => 1);
				$success_message = $response->success_message;
			}
		}
		$this->send_response_to_api($ArrData, $errors, $success_message);
	}

	function send_response_to_api($ArrData, $errors = '', $success_message = '')
	{
		//header("Access-Control-Allow-Origin: *");
		if ((is_array($ArrData) && count($ArrData) > 0) || $success_message != '') {
			$ArrResponse = array('is_successful' => '1', 'error_code' => -1, 'data' => $ArrData, 'errors' => '', 'success_message' => $success_message);
			$myJSON = json_encode($ArrResponse);
			header('Content-Type: application/json');
			echo $myJSON;
		} else {
			$ArrError = array('is_successful' => '0', 'error_code' => 400, 'data' => null, 'errors' => $errors, 'success_message' => '');
			$myJSON = json_encode($ArrError);
			header('Content-Type: application/json');
			echo $myJSON;
		}
	}


	public function update_order_process()
	{
		//echo "<pre>";print_r($_POST);exit;
		$flag = false;
		$total_order_amount = 0;
		$updated_product_count = 0;
		$order_product_total_tax = 0;
		$updateOldQTY = false;
		$order_id = $this->input->post('order_id');
		if ($order_id > 0) {
			//-------------------Existing Products Process Start-------------------
			$ArrOrderProductIds = $this->input->post('ArrOrderProductIds');
			$ArrQty = $this->input->post('qty');
			$ArrOldQty = $this->input->post('oldqty');
			$ArrUnit_price = $this->input->post('unit_price');
			$ArrTotal_amount = $this->input->post('total_amount');
			$ArrProduct_tax = $this->input->post('product_tax');
			
			foreach($ArrQty as $key=>$qty)
			{
				if(isset($ArrOldQty[$key]) && $ArrOldQty[$key]!=$qty && !$updateOldQTY)
				{
					$updateOldQTY = true;
				}
			}
			//update OLD QTY
			if($updateOldQTY)
			{
				$updated_product_count = $this->order_product_model->updateOldQty($order_id);
			}
			
			if (is_array($ArrOrderProductIds) && count($ArrOrderProductIds) > 0) {
				foreach ($ArrOrderProductIds as $order_product_id) {
					$product_tax_amount = (isset($ArrProduct_tax[$order_product_id]) && $ArrProduct_tax[$order_product_id] != "") ? $ArrProduct_tax[$order_product_id] : 0;
					$ArrOrderProductData = array(
						'qty' => $ArrQty[$order_product_id],
						'unit_price' => $ArrUnit_price[$order_product_id],
						'product_tax_amount' => $product_tax_amount,
						'total_amount' => $ArrTotal_amount[$order_product_id] - $product_tax_amount,
						'modified_datetime' => date('Y-m-d H:i:s'),
						'modified_by' => get_current_admin_id(),
					);
					$order_product_total_tax = $order_product_total_tax + $product_tax_amount;
					$this->order_product_model->update($order_product_id, $ArrOrderProductData);
					$total_order_amount += $ArrTotal_amount[$order_product_id];
				}
				$flag = true;
			}
			//-------------------Existing Products Process End-------------------

			//-------------------Newly Added Products Process Start-------------------
			$ArrNewProducts = $this->input->post('ArrNewProducts');
			$ArrNewQty = $this->input->post('ArrNewQty');
			$ArrNewUnit_price = $this->input->post('ArrNewUnit_price');
			$ArrNewTotal_amount = $this->input->post('ArrNewTotal_amount');
			$ArrNewprod_tax = $this->input->post('ArrNewproduct_tax');

			//--------------------------------------send credit note start--------------------------------------

			/*if ($updated_product_count > 0)
			{
				$ArrOrderDetails = $this->order_model->getOrderById($order_id);

				$email = $ArrOrderDetails['shipping_email'];
				$shipping_method = $ArrOrderDetails['delivery_type'];
				$billing_email = $ArrOrderDetails['billing_email'];
				$billing_phone = $ArrOrderDetails['billing_phone'];
				$order_date = $ArrOrderDetails['order_datetime'];
				$order_id = $ArrOrderDetails['order_id'];
				$billing_name = $ArrOrderDetails['billing_first_name'] . " " . $ArrOrderDetails['billing_last_name'];
				$billing_address = $ArrOrderDetails['billing_street_name'] . " " . $ArrOrderDetails['billing_apartment_name'] . "<br>" . $ArrOrderDetails['billing_city'] . " " . $ArrOrderDetails['billing_zipcode'] . getStateName($ArrOrderDetails['billing_state_id']);
				$shipping_name = $ArrOrderDetails['shipping_first_name'] . " " . $ArrOrderDetails['shipping_last_name'];
				$shipping_address = $ArrOrderDetails['shipping_street_name'] . " " . $ArrOrderDetails['shipping_apartment_name'] . "<br>" . $ArrOrderDetails['shipping_city'] . " " . $ArrOrderDetails['shipping_zipcode'] . getStateName($ArrOrderDetails['shipping_state_id']);


				$ArrUpdatedProducts = $this->order_product_model->getOrderProductByOrderId($order_id, '*', 'old_qty IS NOT NULL');

				$order_item_details = '';
				if (is_array($ArrUpdatedProducts) && count($ArrUpdatedProducts) > 0) {
					foreach ($ArrUpdatedProducts as $data) {
						$order_item_details .= '<tr>';
						$order_item_details .= '<td>' . $data['product_sku'] . '</td>';
						$order_item_details .= '<td>' . $data['product_name'] . '</td>';
						if($data['old_qty']>0)
						{
						$order_item_details .= '<td>' . $data['qty'] . '&nbsp;&nbsp;<del>' . $data['old_qty'] . '</del></td>';
						}
						else
						{
						$order_item_details .= '<td>' . $data['qty'] . '</td>';
						}
						$order_item_details .= '<td>' . $data['product_weight_gms'] . '</td>';
						$order_item_details .= '<td>' . $data['total_amount'] . '</td>';
						$order_item_details .= '</tr>';
					}
				}

				$subject = $billing_name . ", your order credit note";
				$email_content = file_get_contents('templates/creditnote.html');

				$arr_replace = array('##billing_email##', '##billing_phone##', '##shipping_method##', '##billing_name##', '##billing_address##', '##shipping_name##', '##shipping_address##', '##order_id##', '##order_date##', '##order_item_details##');
				$arr_replace_with = array($billing_email, $billing_phone, $shipping_method, $billing_name, $billing_address, $shipping_name, $shipping_address, $order_id, $order_date, $order_item_details);
				$email_content = str_replace($arr_replace, $arr_replace_with, $email_content);

				send_mail($email, $subject, $email_content);


				//send sms
				$SMSbody = "Sorry, we had to make some changes to your VrajFresh order. Review these changes:https://www.vrajfresh.com/my-orders";
				sendSMS($ArrOrderDetails['shipping_phone'], $SMSbody);
			}*/
			//--------------------------------------update OLD QTY end--------------------------------------

			if (is_array($ArrNewProducts) && count($ArrNewProducts) > 0) {
				for ($key = 0; $key < count($ArrNewProducts); $key++) {
					$product_details = $ArrNewProducts[$key];

					$product_variant_id = $prodcut_id = $product_variant_id = '';
					$ArrProductDetails = explode("|", $product_details);
					if (is_array($ArrProductDetails) && count($ArrProductDetails) > 0) {
						//Format: product_id-variant_id-variant_price-product_name
						$prodcut_id = $ArrProductDetails[0];
						$product_variant_id = $ArrProductDetails[1];
						$unit_price = $ArrProductDetails[2];
						$product_name = $ArrProductDetails[3];
					}


					if ($prodcut_id > 0) {
						$ArrOrderProductData = array(
							'order_id' => $order_id,
							'product_id' => $prodcut_id,
							'qty' => $ArrNewQty[$key],
							'product_name' => $product_name,
							'product_variant_id' => $product_variant_id,
							'unit_price' => $ArrNewUnit_price[$key],
							'product_tax_amount' => $ArrNewprod_tax[$key],
							'total_amount' => $ArrNewTotal_amount[$key] - $ArrNewprod_tax[$key],
							'created_datetime' => date('Y-m-d H:i:s'),
							'created_by' => get_current_admin_id(),
						);
						$this->order_product_model->add($ArrOrderProductData);
						$total_order_amount += $ArrNewTotal_amount[$key];
					}
				}
				$flag = true;
			}
			//-------------------Newly Added Products Process End-------------------
			$final_order_amount = 0;
			$total_latest_order_pro = $this->order_model->getTotalOrderProductsSum($order_id);
			$final_order_amount = $total_latest_order_pro[0]['total_amount'];
			//-------------------Update Order master start-------------------
			// $total_updated_order_diff = 0;
			// $total_updated_order_diff = $this->input->post('NewTotalA') - $this->input->post('total_order_without_taxw');
			 $orderdetails = $this->order_model->getOrderById($order_id);
			// $cal_per_amt = 0; $total_disp_amt = 0;
			// if($total_updated_order_diff > 0){
			// 	if ($orderdetails['promotional_code'] != '') { 
			// 		$promo_dis_val = $orderdetails['promo_dis_val'];
			// 		$dis_type=$orderdetails['promo_dis_type'];
			// 		$total_diff_amt = $total_updated_order_diff;
			// 		if($dis_type=='%'){
			// 			$cal_per_amt = ($promo_dis_val/100) * $total_diff_amt;
			// 		}else{
			// 			$total_prod = count($ArrOrderProduct);
			// 			$total_order_sum = $orderdetails['order_amount'];
			// 			$find_perc = ($total_prod / $total_order_sum) * 100;
			// 			$cal_per_amt = ($find_perc/100) * $total_diff_amt;
			// 		}
			// 	}
			// }
				$total_dis_amount = 0;
				if ($orderdetails['promotional_code'] != '') { 
					$promo_dis_val = $orderdetails['promo_dis_val'];
					$dis_type=$orderdetails['promo_dis_type'];
					$maximum_dis_val=$orderdetails['maximum_order_discount'];
					if($dis_type=='%'){
						$total_dis_amount = ($promo_dis_val/100) * $final_order_amount;
						if($total_dis_amount < $maximum_dis_val){
							$total_dis_amount = $total_dis_amount;
						}else{
							$total_dis_amount = $maximum_dis_val;
						}
					}else{
						// $total_prod = intval($this->input->post('total_or_qty'));
						// $total_order_sum = $orderdetails['order_amount'];
						// $find_perc = ($total_prod / $total_order_sum) * 100;
						// $total_dis_amount = ($find_perc/100) * $final_order_amount;
						$total_dis_amount = $promo_dis_val;
					}
				}


			$shipping_charge = $this->input->post('shipping_charge');
			$order_tip = $this->input->post('order_tip');
			$order_total_tax = $this->input->post('order_total_tax');
			$discount_amount = $total_dis_amount;
			$order_status = $this->input->post('order_status');
			$preparation_cost = $this->input->post('preparation_cost');
			$packaging_cost = $this->input->post('packaging_cost');
			$delivery_datetime = (trim($this->input->post('delivery_datetime'))) ? date('Y-m-d H:i:s', strtotime(trim($this->input->post('delivery_datetime')))) : null;
			//calculate credit per
			$total_ear_cr_amount = 0;
			$order_credit_per = $this->input->post('order_credit_per');
			$used_credit_v = $this->input->post('used_credit');

			if(!empty($order_credit_per)):
				$ord_total_amount = $final_order_amount + $shipping_charge + $preparation_cost + $packaging_cost + $order_tip + $used_credit_v - $discount_amount;
				$total_ear_cr_amount = ($order_credit_per/100) * $ord_total_amount;
				if(!empty($total_ear_cr_amount)):
					$crtr_data = array(
						'amount' => number_format($total_ear_cr_amount,2),
					);
					$this->credittransaction_model->update_earned_credittrans($crtr_data, $order_id);
				endif;
			endif;
			
			
			$order_total_amount = $total_order_amount + $shipping_charge + $preparation_cost + $packaging_cost + $order_tip + $used_credit_v - $discount_amount;
			// if($total_updated_order_diff > 0){
			// 	$diff_c_dis = $orderdetails['totaldiff_coupon_discount'];
			// 	$order_data = array(
			// 		'order_amount' => trim($total_order_amount),
			// 		'fedex_shipping_charge' => trim($shipping_charge),
			// 		'order_tip' => trim($order_tip),
			// 		'order_total_tax' => trim($order_total_tax),
			// 		'discount_amount' => trim($discount_amount + $cal_per_amt),
			// 		'order_total_amount' => trim($order_total_amount),
			// 		'diff_amount' => trim($total_updated_order_diff),
			// 		'diff_coupon_discount' => trim($cal_per_amt),
			// 		'totaldiff_coupon_discount' => trim($diff_c_dis + $cal_per_amt),
			// 	);
			// }else{
				$order_data = array(
					'order_amount' => trim($total_order_amount),
					'fedex_shipping_charge' => trim($shipping_charge),
					'order_tip' => trim($order_tip),
					'order_total_tax' => trim($order_total_tax),
					'discount_amount' => trim($discount_amount),
					'order_total_amount' => trim($order_total_amount),
					'delivery_datetime' => $delivery_datetime,
				);
			//}
			
			if ($order_status != '') {
				$order_data['order_status'] = trim($order_status);
			}
			$this->order_model->update($order_id, $order_data);
			//-------------------Update Order master end-------------------


			//-------------------Send mail when order stsatus is Out For Delivery-------------------

			if ($order_status == 'Out For Delivery') {
				$ArrOrderDetails = $this->order_model->getOrderById($order_id);

				$email = $ArrOrderDetails['shipping_email'];
				$name = $ArrOrderDetails['shipping_first_name'];
				$order_date = $ArrOrderDetails['order_datetime'];
				$order_id = $ArrOrderDetails['order_id'];
				$customer_name = $ArrOrderDetails['shipping_first_name'] . " " . $ArrOrderDetails['shipping_last_name'];
				$customer_email = $ArrOrderDetails['shipping_email'];
				$order_delivey_time = $ArrOrderDetails['delivery_datetime'];
				$address = $ArrOrderDetails['shipping_street_name'] . " " . $ArrOrderDetails['shipping_apartment_name'] . "<br>" . $ArrOrderDetails['shipping_city'] . " " . $ArrOrderDetails['shipping_zipcode'] . "<br>Phone: " . $ArrOrderDetails['shipping_phone'] . "<br>Email: " . $ArrOrderDetails['shipping_email'];

				$replace_item_text='No, please refund for unavailable items';
				if ($ArrOrderDetails['is_replace_item'] == 1) {
					$replace_item_text='Yes, please substitute unavailable items with similar products';
				}

				$order_product_data='';
				// $ArrOrderProduct = $this->order_product_model->getOrderProductByOrderId($order_id);
				// foreach ($ArrOrderProduct as $arr) {
				// 	if ($arr['old_qty'] > 0) {
				// 		$qty=$arr['old_qty'];
				// 	}else{
				// 		$qty=$arr['qty'];
				// 	}
				// 	$product_tax='NT';
				// 	if (!empty($arr['product_tax'])) {
				// 		$product_tax='T';
				// 	}
				// 	$order_product_data .='<tr>
				// 		<td>'.$arr['product_name'].'</td>
				// 		<td>$'.$arr['unit_price'].'</td>
				// 		<td>'.$qty.'</th>
				// 	   	<td>'.$product_tax.'</td>
				// 	   	<td>$'.$arr['total_amount'].'</td>
				// 	</tr>';
				// }
				// $order_product_data .='<tr>
				// 	<td colspan="4" class="right-txt">Shipping Charge :</td>
				// 	<td>$'.$ArrOrderDetails['fedex_shipping_charge'].'</td>
				// </tr>';
				// $order_product_data .='<tr>
				// 	<td colspan="4" class="right-txt">Tip Amount :</td>
				// 	<td>$'.$ArrOrderDetails['order_tip'].'</td>
				// </tr>';
				// $order_product_data .='<tr>
				// 	<td colspan="4" class="right-txt">Order Discount :</td>
				// 	<td>$'.$ArrOrderDetails['discount_amount'].'</td>
				// </tr>';
				// $order_product_data .='<tr>
				// 	<td colspan="4" class="right-txt">Preparation Cost :</td>
				// 	<td>$'.$ArrOrderDetails['preparation_cost'].'</td>
				// </tr>';
				// $order_product_data .='<tr>
				// 	<td colspan="4" class="right-txt">Packaging Cost :</td>
				// 	<td>$'.$ArrOrderDetails['packaging_cost'].'</td>
				// </tr>';
				// $order_product_data .='<tr>
				// 	<td colspan="4" class="right-txt">Total :</td>
				// 	<td>$'.$ArrOrderDetails['order_total_amount'].'</td>
				// </tr>';

				//send email
				$subject = $name . ", your order is out for delivery";
				$order_content = file_get_contents('templates/out_of_delivery.html');
				// $order_content_pdf = file_get_contents('templates/out_of_delivery_pdf.html');

				$arr_replace = array('##order_date##', '##order_id##', '##customer_name##', '##order_delivey_time##', '##address##','##customer_email##','##shipping_first_name##','##shipping_last_name##','##shipping_street_name##','##shipping_city##','##shipping_state##','##shipping_country##','##shipping_zipcode##','##shipping_phone##','##billing_first_name##','##billing_last_name##','##billing_street_name##','##billing_city##','##billing_state##','##billing_country##','##billing_zipcode##','##billing_phone##','##order_product_data##','##replace_item_text##','##order_status##');
				$arr_replace_with = array($order_date, $order_id, $customer_name, $order_delivey_time, $address,$customer_email,$ArrOrderDetails['shipping_first_name'],$ArrOrderDetails['shipping_last_name'],$ArrOrderDetails['shipping_street_name'] .' '. $ArrOrderDetails['shipping_apartment_name'] , $ArrOrderDetails['shipping_city'] , $ArrOrderDetails['shipping_state_name'] , 'United States' , $ArrOrderDetails['shipping_zipcode'] , $ArrOrderDetails['shipping_phone'],$ArrOrderDetails['shipping_first_name'],$ArrOrderDetails['shipping_last_name'],$ArrOrderDetails['billing_street_name'] .' '. $ArrOrderDetails['billing_apartment_name'] , $ArrOrderDetails['billing_city'] , $ArrOrderDetails['billing_state_name'] , 'United States' , $ArrOrderDetails['billing_zipcode'] , $ArrOrderDetails['billing_phone'],$order_product_data,$replace_item_text,$order_status);
				$order_content = str_replace($arr_replace, $arr_replace_with, $order_content);
				// $order_content_pdf = str_replace($arr_replace, $arr_replace_with, $order_content_pdf);

				// $this->load->library('pdf');
				// $this->dompdf->loadHtml($order_content_pdf);
				// $this->dompdf->setPaper('A4', 'potrait');
				// $this->dompdf->render();
				// // Get the PDF content
				// $pdf_content = $this->dompdf->output();
				// // Path to save the PDF file temporarily
				// $file_path = "./uploads/attach/".$order_id."_invoice.pdf";
				// // Save the PDF file locally
				// file_put_contents($file_path, $pdf_content);

				// send_mail($email, $subject, $order_content,$file_path);
				send_mail($email, $subject, $order_content);


				//send sms
				$SMSbody = "Great news, " . $name . "! Your VrajFresh package is out for delivery and should arrive today.";
				sendSMS($ArrOrderDetails['shipping_phone'], $SMSbody);
			}

			//-------------------Send mail when order stsatus is Out For Delivery-------------------



			//-------------------Send mail when order stsatus is completed-------------------

			if ($order_status == 'Completed') {
				$ArrOrderDetails = $this->order_model->getOrderById($order_id);

				$email = $ArrOrderDetails['shipping_email'];
				$name = $ArrOrderDetails['shipping_first_name'];
				$order_date = $ArrOrderDetails['order_datetime'];
				$order_id = $ArrOrderDetails['order_id'];
				$customer_name = $ArrOrderDetails['shipping_first_name'] . " " . $ArrOrderDetails['shipping_last_name'];
				$customer_email = $ArrOrderDetails['shipping_email'];
				$order_delivey_time = $ArrOrderDetails['delivery_datetime'];
				$address = $ArrOrderDetails['shipping_street_name'] . " " . $ArrOrderDetails['shipping_apartment_name'] . "<br>" . $ArrOrderDetails['shipping_city'] . " " . $ArrOrderDetails['shipping_zipcode'] . "<br>Phone: " . $ArrOrderDetails['shipping_phone'] . "<br>Email: " . $ArrOrderDetails['shipping_email'];

				$replace_item_text='No, please refund for unavailable items';
				if ($ArrOrderDetails['is_replace_item'] == 1) {
					$replace_item_text='Yes, please substitute unavailable items with similar products';
				}

				$order_product_data='';
				$ArrOrderProduct = $this->order_product_model->getOrderProductByOrderId($order_id);
				foreach ($ArrOrderProduct as $arr) {
					if ($arr['old_qty'] > 0) {
						$qty=$arr['old_qty'];
					}else{
						$qty=$arr['qty'];
					}
					$product_tax='NT';
					if (!empty($arr['product_tax'])) {
						$product_tax='T';
					}
					$order_product_data .='<tr>
						<td>'.$arr['product_name'].'</td>
						<td>$'.$arr['unit_price'].'</td>
						<td>'.$qty.'</th>
					   	<td>'.$product_tax.'</td>
					   	<td>$'.$arr['total_amount'].'</td>
					</tr>';
				}
				$order_product_data .='<tr>
					<td colspan="4" class="right-txt">Shipping Charge :</td>
					<td>$'.$ArrOrderDetails['fedex_shipping_charge'].'</td>
				</tr>';
				$order_product_data .='<tr>
					<td colspan="4" class="right-txt">Tip Amount :</td>
					<td>$'.$ArrOrderDetails['order_tip'].'</td>
				</tr>';
				$order_product_data .='<tr>
					<td colspan="4" class="right-txt">Order Discount :</td>
					<td>$'.$ArrOrderDetails['discount_amount'].'</td>
				</tr>';
				$order_product_data .='<tr>
					<td colspan="4" class="right-txt">Preparation Cost :</td>
					<td>$'.$ArrOrderDetails['preparation_cost'].'</td>
				</tr>';
				$order_product_data .='<tr>
					<td colspan="4" class="right-txt">Packaging Cost :</td>
					<td>$'.$ArrOrderDetails['packaging_cost'].'</td>
				</tr>';
				$order_product_data .='<tr>
					<td colspan="4" class="right-txt">Total :</td>
					<td>$'.$ArrOrderDetails['order_total_amount'].'</td>
				</tr>';

				//send email
				$subject = "Delivered: items from order #".$order_id;
				$order_content = file_get_contents('templates/completed_mail.html');
				$order_content_pdf = file_get_contents('templates/completed_pdf.html');

				$arr_replace = array('##order_date##', '##order_id##', '##customer_name##', '##order_delivey_time##', '##address##','##customer_email##','##shipping_first_name##','##shipping_last_name##','##shipping_street_name##','##shipping_city##','##shipping_state##','##shipping_country##','##shipping_zipcode##','##shipping_phone##','##billing_first_name##','##billing_last_name##','##billing_street_name##','##billing_city##','##billing_state##','##billing_country##','##billing_zipcode##','##billing_phone##','##order_product_data##','##replace_item_text##','##order_status##');
				$arr_replace_with = array($order_date, $order_id, $customer_name, $order_delivey_time, $address,$customer_email,$ArrOrderDetails['shipping_first_name'],$ArrOrderDetails['shipping_last_name'],$ArrOrderDetails['shipping_street_name'] .' '. $ArrOrderDetails['shipping_apartment_name'] , $ArrOrderDetails['shipping_city'] , $ArrOrderDetails['shipping_state_name'] , 'United States' , $ArrOrderDetails['shipping_zipcode'] , $ArrOrderDetails['shipping_phone'],$ArrOrderDetails['shipping_first_name'],$ArrOrderDetails['shipping_last_name'],$ArrOrderDetails['billing_street_name'] .' '. $ArrOrderDetails['billing_apartment_name'] , $ArrOrderDetails['billing_city'] , $ArrOrderDetails['billing_state_name'] , 'United States' , $ArrOrderDetails['billing_zipcode'] , $ArrOrderDetails['billing_phone'],$order_product_data,$replace_item_text,$order_status);
				$order_content = str_replace($arr_replace, $arr_replace_with, $order_content);
				$order_content_pdf = str_replace($arr_replace, $arr_replace_with, $order_content_pdf);

				$this->load->library('pdf');
				$this->dompdf->loadHtml($order_content_pdf);
				$this->dompdf->setPaper('A4', 'potrait');
				$this->dompdf->render();
				// Get the PDF content
				$pdf_content = $this->dompdf->output();

				// Path to save the PDF file temporarily
				$file_path = "./uploads/attach/".$order_id."_invoice.pdf";

				// Save the PDF file locally
				file_put_contents($file_path, $pdf_content);


				send_mail($email, $subject, $order_content,$file_path);
				
				// old code bkp
				// $subject = "Delivered: items from order #".$order_id;
				// $order_content = file_get_contents('templates/completed_mail.html');
				// // for link to direct in order page
				// $order_content = str_replace('##order_date##', $order_date, $order_content);
				// $order_content = str_replace('##order_id##', $order_id, $order_content);
				// $order_content = str_replace('##name##', $name, $order_content);
				// $order_content = str_replace('##completed_date##', date('Y-m-d H:i:s'), $order_content);
				// send_mail($email, $subject, $order_content);

				//send sms
				$SMSbody = "Your VrajFresh order was delivered. Thanks for shopping with us!";
				sendSMS($ArrOrderDetails['shipping_phone'], $SMSbody);
			}

			//-------------------Send mail when order stsatus is completed-------------------

			if ($order_status == 'Refunded') {
				$ArrOrderDetails = $this->order_model->getOrderById($order_id);
				//call function in strip controller (API)
				$url = API_URL . 'stripe/payment_cancel';
				$ArrPayment = array('stripeToken' => $stripeToken, 'payment_intent_id' => $payment_intent_id);
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

				$response = json_decode($response);

				//send sms
				$SMSbody = "Good news! A refund was issued for Vraj Fresh order " . $order_id . ". We'll send you an email for your records.";
				sendSMS($ArrOrderDetails['shipping_phone'], $SMSbody);
			}

			if ($order_status == 'Cancelled') {
				$ArrOrderDetails = $this->order_model->getOrderById($order_id);
				$crtr_data = array(
					'amount' => number_format(0,2),
				);
				$this->credittransaction_model->update_earned_credittrans($crtr_data, $order_id);
				//send sms
				$SMSbody = "As requested, we've canceled your Vraj Fresh order. View updated order details: https://www.vrajfresh.com/my-orders";
				sendSMS($ArrOrderDetails['shipping_phone'], $SMSbody);
			}
		}
		if ($flag) {
			$this->session->set_flashdata('success_message', 'Order details has been updated successfully.');
		} else {
			$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
		}
		// redirect('orders');
	}


	public function remove_order_product($order_product_id)
	{
		$this->order_product_model->delete($order_product_id);
	}

	public function getCartProductRow($product_counter)
	{
		$ArrProducts = $this->product_model->product_list_data_with_variant();
		?>
		<tr>
			<td>
				<?php
				$i=0;
				$name = 'ArrNewProducts[]';
				$ArrOptions = array('' => "Select");
				foreach ($ArrProducts as $key => $value) {
					
					if (isset($value['id']) && $value['id'] > 0) {
						$val = $value['product_id'] . "|" . $value['id'] . "|" . $value['variant_price'] . "|" . $value['product_name'] . "|" . $value['product_tax'];
						$data = $value['product_name'] . " - " . $value['product_variant_size'] . " gms";
					} else {
						$val = $value['product_id'] . "||" . $value['product_price'] . "|" . $value['product_name'] . "|" . $value['product_tax'];
						$data = $value['product_name'];
					}

					$ArrOptions[$val] = $data;
				}
				$html_element = 'class="form-control select_product_ajax" required onChange="SetPrice(this.value,' . $product_counter . ');"';
				echo form_dropdown($name, $ArrOptions, '', $html_element);
				?>
			</td>
			<td>
				<input type="text" placeholder="QTY" class="form-control qty only_number" id="qty<?php echo $product_counter; ?>"
					name="ArrNewQty[]" value="" required
					onChange="updateProductTotalAmount(this.value,<?php echo $product_counter; ?>);">
			</td>

			<td>
				<input type="text" placeholder="Unit Price" class="form-control" id="unit_price<?php echo $product_counter; ?>"
					name="ArrNewUnit_price[]" value="" required readonly>
			</td>
			<td>
				<input type="text" placeholder="Product Tax" class="form-control total_taxamount"
					id="product_tax<?php echo $product_counter; ?>" name="ArrNewproduct_tax[]" value="" required readonly>
			</td>

			<td>
				<input type="text" placeholder="Total Amount" class="form-control total_amount"
					id="total_amount<?php echo $product_counter; ?>" name="ArrNewTotal_amount[]" value="" required readonly>
				<input type="hidden"  class="form-control total_new_amount"
					id="total_new_amount<?php echo $product_counter; ?>" value="" >
			</td>
			<td>
				<a href="javascript:void(0);" class="remove-button" title="Click here to remove product">
					<img src="<?php echo admin_media(); ?>dist/img/close-2.png">
				</a>
			</td>
		</tr>

		<?php
	}

	public function order_packlist_pdf(){
		if(isset($_REQUEST['product_ids'])){
			$product_ids = $_REQUEST['product_ids'];
			$product_ids = explode(',',$product_ids);
			$product_ids_name = implode('_',$product_ids);
			$order_products=$this->order_product_model->getCategoryWiseOrderProducts($product_ids);
			$arr=[
				'order_products'=>$order_products,
				'product_ids'=>$product_ids,
			];
			$html =$this->load->view('admin_pdf/order_packlist',$arr ,true);

			// echo '<pre>';
			// print_r($html);
			// exit;
			ini_set('memory_limit', '256M');
			// header("Content-type: application/pdf");
			// header("Content-Disposition: inline; filename=packlist_bulk_$product_ids_name.pdf");
			$this->load->library('pdf');
			$this->dompdf->loadHtml($html);
			$this->dompdf->setPaper('A4', 'potrait');
			$this->dompdf->render();

    		// Output the generated PDF to Browser
    		$this->dompdf->stream("picklist_bulk_".$product_ids_name.".pdf");
			exit;

			// Get the PDF content
			// $pdf_content = $this->dompdf->output();
			// echo $pdf_content;


		}else{
			echo 'please select order';
		}
	}
	
	public function send_test_email(){
		if(send_mail('hardikvd@vedikin.com', "Email From Vraj Fresh", " Hello!<br/> This is just a test email from Vraj Fresh Live.")){
			echo "Email Sent";
		}
		else {
			echo 'Error while sending email';
		}
		
		
	}

	public function order_label_pdf(){
		if(isset($_POST['order_id'])){
			$no_of_box = $_REQUEST['no_of_box'];
			$order_data = $this->order_model->getOrderById($_POST['order_id']);
			//print_r($order_data);exit;
			$arr=[
				'order_data'=>$order_data,
				'no_of_box'=>$no_of_box,
			];
			$html =$this->load->view('admin_pdf/order_sticker',$arr ,true);

			ini_set('memory_limit', '256M');
			$this->load->library('pdf');
			$this->dompdf->loadHtml($html);
			$this->dompdf->setPaper('A6', 'potrait');
			$this->dompdf->render();

    		// Output the generated PDF to Browser
    		$this->dompdf->stream("order_sticker".$_POST['order_id'].".pdf");
			exit;

		}else{
			echo 'please select order';
		}
	}


}