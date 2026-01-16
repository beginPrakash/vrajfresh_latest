<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class controller_dashboard extends CI_Controller {

	public function __construct() {
	parent::__construct();
		
		$this->module_name = 'dashboard';
		if(!IsUserLogin()){
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}
	}
	
	public function SaveSideBarClickEvent()
	{
		$side_bar_menu_status = 'close';
		if(isset($_SESSION['side_bar_menu_status']))
		{
			if($_SESSION['side_bar_menu_status']=='close')
				$side_bar_menu_status = 'open';
			else
				$side_bar_menu_status = 'close';
				
		}
		$data = array('side_bar_menu_status'=>$side_bar_menu_status);
		$this->session->set_userdata($data);
	}
	#DASHBOARD
	
	public function index()
	{
		$admin_id = $this->session->userdata('admin_id');
		$user_role_id = $_SESSION['user_role_id'];
		
		$data = array();
		
		$fromdate = date('Y-m-d',strtotime('first day of this month'));
		$todate = date('Y-m-d',strtotime('last day of this month'));
		
		$data['fromdate'] = $fromdate;
		$data['todate'] = $todate;
		
		if($user_role_id == 1)
		{
			$page = 'superadmin';
			$filter_time_period = 'current_month';
			$data_show_title = 'in this month';
			$fromdate = date('Y-m-d',strtotime('first day of this month'));
			$todate = date('Y-m-d',strtotime('last day of this month'));
		}

		if(!isset($user_role_id) || $user_role_id == '' || $user_role_id < 1){
			$page = 'authorized_user';
		}

		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/js/perfect-scrollbar.jquery.js'),
			array(ADMIN_PANEL_THEME_PATH.'dist/js/admin-dashboard.js'),
			array(ADMIN_PANEL_THEME_PATH.'dist/js/jquery.multiselect.js'),
		);
		$css_assets = array(
			array(ADMIN_PANEL_THEME_PATH.'dist/css/perfect-scrollbar.css'),
			array(ADMIN_PANEL_THEME_PATH.'dist/css/jquery.multiselect.css'),
		);
		
		$data['view_name'] = 'view_dashboard_'. $page .'.php';
		$data['cms_title'] = 'Dashboard';
		$this->carabiner->js( $js_assets );
		$this->carabiner->css( $css_assets );
		$this->load->view('admin_panel/admin_panel',$data);

	}
	


	public function random_color_part() {
		return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	public function random_color() {
		return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}

}
