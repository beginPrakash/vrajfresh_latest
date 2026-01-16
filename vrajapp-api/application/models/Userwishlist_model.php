<?php
class Userwishlist_model extends CI_Model
{
    public function add_userwishlist($data)
    {

        if($data['is_like'] == 1){
            $this->db->insert('tbl_user_wishlist', $data);
            if ($this->db->affected_rows() > 0) {
                return $this->db->insert_id();
            } else {
                return 0;
            }
        }else{
            $this->db->where('user_id', $data['user_id'])->where('product_id', $data['product_id'])->delete('tbl_user_wishlist');
            return true;
        }
         
        
    }
    
     public function get_wishlist_by_user_id_count($data)

    {

        $query = $this->db->select('uw.user_id,p.product_id,p.product_name')
        ->from('tbl_user_wishlist uw')
            ->join('tbl_products p', 'uw.product_id=p.product_id', 'left')
            ->where('uw.user_id', $data)
            ->order_by('uw.wishlist_id', 'asc')
            ->get();

        return $query->result();


    }

    public function get_wishlist_by_user_id($data,$limit, $offset)

    {
        $query = $this->db->from('tbl_user_wishlist uw')
            ->select('uw.user_id,uw.is_like,p.product_id,p.product_name,p.product_slug,p.product_price,p.sale_price,p.product_weight_gms,p.product_image,p.is_perisible_products,p.product_tax,pv.is_out_of_stock as variant_is_out_of_stock, p.is_out_of_stock, pv.id,pv.product_variant_size,pv.variant_price,pv.variant_image')
            ->join('tbl_products p', 'uw.product_id=p.product_id')
            ->join('tbl_categories_products_mapping cpm', 'p.product_id = cpm.product_id')
            ->join('tbl_categories c', 'c.category_id=cpm.category_id')
            ->join('tblproduct_variant pv', 'p.product_id=pv.product_id', "LEFT")
            ->join('tbl_brands b', 'p.brand_id = b.brand_id')
            ->join(' tbl_product_tags_mapping t', ' p.product_id = t.product_id')
            ->where('uw.user_id', $data)
            ->order_by('uw.wishlist_id', 'asc')
            ->group_by('uw.product_id')
            ->limit($limit, $offset)
            ->get();

        return $query->result();


    }

}