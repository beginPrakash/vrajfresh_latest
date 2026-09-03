<?php



class Controller_zipcode extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('zipcode_model');

		$this->load->model('zone_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}

	public function zipcode()

	{
		$SearchData= [];
		$ArrData = array();
		$ArrData['txtSearchZipcode'] = "";
		$ArrData['txtSearchState'] = "";
		$ArrData['txtSearchArea'] = "";
		
		
		if($_POST){
			if(isset($_POST['txtSearchZipcode']) && $_POST['txtSearchZipcode'] != ""){
				$SearchData['txtSearchZipcode'] = $_POST['txtSearchZipcode'];
				$ArrData['txtSearchZipcode']  = $_POST['txtSearchZipcode'];
			}
			if(isset($_POST['txtSearchState']) && $_POST['txtSearchState'] != ""){
				$SearchData['txtSearchState'] = $_POST['txtSearchState'];
				$ArrData['txtSearchState']  = $_POST['txtSearchState'];
			}
			if(isset($_POST['txtSearchArea']) && $_POST['txtSearchArea'] != ""){
				$SearchData['txtSearchArea'] = $_POST['txtSearchArea'];
				$ArrData['txtSearchArea']  = $_POST['txtSearchArea'];
			}
		}

		$ArrData['Arrzipcode'] = $this->zipcode_model->getZIPCode($SearchData);

		$ArrState = get_state('', 'US');
		$ArrStateOption = array();
		$ArrStateOption['0'] = "--State--";
		if (count($ArrState) > 0) {
			foreach ($ArrState as $row) {
				$ArrStateOption[$row['state_id']] = $row['state'];
			}
		}

		$ArrData['ArrStateOption'] = $ArrStateOption;
		//get zone list 
		$ArrZonelist = $this->zone_model->zone_list();
		$ArrData['ArrZonelist'] = $ArrZonelist;

		$ArrData['cms_title'] = 'ZIP Code Configuration';
		$ArrData['view_name'] = 'view_zipcode_configuration.php';
		$this->load->view('admin_panel/admin_panel', $ArrData);

	}

	public function save_zipcode()

	{

		

		$zipcode_id = $_POST['zipcode_id'];

		$ArrZipCodeData = $_POST['ArrZipCodeData'];

		$ArrCanDeliverPerishable = $_POST['ArrCanDeliverPerishable'] ?? '';

		$ArrCanDeliverLiker = $_POST['ArrCanDeliverLiker'] ?? '';

		$ArrCanDeliverCookFood = $_POST['ArrCanDeliverCookFood'] ?? '';

		$ArrState = $_POST['ArrState'];

		$ArrDeliveryTypes = $_POST['ArrDeliveryTypes'];

		$ArrDeliveryDays = $_POST['ArrDeliveryDays'] ?? '';

		$ArrDeliveryCutoff = $_POST['ArrDeliveryCutoff'];

		$ArrZone = $_POST['ArrZone'];

		if ($zipcode_id > 0) {

			$arrData = array(

				'zipcode' => $ArrZipCodeData[0],

				'area_name' => $ArrZipCodeData[1],

				'town_name' => $ArrZipCodeData[2],

				'minimum_order_value' => $ArrZipCodeData[3],

				'can_deliver_perishable_products' => $ArrCanDeliverPerishable[0] ?? '',

				'can_deliver_liker_products' => $ArrCanDeliverLiker[0] ?? '',

				'can_deliver_cook_food_products' => $ArrCanDeliverCookFood[0] ?? '',

				'state' => $ArrState[0],

				'delivery_types' => $ArrDeliveryTypes[0],

				'cutoff_time' => $ArrDeliveryCutoff[0],

				'delivery_days' => $ArrDeliveryDays[0] ?? '',

				'modified_by' => $_SESSION['admin_id'],

				'modified_datetime' => date('Y-m-d'),

			);

			$this->zipcode_model->update($zipcode_id, $arrData);

		} else {

			$arrData = array(

				'zipcode' => $ArrZipCodeData[0],

				'area_name' => $ArrZipCodeData[1],

				'town_name' => $ArrZipCodeData[2],

				'minimum_order_value' => $ArrZipCodeData[3],

				'can_deliver_perishable_products' => $ArrCanDeliverPerishable[0] ?? '',

				'can_deliver_liker_products' => $ArrCanDeliverLiker[0] ?? '',

				'can_deliver_cook_food_products' => $ArrCanDeliverCookFood[0] ?? '',

				'state' => $ArrState[0],

				'delivery_types' => $ArrDeliveryTypes[0],

				'cutoff_time' => $ArrDeliveryCutoff[0],

				'delivery_days' => $ArrDeliveryDays[0] ?? '',

				'created_by' => $_SESSION['admin_id'],

				'created_datetime' => date('Y-m-d'),

			);

			$this->zipcode_model->add($arrData);

		}





	}

	public function delete_zipcode()

	{

		$zipcode_id = $_POST['zipcode_id'];

		$this->zipcode_model->delete($zipcode_id);

	}


	public function save_holiday_date()

	{	

		$zipcode_id = $_POST['zipcode_id'];
		$start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : NULL;
		$end_date   = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;


		if ($zipcode_id > 0) {

			$arrData = array(

				'holiday_start_date' => $start_date,
				'holiday_end_date' => $end_date,

			);

			$this->zipcode_model->update($zipcode_id, $arrData);

		}
		echo json_encode([
			'status'  => true,
			'message' => 'Date range saved successfully.'
		]);

	}


}