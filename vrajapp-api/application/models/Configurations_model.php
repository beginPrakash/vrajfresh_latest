<?php

class Configurations_model extends CI_Model
{
    public function get_configurations($data)
    {
        $query = $this->db->select('configuration_id,configuration_type,configuration_key,configuration_value,is_active')
            ->where('is_active', $data['is_active'])
            ->where('is_deleted', '0')
            ->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_configurations');

        return $query->result();
    }
    public function get_configurations_by_search($data, $search_keyword)
    {
        $query = $this->db->select('configuration_id,configuration_type,configuration_key,configuration_value,is_active')
            ->where('is_deleted', '0')
            ->where('is_active', $data['is_active']);

        foreach ($search_keyword as $key => $value) {
            $query = $this->db->like($key, $value);
        }
        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_configurations');
        // echo $this->db->last_query();exit;
        return $query->result();

    }
    public function add_configuration($data)
    {
        $this->db->insert('tbl_configurations', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_configuration_by_id($data)
    {
        $query = $this->db->select('configuration_id,configuration_type,configuration_key,configuration_value,is_active')
            ->where('is_deleted', '0')
            ->where('configuration_id', $data['configuration_id'])
            ->get('tbl_configurations');
        return $query->result();
    }
    public function update_configuration($data, $configuration_id)
    {
        $this->db->where('is_deleted', '0')->where('configuration_id', $configuration_id);
        $update = $this->db->update('tbl_configurations', $data);
        return $update;
    }
    public function get_configuration_by_key($data)
    {
        $query = $this->db->select('configuration_id,configuration_type,configuration_value')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->where('configuration_key IN(' . $data["configuration_key"] . ')')
            ->get('tbl_configurations');
        //   echo $this->db->last_query();exit;
        return $query->result();
    }
}