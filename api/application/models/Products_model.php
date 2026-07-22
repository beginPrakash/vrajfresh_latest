<?php

class Products_model extends CI_Model

{

    public function get_products($data)

    {

        if ($data['is_home_display'] == 1) {

            $query = $this->db->where('p.is_home_display', '1');

        }

        if ($data['product_style'] != " ") {

            $query = $this->db->where('p.product_style', $data['product_style']);

        }

        $query = $this->db->select('p.product_id,p.product_name,ip.image,p.product_slug,p.product_price,p.product_weight_gms,p.is_active,p.is_perisible_products')

            ->join('tbl_brands b', 'p.brand_id=b.brand_id')

            ->join('tbl_product_images ip', 'p.product_id=ip.product_id')

            ->where('p.is_active', $data['is_active'])

            ->where('p.is_deleted', '0')

            ->order_by($data['sort_column'], $data['sort_order'])

            ->limit($data['limit'], $data['page_no'])

            ->get('tbl_products p');

        //    echo $this->db->last_query();exit;

        return $query->result();

    }

    public function get_products_by_search($data, $search_keyword)

    {

        $query = $this->db->select('p.product_id,p.product_name,p.product_image,p.product_slug,p.product_price,p.product_weight_gms,p.is_active,p.is_perisible_products')

            ->join('tbl_brands b', 'p.brand_id=b.brand_id')

            ->where('p.is_deleted', '0')

            ->where('p.is_active', $data['is_active']);



        foreach ($search_keyword as $key => $value) {

            $query = $this->db->like('p.' . $key, $value);

        }

        $query = $this->db->order_by($data['sort_column'], $data['sort_order'])

            ->limit($data['limit'], $data['page_no'])

            ->get('tbl_products p');

        return $query->result();



    }

    public function get_product_with_multi_category($data)

    {
        $query = $this->db->select('c.category_id,c.category_name, c.is_perisible_products AS is_perisible_category,c.is_liker_category,c.is_cook_food_category,p.is_out_of_stock,p.product_id,p.product_sub_name,p.brand_id,p.product_name,p.is_perisible_products,p.is_liker_products,p.is_cook_food_products')

            ->join('tbl_brands b', 'p.brand_id=b.brand_id')

            ->join('tbl_categories_products_mapping cpm', 'cpm.product_id=p.product_id', "LEFT")

            ->join('tbl_categories c', 'cpm.category_id=c.category_id', "LEFT")

            ->where('p.product_slug', $data['product_slug'])

            ->where('p.is_active', 1)

            ->where('p.is_deleted', '0')

            ->get('tbl_products p');

        return $query->result();

    }

    public function get_product_by_slug($data)

    {

        $query = $this->db->select('p.is_out_of_stock,p.product_id,p.product_sub_name,p.brand_id,p.product_name,p.product_image,p.product_slug,p.product_price,p.sale_price,p.product_weight_gms,p.is_active,p.product_description,p.badge_text,p.badge_text_color,p.badge_background_color,p.product_sku,p.created_by,p.created_datetime,p.modified_by,p.modified_datetime,p.is_active,b.brand_name,b.brand_slug,p.is_perisible_products, p.product_tax,p.is_liker_products,p.is_cook_food_products')

            ->join('tbl_brands b', 'p.brand_id=b.brand_id')



            ->where('p.product_slug', $data['product_slug'])

            ->where('p.is_active', 1)

            ->where('p.is_deleted', '0')

            ->get('tbl_products p');

        return $query->result();

    }

    public function add_product($data, $table)

    {

        $this->db->insert($table, $data);

        if ($this->db->affected_rows() > 0) {

            return $this->db->insert_id();

        } else {

            return 0;

        }

    }

    public function get_product_brand($product_id)

    {

        $query = $this->db->select("brand_id,GROUP_CONCAT(brand_id) AS brand")->where('product_id', $product_id)->get('tbl_products');

        return $query->result_array();

    }

    public function get_product_category($product_id)

    {

        $query = $this->db->select("category_id,GROUP_CONCAT(category_id) AS category")->where('product_id', $product_id)->get('tbl_categories_products_mapping');

        return $query->result_array();

    }

    public function get_product_by_id($data)

