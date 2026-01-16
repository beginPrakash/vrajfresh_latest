<?php

class states_model extends CI_Model

{

    public function get_states($data)

    {

        $query = $this->db->select('s.*')

            ->where('s.is_active', $data['is_active'])

            ->where('s.is_deleted', '0')

            ->order_by($data['sort_column'], $data['sort_order'])

            ->limit($data['limit'], $data['page_no'])

            ->get('tbl_states s');



        return $query->result();

    }

    public function get_states_by_search($data, $search_keyword)

    {

        $query = $this->db->select('s.*')

            ->where('s.is_deleted', '0')

            ->where('s.is_active', $data['is_active']);



        foreach ($search_keyword as $key => $value) {

            $query = $this->db->like('s.' . $key, $value);

        }

        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])

            ->limit($data['limit'], $data['page_no'])

            ->get('tbl_states s');

        // echo $this->db->last_query();exit;

        return $query->result();



    }

    public function add_state($data)

    {

        $this->db->insert('tbl_states', $data);

        if ($this->db->affected_rows() > 0) {

            return $this->db->insert_id();

        } else {

            return 0;

        }

    }

    public function get_state_by_id($data)

    {

        $query = $this->db->select('s.*')

            ->where('s.state_id', $data['state_id'])

            ->get('state s');

        return $query->result();

    }

    public function update_state($data, $state_id)

    {

        $this->db->where('is_deleted', '0')->where('state_id', $state_id);

        $update = $this->db->update('tbl_state s', $data);

        return $update;

    }

    public function get_state_by_country_id($data)

    {

        $query = $this->db->select('s.*')

            ->where('s.geo_id', $data)

            ->get('state s');

        return $query->result();

    }

}