<?php

class Controller_taxreport extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('taxreport_model');
		$this->load->model('common_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	public function index()
	{
		$ArrData = $this->taxreport_model->getOrderListData($_POST);
       // echo'<pre>';print_r($ArrData);exit;
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());


			$i = $_REQUEST['start'] + 1;
            $start_date = '01-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
            $end_date = '31-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
			$total_am = 0;
			foreach ($ArrData['result'] as $aRow) {
				$or_array = explode(',',$aRow['order_ids']);

				$or_array = array_unique($or_array);

				$t_amount = $this->taxreport_model->sum_order_no($or_array);
				if(!empty($t_amount)){
					$total_am = $t_amount[0]['order_total_amount'];
				}
				//print_r($t_amount);exit;
				$row =  array();
				$row[] = $aRow['state'];
				$row[] = $aRow['tax'];
				$row[] = $start_date.' to '.$end_date;
				$row[] = $aRow['total_tax'];
				$row[] = $total_am;
				
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Tax Reports';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_taxreport_list.php';
        $ArrPageData['states'] = $this->taxreport_model->state_list_data();
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}



    public function order_list_export() 
	{	
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->taxreport_model->ExportReportOrderData($_POST);
		$data = array();
		$no = 0;
        $cat_name = '';
		$start_date = '01-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
        $end_date = '31-'.@$_REQUEST['src_month'].'-'.@$_REQUEST['src_year'];
		foreach ($ArrDataList as $key => $ArrData) {
            $or_array = explode(',',$ArrData['order_ids']);

			$or_array = array_unique($or_array);

			$t_amount = $this->taxreport_model->sum_order_no($or_array);
			if(!empty($t_amount)){
				$total_am = $t_amount[0]['order_total_amount'];
			}
			$row = array();
			$row[] = $ArrData['state'];
			$row[] = $ArrData['tax'];
			$row[] = $start_date.' to '.$end_date;;
			$row[] = $ArrData['total_tax'];
			$row[] = $total_am;
			$data[] = $row;
			$no++;
    	}
		//exit;
		$report_title = "tax_report_".time();
		$ArrHeading = array('State Name','Tax percantage','Date','Total Tax','Total Amount');
		array_to_csv($ArrHeading,$data,$report_title);		
	}


    

}