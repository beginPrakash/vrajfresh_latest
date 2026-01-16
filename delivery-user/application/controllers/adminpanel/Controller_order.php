<?php

class Controller_order extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('order_model');
		$this->load->model('common_model');
		$this->load->model('user_model');
		$this->load->model('order_product_model');
		$this->load->model('Master_model', 'master');
		$this->load->library('image_lib');
		$this->module_name = 'order';

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	public function index()
	{
		$login_duser_id = $this->session->userdata('admin_id');
		$ArrData = $this->order_model->getOrderListData($_POST,$login_duser_id);

		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());


			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();

				$base_url = base_url();
				$order_id = $aRow['order_id'];
				$actions = '';
				
				$actions .= '<a href="' . $base_url . 'view-order/' . $order_id . '" title="Order Edit" class=" btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$row[] = $actions;
				$row[] = $aRow['order_id'];
				$row[] = $aRow['display_name'];
				$row[] = $aRow['mobile_no'];
				$row[] = $aRow['shipping_city'];
				$row[] = $aRow['shipping_zipcode'];
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
		

		
		$ArrPageData['ArrUser'] = $this->user_model->getUserByID($ArrPageData['ArrFieldData']['user_id']);
		
		$ArrPageData['ArrOrderStatus'] = getOrderStatus();

		$ArrPageData['view_name'] = 'view_' . $this->module_name . '_details.php';

		$js_assets = array(

			//array(ADMIN_PANEL_THEME_PATH . 'dist/js/' . $this->module_name . '-script.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);

		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}


	public function update_order_process()
	{
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/admin/uploads/products/';
		$config['upload_path'] = $uploadPath; // set the filter image types
		$config['allowed_types'] = 'gif|jpg|jpeg|png'; //load the upload library
		$config['file_name'] = GUID();
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$this->upload->set_allowed_types('*');

		$flag = false;
		$order_id = $this->input->post('order_id');
		if ($order_id > 0) {


			$order_data = array(
					'order_status' => 'Completed',
				);	
			
		//	if (isset($_FILES['delivery_attachment']) && is_uploaded_file($_FILES['delivery_attachment']['tmp_name'])) {
		 if (!empty($_FILES['delivery_attachment']['name'])) {

				if (!$this->upload->do_upload('delivery_attachment')) {
					$data = array('error_message' => $this->upload->display_errors());
				} else {
					$data['delivery_attachments'] = $this->upload->data();
					$order_data['delivery_attachment'] = $data['delivery_attachments']['file_name'];
				}
			
			}

$flag = true;
			$this->order_model->update($order_id, $order_data);
			//-------------------Update Order master end-------------------

			//-------------------Send mail when order stsatus is completed-------------------


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
				$arr_replace_with = array($order_date, $order_id, $customer_name, $order_delivey_time, $address,$customer_email,$ArrOrderDetails['shipping_first_name'],$ArrOrderDetails['shipping_last_name'],$ArrOrderDetails['shipping_street_name'] .' '. $ArrOrderDetails['shipping_apartment_name'] , $ArrOrderDetails['shipping_city'] , $ArrOrderDetails['shipping_state_name'] , 'United States' , $ArrOrderDetails['shipping_zipcode'] , $ArrOrderDetails['shipping_phone'],$ArrOrderDetails['shipping_first_name'],$ArrOrderDetails['shipping_last_name'],$ArrOrderDetails['billing_street_name'] .' '. $ArrOrderDetails['billing_apartment_name'] , $ArrOrderDetails['billing_city'] , $ArrOrderDetails['billing_state_name'] , 'United States' , $ArrOrderDetails['billing_zipcode'] , $ArrOrderDetails['billing_phone'],$order_product_data,$replace_item_text,$ArrOrderDetails['order_status']);
				$order_content = str_replace($arr_replace, $arr_replace_with, $order_content);
				$order_content_pdf = str_replace($arr_replace, $arr_replace_with, $order_content_pdf);

				$this->load->library('pdf');
				$this->dompdf->loadHtml($order_content_pdf);
				$this->dompdf->setPaper('A4', 'potrait');
				$this->dompdf->render();
				// Get the PDF content
				$pdf_content = $this->dompdf->output();
				$attachments = [];
				// Path to save the PDF file temporarily
				$invoice_path = $_SERVER['DOCUMENT_ROOT'] . "/admin/uploads/attach/".$order_id."_invoice.pdf";
			

				// Save the PDF file locally
				file_put_contents($invoice_path, $pdf_content);
				$attachments[] = $invoice_path;

				// Delivery image (uploaded file)
				if (!empty($_FILES['delivery_attachment']['name'])) {
					$attachments[] = $uploadPath.'/'.$ArrOrderDetails['delivery_attachment'];
				}

				send_mail($email, $subject, $order_content,$attachments);
				
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

			//-------------------Send mail when order stsatus is completed-------------------

		}
		if ($flag) {
			$this->session->set_flashdata('success_message', 'Order details has been updated successfully.');
		} else {
			$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
		}
		// redirect('orders');
	}


}