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

	public function getTransactionsBYOrderIdSingle($order_id, $parm = "*")
	{
		$this->db->select('transaction_amount');
		$this->db->from('tbl_transactions');
		$this->db->where('order_id', $order_id);
		$this->db->order_by('transaction_id', 'ASC'); // Ascending order
		$this->db->limit(1); // Only first row

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->transaction_amount; // Single row
		} else {
			return false;
		}
	}


	public function getRefundTransactionsBYOrderIdSingle($order_id, $parm = "*")
	{
		$this->db->select('payment_refund_id');
		$this->db->from('tbl_transactions');
		$this->db->where('order_id', $order_id);
		$this->db->where('payment_type', 'refund');
		$this->db->order_by('transaction_id', 'ASC'); // Ascending order
		$this->db->limit(1); // Only first row

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->payment_refund_id; // Single row
		} else {
			return false;
		}
	}



}

?>