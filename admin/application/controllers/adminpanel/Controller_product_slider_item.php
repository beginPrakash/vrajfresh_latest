<?php



class Controller_product_slider_item extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('product_slider_item_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}

	public function index()

	{

		$ArrData = $this->product_slider_item_model->getBrandListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$home_product_slider_item_id = $aRow['home_product_slider_item_id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $home_product_slider_item_id . '" /><div class="btn-group">';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_product_slider_item/view_slider_item_ajax/" id="' . $home_product_slider_item_id . '" title="Home Product Slider Item Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$actions .= '<a href="' . $base_url . 'homep_slider_item-update/' . $home_product_slider_item_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'homep_slider_item-delete/' . $home_product_slider_item_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';



				$row[] = $actions;

				$row[] = $aRow['home_product_slider_item_id'];

                $row[] = $aRow['title'];

				$row[] = $aRow['product_name'];

				$row[] = getIsactiveButtonForList($aRow['is_active'], $home_product_slider_item_id, 'tbl_home_product_slider_item', 'home_product_slider_item_id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Home Product Slider Item List';

		$ArrPageData['button_url'] = base_url() . 'homep_slider_item-add';

		$ArrPageData['button_label'] = 'Add Home Product Slider Item';

		$ArrPageData['view_name'] = 'view_homep_slider_item_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}


	public function view_slider_item_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->product_slider_item_model->getBrandAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_homep_slider_item_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->product_slider_item_model->delete($id);

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

			$result += $this->product_slider_item_model->delete($primary_id);

		}

		if ($result > 0) {

			echo 1;

		} else {

			echo 0;

		}



	}



	#ADD/EDIT Portfolio

	public function add($home_product_slider_item_id = 0)

	{

		$ArrData = array();

        $ArrData['ArrProductslider'] = $this->product_slider_item_model->productslider_list_data();

		$ArrData['ArrProduct'] = $this->product_slider_item_model->product_list_data();

		if ($home_product_slider_item_id > 0) {

			$ArrData['brand'] = $this->product_slider_item_model->getBrandById($home_product_slider_item_id);

			$ArrData['cms_title'] = "Update Home Product Slider Item";

			$ArrData['edit_id'] = $home_product_slider_item_id;

		} else {

			$ArrData['cms_title'] = "Add Home Product Slider Item";

			$ArrData['edit_id'] = '';

		}



		$ArrData['button_url'] = base_url() . 'homep_slider_item';

		$ArrData['button_label'] = 'View Home Product Slider Item';

		$ArrData['view_name'] = 'view_homep_slider_item_addedit.php';




		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);





		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {

			/* validation Process Start */

			$this->load->library('form_validation');

            //$this->form_validation->set_rules('home_product_slider_id', 'Product Slider ID', 'required');

			$this->form_validation->set_rules('product_id', 'Product ID', 'required');

			$this->form_validation->set_rules('search_string', 'Search', 'trim');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($home_product_slider_item_id > 0) { //update process

					$brand_update_data = array(

                        'home_product_slider_id' => $this->input->post('home_product_slider_id'),

						'product_id' => $this->input->post('product_id'),

						'is_active' => $this->input->post('is_active'),

						'modified_by' => $_SESSION['admin_id'],

						'modified_datetime' => date('Y-m-d'),

					);

					/* ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($brand_update_data);

								   exit; */

					$update = $this->product_slider_item_model->update($home_product_slider_item_id, $brand_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Slider Item details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('homep_slider_item-update/' . $home_product_slider_item_id);

				} else { // insert

					$brand_data = array(

                        'home_product_slider_id' => $this->input->post('home_product_slider_id'),

						'product_id' => $this->input->post('product_id'),

						'is_active' => $this->input->post('is_active'),

						'created_by' => $_SESSION['admin_id'],

						'created_datetime' => date('Y-m-d'),

					);



					$insert_id = $this->product_slider_item_model->add($brand_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Slider Item details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('homep_slider_item');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}



}