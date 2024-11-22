<?php



class Controller_stockup extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('stockup_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}

	public function index()

	{

		$ArrData = $this->stockup_model->getStockupListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$stockup_id = $aRow['stockup_id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $stockup_id . '" /><div class="btn-group">';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_stockup/view_stockup_ajax/" id="' . $stockup_id . '" title="Stockup Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$actions .= '<a href="' . $base_url . 'stockup-update/' . $stockup_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'stockup-delete/' . $stockup_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';



				$row[] = $actions;

				$row[] = $aRow['stockup_id'];

				$row[] = $aRow['stockup_name'];

                $row[] = $aRow['stockup_link'];

				if ($aRow['stockup_image'] == '') {

					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 >';

				} else {

					$image = $aRow['stockup_image'];

					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/stockup/' . $image . '" border=0 alt="No Image found">';

				}

				$row[] = getIsactiveButtonForList($aRow['is_active'], $stockup_id, 'tbl_stockup_frozen', 'stockup_id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Stockup Frozen List';

		$ArrPageData['button_url'] = base_url() . 'stockup-add';

		$ArrPageData['button_label'] = 'Add Stockup Frozen';

		$ArrPageData['view_name'] = 'view_stockup_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}



	public function view_stockup_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->stockup_model->getStockupAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_stockup_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->stockup_model->delete($id);

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

			$result += $this->stockup_model->delete($primary_id);

		}

		if ($result > 0) {

			echo 1;

		} else {

			echo 0;

		}



	}



	#ADD/EDIT Portfolio

	public function add($stockup_id = 0)

	{



		$ArrData = array();

		$ArrData['ArrParentBrand'] = $this->stockup_model->stockup_list_data();

		if ($stockup_id > 0) {

			$ArrData['brand'] = $this->stockup_model->getStockupById($stockup_id);

			$ArrData['cms_title'] = "Update Stockup Frozen";

			$ArrData['edit_id'] = $stockup_id;

		} else {

			$ArrData['cms_title'] = "Add Stockup Frozen";

			$ArrData['edit_id'] = '';

		}



		$ArrData['button_url'] = base_url() . 'stockup';

		$ArrData['button_label'] = 'View Stockup Frozen';

		$ArrData['view_name'] = 'view_stockup_addedit.php';






		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);





		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {

			/* validation Process Start */

			$this->load->library('form_validation');

			$this->form_validation->set_rules('stockup_name', 'Stockup Name', 'required');

			$this->form_validation->set_rules('search_string', 'Search', 'trim');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($stockup_id > 0) { //update process

					if (isset($_FILES['stockup_image']) && is_uploaded_file($_FILES['stockup_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/stockup/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$data['upload_data'] = '';



						if (!$this->upload->do_upload('stockup_image')) {

							$data = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$data = array('msg' => "Upload success!");

							$data['upload_data'] = $this->upload->data();

						}

						$data['image_name'] = $this->stockup_model->delete_image($stockup_id);

						$data['image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$data['image_name']['file_name'] = $this->input->post('cat_image');

					}

					$brand_update_data = array(

						'stockup_name' => $this->input->post('stockup_name'),

						'stockup_slug' => str_replace(' ', '-', strtolower(trim($this->input->post('stockup_name')))),

						'stockup_image' => $data['image_name']['file_name'],

						'stockup_link' => $this->input->post('stockup_link'),

						'is_active' => $this->input->post('is_active'),

						'modified_by' => $_SESSION['admin_id'],

						'modified_datetime' => date('Y-m-d'),

					);

					/* ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($brand_update_data);

								   exit; */

					$update = $this->stockup_model->update($stockup_id, $brand_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Stockup details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('stockup-update/' . $stockup_id);

				} else { // insert

					$config['upload_path'] = 'uploads/stockup/';

					$config['allowed_types'] = 'gif|jpg|png';

					//load the upload library

					$this->load->library('upload', $config);

					$this->upload->initialize($config);

					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';



					if (!$this->upload->do_upload('stockup_image')) {

						$data = array('msg' => $this->upload->display_errors());

					} else {

						$data = array('msg' => "Upload success!");

						$data['upload_data'] = $this->upload->data();

					}



					$data['image_name'] = $this->upload->data();

					$brand_data = array(

						'stockup_name' => $this->input->post('stockup_name'),

						'stockup_slug' => str_replace(' ', '-', strtolower(trim($this->input->post('stockup_name')))),

						'stockup_link' => $this->input->post('stockup_link'),

						'stockup_image' => $data['image_name']['file_name'],

						'is_active' => $this->input->post('is_active'),

						'created_by' => $_SESSION['admin_id'],

						'created_datetime' => date('Y-m-d'),

					);



					$insert_id = $this->stockup_model->add($brand_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Stockup details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('stockup');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}



}