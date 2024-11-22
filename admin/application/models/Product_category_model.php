<?php
class product_category_model extends CI_Model
{

	public function add($ArrProductCategoryData)
	{

		$this->db->insert('tbl_categories', $ArrProductCategoryData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($category_id, $ArrProductCategoryData)
	{
		$this->db->where('category_id', $category_id);
		$update = $this->db->update('tbl_categories', $ArrProductCategoryData);
		return $this->db->affected_rows();
	}

	public function delete($category_id)
	{
		$this->db->where('category_id', $category_id);
		// $this->db->update('tbl_categories', array('is_deleted'=>1));
		$this->db->delete('tbl_categories');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getProductCategoryUsingID($category_id)
	{
		$this->db->select("*");
		$this->db->from('tbl_categories');
		$this->db->where('category_id', $category_id);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function checkDuplicate($category_id, $category_name)
	{
		$query = $this->db->query("SELECT * FROM `tbl_categories` WHERE `category_name` = '" . $category_name . "' and `category_id` != '" . $category_id . "'");
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getProductCategoryQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tbl_categories');
		$this->db->where('is_deleted', 0);
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}



}

?>