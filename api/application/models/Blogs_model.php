<?php
class Blogs_model extends CI_Model
{
    public function get_blogs($data)
    {
        $query = $this->db->select('*')
            ->where('is_active', $data['is_active'])
            ->where('is_deleted', '0')
            ->order_by('blog_id','desc');
        
        if ($data['limit'] != "") {
            $query = $this->db->limit($data['limit'], $data['page_no']);
        }

        $query = $this->db->get('tblblog_master');

        return $query->result();
    }
    public function get_blogs_by_search($data)
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', 1);

        $query = $this->db->order_by('blog_id', 'desc')
            ->limit($data['limit'], $data['page_no'])
            ->get('tblblog_master');
        // echo $this->db->last_query();exit;
        return $query->result();

    }
    public function add_brand($data)
    {
        $this->db->insert('tblblog_master', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_brand_by_id($data)
    {
        $query = $this->db->select('brand_id,brand_name,brand_image,is_home_display,is_active')
            ->where('brand_id', $data['brand_id'])
            ->where('is_deleted', '0')
            ->get('tblblog_master');
        return $query->result();
    }
    public function update_brand($data, $brand_id)
    {
        $this->db->where('is_deleted', '0')->where('brand_id', $brand_id);
        $update = $this->db->update('tblblog_master', $data);
        return $update;
    }
    public function get_brand_by_slug($data)
    {
        $query = $this->db->select('b.brand_id,b.brand_slug,b.brand_name')
            ->where('b.brand_slug', $data['brand_slug'])
            ->get('tblblog_master b');
        //   echo $this->db->last_query();exit;
        return $query->result();
    }
    public function get_product_by_brand_id($data)
    {
        $brand_store_proc = "CALL sp_product_search(?,?,?,?,?,?,?,?,?,?)";
        $data = array('category_slug' => '', 'category_id' => '', 'brand_id' => $data, 'min_price' => '', 'max_price' => '', 'tag_id' => '', 'search_term' => '', 'page_no' => 0, 'page_size' => 10, 'sort_order' => 'desc');
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