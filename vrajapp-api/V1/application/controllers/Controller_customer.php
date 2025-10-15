<?php

class Controller_customer extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('customer_model');
		$this->load->model('common_model');
		$this->load->model('category_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}

	}

	public function index()
	{
		$ArrData = $this->customer_model->getCustomerListData($_POST);

		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();
				$base_url = base_url();
				$user_id = $aRow['user_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $user_id . '" /><div class="btn-group">';
				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_customer/view_customer_ajax/" id="' . $user_id . '" title="Customer Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$actions .= '<a href="' . $base_url . 'customer-update/' . $user_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$actions .= '<a rel="' . $base_url . 'customer-delete/' . $user_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';


				$row[] = $actions;
				$row[] = $aRow['user_id'];
				$row[] = $aRow['display_name'];
				$row[] = $aRow['mobile_no'];
				$row[] = $aRow['address'];


				$row[] = getIsactiveButtonForList($aRow['is_active'], $user_id, 'tbl_users', 'user_id');
				$i++;
				$output['aaData'][] = $row;

			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Customer List';
		$ArrPageData['button_url'] = '';
		$ArrPageData['button_label'] = '';
		$ArrPageData['view_name'] = 'view_customer_list.php';
		$ArrPageData['category'] = $this->category_model->category_list_data();
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function customer_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->customer_model->ExportCustomerData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['name'];
			$row[] = $ArrData['mobile'];
			$row[] = $ArrData['address'];
			$row[] = $ArrData['category_name'];
			$row[] = $ArrData['Preference'];
			$row[] = $ArrData['site_name'];
			$row[] = ($ArrData['is_active'] == '1') ? 'Yes' : 'No';
			$data[] = $row;
		}
		$report_title = "qe_customer_list_" . time();
		$ArrHeading = array('Sr No', 'Customer Name', 'Mobile', 'Address', 'Category Name', 'Preference', 'site Name', 'Active');
		array_to_csv($ArrHeading, $data, $report_title);

	}

	public function view_customer_ajax()
	{
		$id = $this->input->post('id');
		$data['ArrFieldData'] = $this->customer_model->getCustomerUsingID($id);
		//echo "<pre>";print_r($data['ArrFieldData']);exit;
		$this->load->view('admin_panel/quickview/view_customer_details_popup', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->customer_model->delete($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}

	public function delete_multiple_customer_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->customer_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

	#ADD/EDIT
	public function add($user_id = 0)
	{

		$ArrData = array();
		$ArrData['category'] = $this->category_model->category_list_data();
		//$ArrData['ArrCustomers'] = $this->customer_model->customer_list_data();	
		if ($user_id > 0) {
			$ArrData['ArrFieldData'] = $this->customer_model->getCustomerUsingID($user_id);
			$ArrData['cms_title'] = "Update Customer";
			$ArrData['edit_id'] = $user_id;
		} else {
			$ArrData['cms_title'] = "Add Customer";
			$ArrData['edit_id'] = '';
		}
		$ArrCountry = get_country();
		$ArrCountryOption = array();
		$ArrCountryOption['0'] = "--Country--";
		if (count($ArrCountry) > 0) {
			foreach ($ArrCountry as $row) {
				$ArrCountryOption[$row['id']] = $row['country_name'];
			}
		}
		$ArrData['ArrCountryOption'] = $ArrCountryOption;


		$ArrState = get_state();
		$ArrStateOption = array();
		$ArrStateOption['0'] = "--State--";
		if (count($ArrState) > 0) {
			foreach ($ArrState as $row) {
				$ArrStateOption[$row['state_id']] = $row['state'];
			}
		}
		$ArrData['ArrStateOption'] = $ArrStateOption;

		$ArrData['button_url'] = base_url() . 'customers';
		$ArrData['button_label'] = 'View Customer';
		$ArrData['view_name'] = 'view_customer_addedit.php';

		$css_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/css/jquery.multiselect.css'),
		);
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/customer-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/jquery.multiselect.js'),
		);
		$this->carabiner->css($css_assets);
		$this->carabiner->js($js_assets);
		//echo "<pre>";print_r($_POST);exit;
		if (isset($_POST['save_user']) && ($_POST['save_user'] == "Add" || $_POST['save_user'] == "Update")) {
			/* validation Process Start */
			$this->load->library('form_validation');
			$this->form_validation->set_rules('first_name', 'First Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('mobile_no', 'Mobile', 'required');
			/* validation Process End */
			if ($this->form_validation->run()) {

				$customer_update_data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'display_name' => $this->input->post('display_name'),
					'email' => $this->input->post('email'),
					'address' => $this->input->post('address'),
					'address2' => $this->input->post('address2'),
					'country_id' => $this->input->post('country_id'),
					'state' => $this->input->post('state'),
					'city' => $this->input->post('city'),
					'zip' => $this->input->post('zip'),
					'phone' => $this->input->post('phone'),
					'mobile_no' => $this->input->post('mobile_no'),
					'is_active' => $this->input->post('is_active'),
					'modified_datetime' => date('Y-m-d H:i:s'),
					'modified_by' => get_current_admin_id(),
				);

				$update = $this->customer_model->update($this->input->post('user_id'), $customer_update_data);
				if ($update > 0) {
					$this->session->set_flashdata('success_message', 'Customer details has been updated successfully.');
				} else {
					$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
				}
				//echo $this->db->last_query();exit;
				redirect('customers');

			} else { // insert

				$customer_data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					'display_name' => $this->input->post('display_name'),
					'email' => $this->input->post('email'),
					'address' => $this->input->post('address'),
					'address2' => $this->input->post('address2'),
					'country_id' => $this->input->post('country_id'),
					'state' => $this->input->post('state'),
					'country_id' => $this->input->post('country_id'),
					'city' => $this->input->post('city'),
					'zip' => $this->input->post('zip'),
					'phone' => $this->input->post('phone'),
					'mobile_no' => $this->input->post('mobile_no'),
					'is_active' => $this->input->post('is_active'),
					'modified_datetime' => date('Y-m-d H:i:s'),
					'modified_by' => get_current_admin_id(),
				);
				$insert_id = $this->customer_model->add($customer_data);
				if ($insert_id > 0) {
					$this->session->set_flashdata('success_message', 'Customer details has been added successfully.');
				} else {
					$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
				}
				redirect('customer-update/' . $user_id);


			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}

}