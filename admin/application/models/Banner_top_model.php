<?php
class banner_top_model extends CI_Model
{

	public function update($id, $ArrUserData)
	{
		$this->db->where('banner_top_id', $id);
		$this->db->update('tbl_banner_top', $ArrUserData);
		return true;
	}

	public function getDataByID($id)
	{
		$this->db->select("*");
		$this->db->from('tbl_banner_top');
		$this->db->where('banner_top_id', $id);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return array();
		}
	}
	public function getUserUsingQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tbl_users');
		$this->db->where('is_deleted', 0);
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