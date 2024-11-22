<?php

class Controller_testimonial extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('testimonial_model');
		$this->load->model('common_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}

	}
	public function index()
	{
		$ArrData = $this->testimonial_model->getTestimonialListData($_POST);
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = array();
				$base_url = base_url();
				$testimonial_id = $aRow['testimonial_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $testimonial_id . '" /><div class="btn-group">';
				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_testimonial/view_testimonial_ajax/" id="' . $testimonial_id . '" title="Testimonial Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$actions .= '<a href="' . $base_url . 'testimonial-update/' . $testimonial_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$actions .= '<a rel="' . $base_url . 'testimonial-delete/' . $testimonial_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

				$row[] = $actions;
				$row[] = $aRow['testimonial_id'];
				$row[] = $aRow['customer_name'];


				if (empty($aRow['description']))
					$row[] = '';
				else
					$row[] = substr($aRow['description'], 0, 20);

				if ($aRow['testimonial_image'] == '') {
					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 >';
				} else {
					$image = $aRow['testimonial_image'];
					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/testimonial/' . $image . '" border=0 alt="No Image found">';
				}
				$row[] = getIsactiveButtonForList($aRow['is_active'], $testimonial_id, 'tbltestimonial', 'testimonial_id');
				$i++;
				$output['aaData'][] = $row;

			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Testimonial List';
		$ArrPageData['button_url'] = base_url() . 'testimonial-add';
		$ArrPageData['button_label'] = 'Add Testimonial';
		$ArrPageData['view_name'] = 'view_testimonial_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function testimonial_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->testimonial_model->ExportTestimonialData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['testimonial_id'];
			$row[] = $ArrData['customer_name'];
			$row[] = $ArrData['customer_subname'];
			$row[] = $ArrData['description'];
			$row[] = $ArrData['is_active'];
			$data[] = $row;
		}
		$report_title = "qe_testimonial_list_" . time();
		$ArrHeading = array('Sr No', 'Testimonial ID', 'site Name', 'Customer Name', 'Description', 'Long Description', 'Active');
		array_to_csv($ArrHeading, $data, $report_title);

	}

	public function view_testimonial_ajax()
	{
		$id = $this->input->post('id');
		$data['ArrFieldData'] = $this->testimonial_model->getTestimonialAllDetailsById($id);
		$this->load->view('admin_panel/quickview/view_testimonial_details_popup', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->testimonial_model->delete($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}

	public function delete_multiple_testimonial_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->testimonial_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

	#ADD/EDIT Portfolio
	public function add($testimonial_id = 0)
	{

		$ArrData = array();
		if ($testimonial_id > 0) {
			$ArrData['testimonial'] = $this->testimonial_model->getTestimonialById($testimonial_id);
			$ArrData['cms_title'] = "Update Testimonial";
			$ArrData['edit_id'] = $testimonial_id;
		} else {
			$ArrData['cms_title'] = "Add Testimonial";
			$ArrData['edit_id'] = '';
		}

		$ArrData['button_url'] = base_url() . 'testimonial';
		$ArrData['button_label'] = 'View Testimonial';
		$ArrData['view_name'] = 'view_testimonial_addedit.php';

		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/testimonial-add-update-script.js')
		);
		$this->carabiner->js($js_assets);


		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/testimonial-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->js($js_assets);


		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {
			/* validation Process Start */
			$this->load->library('form_validation');
			$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
			$this->form_validation->set_rules('display_order', 'Display Order', 'trim|required|is_natural|numeric');
			/* validation Process End */
			if ($this->form_validation->run()) {
				if ($testimonial_id > 0) { //update process
					if (isset($_FILES['testimonial_image']) && is_uploaded_file($_FILES['testimonial_image']['tmp_name'])) {
						$config['upload_path'] = 'uploads/testimonial/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');
						$data['upload_data'] = '';

						if (!$this->upload->do_upload('testimonial_image')) {
							$data = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$data = array('msg' => "Upload success!");
							$data['upload_data'] = $this->upload->data();
						}
						$data['image_name'] = $this->testimonial_model->delete_image($testimonial_id);
						$data['image_name'] = $this->upload->data();
					} else // new file is not uploaded
					{
						$data['image_name']['file_name'] = $this->input->post('cat_image');
					}
					$testimonial_update_data = array(
						'customer_name' => $this->input->post('customer_name'),
						'customer_subname' => $this->input->post('customer_subname'),
						'description' => $this->input->post('description'),
						'testimonial_image' => $data['image_name']['file_name'],
						'display_order' => $this->input->post('display_order'),
						'is_active' => $this->input->post('is_active'),
						'modified_by' => $_SESSION['admin_id'],
						'modified_datetime' => date('Y-m-d'),
					);
					/* ECHO "<PRE>";
								   print_r($_POST);
								   print_r($_FILES);
								   print_r($testimonial_update_data);
								   exit; */
					$update = $this->testimonial_model->update($testimonial_id, $testimonial_update_data);
					if ($update > 0) {
						$this->session->set_flashdata('success_message', 'Testimonial details has been updated successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('testimonial-update/' . $testimonial_id);
				} else { // insert
					$config['upload_path'] = 'uploads/testimonial/';
					$config['allowed_types'] = 'gif|jpg|png';
					//load the upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');
					$data['upload_data'] = '';

					if (!$this->upload->do_upload('testimonial_image')) {
						$data = array('msg' => $this->upload->display_errors());
					} else {
						$data = array('msg' => "Upload success!");
						$data['upload_data'] = $this->upload->data();
					}

					$data['image_name'] = $this->upload->data();
					$testimonial_data = array(
						'customer_name' => $this->input->post('customer_name'),
						'customer_subname' => $this->input->post('customer_subname'),
						'description' => $this->input->post('description'),
						'display_order' => $this->input->post('display_order'),
						'testimonial_image' => $data['image_name']['file_name'],
						'is_active' => $this->input->post('is_active'),
						'created_by' => $_SESSION['admin_id'],
						'created_datetime' => date('Y-m-d'),
					);

					$insert_id = $this->testimonial_model->add($testimonial_data);
					if ($insert_id > 0) {
						$this->session->set_flashdata('success_message', 'Testimonial details has been added successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('testimonial');
				}
			} else {
				$this->load->view('admin_panel/admin_panel', $ArrData);
			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}

}