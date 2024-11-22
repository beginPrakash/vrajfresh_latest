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
        $query = $this->db->select('*')->where('customer_id', $customer_id)->get('tbl_cart_items');
        $data = $query->result();
		
		$this->db->where('customer_id', $customer_id);
		$this->db->delete('tbl_cart_items');
		
		return $data;
    }
    public function update($data, $row_id)
    {
        $this->db->where('row_id', $row_id);
        $update = $this->db->update('tbl_cart_items', $data);
        return $update;
    }
	public function delete($row_id)
	{
		$this->db->where('row_id', $row_id);
		$this->db->delete('tbl_cart_items');
		return true;
	}

    public function delete_all($customer_id)
	{
		$this->db->where('customer_id', $customer_id);
		$this->db->delete('tbl_cart_items');
		return true;
	}
}