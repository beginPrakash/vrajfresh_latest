<?php
class Cart_model extends CI_Model
{
    public function add($data)
    {
        $this->db->insert('tbl_cart_items', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }
    public function get_cart_by_customer($customer_id)
    {
        $query = $this->db->select('cart_item_id,row_id,customer_id,name as product_name,image as product_image,price,qty as quantity,product_slug,is_perisible,id as product_id,product_tax,options_weight as weight,options_variant_id as variant_id,customer_id')->where('customer_id', $customer_id)->get('tbl_cart_items');
        $data = $query->result();
		return $data;
    }
    public function update($data, $row_id)
    {
        $this->db->where('cart_item_id', $row_id);
        $update = $this->db->update('tbl_cart_items', $data);
        return $update;
    }
	public function delete($row_id)
	{
		$this->db->where('cart_item_id', $row_id);
		$this->db->delete('tbl_cart_items');
		return true;
	}

    public function delete_all($customer_id)
	{
		$this->db->where('customer_id', $customer_id);
		$this->db->delete('tbl_cart_items');
		return true;
	}

    public function userid_by_token($user_token='')
    {

        $result = $this->db->get_where('tbl_users_token', ['access_token' => $user_token])->row();

        return $result->user_id;
    }

    public function is_product_addtocart($product_id='',$customer_id='')
    {

        $result = $this->db->get_where('tbl_cart_items', ['customer_id' => $customer_id,'id' => $product_id])->row();

        return $result->cart_item_id ?? '';
    }

    
}