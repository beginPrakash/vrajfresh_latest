<?php



defined('BASEPATH') or exit('No direct script access allowed');



class controller_dashboard extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		

		

		

		

		$this->module_name = 'dashboard';

		$this->load->model('order_model');

		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}

	}



	public function menu()

	{

		

		

		$query = $this->db->select('mi.*,pmi.menu_title as parent_menu_title,pmi.menu_link as parent_menu_link')

            ->join('tbl_menu_item pmi', 'mi.parent_menu_item_id=pmi.menu_item_id', 'left')

            ->where('mi.menu_id', 1)

            ->where('mi.is_active', '1')

            ->get('tbl_menu_item mi');

        $temp_result =  $query->result();

		

		$ArrFinal = array();

				$ArrFinal = array();

				$i = 0;

				$prev_menu_id = 0;

				foreach ($temp_result as $arr) {

					if ($arr->parent_menu_item_id > 0) {

						if ($prev_menu_id != $arr->parent_menu_item_id) {



							$tempArray = array();



							foreach ($temp_result as $arr1) {



								if ($arr->parent_menu_item_id == $arr1->parent_menu_item_id) {

									$t = array();

									if ($arr1->menu_item_id > 0) {

										$t['child_menu_title'] = $arr1->menu_title;

										$t['child_menu_link'] = $arr1->menu_link;

										$t['child_menu_id'] = $arr1->menu_item_id;

									}

									$tempArray[] = $t;

								}

							}



							$ArrFinal[$i] = $arr;



							$ArrFinal[$i]->child_menus = $tempArray;



							$i++;

						}



						$prev_menu_id = $arr->parent_menu_item_id;

					} else {

						$ArrFinal[] = $arr;

					}

				}

				

		echo "<pre>";print_r($ArrFinal);exit;

	}

	public function sms()

	{

		$to = "9428706170";

		

		$SMSbody = "Your Vraj Fresh order 5445435 has been placed. It will be delivered soon. We will send you an update when your order is shipped. Thank you for shopping.";

		sendSMS($to, $SMSbody);

		exit;

		//$to = "+12012708377";

		$body = "Your Vraj Fresh order 7777 has been placed. It will be delivered soon. We will send you an update when your order is shipped. Thank you for shopping.";

		

		if($to!='')

		{

			$id = "AC7d6f6f9b2c442e6e50c7929ba3cd05dd";

			$token = "197a07dc872735f1d26ed9f72b832b60";

			$from = '+14178052045';

			

			$COUNTRY = "IND";

			if($COUNTRY=="US")

			{

				$pre_fix = "1"; //US: 1 IND: 91

				$pre_fix_char_count = "1"; //US: 1 IND: 2

			}

			else

			{

				$pre_fix = "91"; //US: 1 IND: 91

				$pre_fix_char_count = "2"; //US: 1 IND: 2

			}

			//CHECK AND ADD COUNTRY CODE

			if(substr($to,0,1)!="+")

			{

				if(substr($to,0,$pre_fix_char_count)!=$pre_fix)

				{

					$to = "+".$pre_fix.$to;

				}

				else

				{

					$to = "+".$to;

				}

			}

			$url = "https://api.twilio.com/2010-04-01/Accounts/$id/Messages.json";

			$data = array(

				'From' => $from,

				'To' => $to,

				'Body' => $body,

			);

			$post = http_build_query($data);

			$x = curl_init($url);

			curl_setopt($x, CURLOPT_POST, true);

			curl_setopt($x, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

			curl_setopt($x, CURLOPT_USERPWD, "$id:$token");

			curl_setopt($x, CURLOPT_POSTFIELDS, $post);

			$y = curl_exec($x);

			curl_close($x);

			var_dump($post);

			var_dump($y);

		}

		$response = json_decode($y);



		echo "<pre>";print_r($y);

	}

	public function SaveSideBarClickEvent()

	{

		$side_bar_menu_status = 'close';

		if (isset($_SESSION['side_bar_menu_status'])) {

			if ($_SESSION['side_bar_menu_status'] == 'close')

				$side_bar_menu_status = 'open';

			else

				$side_bar_menu_status = 'close';



		}

		$data = array('side_bar_menu_status' => $side_bar_menu_status);

		$this->session->set_userdata($data);

	}

	#DASHBOARD



	public function index()

	{

		$admin_id = $this->session->userdata('admin_id');

		$user_role_id = $_SESSION['user_role_id'];



		$data = array();



		$fromdate = date('Y-m-d', strtotime('first day of this month'));

		$todate = date('Y-m-d', strtotime('last day of this month'));



		$data['fromdate'] = $fromdate;

		$data['todate'] = $todate;



		if ($user_role_id == 1) {

			$page = 'superadmin';

			$filter_time_period = 'current_month';

			$data_show_title = 'in this month';

			$fromdate = date('Y-m-d', strtotime('first day of this month'));

			$todate = date('Y-m-d', strtotime('last day of this month'));



			$ArrOrderCounts = $this->order_model->getMonthlyOrderNumbers();

			//echo "<pre>";print_r($ArrOrderCounts);exit;

			$data['ArrOrderCounts'] = $ArrOrderCounts;

		}



		if ($user_role_id == 2) {

			$page = 'superadmin';

			$filter_time_period = 'current_month';

			$data_show_title = 'in this month';

			$fromdate = date('Y-m-d', strtotime('first day of this month'));

			$todate = date('Y-m-d', strtotime('last day of this month'));



			$ArrOrderCounts = $this->order_model->getMonthlyOrderNumbers();

			//echo "<pre>";print_r($ArrOrderCounts);exit;

			$data['ArrOrderCounts'] = $ArrOrderCounts;

		}







		if (!isset($user_role_id) || $user_role_id == '' || $user_role_id < 1) {

			$page = 'authorized_user';

		}



		$js_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/perfect-scrollbar.jquery.js'),

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/admin-dashboard.js'),

			array(ADMIN_PANEL_THEME_PATH . 'dist/js/jquery.multiselect.js'),

		);

		$css_assets = array(

			array(ADMIN_PANEL_THEME_PATH . 'dist/css/perfect-scrollbar.css'),

			array(ADMIN_PANEL_THEME_PATH . 'dist/css/jquery.multiselect.css'),

		);



		$data['view_name'] = 'view_dashboard_' . $page . '.php';

		$data['cms_title'] = 'Dashboard';

		$this->carabiner->js($js_assets);

		$this->carabiner->css($css_assets);

		$this->load->view('admin_panel/admin_panel', $data);



	}







	public function random_color_part()

	{

		return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);

	}



	public function random_color()

	{

		return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();

	}



}