<?php

class Controller_state_wise_tax extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('state_wise_tax');
		$this->load->model('zipcode_model');
		$this->load->model('common_model');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}

	}

	public function zipcode()
	{
		$ArrData = array();

		$data = "US";
		// $ArrData['Arrzipcode'] = $this->zipcode_model->getZIPCode();
		$ArrData['ArrState'] = $this->state_wise_tax->getState($data);

		// $ArrState  = get_state('','US');
		$ArrState = $ArrData['ArrState'];
		$ArrStateOption = array();
		$ArrStateOption['0'] = "--State--";
		if (count($ArrState) > 0) {
			foreach ($ArrState as $row) {
				$ArrStateOption[$row['state_id']] = $row['state'];
			}
		}
		$ArrData['ArrStateOption'] = $ArrStateOption;

		$ArrData['cms_title'] = 'State Wise Tax';
		$ArrData['view_name'] = 'view_state_wise_tax.php';
		$this->load->view('admin_panel/admin_panel', $ArrData);

	}
	public function save_state()
	{
		// echo "<pre>";print_r($_POST);
		$state_id = $_POST['state_id'];
		$ArrStateData = $_POST['ArrStateData'];
		// $ArrCanDeliverPerishable = $_POST['ArrCanDeliverPerishable'];
		// $ArrState = $_POST['ArrState'];
		// $ArrDeliveryTypes = $_POST['ArrDeliveryTypes'];
		// $ArrDeliveryDays = $_POST['ArrDeliveryDays'];
		if ($state_id > 0) {
			$arrData = array(
				// 'state' => $ArrStateData[0],
				'tax' => $ArrStateData[0],

			);
			$this->state_wise_tax->update($state_id, $arrData);
		}
	}

	public function delete_zipcode()
	{
		$zipcode_id = $_POST['zipcode_id'];
		// $this->zipcode_model->delete($zipcode_id);
	}


}