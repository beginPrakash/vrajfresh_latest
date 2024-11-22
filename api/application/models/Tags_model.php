<?php
class Tags_model extends CI_Model
{
    public function get_tags($data)
    {
        $query = $this->db->select('t.*,u.user_name')
            ->join('tbl_users u', 't.created_by=u.user_id')
            ->where('t.is_deleted', '0')
            ->where('t.is_active', $data['is_active'])
            ->order_by('t.' . $data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_tag_master t');

        return $query->result();
    }
    public function get_tags_by_search($data, $search_keyword)
    {
        $query = $this->db->select('t.*,u.user_name')
            ->join('tbl_users u', 't.created_by=u.user_id')
            ->where('t.is_deleted', '0')
            ->where('t.is_active', $data['is_active']);

        foreach ($search_keyword as $key => $value) {
            $query = $this->db->like($key, $value);
        }
        $query = $this->db->order_by('t.' . $data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_tag_master t');
        // echo $this->db->last_query();exit;
        return $query->result();

    }
    public function add_tag($data)
    {
        $this->db->insert('tbl_tag_master', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_tag_by_id($data)
    {
        $query = $this->db->select('t.*,u.user_name')
            ->join('tbl_users u', 't.created_by=u.user_id')
            ->where('t.tag_id', $data['tag_id'])
            ->where('t.is_deleted', '0')
            ->get('tbl_tag_master t');
        return $query->result();
    }
    public function update_tag($data, $tag_id)
    {
        $this->db->select('t.*,u.user_name')
            ->join('tbl_users u', 't.created_by=u.user_id')
            ->where('t.is_deleted', '0')
            ->where('t.tag_id', $tag_id);
        $update = $this->db->update('tbl_tag_master t', $data);
        return $update;
    }
    public function get_product_by_tag_id($data)
    {
        $brand_store_proc = "CALL sp_product_search(?,?,?,?,?,?,?,?,?,?)";
        $data = array('category_slug' => '', 'category_id' => '', 'brand_id' => '', 'min_price' => '', 'max_price' => '', 'tag_id' => $data, 'search_term' => '', 'page_no' => 0, 'page_size' => 10, 'sort_order' => 'desc');
        $result = $this->db->query($brand_store_proc, $data);
        //echo $this->db->last_query();exit;
        $res = $result->result();

        $result->next_result();
        $result->free_result();

        if ($res != null) {
            return $res;
        } else {
            return 0;
        }

    }
}