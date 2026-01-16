<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controller_delivery_user extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('delivery_user_model');
		$this->load->model('userloginlog_model');
		$this->module_name = 'deliveryuser';
	}
	#DASHBOARD
	public function index()
	{
		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
		$column_order = array('null', 'tbl_delivery_users.user_id', 'tbl_delivery_users.phone', 'tbl_delivery_users.email', 'tbl_delivery_users.is_active');
		$column_search = array('tbl_delivery_users.user_id', 'tbl_delivery_users.phone', 'tbl_delivery_users.email', 'tbl_delivery_users.is_active','tbl_delivery_users.first_name', 'tbl_delivery_users.last_name');
		$aColumns = array('tbl_delivery_users.user_id', 'tbl_delivery_users.first_name', 'tbl_delivery_users.city', 'tbl_delivery_users.zipcode', 'tbl_delivery_users.last_name', 'tbl_delivery_users.phone', 'tbl_delivery_users.email', 'tbl_delivery_users.is_active', 'tbl_delivery_users.created_datetime');
		$sTable = 'tbl_delivery_users';
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
		if (@$_REQUEST['ddIsActive'] != "") {
			$this->db->where('tbl_delivery_users.is_active', $_REQUEST['ddIsActive']);
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
			$order = array('tbl_delivery_users.user_id' => 'DESC');
			if (isset($_POST['order'])) { // here order processing
				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else if (isset($order)) {
				$order = $order;
				$this->db->order_by(key($order), $order[key($order)]);
			}
			$this->db->where('tbl_delivery_users.is_deleted', 0);
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
				$action = '';
				$action .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $user_id . '" /><div class="btn-group">';
				$action .= '<a href="' . $base_url . 'deliveryuser-view/' . $user_id . '" id="' . $user_id . '" title="User Details" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$action .= '<a href="' . $base_url . 'deliveryuser-update/' . $user_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$action .= '<a rel="' . $base_url . 'deliveryuser-delete/' . $user_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';
				$row[] = $action;
				$row[] = $aRow['user_id'];
				$row[] = $aRow['first_name'].' '.$aRow['last_name'];
                $row[] = $aRow['email'];
				$row[] = $aRow['phone'];
				$row[] = $aRow['city'];
				$row[] = $aRow['zipcode'];
				$row[] = getIsactiveButtonForList($aRow['is_active'], $aRow['user_id'], 'tbl_delivery_users', 'user_id');
				//$row[] = '<div class="btn-group">'.getActionButtonForList($aRow['user_id'],'user',array("V","E","D"))."</div>";					
				$i++;
				$output['aaData'][] = $row;
			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Delivery User List';
		$ArrPageData['usertype'] = get_all_usertype_except();
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/deliveryuser-script.js'),
		);
		$this->carabiner->js($js_assets);
		//$ArrPageData['button_url'] = $this->module_name.'-add';
		$ArrPageData['view_name'] = 'view_delivery_user_list.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}
	public function user_list_export()
	{
		$this->load->helper('csv_helper');
		$user_list_Arr = $this->delivery_user_model->get_userlist_export_data();
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
			$ArrUserData['ArrFieldData'] = $this->delivery_user_model->getUserByID($id);
			//echo "<pre>";print_r($ArrUserData['ArrFieldData']);exit;
			//$ArrUserData['ArrUserTypeData'] = $this->common_model->select("user_type,user_role_id","tbluser_type_master",$where = "1=1  And user_role_id != '1'",$asc="","");
			$ArrUserData['cms_title'] = 'Update Delivery User';
		} else {
			//$ArrUserData['ArrUserTypeData'] = $this->common_model->select("user_type,user_role_id","tbluser_type_master",$where = "1=1  And user_role_id != '1'",$asc="","");
			$ArrUserData['cms_title'] = 'Add Delivery User';
		}

		$ArrUserData['button_label'] = 'View Delivery Users';
		$ArrUserData['view_name'] = 'view_deliveryuser_addedit.php';
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/deliveryuser-script.js'),
		);
		//echo "<PRE>"; print_r($ArrUserData['ArrFieldData']); exit;
		//
		$this->carabiner->js($js_assets);
		$this->load->view('admin_panel/admin_panel', $ArrUserData);
	}
	#ADD/EDIT PAGE
	public function view_deliveryuser_ajax($user_id)
	{
		$ArrUserData['ArrFieldData'] = $this->delivery_user_model->getUserByID($user_id);
		$ArrUserData['ArrOrderData'] = $this->delivery_user_model->getAssignedorderByID($user_id);
		$ArrUserData['view_name'] = 'view_deliveryuser_details_using_ajax.php';
		
		$this->load->view('admin_panel/admin_panel', $ArrUserData);
	}
	public function delete_ajax($id)
	{
		$result = $this->delivery_user_model->delete($id);
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
			$result += $this->delivery_user_model->delete($primary_id);
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
			$first_name = (trim($_POST['first_name'])) ? trim($_POST['first_name']) : null;
            $last_name = (trim($_POST['last_name'])) ? trim($_POST['last_name']) : null;
			$password = (trim($_POST['password'])) ? trim($_POST['password']) : null;
			$email = (trim($_POST['email'])) ? trim($_POST['email']) : null;
			$city = (trim($_POST['city'])) ? trim($_POST['city']) : null;
			$zipcode = (trim($_POST['zipcode'])) ? trim($_POST['zipcode']) : null;
			$phone = (trim($_POST['phone'])) ? trim($_POST['phone']) : null;
			$is_active = (trim($_POST['is_active'])) ? trim($_POST['is_active']) : null;
			$Arrdata = array(
                'first_name' => $first_name,
				'last_name' => $last_name,
                'email' => strtolower($email),
				'password' => $password,
				'city' => $city,
				'zipcode' => $zipcode,
				'phone' => $phone,
				'is_active' => $is_active,
				'created_datetime' => date('Y-m-d H:i:s'),
				'created_by' => get_current_admin_id(),
			);
			$user_id = $this->delivery_user_model->add($Arrdata);
			//echo $this->db->last_query();exit;
			if ($user_id > 0) {
				$this->session->set_flashdata('success_message', 'Delivery User details has been saved successfully');
				redirect('deliveryuser');
				exit;
			} else {
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to insert Delivery User details, please try again');
				redirect('deliveryuser-add');
				exit;
			}
		}
		if (isset($_POST) && $_POST['save_user'] != "" && $_POST['save_user'] == "Update" && $_POST['user_id'] > 0) {
            $user_id = $_POST['user_id'];
            $first_name = (trim($_POST['first_name'])) ? trim($_POST['first_name']) : null;
			$last_name = (trim($_POST['last_name'])) ? trim($_POST['last_name']) : null;
            $email = (trim($_POST['email'])) ? trim($_POST['email']) : null;
			$city = (trim($_POST['city'])) ? trim($_POST['city']) : null;
			$zipcode = (trim($_POST['zipcode'])) ? trim($_POST['zipcode']) : null;
			$phone = (trim($_POST['phone'])) ? trim($_POST['phone']) : null;
			$is_active = (trim($_POST['is_active'])) ? trim($_POST['is_active']) : null;
			$Arrdata = array(
				'first_name' => $first_name,
				'last_name' => $last_name,
				'email' => strtolower($email),
				'phone' => $phone,
				'city' => $city,
				'zipcode' => $zipcode,
				'is_active' => $is_active,
				'modified_datetime' => date('Y-m-d H:i:s'),
				'modified_by' => get_current_admin_id(),
			);

			$result = $this->delivery_user_model->update($user_id, $Arrdata);
			if ($result) {
				$this->session->set_flashdata('success_message', 'Delivery User details has been updated successfully');
				//echo $this->session->flashdata('success_message');exit;
				redirect('deliveryuser');
				exit;
			} else {
				$this->session->set_flashdata('error_message', 'Oops...! something went wrong to update delivery user details, please try again');
				redirect('deliveryuser-update/' . $user_id);
				exit;
			}
		}
	}
	#ADD/EDIT PAGE
	public function ajaxShowTblUserMasterData()
	{
		$user_id = $_POST['user_id'];
		$ArrUserData['ArrFieldData'] = $this->delivery_user_model->getUserByID($user_id);
		$this->load->view('admin_panel/view_user_details_using_ajax', $ArrUserData);
	}
	/* Email DUBLICATE */
	public function ajaxCheckEmail()
	{
		$email_id = $_POST['email'];
		$user_id = $_POST['user_id'];
		$result = $this->delivery_user_model->CheckDuplicateEmailId($user_id, $email_id);
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
		$result = $this->delivery_user_model->CheckDuplicateuserName($user_id, $user_name);
		if ($result > 0) {
			echo 'Yes';
		} else {
			echo 'No';
		}
	}


} //:)
