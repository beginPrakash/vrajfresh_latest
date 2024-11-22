<?php
class product_review_model extends CI_Model
{

	public function add($ArrProductReviewData)
	{

		$this->db->insert('tblproduct_review', $ArrProductReviewData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($product_review_id, $ArrProductReviewData)
	{
		$this->db->where('product_review_id', $product_review_id);
		$update = $this->db->update('tblproduct_review', $ArrProductReviewData);
		return $this->db->affected_rows();
	}

	public function delete($product_review_id)
	{
		$this->db->where('product_review_id', $product_review_id);
		// $this->db->update('tblproduct_review', array('is_deleted'=>1));
		$this->db->delete('tblproduct_review');
		return true;
	}

	public function getProductReviewByOrderId($order_id, $parm = "*")
	{
		$this->db->select($parm);
		$this->db->from('tblproduct_review');
		$this->db->where('order_id', $order_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	public function getProductReviewQueryString($searchString = '', $whrArray = array('is_active' => '1'))
	{
		$this->db->select("*");
		$this->db->from('tblproduct_review');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		if (is_array($whrArray) && count($whrArray) > 0)
			$this->db->where($whrArray);

		$this->db->order_by('tblproduct_review.product_review_id', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

}
?>