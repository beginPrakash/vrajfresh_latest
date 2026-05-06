<?php
class Usernotification_model extends CI_Model
{
    public function add_usernotification($data)
    {
        $this->db->insert('tbl_user_notifications', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
        
    }
    
     public function get_notification_by_user_id_count($data)

    {

        $query = $this->db->select('uw.user_id')
        ->from('tbl_user_notifications uw')
            ->where('uw.user_id', $data)
            ->order_by('uw.notification_id', 'asc')
            ->get();

        return $query->result();


    }

    public function get_notification_by_user_id($data,$limit, $offset)

    {
        $query = $this->db->from('tbl_user_notifications uw')
            ->select('uw.notification_id,uw.user_id,uw.message,uw.is_read')
            ->where('uw.user_id', $data)
            ->order_by('uw.notification_id ', 'desc')
            ->limit($limit, $offset)
            ->get();

        return $query->result();


    }

}