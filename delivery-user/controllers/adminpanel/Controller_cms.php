<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_cms extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('cms_master_model');
		$this->load->model('common_model');	
		$this->module_name = 'cms';
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	#LIST PAGE
	public function index()
	{		
		$column_order = array('null','tbl_cms.created_datetime','tbl_cms.cms_title','tbl_cms.cms_url','tbl_cms.is_active'); 
		
		$column_search = array('tbl_cms.cms_id','tbl_cms.created_datetime','tbl_cms.cms_title','tbl_cms.cms_url','tbl_cms.is_active','tbl_cms.cms_description','tbl_cms.meta_title','tbl_cms.meta_descriptions'); 
		
		$aColumns = array('tbl_cms.cms_id','tbl_cms.created_datetime','tbl_cms.cms_title','tbl_cms.cms_url','tbl_cms.is_active'); 
		
		$sTable = 'tbl_cms';
		
		$i=0;
		foreach ($column_search as $item) { /*loop column */        
			if(@$_POST['search']['value'] || @$_POST['txtSearchKeyWord'])  /*if datatable send POST for search*/
			{                            
				if($i===0)
				{ 
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					if($_POST['search']['value'] && $_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['search']['value']);                        
						$this->db->or_like($item, $_POST['txtSearchKeyWord']);
					}  else if ($_POST['search']['value'] && !$_POST['txtSearchKeyWord']) {                            
						$this->db->like($item, $_POST['search']['value']);
					} else if (!$_POST['search']['value'] && $_POST['txtSearchKeyWord']) {                            
						$this->db->like($item, $_POST['txtSearchKeyWord']);
					}
				}
				else
				{
					if($_POST['search']['value']) { $this->db->or_like($item, $_POST['search']['value']);}
					if($_POST['txtSearchKeyWord']){$this->db->or_like($item, $_POST['txtSearchKeyWord']);}
				} 
				if(count($column_search) - 1 == $i) { $this->db->group_end(); /*close bracket*/ }
			}
			$i++;
		}

		if(@$_REQUEST['ddIsActive'] != "")
		{
		$this->db->where('is_active',$_REQUEST['ddIsActive']);		 
		}
		
		// set columns start
		if(@$_REQUEST['columns'] != "") 
		{
			if(@$_REQUEST['columns'] != ""){

			if(@$_REQUEST['length'] != -1){
			$this->db->limit($_REQUEST['length'], $_REQUEST['start']);
			}
			}

			// Select Data
			$this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
			$order = array('tbl_cms.cms_id' => 'DESC'); 
			if(isset($_POST['order'])) { // here order processing
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}  else if(isset($order)) {
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
			}


			$rResult = $this->db->get($sTable);

			// Data set length after filtering
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;

			// Total data set length
			$iTotal = $this->db->count_all($sTable);

			// Output
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $iTotal,'iTotalDisplayRecords' => $iFilteredTotal,'aaData' => array());
				
			$i = $_REQUEST['start']+1;
			foreach($rResult->result_array() as $aRow)
			{
				$row = array();				
				$row[] = $i;
				$row[] = changeDateFormat($aRow['created_datetime']);
				$row[] = $aRow['cms_title'];
				$row[] = $aRow['cms_url'];
				$row[] = getIsactiveButtonForList($aRow['is_active'],$aRow['cms_id'],'tbl_cms','cms_id');					
				$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['cms_id'],$this->module_name,array("V","E","D"))."</div>";					
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'CMS Pages';
		$ArrPageData['button_url'] = $this->module_name.'-add';
		$ArrPageData['button_label'] = 'Add CMS Page';
		$ArrPageData['view_name'] = 'view_'.$this->module_name.'_list.php';		
		$this->load->view('admin_panel/admin_panel',$ArrPageData);
	}

	#ADD/EDIT PAGE
	public function add($id=0)
	{
		$ArrPageData = array();
		$ArrPageData['ArrFieldData'] = array();
		if($id > 0 ){
			$ArrPageData['ArrFieldData'] = $this->cms_master_model->getCMSDetailsUsingID($id);
			$ArrPageData['cms_title'] = 'Update CMS Page';
			$ArrPageData['button_url'] = base_url().$this->module_name;
		}else{
			$ArrPageData['cms_title'] = 'Add CMS Page';
			$ArrPageData['button_url'] = base_url().$this->module_name;
		}
		$ArrPageData['button_label'] = 'View CMS Pages';
		
		$ArrPageData['view_name'] = 'view_'.$this->module_name.'_addedit.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/'.$this->module_name.'-script.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js( $js_assets );
		$this->load->view('admin_panel/admin_panel',$ArrPageData);
	}

	#ADD/EDIT PAGE
	public function delete($id=0)
	{
		$result = $this->cms_master_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
	}
	
	#ADD/EDIT PROCESS
	public function save()
	{
		if( !isset($_POST['save_page']) ){
			redirect($this->module_name);exit;
		}
		
		if(isset($_POST) && $_POST['save_page']!="" && $_POST['save_page']=="Add")
		{

			$cmstitle = (trim($_POST['cmstitle']))?$_POST['cmstitle']:'';
			$cms_url = (trim($_POST['cms_url']))?$_POST['cms_url']:'';
			$cms_description = (trim($_POST['cms_description']))?$_POST['cms_description']:'';
			$meta_title = (trim($_POST['meta_title']))?$_POST['meta_title']:'';
			$meta_desc = (trim($_POST['meta_desc']))?$_POST['meta_desc']:'';
			$is_active = $_POST['is_active'];

			$ArrCmsData = $this->cms_master_model->getCmsResultBySlug($cms_url);
			if( count($ArrCmsData) > 0 ){
				$this->session->set_flashdata('error_message', 'URL already exist please change page URL');
				if($cms_id>0){
					redirect($this->module_name.'-update/'.$cms_id);exit;
				}else{
					redirect($this->module_name.'-add');exit;
				}
			}
			$Arrdata = array(
				'cms_title' => $cmstitle,
				'cms_url' => $cms_url,
				'cms_description' => $cms_description,
				'meta_title' => $meta_title,
				'meta_descriptions' => $meta_desc,
				'created_by' => get_current_admin_id(),
				'created_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'modified_datetime' => NULL,
				'is_active' => $is_active
			);
			$cms_id = $this->cms_master_model->add($Arrdata);
			if($cms_id>0){
				$this->session->set_flashdata('success_message', 'Page details has been saved successfully');
				redirect($this->module_name.'-update/'.$cms_id);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to insert page details, please try again');
				redirect($this->module_name.'-add');exit;
			}

		}
		if(isset($_POST) && $_POST['save_page']!="" && $_POST['save_page']=="Update" && $_POST['cms_id'] > 0)
		{

			$cmstitle = (trim($_POST['cmstitle']))?$_POST['cmstitle']:'';
			$cms_url = (trim($_POST['cms_url']))?$_POST['cms_url']:'';
			$cms_description = (trim($_POST['cms_description']))?$_POST['cms_description']:'';
			$meta_title = (trim($_POST['meta_title']))?$_POST['meta_title']:'';
			$meta_desc = (trim($_POST['meta_desc']))?$_POST['meta_desc']:'';
			$is_active = $_POST['is_active'];
			$cms_id = $_POST['cms_id'];
			
			$ArrCmsData = $this->cms_master_model->isUrlExist($cms_url,$cms_id);

			if( $ArrCmsData == 1 ){
				$this->session->set_flashdata('error_message', 'URL already exist please change page URL');
				if($cms_id>0){
					redirect($this->module_name.'-update/'.$cms_id);exit;
				}else{
					redirect($this->module_name.'-add');exit;
				}
			}

			$Arrdata = array(
				'cms_title' => $cmstitle,
				'cms_url' => $cms_url,
				'cms_description' => $cms_description,
				'meta_title' => $meta_title,
				'meta_descriptions' => $meta_desc,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'is_active' => $is_active
			);
			$result = $this->cms_master_model->update($cms_id,$Arrdata);
			if($result){
				$this->session->set_flashdata('success_message', 'Page details has been updated successfully');
				//echo $this->session->flashdata('success_message');exit;
				redirect($this->module_name);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update page details, please try again');
				redirect($this->module_name.'-update/'.$cms_id);exit;
			}
		}
	}

	#ADD/EDIT PAGE
	public function ajaxShowTblcmsMasterData()
	{
		$cms_id = $_POST['cms_id'];
		$ArrPageData['ArrFieldData'] = $this->cms_master_model->getCMSDetailsUsingID($cms_id);
		$this->load->view('admin_panel/view_cms_details_using_ajax',$ArrPageData);
	}
	
	/* PAGE URL DUBLICATE */
	public function ajaxCheckUrl()
	{
		$cms_url = $_POST['cms_url'];
		$cms_id = $_POST['cms_id'];
		$result = $this->cms_master_model->isUrlExist($cms_url,$cms_id);
		if($result == 1 ){
			echo 'Yes';
		}else{
			echo 'No';
		}

	}

	//- Export to csv of cms - START
	public function cms_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->cms_master_model->ExportCmsListData($_POST);		
		$data = array();
		$no = 0;

		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['created_datetime'];
			$row[] = $ArrData['cms_title'];
			$row[] = $ArrData['cms_url'];
			$row[] = $ArrData['is_active'];			
			$data[] = $row;
		}
		$report_title = "qe_user_refer_list_".time();
		$ArrHeading = array('Sr No','Created Date','Page Title','Page Url','Is Active');
		array_to_csv($ArrHeading,$data,$report_title);		
	}
	//- Export to csv of cms - END



}
