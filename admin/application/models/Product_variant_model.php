<?php
class product_variant_model extends CI_Model
{

	public function add($ArrProductData)
	{

		$this->db->insert('tblproduct_variant', $ArrProductData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}


	public function decreaseQTY($product_id, $qty, $product_variant_color = '', $product_variant_size = '')
	{
		$sql = "UPDATE `tblproduct_variant` SET `variant_qty` = variant_qty-$qty WHERE `product_id` = $product_id";
		if ($product_variant_color != '')
			$sql .= " AND `product_variant_color` = '$product_variant_color'";
		if ($product_variant_color != '')
			$sql .= " AND `product_variant_size` = '$product_variant_size'";
		//echo $sql;exit;
		$query = $this->db->query($sql);

	}
	public function getVariantProductPrice($product_id, $product_variant_color = '', $product_variant_size = '')
	{
		$sql = "SELECT * FROM `tblproduct_variant` WHERE `product_id` = $product_id";
		if ($product_variant_color != '')
			$sql .= " AND `product_variant_color` = '$product_variant_color'";
		if ($product_variant_color != '')
			$sql .= " AND `product_variant_size` = '$product_variant_size'";
		//echo $sql;exit;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}
	public function deleteProductVariant($product_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->delete('tblproduct_variant');
		return true;
	}


	public function getProductVariants($product_id)
	{
		if ($product_id > 0) {
			$ArrProductData = array();
			$this->db->select("*");
			$this->db->from('tblproduct_variant');
			$this->db->where("tblproduct_variant.product_id", $product_id);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return array();
			}
		} else {
			return array();
		}
	}

} //:)
?>