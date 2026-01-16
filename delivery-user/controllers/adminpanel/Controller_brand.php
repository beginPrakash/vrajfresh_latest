<?php

class Controller_brand extends CI_Controller{
	
	public function __construct()
    {
        parent::__construct();
        $this->load->model('brand_model');		
				$this->load->model('common_model');
		
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		
    }
	public function index()
	{
		$ArrData = $this->brand_model->getBrandListData($_POST);
		if(@$_REQUEST['columns'] != "")
		{						
			$output = array('sEcho' => $_REQUEST['draw'],'iTotalRecords' => $ArrData['iTotalRecords'],'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'],'aaData' => array());
				
				$i = $_REQUEST['start']+1;
				foreach($ArrData['result'] as $aRow)
				{
					$row = array();
					$base_url = base_url();	
					$brand_id = $aRow['brand_id'];
					$actions = '';
					$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="'.$brand_id.'" /><div class="btn-group">';
					$actions .='<a rel="'.$base_url.'adminpanel/controller_brand/view_brand_ajax/" id="'.$brand_id.'" title="Brand Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
					$actions .= '<a href="'.$base_url.'brand-update/'.$brand_id.'" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
					$actions .= '<a rel="'.$base_url.'brand-delete/'.$brand_id.'" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

					$row[] = $actions;
					$row[] = $aRow['brand_id'];
					$row[] = $aRow['brand_name'];
					
					
					if( empty($aRow['brand_description']) )
					$row[] = '';
					else
					$row[] = substr($aRow['brand_description'],0,20);

					if($aRow['brand_image'] == ''){
						$row[] = $imag = '<img height="70px" width="70px" src="'.$base_url.'uploads/noimg.gif" border=0 >'; 
					}else{
						$image = $aRow['brand_image'];
						$row[] = $imag = '<img height="70px" width="70px" src="'.$base_url.'uploads/brand/'.$image.'" border=0 alt="No Image found">';
					}
					$row[] = getIsactiveButtonForList($aRow['is_active'],$brand_id,'tbl_brands','brand_id');
					$i++;
					$output['aaData'][] = $row;

				}
				echo json_encode($output);
				exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Brand List';
		$ArrPageData['button_url'] = base_url().'brand-add';
		$ArrPageData['button_label'] = 'Add Brand';
		$ArrPageData['view_name'] = 'view_brand_list.php';
		$this->load->view('admin_panel/admin_panel',$ArrPageData);
	}

