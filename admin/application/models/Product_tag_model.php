<?php
class product_tag_model extends CI_Model
{

	public function add($ArrProductTag)
	{

		$this->db->insert('tbl_product_tags_mapping', $ArrProductTag);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}
	public function update($product_tags_mapping_id, $ArrProductTag)
	{
		$this->db->where('product_tags_mapping_id', $product_tags_mapping_id);
		$update = $this->db->update('tbl_product_tags_mapping', $ArrProductTag);
		return $this->db->affected_rows();
	}

	public function delete($product_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->delete('tbl_product_tags_mapping');
		return true;
	}



	public function getProductTags($product_id)
	{
		if ($product_id > 0) {
			$ArrProductData = array();
			$this->db->select("tbl_tag_master.tag,tbl_tag_master.tag_id");
			$this->db->from('tbl_product_tags_mapping');
			$this->db->join('tbl_tag_master', 'tbl_tag_master.tag_id = tbl_product_tags_mapping.tag_id', 'left');
			$this->db->where("tbl_product_tags_mapping.product_id", $product_id);
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return array();
			}
		} else {
			return array();
		}
	}


	public function getAllTags()
	{
		$ArrProductData = array();
		$this->db->select("tbl_tag_master.tag,tbl_tag_master.tag_id");
		$this->db->from('tbl_tag_master');
		$this->db->where("is_deleted", 0);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}




} //:)
?>