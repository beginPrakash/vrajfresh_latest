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
				$prod_unitcost = $this->prodreport_model->get_prod_unitcost($aRow['product_id'],$aRow['unit_price'],$aRow['product_variant_id']);
				$total_cost = $prod_unitcost ?? 0.00;
				$row = $category_name = array();
				$row[] = date('d-m-Y',strtotime($aRow['order_datetime']));
				$row[] = $aRow['product_name'];
				$row[] = $aRow['unit_price'];
				$row[] = number_format($total_cost,2);
				$row[] = $aRow['pro_qty'];
				$row[] = $aRow['porder_id'];
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
			$prod_unitcost = $this->prodreport_model->get_prod_unitcost($ArrData['product_id'],$ArrData['unit_price'],$ArrData['product_variant_id']);
			$total_cost = $prod_unitcost ?? 0;
			$row = array();
			$row[] = date('d-m-Y',strtotime($ArrData['order_datetime']));
			$row[] = $ArrData['product_name'];
			$row[] = $ArrData['unit_price'];
			$row[] = $total_cost;
			$row[] = $ArrData['pro_qty'];
			$row[] = $ArrData['porder_id'];
			$row[] = $ArrData['pro_total_amount'];
			$data[] = $row;
			$no++;
    	}
		//exit;
		$report_title = "product_report_".time();
		$ArrHeading = array('Date','Product Name','Product Price','Unit Cost','Quantity','Order ID','Total');
		array_to_csv($ArrHeading,$data,$report_title);		
	}


}