<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_user extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('userloginlog_model');
		$this->module_name = 'user';
	}
	#DASHBOARD
	public function index()
	{
		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		$column_order = array('null', 'tbl_users.user_id', 'tbl_users.user_name', 'tbl_users.display_name', 'tbl_users.address', 'tbl_users.city', 'tbl_users.state', 'tbl_users.phone', 'tbl_users.email', 'tbl_users.is_active');
		$column_search = array('tbl_users.user_id', 'tbl_users.user_name', 'tbl_users.display_name', 'tbl_users.address', 'tbl_users.city', 'tbl_users.state', 'tbl_users.phone', 'tbl_users.email', 'tbl_users.is_active');
		$aColumns = array('tbl_users.user_id', 'tbl_users.user_name', 'tbl_users.display_name', 'tbl_users.address', 'tbl_users.city', 'tbl_users.state', 'tbl_users.phone', 'tbl_users.email', 'tbl_users.is_active', 'tbl_users.created_datetime', 'tbl_users.user_role_id');
		$sTable = 'tbl_users';
		$i = 0;
		foreach ($column_search as $item) { /*loop column */
			if (@$_POST['search']['value'] || @$_POST['txtSearchKeyWord']) /*if datatable send POST for search*/ {
				if ($i === 0) {
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					if ($_POST['search']['value'] && $_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['search']['value']);
						$this->db->or_like($item, $_POST['txtSearchKeyWord']);
					} else if ($_POST['search']['value'] && !$_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['search']['value']);
					} else if (!$_POST['search']['value'] && $_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['txtSearchKeyWord']);
					}
				} else {
					if ($_POST['search']['value']) {
						$this->db->or_like($item, $_POST['search']['value']);
					}
					if ($_POST['txtSearchKeyWord']) {
						$this->db->or_like($item, $_POST['txtSearchKeyWord']);
					}
				}
				if (count($column_search) - 1 == $i) {
					$this->db->group_end(); /*close bracket*/
				}
			}
			$i++;
		}
		if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] != "") {
			$txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
			$txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
			$this->db->where('tbl_users.created_datetime >=', $txtSearchFrom . " 00:00:00");
			$this->db->where('tbl_users.created_datetime <=', $txtSearchTo . " 23:59:59");
		}
		if (@$_REQUEST['txtSearchFrom'] != "") {
			$txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
			$this->db->where('tbl_users.created_datetime >=', $txtSearchFrom . " 00:00:00");
		}
		if (@$_REQUEST['txtSearchTo'] != "") {
			$txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
			$this->db->where('tbl_users.created_datetime <=', $txtSearchTo . " 23:59:59");
		}
		if (@$_REQUEST['user_role_id'] != "") {
			$this->db->where('tbl_users.user_role_id', $_REQUEST['user_role_id']);
		}
		if (@$_REQUEST['ddIsActive'] != "") {
			$this->db->where('tbl_users.is_active', $_REQUEST['ddIsActive']);
		}
		// set columns start
		if (@$_REQUEST['columns'] != "") {
			if (@$_REQUEST['columns'] != "") {
				if (@$_REQUEST['length'] != -1) {
					$this->db->limit($_REQUEST['length'], $_REQUEST['start']);
				}
			}
			// Select Data
			$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);
			$order = array('tbl_users.user_id' => 'DESC');
			$this->db->where('tbl_users.user_role_id !=', '4');
			if (isset($_POST['order'])) { // here order processing
				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else if (isset($order)) {
				$order = $order;
				$this->db->order_by(key($order), $order[key($order)]);
			}
			$this->db->where('tbl_users.is_deleted', 0);
			$rResult = $this->db->get($sTable);
			//echo $this->db->last_query();exit;
			// Data set length after filtering
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			// Total data set length
			$iTotal = $this->db->count_all($sTable);
			// Output
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'aaData' => array());
			$i = $_REQUEST['start'] + 1;
			foreach ($rResult->result_array() as $aRow) {
				$row = array();
				$base_url = base_url();
				$user_id = $aRow['user_id'];
				/*  $actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="'.$user_id.'" />';
				 $actions .= '&nbsp;<a href="'.$base_url.'user-update/'.$user_id.'"><img src="'.ADMIN_PANEL_THEME_PATH.'dist/img/edit.png" height="20" width="20" title="Edit"></a>';
				 $actions .= '&nbsp;<a onclick = "javascript:Del_Mesg('.$user_id.')"><img src="'.ADMIN_PANEL_THEME_PATH.'dist/img/remove-row.png" height="20" width="20" title="Remove"></a>';
					 
				 $actions .= '<input type="hidden" name="user_'.$i.'" value="'.$user_id.'"> <input type="hidden" name="tot_user" value="'.$i.'">'; */
				$action = '';
				$action .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $user_id . '" /><div class="btn-group">';
				$action .= '<a rel="' . $base_url . 'adminpanel/controller_user/view_user_ajax/" id="' . $user_id . '" title="User Details" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$action .= '<a href="' . $base_url . 'user-update/' . $user_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$action .= '<a rel="' . $base_url . 'user-delete/' . $user_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';
				$row[] = $action;
				$row[] = $aRow['user_id'];
				$row[] = $aRow['user_name'];
				$row[] = $aRow['display_name'];
				$row[] = $aRow['address'];
				$row[] = $aRow['city'];
				$row[] = $aRow['state'];
				$row[] = $aRow['phone'];
				$row[] = $aRow['email'];
				$row[] = getIsactiveButtonForList($aRow['is_active'], $aRow['user_id'], 'tbl_users', 'user_id');
				//$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['user_id'],'user',array("V","E","D"))."</div>";					
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'User List';
		$ArrPageData['usertype'] = get_all_usertype_except();
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/user-script.js'),
		);
		$this->carabiner->js($js_assets);
		//$ArrPageData['button_url'] = $this->module_name.'-add';
		$ArrPageData['view_name'] = 'view_user_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}
	public function user_list_export()
	{
		$this->load->helper('csv_helper');
		$user_list_Arr = $this->user_model->get_userlist_export_data();
		$data = array();
		$no = 0;
		foreach ($user_list_Arr as $user_list) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $user_list['user_id'];
			$row[] = $user_list['user_name'];
			$row[] = ucfirst($user_list['name']);
			$row[] = $user_list['address'];
			$row[] = $user_list['city'];
			$row[] = $user_list['state'];
			$row[] = $user_list['phone'];
			$row[] = $user_list['email'];
			$data[] = $row;
		}
		$report_title = "qe_user_list_" . time();
		$ArrHeading = array('Sr No', 'user_id', 'site Name', 'Login Name', 'Name', 'Address', 'City', 'State', 'Phone', 'Email');
		array_to_csv($ArrHeading, $data, $report_title);
	}
	#ADD/EDIT User
	public function add($id = 0)
	{
		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		$ArrUserData = array();
		$ArrUserData['ArrFieldData'] = array();
		$ArrUserData['ArrUserTypeData'] = array();
		$ArrUserData['usertype'] = get_all_usertype();
		if ($id > 0) {
			$ArrUserData['ArrFieldData'] = $this->user_model->getUserByID($id);
			//echo "<pre>";print_r($ArrUserData['ArrFieldData']);exit;
			//$ArrUserData['ArrUserTypeData'] = $this->common_model->select("user_type,user_role_id","tbluser_type_master",$where = "1=1  And user_role_id != '1'",$asc="","");
			$ArrUserData['cms_title'] = 'Update User';
		} else {
			//$ArrUserData['ArrUserTypeData'] = $this->common_model->select("user_type,user_role_id","tbluser_type_master",$where = "1=1  And user_role_id != '1'",$asc="","");
			$ArrUserData['cms_title'] = 'Add User';
		}
		$ArrCountry = get_country();
		$ArrCountryOption = array();
		$ArrCountryOption['0'] = "--Country--";
		if (count($ArrCountry) > 0) {
			foreach ($ArrCountry as $row) {
				$ArrCountryOption[$row['id']] = $row['country_name'];
			}
		}
		$ArrUserData['ArrCountryOption'] = $ArrCountryOption;
		$ArrState = get_state('', 'US');
		$ArrStateOption = array();
		$ArrStateOption['0'] = "--State--";
		if (count($ArrState) > 0) {
			foreach ($ArrState as $row) {
				$ArrStateOption[$row['state_id']] = $row['state'];
			}
		}
		$ArrUserData['ArrStateOption'] = $ArrStateOption;
		$ArrUserData['button_label'] = 'View Users';
		$ArrUserData['view_name'] = 'view_user_addedit.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/user-script.js'),
		);
		//echo "<PRE>"; print_r($ArrUserData['ArrFieldData']); exit;
		//
		$this->carabiner->js($js_assets);
		$this->load->view('admin_panel/admin_panel', $ArrUserData);
	}
	#ADD/EDIT PAGE
	public function view_user_ajax()
	{
		$user_id = $_POST['id'];
		$ArrUserData['ArrFieldData'] = $this->user_model->getUserByID($user_id);
		//echo "<PRE>"; print_r($ArrUserData); exit;
		$this->load->view('admin_panel/view_user_details_using_ajax', $ArrUserData);
	}
	public function delete_ajax($id)
	{
		$result = $this->user_model->delete($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}
	}
	public function delete_multiple_user_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->user_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}
	}
	#ADD/EDIT PROCESS
	public function save()
	{
		if (!isset($_POST['save_user']) || $_POST['save_user'] == "") {
			redirect($this->module_name);
			exit;
		}
		if (isset($_POST) && $_POST['save_user'] != "" && $_POST['save_user'] == "Add") {
			$user_role_id = (trim($_POST['user_role_id'])) ? trim($_POST['user_role_id']) : null;
			$user_name = (trim($_POST['user_name'])) ? trim($_POST['user_name']) : null;
			$password = (trim($_POST['password'])) ? trim($_POST['password']) : null;
			$email = (trim($_POST['email'])) ? trim($_POST['email']) : null;
			$joining_date = (trim($_POST['joining_date'])) ? date('Y-m-d', strtotime(trim($_POST['joining_date']))) : null;
			$first_name = (trim($_POST['first_name'])) ? trim($_POST['first_name']) : null;
			$last_name = (trim($_POST['last_name'])) ? trim($_POST['last_name']) : null;
			$address1 = (trim($_POST['address'])) ? trim($_POST['address']) : null;
			$address2 = (trim($_POST['address2'])) ? trim($_POST['address2']) : null;
			$city = (trim($_POST['city'])) ? trim($_POST['city']) : null;
			$state = (trim($_POST['state'])) ? trim($_POST['state']) : null;
			$country_id = (trim($_POST['country_id'])) ? trim($_POST['country_id']) : null;
			$zip = (trim($_POST['zip'])) ? trim($_POST['zip']) : null;
			$phone = (trim($_POST['phone'])) ? trim($_POST['phone']) : null;
			$mobile_no = (trim($_POST['mobile_no'])) ? trim($_POST['mobile_no']) : null;
			$birth_date = (trim($_POST['birth_date'])) ? date('Y-m-d', strtotime(trim($_POST['birth_date']))) : null;
			$anniversary = (trim($_POST['anniversary'])) ? date('Y-m-d', strtotime(trim($_POST['anniversary']))) : null;
			$qualification = (trim($_POST['qualification'])) ? trim($_POST['qualification']) : null;
			$experience = (trim($_POST['experience'])) ? trim($_POST['experience']) : null;
			$skills = (trim($_POST['skills'])) ? trim($_POST['skills']) : null;
			$is_active = (trim($_POST['is_active'])) ? trim($_POST['is_active']) : null;
			$Arrdata = array(
				'user_role_id' => $user_role_id,
				'user_name' => $user_name,
				'password' => $password,
				'email' => strtolower($email),
				'created_datetime' => $joining_date,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'address' => $address1,
				'address2' => $address2,
				'city' => $city,
				'state' => $state,
				'country_id' => $country_id,
				'zip' => $zip,
				'phone' => $phone,
				'mobile_no' => $mobile_no,
				'birth_date' => $birth_date,
				'anniversary_date' => $anniversary,
				'qualification' => $qualification,
				'experience' => $experience,
				'skills' => $skills,
				'is_active' => $is_active,
				'created_datetime' => date('Y-m-d H:i:s'),
				'created_by' => get_current_admin_id(),
			);
			$user_id = $this->user_model->add($Arrdata);
			//echo $this->db->last_query();exit;
			if ($user_id > 0) {
				$this->session->set_flashdata('success_message', 'User details has been saved successfully');
				redirect('user-update/' . $user_id);
				exit;
			} else {
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to insert User details, please try again');
				redirect('user-add');
				exit;
			}
		}
		if (isset($_POST) && $_POST['save_user'] != "" && $_POST['save_user'] == "Update" && $_POST['user_id'] > 0) {
			$user_role_id = (trim($_POST['user_role_id'])) ? trim($_POST['user_role_id']) : null;
			$user_name = (trim($_POST['user_name'])) ? trim($_POST['user_name']) : null;
			$password = (trim($_POST['password'])) ? trim($_POST['password']) : null;
			$email = (trim($_POST['email'])) ? trim($_POST['email']) : null;
			$joining_date = (trim($_POST['joining_date'])) ? date('Y-m-d', strtotime(trim($_POST['joining_date']))) : null;
			$first_name = (trim($_POST['first_name'])) ? trim($_POST['first_name']) : null;
			$last_name = (trim($_POST['last_name'])) ? trim($_POST['last_name']) : null;
			$address1 = (trim($_POST['address'])) ? trim($_POST['address']) : null;
			$address2 = (trim($_POST['address2'])) ? trim($_POST['address2']) : null;
			$city = (trim($_POST['city'])) ? trim($_POST['city']) : null;
			$state = (trim($_POST['state'])) ? trim($_POST['state']) : null;
			$country_id = (trim($_POST['country_id'])) ? trim($_POST['country_id']) : null;
			$zip = (trim($_POST['zip'])) ? trim($_POST['zip']) : null;
			$phone = (trim($_POST['phone'])) ? trim($_POST['phone']) : null;
			$mobile_no = (trim($_POST['mobile_no'])) ? trim($_POST['mobile_no']) : null;
			$birth_date = (trim($_POST['birth_date'])) ? date('Y-m-d', strtotime(trim($_POST['birth_date']))) : null;
			$anniversary = (trim($_POST['anniversary'])) ? date('Y-m-d', strtotime(trim($_POST['anniversary']))) : null;
			$qualification = (trim($_POST['qualification'])) ? trim($_POST['qualification']) : null;
			$experience = (trim($_POST['experience'])) ? trim($_POST['experience']) : null;
			$skills = (trim($_POST['skills'])) ? trim($_POST['skills']) : null;
			$is_active = (trim($_POST['is_active'])) ? trim($_POST['is_active']) : null;
			$Arrdata = array(
				'user_role_id' => $user_role_id,
				'user_name' => $user_name,
				'email' => strtolower($email),
				'created_datetime' => $joining_date,
				'first_name' => $first_name,
				'last_name' => $last_name,
				'address' => $address1,
				'address2' => $address2,
				'city' => $city,
				'state' => $state,
				'country_id' => $country_id,
				'zip' => $zip,
				'phone' => $phone,
				'mobile_no' => $mobile_no,
				'birth_date' => $birth_date,
				'anniversary_date' => $anniversary,
				'qualification' => $qualification,
				'experience' => $experience,
				'skills' => $skills,
				'is_active' => $is_active,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
			);
			$result = $this->user_model->update($user_id, $Arrdata);
			if ($result) {
				$this->session->set_flashdata('success_message', 'User details has been updated successfully');
				//echo $this->session->flashdata('success_message');exit;
				redirect('user');
				exit;
			} else {
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update user details, please try again');
				redirect('user-update/' . $user_id);
				exit;
			}
		}
	}
	#ADD/EDIT PAGE
	public function ajaxShowTblUserMasterData()
	{
		$user_id = $_POST['user_id'];
		$ArrUserData['ArrFieldData'] = $this->user_model->getUserByID($user_id);
		$this->load->view('admin_panel/view_user_details_using_ajax', $ArrUserData);
	}
	/* Email DUBLICATE */
	public function ajaxCheckEmail()
	{
		$email_id = $_POST['email'];
		$user_id = $_POST['user_id'];
		$result = $this->user_model->CheckDuplicateEmailId($user_id, $email_id);
		if ($result > 0) {
			echo 'Yes';
		} else {
			echo 'No';
		}
	}
	public function ajaxCheckUsername()
	{
		$user_name = $_POST['user_name'];
		$user_id = $_POST['user_id'];
		$result = $this->user_model->CheckDuplicateuserName($user_id, $user_name);
		if ($result > 0) {
			echo 'Yes';
		} else {
			echo 'No';
		}
	}
	#LOGIN
	public function login()
	{
		if (IsUserLogin()) {
			redirect('dashboard');
			exit;
		}
		$ArrPageData = array();
		/* $js_assets = array(
				  array(ADMIN_PANEL_THEME_PATH.'dist/js/adminuser-script.js'),
			  );
			  $this->carabiner->js( $js_assets ); */
		$this->load->view('admin_panel/view_login', $ArrPageData);
	}
	public function profile()
	{
		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		$ArrPageData = array();
		$user_id = get_current_admin_id();
		$data['msg'] = '';
		$data['error'] = '';
		if ($this->input->post('submit')) {
			$user_data = array(
				'user_name' => $this->input->post('user_name'),
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'address' => $this->input->post('address1'),
				'address2' => $this->input->post('address2'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'country_id' => $this->input->post('country_id'),
				'zip' => $this->input->post('zip'),
				'phone' => $this->input->post('phone'),
				'mobile_no' => $this->input->post('mobile_no'),
				'email' => $this->input->post('email'),
				'modified_by' => $_SESSION['admin_id']
			);
			if ($this->input->post('birth_date') != '')
				$user_data['birth_date'] = $this->input->post('birth_date');
			if ($this->input->post('anniversary') != '')
				$user_data['anniversary_date'] = $this->input->post('anniversary');
			if ($this->user_model->update($this->session->userdata('admin_id'), $user_data) == TRUE || $email_sucess) {
				$ArrUserData = array(
					'user_name' => $this->input->post('user_name'),
					'display_name' => $this->input->post('name'),
				);
				$this->session->set_userdata($ArrUserData);
				$data['msg'] = "Your profile updated successfully.";
			} else {
				$data['error'] = "Your profile not updated please try again.";
			}
		}
		$ArrPageData['user'] = $this->user_model->getUserByID($this->session->userdata('admin_id'));
		$ArrPageData['country'] = country_list_data();
		$ArrPageData['cms_title'] = 'My Profile';
		$ArrPageData['view_name'] = 'view_profile.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/adminuser-script.js'),
		);
		$this->carabiner->js($js_assets);
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}
	public function change_password()
	{
		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		$ArrPageData = array();
		$ArrPageData['button_label'] = 'Update Profile';
		$ArrPageData['cms_title'] = 'My Profile';
		$ArrPageData['button_url'] = 'my-profile';
		$ArrPageData['view_name'] = 'view_change_password.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/adminuser-script.js'),
		);
		$this->carabiner->js($js_assets);
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}
	public function change_password_process()
	{
		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		$this->form_validation->set_rules('oldpassword', 'Old Password', 'required|trim|xss_clean');
		$this->form_validation->set_rules('newPassword', 'New Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE) {
			$ArrPageData = array();
			$ArrPageData['button_label'] = 'Update Profile';
			$ArrPageData['cms_title'] = 'My Profile';
			$ArrPageData['button_url'] = 'profile';
			$ArrPageData['view_name'] = 'view_change_password.php';
			$this->load->view('admin_panel/admin_panel', $ArrPageData);
		} else {
			$user_id = get_current_admin_id();
			$oldpassword = trim($_POST['oldpassword']);
			$newPassword = trim($_POST['newPassword']);
			$confirm_password = trim($_POST['confirm_password']);
			$ArrUser = $this->user_model->getUserByID($user_id);
			if (trim($ArrUser['password']) != md5($oldpassword)) {
				$this->session->set_flashdata('error_message', 'Old password not natch!');
				redirect('update-password');
				exit; //-
			}
			if ($newPassword != $confirm_password) {
				$this->session->set_flashdata('error_message', 'Password not match!');
				redirect('update-password');
				exit; //-
			}
			$current_data = date('Y-m-d H:i:s');
			$Arrdata = array(
				'password' => md5($newPassword),
				'modified_datetime' => $current_data,
				'modified_by' => $user_id
			);
			$result = $this->user_model->update($user_id, $Arrdata); // FORCE TO LOGOUT FROM ALL BROWSER
			if ($result) {
				$this->session->set_flashdata('success_message', 'Password updated successfully');
				redirect('update-password');
				exit; //-
			} else {
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update page details, please try again');
				redirect('update-password');
				exit; //-
			}
		}
	}
	public function logout()
	{
		$this->session->unset_userdata('login_view');
		$this->session->unset_userdata('admin_id');
		$this->session->unset_userdata('is_logged_in');
		$this->session->unset_userdata('user_role_id');
		$this->session->unset_userdata('user_role_name');
		$this->session->unset_userdata('user_name');
		$this->session->unset_userdata('created_datetime');
		$this->session->unset_userdata('display_name');
		$this->session->unset_userdata('password_changed');
		$this->session->unset_userdata('last_login_date');
		$this->session->unset_userdata('last_name');
		$this->session->unset_userdata('login_email');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', FALSE);
		header('Pragma: no-cache');
		redirect('login');
	}
	#FORGOT PASSWORD
	public function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
		if ($this->form_validation->run() == FALSE) {
			$ArrPageData = array();
			$js_assets = array(
				array(ADMIN_PANEL_THEME_PATH . 'dist/js/adminuser-script.js'),
			);
			$this->carabiner->js($js_assets);
			$this->load->view('admin_panel/view_forgot_password', $ArrPageData);
		} else {
			$email = $this->input->post('email');
			$ArrUser = $this->user_master_model->getUserDetailsByEmailId($email);
			if (!empty($ArrUser) && count($ArrUser) == 1) {
				$user_id = $ArrUser[0]['user_id'];
				$temp_pass = md5(uniqid());
				$ArrUpdateData = array('user_activation_key' => $temp_pass);
				$this->user_master_model->update($user_id, $ArrUpdateData);
				$this->session->set_flashdata('success_message', 'Confirmation link has been send to your email please check your E-mail');
				redirect('forgot-password');
				exit;
			} else {
				$this->session->set_flashdata('error_message', 'Email Id not found');
				redirect('forgot-password');
				exit;
			}
		}
	}
	#LOGIN PROCESS
	public function login_process()
	{
		$this->form_validation->set_rules('login_view', 'Username', 'trim|required|min_length[2]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[2]|max_length[32]');
		if ($this->form_validation->run() == FALSE) {
			$username = '';
			$password = '';
			$data['username'] = $username;
			$data['password'] = $password;
			$this->load->view('admin_panel/view_login', $data);
		} else {
			$username = $this->input->post('login_view');
			$pass = $this->input->post('password');
			$result = $this->user_model->admin_user_validate($username, $pass);

			if ($result) {
				$data = array(
					'login_view' => $this->input->post('login_view'),
					'admin_id' => $result[0],
					'is_logged_in' => true,
					'user_role_id' => $result[1],
					'user_role_name' => $result[2],
					'user_name' => $result[3],
					'created_datetime' => $result[4],
					'display_name' => $result[6],
					'last_login_date' => $result[8],
					'login_email' => $result[9],
				);
				$this->session->set_userdata($data);
				#remember_me start
				if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
					setcookie("username", $this->input->post('login_view'), time() + (60 * 60 * 1));
					setcookie("password", base64_encode($this->input->post('password')), time() + (60 * 60 * 1));
				} else {
					setcookie("username", $this->input->post('login_view'), time() - 1);
					setcookie("password", base64_encode($this->input->post('password')), time() - 1);
				}
				#remember_me end
				#Update online status
				$statusData = array('last_login_date' => date('Y-m-d H:i:s'));
				$this->user_model->update($_SESSION['admin_id'], $statusData);
				#End
				#UPDATE PRE LOG
				if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0) {
					$this->userloginlog_model->updateLogOutEntryLog($_SESSION['admin_id']);
				}
				#END
				#ADD LOGIN LOG
				$ArrLoginLogDetails = array('user_id' => $_SESSION['admin_id'], 'login_time' => date('Y-m-d H:i:s'));
				$userloginlog_id = $this->userloginlog_model->add($ArrLoginLogDetails);
				$data = array('userloginlog_id' => $userloginlog_id);
				$this->session->set_userdata($data);
				#END LOG
				redirect('home');
			} else {
				$data3['error'] = "Incorrect Username and Password or In-active User";
				$data3['username'] = '';
				$data3['password'] = '';
				$this->load->view('admin_panel/view_login', $data3);
			}
		}
	}
	public function update_user()
	{
		if (!isset($_POST['update_pass']) && !isset($_POST['update'])) {
			redirect('profile');
			exit;
		}
		if (isset($_POST['update']) && $_POST['update'] != "" && $_POST['update'] == "update") {
			$user_id = get_current_admin_id();
			$user_name = (trim($_POST['user_name'])) ? $_POST['user_name'] : '';
			$user_email = (trim($_POST['user_email'])) ? $_POST['user_email'] : '';
			$user_phone = (trim($_POST['user_phone'])) ? $_POST['user_phone'] : '';
			$user_address = (trim($_POST['user_address'])) ? $_POST['user_address'] : '';
			$admin_email_id = trim($_POST['user_email']);
			$ArrUser = $this->user_master_model->CheckDuplicateEmailId($user_id, $admin_email_id);
			if ($ArrUser) {
				$this->session->set_flashdata('error_message', 'Email id already exists');
				redirect('profile');
				exit;
			}
			$Arrdata = array(
				'first_name' => $user_name,
				'email' => $user_email,
				'phone' => $user_phone,
				'address' => $user_address,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => $user_id
			);
			$result = $this->user_master_model->update($user_id, $Arrdata);
			if ($result) {
				$this->session->set_flashdata('success_message', 'Profile details has been updated successfully');
				//echo $this->session->flashdata('success_message');exit;
				redirect('profile');
				exit;
			} else {
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update page details, please try again');
				redirect('profile');
				exit;
			}
		}
		/* UPDATE PASSWORD */
		if (isset($_POST['update_pass']) && $_POST['update_pass'] != "" && $_POST['update_pass'] == "update_password") {
			$this->form_validation->set_rules('oldpassword', 'Old Password', 'required|trim|xss_clean');
			$this->form_validation->set_rules('newPassword', 'New Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
			if ($this->form_validation->run() == FALSE) {
				$ArrPageData = array();
				$ArrPageData['button_label'] = 'Update Profile';
				$ArrPageData['cms_title'] = 'My Profile';
				$ArrPageData['button_url'] = 'profile';
				$ArrPageData['view_name'] = 'view_change_password.php';
				$this->load->view('admin_panel/admin_panel', $ArrPageData);
			} else {
				$user_id = get_current_admin_id();
				$oldpassword = trim($_POST['oldpassword']);
				$newPassword = trim($_POST['newPassword']);
				$confirm_password = trim($_POST['confirm_password']);
				$ArrUser = $this->user_model->getUserByID($user_id);
				if (trim($ArrUser[0]['password']) != $oldpassword) {
					$this->session->set_flashdata('error_message', 'Old password not natch!');
					redirect('change-password');
					exit;
				}
				if ($newPassword != $confirm_password) {
					$this->session->set_flashdata('error_message', 'Password not match!');
					redirect('change-password');
					exit;
				}
				$current_data = date('Y-m-d H:i:s');
				$Arrdata = array(
					'password' => $newPassword,
					'modified_datetime' => $current_data,
					'after_password_update_login_time' => $current_data,
					'last_login_time' => $current_data,
					'modified_by' => $user_id
				);
				$session_data = array(
					'user_id' => $this->session->userdata['logged_in']['user_id'],
					'email' => $this->session->userdata['logged_in']['email'],
					'first_name' => $this->session->userdata['logged_in']['first_name'],
					'last_name' => $this->session->userdata['logged_in']['last_name'],
					'user_role_id' => $this->session->userdata['logged_in']['user_role_id'],
					'Is_login' => true,
					'login_time' => $current_data,
				);
				// Add user data in session
				$this->session->set_userdata('logged_in', $session_data);
				$result = $this->user_master_model->update($user_id, $Arrdata); // FORCE TO LOGOUT FROM ALL BROWSER
				if ($result) {
					$this->session->set_flashdata('success_message', 'Password updated successfully');
					redirect('change-password');
					exit;
				} else {
					$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update page details, please try again');
					redirect('change-password');
					exit;
				}
			}
		}
	}
	public function search_customer()
	{
		$search_user_redirect_to = "";
		$search_string = "1=1";
		if ($_POST['search_user_redirect_to'] != '') {
			$search_user_redirect_to = $_POST['search_user_redirect_to'];
		}
		if ($_POST['search_customer_name'] != '') {
			$search_string .= " AND tbl_users.user_name LIKE '%" . $_POST['search_customer_name'] . "%'";
		}
		if ($_POST['search_customer_email'] != '') {
			$search_string .= " AND tbl_users.email LIKE '%" . $_POST['search_customer_email'] . "%'";
		}
		if ($_POST['search_customer_website'] != '') {
			$search_string .= " AND tbl_users.site_url LIKE '%" . $_POST['search_customer_website'] . "%'";
		}
		if ($_POST['search_company_name'] != '') {
			$search_string .= " AND tbl_users.company_name LIKE '%" . $_POST['search_company_name'] . "%'";
		}
		if (isset($_POST['search_is_active']) && $_POST['search_is_active'] > 0) {
			$search_string .= " AND tbl_users.is_active =" . $_POST['search_is_active'];
		}
		if (isset($_POST['id']) && $_POST['id'] > 0) {
			$search_string .= " AND tbl_users.id =" . $_POST['id'];
		}
		if ((isset($_POST['search_from_date']) && $_POST['search_from_date'] != '') && ($_POST['search_to_date'] == '')) {
			$fromdate = $_POST['search_from_date'];
			$fromdate = date('Y-m-d', strtotime($fromdate));
			$search_string .= " AND (tbl_users.created_datetime >= '" . $fromdate . "') ";
		}
		if ((!isset($_POST['search_to_date']) && $_POST['search_to_date'] == '') && ($_POST['search_from_date'] != '')) {
			$todate = $_POST['search_to_date'];
			$todate = date('Y-m-d', strtotime($todate));
			$search_string .= " AND (tbl_users.created_datetime <= '" . $todate . "') ";
		}
		if ((isset($_POST['search_from_date']) && $_POST['search_from_date'] != '') && (isset($_POST['search_to_date']) && $_POST['search_to_date'] != '')) {
			$fromdate = $_POST['search_from_date'];
			$todate = $_POST['search_to_date'];
			$fromdate = date('Y-m-d', strtotime($fromdate));
			$todate = date('Y-m-d', strtotime($todate));
			$search_string .= " AND (tbl_users.created_datetime >= '" . $fromdate . "' AND tbl_users.created_datetime <= '" . $todate . "') ";
		}
		$counter = 1;
		$ArrUser = $this->user_model->getUserUsingKeyWord($search_string);
		if (is_array($ArrUser) && count($ArrUser) > 0) {
			$count = 1;
			foreach ($ArrUser as $ArrUserDetails) {
				echo '<tr>';
				echo '<td>' . $counter++ . '</td>';
				echo '<td>';
				echo '<td>' . $ArrUserDetails['user_name'] . '<br />';
				echo $ArrUserDetails['email'] . ' </td>';
				echo '<td>' . $ArrUserDetails['format_joining_date'] . ' <br />';
				echo '<b>Is Member:</b>' . $ArrUserDetails['membership_status'] . '</td>';
				$actions = '';
				$actions .= '<a href="' . base_url() . 'admin/controller_lead/lead/' . $ArrUserDetails['user_id'] . '" target="_blank"><img src="' . admin_media() . 'dist/img/lead.png" height="18" width="18" title="View Lead"></a>';
				$actions .= '&nbsp;';
				$actions .= '<a href="javascript:void(0);" onClick="searchMockByCustomer(' . $ArrUserDetails['user_id'] . ')"><img src="' . admin_media() . 'dist/css/img/picture_icon.png" height="18" width="18" title="Mockup requested by a customer"></a>';
				$actions .= '&nbsp;';
				$actions .= '<a href="javascript:void(0);" onClick="searchOrderByCustomer(' . $ArrUserDetails['user_id'] . ')"><img src="' . admin_media() . 'dist/css/img/doller.png" height="18" width="18" title="View all order of this customer"></a>';
				echo '<td><a href="javascript:void(0);" onClick="selectCustomer(' . $ArrUserDetails['user_id'] . ');" class="btn btn-success">Select</a>';
				echo '<br />' . $actions . '</td>';
				echo '</tr>';
			}
		} else {
			echo "<tr><td colspan='5' align='center'>Contact(s) not found</td></tr>";
		} #END IF
	}
	public function getUserJson()
	{
		$term = (isset($_GET['term']) && $_GET['term'] != "") ? trim($_GET['term']) : '';
		if ($term != "") {
			$Arr_customer = $this->user_model->getUserJsonDropDown($term);
		} else {
			$Arr_customer = $this->user_model->getUserJsonDropDown();
		}
		$p = ['results' => $Arr_customer];
		echo json_encode($p);
	}
	public function getState($geo_id)
	{
		$ArrState = get_state($geo_id);
		//echo "<pre>";print_r($ArrState);exit;
		$ArrStateOption = array();
		$ArrStateOption['0'] = "--State--";
		if (count($ArrState) > 0) {
			foreach ($ArrState as $row) {
				$ArrStateOption[$row['state_id']] = $row['state'];
			}
		}
		echo form_dropdown('state', $ArrStateOption, 0, 'id="state" class="form-control"');
	}
	public function getCustomerJson()
	{
		$term = (isset($_GET['term']) && $_GET['term'] != "") ? trim($_GET['term']) : '';
		if ($term != "") {
			$ArrProduct = $this->user_model->getUserJsonDropDown($term);
		} else {
			$ArrProduct = $this->user_model->getUserJsonDropDown();
		}
		$p = ['results' => $ArrProduct];
		echo json_encode($p);
	}
} //:)
