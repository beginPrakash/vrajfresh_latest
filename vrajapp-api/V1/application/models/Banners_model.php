<?php
class Banners_model extends CI_Model
{
    public function get_banners($data)
    {
        $query = $this->db->select('banner_id,banner_text,banner_image,banner_link,banner_type,banner_srno,is_active')
            ->where('banner_type', $data['banner_type'])
            ->where('is_deleted', '0')
            ->where('is_active', $data['is_active'])
            ->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_banners');
        return $query->result();
    }
    public function get_banners_by_search($data, $search_keyword)
    {
        $query = $this->db->select('banner_id,banner_text,banner_image,banner_link,banner_type,banner_srno,is_active')
            ->where('banner_type', $data['banner_type'])
            ->where('is_deleted', '0')
            ->where('is_active', $data['is_active']);

        foreach ($search_keyword as $key => $value) {
            $query = $this->db->like($key, $value);
        }
        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_banners');
        // echo $this->db->last_query();exit;
        return $query->result();
        // $banner_store_proced="CALL sp_get_banners_by_search(?,?,?,?,?,?)";
        // $data=array($data['limit'],$data['page_no'],$data['sort_column'],$data['sort_order'],$data['is_active'],$search_query);
        // $output=$this->db->query($banner_store_proced,$data);
        // return $output->result();
    }
    public function add_banner($data)
    {
        $this->db->insert('tbl_banners', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_banner_by_id($data)
    {
        $query = $this->db->select('banner_id,banner_text,banner_image,banner_link,banner_type,banner_srno,is_active')
            ->where('banner_id', $data['banner_id'])
            ->where('is_deleted', '0')
            ->get('tbl_banners');
        return $query->result();
    }
    public function update_banner($data, $banner_id)
    {
        $this->db->where('is_deleted', '0')->where('banner_id', $banner_id);
        $update = $this->db->update('tbl_banners', $data);
        return $update;
    }
}