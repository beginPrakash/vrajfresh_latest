<?php
class user_model extends CI_Model
{

	#get customer list in promotional code	
	public function search_customer_detail_by_email($email_id)
	{
		$this->db->select('user_id,email');
		$this->db->from('tbl_delivery_users');
		$this->db->like('email', $email_id);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function add($ArrUserData)
	{

		$this->db->insert('tbl_delivery_users', $ArrUserData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($user_id, $ArrUserData)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('tbl_delivery_users', $ArrUserData);
		return true;
	}

	public function delete($user_id)
	{
		$this->db->where('user_id', $user_id);
		// $this->db->update('tbl_delivery_users', array('is_deleted'=>1));
		$this->db->delete('tbl_delivery_users');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}



	public function getUserByID($user_id)
	{
		$this->db->select("*");
		$this->db->from('tbl_delivery_users');
		$this->db->where('user_id', $user_id);
		$this->db->where('is_deleted', 0);

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
		$this->db->from('tbl_delivery_users');
		$this->db->where('is_deleted', 0);
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}
	public function is_email_exist($email)
	{
		$this->db->select("user_id");
		$this->db->from('tbl_delivery_users');
		$this->db->where('is_deleted', 0);
		$this->db->where('email', strtolower(trim($email)));
		$this->db->where('user_role_id', '4');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}




	public function getCustomerById($user_id, $select = "*")
	{
		$this->db->select($select);
		$this->db->from('tbl_delivery_users');
		$this->db->where('is_deleted', 0);
		$this->db->where('tbl_delivery_users.user_role_id', 4);
		$this->db->where('user_id', $user_id);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}



	#USER VALIDATE FOR ADMIN PANEL LOGIN
	public function admin_user_validate($username, $password)
	{

		$this->db->select('*');
		$this->db->from('tbl_delivery_users');
		$this->db->where('email', $username);
		$this->db->where('password', $password);
		$this->db->where('is_active', '1');
		$this->db->where('is_deleted', 0);
		$this->db->limit(1);

		$query = $this->db->get();
		//  echo $this->db->last_query();exit;
		$user = $query->row();
		$value = array();
		if ($query->num_rows() == 1) {
			$user = $query->row();
			$value[0] = $user->user_id;
			$value[1] = $user->first_name;
			$value[2] = $user->created_datetime;
			$value[3] = $user->last_name;
			$value[4] = $user->email;
			return $value;
		} else {
			return false;
		}
	}

	public function CheckDuplicateEmailId($user_id, $email_id)
	{
		$query = $this->db->query("SELECT * FROM `tbl_delivery_users` WHERE `email` = '" . $email_id . "' and `user_id` != '" . $user_id . "'");
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function CheckDuplicateuserName($user_id, $user_name)
	{
		$query = $this->db->query("SELECT * FROM `tbl_delivery_users` WHERE `user_name` = '" . $user_name . "' and `user_id` != '" . $user_id . "'");
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getUserJsonDropDown($term = '')
	{

		$this->db->select('user_id,email,first_name');
		$this->db->from('tbl_delivery_users');
		if ($term != "") {
			$this->db->like('email', $term);
			$this->db->or_like('first_name', $term);
		}
		$this->db->where('tbl_delivery_users.is_deleted', 0);
		$this->db->order_by('email', 'asc', false);
		$this->db->limit(30);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			//$array_data[] = array('id' => '', 'text'=> 'Select What');
			foreach ($query->result_array() as $aRow) {
				$array_data[] = array(
					'id' => $aRow['user_id'],
					'text' => $aRow['email'] . ' - ' . $aRow['first_name']
				);

			}
			return $array_data;
		} else {
			return array();
		}
	}
	public function getAllCustomer()
	{
		$arraywhr = "is_active='1' AND user_role_id=4";

		$sql = "SELECT `user_id`, CONCAT(UCASE(LEFT(user_name, 1)), LCASE(SUBSTRING(user_name, 2))) AS `user_name`, `email`,`first_name` FROM `tbl_delivery_users` WHERE " . $arraywhr . " ORDER BY `email`";

		$query = $this->db->query($sql);
		//echo '<pre>'; print_r( $this->db->last_query() );exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}


}
?>