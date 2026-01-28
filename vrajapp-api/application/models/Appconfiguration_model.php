<?php

class Appconfiguration_model extends CI_Model

{

	public function getConfiguration()

	{

		$ArrProductData = array();

		$this->db->select("*");

		$this->db->from('tbl_appconfigurations');

		$this->db->where("tbl_appconfigurations.is_active", 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}



}



?>