    {

        //     $query=$this->db->select('p.*,c.category_name,c.category_slug,b.brand_name,v.variant_1_value,v.variant_2_value,v.variant_3_value,v.variant_price,pi.image')

        //                     ->join('tbl_brands b','p.brand_id=b.brand_id')

        //                     ->join('tbl_categories_products_mapping cpm','p.product_id=cpm.product_id')

        //                     ->join('tbl_categories c','cpm.category_id=c.category_id')

        //                     ->join('tbl_product_images pi','p.product_id=pi.product_id','left')

        //                     ->join('tbl_variant_products v','v.product_id=p.product_id','left')

        //                     ->where('p.product_id',$data['product_id'])

        //                     ->where('p.is_active','1')

        //                     ->where('p.is_deleted','0')

        //                     ->get('tbl_products p');

        //           //          echo $this->db->last_query();exit;

        //                     return $query->result();

        $query = $this->db->select('p.*')

            ->where('p.product_id', $data['product_id'])

            ->where('p.is_active', '1')

            ->where('p.is_deleted', '0')

            ->get('tbl_products p');

        return $query->result_array();

    }



    public function get_category_by_product_id($data)

    {

        $query = $this->db->select('c.category_id,c.category_slug,c.category_name')

            ->join('tbl_categories c', 'cpm.category_id=c.category_id')

            ->where('cpm.product_id', $data)

            ->get('tbl_categories_products_mapping cpm');

        return $query->result();

    }

    public function get_brand_by_product_id($data)

    {

        $query = $this->db->select('b.brand_name')

            ->join('tbl_brands b', 'b.brand_id=p.brand_id')

            ->where('p.product_id', $data['product_id'])

            ->get('tbl_products p');

        return $query->result();

    }

    public function get_tag_by_product_id($data)

    {

        $query = $this->db->select('t.tag_id,t.tag')

            ->join('tbl_tag_master t', 'tpm.tag_id=t.tag_id')

            ->where('tpm.product_id', $data)

            ->get('tbl_product_tags_mapping tpm');

        return $query->result();

    }

    public function get_image_by_product_id($data)

    {

        $query = $this->db->select('pi.image')

            ->join('tbl_product_images pi', 'pi.product_id=p.product_id', 'left')

            ->join('tbl_variant_products v', 'pi.variant_id=v.variant_id', 'left')

            ->where('p.product_id', $data)

            ->get('tbl_products p');

        return $query->result();

    }

    public function get_variant_by_product_id($data)

    {

        $query = $this->db->select('v.product_variant_size,v.variant_price,v.id,v.is_out_of_stock, v.variant_image')

            ->join('tblproduct_variant v', 'v.product_id=p.product_id', 'left')

            //->join('tbl_users u','u.user_id=v.created_by','left')

            ->where('p.product_id', $data)

            // ->where('v.is_active','1')

            // ->where('v.is_deleted','0')
            ->order_by('CAST(v.variant_price as UNSIGNED)', 'ASC')
            ->get('tbl_products p');

        return $query->result();

    }

    public function get_variant_by_product_id_filter($data)

    {

        $query = $this->db->select('v.product_variant_size,v.variant_price,v.id,v.is_out_of_stock, v.variant_image')

            ->join('tblproduct_variant v', 'v.product_id=p.product_id', 'left')

            //->join('tbl_users u','u.user_id=v.created_by','left')

            ->where('p.product_id', $data)

            ->where('v.is_out_of_stock','1')

            // ->where('v.is_deleted','0')
            ->order_by('CAST(v.variant_price as UNSIGNED)', 'ASC')

            ->get('tbl_products p');

        return $query->result();

    }

    public function update_product($data, $product_id, $table)

    {

        $this->db->where('is_deleted', '0')->where('product_id', $product_id);

        $update = $this->db->update($table, $data);

        return $update;

    }

    public function get_special_products_ids($data)

    {

        $query = $this->db->select('special_product_name,product_ids')->where('special_product_slug', $data['special_product_slug'])->get('tbl_special_products');

        return $query->result_array();

    }

    public function get_special_products($data)

