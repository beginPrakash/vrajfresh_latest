<?php

class Appbannerdata_model extends CI_Model

{



	public function add($data)

	{



		$this->db->insert('tbl_appbannerdata', $data);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return 0;

		}

	}





	public function update($banner_id, $arrData)

	{

		$this->db->where('banner_id', $banner_id);

		$update = $this->db->update('tbl_appbannerdata', $arrData);

		//echo $this->db->last_query();exit;

		return $this->db->affected_rows();

	}



	public function delete($banner_id)

	{

		$this->db->where('banner_id', $banner_id);

		// $this->db->update('tbl_appbannerdata', array('is_deleted'=>1));

		$this->db->delete('tbl_appbannerdata');

		if ($this->db->affected_rows() > 0) {

			return true;

		} else {

			return false;

		}

	}





	public function getbannerData()

	{

		$ArrProductData = array();

		$this->db->select('*');
		$this->db->from('tbl_appbannerdata');

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}

	}

	public function getbannerDataSingle()

	{

		$ArrProductData = array();

		$this->db->select('*');
		$this->db->from('tbl_appbannerdata');
        $query = $this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return array();
		}

	}


	public function getBannerCategory()

	{

		$this->db->select("*");

		$this->db->from('tbl_appbannerdata');


		$query = $this->db->get();



		if ($query->num_rows() > 0) {

			return $query->result_array();



		}

	}



}



?>