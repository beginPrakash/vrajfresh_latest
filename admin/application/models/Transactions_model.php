<?php
class transactions_model extends CI_Model
{

	public function add($ArrOrderData)
	{

		$this->db->insert('tbl_transactions', $ArrOrderData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($transaction_id, $ArrOrderData)
	{
		$this->db->where('transaction_id', $transaction_id);
		$update = $this->db->update('tbl_transactions', $ArrOrderData);
		return $this->db->affected_rows();
	}
	public function getTransactionsBYOrderId($order_id, $parm = "*")
	{
		$this->db->select('tbl_transactions.' . $parm );
		$this->db->from('tbl_transactions');
		$this->db->where('order_id', $order_id);
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

}
?>