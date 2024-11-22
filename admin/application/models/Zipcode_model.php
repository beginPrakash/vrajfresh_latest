<?php
class Zipcode_model extends CI_Model
{

	public function add($data)
	{

		$this->db->insert('tbl_zipcodes', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}


	public function update($zipcode_id, $arrData)
	{
		$this->db->where('zipcode_id', $zipcode_id);
		$update = $this->db->update('tbl_zipcodes', $arrData);
		return $this->db->affected_rows();
	}

	public function delete($zipcode_id)
	{
		$this->db->where('zipcode_id', $zipcode_id);
		//$this->db->update('tbl_zipcodes', array('is_deleted'=>1));
		$this->db->delete('tbl_zipcodes');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getZIPCode()
	{
		$ArrProductData = array();
		$this->db->select("*");
		$this->db->from('tbl_zipcodes');
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

}

?>