<?php
class Blogs_model extends CI_Model {

     public function count_all()
    {
        return $this->db
            ->where('is_active', '1')
            ->count_all_results('tblblog_master');
    }

    public function get_blogs($limit, $offset)
    {
        return $this->db
            ->where('is_active', '1')
            ->order_by('blog_id', 'DESC')
            ->limit($limit, $offset)
            ->get('tblblog_master')
            ->result_array();
    }

    public function get_blog_by_slug($slug)
{
    return $this->db
        ->where('blog_slug', $slug)
        ->where('is_active', '1')
        ->get('tblblog_master')
        ->row_array();
}
}
