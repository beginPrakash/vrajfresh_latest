<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_common extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('common_model');
		$this->load->model('user_model');
		$this->load->model('newsletter_subscriber_model');
	}

	public function not_found()
	{
		$this->load->view('/not_found');
	}


	public function ajaxStatusUpdate()
	{
		$primary_id = $_POST['cms_id'];
		$tablename = $_POST['tablename'];
		$column_name = $_POST['column_name'];

		$result = $this->common_model->updateIsActive($primary_id, $column_name, $tablename);
		if ($result['responce']) {
			echo json_encode(array('message' => 'success', 'is_active' => $result['status']));
		} else {
			echo json_encode(array('message' => 'fail'));
		}

	}

	public function ajaxStatusUpdateAnyColumn()
	{
		$primary_id = $_POST['cms_id'];
		$tablename = $_POST['tablename'];
		$column_name = $_POST['column_name'];
		$col_to_update = $_POST['col_to_update'];

		$result = $this->common_model->updateAnyColumn($primary_id, $column_name, $tablename, $col_to_update);
		if ($result['responce']) {
			echo json_encode(array('message' => 'success', 'status' => $result['status']));
		} else {
			echo json_encode(array('message' => 'fail'));
		}

	}

	public function delete_multiple_record()
	{
		$primary_id = $_POST['primary_id'];
		$tablename = $_POST['table_name'];
		$column_name = $_POST['column_name'];
		$primary_idArr = explode(',', $primary_id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->common_model->delete_multiple_records($primary_id, $column_name, $tablename);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

}