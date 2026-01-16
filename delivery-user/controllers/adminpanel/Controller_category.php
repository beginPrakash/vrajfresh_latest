<?php

class Controller_category extends CI_Controller{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');		
				$this->load->model('common_model');
		
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		
    }
	public function index()
	{
		$ArrData = $this->category_model->getCategoryListData($_POST);
		if(@$_REQUEST['columns'] != "")
		{						
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $ArrData['iTotalRecords'],'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'],'aaData' => array());
				
				$i = $_REQUEST['start']+1;
				foreach($ArrData['result'] as $aRow)
				{
					$row = array();
					$base_url = base_url();	
					$category_id = $aRow['category_id'];
					$actions = '';
					$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="'.$category_id.'" /><div class="btn-group">';
					$actions .='<a rel="'.$base_url.'adminpanel/controller_category/view_category_ajax/" id="'.$category_id.'" title="Category Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
					$actions .= '<a href="'.$base_url.'category-update/'.$category_id.'" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
					$actions .= '<a rel="'.$base_url.'category-delete/'.$category_id.'" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

					$row[] = $actions;
					$row[] = $aRow['category_id'];
					$row[] = $aRow['category_name'];
					
					
					if( empty($aRow['category_description']) )
					$row[] = '';
					else
					$row[] = substr($aRow['category_description'],0,20);

					if($aRow['category_image'] == ''){
						$row[] = $imag = '<img height="70px" width="70px" src="'.$base_url.'uploads/noimg.gif" border=0 >'; 
					}else{
						$image = $aRow['category_image'];
						$row[] = $imag = '<img height="70px" width="70px" src="'.$base_url.'uploads/category/'.$image.'" border=0 alt="No Image found">';
					}
					$row[] = getIsactiveButtonForList($aRow['is_active'],$category_id,'tbl_categories','category_id');
					$i++;
					$output['aaData'][] = $row;

				}
				echo json_encode($output);
				exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Category List';
		$ArrPageData['button_url'] = base_url().'category-add';
		$ArrPageData['button_label'] = 'Add Category';
		$ArrPageData['view_name'] = 'view_category_list.php';
		$this->load->view('admin_panel/admin_panel',$ArrPageData);
	}

	public function category_list_export() 
	{
		extract($_POST);
		$this->load->helper('csv_helper'); 
		$ArrDataList = $this->category_model->ExportCategoryData($_POST);
		$data = array();
    $no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['category_id'];
			$row[] = $ArrData['site_name'];
			$row[] = $ArrData['category_name'];
			$row[] = $ArrData['category_description'];
			$row[] = $ArrData['is_active'];
      $data[] = $row;
    }
		$report_title = "qe_category_list_".time();
		$ArrHeading = array('Sr No','Category ID','site Name','Category Name','Short Description','Long Description','Active');
		array_to_csv($ArrHeading,$data,$report_title);
		
	}

	public function view_category_ajax(){
		$id = $this->input->post('id');
		$data['ArrFieldData']  = $this->category_model->getCategoryAllDetailsById( $id );
		$this->load->view('admin_panel/quickview/view_category_details_popup',$data);
	}

	public function delete_ajax($id){
		$result = $this->category_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
		
	}

	public function delete_multiple_category_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',',$id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result+= $this->category_model->delete($primary_id);
		}		
		if($result > 0){
			echo 1;
		}else{
			echo 0;
		}

	}

	#ADD/EDIT Portfolio
	public function add($category_id=0){

		$ArrData = array();
		$ArrData['ArrParentCategory'] = $this->category_model->parent_category_list_data();
		if($category_id > 0){
			$ArrData['category'] = $this->category_model->getCategoryById($category_id);
			$ArrData['cms_title'] = "Update Category";
			$ArrData['edit_id'] = $category_id;
		}else{
			$ArrData['cms_title'] = "Add Category";
			$ArrData['edit_id'] = '';
		}
		
		$ArrData['button_url'] = base_url().'category';
		$ArrData['button_label'] = 'View Category';
		$ArrData['view_name'] = 'view_category_addedit.php';
		
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/pages/category-add-update-script.js')
		);
		$this->carabiner->js( $js_assets );
		
		
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/pages/category-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js( $js_assets );
		
		
		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update" ) )
		{
			/* validation Process Start */
			$this->load->library('form_validation');
      		$this->form_validation->set_rules('category_name', 'Category Name', 'required');
			$this->form_validation->set_rules('parent_category_id', 'parent_category_id', 'required');
			/* validation Process End */
			if ($this->form_validation->run())
			{
				if($category_id > 0){ //update process
					if(isset($_FILES['category_image']) && is_uploaded_file($_FILES['category_image']['tmp_name'])){
						$config['upload_path'] = 'uploads/category/';		// set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; 		//load the upload library
						$this->load->library('upload', $config);    
						$this->upload->initialize($config);			
						$this->upload->set_allowed_types('*');		
						$data['upload_data'] = '';

						if (!$this->upload->do_upload('category_image')) {
							//$data = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							//$data = array('msg' => "Upload success!");		  
							$data['upload_data'] = $this->upload->data();	
						}
						$data['image_name'] = $this->category_model->delete_image($category_id);
						$data['image_name'] = $this->upload->data();
					}
					else // new file is not uploaded
					{
						$data['image_name']['file_name'] =  $this->input->post('cat_image') ;
					}
					$category_update_data = array(
						'category_name' => $this->input->post('category_name'),
						'category_slug' => str_replace(' ', '-',strtolower(trim($this->input->post('category_name')))),
						'parent_category_id' => $this->input->post('parent_category_id'),
						'category_description' => $this->input->post('category_description'),
						'category_image' => $data['image_name']['file_name'],
						'is_active' => $this->input->post('is_active'),
						'is_perisible_products' => $this->input->post('is_perisible_products'),
						'is_home_display' => $this->input->post('is_home_display'),
						'meta_title' => $this->input->post('meta_title'),
						'meta_keyword' => $this->input->post('meta_keyword'),
						'meta_description' => $this->input->post('meta_description'),
						'style' => $this->input->post('style'),
						'modified_by' => $_SESSION['admin_id'],
						'modified_datetime' => date('Y-m-d'),
					);
					/* ECHO "<PRE>";
					print_r($_POST);
					print_r($_FILES);
					print_r($category_update_data);
					exit; */
					$update = $this->category_model->update($category_id,$category_update_data);
					if($update > 0){
						
						$this->category_model->updatePerisibleProducts($category_id,$this->input->post('is_perisible_products'));
						$this->session->set_flashdata('success_message', 'Category details has been updated successfully.');
					}else{
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('category-update/'.$category_id);
				}else{ // insert
					$config['upload_path'] = 'uploads/category/';		
					$config['allowed_types'] = 'gif|jpg|png';			
					//load the upload library
					$this->load->library('upload', $config);    
					$this->upload->initialize($config);			
					$this->upload->set_allowed_types('*');		
					$data['upload_data'] = '';
					
					if (!$this->upload->do_upload('category_image')) {
						//$data = array('msg' => $this->upload->display_errors());
					} else {
						//$data = array('msg' => "Upload success!");
						$data['upload_data'] = $this->upload->data();
					}

					$data['image_name'] = $this->upload->data();
					$category_data = array(
						'category_name' => $this->input->post('category_name'),
						'category_slug' => str_replace(' ', '-',strtolower(trim($this->input->post('category_name')))),
						'parent_category_id' => $this->input->post('parent_category_id'),
						'category_description' => $this->input->post('category_description'),
						'category_image' => $data['image_name']['file_name'],
						'is_active' => $this->input->post('is_active'),
						'is_perisible_products' => $this->input->post('is_perisible_products'),
						'meta_title' => $this->input->post('meta_title'),
						'meta_keyword' => $this->input->post('meta_keyword'),
						'meta_description' => $this->input->post('meta_description'),
						'style' => $this->input->post('style'),
						'created_by' => $_SESSION['admin_id'],
						'created_datetime' => date('Y-m-d'),
					);
					
					$category_id = $this->category_model->add($category_data);
					if($category_id > 0){
						$this->category_model->updatePerisibleProducts($category_id,$this->input->post('is_perisible_products'));
						
						$this->session->set_flashdata('success_message', 'Category details has been added successfully.');
					}else{
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('category');
				}
			}else{
				$this->load->view('admin_panel/admin_panel',$ArrData);
			}
		}else{
			$this->load->view('admin_panel/admin_panel',$ArrData);
		}
	}
	
}