<?php
class Product_model extends CI_Model {

   public function getProductDetail($product_slug)

	{

		$this->db->select('*');

		$this->db->from('tbl_products');

		$this->db->where('is_deleted', 0);

		$this->db->where('product_slug', $product_slug);

		$query = $this->db->get();

		return $query->result_array();

	}

	public function getBrandname($brand_id)

	{

		$this->db->select('brand_name','brand_id');

		$this->db->from('tbl_brands');

		$this->db->where('brand_id', $brand_id);

		$query = $this->db->get();

		$brand_data = $query->result_array();
		return $brand_data[0]['brand_name'] ?? '';

	}
}