    {

        $query = $this->db->select('p.product_id,p.product_slug,p.product_name,pi.image,p.product_image,p.product_price,p.sale_price,p.product_weight_gms,pv.product_variant_size,pv.variant_price,pv.id AS product_variant_id,p.is_perisible_products,p.product_tax,p.is_out_of_stock,c.is_perisible_products AS category_is_perisible_products,p.is_liker_products,p.is_cook_food_products,c.is_liker_category AS category_is_liker_category,c.is_cook_food_category AS category_is_cook_food_category')

            //->join('tbl_users u','u.user_id=pv.created_by','left')

            ->join('tbl_product_images pi', 'p.product_id=pi.product_id', 'left')

            ->join('tblproduct_variant pv', 'p.product_id=pv.product_id', "LEFT")

            ->join('tbl_categories_products_mapping cpm', 'cpm.product_id=p.product_id', "LEFT")

            ->join('tbl_categories c', 'cpm.category_id=c.category_id', "LEFT")

            ->where('p.is_deleted', '0')->where('p.product_id IN (' . $data["product_id"] . ')');

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

        $query = $this->db->get('tbl_products p');
        //echo $this->db->last_query();exit;

        return $query->result();

    }

    public function get_home_category_product($data = "")

    {

        $query = $this->db->select('pv.id,c.category_name,c.category_slug,p.product_id,p.product_name,p.product_slug,pi.image,p.product_price,p.product_weight_gms,pv.product_variant_size,pv.variant_price,p.is_perisible_products,p.product_tax,p.is_out_of_stock')

            ->join('tbl_categories_products_mapping cpm', 'cpm.product_id=p.product_id')

            ->join('tbl_categories c', 'cpm.category_id=c.category_id')

            ->join('tbl_product_images pi', 'p.product_id=pi.product_id', 'left')

            ->join('tblproduct_variant pv', 'cpm.product_id=pv.product_id', "LEFT")

            ->where('c.style', 'category_title')

            ->where('c.is_home_display', '1')

            ->where('p.is_home_display', '1');

        /* if($data !="")

         {

             $query=$this->db->where('c.is_perisible_products',0);

         }*/

        $query = $this->db->get('tbl_products p');

        return $query->result();

    }

    public function get_product_by_category_slug($slug, $zipcode)

    {

        $query = $this->db->select('p.product_id,p.product_name,p.product_slug,p.product_image,p.product_price,p.product_weight_gms,pv.product_variant_size,pv.variant_price,pv.id,p.is_perisible_products,p.is_liker_products,p.is_cook_food_products,p.is_out_of_stock,pv.is_out_of_stock as variant_is_out_of_stock,p.product_tax')

            ->join('tbl_categories_products_mapping cpm', 'cpm.product_id=p.product_id')

            ->join('tbl_categories c', 'cpm.category_id=c.category_id')

            ->join('tblproduct_variant pv', 'cpm.product_id=pv.product_id', "LEFT")

            ->where('c.category_slug', $slug)

            ->where('p.is_home_display', '1')

            ->where('p.is_active', '1')

            ->where('p.is_deleted', '0');

            if(isset($data['is_perisible_zipcode']) && $data['is_perisible_zipcode'] == "No"){
            
                $query=$this->db->where('p.is_perisible_products', 1);
            }
    
            if(isset($data['is_liker_zipcode']) && $data['is_liker_zipcode'] == "No"){
                
                $query=$this->db->where('p.is_liker_products', 1);
            }
    
            if(isset($data['is_cook_food_zipcode']) && $data['is_cook_food_zipcode'] == "No"){
                
                $query=$this->db->where('p.is_cook_food_products', 1);
            }

            $query = $this->db->get('tbl_products p');



        return $query->result();

    }

    public function get_related_product_by_category_slug($slug, $product_id, $data = array())

    {

        $query = $this->db->select('c.category_name,p.product_id,p.product_name,p.product_slug,p.product_image,p.product_price,p.product_weight_gms,p.is_perisible_products,p.is_liker_products,p.is_cook_food_products,c.is_perisible_products as is_perisible_category,c.is_liker_category,c.is_cook_food_category,p.product_tax,p.is_out_of_stock')

            ->join('tbl_categories_products_mapping cpm', 'cpm.product_id=p.product_id')

            ->join('tbl_categories c', 'cpm.category_id=c.category_id')

            ->where('c.category_slug', $slug)

            ->where('p.product_id !=' . $product_id)

            ->where('p.is_active', '1')

            ->where('p.is_deleted', '0');

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


            $query=$this->db->limit('5')->get('tbl_products p');
            //echo $this->db->last_query();exit;

        return $query->result();

    }

    public function calculate_tag_discount($prod_price,$sale_price){
        if(!empty($prod_price) && !empty($sale_price)){
            $percentageDifference = (abs($prod_price - $sale_price) / (($prod_price))) * 100;
            return round($percentageDifference);
        }
    }

}