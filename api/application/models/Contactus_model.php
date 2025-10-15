<?php
class Contactus_model extends CI_Model
{

    public function add_contact($data, $table)
    {
        $this->db->insert($table, $data);
        // echo $this->db->last_query();exit;
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
}