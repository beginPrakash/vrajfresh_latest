<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fcm_token_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Store or update an FCM token upon login
     */
    public function save_token($user_id, $fcm_token, $device_type = null) {
    if (empty($fcm_token)) {
        return FALSE;
    }

    // Check if this specific token already exists in the system
    $this->db->where('fcm_token', $fcm_token);
    $query = $this->db->get('tbl_user_fcm_tokens');

    $data = [
        'user_id'     => $user_id,
        'device_type' => $device_type,
        'updated_at'  => date('Y-m-d H:i:s')
    ];

    // FIX: Changed num_counts() to num_rows()
    if ($query->num_rows() > 0) {
        // Token exists. Update it to point to the current logged-in user.
        $this->db->where('fcm_token', $fcm_token);
        return $this->db->update('tbl_user_fcm_tokens', $data);
    } else {
        // New device token entirely. Insert it.
        $data['fcm_token'] = $fcm_token;
        return $this->db->insert('tbl_user_fcm_tokens', $data);
    }
}

    /**
     * Remove the FCM token upon logout so they stop receiving notifications
     */
    public function delete_token($fcm_token) {
        if (empty($fcm_token)) {
            return FALSE;
        }
        
        $this->db->where('fcm_token', $fcm_token);
        return $this->db->delete('tbl_user_fcm_tokens');
    }
}