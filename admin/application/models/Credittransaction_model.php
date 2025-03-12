<?php

class Credittransaction_model extends CI_Model

{
	public function getCredittransdetails($id,$type)

	{

		$this->db->select("amount");
		$this->db->from('tbl_credit_transaction');
        $this->db->where('order_id',$id);
		$this->db->where('type',$type);
		$query = $this->db->get()->row();

		if (isset($query)) {
			return $query->amount;

		} else {

			return 0;

		}

	}

	public function getCreditperbycreditid($id)

	{

		$this->db->select("cash_credit_id");
		$this->db->from('tbl_credit_transaction');
        $this->db->where('order_id',$id);
		$this->db->where('type','earned');
		$query = $this->db->get()->row();

		if (isset($query)) {
			$credit_id = $query->cash_credit_id;
			$this->db->select("credit_per");
			$this->db->from('tbl_cash_credit');
			$this->db->where('credit_id',$credit_id);
			$query = $this->db->get()->row();
			if (isset($query)) {
				return $query->credit_per;
			} else {

				return 0;
		
			}
		}

	}

	public function update_earned_credittrans($data, $order_id)
    {
        $this->db->where('order_id', $order_id)->where('type','earned');
        $update = $this->db->update('tbl_credit_transaction', $data);
        return $update;
    }



}



?>