<?php
class Notification_model extends CI_Model
{
    public function get_notifications($data)
    {
        $limit = (int) $data['limit'];
        $page_no = (int) $data['page_no'];
        $offset = ($page_no - 1) * $limit;

        // We add ', COUNT(*) OVER() as total_records' to the select statement
        $query = $this->db->select('n.notification_id, n.user_id, n.title, n.body, n.custom_data, n.read_status, n.created_at, COUNT(*) OVER() as total_records')
            ->where('n.user_id', $data['user_id']) 
            ->order_by('n.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get('tbl_notification n');

        $records = $query->result_array();

        // If no records are found, total_records is 0
        $total_records = !empty($records) ? (int)$records[0]['total_records'] : 0;

        return array(
            'records' => $records,
            'total_records' => $total_records
        );
    }

    public function get_notification_by_id($notification_id)
    {
        $query = $this->db->select('n.notification_id, n.user_id, n.title, n.body, n.custom_data, n.read_status, n.created_at')
            ->where('n.notification_id', $notification_id)
            ->get('tbl_notification n');

        return $query->row_array();
    }

    public function mark_as_read($notification_id, $user_id)
    {
        $this->db->set('read_status', 1);
        $this->db->where('notification_id', $notification_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_notification');

        return ($this->db->affected_rows() > 0);
    }
}