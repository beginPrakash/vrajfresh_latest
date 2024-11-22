<?php



class Controller_banner_top extends CI_Controller

{



	public function __construct()

	{

		parent::__construct();

		$this->load->model('banner_top_model');

		$this->load->model('common_model');



		if (!IsUserLogin()) {

			$authorized_error = "You are not authorized to view this page....!";

			$this->session->set_flashdata('authorized_error', $authorized_error);

			redirect('login');

		}



	}

	public function top_banner_data()
	{
		$data['msg'] = '';
		$data['error'] = '';
		if ($this->input->post('submit')) {
			$user_data = array(
				'title' => $this->input->post('title'),
				'url' => $this->input->post('url'),
				'f_categories_title' => $this->input->post('f_categories_title'),
				'stockup_title' => $this->input->post('stockup_title'),
				'refill_title' => $this->input->post('refill_title'),
				'is_active' => $this->input->post('is_active'),
				'modified_by' => $_SESSION['admin_id']
			);
			if ($this->banner_top_model->update(1, $user_data) == TRUE ) {
				$data['msg'] = "Data updated successfully.";
			} else {
				$data['error'] = "Data not updated please try again.";
			}
		}
		$ArrPageData['user'] = $this->banner_top_model->getDataByID(1);
		$ArrPageData['cms_title'] = 'Top Data';
		$ArrPageData['view_name'] = 'view_banner_top_data.php';
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}



}