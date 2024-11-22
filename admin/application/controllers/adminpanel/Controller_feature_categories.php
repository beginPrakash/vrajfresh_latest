<?php



class Controller_feature_categories extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('feature_categories_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}

	public function index()

	{

		$ArrData = $this->feature_categories_model->getFcategoryListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$feturecat_id = $aRow['feturecat_id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $feturecat_id . '" /><div class="btn-group">';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_feature_categories/view_fcategories_ajax/" id="' . $feturecat_id . '" title="Category Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$actions .= '<a href="' . $base_url . 'fcategories-update/' . $feturecat_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'fcategories-delete/' . $feturecat_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';



				$row[] = $actions;

				$row[] = $aRow['feturecat_id'];

				$row[] = $aRow['cat_name'];

                $row[] = $aRow['cat_link'];


				if ($aRow['cat_image'] == '') {

					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 >';

				} else {

					$image = $aRow['cat_image'];

					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/feature_categories/' . $image . '" border=0 alt="No Image found">';

				}

				$row[] = getIsactiveButtonForList($aRow['is_active'], $feturecat_id, 'tbl_feture_categories', 'feturecat_id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Feature Category List';

		$ArrPageData['button_url'] = base_url() . 'fcategories-add';

		$ArrPageData['button_label'] = 'Add Feature Category';

		$ArrPageData['view_name'] = 'view_feature_categories_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}

	public function view_fcategories_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->feature_categories_model->getBrandAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_fcategories_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->feature_categories_model->delete($id);

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

			$result += $this->feature_categories_model->delete($primary_id);

		}

		if ($result > 0) {

			echo 1;

		} else {

			echo 0;

		}



	}



	#ADD/EDIT Portfolio

	public function add($feturecat_id = 0)

	{



		$ArrData = array();

		$ArrData['ArrParentBrand'] = $this->feature_categories_model->brand_list_data();

		if ($feturecat_id > 0) {

			$ArrData['brand'] = $this->feature_categories_model->getBrandById($feturecat_id);

			$ArrData['cms_title'] = "Update Feature Categories";

			$ArrData['edit_id'] = $feturecat_id;

		} else {

			$ArrData['cms_title'] = "Add Feature Categories";

			$ArrData['edit_id'] = '';

		}



		$ArrData['button_url'] = base_url() . 'fcategories';

		$ArrData['button_label'] = 'View Feature Categories';

		$ArrData['view_name'] = 'view_feature_categories_addedit.php';



		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/brand-add-update-script.js')

		);

		$this->carabiner->js($js_assets);





		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);





		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {
 
			/* validation Process Start */

			$this->load->library('form_validation');

			$this->form_validation->set_rules('cat_name', 'Category Name', 'required');

			$this->form_validation->set_rules('search_string', 'Search', 'trim');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($feturecat_id > 0) { //update process

					if (isset($_FILES['cat_image']) && is_uploaded_file($_FILES['cat_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/feature_categories/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$data['upload_data'] = '';



						if (!$this->upload->do_upload('cat_image')) {

							$data = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$data = array('msg' => "Upload success!");

							$data['upload_data'] = $this->upload->data();

						}

						$data['image_name'] = $this->feature_categories_model->delete_image($feturecat_id);

						$data['image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$data['image_name']['file_name'] = $this->input->post('cat_image');

					}

					$brand_update_data = array(

						'cat_name' => $this->input->post('cat_name'),

						'cat_slug' => str_replace(' ', '-', strtolower(trim($this->input->post('cat_name')))),

						'cat_link' => $this->input->post('cat_link'),

						'cat_image' => $data['image_name']['file_name'],

						'is_active' => $this->input->post('is_active'),

						'modified_by' => $_SESSION['admin_id'],

						'modified_datetime' => date('Y-m-d'),

					);

					/* ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($brand_update_data);

								   exit; */

					$update = $this->feature_categories_model->update($feturecat_id, $brand_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Brand details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('fcategories-update/' . $feturecat_id);

				} else { // insert

					$config['upload_path'] = 'uploads/feature_categories/';

					$config['allowed_types'] = 'gif|jpg|png';

					//load the upload library

					$this->load->library('upload', $config);

					$this->upload->initialize($config);

					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';



					if (!$this->upload->do_upload('cat_image')) {

						$data = array('msg' => $this->upload->display_errors());

					} else {

						$data = array('msg' => "Upload success!");

						$data['upload_data'] = $this->upload->data();

					}



					$data['image_name'] = $this->upload->data();

					$brand_data = array(

						'cat_name' => $this->input->post('cat_name'),

						'cat_slug' => str_replace(' ', '-', strtolower(trim($this->input->post('cat_name')))),

						'cat_link' => $this->input->post('cat_link'),

						'cat_image' => $data['image_name']['file_name'],

						'is_active' => $this->input->post('is_active'),

						'created_by' => $_SESSION['admin_id'],

						'created_datetime' => date('Y-m-d'),

					);



					$insert_id = $this->feature_categories_model->add($brand_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Brand details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('fcategories');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}


}