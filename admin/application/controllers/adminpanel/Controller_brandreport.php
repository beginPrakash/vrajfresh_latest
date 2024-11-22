<?php

class Controller_brandreport extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('brandreport_model');
		$this->load->model('common_model');
        $this->load->model('brand_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	public function index()
	{
		$ArrData = $this->brandreport_model->getOrderListData($_POST);
       // echo'<pre>';print_r($ArrData);exit;
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			
			$i = $_REQUEST['start'] + 1;
			
			foreach ($ArrData['result'] as $aRow) {
				$or_array = explode(',',$aRow['pro_total_amount']);
				$pro_total = array_sum($or_array);
				$or_qty = explode(',',$aRow['pro_qty']);
				$qty_total = array_sum($or_qty);
				$unitpr_array = explode(',',$aRow['pro_unit_price']);
				$pro_html = '';	
				if(count($unitpr_array) > 0):
					foreach($unitpr_array as $key => $val):
						$pro_html.=$or_qty[$key].'&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;'.$val;
					endforeach;
				endif;
				$row = $category_name = array();
				$row[] = $aRow['brand_name'];
				$row[] = $aRow['product_name'];
				$row[] = $pro_html;
				$row[] = $qty_total;
				$row[] = round($pro_total,2);
				
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Brand Reports';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_brandreport_list.php';
        $ArrPageData['brand'] = $this->brand_model->brand_list_data();
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}



    public function order_list_export() 
	{	
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->brandreport_model->ExportReportOrderData($_POST);
		$data = array();
		$no = 0;
        $cat_name = '';
		
		foreach ($ArrDataList as $key => $ArrData) {
				$or_array = explode(',',$ArrData['pro_total_amount']);
				
				$pro_total = array_sum($or_array);
				$or_qty = explode(',',$ArrData['pro_qty']);
				$qty_total = array_sum($or_qty);
				$unitpr_array = explode(',',$ArrData['pro_unit_price']);
				$pro_html = '';	
				if(count($unitpr_array) > 0):
					foreach($unitpr_array as $key => $val):
						$pro_html.=$or_qty[$key].' - '.$val.' | ';
					endforeach;
				endif;
			$row = array();
			$row[] = $ArrData['brand_name'];
			$row[] = $ArrData['product_name'];
			$row[] = $pro_html;
			$row[] = $qty_total;
			$row[] = round($pro_total,2);
			$data[] = $row;
			$no++;
    	}
		//exit;
		$report_title = "brand_report_".time();
		$ArrHeading = array('Brand Name','Product Name','Product Price','Quantity','Total Amount');
		array_to_csv($ArrHeading,$data,$report_title);		
	}


}