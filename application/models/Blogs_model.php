<?php
class Blogs_model extends CI_Model {

    public function get_blogs($limit, $start) {
        return $this->db->order_by('id','DESC')
                        ->limit($limit, $start)
                        ->get('blogs')
                        ->result();
    }
}
