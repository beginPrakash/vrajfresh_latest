<?php
class zipcodes_model extends CI_Model
{
    public function get_zipcodes($data)
    {
        $query = $this->db->select('z.zipcode_id,z.zipcode,z.state_id,z.minimum_order_value,z.can_deliver_perishable_products,z.delivery_types,z.is_active,s.state,u.user_name,z.created_by')
            ->join('tbl_states s', 'z.state_id=s.state_id')
            ->join('tbl_users u', 'z.created_by=u.user_id')
            ->where('z.is_active', $data['is_active'])
            ->where('z.is_deleted', '0')
            ->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_zipcodes z');

        return $query->result();
    }
    public function get_zipcodes_by_search($data, $search_keyword)
    {
        $query = $this->db->select('z.zipcode_id,z.zipcode,z.state_id,z.minimum_order_value,z.can_deliver_perishable_products,z.delivery_types,z.is_active,s.state,u.user_name,z.created_by')
            ->join('tbl_states s', 'z.state_id=s.state_id')
            ->join('tbl_users u', 'z.created_by=u.user_id')
            ->where('z.is_deleted', '0')
            ->where('z.is_active', $data['is_active']);

        foreach ($search_keyword as $key => $value) {
            $query = $this->db->like($key, $value);
        }
        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_zipcodes z');
        // echo $this->db->last_query();exit;
        return $query->result();

    }
    public function select_zipcodes_autocomplete($search_keyword)
    {
        $query = $this->db->select('z.zipcode_id,z.zipcode,z.state,z.area_name,z.town_name')
            ->where('z.is_deleted', '0')
            ->where('z.is_active', '1');

        $query = $this->db->like('zipcode',$search_keyword);
        $query = $this->db->or_like('area_name', $search_keyword);
        $query = $this->db->get('tbl_zipcodes z');
        return $query->result();

    }
    public function add_zipcode($data)
    {
        $this->db->insert('tbl_zipcodes', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_zipcode_by_id($data)
    {
        $query = $this->db->select('z.zipcode_id,z.zipcode,z.state_id,z.minimum_order_value,z.can_deliver_perishable_products,z.delivery_types,z.is_active,s.state,u.user_name,z.created_by')
            ->join('tbl_states s', 'z.state_id=s.state_id')
            ->join('tbl_users u', 'z.created_by=u.user_id')
            ->where('z.zipcode_id', $data['zipcode_id'])
            ->where('z.is_deleted', '0')
            ->get('tbl_zipcodes z');
        return $query->result();
    }
    public function update_zipcode($data, $state_id)
    {
        $this->db->where('is_deleted', '0')->where('zipcode_id', $state_id);
        $update = $this->db->update('tbl_zipcodes', $data);
        return $update;
    }
    public function get_zipcode_by_data($data)
    {
        $query = $this->db->select('z.zipcode_id,z.zipcode,z.minimum_order_value,z.can_deliver_perishable_products,z.can_deliver_liker_products,z.can_deliver_cook_food_products,z.delivery_types,z.delivery_days,z.is_active,z.area_name,z.state,s.state_id,s.tax as state_tax')
            ->where('z.zipcode', $data)
            ->join('state s', 's.state=z.state')
            ->where('z.is_deleted', '0')
            ->get('tbl_zipcodes z');

        return $query->result();
    }
}