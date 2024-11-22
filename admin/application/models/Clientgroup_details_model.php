<?php
class clientgroup_details_model extends CI_Model
{

	public function add($ArrClientGroupDetailData)
	{

		$this->db->insert('tblclientgroup_details', $ArrClientGroupDetailData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($clientgroup_details_id, $ArrClientGroupDetailData)
	{
		$this->db->where('clientgroup_details_id', $clientgroup_details_id);
		$update = $this->db->update('tblclientgroup_details', $ArrClientGroupDetailData);
		return $this->db->affected_rows();
	}
	public function delete($clientgroup_details_id)
	{
		$this->db->where('clientgroup_details_id', $clientgroup_details_id);
		$this->db->delete('tblclientgroup_details');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getClientGroupDetailUsingID($clientgroup_details_id)
	{
		$this->db->select("*");
		$this->db->from('tblclientgroup_details');
		$this->db->where('clientgroup_details_id', $clientgroup_details_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}


	public function getClientGroupDetailQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tblclientgroup_details');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function deleteGroupClient($clientgroup_id)
	{
		if ($clientgroup_id > 0) {
			$this->db->where('clientgroup_id', $clientgroup_id);
			$this->db->delete('tblclientgroup_details');
		}
	}

	public function addClientGroupDetails($data)
	{
		$insert = $this->db->insert('tblclientgroup_details', $data);
		return $insert;
	}

	public function getGroupClient($clientgroup_id)
	{
		$this->db->select("clientgroup_details_id,first_name,email,DATE_FORMAT(tbl_users.created_datetime, '%d-%b-%Y') as format_joining_date,tbl_users.user_id,tbl_users.first_name");
		$this->db->from('tblclientgroup_details');
		$this->db->join('tbl_users', 'tbl_users.user_id = tblclientgroup_details.user_id', 'JOIN');
		$this->db->where('clientgroup_id', $clientgroup_id);
		$query_result = $this->db->get();
		//echo '<pre>'; print_r( $this->db->last_query() );exit;		
		if ($query_result->num_rows() > 0) {
			$i = 0;
			foreach ($query_result->result_array() as $row) {
				foreach ($row as $key => $val) {
					$ArrTemp[$key] = $val;
				}
				$sdata[$i++] = $ArrTemp;
			}
			return $sdata;
		} else {
			return false;
		}
	}

	public function updateClientGroupIdInClientGroupDetails($old_clientgroup_id, $new_clientgroup_id)
	{
		if ($new_clientgroup_id > 0 && $new_clientgroup_id > 0) {
			$data = array();
			$data['clientgroup_id'] = $new_clientgroup_id;
			$this->db->where('clientgroup_id', $old_clientgroup_id);
			$update = $this->db->update('tblclientgroup_details', $data);
			$report = array();

			if ($report !== 0) {
				return true;
			}
		}
		return false;
	}

	function removeClientGroupCustomer($checked_ids)
	{
		if ($checked_ids) {
			for ($i = 0; $i < count($checked_ids); $i++) {
				if ($checked_ids[$i] > 0) {
					$this->db->where('clientgroup_details_id', $checked_ids[$i]);
					$this->db->delete('tblclientgroup_details');

					$status = TRUE;
				}
			}
			return $status;
		}
	}
}

?>