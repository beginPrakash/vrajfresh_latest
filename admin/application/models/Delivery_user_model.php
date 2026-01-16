<?php
class delivery_user_model extends CI_Model
{

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
		$this->db->select("tbl_delivery_users.*");
		$this->db->from('tbl_delivery_users');
		$this->db->where('tbl_delivery_users.user_id', $user_id);
		$this->db->where('tbl_delivery_users.is_deleted', 0);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return array();
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

		$this->db->select('tbl_user_roles.user_role_id,tbl_delivery_users.user_id,tbl_delivery_users.email,tbl_delivery_users.user_name,tbl_delivery_users.password,tbl_delivery_users.user_role_id,tbl_user_roles.user_role_name,tbl_delivery_users.created_datetime,tbl_delivery_users.first_name,tbl_delivery_users.last_name,tbl_delivery_users.display_name,tbl_delivery_users.last_login_date');
		$this->db->from('tbl_delivery_users');
		$this->db->join('tbl_user_roles', 'tbl_user_roles.user_role_id = tbl_delivery_users.user_role_id', 'JOIN');
		$this->db->where('tbl_delivery_users.user_name', $username);
		$this->db->where('tbl_delivery_users.password', $password);
		$this->db->where_not_in('tbl_user_roles.user_role_id', '4');
		$this->db->where('tbl_delivery_users.is_active', '1');
		$this->db->where('tbl_delivery_users.is_deleted', 0);
		$this->db->limit(1);

		$query = $this->db->get();
		//  echo $this->db->last_query();exit;
		$user = $query->row();
		$value = array();
		if ($query->num_rows() == 1) {
			$user = $query->row();
			$value[0] = $user->user_id;
			$value[1] = $user->user_role_id;
			$value[2] = $user->user_role_name;
			$value[3] = $user->user_name;
			$value[4] = $user->created_datetime;
			$value[6] = $user->first_name;
			$value[8] = $user->last_login_date;
			$value[9] = $user->email;
			return $value;
		} else {
			return false;
		}
	}

	public function CheckDuplicateEmailId($user_id, $email_id)
	{
		$query = $this->db->query("SELECT * FROM `tbl_delivery_users` WHERE `email` = '" . $email_id . "' and `user_id` != '" . $user_id . "'");
		echo $query->num_rows();exit;
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


	public function getAssignedorderByID($user_id, $parm = "*")

	{

		$this->db->select('tbl_orders.' . $parm );

		$this->db->from('tbl_orders');

		$this->db->where('delivery_user_id', $user_id);

		$query = $this->db->get();

		// echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return false;

		}

	}


}
?>