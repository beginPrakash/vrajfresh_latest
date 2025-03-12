<?php



class Controller_credits extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('credits_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}



	public function cashcredits()

	{

		$ArrData = array();

		$ArrData['creditData'] = $this->credits_model->getCreditdetails();


		//echo "<pre>";print_r($ArrData['ArrProducts']);exit;

		$ArrData['cms_title'] = 'Cash Credit';

		$ArrData['view_name'] = 'view_cash_credits.php';

		$this->load->view('admin_panel/admin_panel', $ArrData);

	}

	public function save_cash_credits()

	{
		$credit_val = $_POST['credit_val'];

		if (!empty($credit_val)) {


				$Arrdata = array(

					'credit_per' => $credit_val,

					'modified_datetime' => date('Y-m-d H:i:s'),

				);

				$this->credits_model->add($Arrdata);


		}



		$this->session->set_flashdata('success_message', 'Cash Credit details has been saved successfully');

		redirect('cash-credits');

		exit;

	}



}