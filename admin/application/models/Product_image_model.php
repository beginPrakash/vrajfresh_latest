<?php
class product_image_model extends CI_Model
{

	public function add($ArrProductData)
	{

		$this->db->insert('tbl_product_images', $ArrProductData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}


	public function delete($product_image_id)
	{
		$this->delete_image($product_image_id);
		$this->db->where('product_image_id', $product_image_id);
		$this->db->delete('tbl_product_images');
		return true;
	}

	public function delete_image($product_image_id)
	{
		$Arrdata = $this->getProductImageUsingId($product_image_id);
		if (isset($Arrdata)) {
			$product_image = $Arrdata[0]['image'];
			if (trim($product_image) != "") {
				$filename = 'uploads/products/' . $product_image;
				if (file_exists($filename)) {
					unlink($filename);
				}
			}
			return true;
		} else {
			return FALSE;
		}
	}

	public function getProductImages($product_id)
	{
		if ($product_id > 0) {
			$ArrProductData = array();
			$this->db->select("*");
			$this->db->from('tbl_product_images');
			$this->db->where("tbl_product_images.product_id", $product_id);
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
	public function getProductImageUsingId($product_image_id)
	{
		if ($product_image_id > 0) {
			$ArrProductData = array();
			$this->db->select("*");
			$this->db->from('tbl_product_images');
			$this->db->where("tbl_product_images.product_image_id", $product_image_id);
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