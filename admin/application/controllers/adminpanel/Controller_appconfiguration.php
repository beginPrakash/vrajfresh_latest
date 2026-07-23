<?php



class Controller_appconfiguration extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('appconfiguration_model');

		$this->load->model('special_products_model');

		$this->load->model('product_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}



	public function website()

	{

		$ArrData = array();

		$ArrData['ArrConfiguration'] = $this->appconfiguration_model->getConfiguration();

		$ArrData['ArrSpecialProducts'] = $this->special_products_model->getSpecialProducts();


		$ArrData['cms_title'] = 'Application Configuration';

		$ArrData['view_name'] = 'view_app_configuration.php';

		$this->load->view('admin_panel/admin_panel', $ArrData);

	}

	public function save_website_configuration()

	{

		$ArrData = $_POST['ArrData'];
		
		if (count($ArrData) > 0) {

			foreach ($ArrData as $configuration_id => $configuration_value) {

				$Arrdata = array(

					'configuration_value' => $configuration_value,

					'modified_datetime' => date('Y-m-d H:i:s'),

					'modified_by' => get_current_admin_id(),

				);

				$this->appconfiguration_model->update($configuration_id, $Arrdata);

			}

		}


		$this->session->set_flashdata('success_message', 'Website configuration details has been saved successfully');

		redirect('app-configuration');

		exit;

	}



}