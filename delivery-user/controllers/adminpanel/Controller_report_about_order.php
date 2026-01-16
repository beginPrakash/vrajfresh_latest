<?php
class Controller_report_about_order extends CI_Controller{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('report_about_order_model');		
		$this->load->model('common_model');
				
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		
		}
		
	public function index()
	{
		$ArrData = $this->report_about_order_model->getReportAboutOrderListData();
		if(@$_REQUEST['columns'] != "")
		{						
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $ArrData['iTotalRecords'],'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'],'aaData' => array());
				
				$i = $_REQUEST['start']+1;
				$base_url = base_url();	
				foreach($ArrData['result'] as $aRow)
				{
					$row = array();
					$order_complain_id = $aRow['order_complain_id'];
					$actions = '';
					$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="'.$order_complain_id.'" />';
					$actions .='<a rel="'.$base_url.'adminpanel/controller_report_about_order/view_report_about_order_ajax/" id="'.$order_complain_id.'" title="Report About Order Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
					
					$actions .= '<a rel="'.$base_url.'report-about-order-delete/'.$order_complain_id.'" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a>';

					

					$row[] = $actions;
					$row[] = $aRow['created_datetime'];
					$row[] = $aRow['order_id'];
					$row[] = $aRow['display_name'];
					$row[] = $aRow['email'];
					$row[] = $aRow['phone'];
					$i++;
					$output['aaData'][] = $row;

				}
				echo json_encode($output);
				exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Report About Order List';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_report_about_order_list.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/jquery.multiselect.js'),
		);
		$css_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/css/jquery.multiselect.css'),
		);
		$this->carabiner->js( $js_assets );
		$this->carabiner->css( $css_assets );
		$this->load->view('admin_panel/admin_panel',$ArrPageData);
	}

	public function report_about_order_list_export() 
	{	
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->report_about_order_model->ExportReportAboutOrderData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $ArrData['order_complain_id'];
			$row[] = $ArrData['created_datetime'];
			$row[] = $ArrData['contact_first_name']." ".$ArrData['contact_last_name'];
			$row[] = $ArrData['contact_company_name'];
			$row[] = $ArrData['contact_email'];
			$row[] = $ArrData['contact_phone_number'];
			$row[] = $ArrData['contact_domain_name'];
			$row[] = $ArrData['contact_country_name'];
			$row[] = $ArrData['ip_address'];
			$data[] = $row;
    	}
		$report_title = "qe_report_about_order_list_".time();
		$ArrHeading = array('Sr No','Website','Date','Name','Company','Email','Contact','Domain','Country','IP Address');
		array_to_csv($ArrHeading,$data,$report_title);		
	}

	public function view_report_about_order_ajax(){
		$id = $this->input->post('id');
		$data['ArrFieldData']  = $this->report_about_order_model->getReportAboutOrderById( $id );
		$this->load->view('admin_panel/quickview/view_report_about_order_details_popup',$data);
	}

	public function delete_ajax($id){
		$result = $this->report_about_order_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
		
	}

	public function delete_multiple_report_about_order_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',',$id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result+= $this->report_about_order_model->delete($primary_id);
		}		
		if($result > 0){
			echo 1;
		}else{
			echo 0;
		}

	}

	
}