<?php



class Controller_fresh_veg extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('fresh_veg_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}

	public function index()

	{

		$ArrData = $this->fresh_veg_model->getBrandListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$fresh_id = $aRow['fresh_id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $fresh_id . '" /><div class="btn-group">';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_fresh_veg/view_brand_ajax/" id="' . $fresh_id . '" title="Fresh veg & fruites Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$actions .= '<a href="' . $base_url . 'fresh_veg-update/' . $fresh_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'fresh_veg-delete/' . $fresh_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';



				$row[] = $actions;

				$row[] = $aRow['fresh_id'];

				$row[] = $aRow['product_name'];



				$row[] = getIsactiveButtonForList($aRow['is_active'], $fresh_id, 'tbl_fresh_veg', 'fresh_id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Fresh Vegetables & Fruits List';

		$ArrPageData['button_url'] = base_url() . 'fresh_veg-add';

		$ArrPageData['button_label'] = 'Add New Fresh Vegetables & Fruits';

		$ArrPageData['view_name'] = 'view_fresh_veg_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}


	public function view_brand_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->fresh_veg_model->getBrandAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_fresh_veg_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->fresh_veg_model->delete($id);

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

			$result += $this->fresh_veg_model->delete($primary_id);

		}

		if ($result > 0) {

			echo 1;

		} else {

			echo 0;

		}



	}



	#ADD/EDIT Portfolio

	public function add($fresh_id = 0)

	{



		$ArrData = array();

		$ArrData['ArrProduct'] = $this->fresh_veg_model->product_list_data();

		if ($fresh_id > 0) {

			$ArrData['brand'] = $this->fresh_veg_model->getBrandById($fresh_id);

			$ArrData['cms_title'] = "Update New Fresh Vegetables & Fruits";

			$ArrData['edit_id'] = $fresh_id;

		} else {

			$ArrData['cms_title'] = "Add New Fresh Vegetables & Fruits";

			$ArrData['edit_id'] = '';

		}



		$ArrData['button_url'] = base_url() . 'fresh_veg';

		$ArrData['button_label'] = 'View New Fresh Vegetables & Fruits';

		$ArrData['view_name'] = 'view_fresh_veg_addedit.php';




		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/brand-add-update-script.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);





		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {

			/* validation Process Start */

			$this->load->library('form_validation');

			$this->form_validation->set_rules('fresh_product_id', 'Product ID', 'required');

			$this->form_validation->set_rules('search_string', 'Search', 'trim');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($fresh_id > 0) { //update process

					$brand_update_data = array(

						'fresh_product_id' => $this->input->post('fresh_product_id'),

						'is_active' => $this->input->post('is_active'),

						'modified_by' => $_SESSION['admin_id'],

						'modified_datetime' => date('Y-m-d'),

					);

					/* ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($brand_update_data);

								   exit; */

					$update = $this->fresh_veg_model->update($fresh_id, $brand_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Fresh veg & fruites details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('fresh_veg-update/' . $fresh_id);

				} else { // insert

					$brand_data = array(

						'fresh_product_id' => $this->input->post('fresh_product_id'),

						'is_active' => $this->input->post('is_active'),

						'created_by' => $_SESSION['admin_id'],

						'created_datetime' => date('Y-m-d'),

					);



					$insert_id = $this->fresh_veg_model->add($brand_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Fresh veg & fruites details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('fresh_veg');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}



}