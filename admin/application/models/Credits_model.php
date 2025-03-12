<?php

class Credits_model extends CI_Model

{



	public function add($data)

	{ 

		$this->db->insert('tbl_cash_credit', $data);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return 0;

		}

	}





	public function update($credit_id, $arrData)

	{

		$this->db->where('credit_id', $credit_id);

		$update = $this->db->update('tbl_cash_credit', $arrData);

		//echo $this->db->last_query();exit;

		return $this->db->affected_rows();

	}



	public function delete($credit_id)

	{

		$this->db->where('credit_id', $credit_id);

		// $this->db->update('tbl_cash_credit', array('is_deleted'=>1));

		$this->db->delete('tbl_cash_credit');

		if ($this->db->affected_rows() > 0) {

			return true;

		} else {

			return false;

		}

	}





	public function getCreditdetails()

	{

		$this->db->select("*");
		$this->db->from('tbl_cash_credit')->limit(1);
		$this->db->order_by('credit_id',"desc");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return array();

		}

	}



}



?>