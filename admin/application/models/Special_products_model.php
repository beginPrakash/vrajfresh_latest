<?php
class special_products_model extends CI_Model
{



	public function update($special_product_id, $ArrData)
	{
		$this->db->where('special_product_id', $special_product_id);
		$update = $this->db->update('tbl_special_products', $ArrData);
		return $this->db->affected_rows();
	}

	public function getSpecialProducts()
	{
		$this->db->select("*");
		$this->db->from('tbl_special_products');
		$this->db->where("is_active", 1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}



} // :)

?>