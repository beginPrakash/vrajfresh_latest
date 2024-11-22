<?php

class Controller_promotional_code extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();
		$this->load->model('promotional_code_model');
		$this->load->model('promotional_code_product_model');
		$this->load->model('clientgroup_model');
		$this->load->model('brand_model');
		$this->load->model('category_model');
		$this->load->model('user_model');
		$this->load->model('common_model');


		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}

	}
	public function index()
	{
		$ArrData = $this->promotional_code_model->getPromotionalCodeListData($_POST);
		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {

				$row = array();
				$base_url = base_url();
				$promotional_code_id = $aRow['promotional_code_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $promotional_code_id . '" /><div class="btn-group">';
				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_promotional_code/view_promotional_code_ajax/" id="' . $promotional_code_id . '" title="Promotional Code Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$actions .= '<a href="' . $base_url . 'promotional-code-update/' . $promotional_code_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$actions .= '<a rel="' . $base_url . 'promotional-code-delete/' . $promotional_code_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';
				$actions .= '';
				$promotional_type = '';
				if ($aRow['promotional_type'] == 'S') {
					$promotional_type = 'Single';
				} elseif ($aRow['promotional_type'] == 'M') {
					$promotional_type = 'Multiple';
				} elseif ($aRow['promotional_type'] == 'OT') {
					$promotional_type = 'Only One Time';
				}
				$apply_to_product = '';
				if ($aRow['apply_to_product'] == 'A') {
					$apply_to_product = 'All Products';
				} elseif ($aRow['apply_to_product'] == 'SP') {
					$apply_to_product = 'Specific Products';
				}
				$apply_to = '';
				if ($aRow['apply_to'] == 'A') {
					$apply_to = 'All Customer';
				} elseif ($aRow['apply_to'] == 'SC') {
					$apply_to = 'Specific Customer';
				} elseif ($aRow['apply_to'] == 'SG') {
					$apply_to = 'Specific Group';
				}
				$row[] = $actions;
				$row[] = $aRow['promotional_code_id'];
				$row[] = $aRow['created_datetime'];
				$row[] = $aRow['promotional_code'];
				//$row[] = $promotional_type;
				$row[] = $apply_to_product;
				//$row[] = $apply_to;
				$row[] = $aRow['start_from'];
				$row[] = $aRow['valid_upto'];

				//$row[] = "<a rel='".$base_url."adminpanel/controller_promotional_code/view_promotional_code_payment_ajax/' id= '".$promotional_code_id."' title='Promotional Code Detail' class='view_action btn btn-default btn-sm'>view</a>";

				$row[] = getIsactiveButtonForList($aRow['is_active'], $promotional_code_id, 'tblpromotional_code', 'promotional_code_id');
				//$row[] = $status;
				$i++;
				$output['aaData'][] = $row;

			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Promotional Code List';
		$ArrPageData['button_url'] = base_url() . 'promotional-code-add';
		$ArrPageData['button_label'] = 'Add Promotional Code';
		$ArrPageData['view_name'] = 'view_promotional_code_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function promotional_code_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->promotional_code_model->ExportPromotionalCodeData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;

			$row = array();
			$row[] = $no;
			$row[] = $ArrData['promotional_code_id'];
			$row[] = $ArrData['created_datetime'];
			$row[] = $ArrData['promotional_code'];
			$row[] = $ArrData['promotional_type'];
			$row[] = $ArrData['apply_to_product'];
			$row[] = $ArrData['apply_to'];
			$row[] = $ArrData['start_from'];
			$row[] = $ArrData['valid_upto'];
			$row[] = $ArrData['is_active'];
			$data[] = $row;
		}
		$report_title = "qe_promotional_code_list_" . time();
		$ArrHeading = array('Sr No', 'Customer Membership ID', 'site Name', 'Date', 'Membership ID', 'Customer', 'Membership', 'Last Payment Date', 'Last Payment Status', 'Next Payment Date', 'Active');
		array_to_csv($ArrHeading, $data, $report_title);

	}

	public function view_promotional_code_ajax()
	{
		$id = $this->input->post('id');
		//echo $id; exit;
		$data['ArrFieldData'] = $this->promotional_code_model->getPromotionalCodeAllDetailsById($id);
		//echo "<PRE>"; print_r($data); exit;
		$this->load->view('admin_panel/quickview/view_promotional_code_details_popup', $data);
	}

	public function view_promotional_code_payment_ajax()
	{
		$id = $this->input->post('id');
		$data['ArrFieldData'] = $this->promotional_code_model->getPromotionalCodeAllPaymentDetailsById($id);
		$this->load->view('admin_panel/quickview/view_promotional_code_payment_details_popup', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->promotional_code_model->delete($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}


	public function delete_multiple_promotional_code_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->promotional_code_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

	#ADD/EDIT Portfolio
	public function add($promotional_code_id = 0)
	{
		//echo "<pre>";print_r($_POST);exit;
		$ArrData = array();

		#Get customer
		//$ArrData['ArrCustomer'] = $this->user_model->getAllCustomer();
//	echo $this->db->last_query();exit;
		#get code's selected client group
		$ArrSelectedClientGroup = $this->promotional_code_model->getPromotionalCodeClientGroup($promotional_code_id);

		#get products
		$ArrProducts = $this->promotional_code_model->get_all_products();
		$ArrData['ArrProducts'] = $ArrProducts;
		$ArrData['ArrBrands'] = $this->brand_model->brand_list_data();
		$ArrData['category'] = $this->category_model->category_list_data();

		#get client group
		$ArrClientGroup = $this->clientgroup_model->getClientGroup('is_active="1"');
		$ArrData['ArrClientGroup'] = $ArrClientGroup;

		$ArrSelectedProducts = array();
		$ArrData['ArrSelectedProducts'] = $ArrSelectedProducts;


		#make selected client group ids array
		$ArrSelectedClientGroupId = array();
		if (is_array($ArrSelectedClientGroup) && count($ArrSelectedClientGroup) > 0) {
			foreach ($ArrSelectedClientGroup as $clientgroup) {
				$ArrSelectedClientGroupId[] = $clientgroup['clientgroup_id'];
			}
		}

		if ($promotional_code_id > 0) {
			$ArrData['ArrFieldData'] = $this->promotional_code_model->getPromotionalCodeUsingID($promotional_code_id);
			$ArrSelectedClientGroup = $this->promotional_code_model->getPromotionalCodeClientGroup($promotional_code_id);
			$ArrSelectedProducts = $this->promotional_code_model->getSelectedProduct($promotional_code_id);
			$ArrData['ArrSelectedProducts'] = $ArrSelectedProducts;
			$ArrData['cms_title'] = "Update Promotional Code";
			$ArrData['edit_id'] = $promotional_code_id;
			#make selected client group ids array
			$ArrSelectedClientGroupId = array();
			if (is_array($ArrSelectedClientGroup) && count($ArrSelectedClientGroup) > 0) {
				foreach ($ArrSelectedClientGroup as $clientgroup) {
					$ArrSelectedClientGroupId[] = $clientgroup['clientgroup_id'];
				}
			}
			$ArrData['ArrSelectedClientGroupId'] = $ArrSelectedClientGroupId;
			//echo "<pre>";print_r($ArrSelectedClientGroupId);exit;

		} else {
			$ArrData['cms_title'] = "Add Promotional Code";
			$ArrData['edit_id'] = '';
		}

		$ArrData['button_url'] = base_url() . 'promotional-code';
		$ArrData['button_label'] = 'View Promotional Code';
		$ArrData['view_name'] = 'view_promotional_code_addedit.php';

		$css_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/css/jquery.multiselect.css'),
		);
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/promotional-code-script.js'),
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/jquery.multiselect.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->css($css_assets);
		$this->carabiner->js($js_assets);

		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {
			//print_r($_POST);exit;
			/* validation Process Start */
			$this->load->library('form_validation');
			$this->form_validation->set_rules('promotional_code', 'Promotional Code', 'required');
			/* validation Process End */
			$brand_ids = '';
			if (is_array($this->input->post('brands'))) {
				$brand_ids = "," . implode(",", $this->input->post('brands')) . ",";
			}
			$exclude_category = '';
			if (is_array($this->input->post('exclude_category'))) {
				$exclude_category = "," . implode(",", $this->input->post('exclude_category')) . ",";
			}
			if ($this->form_validation->run()) {
				//echo "1111";exit;
				if ($promotional_code_id > 0) { //update process
					$promotional_code_update_data = array(
						'promotional_code' => $this->input->post('promotional_code'),
						'discount_type' => $this->input->post('discount_type'),
						'start_from' => date('Y-m-d', strtotime($this->input->post('start_from'))),
						'valid_upto' => date('Y-m-d', strtotime($this->input->post('valid_upto'))),
						'description' => $this->input->post('description'),
						'discount_value' => $this->input->post('discount_value'),
						'promotional_type' => $this->input->post('promotional_type'),
						'minimum_order_value' => $this->input->post('minimum_order_value'),
						'maximum_order_discount' => $this->input->post('maximum_order_discount'),
						'brand_ids' => $brand_ids,
						'exclude_category' => $exclude_category,
						'apply_to' => $this->input->post('apply_to'),
						'apply_to_product' => $this->input->post('apply_to_product'),
						'specific_customer_id' => $this->input->post('specific_customer_id'),
						'is_active' => $this->input->post('is_active'),
						'modified_datetime' => date('Y-m-d H:i:s'),
						'modified_by' => get_current_admin_id(),
					);
					/* print_r($promotional_code_update_data);
								   exit; */
					$update = $this->promotional_code_model->update($promotional_code_id, $promotional_code_update_data);
					if ($update > 0) {

						#Start client group
						$ArrSelectedClientGroup = array();
						if (isset($_POST['selected_clientgroup'])) {
							$ArrSelectedClientGroup = $_POST['selected_clientgroup'];
						}

						#delete pre Client Group
						$this->promotional_code_model->delete_promotional_code_client_group($promotional_code_id);

						#add Client Group
						if (is_array($ArrSelectedClientGroup) && count($ArrSelectedClientGroup) > 0 && $_POST['apply_to'] == 'SG') {
							foreach ($ArrSelectedClientGroup as $clientgroup_id) {
								$promotional_code_client_group_data = array(
									'promotional_code_id' => $promotional_code_id,
									'clientgroup_id' => $clientgroup_id,
									'created_by' => $_SESSION['admin_id'],
									'created_datetime' => date('Y-m-d')
								);
								$this->promotional_code_model->addPromotionalCodeClientGroup($promotional_code_client_group_data);
							}
						}
						#End clien group



						#add product
						#delete pre Client Group
						$this->promotional_code_model->delete_promotional_code_product($promotional_code_id);

						$ArrSelectedProduct = array();
						if (isset($_POST['product_selected_product']) && isset($_POST['discount_value']) && isset($_POST['discount_type'])) {
							$ArrSelectedProduct = $_POST['product_selected_product'];
							$Arrdiscount_value = $_POST['discount_value'];
							$Arrdiscount_type = $_POST['discount_type'];
						}

						if (is_array($ArrSelectedProduct) && count($ArrSelectedProduct) > 0 && $_POST['apply_to_product'] == 'SP') {
							$i = 0;
							foreach ($ArrSelectedProduct as $product_id) {
								if ($ArrSelectedProduct[$i] > 0) {
									$promotional_code_client_group_data = array(
										'promotional_code_id' => $promotional_code_id,
										'product_id' => $ArrSelectedProduct[$i],
										'discount_value' => $this->input->post('discount_value'), //$Arrdiscount_value[$i],
										'discount_type' => $this->input->post('discount_type'), //$Arrdiscount_type[$i],
										'created_by' => $_SESSION['admin_id'],
										'created_datetime' => date('Y-m-d')
									);
									$this->promotional_code_model->addPromotionalCodeProduct($promotional_code_client_group_data);
								}
								$i++;
							}
						}
						#End product

						$this->session->set_flashdata('success_message', 'Promotional Code details has been updated successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('promotional-code-update/' . $promotional_code_id);
				} else { // insert
					//print_r($_REQUEST);
					$promotional_code_add_data = array(
						'promotional_code' => $this->input->post('promotional_code'),
						'discount_type' => $this->input->post('discount_type'),
						'start_from' => date('Y-m-d', strtotime($this->input->post('start_from'))),
						'valid_upto' => date('Y-m-d', strtotime($this->input->post('valid_upto'))),
						'description' => $this->input->post('description'),
						'discount_value' => $this->input->post('discount_value'),
						'promotional_type' => $this->input->post('promotional_type'),
						'minimum_order_value' => $this->input->post('minimum_order_value'),
						'maximum_order_discount' => $this->input->post('maximum_order_discount'),
						'brand_ids' => $brand_ids,
						'exclude_category' => $exclude_category,
						'apply_to' => $this->input->post('apply_to'),
						'apply_to_product' => $this->input->post('apply_to_product'),
						'specific_customer_id' => $this->input->post('specific_customer_id'),
						'is_active' => $this->input->post('is_active'),
						'created_datetime' => date('Y-m-d H:i:s'),
						'created_by' => get_current_admin_id(),
					);
					//print_r($promotional_code_add_data);
					//exit;
					$insert_id = $this->promotional_code_model->add($promotional_code_add_data);
					if ($insert_id > 0) {
						#add Client Group
						$ArrSelectedClientGroup = array();
						if (isset($_POST['selected_clientgroup'])) {
							$ArrSelectedClientGroup = $_POST['selected_clientgroup'];
						}

						if (is_array($ArrSelectedClientGroup) && count($ArrSelectedClientGroup) > 0 && $_POST['apply_to'] == 'SG') {
							foreach ($ArrSelectedClientGroup as $clientgroup_id) {

								$promotional_code_client_group_data = array(
									'promotional_code_id' => $insert_id,
									'clientgroup_id' => $clientgroup_id,
									'created_by' => $_SESSION['admin_id'],
									'created_datetime' => date('Y-m-d')
								); //print_r($promotional_code_client_group_data);//exit;
								$this->promotional_code_model->addPromotionalCodeClientGroup($promotional_code_client_group_data);
							}
						}
						#End client group

						#add product
						$ArrSelectedProduct = array();
						if (isset($_POST['product_selected_product']) && is_array($_POST['product_selected_product'])) {
							$ArrSelectedProduct = $_POST['product_selected_product'];
							$Arrdiscount_value = $_POST['discount_value'];
							$Arrdiscount_type = $_POST['discount_type'];
						}

						if (is_array($ArrSelectedProduct) && count($ArrSelectedProduct) > 0 && $_POST['apply_to_product'] == 'SP') {
							$i = 0;
							foreach ($ArrSelectedProduct as $product_id) {
								if ($ArrSelectedProduct[$i] > 0) {
									$promotional_code_client_group_data = array(
										'promotional_code_id' => $insert_id,
										'product_id' => $ArrSelectedProduct[$i],
										'discount_value' => $Arrdiscount_value[$i],
										'discount_type' => $Arrdiscount_type[$i],
										'created_by' => $_SESSION['admin_id'],
										'created_datetime' => date('Y-m-d')
									);
									//print_r($promotional_code_client_group_data);//
									$this->promotional_code_model->addPromotionalCodeProduct($promotional_code_client_group_data);
								}
								$i++;
							} //exit;
						}
						#End product				

						$this->session->set_flashdata('success_message', 'Promotional Code details has been added successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('promotional-code');
				}
			} else {
				$this->load->view('admin_panel/admin_panel', $ArrData);
			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}

	public function get_user_email()
	{

		$email_id = $_POST['keyword'];
		$result = $this->user_model->search_customer_detail_by_email($email_id);
		//print_r($result);exit;
		$rawData = null;
		if (!empty($result)) {

			$rawData = "<ul id='country-list'>";

			foreach ($result as $country) {
				$user_email = trim($country["email"]);
				$user_id = trim($country["user_id"]);
				$rawData .= "<li onClick='selectCountry(\"$user_email\",\"$user_id\");'>" . $user_email . "</li>";
			}
			$rawData .= "</ul>";
		}

		if ($rawData) {
			echo $rawData;
		} else {
			$rawData = "<ul id='country-list'>";
			$rawData .= "<li onClick='selectCountry(\"No Match Found\",\"0\");'>No Match Found</li>";
			$rawData .= "</ul>";
			echo $rawData;
		}

	}
	public function check_user_email()
	{

		$email_id = $_POST['keyword'];
		$result = $this->user_model->getUserByEmail($email_id);
		if (!empty($result)) {
			echo "success";
		} else {
			echo "fail";
		}

	}

}