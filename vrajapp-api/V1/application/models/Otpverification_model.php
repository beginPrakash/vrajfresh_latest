<?php
class Otpverification_model extends CI_Model
{
    public function add_otpcode($data)
    {
        $this->db->insert('tbl_otp_verification', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_cms_by_id($data)
    {
        $query = $this->db->select('cms_id,cms_url,cms_title,cms_description,created_by,modified_by,created_datetime,modified_datetime,is_active')
            ->where('is_deleted', '0')
            ->where('cms_id', $data['cms_id'])
            ->get('tbl_cms');
        return $query->result();
    }

    public function verify_otp($data, $user_id,$user_otp)
    {
        $this->db->where('is_verify', '0')
            ->where('user_id', $user_id)
            ->where('otp', $user_otp);
        $update = $this->db->update('tbl_otp_verification', $data);
        
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function update_cms($data, $cms_id)
    {
        $this->db->where('is_deleted', '0')->where('cms_id', $cms_id);
        $update = $this->db->update('tbl_cms', $data);
        return $update;
    }
}