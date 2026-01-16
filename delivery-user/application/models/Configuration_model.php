<?php
class Configuration_model extends CI_Model
{

	public function add($data)
	{

		$this->db->insert('tbl_configurations', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}


	public function update($configuration_id, $arrData)
	{
		$this->db->where('configuration_id', $configuration_id);
		$update = $this->db->update('tbl_configurations', $arrData);
		//echo $this->db->last_query();exit;
		return $this->db->affected_rows();
	}

	public function delete($configuration_id)
	{
		$this->db->where('configuration_id', $configuration_id);
		// $this->db->update('tbl_configurations', array('is_deleted'=>1));
		$this->db->delete('tbl_configurations');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getConfiguration()
	{
		$ArrProductData = array();
		$this->db->select("*");
		$this->db->from('tbl_configurations');
		$this->db->where("tbl_configurations.is_active", 0);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

}

?>