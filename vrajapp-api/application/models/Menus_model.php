<?php

class Menus_model extends CI_Model

{

    public function get_menus($data)

    {

        $query = $this->db->select('mi.*, mi.menu_title as parent_menu_title,mi.menu_link as parent_menu_link, c.category_name, c.category_slug, c.is_perisible_products, c.is_liker_category, c.is_cook_food_category')



            ->join('tbl_categories c', 'mi.category_id=c.category_id', 'left')



            ->where('mi.menu_id', $data['menu_id'])



            ->where('mi.is_active', '1');

            if(isset($data['is_perisible_zipcode']) && $data['is_perisible_zipcode'] == "No"){
            
                $query=$this->db->where_in('c.is_perisible_products', [0,2]);
            }
    
            if(isset($data['is_liker_zipcode']) && $data['is_liker_zipcode'] == "No"){
                
                $query=$this->db->where_in('c.is_liker_category', [0,2]);
            }
    
            if(isset($data['is_cook_food_zipcode']) && $data['is_cook_food_zipcode'] == "No"){
                
                $query=$this->db->where_in('c.is_cook_food_category', [0,2]);
            }



            //->where('mi.only_one !=','no')



            $query = $this->db->get('tbl_menu_item mi');



        return $query->result();

    }



}