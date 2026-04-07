<?php
class Users_model extends CI_Model
{
    public function get_users($data)
    {
        $query = $this->db->select('u.user_name,u.user_id,u.email,u.zipcode,u.mobile_no,u.created_by,u.is_active')
            ->join('tbl_user_roles ur', 'u.user_role_id=ur.user_role_id')
            ->where('u.is_deleted', '0')
            ->where('u.is_active', $data['is_active'])
            ->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_users u');

        return $query->result();
    }
    public function get_users_by_search($data, $search_keyword)
    {
        $query = $this->db->select('u.user_name,u.user_id,u.email,u.zipcode,u.mobile_no,u.created_by,ur.user_role_name,u.is_active')
            ->join('tbl_user_roles ur', 'u.user_role_id=ur.user_role_id')
            ->where('u.is_deleted', '0')
            ->where('u.is_active', $data['is_active']);

        foreach ($search_keyword as $key => $value) {
            $query = $this->db->like($key, $value);
        }
        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_users u');
        // echo $this->db->last_query();exit;
        return $query->result();

    }
    public function add_user($data, $table)
    {
        $this->db->insert($table, $data);
        // echo $this->db->last_query();exit;
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_user_by_id($data)
    {
        $query = $this->db->select('u.user_id, u.user_name, u.first_name, u.last_name, u.display_name, u.zipcode, u.user_role_id, u.email, u.address, u.address2, u.city, u.mobile_no, u.created_by, u.card_num, u.card_exp_month, u.card_exp_year, u.card_cvc, u.is_active, ur.user_role_name')
            ->join('tbl_user_roles ur', 'u.user_role_id=ur.user_role_id')
            ->where('u.user_id', $data['user_id'])
            ->where('u.is_deleted', '0')
            ->get('tbl_users u');
            // echo $this->db->last_query();exit;
            
        return $query->result();
    }
    public function update_user($data, $user_id, $table)
    {
        $this->db->where('user_id', $user_id);
        $update = $this->db->update($table, $data);
        
        if ($update) {
            return true;
        } else {
            return false;
        }
    }
    public function update_user_by_email($data, $email_id, $table)
    {
        $this->db->where('is_deleted', '0')
            ->where('email', $email_id);
        $update = $this->db->update($table, $data);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }
    public function check_user($data)
    {

        $query = $this->db->select('user_id,user_name,first_name,last_name,display_name,zipcode,user_role_id,email,address,address2,city,mobile_no')
            ->where('email', $data['user_name'])
            ->where('user_role_id', $data['user_role_id'])
            ->or_where('mobile_no', $data['user_name'])
            ->where('is_active', '1')
            ->get('tbl_users');
        // echo $this->db->last_query();exit;
        return $query->result();
    }

    public function get_user($data)
    {
        $query = $this->db->select('user_id,user_name,first_name,last_name,display_name,zipcode,user_role_id,email,address,address2,city')
            ->where('user_id', $data['user_id'])
            ->where('is_active', '1')
            ->get('tbl_users');
        // echo $this->db->last_query();exit;
        return $query->result();
    }
    public function user_exist($data)
    {
        $query = $this->db->select('user_name,mobile_no,user_id')
            ->where('mobile_no', $data['mobile_no'])
            ->or_where('email', $data['email'])
            ->where('user_role_id', $data['user_role_id'])
            ->get('tbl_users');
        return $query->num_rows();
    }
    public function email_exist($data)
    {
        $query = $this->db->select('user_name,email,user_id,first_name,last_name,display_name,user_role_id')
            ->where('email', $data)
            ->get('tbl_users');
        return $query->result();
    }
    public function get_user_by_email($data)
    {
        $query = $this->db->select('first_name,last_name,email,user_id')
            ->where('email', $data)
            ->get('tbl_users');
        return $query->result();
    }
    public function get_user_address($data)
    {
        $query = $this->db->select('u.user_id,u.first_name,u.last_name,u.display_name,u.address,u.address2,u.city,u.state,u.zip,u.phone,c.country_name,u.display_name,u.shipping_street_address,u.shipping_apartment,u.shipping_city,u.shipping_state,u.shipping_zip_code,u.shipping_phone,bs.state as billing_state_name,ss.state as shipping_state_name')
            ->join('country c', 'u.country_id=c.id', 'left')
            ->join('state bs', 'u.state=bs.state_id', 'left')
            ->join('state ss', 'u.shipping_state=ss.state_id', 'left')
            ->where('u.user_id', $data['user_id'])
            //->where('u.user_role_id', $data['user_role_id'])
            ->get('tbl_users u');
        return $query->result();
    }
    public function update_user_by_guid($data, $guid)
    {
        $this->db->where('is_deleted', '0')
            ->where('guid', $guid);
        $update = $this->db->update('tbl_users', $data);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function check_email($data)
    {
        $query = $this->db->select('user_id,user_name,first_name,last_name,display_name,zipcode,user_role_id,email,address,address2,city')
            ->where('email', $data)
            ->or_where('mobile_no', $data)
            ->where('is_active', '1')
            ->get('tbl_users');
        // echo $this->db->last_query();exit;
        return $query->result();
    }


   public function delete_user_by_emailphone($data)
    {
        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");

        $this->db->group_start();
        $this->db->where('email', $data);
        $this->db->or_where('mobile_no', $data);
        $this->db->group_end();

        $this->db->delete('tbl_users');

        $deleted = $this->db->affected_rows();   // CHECK HERE

        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");

        if ($deleted > 0) {
            return true;   // data deleted
        } else {
            return false;  // no data deleted
        }
    }
    
}