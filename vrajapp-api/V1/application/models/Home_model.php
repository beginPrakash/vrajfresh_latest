<?php
class Home_model extends CI_Model
{
    public function get_top_banners_data()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get('tbl_banner_top');
        return $query->result();
    }

    public function get_featured_category_model($data)
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_feture_categories');
        return $query->result();
    }

    public function get_stockup_your_frozen_model($data)
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_stockup_frozen');
        return $query->result();
    }

    public function get_refill_pantry_model($data)
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_pantry');
        return $query->result();
    }
    public function get_home_product_slider()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get('tbl_home_product_slider');
        return $query->result();
    }
    public function get_home_product_slider_item($home_product_slider_id)
    {
        $query = $this->db->select('*')
            ->where('home_product_slider_id', $home_product_slider_id)
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get('tbl_home_product_slider_item');
        return $query->result();
    }

    public function get_title_by_id($key)
    {
        $query = $this->db->select($key)
            ->where('banner_top_id', 1)
            ->where('is_deleted', '0')
            ->get('tbl_banner_top');
        $data = $query->result_array();
        if(!empty($data)):
            return $data[0][$key];
        endif;
    }
    /*
    public function get_new_savings_model()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get('tbl_new_savings');
        return $query->result();
    }

    public function get_fresh_veg_model()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get('tbl_fresh_veg');
        return $query->result();
    }

    public function get_vraj_backery_model()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get('tbl_vraj_bakery');
        return $query->result();
    }
    public function get_shop_ayurvedic_model()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->get('tbl_shop_ayurvedic');
        return $query->result();
    }
    */

    public function get_home_banner_model()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->order_by('banner_srno','ASC')
            ->get('tbl_home_banners');
        return $query->result();
    }

    public function get_advertise_top_model()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->where('adv_type', 'top')
            ->get('tbl_advertise_top');
        return $query->result();
    }

    public function get_advertise_bottom_model()
    {
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->where('adv_type', 'bottom')
            ->order_by('adv_srno', 'ASC')
            ->get('tbl_advertise_top');
        return $query->result();
    }

    public function get_special_category_product_model($data)
    {
        $special_category_slug=$data['special_category_slug'];
		
        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->where('slug', $special_category_slug)
            ->get('tbl_home_product_slider');
        $home_product_slider_result = $query->result();
        // print_r($home_product_slider_result);

        $query = $this->db->select('*')
            ->where('is_deleted', '0')
            ->where('is_active', '1')
            ->where('home_product_slider_id', $home_product_slider_result[0]->home_product_slider_id)
            ->get('tbl_home_product_slider_item');
        $result = $query->result();
        // print_r($result);

        $product_ids=[];
        for ($i = 0; $i < count($result); $i++) {
            $product_ids[] =$result[$i]->product_id;
        }
        $response=[
            'home_product_slider_data'=>$home_product_slider_result,
            'product_ids'=>$product_ids,
        ];
        return $response;
    }
}