<?php

class Controller_home_product_slider extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('home_product_slider_model');
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
		$ArrData = $this->home_product_slider_model->getBannerListData($_POST);
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = array();
				$base_url = base_url();
				$home_product_slider_id = $aRow['home_product_slider_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $home_product_slider_id . '" /><div class="btn-group">';
				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_home_product_slider/view_homep_slider_ajax/" id="' . $home_product_slider_id . '" title="Slider Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$actions .= '<a href="' . $base_url . 'homep_slider-update/' . $home_product_slider_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$actions .= '<a rel="' . $base_url . 'homep_slider-delete/' . $home_product_slider_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

				$row[] = $actions;
				$row[] = $aRow['home_product_slider_id'];
				$row[] = $aRow['title'];
				$row[] = getIsactiveButtonForList($aRow['is_active'], $home_product_slider_id, 'tbl_home_product_slider', 'home_product_slider_id');
				$i++;
				$output['aaData'][] = $row;

			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Home Product Slider List';
		$ArrPageData['button_url'] = base_url() . 'homep_slider-add';
		$ArrPageData['button_label'] = 'Add Home Product Slider';
		$ArrPageData['view_name'] = 'view_homep_slider_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function view_homep_slider_ajax()
	{
		$id = $this->input->post('id');
		$data['ArrFieldData'] = $this->home_product_slider_model->getBannerAllDetailsById($id);
		$this->load->view('admin_panel/quickview/view_homep_slider_details_popup', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->home_product_slider_model->delete($id);
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
			$result += $this->home_product_slider_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

	#ADD/EDIT Portfolio
	public function add($home_product_slider_id = 0)
	{

		$ArrData = array();
		if ($home_product_slider_id > 0) {
			$ArrData['banner'] = $this->home_product_slider_model->getBannerById($home_product_slider_id);
			$ArrData['cms_title'] = "Update Home Product Slider";
			$ArrData['edit_id'] = $home_product_slider_id;
		} else {
			$ArrData['cms_title'] = "Add Home Product Slider";
			$ArrData['edit_id'] = '';
		}
		$ArrData['button_url'] = base_url() . 'homep_slider';
		$ArrData['button_label'] = 'View Home Product Slider';
		$ArrData['view_name'] = 'view_homep_slider_addedit.php';


		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/banner-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js($js_assets);


		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {
			/* validation Process Start */
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('slug', 'Slug', 'required');
			$this->form_validation->set_rules('display_order', 'Display Order', 'trim|required|is_natural|numeric');
			/* validation Process End */
			if ($this->form_validation->run()) {
				if ($home_product_slider_id > 0) { //update process
					$banner_update_data = array(
						'title' => $this->input->post('title'),
						'slug' => $this->input->post('slug'),
						'display_order' => $this->input->post('display_order'),
						'is_active' => $this->input->post('is_active'),
						'modified_by' => $_SESSION['admin_id'],
						'modified_datetime' => date('Y-m-d'),
					);
					/* ECHO "<PRE>";
								   print_r($_POST);
								   print_r($_FILES);
								   print_r($banner_update_data);
								   exit; */
					$update = $this->home_product_slider_model->update($home_product_slider_id, $banner_update_data);
					if ($update > 0) {
						$this->session->set_flashdata('success_message', 'Product Slider details has been updated successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('homep_slider-update/' . $home_product_slider_id);
				} else { // insert
					$banner_data = array(
						'title' => $this->input->post('title'),
						'slug' => $this->input->post('slug'),
						'display_order' => $this->input->post('display_order'),
						'is_active' => $this->input->post('is_active'),
						'created_by' => $_SESSION['admin_id'],
						'created_datetime' => date('Y-m-d'),
					);

					$insert_id = $this->home_product_slider_model->add($banner_data);
					if ($insert_id > 0) {
						$this->session->set_flashdata('success_message', 'Product Slider details has been added successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('homep_slider');
				}
			} else {
				$this->load->view('admin_panel/admin_panel', $ArrData);
			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}

}