	public function brand_list_export() 
	{
		extract($_POST);
		$this->load->helper('csv_helper'); 
		$ArrDataList = $this->brand_model->ExportBrandData($_POST);
		$data = array();
    $no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['brand_id'];
			$row[] = $ArrData['site_name'];
			$row[] = $ArrData['brand_name'];
			$row[] = $ArrData['brand_description'];
			$row[] = $ArrData['is_active'];
      $data[] = $row;
    }
		$report_title = "qe_brand_list_".time();
		$ArrHeading = array('Sr No','Brand ID','site Name','Brand Name','Short Description','Long Description','Active');
		array_to_csv($ArrHeading,$data,$report_title);
		
	}

	public function view_brand_ajax(){
		$id = $this->input->post('id');
		$data['ArrFieldData']  = $this->brand_model->getBrandAllDetailsById( $id );
		$this->load->view('admin_panel/quickview/view_brand_details_popup',$data);
	}

	public function delete_ajax($id){
		$result = $this->brand_model->delete($id);
		if($result){
			echo 'Yes';
		}else{
			echo 'No';
		}
		
	}

	public function delete_multiple_brand_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',',$id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result+= $this->brand_model->delete($primary_id);
		}		
		if($result > 0){
			echo 1;
		}else{
			echo 0;
		}

	}

	#ADD/EDIT Portfolio
	public function add($brand_id=0){

		$ArrData = array();
		$ArrData['ArrParentBrand'] = $this->brand_model->brand_list_data();
		if($brand_id > 0){
			$ArrData['brand'] = $this->brand_model->getBrandById($brand_id);
			$ArrData['cms_title'] = "Update Brand";
			$ArrData['edit_id'] = $brand_id;
		}else{
			$ArrData['cms_title'] = "Add Brand";
			$ArrData['edit_id'] = '';
		}
		
		$ArrData['button_url'] = base_url().'brand';
		$ArrData['button_label'] = 'View Brand';
		$ArrData['view_name'] = 'view_brand_addedit.php';
		
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/pages/brand-add-update-script.js')
		);
		$this->carabiner->js( $js_assets );
		
		
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/pages/brand-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH.'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js( $js_assets );
		
		
		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update" ) )
		{
			/* validation Process Start */
			$this->load->library('form_validation');
      		$this->form_validation->set_rules('brand_name', 'Brand Name', 'required');
			$this->form_validation->set_rules('search_string', 'Search', 'trim');
			/* validation Process End */
			if ($this->form_validation->run())
			{
				if($brand_id > 0){ //update process
					if(isset($_FILES['brand_image']) && is_uploaded_file($_FILES['brand_image']['tmp_name'])){
						$config['upload_path'] = 'uploads/brand/';		// set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; 		//load the upload library
						$this->load->library('upload', $config);    
						$this->upload->initialize($config);			
						$this->upload->set_allowed_types('*');		
						$data['upload_data'] = '';

						if (!$this->upload->do_upload('brand_image')) {
							$data = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$data = array('msg' => "Upload success!");		  
							$data['upload_data'] = $this->upload->data();	
						}
						$data['image_name'] = $this->brand_model->delete_image($brand_id);
						$data['image_name'] = $this->upload->data();
					}
					else // new file is not uploaded
					{
						$data['image_name']['file_name'] =  $this->input->post('cat_image') ;
					}
					$brand_update_data = array(
						'brand_name' => $this->input->post('brand_name'),
						'brand_slug' => str_replace(' ', '-',strtolower(trim($this->input->post('brand_name')))),
						'brand_description' => $this->input->post('brand_description'),
						'brand_image' => $data['image_name']['file_name'],
						'is_home_display' => $this->input->post('is_home_display'),
						'is_active' => $this->input->post('is_active'),
						'meta_title' => $this->input->post('meta_title'),
						'meta_keyword' => $this->input->post('meta_keyword'),
						'meta_description' => $this->input->post('meta_description'),
						'modified_by' => $_SESSION['admin_id'],
						'modified_datetime' => date('Y-m-d'),
					);
					/* ECHO "<PRE>";
					print_r($_POST);
					print_r($_FILES);
					print_r($brand_update_data);
					exit; */
					$update = $this->brand_model->update($brand_id,$brand_update_data);
					if($update > 0){
						$this->session->set_flashdata('success_message', 'Brand details has been updated successfully.');
					}else{
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('brand-update/'.$brand_id);
				}else{ // insert
					$config['upload_path'] = 'uploads/brand/';		
					$config['allowed_types'] = 'gif|jpg|png';			
					//load the upload library
					$this->load->library('upload', $config);    
					$this->upload->initialize($config);			
					$this->upload->set_allowed_types('*');		
					$data['upload_data'] = '';
					
					if (!$this->upload->do_upload('brand_image')) {
						$data = array('msg' => $this->upload->display_errors());
					} else {
						$data = array('msg' => "Upload success!");
						$data['upload_data'] = $this->upload->data();
					}

					$data['image_name'] = $this->upload->data();
					$brand_data = array(
						'brand_name' => $this->input->post('brand_name'),
						'brand_slug' => str_replace(' ', '-',strtolower(trim($this->input->post('brand_name')))),
						'brand_description' => $this->input->post('brand_description'),
						'brand_image' => $data['image_name']['file_name'],
						'is_home_display' => $this->input->post('is_home_display'),
						'is_active' => $this->input->post('is_active'),
						'meta_title' => $this->input->post('meta_title'),
						'meta_keyword' => $this->input->post('meta_keyword'),
						'meta_description' => $this->input->post('meta_description'),
						'created_by' => $_SESSION['admin_id'],
						'created_datetime' => date('Y-m-d'),
					);
					
					$insert_id = $this->brand_model->add($brand_data);
					if($insert_id > 0){
						$this->session->set_flashdata('success_message', 'Brand details has been added successfully.');
					}else{
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('brand');
				}
			}else{
				$this->load->view('admin_panel/admin_panel',$ArrData);
			}
		}else{
			$this->load->view('admin_panel/admin_panel',$ArrData);
		}
	}
	
}