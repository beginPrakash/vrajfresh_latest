<?php
class promotional_code_product_model extends CI_Model
{

	public function add($ArrPromotionalCodeData)
	{

		$this->db->insert('tblpromotional_code_product', $ArrPromotionalCodeData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($promotional_code_product_id, $ArrPromotionalCodeData)
	{
		$this->db->where('promotional_code_product_id', $promotional_code_product_id);
		$update = $this->db->update('tblpromotional_code_product', $ArrPromotionalCodeData);
		return $this->db->affected_rows();
	}

	public function delete($promotional_code_product_id)
	{
		$this->db->where('promotional_code_product_id', $promotional_code_product_id);
		// $this->db->update('tblpromotional_code_product', array('is_deleted'=>1));
		$this->db->delete('tblpromotional_code_product');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getPromotionalCodeProductUsingID($promotional_code_product_id)
	{
		$this->db->select("*");
		$this->db->from('tblpromotional_code_product');
		$this->db->where('promotional_code_product_id', $promotional_code_product_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function getPromotionalCodeProductQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tblpromotional_code_product');
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