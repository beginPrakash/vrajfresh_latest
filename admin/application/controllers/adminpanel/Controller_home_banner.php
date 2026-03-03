<?php



class Controller_home_banner extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('home_banner_model');

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

		$ArrData = $this->home_banner_model->getBannerListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$banner_id = $aRow['banner_id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $banner_id . '" /><div class="btn-group">';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_home_banner/view_banner_ajax/" id="' . $banner_id . '" title="Banner Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$actions .= '<a href="' . $base_url . 'homebanners-update/' . $banner_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'homebanners-delete/' . $banner_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';



				$row[] = $actions;

				$row[] = $aRow['banner_id'];

				$row[] = $aRow['banner_link'];

                $row[] = $aRow['banner_srno'];



				if ($aRow['banner_image'] == '') {

					$row[] = $imag = '<img width="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 >';

				} else {

					$image = $aRow['banner_image'];
					if($aRow['banner_type']=='video'){
						$row[] = $imag = '<video width="70" height="70" controls autoplay><source src="' . $base_url . 'uploads/home_banner/' . $image . '" type="video/mp4"></video>';
					}else{
						$row[] = $imag = '<img width="70px" src="' . $base_url . 'uploads/home_banner/' . $image . '" border=0 alt="No Image found">';
					}
				}

				$row[] = getIsactiveButtonForList($aRow['is_active'], $banner_id, 'tbl_home_banners', 'banner_id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Banner List';

		$ArrPageData['button_url'] = base_url() . 'homebanners-add';

		$ArrPageData['button_label'] = 'Add Banner';

		$ArrPageData['view_name'] = 'view_homebanner_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}


	public function view_banner_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->home_banner_model->getBannerAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_home_banner_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->home_banner_model->delete($id);

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

			$result += $this->home_banner_model->delete($primary_id);

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

			$ArrData['banner'] = $this->home_banner_model->getBannerById($banner_id);

			$ArrData['cms_title'] = "Update Banner";

			$ArrData['edit_id'] = $banner_id;

		} else {

			$ArrData['cms_title'] = "Add Banner";

			$ArrData['edit_id'] = '';

		}

		$ArrData['button_url'] = base_url() . 'homebanners';

		$ArrData['button_label'] = 'View Banner';

		$ArrData['view_name'] = 'view_homebanner_addedit.php';



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

			$this->form_validation->set_rules('banner_link', 'Banner URL', 'required');

			$this->form_validation->set_rules('banner_srno', 'Display Order', 'trim|required|is_natural|numeric');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($banner_id > 0) { //update process

					if (isset($_FILES['banner_image']) && is_uploaded_file($_FILES['banner_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/home_banner/'; // set the filter image types

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

						$data['image_name'] = $this->home_banner_model->delete_image($banner_id);

						$data['image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$data['image_name']['file_name'] = $this->input->post('cat_image');

					}

                    if (isset($_FILES['banner_mob_image']) && is_uploaded_file($_FILES['banner_mob_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/home_banner/'; // set the filter image types

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

						$mdata['mob_image_name'] = $this->home_banner_model->delete_image($banner_id );

						$mdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$mdata['mob_image_name']['file_name'] = $this->input->post('mob_cat_image');

					}

					if (isset($_FILES['banner_mobapp_image']) && is_uploaded_file($_FILES['banner_mobapp_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/home_banner/'; // set the filter image types

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

						$madata['mobapp_image_name'] = $this->home_banner_model->delete_image($banner_id );

						$madata['mobapp_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$madata['mobapp_image_name']['file_name'] = $this->input->post('mobapp_cat_image');

					}

					$banner_update_data = array(

						'banner_link' => $this->input->post('banner_link'),

						'banner_type' => $this->input->post('banner_type'),

						'banner_image' => $data['image_name']['file_name'],

                        'banner_mob_image' => $mdata['mob_image_name']['file_name'],

						'banner_mobapp_image' => $madata['mobapp_image_name']['file_name'],

						'banner_srno' => $this->input->post('banner_srno'),

						'is_active' => $this->input->post('is_active'),

						'modified_by' => $_SESSION['admin_id'],

						'modified_datetime' => date('Y-m-d'),

					);

					/* ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($banner_update_data);

								   exit; */

					$update = $this->home_banner_model->update($banner_id, $banner_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Banner details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('homebanners-update/' . $banner_id);

				} else { // insert

					$config['upload_path'] = 'uploads/home_banner/';

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

                    if (isset($_FILES['banner_mob_image']) && is_uploaded_file($_FILES['banner_mob_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/home_banner/'; // set the filter image types

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

						$mdata['mob_image_name'] = $this->home_banner_model->delete_image($banner_id );

						$mdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$mdata['mob_image_name']['file_name'] = $this->input->post('mob_cat_image');

					}

					if (isset($_FILES['banner_mobapp_image']) && is_uploaded_file($_FILES['banner_mobapp_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/home_banner/'; // set the filter image types

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

						$madata['mobapp_image_name'] = $this->home_banner_model->delete_image($banner_id );

						$madata['mobapp_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$madata['mobapp_image_name']['file_name'] = $this->input->post('mobapp_cat_image');

					}

					$banner_data = array(

						'banner_link' => $this->input->post('banner_link'),

						'banner_type' => $this->input->post('banner_type'),

						'banner_srno' => $this->input->post('banner_srno'),

						'banner_image' => $data['image_name']['file_name'],

                        'banner_mob_image' => $mdata['mob_image_name']['file_name'],

						'banner_mobapp_image' => $madata['mobapp_image_name']['file_name'],

						'is_active' => $this->input->post('is_active'),

						'created_by' => $_SESSION['admin_id'],

						'created_datetime' => date('Y-m-d'),

					);



					$insert_id = $this->home_banner_model->add($banner_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Banner details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('homebanners');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}



}