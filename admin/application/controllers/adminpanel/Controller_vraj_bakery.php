<?php



class Controller_vraj_bakery extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('vraj_bakery_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}

	public function index()

	{

		$ArrData = $this->vraj_bakery_model->getBrandListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$bakery_id = $aRow['bakery_id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $bakery_id . '" /><div class="btn-group">';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_vraj_bakery/view_bakery_ajax/" id="' . $bakery_id . '" title="Vraj Bakery Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$actions .= '<a href="' . $base_url . 'vraj_bakery-update/' . $bakery_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'vraj_bakery-delete/' . $bakery_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';



				$row[] = $actions;

				$row[] = $aRow['bakery_id'];

				$row[] = $aRow['product_name'];



				$row[] = getIsactiveButtonForList($aRow['is_active'], $bakery_id, 'tbl_vraj_bakery', 'bakery_id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Vraj Bakery List';

		$ArrPageData['button_url'] = base_url() . 'vraj_bakery-add';

		$ArrPageData['button_label'] = 'Add Vraj Bakery';

		$ArrPageData['view_name'] = 'view_vraj_bakery_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}


	public function view_bakery_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->vraj_bakery_model->getBrandAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_vraj_bakery_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->vraj_bakery_model->delete($id);

		if ($result) {

			echo 'Yes';

		} else {

			echo 'No';

		}



	}



	public function delete_multiple_brand_record()

	{

		$id = $_POST['primary_id'];

		$primary_idArr = explode(',', $id);

		$result = 0;

		foreach ($primary_idArr as $primary_id) {

			$result += $this->vraj_bakery_model->delete($primary_id);

		}

		if ($result > 0) {

			echo 1;

		} else {

			echo 0;

		}



	}



	#ADD/EDIT Portfolio

	public function add($bakery_id = 0)

	{



		$ArrData = array();

		$ArrData['ArrProduct'] = $this->vraj_bakery_model->product_list_data();

		if ($bakery_id > 0) {

			$ArrData['brand'] = $this->vraj_bakery_model->getBrandById($bakery_id);

			$ArrData['cms_title'] = "Update Vraj Bakery";

			$ArrData['edit_id'] = $bakery_id;

		} else {

			$ArrData['cms_title'] = "Add Vraj Bakery";

			$ArrData['edit_id'] = '';

		}



		$ArrData['button_url'] = base_url() . 'vraj_bakery';

		$ArrData['button_label'] = 'View Vraj Bakery';

		$ArrData['view_name'] = 'view_vraj_bakery_addedit.php';




		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/brand-add-update-script.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);





		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {

			/* validation Process Start */

			$this->load->library('form_validation');

			$this->form_validation->set_rules('bakery_product_id', 'Product ID', 'required');

			$this->form_validation->set_rules('search_string', 'Search', 'trim');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($bakery_id > 0) { //update process

					$brand_update_data = array(

						'bakery_product_id' => $this->input->post('bakery_product_id'),

						'is_active' => $this->input->post('is_active'),

						'modified_by' => $_SESSION['admin_id'],

						'modified_datetime' => date('Y-m-d'),

					);

					/* ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($brand_update_data);

								   exit; */

					$update = $this->vraj_bakery_model->update($bakery_id, $brand_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Bakery details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('vraj_bakery-update/' . $bakery_id);

				} else { // insert

					$brand_data = array(

						'bakery_product_id' => $this->input->post('bakery_product_id'),

						'is_active' => $this->input->post('is_active'),

						'created_by' => $_SESSION['admin_id'],

						'created_datetime' => date('Y-m-d'),

					);



					$insert_id = $this->vraj_bakery_model->add($brand_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Bakery details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('vraj_bakery');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}



}