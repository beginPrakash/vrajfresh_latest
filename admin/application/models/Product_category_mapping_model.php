<?php
class product_category_mapping_model extends CI_Model
{

	public function add($ArrProductCategory)
	{

		$this->db->insert('tbl_categories_products_mapping', $ArrProductCategory);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}
	public function update($category_product_id, $ArrProductCategory)
	{
		$this->db->where('category_product_id', $category_product_id);
		$update = $this->db->update('tbl_categories_products_mapping', $ArrProductCategory);
		return $this->db->affected_rows();
	}

	public function delete($product_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->delete('tbl_categories_products_mapping');
		return true;
	}



	public function getProductCategory($product_id)
	{
		if ($product_id > 0) {
			$ArrProductData = array();
			$this->db->select("category_id");
			$this->db->from('tbl_categories_products_mapping');
			$this->db->where("product_id", $product_id);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if ($query->num_rows() > 0) {
				$ArrData = $query->result_array();
				$ArrCate = array();
				foreach ($ArrData as $val) {
					$ArrCate[] = $val['category_id'];
				}
				return $ArrCate;
			} else {
				return array();
			}
		} else {
			return array();
		}
	}






} //:)
?>