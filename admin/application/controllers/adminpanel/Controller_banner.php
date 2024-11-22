<?php

class Controller_banner extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('banner_model');
		$this->load->model('common_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}

		$user_role_id = $this->session->userdata['is_logged_in']; // Assuming you have a function to get the user role ID
		
		if (trim($user_role_id) == 2) {
			show_404(); // or any other action you want to take for unauthorized users
		}

	}
	public function index()
	{
		$ArrData = $this->banner_model->getBannerListData($_POST);
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = array();
				$base_url = base_url();
				$banner_id = $aRow['banner_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $banner_id . '" /><div class="btn-group">';
				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_banner/view_banner_ajax/" id="' . $banner_id . '" title="Banner Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$actions .= '<a href="' . $base_url . 'banner-update/' . $banner_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$actions .= '<a rel="' . $base_url . 'banner-delete/' . $banner_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

				$row[] = $actions;
				$row[] = $aRow['banner_id'];
				$row[] = $aRow['banner_text'];


				if ($aRow['banner_image'] == '') {
					$row[] = $imag = '<img width="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 >';
				} else {
					$image = $aRow['banner_image'];
					$row[] = $imag = '<img width="70px" src="' . $base_url . 'uploads/banner/' . $image . '" border=0 alt="No Image found">';
				}
				$row[] = getIsactiveButtonForList($aRow['is_active'], $banner_id, 'tbl_banners', 'banner_id');
				$i++;
				$output['aaData'][] = $row;

			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Banner List';
		$ArrPageData['button_url'] = base_url() . 'banner-add';
		$ArrPageData['button_label'] = 'Add Banner';
		$ArrPageData['view_name'] = 'view_banner_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function banner_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->banner_model->ExportBannerData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['banner_id'];
			$row[] = $ArrData['site_name'];
			$row[] = $ArrData['banner_text'];
			$row[] = $ArrData['banner_link'];
			$row[] = $ArrData['is_active'];
			$data[] = $row;
		}
		$report_title = "qe_banner_list_" . time();
		$ArrHeading = array('Sr No', 'Banner ID', 'site Name', 'Banner Name', 'Description', 'Long Description', 'Active');
		array_to_csv($ArrHeading, $data, $report_title);

	}

	public function view_banner_ajax()
	{
		$id = $this->input->post('id');
		$data['ArrFieldData'] = $this->banner_model->getBannerAllDetailsById($id);
		$this->load->view('admin_panel/quickview/view_banner_details_popup', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->banner_model->delete($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}

	public function delete_multiple_banner_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->banner_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

	#ADD/EDIT Portfolio
	public function add($banner_id = 0)
	{

		$ArrData = array();
		if ($banner_id > 0) {
			$ArrData['banner'] = $this->banner_model->getBannerById($banner_id);
			$ArrData['cms_title'] = "Update Banner";
			$ArrData['edit_id'] = $banner_id;
		} else {
			$ArrData['cms_title'] = "Add Banner";
			$ArrData['edit_id'] = '';
		}
		$ArrData['button_url'] = base_url() . 'banner';
		$ArrData['button_label'] = 'View Banner';
		$ArrData['view_name'] = 'view_banner_addedit.php';

		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/banner-add-update-script.js')
		);
		$this->carabiner->js($js_assets);


		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/banner-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js($js_assets);


		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {
			/* validation Process Start */
			$this->load->library('form_validation');
			$this->form_validation->set_rules('banner_text', 'Banner Name', 'required');
			$this->form_validation->set_rules('banner_srno', 'Display Order', 'trim|required|is_natural|numeric');
			/* validation Process End */
			if ($this->form_validation->run()) {
				if ($banner_id > 0) { //update process
					if (isset($_FILES['banner_image']) && is_uploaded_file($_FILES['banner_image']['tmp_name'])) {
						$config['upload_path'] = 'uploads/banner/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');
						$data['upload_data'] = '';

						if (!$this->upload->do_upload('banner_image')) {
							$data = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$data = array('msg' => "Upload success!");
							$data['upload_data'] = $this->upload->data();
						}
						$data['image_name'] = $this->banner_model->delete_image($banner_id);
						$data['image_name'] = $this->upload->data();
					} else // new file is not uploaded
					{
						$data['image_name']['file_name'] = $this->input->post('cat_image');
					}
					$banner_update_data = array(
						'banner_text' => $this->input->post('banner_text'),
						'banner_link' => $this->input->post('banner_link'),
						'description' => $this->input->post('description'),
						'banner_image' => $data['image_name']['file_name'],
						'banner_srno' => $this->input->post('banner_srno'),
						'is_active' => $this->input->post('is_active'),
						'banner_type' => $this->input->post('banner_type'),
						'modified_by' => $_SESSION['admin_id'],
						'modified_datetime' => date('Y-m-d'),
					);
					/* ECHO "<PRE>";
								   print_r($_POST);
								   print_r($_FILES);
								   print_r($banner_update_data);
								   exit; */
					$update = $this->banner_model->update($banner_id, $banner_update_data);
					if ($update > 0) {
						$this->session->set_flashdata('success_message', 'Banner details has been updated successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('banner-update/' . $banner_id);
				} else { // insert
					$config['upload_path'] = 'uploads/banner/';
					$config['allowed_types'] = 'gif|jpg|png';
					//load the upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');
					$data['upload_data'] = '';

					if (!$this->upload->do_upload('banner_image')) {
						$data = array('msg' => $this->upload->display_errors());
					} else {
						$data = array('msg' => "Upload success!");
						$data['upload_data'] = $this->upload->data();
					}

					$data['image_name'] = $this->upload->data();
					$banner_data = array(
						'banner_text' => $this->input->post('banner_text'),
						'banner_link' => $this->input->post('banner_link'),
						'description' => $this->input->post('description'),
						'banner_srno' => $this->input->post('banner_srno'),
						'banner_image' => $data['image_name']['file_name'],
						'is_active' => $this->input->post('is_active'),
						'banner_type' => $this->input->post('banner_type'),
						'created_by' => $_SESSION['admin_id'],
						'created_datetime' => date('Y-m-d'),
					);

					$insert_id = $this->banner_model->add($banner_data);
					if ($insert_id > 0) {
						$this->session->set_flashdata('success_message', 'Banner details has been added successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('banner');
				}
			} else {
				$this->load->view('admin_panel/admin_panel', $ArrData);
			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}

}