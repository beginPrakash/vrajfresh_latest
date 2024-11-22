<?php
class promotional_code_client_group_model extends CI_Model
{

	public function add($ArrPromotionalCodeData)
	{

		$this->db->insert('tblpromotional_code_client_group', $ArrPromotionalCodeData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($promotional_code_client_group_id, $ArrPromotionalCodeData)
	{
		$this->db->where('promotional_code_client_group_id', $promotional_code_client_group_id);
		$update = $this->db->update('tblpromotional_code_client_group', $ArrPromotionalCodeData);
		return $this->db->affected_rows();
	}

	public function delete($promotional_code_client_group_id)
	{
		$this->db->where('promotional_code_client_group_id', $promotional_code_client_group_id);
		$this->db->delete('tblpromotional_code_client_group');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getPromotionalCodeClientGroupUsingID($promotional_code_client_group_id)
	{
		$this->db->select("*");
		$this->db->from('tblpromotional_code_client_group');
		$this->db->where('promotional_code_client_group_id', $promotional_code_client_group_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function getPromotionalCodeClientGroupQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tblpromotional_code_client_group');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}



}

?>