<?php

class Controller_tag extends CI_Controller{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('tag_model');		
				$this->load->model('common_model');
		
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		
    }
	public function index()
	{
		$ArrData = $this->tag_model->getTagListData($_POST);
		if(@$_REQUEST['columns'] != "")
		{						
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $ArrData['iTotalRecords'],'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'],'aaData' => array());
				
				$i = $_REQUEST['start']+1;
				foreach($ArrData['result'] as $aRow)
				{
					$row = array();
					$base_url = base_url();	
					$tag_id = $aRow['tag_id'];
					$actions = '';
					$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="'.$tag_id.'" /><div class="btn-group">';
					$actions .='<a rel="'.$base_url.'adminpanel/controller_tag/view_tag_ajax/" id="'.$tag_id.'" title="Tag Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
					$actions .= '<a href="'.$base_url.'tag-update/'.$tag_id.'" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
					$actions .= '<a rel="'.$base_url.'tag-delete/'.$tag_id.'" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

					$row[] = $actions;
					$row[] = $aRow['tag_id'];
					$row[] = $aRow['tag'];
					
					$row[] = getIsactiveButtonForList($aRow['is_active'],$tag_id,'tbl_tag_master','tag_id');
					$i++;
					$output['aaData'][] = $row;

				}
				echo json_encode($output);
				exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Tag List';
		$ArrPageData['button_url'] = base_url().'tag-add';
		$ArrPageData['button_label'] = 'Add Tag';
		$ArrPageData['view_name'] = 'view_tag_list.php';
		$this->load->view('admin_panel/admin_panel',$ArrPageData);
	}

	public function tag_list_export() 
	{
		extract($_POST);
		$this->load->helper('csv_helper'); 
		$ArrDataList = $this->tag_model->ExportTagData($_POST);
		$data = array();
    $no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['tag_id'];
			$row[] = $ArrData['site_name'];
			$row[] = $ArrData['tag'];
			$row[] = $ArrData['tag_description'];
			$row[] = $ArrData['is_active'];
      $data[] = $row;
    }
		$report_title = "qe_tag_list_".time();
		$ArrHeading = array('Sr No','Tag ID','site Name','Tag Name','Short Description','Long Description','Active');
		array_to_csv($ArrHeading,$data,$report_title);
		
	}

	public function view_tag_ajax(){
		$id = $this->input->post('id');
		$data['ArrFieldData']  = $this->tag_model->getTagAllDetailsById( $id );
		$this->load->view('admin_panel/quickview/view_tag_details_popup',$data);
	}

	public function delete_ajax($id){
		$result = $this->tag_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
		
	}

	public function delete_multiple_tag_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',',$id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result+= $this->tag_model->delete($primary_id);
		}		
		if($result > 0){
			echo 1;
		}else{
			echo 0;
		}

	}

	#ADD/EDIT Portfolio
	public function add($tag_id=0){

		$ArrData = array();
		$ArrData['ArrParentTag'] = $this->tag_model->tag_list_data();
		if($tag_id > 0){
			$ArrData['tag'] = $this->tag_model->getTagById($tag_id);
			$ArrData['cms_title'] = "Update Tag";
			$ArrData['edit_id'] = $tag_id;
		}else{
			$ArrData['cms_title'] = "Add Tag";
			$ArrData['edit_id'] = '';
		}
		
		$ArrData['button_url'] = base_url().'tag';
		$ArrData['button_label'] = 'View Tag';
		$ArrData['view_name'] = 'view_tag_addedit.php';
		
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/pages/tag-add-update-script.js')
		);
		$this->carabiner->js( $js_assets );
		
		
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/pages/tag-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js( $js_assets );
		
		
		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update" ) )
		{
			/* validation Process Start */
			$this->load->library('form_validation');
      		$this->form_validation->set_rules('tag', 'Tag Name', 'required');
			/* validation Process End */
			if ($this->form_validation->run())
			{
				if($tag_id > 0){ //update process
					
					$tag_update_data = array(
						'tag' => $this->input->post('tag'),
						'is_active' => $this->input->post('is_active'),
						'modified_by' => $_SESSION['admin_id'],
						'modified_datetime' => date('Y-m-d'),
					);
					
					$update = $this->tag_model->update($tag_id,$tag_update_data);
					if($update > 0){
						$this->session->set_flashdata('success_message', 'Tag details has been updated successfully.');
					}else{
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('tag-update/'.$tag_id);
				}else{ // insert
					
					$tag_data = array(
						'tag' => $this->input->post('tag'),
						'is_active' => $this->input->post('is_active'),
						'created_by' => $_SESSION['admin_id'],
						'created_datetime' => date('Y-m-d'),
					);
					
					$insert_id = $this->tag_model->add($tag_data);
					if($insert_id > 0){
						$this->session->set_flashdata('success_message', 'Tag details has been added successfully.');
					}else{
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('tag');
				}
			}else{
				$this->load->view('admin_panel/admin_panel',$ArrData);
			}
		}else{
			$this->load->view('admin_panel/admin_panel',$ArrData);
		}
	}
	
}