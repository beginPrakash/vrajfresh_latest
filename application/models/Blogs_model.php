<?php
class Blogs_model extends CI_Model {

     public function count_all()
    {
        return $this->db
            ->where('is_active', 'Y')
            ->count_all_results('tblblog_master');
    }

    public function get_blogs($limit, $offset)
    {
        return $this->db
            ->where('is_active', 'Y')
            ->order_by('blog_id', 'DESC')
            ->limit($limit, $offset)
            ->get('tblblog_master')
            ->result_array();
    }
}
