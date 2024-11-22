<?php

class Controller_catreport extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('catreport_model');
		$this->load->model('common_model');
        $this->load->model('category_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	public function index()
	{
		$ArrData = $this->catreport_model->getOrderListData($_POST);
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());


			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();
				$row[] = $aRow['category_name'];
				$row[] = $aRow['product_name'];
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
		$ArrPageData['cms_title'] = 'Category Reports';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_catreport_list.php';
        $ArrPageData['category'] = $this->category_model->category_list_data();
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}



    public function order_list_export() 
	{	
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->catreport_model->ExportReportOrderData($_POST);
		$data = array();
		$no = 0;
        $cat_name = '';
		foreach ($ArrDataList as $key => $ArrData) {
			
            if($no == 1){
                $cat_name = $ArrData['category_name'];
            }
			
			$row = array();
			$row[] = $ArrData['category_name'];
			$row[] = $ArrData['product_name'];
			$row[] = $ArrData['pro_qty'];
			$row[] = $ArrData['pro_total_amount'];
			$data[] = $row;
			$no++;
    	}
		//exit;
		$report_title = "category_report_".time();
		$ArrHeading = array('Category Name','Products','Total Order Items','Total amount');
		array_to_csv($ArrHeading,$data,$report_title);		
	}


}