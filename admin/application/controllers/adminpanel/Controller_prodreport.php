<?php

class Controller_prodreport extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('prodreport_model');
		$this->load->model('common_model');
        $this->load->model('product_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	public function index()
	{
		$ArrData = $this->prodreport_model->getOrderListData($_POST);
       // echo'<pre>';print_r($ArrData);exit;
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());


			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();
				$row[] = date('d-m-Y',strtotime($aRow['order_datetime']));
				$row[] = $aRow['product_name'];
				$row[] = $aRow['unit_price'];
				$row[] = $aRow['pro_qty'];
				$row[] = $aRow['pro_total_amount'];
				
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Product Reports';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_prodreport_list.php';
        $ArrPageData['product'] = $this->product_model->product_list_data();
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}



    public function order_list_export() 
	{	
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->prodreport_model->ExportReportOrderData($_POST);
		$data = array();
		$no = 0;
        $cat_name = '';
		
		foreach ($ArrDataList as $key => $ArrData) {

			$row = array();
			$row[] = date('d-m-Y',strtotime($ArrData['order_datetime']));
			$row[] = $ArrData['product_name'];
			$row[] = $ArrData['unit_price'];
			$row[] = $ArrData['pro_qty'];
			$row[] = $ArrData['pro_total_amount'];
			$data[] = $row;
			$no++;
    	}
		//exit;
		$report_title = "product_report_".time();
		$ArrHeading = array('Date','Product Name','Product Price','Quantity','Total');
		array_to_csv($ArrHeading,$data,$report_title);		
	}


}