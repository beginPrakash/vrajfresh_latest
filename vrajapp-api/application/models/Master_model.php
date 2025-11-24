<?php

class Master_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function check_exist_email($email_address, $login_id)
    {

        $this->db->select('*');
        $this->db->where('email_address', $email_address);

        if ($login_id > 0) {
            $this->db->where('login_id != ', $login_id);
        }

        $this->db->where('is_active', 1);
        $query = $this->db->get('login');
        $num = $query->num_rows();
        if ($num > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function user_detail_by_email($email_id)
    {
        $this->db->select('login.email_address,login.password,users.*');
        $this->db->from("login");
        $this->db->join("users", 'login.login_id = users.login_id', "INNER");
        $this->db->where('login.email_address', $email_id);
        $query = $this->db->get();
        $row = $query->row_array();

        if (!empty($row)) {
            return $row;
        } else {
            return array();
        }
    }

    public function update_login_detail($data, $login_id)
    {
        $this->db->where("login_id", $login_id);
        if ($this->db->update('login', $data)) {
            return TRUE;
        }
        return FALSE;
    }
    
    public function check_exist_email1($tbl, $cond, $login_id)
    {
        $this->db->select('*');
        $this->db->where($cond);

        if ($login_id > 0) {
            $this->db->where('login_id != ', $login_id);
        }

        $query = $this->db->get($tbl);
        /*echo $this->db->last_query();
        exit();*/
        $num = $query->num_rows();
        

        if ($num > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

	public function check_customer_email($email_address, $login_id)
    {

        $this->db->select('*');
        $this->db->where('email_address', $email_address);

        if ($login_id > 0) {
            $this->db->where('login_id != ', $login_id);
        }

        $this->db->where('is_active', 1);
        $query = $this->db->get('customer');
        $num = $query->num_rows();
        if ($num > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function update_detail($tbl, $data, $cond)
    {
        $this->db->where($cond);
        if ($this->db->update($tbl, $data)) {
            return TRUE;
        }
        return FALSE;
    }

    public function insertData($tbl, $data)
    {
        if ($this->db->insert($tbl, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }
	
	public function user_details($login_id)
    {

        $this->db->select('*');
        $this->db->from("users");
        $this->db->where('login_id', $login_id);
        $query = $this->db->get();
        $row = $query->row_array();

        if (!empty($row)) {
            return $row;
        } else {
            return array();
        }
    }

    public function get_list_of_data($tbl, $cond = array(), $fld = "", $betweenCond = "")
    {

        $this->db->select('*');
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);
        }
		if($betweenCond != "") {
            $this->db->where($betweenCond);
        }
        $this->db->where('is_active', 1);
        if ($fld) {
            $this->db->order_by($fld, "DESC");
        }
        $query = $this->db->get();
        // echo $this->db->last_query();
        // exit();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }
	
	public function get_list_of_data_order_by($tbl, $cond = array(), $fld = "")
    {

        $this->db->select('*');
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        $this->db->where('is_active', 1);
        if ($fld) {
            $this->db->order_by($fld, "ASE");
        }
        $query = $this->db->get();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_of_data_grp($tbl, $cond = array(), $fld = "")
    {
        $this->db->select('*');
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);
        }

        if ($fld) {
            $this->db->group_by($fld);
            $this->db->order_by($fld, "DESC");
        }
        $query = $this->db->get();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_order_details($tbl1, $tbl2, $fld, $cond = array())
    {

        $this->db->select("$tbl1.*,$tbl2.product_name");
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1 . '.' . $fld . ' = ' . $tbl2 . '.' . $fld);
        if (!empty($cond)) {
            $this->db->where($cond);
        }

        $query = $this->db->get();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_of_data_join($tbl1, $tbl2, $fld1, $cond = array(), $fld2 = "", $betweenCond = "")
    {

        $this->db->select('*');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1 . '.' . $fld1 . ' = ' . $tbl2 . '.' . $fld1, 'INNER');
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        if($betweenCond != "") {
            $this->db->where($betweenCond);
        }
        if ($fld2) {
            $this->db->order_by($fld2, "DESC");
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        //exit();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_of_data_join_asc($tbl1, $tbl2, $fld1, $cond = array(), $fld2 = "", $betweenCond = "")
    {

        $this->db->select('*');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1 . '.' . $fld1 . ' = ' . $tbl2 . '.' . $fld1, 'INNER');
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        if($betweenCond != "") {
            $this->db->where($betweenCond);
        }
        if ($fld2) {
            $this->db->order_by($fld2, "ASC");
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        //exit();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_row_detail($tbl, $cond)
    {

        $this->db->select('*');
        $this->db->from($tbl);
        $this->db->where($cond);
        $query = $this->db->get();
        $result = $query->row_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_transaction_detail($tbl, $cond, $order_by)
    {

        $this->db->select('*');
        $this->db->from($tbl);
        $this->db->where($cond);
        $this->db->order_by($order_by, 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_row_detail_join($tbl1, $tbl2, $fld1, $cond)
    {
        $this->db->select('*');
        $this->db->from($tbl1);
        $this->db->join($tbl2, $tbl1 . '.' . $fld1 . ' = ' . $tbl2 . '.' . $fld1, 'LEFT');
        $this->db->where($cond);
        $this->db->where("$tbl1.is_active", 1);
        $query = $this->db->get();
        $result = $query->row_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }
    
    public function get_concat_row($tbl, $cond)
    {
        $query = $this->db->query('SELECT GROUP_CONCAT(product_name) as product_name FROM map_' . $tbl . ' WHERE ' . $cond);
        $result = $query->row_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_list_of_data_order($tbl, $cond = array())
    {

        $this->db->select('*');
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);

        }
        $this->db->where('is_active', 1);
        $this->db->order_by('order_id', "DESC");
        $query = $this->db->get();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

	public function get_data_with_pagination_two($tbl1, $tbl2, $common, $limit = null, $start = null, $cond = array(), $fld = "", $filter = "")
    {
        $this->db->select("$tbl1.*,$tbl2.*");
        $this->db->from($tbl1);
        $this->db->join($tbl2, "$tbl1.$common = $tbl2.$common", "INNER");
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        
		if($filter != ""){
			
			$this->db->where("(tbl_login.email_address LIKE '%".$filter."%' OR tbl_login.mobile_number LIKE '%".$filter."%' OR tbl_users.username LIKE '%".$filter."%')", NULL, FALSE);
        
		}
		
        $this->db->limit($limit, $start);
        
        if ($fld != "") {
            $this->db->order_by($fld);
        }

        $query = $this->db->get();
        $results = $query->result();

        if (!empty($results)) {
            return $results;
        } else {
            return array();
        }
    }
	
    public function get_data_with_pagination_two_tbl($tbl1, $tbl2, $common, $limit = null, $start = null, $cond = array(), $fld = "")
    {
        $this->db->select("$tbl1.*,$tbl2.name as title");
        $this->db->from($tbl1);
        $this->db->join($tbl2, "$tbl1.$common = $tbl2.$common", "INNER");
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        $this->db->where("$tbl1.is_active", 1);
        $this->db->limit($limit, $start);
        //$this->db->group_by($fld);
        if ($fld != "") {
            $this->db->order_by($fld);
        }

        $query = $this->db->get();
        $results = $query->result();

        if (!empty($results)) {
            return $results;
        } else {
            return array();
        }
    }

    public function getUserData($tbl1, $tbl2, $limit = null, $start = null, $cond = array(), $fld = "")
    {
        $this->db->select("$tbl2.*,$tbl2.*");
        $this->db->from($tbl1);
        $this->db->join($tbl2, "$tbl1.login_id = $tbl2.login_id", "INNER");
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        $this->db->where("$tbl1.is_active", 1);
        $this->db->limit($limit, $start);

        if ($fld != "") {
            $this->db->order_by($fld, "DESC");
        }

        $query = $this->db->get();
        $results = $query->result();

        if (!empty($results)) {
            return $results;
        } else {
            return array();
        }
    }

    public function get_data_with_pagination($tbl, $limit = null, $start = null, $cond = array(), $fld = "", $betweenCond = "")
    {
        $this->db->select('*');
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);
        }
		if($betweenCond != "") {
            $this->db->where($betweenCond);
        }
        $this->db->where("is_active", 1);
        $this->db->limit($limit, $start);
        if ($fld != "") {
            $this->db->order_by($fld, "DESC");
        }
        $query = $this->db->get();
		//echo $this->db->last_query();
		//exit();
        $results = $query->result();

        if (!empty($results)) {
            return $results;
        } else {
            return array();
        }
    }

    public function get_total_of_data($tbl, $cond = array(), $betweenCond = "")
    {
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);
        }
		if($betweenCond != "") {
            $this->db->where($betweenCond);
        }
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }

    }
	
	public function get_total_of_data_join($tbl, $tbl1, $cond = array(), $fld = '', $filter = "")
    {
		$this->db->select('*');
        $this->db->from($tbl);
        $this->db->join($tbl1,"$tbl.$fld = $tbl1.$fld", "INNER");
        $this->db->where($cond);
		if($filter != ""){
			
			$this->db->where("(tbl_login.email_address LIKE '%".$filter."%' OR tbl_login.mobile_number LIKE '%".$filter."%' OR tbl_users.username LIKE '%".$filter."%')", NULL, FALSE);
        
		}
        $query = $this->db->get();

        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }

    }

    public function get_total_of_data_distinct($tbl, $cond = array())
    {
        $this->db->select('DISTINCT(order_id)');
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }

    }

    public function get_sum_of_result($tbl, $fld = "", $cond = array())
    {
        $this->db->select("SUM($fld) as totalAmount");
        $this->db->from($tbl);
        if (!empty($cond)) {
            $this->db->where($cond);
        }
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $query->row_array();
        } else {
            return 0;
        }

    }

    public function deleteRow($tbl, $cond)
    {
        $this->db->where($cond);
        if ($this->db->delete($tbl)) {
            return TRUE;
        }
        return FALSE;
    }

    public function list_of_users($type='')
    {
        $this->db->select('*');
        $this->db->from("registrations");
        $this->db->join('login','login.login_id = registrations.login_id');
        $this->db->where('login.is_active', 1);
        $this->db->where('login.login_type', 'user');
        if($type != "") {
            $this->db->limit(8);
        }
        $this->db->order_by('login.login_id','DESC');
        $query = $this->db->get();
        $result = $query->result();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function check_user_token_expired($user_token='')
    {

        $result = $this->db->get_where('tbl_users_token', ['access_token' => $user_token])->row();

        if (!$result || strtotime($result->access_expiry) < time()) {
            return false;
        }

        return $result->user_id;
    }
}