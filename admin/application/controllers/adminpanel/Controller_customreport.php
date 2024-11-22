<?php

class Controller_customreport extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('customreport_model');
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
		$ArrData = $this->customreport_model->getOrderListData($_POST);
       // echo'<pre>';print_r($ArrData);exit;
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());


			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();
                if($aRow['cnt'] <= 1){
                    $type = 'New';
                }else{
                    $type = 'Existing';
                }
				$row[] = $type;
				
					$row[] = date('d-m-Y',strtotime($aRow['created_datetime']));
				
				$dddate = [];
				$odata = explode(',',$aRow['order_date']);
				if(!empty($odata) && count($odata) > 0){
					foreach($odata as $key => $val){
						$dddate[] = date('d-m-Y',strtotime($val));
					}
				}
				$row[] = $aRow['email'];
				$row[] = implode(', ',$dddate);
				$row[] = $aRow['order_id'];
				$row[] = $aRow['order_total_amount'];
				
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Customer Reports';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_customreport_list.php';
        $ArrPageData['brand'] = $this->brand_model->brand_list_data();
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}



    public function order_list_export() 
	{	
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->customreport_model->ExportReportOrderData($_POST);
		$data = array();
		$no = 0;
        $cat_name = '';
		
		foreach ($ArrDataList as $key => $ArrData) {

			$row = array();
            if($ArrData['cnt'] <= 1){
                $type = 'New';
            }else{
                $type = 'Existing';
            }
            $row[] = $type;
			$row[] = date('d-m-Y',strtotime($ArrData['created_datetime']));
            
			$dddate = [];
				$odata = explode(',',$ArrData['order_date']);
				if(!empty($odata) && count($odata) > 0){
					foreach($odata as $key => $val){
						$dddate[] = date('d-m-Y',strtotime($val));
					}
				}

            $row[] = $ArrData['email'];
			$row[] = implode(', ',$dddate);
            $row[] = $ArrData['order_id'];
			$row[] = $ArrData['order_total_amount'];
			$data[] = $row;
			$no++;
    	}
		//exit;
		$report_title = "customer_report_".time();
		
			$ArrHeading = array('Customer Type','Registration Date','Customer Email','Order Number','Order Amount');
		
		array_to_csv($ArrHeading,$data,$report_title);		
	}


}