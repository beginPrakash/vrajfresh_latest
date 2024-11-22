<?php



class Controller_adver_top extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('adver_top_model');

		$this->load->model('common_model');

		$this->load->model('Master_model', 'master');



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

		$ArrData = $this->adver_top_model->getBannerListData($_POST);

		if (@$_REQUEST['columns'] != "") {

			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());



			$i = $_REQUEST['start'] + 1;

			foreach ($ArrData['result'] as $aRow) {

				$row = array();

				$base_url = base_url();

				$adv_id  = $aRow['adv_id'];

				$actions = '';

				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $adv_id  . '" /><div class="btn-group">';

				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_adver_top/view_banner_ajax/" id="' . $adv_id  . '" title="Advertise Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';

				$actions .= '<a href="' . $base_url . 'advertises-update/' . $adv_id  . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';

				$actions .= '<a rel="' . $base_url . 'advertises-delete/' . $adv_id  . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';



				$row[] = $actions;

				$row[] = $aRow['adv_id'];


				if ($aRow['adv_image'] == '') {

					$row[] = $imag = '<img width="70px" height="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 >';

				} else {

					$image = $aRow['adv_image'];

					$row[] = $imag = '<img width="70px" height="70px" src="' . $base_url . 'uploads/advertise/' . $image . '" border=0 alt="No Image found">';

				}

				$row[] = $aRow['adv_link'];

				$row[] = $aRow['adv_srno'];

				$row[] = ucfirst($aRow['adv_type']);

				$row[] = getIsactiveButtonForList($aRow['is_active'], $adv_id , 'tbl_advertise_top', 'adv_id');

				$i++;

				$output['aaData'][] = $row;



			}

			echo json_encode($output);

			exit;

		}

		/* DATA TABLE END */

		$ArrPageData = array();

		$ArrPageData['cms_title'] = 'Advertisement List';

		$ArrPageData['button_url'] = base_url() . 'advertises-add';

		$ArrPageData['button_label'] = 'Add Advertisement';

		$ArrPageData['view_name'] = 'view_adver_top_list.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}



	public function view_banner_ajax()

	{

		$id = $this->input->post('id');

		$data['ArrFieldData'] = $this->adver_top_model->getBannerAllDetailsById($id);

		$this->load->view('admin_panel/quickview/view_adver_details_popup', $data);

	}



	public function delete_ajax($id)

	{

		$result = $this->adver_top_model->delete($id);

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

			$result += $this->adver_top_model->delete($primary_id);

		}

		if ($result > 0) {

			echo 1;

		} else {

			echo 0;

		}



	}



	#ADD/EDIT Portfolio

	public function add($adv_id  = 0)
	{



		$ArrData = array();

		$ArrData['category'] = $this->master->get_list_of_data_order_by('tbl_categories', array('is_active' => 1, 'is_deleted' => 0), 'category_name');

		if ($adv_id  > 0) {

			$ArrData['banner'] = $this->adver_top_model->getBannerById($adv_id );

			$ArrData['cms_title'] = "Update Advertisement";

			$ArrData['edit_id'] = $adv_id ;

		} else {

			$ArrData['cms_title'] = "Add Advertisement";

			$ArrData['edit_id'] = '';

		}

		$ArrData['button_url'] = base_url() . 'advertises';

		$ArrData['button_label'] = 'View Advertisement';

		$ArrData['view_name'] = 'view_adver_top_addedit.php';




		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/banner-add-update-script.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);





		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {

			/* validation Process Start */

			$this->load->library('form_validation');

			$this->form_validation->set_rules('adv_srno', 'Display Order', 'trim|required|is_natural|numeric');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($adv_id  > 0) { //update process

					if (isset($_FILES['adv_image']) && is_uploaded_file($_FILES['adv_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library
						
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');
						
						$data['upload_data'] = '';

						if (!$this->upload->do_upload('adv_image')) {							
							$data = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$data = array('msg' => "Upload success!");
							$data['upload_data'] = $this->upload->data();
						}

						$data['image_name'] = $this->adver_top_model->delete_adv_image($adv_id, 'adv_image');
						$data['image_name'] = $this->upload->data();

					} else // new file is not uploaded
					{
						$data['image_name']['file_name'] = $this->input->post('cat_image');
					}

					if (isset($_FILES['adv_mob_image']) && is_uploaded_file($_FILES['adv_mob_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');

						$mdata['mob_upload_data'] = '';

						if (!$this->upload->do_upload('adv_mob_image')) {
							$mdata = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$mdata = array('msg' => "Upload success!");
							$mdata['mob_upload_data'] = $this->upload->data();
						}

						$mdata['mob_image_name'] = $this->adver_top_model->delete_adv_image($adv_id, 'adv_mob_image');
						$mdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded
					{
						$mdata['mob_image_name']['file_name'] = $this->input->post('mob_cat_image');
					}

					if (isset($_FILES['alt_adv_image']) && is_uploaded_file($_FILES['alt_adv_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library
						
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');
						
						$pdata['upload_data'] = '';

						if (!$this->upload->do_upload('alt_adv_image')) {							
							$pdata = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$pdata = array('msg' => "Upload success!");
							$pdata['upload_data'] = $this->upload->data();
						}

						$pdata['image_name'] = $this->adver_top_model->delete_adv_image($adv_id, 'alt_adv_image');
						$pdata['image_name'] = $this->upload->data();

					} else // new file is not uploaded
					{
						$pdata['image_name']['file_name'] = $this->input->post('cat_alt_image');
					}

					if (isset($_FILES['alt_adv_mob_image']) && is_uploaded_file($_FILES['alt_adv_mob_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');

						$pmdata['mob_upload_data'] = '';

						if (!$this->upload->do_upload('alt_adv_mob_image')) {
							$pmdata = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$pmdata = array('msg' => "Upload success!");
							$pmdata['mob_upload_data'] = $this->upload->data();
						}

						$pmdata['mob_image_name'] = $this->adver_top_model->delete_adv_image($adv_id, 'alt_adv_mob_image');
						$pmdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded
					{
						$pmdata['mob_image_name']['file_name'] = $this->input->post('cat_alt_m_image');
					}

					$banner_update_data = array(
						'adv_link' => $this->input->post('adv_link'),
						'category_id' => $this->input->post('category_id'),
						'adv_image' => $data['image_name']['file_name'],					
						'adv_mob_image' => $mdata['mob_image_name']['file_name'],
						'adv_srno' => $this->input->post('adv_srno'),
						'is_active' => $this->input->post('is_active'),
						'adv_type' => $this->input->post('adv_type'),
						'modified_by' => $_SESSION['admin_id'],
						'modified_datetime' => date('Y-m-d'),
					);

					if($this->input->post('adv_type') == "bottom"){
						$banner_update_data['alt_adv_image'] = $pdata['image_name']['file_name'];
						$banner_update_data['alt_adv_mob_image'] = $pmdata['mob_image_name']['file_name'];
						$banner_update_data['alt_adv_link'] = $this->input->post('alt_adv_link');
					} else {
						$this->adver_top_model->delete_adv_image($adv_id, 'alt_adv_image');
						$this->adver_top_model->delete_adv_image($adv_id, 'alt_adv_mob_image');
						$banner_update_data['alt_adv_image'] = '';
						$banner_update_data['alt_adv_mob_image'] = '';
						$banner_update_data['alt_adv_link'] = '';
					}

					$update = $this->adver_top_model->update($adv_id , $banner_update_data);
					if ($update > 0) {
						$this->session->set_flashdata('success_message', 'Advertise details has been updated successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}

					redirect('advertises-update/' . $adv_id );

				} else { // insert
					
					$config['upload_path'] = 'uploads/advertise/';
					$config['allowed_types'] = 'gif|jpg|png';
					
					//load the upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';

					if (!$this->upload->do_upload('adv_image')) {
						$data = array('msg' => $this->upload->display_errors());
					} else {
						$data = array('msg' => "Upload success!");
						$data['upload_data'] = $this->upload->data();
					}
					$data['image_name'] = $this->upload->data();

					if (isset($_FILES['adv_mob_image']) && is_uploaded_file($_FILES['adv_mob_image']['tmp_name'])) {
						
						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');
						$mdata['mob_upload_data'] = '';

						if (!$this->upload->do_upload('adv_mob_image')) {
							$mdata = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$mdata = array('msg' => "Upload success!");
							$mdata['mob_upload_data'] = $this->upload->data();
						}

						//$mdata['mob_image_name'] = $this->adver_top_model->delete_image($adv_id );
						$mdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded
					{
						$mdata['mob_image_name']['file_name'] = $this->input->post('mob_cat_image');
					}

					if (isset($_FILES['alt_adv_image']) && is_uploaded_file($_FILES['alt_adv_image']['tmp_name'])) {
						
						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');
						$pdata['upload_data'] = '';

						if (!$this->upload->do_upload('alt_adv_image')) {
							$pdata = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$pdata = array('msg' => "Upload success!");
							$pdata['upload_data'] = $this->upload->data();
						}

						$pdata['image_name'] = $this->upload->data();

					} else // new file is not uploaded
					{
						$pdata['image_name']['file_name'] = '';
					}

					if (isset($_FILES['alt_adv_mob_image']) && is_uploaded_file($_FILES['alt_adv_mob_image']['tmp_name'])) {
						
						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');
						$pmdata['mob_upload_data'] = '';

						if (!$this->upload->do_upload('alt_adv_mob_image')) {
							$pmdata = array('msg' => $this->upload->display_errors());
						} else { //else, set the success message
							$pmdata = array('msg' => "Upload success!");
							$pmdata['mob_upload_data'] = $this->upload->data();
						}

						//$pmdata['mob_image_name'] = $this->adver_top_model->delete_image($adv_id );
						$pmdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded
					{
						$pmdata['mob_image_name']['file_name'] = '';
					}

					$banner_data = array(
						'adv_link' => $this->input->post('adv_link'),
						'category_id' => $this->input->post('category_id'),
						'adv_srno' => $this->input->post('adv_srno'),
						'adv_image' => $data['image_name']['file_name'],
						'adv_mob_image' => $mdata['mob_image_name']['file_name'],
						'is_active' => $this->input->post('is_active'),
						'adv_type' => $this->input->post('adv_type'),
						'created_by' => $_SESSION['admin_id'],
						'created_datetime' => date('Y-m-d'),
					);
					if($this->input->post('adv_type') == "bottom"){
						$banner_data['alt_adv_image'] = $pdata['image_name']['file_name'];
						$banner_data['alt_adv_mob_image'] = $pmdata['mob_image_name']['file_name'];
						$banner_data['alt_adv_link'] = $this->input->post('alt_adv_link');
					}


					$insert_id = $this->adver_top_model->add($banner_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Advertisement details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('advertises');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}
	
	public function add_old($adv_id  = 0)
	{



		$ArrData = array();

		if ($adv_id  > 0) {

			$ArrData['banner'] = $this->adver_top_model->getBannerById($adv_id );

			$ArrData['cms_title'] = "Update Advertisement";

			$ArrData['edit_id'] = $adv_id ;

		} else {

			$ArrData['cms_title'] = "Add Advertisement";

			$ArrData['edit_id'] = '';

		}

		$ArrData['button_url'] = base_url() . 'advertises';

		$ArrData['button_label'] = 'View Advertisement';

		$ArrData['view_name'] = 'view_adver_top_addedit.php';




		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/banner-add-update-script.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),

			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),

		);

		$this->carabiner->js($js_assets);





		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {

			/* validation Process Start */

			$this->load->library('form_validation');

			$this->form_validation->set_rules('adv_srno', 'Display Order', 'trim|required|is_natural|numeric');

			/* validation Process End */

			if ($this->form_validation->run()) {

				if ($adv_id  > 0) { //update process

					if (isset($_FILES['adv_image']) && is_uploaded_file($_FILES['adv_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$data['upload_data'] = '';



						if (!$this->upload->do_upload('adv_image')) {

							$data = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$data = array('msg' => "Upload success!");

							$data['upload_data'] = $this->upload->data();

						}

						$data['image_name'] = $this->adver_top_model->delete_image($adv_id );

						$data['image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$data['image_name']['file_name'] = $this->input->post('cat_image');

					}

					if (isset($_FILES['adv_mob_image']) && is_uploaded_file($_FILES['adv_mob_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$mdata['mob_upload_data'] = '';



						if (!$this->upload->do_upload('adv_mob_image')) {

							$mdata = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$mdata = array('msg' => "Upload success!");

							$mdata['mob_upload_data'] = $this->upload->data();

						}

						$mdata['mob_image_name'] = $this->adver_top_model->delete_image($adv_id );

						$mdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$mdata['mob_image_name']['file_name'] = $this->input->post('mob_cat_image');

					}

					$banner_update_data = array(

						'adv_link' => $this->input->post('adv_link'),

						'adv_image' => $data['image_name']['file_name'],
						
						'adv_mob_image' => $mdata['mob_image_name']['file_name'],

						'adv_srno' => $this->input->post('adv_srno'),

						'is_active' => $this->input->post('is_active'),

						'adv_type' => $this->input->post('adv_type'),

						'modified_by' => $_SESSION['admin_id'],

						'modified_datetime' => date('Y-m-d'),

					);

					 /*ECHO "<PRE>";

								   print_r($_POST);

								   print_r($_FILES);

								   print_r($banner_update_data);

								   exit; */

					$update = $this->adver_top_model->update($adv_id , $banner_update_data);

					if ($update > 0) {

						$this->session->set_flashdata('success_message', 'Advertise details has been updated successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('advertises-update/' . $adv_id );

				} else { // insert

					$config['upload_path'] = 'uploads/advertise/';

					$config['allowed_types'] = 'gif|jpg|png';

					//load the upload library

					$this->load->library('upload', $config);

					$this->upload->initialize($config);

					$this->upload->set_allowed_types('*');

					$data['upload_data'] = '';



					if (!$this->upload->do_upload('adv_image')) {

						$data = array('msg' => $this->upload->display_errors());

					} else {

						$data = array('msg' => "Upload success!");

						$data['upload_data'] = $this->upload->data();

					}
					$data['image_name'] = $this->upload->data();

					if (isset($_FILES['adv_mob_image']) && is_uploaded_file($_FILES['adv_mob_image']['tmp_name'])) {

						$config['upload_path'] = 'uploads/advertise/'; // set the filter image types

						$config['allowed_types'] = 'gif|jpg|png'; //load the upload library

						$this->load->library('upload', $config);

						$this->upload->initialize($config);

						$this->upload->set_allowed_types('*');

						$mdata['mob_upload_data'] = '';



						if (!$this->upload->do_upload('adv_mob_image')) {

							$mdata = array('msg' => $this->upload->display_errors());

						} else { //else, set the success message

							$mdata = array('msg' => "Upload success!");

							$mdata['mob_upload_data'] = $this->upload->data();

						}

						$mdata['mob_image_name'] = $this->adver_top_model->delete_image($adv_id );

						$mdata['mob_image_name'] = $this->upload->data();

					} else // new file is not uploaded

					{

						$mdata['mob_image_name']['file_name'] = $this->input->post('mob_cat_image');

					}


					

					$banner_data = array(

						'adv_link' => $this->input->post('adv_link'),

						'adv_srno' => $this->input->post('adv_srno'),

						'adv_image' => $data['image_name']['file_name'],

						'adv_mob_image' => $mdata['mob_image_name']['file_name'],

						'is_active' => $this->input->post('is_active'),

						'adv_type' => $this->input->post('adv_type'),

						'created_by' => $_SESSION['admin_id'],

						'created_datetime' => date('Y-m-d'),

					);



					$insert_id = $this->adver_top_model->add($banner_data);

					if ($insert_id > 0) {

						$this->session->set_flashdata('success_message', 'Advertisement details has been added successfully.');

					} else {

						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');

					}

					redirect('advertises');

				}

			} else {

				$this->load->view('admin_panel/admin_panel', $ArrData);

			}

		} else {

			$this->load->view('admin_panel/admin_panel', $ArrData);

		}

	}



}