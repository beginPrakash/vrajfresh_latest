<?php
class Cms_model extends CI_Model
{
    public function get_cms($data)
    {
        $query = $this->db->select('cms_id,cms_title,is_active')
            ->where('is_deleted', '0')
            ->where('is_active', $data['is_active'])
            ->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_cms');

        return $query->result();
    }
    public function get_cms_by_search($data, $search_keyword)
    {
        $query = $this->db->select('cms_id,cms_title,is_active')
            ->where('is_deleted', '0')
            ->where('is_active', $data['is_active']);

        foreach ($search_keyword as $key => $value) {
            $query = $this->db->like($key, $value);
        }
        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_cms');
        // echo $this->db->last_query();exit;
        return $query->result();

    }
    public function add_cms($data)
    {
        $this->db->insert('tbl_cms', $data);
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
    public function get_cms_by_slug($data)
    {
        $query = $this->db->select('cms_id,cms_title,cms_url,cms_description,created_by,modified_by,created_datetime,modified_datetime,is_active,meta_title,meta_descriptions')
            ->where('is_deleted', '0')
            ->where('cms_url', $data['cms_slug'])
            ->get('tbl_cms');
        return $query->result();
    }
    public function update_cms($data, $cms_id)
    {
        $this->db->where('is_deleted', '0')->where('cms_id', $cms_id);
        $update = $this->db->update('tbl_cms', $data);
        return $update;
    }
}