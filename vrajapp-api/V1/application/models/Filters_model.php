<?php
class Filters_model extends CI_Model
{
    public function get_category_by_slug($data)
    {
        $query = $this->db->distinct('c.category_id,c.category_name,c.category_slug,p.category_name')
            ->join('tbl_categories p', 'c.parent_category_id=p.category_id', 'left')
            ->where('c.category_slug', $data['category_slug'])
            ->get('tbl_categories c');
        // echo $this->db->last_query();exit;
        $res = $query->result();
        echo "check";
        // return $query->result();
    }
}