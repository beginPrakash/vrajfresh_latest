<?php
class Categories_model extends CI_Model
{
    public function get_categories($data)
    {
        $query = $this->db->select('c.category_id,c.category_name,c.category_slug,c.is_perisible_products,c.is_liker_category,c.is_cook_food_category,c.category_image,c.category_background_image,c.parent_category_id,c.is_active')
            ->join('tbl_categories p', 'c.parent_category_id=p.category_id', 'left')
            ->where('c.style', $data['style'])
            ->where('c.is_active', $data['is_active'])
            ->where('c.is_deleted', '0');
        if ($data['is_home_display'] != "") {
            $query = $this->db->where('c.is_home_display', $data['is_home_display']);
        }
        
        if(isset($data['is_perisible_zipcode']) && $data['is_perisible_zipcode'] == "No"){
            
            $query=$this->db->where_in('c.is_perisible_products', [0,2]);
        }

        if(isset($data['is_liker_zipcode']) && $data['is_liker_zipcode'] == "No"){
            
            $query=$this->db->where_in('c.is_liker_category', [0,2]);
        }

        if(isset($data['is_cook_food_zipcode']) && $data['is_cook_food_zipcode'] == "No"){
            
            $query=$this->db->where_in('c.is_cook_food_category', [0,2]);
        }
        
        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_categories c');
        //    echo $this->db->last_query();exit;
        return $query->result();
    }
    public function get_categories_by_search($data, $search_keyword)
    {
        $query = $this->db->select('c.category_id,c.category_name,c.category_slug,c.category_image,c.parent_category_id,c.is_active')
            ->join('tbl_categories p', 'c.parent_category_id=p.category_id', 'left')
            ->where('c.is_deleted', '0')
            ->where('c.is_active', $data['is_active']);

        foreach ($search_keyword as $key => $value) {
            $query = $this->db->like('c.' . $key, $value);
        }
        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])
            ->limit($data['limit'], $data['page_no'])
            ->get('tbl_categories c');
        // echo $this->db->last_query();exit;
        return $query->result();

    }
    public function add_category($data)
    {
        $this->db->insert('tbl_categories', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_category_by_id($data)
    {
        $query = $this->db->select('c.category_id,c.category_name,c.category_slug,c.category_image,c.parent_category_id,c.is_active,c.is_perisible_products AS is_perisible_category,c.is_liker_category,c.is_cook_food_category')
            ->join('tbl_categories p', 'c.parent_category_id=p.category_id', 'left')
            ->where('c.category_id', $data['category_id'])
            ->where('c.is_deleted', '0')
            ->get('tbl_categories c');
        return $query->result();
    }
    public function update_category($data, $category_id)
    {
        $this->db->where('is_deleted', '0')->where('category_id', $category_id);
        $update = $this->db->update('tbl_categories', $data);
        return $update;
    }
    public function get_category_by_slug($data)
    {
        $query = $this->db->select('c.category_id,c.category_name,c.category_slug,c.category_description,c.category_image,c.category_background_image,c.category_icon,c.is_home_display,c.style,c.is_active,c.is_perisible_products,c.is_liker_category,c.is_cook_food_category')
            ->join('tbl_categories p', 'c.parent_category_id=p.category_id', 'left')
            ->where('c.category_slug', $data['category_slug']);
            if(isset($data['is_perisible_zipcode']) && $data['is_perisible_zipcode'] == "No"){
                $query=$this->db->where_in('c.is_perisible_products', [0,2]);
            }
            if(isset($data['is_liker_zipcode']) && $data['is_liker_zipcode'] == "No"){
                $query=$this->db->where_in('c.is_liker_category', [0,2]);
            }
            if(isset($data['is_cook_food_zipcode']) && $data['is_cook_food_zipcode'] == "No"){
                $query=$this->db->where_in('c.is_cook_food_category', [0,2]);
            }
            $query = $this->db->get('tbl_categories c');
           //echo $this->db->last_query();exit;
        return $query->result();
    }
    public function get_category_by_zipcode($data)
    {
        $query = $this->db->select('c.category_name,c.category_slug,c.category_description,c.category_image,c.category_background_image,c.category_icon,c.is_home_display,c.style,c.is_active')
            ->join('tbl_categories p', 'c.parent_category_id=p.category_id')
            ->where('c.category_slug', $data['category_slug'])
            ->get('tbl_categories c');
        return $query->result();
    }
    public function get_product_by_category_id($data)
    {
        $category_store_proc = "CALL sp_product_search(?,?,?,?,?,?,?,?,?,?)";
        $data = array('category_slug' => '', 'category_id' => $data, 'brand_id' => '', 'min_price' => '', 'max_price' => '', 'tag_id' => '', 'search_term' => '', 'page_no' => 0, 'page_size' => 10, 'sort_order' => 'desc');
        $result = $this->db->query($category_store_proc, $data);
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
    public function get_image_by_product_id($data)
    {
        $query = $this->db->select('pi.variant_id,pi.image as product_image')
            ->join('tbl_product_images pi', 'pi.product_id=p.product_id', 'left')
            ->join('tbl_variant_products v', 'pi.variant_id=v.variant_id', 'left')
            ->where('p.product_id', $data)
            ->get('tbl_products p');
        return $query->result();
    }
    public function get_variant_by_product_id($data)
    {
        $query = $this->db->select('v.variant_master_1_id,v.variant_1_value,v.variant_master_2_id,v.variant_2_value,v.variant_master_1_id,v.variant_3_value,v.variant_price,v.is_active')
            ->join('tbl_variant_products v', 'v.product_id=p.product_id', 'left')
            ->join('tbl_users u', 'u.user_id=v.created_by', 'left')
            // ->where('p.product_id',$data['product_id'])
            ->where('v.is_active', '1')
            ->where('v.is_deleted', '0')
            ->get('tbl_products p');
        return $query->result();
    }
    public function get_category_filter($product_ids, $is_perisible = '1')
    {
        // $category_filter_store_proc= "CALL sp_get_product_search_filters(?)";
        //$data=array('product_ids'=>$product_ids);
        $query = "CALL sp_get_product_search_filters('" . $product_ids . "','" . $is_perisible . "')";
        $result = $this->db->query("CALL sp_get_product_search_filters('" . $product_ids . "','" . $is_perisible . "')");

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
    public function get_filter_products($data)
    {
        // print_r($data);exit;

        $query = $this->db->from('tbl_products p')
            ->select('p.product_id,p.product_name,p.product_slug,p.product_price,p.product_weight_gms,p.product_image,p.is_perisible_products,p.product_tax,pv.is_out_of_stock as variant_is_out_of_stock, p.is_out_of_stock, pv.id AS variant_id')
            ->join('tbl_categories_products_mapping cpm', 'p.product_id = cpm.product_id')
            ->join('tbl_categories c', 'c.category_id=cpm.category_id')
            ->join('tblproduct_variant pv', 'p.product_id=pv.product_id', "LEFT")
            ->join('tbl_brands b', 'p.brand_id = b.brand_id')
            ->join(' tbl_product_tags_mapping t', ' p.product_id = t.product_id')
            ->where("p.is_active=1");


        if ($data['category_id'] != "") {
            $query = $this->db->where("c.category_id IN(" . $data['category_id'] . ")");
        }
        if ($data['brand_id'] != "") {
            $query = $this->db->where("b.brand_id IN(" . $data['brand_id'] . ")");
        }
        if ($data['tag_id'] != "") {
            $query = $this->db->where("t.tag_id IN(" . $data['tag_id'] . ")");
        }
        if ($data['min_price'] != "") {
            $query = $this->db->where("p.product_price > '" . $data['min_price'] . "'");
        }
        if ($data['max_price'] != "") {
            $query = $this->db->where("p.product_price < '" . $data['max_price'] . "'");
        }
        if(isset($data['can_deliver_perishable_products']) && $data['can_deliver_perishable_products'] == "No"){
            
            $query=$this->db->where_in('c.is_perisible_products', [0,2]);

            $this->db->where("IF(c.is_perisible_products = '2' , p.is_perisible_products = '1', 1)");
        }

        if(isset($data['can_deliver_liker_products']) && $data['can_deliver_liker_products'] == "No"){
        
            $query=$this->db->where_in('c.is_liker_category', [0,2]);

            $this->db->where("IF(c.is_liker_category = '2' , p.is_liker_products = '1', 1)");
        }

        if(isset($data['can_deliver_cook_food_products']) && $data['can_deliver_cook_food_products'] == "No"){
        
            $query=$this->db->where_in('c.is_cook_food_category', [0,2]);

            $this->db->where("IF(c.is_cook_food_category = '2' , p.is_cook_food_products = '1', 1)");
        }

        if ($data['search_keyword'] != "") {
            // $query = $this->db->where("(p.product_name LIKE '%" . $data['search_keyword'] . "%' OR FIND_IN_SET('". $data['search_keyword'] . "',search_tags) > 0)");
            $query = $this->db->where("(p.product_name LIKE '%" . $data['search_keyword'] . "%' OR search_tags LIKE '%".$data['search_keyword']."%')");
        }
        $query = $this->db->group_by("p.product_id,p.product_name,p.product_slug,p.product_price,p.product_weight_gms,p.product_image");
        $query = $this->db->order_by("p.product_name", "ASC");

        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        return $query->result();
    }
    public function get_product_by_category_search($data)
    {
        $category_store_proc = "CALL sp_home_product_search(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $data = array('category_slug' => '', 'category_id' => '', 'brand_id' => '', 'min_price' => '', 'max_price' => '', 'tag_id' => '', 'search_term' => $data['search_term'], 'page_no' => 0, 'page_size' => 10, 'sort_order' => 'desc', 'Z_perishable' => $data['can_deliver_perishable_products'], 'Z_liker' => $data['can_deliver_liker_products'], 'Z_cook_food' => $data['can_deliver_cook_food_products']);
        $result = $this->db->query($category_store_proc, $data);
        // echo $this->db->last_query();exit;
        $res = $result->result();

        $result->next_result();
        $result->free_result();

        if ($res != null) {
            // print_r($res);exit;
            return $res;
        } else {
            return 0;
        }
    }
}