<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_blog extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('blog_master_model');
		$this->load->model('common_model');	
		$this->module_name = 'blog';
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this blog....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}

	#LIST PAGE
	public function index()
	{		
		$column_order = array('null','tblblog_master.created_datetime','tblblog_master.blog_title','tblblog_master.blog_url','tblblog_master.is_active'); 
		
		$column_search = array('tblblog_master.blog_id','tblblog_master.created_datetime','tblblog_master.blog_title','tblblog_master.blog_url','tblblog_master.is_active','tblblog_master.blog_description','tblblog_master.meta_title','tblblog_master.meta_descriptions','tblblog_master.author'); 
		
		$aColumns = array('tblblog_master.blog_id','tblblog_master.created_datetime','tblblog_master.blog_title','tblblog_master.blog_url','tblblog_master.is_active'); 
		
		$sTable = 'tblblog_master';
		
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
			$order = array('tblblog_master.blog_id' => 'DESC'); 
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
				$row[] = $aRow['blog_title'];
				$row[] = $aRow['blog_url'];
				$row[] = getIsactiveButtonForList($aRow['is_active'],$aRow['blog_id'],'tblblog_master','blog_id');					
				$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['blog_id'],$this->module_name,array("V","E","D"))."</div>";					
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrBlogData = array();
		$ArrBlogData['cms_title'] = 'Blogs';
		$ArrBlogData['button_url'] = $this->module_name.'-add';
		$ArrBlogData['button_label'] = 'Add Blog';
		$ArrBlogData['view_name'] = 'view_'.$this->module_name.'_list.php';		
		$this->load->view('admin_panel/admin_panel',$ArrBlogData);
	}

	#ADD/EDIT PAGE
	public function add($id=0)
	{
		$ArrBlogData = array();
		$ArrBlogData['ArrFieldData'] = array();
		if($id > 0 ){
			$ArrBlogData['ArrFieldData'] = $this->blog_master_model->getBlogDetailsUsingID($id);
			$ArrBlogData['cms_title'] = 'Update Blog';
			$ArrBlogData['button_url'] = base_url().$this->module_name;
		}else{
			$ArrBlogData['cms_title'] = 'Add Blog';
			$ArrBlogData['button_url'] = base_url().$this->module_name;
		}
		$ArrBlogData['button_label'] = 'View Blogs';
		
		$ArrBlogData['view_name'] = 'view_'.$this->module_name.'_addedit.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/'.$this->module_name.'-script.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js( $js_assets );
		$this->load->view('admin_panel/admin_panel',$ArrBlogData);
	}

	#ADD/EDIT PAGE
	public function delete($id=0)
	{
		$result = $this->blog_master_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
	}
	
	#ADD/EDIT PROCESS
	public function save()
	{
		if( !isset($_POST['save_blog']) ){
			redirect($this->module_name);exit;
		}
		
		if(isset($_POST) && $_POST['save_blog']!="" && $_POST['save_blog']=="Add")
		{

			$blogtitle = (trim($_POST['blogtitle']))?$_POST['blogtitle']:'';
			$blog_url = (trim($_POST['blog_url']))?$_POST['blog_url']:'';
			$author = (trim($_POST['author']))?$_POST['author']:'';
			$blog_sort_description = (trim($_POST['blog_sort_description']))?$_POST['blog_sort_description']:'';
			$blog_description = (trim($_POST['blog_description']))?$_POST['blog_description']:'';
			$meta_title = (trim($_POST['meta_title']))?$_POST['meta_title']:'';
			$meta_desc = (trim($_POST['meta_desc']))?$_POST['meta_desc']:'';
			$is_active = $_POST['is_active'];
			
			$config['upload_path'] = 'uploads/blog/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';			
			//load the upload library
			$this->load->library('upload', $config);    
			$this->upload->initialize($config);			
			$this->upload->set_allowed_types('*');
			$data['blog_image'] = array();
			if(isset($_FILES['blog_image']) && is_uploaded_file($_FILES['blog_image']['tmp_name']))
			{	
				if (!$this->upload->do_upload('blog_image')) {
					$data = array('error_message' => $this->upload->display_errors());
				} else {
					$data['blog_image'] = $this->upload->data();
				}

			}else{

				$data['blog_image']['file_name'] =  $this->input->post('current_blog_image') ;
			}
			$ArrBlogData = $this->blog_master_model->getBlogResultBySlug($blog_url);
			if( count($ArrBlogData) > 0 ){
				$this->session->set_flashdata('error_message', 'URL already exist please change blog URL');
				if($blog_id>0){
					redirect($this->module_name.'-update/'.$blog_id);exit;
				}else{
					redirect($this->module_name.'-add');exit;
				}
			}
			$Arrdata = array(
				'blog_title' => $blogtitle,
				'blog_url' => $blog_url,
				'author' => $author,
				'blog_sort_description' => $blog_sort_description,
				'blog_description' => $blog_description,
				'meta_title' => $meta_title,
				'meta_descriptions' => $meta_desc,
				'blog_image' =>  $data['blog_image']['file_name'],
				'created_by' => get_current_admin_id(),
				'created_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'modified_datetime' => NULL,
				'is_active' => $is_active
			);
			$blog_id = $this->blog_master_model->add($Arrdata);
			if($blog_id>0){
				$this->session->set_flashdata('success_message', 'Blog details has been saved successfully');
				redirect($this->module_name.'-update/'.$blog_id);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to insert blog details, please try again');
				redirect($this->module_name.'-add');exit;
			}

		}
		if(isset($_POST) && $_POST['save_blog']!="" && $_POST['save_blog']=="Update" && $_POST['blog_id'] > 0)
		{

			$blogtitle = (trim($_POST['blogtitle']))?$_POST['blogtitle']:'';
			$blog_url = (trim($_POST['blog_url']))?$_POST['blog_url']:'';
			$author = (trim($_POST['author']))?$_POST['author']:'';
			$blog_sort_description = (trim($_POST['blog_sort_description']))?$_POST['blog_sort_description']:'';
			$blog_description = (trim($_POST['blog_description']))?$_POST['blog_description']:'';
			$meta_title = (trim($_POST['meta_title']))?$_POST['meta_title']:'';
			$meta_desc = (trim($_POST['meta_desc']))?$_POST['meta_desc']:'';
			
			$is_active = $_POST['is_active'];
			$blog_id = $_POST['blog_id'];
			
			
			$config['upload_path'] = 'uploads/blog/';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';			
			//load the upload library
			$this->load->library('upload', $config);    
			$this->upload->initialize($config);			
			$this->upload->set_allowed_types('*');
			$data['blog_image'] = array();
			if(isset($_FILES['blog_image']) && is_uploaded_file($_FILES['blog_image']['tmp_name']))
			{	
				$this->blog_master_model->delete_image($blog_id);
				if (!$this->upload->do_upload('blog_image')) {
					$data = array('error_message' => $this->upload->display_errors());
				} else {
					$data['blog_image'] = $this->upload->data();
				}

			}else{

				$data['blog_image']['file_name'] =  $this->input->post('current_blog_image') ;
			}
			
			$ArrBlogData = $this->blog_master_model->isUrlExist($blog_url,$blog_id);

			if( $ArrBlogData == 1 ){
				$this->session->set_flashdata('error_message', 'URL already exist please change blog URL');
				if($blog_id>0){
					redirect($this->module_name.'-update/'.$blog_id);exit;
				}else{
					redirect($this->module_name.'-add');exit;
				}
			}
			
			$Arrdata = array(
				'blog_title' => $blogtitle,
				'blog_url' => $blog_url,
				'author' => $author,
				'blog_sort_description' => $blog_sort_description,
				'blog_description' => $blog_description,
				'meta_title' => $meta_title,
				'meta_descriptions' => $meta_desc,
				'blog_image' =>  $data['blog_image']['file_name'],
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
				'is_active' => $is_active
			);
			
			$result = $this->blog_master_model->update($blog_id,$Arrdata);
			if($result){
				$this->session->set_flashdata('success_message', 'Blog details has been updated successfully');
				//echo $this->session->flashdata('success_message');exit;
				redirect($this->module_name);exit;
			}else{
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update blog details, please try again');
				redirect($this->module_name.'-update/'.$blog_id);exit;
			}
		}
	}

	#ADD/EDIT PAGE
	public function ajaxShowTblblogMasterData()
	{
		$blog_id = $_POST['blog_id'];
		$ArrBlogData['ArrFieldData'] = $this->blog_master_model->getBlogDetailsUsingID($blog_id);
		$this->load->view('admin_panel/view_blog_details_using_ajax',$ArrBlogData);
	}
	
	/* PAGE URL DUBLICATE */
	public function ajaxCheckUrl()
	{
		$blog_url = $_POST['blog_url'];
		$blog_id = $_POST['blog_id'];
		$result = $this->blog_master_model->isUrlExist($blog_url,$blog_id);
		if($result == 1 ){
			echo 'Yes';
		}else{
			echo 'No';
		}

	}

	//- Export to csv of blog - START
	public function blog_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->blog_master_model->ExportBlogListData($_POST);		
		$data = array();
		$no = 0;

		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['created_datetime'];
			$row[] = $ArrData['blog_title'];
			$row[] = $ArrData['blog_url'];
			$row[] = $ArrData['is_active'];			
			$data[] = $row;
		}
		$report_title = "qe_user_refer_list_".time();
		$ArrHeading = array('Sr No','Created Date','Blog Title','Blog Url','Is Active');
		array_to_csv($ArrHeading,$data,$report_title);		
	}
	//- Export to csv of blog - END
	
	


}
