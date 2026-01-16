<?php
class Controller_newsletter_subscriber extends CI_Controller{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('newsletter_subscriber_model');		
		$this->load->model('common_model');
		$this->load->model('user_model');
				
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		
		}
		
	public function index()
	{
		$ArrData = $this->newsletter_subscriber_model->getNewsletterSubscriberListData($_POST);
		if(@$_REQUEST['columns'] != "")
		{						
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $ArrData['iTotalRecords'],'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'],'aaData' => array());
				
				$i = $_REQUEST['start']+1;
				$base_url = base_url();	
				foreach($ArrData['result'] as $aRow)
				{
					$row = array();
					
					$user_id = 0;
					
					
					$newsletter_subscriber_id = $aRow['newsletter_subscriber_id'];
					$actions = '';
					$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="'.$newsletter_subscriber_id.'" />';
					$actions .='<a rel="'.$base_url.'adminpanel/controller_newsletter_subscriber/view_newsletter_subscriber_ajax/" id="'.$newsletter_subscriber_id.'" title="Newsletter Subscriber Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
					
					$actions .= '<a rel="'.$base_url.'newsletter-subscriber-delete/'.$newsletter_subscriber_id.'" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a>';

					

					$row[] = $actions;
					$row[] = $aRow['newsletter_subscriber_id'];
					$row[] = $aRow['created_datetime'];
					$row[] = $aRow['name'];
					$row[] = $aRow['email'];
					$row[] = ($aRow['status'] == '1')?'Subscribed':'Unsubscribed';
					$row[] = $aRow['from_which_place'];
					$i++;
					$output['aaData'][] = $row;

				}
				echo json_encode($output);
				exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Newsletter Subscriber List';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_newsletter_subscriber_list.php';
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

	public function newsletter_subscriber_list_export() 
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->newsletter_subscriber_model->ExportNewsletterSubscriberData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $ArrData['newsletter_subscriber_id'];
			$row[] = $ArrData['created_datetime'];
			$row[] = $ArrData['name'];
			$row[] = $ArrData['email'];
			$row[] = ($ArrData['status'] == '1')?'Subscribed':'Unsubscribed';
			$row[] = $ArrData['from_which_place'];
			$data[] = $row;
    }
		$report_title = "qe_newsletter_subscriber_list_".time();
		$ArrHeading = array('Sr No','Date','Name','Email','Status','Subscribe From');
		array_to_csv($ArrHeading,$data,$report_title);
		
	}

	public function view_newsletter_subscriber_ajax(){
		$id = $this->input->post('id');
		$data['ArrFieldData']  = $this->newsletter_subscriber_model->getNewsletterSubscriberUsingID( $id );
		$this->load->view('admin_panel/quickview/view_newsletter_subscriber_details_popup',$data);
	}

	public function delete_ajax($id){
		$result = $this->newsletter_subscriber_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
		
	}

	public function delete_multiple_newsletter_subscriber_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',',$id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result+= $this->newsletter_subscriber_model->delete($primary_id);
		}		
		if($result > 0){
			echo 1;
		}else{
			echo 0;
		}

	}

	
}