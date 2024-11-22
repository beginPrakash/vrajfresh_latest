<?php
class userloginlog_model extends CI_Model
{

	public function updateLogOutEntryLog($user_id)
	{
		$sql = "UPDATE tbluserloginlog SET logout_time = page_refresh_time WHERE user_id = " . $user_id . " AND logout_time IS NULL";
		$query = $this->db->query($sql);
	}
	public function add($ArrUserLoginLogData)
	{

		$this->db->insert('tbluserloginlog', $ArrUserLoginLogData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($userloginlog_id, $ArrUserLoginLogData)
	{
		$this->db->where('userloginlog_id', $userloginlog_id);
		$update = $this->db->update('tbluserloginlog', $ArrUserLoginLogData);
		return $this->db->affected_rows();
	}
	public function delete($userloginlog_id)
	{
		$this->db->where('userloginlog_id', $userloginlog_id);
		$this->db->delete('tbluserloginlog');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getUserLoginLogUsingID($userloginlog_id)
	{
		$this->db->select("*");
		$this->db->from('tbluserloginlog');
		$this->db->where('userloginlog_id', $userloginlog_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}


	public function getUserLoginLogQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tbluserloginlog');
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