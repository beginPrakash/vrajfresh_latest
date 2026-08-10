<?php



class Controller_catpro extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('app_catpro_model');

		$this->load->model('appbannerdata_model');

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

		$ArrData = $this->app_catpro_model->getBannerListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$id = $aRow['id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $id . '" /><div class="btn-group">';

				$actions .= '<a href="' . $base_url . 'appbannercategory_product-update/' . $id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'appbannercategory_product-delete/' . $id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

$procat_name = $this->app_catpro_model->getBannercatnamebyId($aRow['category_id']);

				$row[] = $actions;

				$row[] = $aRow['id'];

				$row[] = $aRow['product_name']. ' (' .$procat_name. ')';

                $row[] = $aRow['product_srno'];



				if ($aRow['product_image'] == '') {

					$row[] = $imag = '<img width="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 >';

				} else {

					$image = $aRow['product_image'];
					$row[] = $imag = '<img width="70px" src="' . $base_url . 'uploads/bannerdata/' . $image . '" border=0 alt="No Image found">';
				}

				$row[] = getIsactiveButtonForList($aRow['is_active'], $id, 'appbanner_products', 'id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Banner Product List';

		$ArrPageData['button_url'] = base_url() . 'appbannercategory_product-add';

		$ArrPageData['button_label'] = 'Add Banner Product';

		$ArrPageData['view_name'] = 'view_appcatpro_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}


	public function view_banner_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->app_catpro_model->getBannerAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_appbannercat_banner_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->app_catpro_model->delete($id);

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

			$result += $this->app_catpro_model->delete($primary_id);

		}

		if ($result > 0) {

			echo 1;

		} else {

			echo 0;

		}



	}



	#ADD/EDIT Portfolio

	public function add($id = 0)

	{



		$ArrData = array();

		if ($id > 0) {

			$ArrData['banner'] = $this->app_catpro_model->getBannerById($id);

			$ArrData['cms_title'] = "Update Banner Product";

			$ArrData['edit_id'] = $id;

		} else {

			$ArrData['cms_title'] = "Add Banner Product";

			$ArrData['edit_id'] = '';

		}

		$ArrData['bannercat'] = $this->appbannerdata_model->getBannerCategory();

		$ArrData['button_url'] = base_url() . 'appbannercategory_product';

		$ArrData['button_label'] = 'View Banner Product';

		$ArrData['view_name'] = 'view_appcatpro_addedit.php';



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

			$this->form_validation->set_rules('product_link', 'Product Slug', 'required');

			$this->form_validation->set_rules('product_srno', 'Display Order', 'trim|required|is_natural|numeric');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($id > 0) { //update process

					if (isset($_FILES['product_image']) && is_uploaded_file($_FILES['product_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/bannerdata/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$data['upload_data'] = '';



						if (!$this->upload->do_upload('product_image')) {

							$data = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$data = array('msg' => "Upload success!");

							$data['upload_data'] = $this->upload->data();

						}

						$data['image_name'] = $this->app_catpro_model->delete_image($id);

						$data['image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$data['image_name']['file_name'] = $this->input->post('cat_image');

					}


					$banner_update_data = array(

						'product_link' => $this->input->post('product_link'),

						'type' => $this->input->post('type'),

						'category_id' => $this->input->post('category_id'),

						'product_image' => $data['image_name']['file_name'],

                       'product_name' => $this->input->post('product_name'),
						'product_srno' => $this->input->post('product_srno'),

						'is_active' => $this->input->post('is_active'),


					);

					/* ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($banner_update_data);

								   exit; */

					$update = $this->app_catpro_model->update($id, $banner_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Banner details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('appbannercategory_product-update/' . $id);

				} else { // insert

					$config['upload_path'] = 'uploads/bannerdata/';

					$config['allowed_types'] = 'gif|jpg|png';

					//load the upload library

					$this->load->library('upload', $config);

					$this->upload->initialize($config);

					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';



					if (!$this->upload->do_upload('product_image')) {

						$data = array('msg' => $this->upload->display_errors());

					} else {

						$data = array('msg' => "Upload success!");

						$data['upload_data'] = $this->upload->data();

					}



					$data['image_name'] = $this->upload->data();

                    if (isset($_FILES['banner_mob_image']) && is_uploaded_file($_FILES['banner_mob_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/bannerdata/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$mdata['mob_upload_data'] = '';



						if (!$this->upload->do_upload('banner_mob_image')) {

							$mdata = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$mdata = array('msg' => "Upload success!");

							$mdata['mob_upload_data'] = $this->upload->data();

						}

						$mdata['mob_image_name'] = $this->app_catpro_model->delete_image($id );

						$mdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$mdata['mob_image_name']['file_name'] = $this->input->post('mob_cat_image');

					}

					if (isset($_FILES['banner_mobapp_image']) && is_uploaded_file($_FILES['banner_mobapp_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/bannerdata/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$madata['mobapp_upload_data'] = '';



						if (!$this->upload->do_upload('banner_mobapp_image')) {

							$madata = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$madata = array('msg' => "Upload success!");

							$madata['mobapp_upload_data'] = $this->upload->data();

						}

						$madata['mobapp_image_name'] = $this->app_catpro_model->delete_image($id );

						$madata['mobapp_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$madata['mobapp_image_name']['file_name'] = $this->input->post('mobapp_cat_image');

					}

					$banner_data = array(

						'product_link' => $this->input->post('product_link'),

						'type' => $this->input->post('type'),

						'category_id' => $this->input->post('category_id'),
'product_name' => $this->input->post('product_name'),

						'product_srno' => $this->input->post('product_srno'),

						'product_image' => $data['image_name']['file_name'],


						'is_active' => $this->input->post('is_active'),


					);



					$insert_id = $this->app_catpro_model->add($banner_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Banner details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('appbannercategory_product');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}



}