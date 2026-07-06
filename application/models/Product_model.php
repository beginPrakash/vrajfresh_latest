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


	public function getCategoryMeta($category_slug='')

	{

		$this->db->select('*');

		$this->db->from('category_meta');

		$this->db->where('category_slug', $category_slug);

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

	public function getCategoryname($product_id)

	{

		$this->db->select('c.category_id, c.category_name');
		$this->db->from('tbl_categories_products_mapping cp');
		$this->db->join('tbl_categories c', 'c.category_id = cp.category_id');
		$this->db->where('cp.product_id', $product_id);

		$query = $this->db->get();
		$category_data = $query->result_array();
		return $category_data[0]['category_name'] ?? '';

	}

	    public function checkProductStock($product_id = 0, $variant_id = 0)
{
    // Check Variant Product
    if (!empty($variant_id) && !empty($product_id)) {

        $product_data = $this->db
            ->select('is_out_of_stock')
            ->from('tblproduct_variant')
            ->where('id', $variant_id)
			->where('product_id', $product_id)
            ->get()
            ->row();

	}else{
		$product_data = $this->db
            ->select('product_id,is_out_of_stock')
            ->from('tbl_products')
            ->where('product_id', $product_id)
            ->where('is_deleted', 0)
            ->get()
            ->row();
	}
return $product_data;
}
}
