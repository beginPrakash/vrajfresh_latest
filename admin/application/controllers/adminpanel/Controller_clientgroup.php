<?php
class Controller_clientgroup extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->temp_clientgroup_id = 999999999;
		$this->load->model('clientgroup_model');
		$this->load->model('user_model');
		$this->load->model('clientgroup_details_model');


		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('admin-login');
		}

	}
	public function index()
	{
		$ArrData = $this->clientgroup_model->getClientGroupListData($_POST);
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/clientgroup-script.js')
		);
		$this->carabiner->js($js_assets);
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {

				$row = array();
				$base_url = base_url();
				$clientgroup_id = $aRow['clientgroup_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $clientgroup_id . '" /><div class="btn-group">';
				//$actions .='<a rel="'.$base_url.'adminpanel/controller_promotional_code/view_promotional_code_ajax/" id="'.$clientgroup_id.'" title="Promotional Code Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				/* $actions .= '<a href="'.$base_url.'clientgroup-update/'.$clientgroup_id.'" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
							   $actions .= '<a rel="'.$base_url.'clientgroup-delete/'.$clientgroup_id.'" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>'; */

				$actions .= '&nbsp; <a rel="' . $base_url . 'adminpanel/controller_clientgroup/view_clientgroup_ajax/" id="' . $clientgroup_id . '" title="ClientGroup Detail" class="view_action btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '&nbsp; <a href="' . base_url() . 'adminpanel/controller_clientgroup/exportGroup/' . $clientgroup_id . '"> <img src="' . admin_media() . 'dist/img/icon_csv.png" height="20" width="20" title="CSV"></a>';


				$row[] = $actions;
				$row[] = $aRow['clientgroup_id'];
				$row[] = date('d-m-Y', strtotime($aRow['created_date']));
				$row[] = $aRow['clientgroup_title'];

				$row[] = getIsactiveButtonForList($aRow['is_active'], $clientgroup_id, 'tblclientgroup', 'clientgroup_id');
				$i++;
				$output['aaData'][] = $row;

			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Customer Group List';
		$ArrPageData['button_url'] = base_url() . 'clientgroup-step1';
		$ArrPageData['button_label'] = 'Add Customer Group';
		$ArrPageData['view_name'] = 'view_clientgroup_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function clientgroup_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->clientgroup_model->ExportClientGroupData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['clientgroup_id'];
			$row[] = date('d-m-Y', strtotime($ArrData['created_date']));
			$row[] = $ArrData['clientgroup_title'];
			$row[] = $ArrData['is_active'];
			$data[] = $row;
		}
		$report_title = "qe_clientgroup_list_" . time();
		$ArrHeading = array('Sr No', 'Customer Group Id', 'Website', 'Created Date', 'Customer Group Title', 'Active');
		array_to_csv($ArrHeading, $data, $report_title);

	}

	public function view_clientgroup_ajax()
	{
		$group_id = $this->input->post('id');
		//echo $group_id;exit;
		$data['ArrGroupClient'] = $this->clientgroup_details_model->getGroupClient($group_id);
		//print_r($data['ArrGroupClient']);exit;
		$this->load->view('admin_panel/view_client_group_details', $data);
	}

	public function view_clientgroup_payment_ajax()
	{
		$id = $this->input->post('id');
		$data['ArrFieldData'] = $this->clientgroup_model->getClientGroupAllPaymentDetailsById($id);
		$this->load->view('admin_panel/view_client_group_details', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->clientgroup_model->delete($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}



	public function delete_multiple_promotional_code_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->clientgroup_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}


	public function exportGroup($clientgroup_id)
	{
		$Arrclientgroup = $this->clientgroup_model->getClientGroupById($clientgroup_id);

		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');

		$this->db->select('first_name,email,DATE_FORMAT(tbl_users.created_datetime, "%d-%b-%Y") as format_joining_date');
		$this->db->from('tblclientgroup_details');
		$this->db->join('tbl_users', 'tbl_users.user_id = tblclientgroup_details.user_id', 'JOIN');


		$this->db->where('clientgroup_id', $clientgroup_id);
		$query_result = $this->db->get();
		//echo '<pre>'; print_r( $this->db->last_query());exit;		
		$delimiter = ",";
		$newline = "\r\n";
		$filename = $Arrclientgroup[0]['clientgroup_title'] . "_client_group.csv";
		$data = $this->dbutil->csv_from_result($query_result, $delimiter, $newline);
		force_download($filename, $data);
	}

	#view group wise client in popup
	/*public function viewClientGroupDetails()	
	   {
		   //echo "test";
		   $data['clientgroup_id'] = $clientgroup_id;
		   $data['ArrGroupClient'] = $this->clientgroup_details_model->getGroupClient($clientgroup_id);
		   $this->load->view('admin_panel/quickview/view_clientgroup_details_popup',$data);
	   }*/

	public function removeClientGroupCustomer($redirect = 0)
	{

		$checked_prodid = $this->input->post('delete'); //selected messages	 
		$status = $this->clientgroup_details_model->removeClientGroupCustomer($checked_prodid);
		if ($status == 1) {
			$this->session->set_flashdata('success_msg', 'Customer Group Deleted Successfully. ');
			//redirect('admin/controller_clientgroup');
		} else {
			$this->session->set_flashdata('error', 'You can not delete this clientgroup');
			//redirect('admin/controller_clientgroup');
		}
		if ($redirect == 0) {
			$this->session->set_flashdata('success_msg', 'Customer has been deleted successfully.');
			redirect('adminpanel/controller_clientgroup');
		} else {
			redirect('adminpanel/controller_clientgroup/create_step2');
		}
	}

	#ADD/EDIT Portfolio
	public function step1()
	{

		#DELETE PAST RECORDS IF ANY
		$this->clientgroup_details_model->deleteGroupClient($this->temp_clientgroup_id);

		#get products
		$ArrProducts = $this->clientgroup_model->getAllProducts();
		$ArrPageData = array();
		$ArrPageData['ArrProducts'] = $ArrProducts;

		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$ArrUserclientType = array();
			$ArrUserSignUpDate = array();
			$ArrUserPaid = array();
			$ArrNumberOfOrder = array();
			$ArrLastOrderDate = array();
			$ArrUserNoOrderInLastDays = array();
			$ArrUserOrderProduct = array();
			$ArrCustomers = array();




			$ArrUserSignUpDateFlag = false;
			$ArrUserPaidFlag = false;
			$ArrUserTotalUnPaidOrderAmountFlag = false;
			$ArrNumberOfOrderFlag = false;
			$ArrLastOrderDateFlag = false;
			$ArrUserNoOrderInLastDaysFlag = false;
			$ArrUserOrderProductFlag = false;
			$ArrCustomersFlag = false;


			/* ---------------USER FILTER START---------------- */

			#Customer Signed up date filter
			if ((isset($_POST['user_signup_date']) && $_POST['user_signup_date'] != '') || (isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to'] != '')) {
				$condition = '';
				if (isset($_POST['user_signup_date']) && $_POST['user_signup_date'] != '') {
					$user_signup_date = $_POST['user_signup_date'];
					$Arruser_signup_date = explode('-', $user_signup_date);
					$user_signup_date = $Arruser_signup_date[2] . "-" . $Arruser_signup_date[1] . "-" . $Arruser_signup_date[0] . "  00:00:00";
					$condition .= " AND created_datetime >= '" . $user_signup_date . "'";
				}
				if (isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to'] != '') {
					$user_signup_date_to = $_POST['user_signup_date_to'];
					$Arruser_signup_date_to = explode('-', $user_signup_date_to);
					$user_signup_date_to = $Arruser_signup_date_to[2] . "-" . $Arruser_signup_date_to[1] . "-" . $Arruser_signup_date_to[0] . "  23:59:59";
					$condition .= " AND created_datetime <= '" . $user_signup_date_to . "'";
				}
				$qry = "SELECT user_id from tbl_users where user_role_id=4" . $condition;


				$ArrUserSignUpDate = $this->clientgroup_model->getFilterUserId($qry, 'user_id');
				$ArrUserSignUpDateFlag = true;

			}


			#Amount paid till date filter
			if ((isset($_POST['amount_paid']) && $_POST['amount_paid'] != '') || (isset($_POST['amount_paid_to']) && $_POST['amount_paid_to'] != '')) {
				#get total order amount user wise
				$condition = ' AND u.user_role_id=4';
				if (isset($_POST['user_signup_date']) && $_POST['user_signup_date'] != '') {
					$user_signup_date = $_POST['user_signup_date'];
					$Arruser_signup_date = explode('-', $user_signup_date);
					$user_signup_date = $Arruser_signup_date[2] . "-" . $Arruser_signup_date[1] . "-" . $Arruser_signup_date[0] . "  00:00:00";
					$condition .= " AND u.created_datetime >= '" . $user_signup_date . "'";
				}
				if (isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to'] != '') {
					$user_signup_date_to = $_POST['user_signup_date_to'];
					$Arruser_signup_date_to = explode('-', $user_signup_date_to);
					$user_signup_date_to = $Arruser_signup_date_to[2] . "-" . $Arruser_signup_date_to[1] . "-" . $Arruser_signup_date_to[0] . "  23:59:59";
					$condition .= " AND u.created_datetime  <= '" . $user_signup_date_to . "'";
				}
				$qry = "SELECT u.user_id FROM tbl_users u LEFT JOIN tbl_orders b ON u.user_id = b.user_id WHERE 1=1" . $condition;


				$qry .= " GROUP BY user_id";
				$ArrUserTotalOrderAmount = $this->clientgroup_model->getUserOrderAmount($qry);

				#get outstanding amount
				$qry = "select u.user_id,sum(amount) as amount FROM tblcart_order_payment scpp JOIN tbl_orders o ON o.order_id = scpp.order_id JOIN tbl_users u ON u.user_id = o.user_id WHERE scpp.payment_status='Unpaid'" . $condition;

				$qry .= " GROUP BY o.user_id";

				//echo "<br>amount_ un Unpaid: ".$qry;
				$ArrTempUserTotalUnPaidOrderAmount = $this->clientgroup_model->getUserOrderPaidAmount($qry);

				$amount_paid = $_POST['amount_paid'];
				$amount_paid_to = $_POST['amount_paid_to'];
				$i = 0;
				$ArrUserPaid = array();
				if ($amount_paid >= 0 || $amount_paid_to >= 0) {
					if ($amount_paid == '') {
						$amount_paid = 0;
					}
					if ($amount_paid_to == '') {
						$amount_paid_to = 50000000;
					}
					#check and less unpaid amount from total amount
					foreach ($ArrUserTotalOrderAmount as $customer_id => $order_amount) {
						$unpaidamount = 0;
						if (isset($ArrTempUserTotalUnPaidOrderAmount[$customer_id])) {
							$unpaidamount = $ArrTempUserTotalUnPaidOrderAmount[$customer_id];
						}
						if ((($order_amount - $unpaidamount) >= $amount_paid) && (($order_amount - $unpaidamount) <= $amount_paid_to)) {
							$ArrUserPaid[$i++] = $customer_id;
						}
					}
				}
				$ArrUserPaidFlag = true;
			}

			/*#Amount outstanding till date filter
						   if(isset($_POST['amount_outstanding']) && $_POST['amount_outstanding']!='' || (isset($_POST['amount_outstanding_to']) && $_POST['amount_outstanding_to']!='') )
						   {
								   #get total order amount user wise
							   $condition=' AND u.user_role_id=4';
							   if(isset($_POST['user_signup_date']) && $_POST['user_signup_date']!='')
							   {
								   $user_signup_date = $_POST['user_signup_date'];
								   $Arruser_signup_date = explode('-',$user_signup_date);
								   $user_signup_date = $Arruser_signup_date[2]."-".$Arruser_signup_date[1]."-".$Arruser_signup_date[0]."  00:00:00";
								   $condition .= " AND u.created_datetime  >= '".$user_signup_date."'";
							   }
							   if(isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to']!='')
							   {
								   $user_signup_date_to = $_POST['user_signup_date_to'];
								   $Arruser_signup_date_to = explode('-',$user_signup_date_to);
								   $user_signup_date_to = $Arruser_signup_date_to[2]."-".$Arruser_signup_date_to[1]."-".$Arruser_signup_date_to[0]."  23:59:59";
								   $condition .= " AND u.created_datetime  <= '".$user_signup_date_to."'";
							   }
							   $qry = "SELECT u.user_id FROM tbl_users u LEFT JOIN tbl_orders b ON u.user_id = b.customer_id WHERE 1=1".$condition;
							   
																			   
							   
							   $qry .=" GROUP BY user_id";
							   $ArrUserTotalOrderAmount = $this->clientgroup_model->getUserOrderAmount($qry);
							   
							   #get outstanding amount
							   $qry = "select u.user_id,sum(amount) as amount FROM tblcart_order_payment scpp JOIN tbl_orders o ON o.order_id = scpp.order_id JOIN tbl_users u ON u.user_id = o.user_id WHERE scpp.payment_status='Paid'".$condition;
																									   
							   
							   $qry .=" GROUP BY o.user_id";
							   $ArrTempUserTotalUnPaidOrderAmount = $this->clientgroup_model->getUserOrderPaidAmount($qry);
							   
							   
							   $amount_outstanding = $_POST['amount_outstanding'];
							   $amount_outstanding_to = $_POST['amount_outstanding_to'];
							   
							   $i=0;
							   $ArrUserTotalUnPaidOrderAmount = array();
							   if($amount_outstanding>=0 || $amount_outstanding_to>=0)
							   {
								   if($amount_outstanding=='')
								   {
									   $amount_outstanding = 0;
								   }
								   if($amount_outstanding_to=='')
								   {
									   $amount_outstanding_to = 50000000;
								   }
								   #check and less unpaid amount from total amount
								   foreach($ArrUserTotalOrderAmount as $customer_id=>$order_amount)
								   {
									   //echo "in foreach";
									   $ActualOutstandingAmount = 0;
									   if(isset($ArrTempUserTotalUnPaidOrderAmount[$customer_id]))
									   {
										   $paidamount = $ArrTempUserTotalUnPaidOrderAmount[$customer_id];
										   $ActualOutstandingAmount = $order_amount - $paidamount;
									   }
									   if( ($ActualOutstandingAmount>=$amount_outstanding) && ($ActualOutstandingAmount<=$amount_outstanding_to) )
									   {
										   $ArrUserTotalUnPaidOrderAmount[$i++] = $customer_id;
									   }
								   }
							   }
							   $ArrUserTotalUnPaidOrderAmountFlag = true;
						   }*/

			#Number of orders between filter
			if ((isset($_POST['number_of_order_from']) && $_POST['number_of_order_from'] != '') || (isset($_POST['number_of_order_to']) && $_POST['number_of_order_to'] != '')) {
				$condition = ' AND u.user_role_id=4';
				if (isset($_POST['user_signup_date']) && $_POST['user_signup_date'] != '') {
					$user_signup_date = $_POST['user_signup_date'];
					$Arruser_signup_date = explode('-', $user_signup_date);
					$user_signup_date = $Arruser_signup_date[2] . "-" . $Arruser_signup_date[1] . "-" . $Arruser_signup_date[0] . "  00:00:00";
					$condition .= " AND u.created_datetime  >= '" . $user_signup_date . "'";
				}
				if (isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to'] != '') {
					$user_signup_date_to = $_POST['user_signup_date_to'];
					$Arruser_signup_date_to = explode('-', $user_signup_date_to);
					$user_signup_date_to = $Arruser_signup_date_to[2] . "-" . $Arruser_signup_date_to[1] . "-" . $Arruser_signup_date_to[0] . "  23:59:59";
					$condition .= " AND u.created_datetime  <= '" . $user_signup_date_to . "'";
				}
				$number_of_order_from = $_POST['number_of_order_from'];
				$number_of_order_to = $_POST['number_of_order_to'];

				if ($number_of_order_from == '') {
					$number_of_order_from = 0;
				}
				if ($number_of_order_to == '') {
					$number_of_order_to = 1000;
				}

				$qry = "SELECT u.user_id, COUNT( b.user_id ) ordercnt FROM tbl_users u LEFT JOIN tbl_orders b ON u.user_id = b.user_id WHERE 1=1" . $condition;

				$qry .= " GROUP BY user_id";
				$TempArr = $this->clientgroup_model->getFilterUserId($qry);
				$ArrNumberOfOrder = array();
				$i = 0;
				foreach ($TempArr as $order) {
					if ($order['ordercnt'] >= $number_of_order_from && $order['ordercnt'] <= $number_of_order_to) {
						$ArrNumberOfOrder[$i++] = $order['user_id'];
					}
				}
				$ArrNumberOfOrderFlag = true;
			}

			#Last order date
			if (isset($_POST['last_order_date']) && $_POST['last_order_date'] != '') {
				$condition = ' AND u.user_role_id=4';
				if (isset($_POST['user_signup_date']) && $_POST['user_signup_date'] != '') {
					$user_signup_date = $_POST['user_signup_date'];
					$Arruser_signup_date = explode('-', $user_signup_date);
					$user_signup_date = $Arruser_signup_date[2] . "-" . $Arruser_signup_date[1] . "-" . $Arruser_signup_date[0] . "  00:00:00";
					$condition .= " AND u.created_datetime  >= '" . $user_signup_date . "'";
				}
				if (isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to'] != '') {
					$user_signup_date_to = $_POST['user_signup_date_to'];
					$Arruser_signup_date_to = explode('-', $user_signup_date_to);
					$user_signup_date_to = $Arruser_signup_date_to[2] . "-" . $Arruser_signup_date_to[1] . "-" . $Arruser_signup_date_to[0] . "  23:59:59";
					$condition .= " AND u.created_datetime  <= '" . $user_signup_date_to . "'";
				}


				$last_order_date = $_POST['last_order_date'];
				$Arrlast_order_date = explode('-', $last_order_date);
				$last_order_date = $Arrlast_order_date[2] . "-" . $Arrlast_order_date[1] . "-" . $Arrlast_order_date[0] . " 23:59:59";
				$condition .= " AND o.created_date <= '" . $last_order_date . "'";

				$qry = "SELECT u.user_id, COUNT( o.user_id ) ordercnt FROM tbl_users u LEFT JOIN tbl_orders o ON u.user_id = o.user_id WHERE 1=1" . $condition;

				$qry .= " GROUP BY user_id";
				$TempArr = $this->clientgroup_model->getFilterUserId($qry);

				$ArrLastOrderDate = array();
				$i = 0;
				foreach ($TempArr as $order) {
					$ArrLastOrderDate[$i++] = $order['user_id'];
				}
				$ArrLastOrderDateFlag = true;
			}

			#Number of order in last filter
			if (isset($_POST['no_order_in_last_days']) && $_POST['no_order_in_last_days'] != '') {

				$condition = '';
				if (isset($_POST['user_signup_date']) && $_POST['user_signup_date'] != '') {
					$user_signup_date = $_POST['user_signup_date'];
					$Arruser_signup_date = explode('-', $user_signup_date);
					$user_signup_date = $Arruser_signup_date[2] . "-" . $Arruser_signup_date[1] . "-" . $Arruser_signup_date[0] . "  00:00:00";
					$condition .= " AND u.created_datetime  >= '" . $user_signup_date . "'";
				}
				if (isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to'] != '') {
					$user_signup_date_to = $_POST['user_signup_date_to'];
					$Arruser_signup_date_to = explode('-', $user_signup_date_to);
					$user_signup_date_to = $Arruser_signup_date_to[2] . "-" . $Arruser_signup_date_to[1] . "-" . $Arruser_signup_date_to[0] . "  23:59:59";
					$condition .= " AND u.created_datetime  <= '" . $user_signup_date_to . "'";
				}
				/*
								  CODE COMMENTED BY BBN 19-June 2017 DUE TO CAD NON-ORDER CUSTOMER ISSUE
								  $qry = "select u.user_id FROM tbl_users u JOIN tbl_orders o ON u.user_id = o.user_id WHERE u.user_id NOT IN (select customer_id FROM tbl_orders WHERE order_create_date>= DATE(NOW()) - INTERVAL 1 DAY AND order_create_date<=DATE(NOW()) + INTERVAL ".($no_order_in_last_days-1)." DAY)";
								  */

				$no_order_in_last_days = $_POST['no_order_in_last_days'];

				$qry = "select u.user_id FROM tbl_users u WHERE u.user_id NOT IN (SELECT user_id FROM tbl_orders WHERE created_date>=DATE(NOW()) - INTERVAL " . ($no_order_in_last_days - 1) . " DAY)";



				$qry .= $condition;

				$ArrUserNoOrderInLastDays = $this->clientgroup_model->getFilterUserId($qry, 'user_id');

				$ArrUserNoOrderInLastDaysFlag = true;
			}


			#order product
			if (isset($_POST['selProduct']) && $_POST['selProduct'] != '') {
				$condition = ' AND op.product_id=' . $_POST['selProduct'];

				if (isset($_POST['user_signup_date']) && $_POST['user_signup_date'] != '') {
					$user_signup_date = $_POST['user_signup_date'];
					$Arruser_signup_date = explode('-', $user_signup_date);
					$user_signup_date = $Arruser_signup_date[2] . "-" . $Arruser_signup_date[1] . "-" . $Arruser_signup_date[0] . "  00:00:00";
					$condition .= " AND u.created_datetime  >= '" . $user_signup_date . "'";
				}
				if (isset($_POST['user_signup_date_to']) && $_POST['user_signup_date_to'] != '') {
					$user_signup_date_to = $_POST['user_signup_date_to'];
					$Arruser_signup_date_to = explode('-', $user_signup_date_to);
					$user_signup_date_to = $Arruser_signup_date_to[2] . "-" . $Arruser_signup_date_to[1] . "-" . $Arruser_signup_date_to[0] . "  23:59:59";
					$condition .= " AND u.created_datetime  <= '" . $user_signup_date_to . "'";
				}
				$qry = "SELECT u.user_id FROM tbl_orders o JOIN tbl_users u ON u.user_id = o.user_id JOIN tbl_order_products op ON op.order_id=o.order_id WHERE 1=1" . $condition;


				$qry .= " GROUP BY user_id";
				$ArrUserOrderProduct = $this->clientgroup_model->getFilterUserId($qry, 'user_id');

				$ArrUserOrderProductFlag = true;
			}

			#customer
			if (isset($_POST['ArrCustomer'])) {
				$ArrCustomers = $_POST['ArrCustomer'];

				$ArrCustomersFlag = true;
			}




			$ArrTempUsers = array();
			$tempArrayCount = 0;
			if ($ArrUserSignUpDateFlag) {
				$ArrTempUsers[] = $ArrUserSignUpDate;
				$tempArrayCount++;
			}


			if ($ArrUserPaidFlag) {
				$ArrTempUsers[] = $ArrUserPaid;
				$tempArrayCount++;
			}

			if ($ArrUserTotalUnPaidOrderAmountFlag) {
				$ArrTempUsers[] = $ArrUserTotalUnPaidOrderAmount;
				$tempArrayCount++;
			}

			if ($ArrNumberOfOrderFlag) {
				$ArrTempUsers[] = $ArrNumberOfOrder;
				$tempArrayCount++;
			}

			if ($ArrLastOrderDateFlag) {
				$ArrTempUsers[] = $ArrLastOrderDate;
				$tempArrayCount++;
			}

			if ($ArrUserNoOrderInLastDaysFlag) {
				$ArrTempUsers[] = $ArrUserNoOrderInLastDays;
				$tempArrayCount++;
			}
			if ($ArrUserOrderProductFlag) {
				$ArrTempUsers[] = $ArrUserOrderProduct;
				$tempArrayCount++;
			}
			if ($ArrCustomersFlag) {
				$ArrTempUsers[] = $ArrCustomers;
				$tempArrayCount++;
			}


			if ($tempArrayCount <= 1) {
				$ArrUsers = $ArrTempUsers[0];
			} else {
				$ArrUsers = call_user_func_array('array_intersect', $ArrTempUsers);
			}
			if (is_array($ArrUsers) && count($ArrUsers) > 0) {
				$ArrUsers = array_unique($ArrUsers);
			}

			/* ADD USER START */
			if (is_array($ArrUsers) && count($ArrUsers) > 0) {
				foreach ($ArrUsers as $user_id) {
					$clientgroupdetails_data = array('clientgroup_id' => $this->temp_clientgroup_id, 'user_id' => $user_id);
					$this->clientgroup_details_model->addClientGroupDetails($clientgroupdetails_data);
				}
			}
			redirect('clientgroup-step2');
			/* END */
		} else {
			//$ArrHeaderData['page_title'] = "Add Customer Group - Select Criteria";
			//$this->load->view('admin_panel/header',$ArrHeaderData);
			//$this->load->view('admin_panel/view_clientgroup_step1',$data);
			//$this->load->view('admin_panel/footer');
			$js_assets = array(
				array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/clientgroup-script.js')
			);
			$this->carabiner->js($js_assets);

			$ArrPageData['cms_title'] = 'Add Customer Group';
			$ArrPageData['button_url'] = base_url() . 'clientgroup';
			$ArrPageData['button_label'] = 'View Customer Group';
			$ArrPageData['view_name'] = 'view_clientgroup_step1.php';
			$this->load->view('admin_panel/admin_panel', $ArrPageData);
		}
	}

	public function step2()
	{

		if ($this->input->server('REQUEST_METHOD') === 'POST') {

			if ($this->input->post('client_group') > 0) {
				$clientgroup_id = $this->input->post('client_group');
				$clientgroup_data['modified_by'] = $_SESSION['admin_id'];
				$clientgroup_data['modified_date'] = date('Y-m-d H:i:s');
				$this->clientgroup_model->updateClientGroup($clientgroup_id, $clientgroup_data);
			} else {
				$clientgroup_data['clientgroup_title'] = $this->input->post('clientgroup_title');
				$clientgroup_data['created_by'] = $_SESSION['admin_id'];
				$clientgroup_data['created_date'] = date('Y-m-d H:i:s');
				$clientgroup_id = $this->clientgroup_model->addClientGroup($clientgroup_data);
			}
			$clientgroup_data = array('is_active' => $this->input->post('is_active'));
			if ($clientgroup_id > 0) {
				$this->clientgroup_details_model->updateClientGroupIdInClientGroupDetails($this->temp_clientgroup_id, $clientgroup_id);
			}
			$data['flash_message'] = TRUE;
			redirect('clientgroup');

		} else {
			$ArrPageData['ArrGroupClient'] = $this->clientgroup_details_model->getGroupClient($this->temp_clientgroup_id);

			#get client group
			$ArrClientGroup = $this->clientgroup_model->getClientGroup('is_active="1"');
			//echo "<pre>";print_r($ArrClientGroup);exit;
			$ArrPageData['ArrClientGroup'] = $ArrClientGroup;

			$ArrPageData['cms_title'] = "Add Customer Group - Select Criteria";
			/* $this->load->view('admin/header',$ArrHeaderData);
					 $this->load->view('admin/view_clientgroup_step2',$data);
					 $this->load->view('admin/footer'); */
			$js_assets = array(
				array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/clientgroup-script.js')
			);
			$this->carabiner->js($js_assets);
			$ArrPageData['view_name'] = 'view_clientgroup_step2.php';
			$this->load->view('admin_panel/admin_panel', $ArrPageData);
		}
	}



	public function get_user_email()
	{

		$email_id = $_POST['keyword'];
		$result = $this->user_model->search_customer_detail_by_email($email_id);
		//print_r($result);exit;
		$rawData = null;
		if (!empty($result)) {
			$rawData = "<ul id='country-list'>";
			foreach ($result as $country) {
				$user_email = trim($country["email"]);
				$user_id = trim($country["user_id"]);
				$rawData .= "<li onClick='selectCountry(\"$user_email\",\"$user_id\");'>" . $user_email . "</li>";
			}
			$rawData .= "</ul>";
		}
		if ($rawData) {
			echo $rawData;
		} else {
			$rawData = "<ul id='country-list'>";
			$rawData .= "<li onClick='selectCountry(\"No Match Found\",\"0\");'>No Match Found</li>";
			$rawData .= "</ul>";
			echo $rawData;
		}

	}
	public function check_user_email()
	{

		$email_id = $_POST['keyword'];
		$result = $this->user_model->getUserByEmail($email_id);
		if (!empty($result)) {
			echo "success";
		} else {
			echo "fail";
		}

	}

	public function delete_multiple_clientgroup_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->clientgroup_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

}