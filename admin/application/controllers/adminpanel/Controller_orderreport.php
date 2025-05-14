<?php

class Controller_orderreport extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('orderreport_model');
		$this->load->model('common_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	public function index()
	{
		$ArrData = $this->orderreport_model->getOrderListData($_POST);
        
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());


			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();
				$city = '';
				$state = '';
				$zip = '';
				if(!empty($aRow['city'])){
					$city= ', '.$aRow['city'];
				}
				if(!empty($aRow['state'])){
					$state = ', '.$aRow['state'];
				}
				if(!empty($aRow['zip'])){
					$zip = ', '.$aRow['zip'];
				}
				// $get_total_order_unitcost = $this->orderreport_model->get_total_order_unitcost($aRow['order_id']);
				// echo $get_total_order_unitcost;exit;
				$row[] = $aRow['user_id'];
				$row[] = $aRow['order_id'];
				$row[] = $aRow['order_datetime'];
				$row[] = $aRow['display_name'];
				$row[] = $aRow['email'];
				$row[] = $aRow['address'].''.$city.''.$zip;
				$row[] = $aRow['mobile_no'];
				$row[] = $aRow['order_status'];
				$row[] = $aRow['order_total_amount'];
				


				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Order Reports';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_orderreport_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}



    public function order_list_export() 
	{	
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->orderreport_model->ExportReportOrderData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$city = '';
				$state = '';
				$zip = '';
				if(!empty($ArrData['city'])){
					$city= ', '.$ArrData['city'];
				}
				if(!empty($ArrData['state'])){
					$state = ', '.$ArrData['state'];
				}
				if(!empty($ArrData['zip'])){
					$zip = ', '.$ArrData['zip'];
				}

			$row[] = $ArrData['user_id'];
			$row[] = $ArrData['order_id'];
			$row[] = $ArrData['order_datetime'];
			$row[] = $ArrData['display_name'];
			$row[] = $ArrData['email'];
			$row[] = $ArrData['address'].''.$city.''.$zip;
			$row[] = $ArrData['mobile_no'];
			$row[] = $ArrData['order_status'];
			$row[] = $ArrData['order_total_amount'];
			$data[] = $row;
    	}
		$report_title = "order_report_".time();
		$ArrHeading = array('Customer ID','Order Number','Date','Customer Name','Email Address','Local Address','Phone No','Order Status','Total amount');
		array_to_csv($ArrHeading,$data,$report_title);		
	}


}