<?php
class Coupons_model extends CI_Model
{
	/* check promo code in user's order details - already used or not */
	public function alReadyUsed($user_id, $coupon_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_orders');
		if ($user_id > 0) {
			$this->db->where('user_id', $user_id);
		}
		if ($coupon_id != '') {
			$this->db->where('coupon_id', $coupon_id);
		}
		$query = $this->db->get();
		$Arr = $query->result_array();
		if (is_array($Arr) && count($Arr) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_coupon_by_code($data)
	{
		$query = $this->db->where('promotional_code', $data['coupon_code'])
			->where('is_active', '1')->where('is_deleted', '0')->where_in('coupon_for', array('website', 'all'))
			->get('tblpromotional_code');
		return $query->result();
	}

	public function get_coupon_by_code_admin($data)
	{
		$query = $this->db->where('promotional_code', $data['coupon_code'])->get('tblpromotional_code');
		return $query->result();
	}
	
	public function get_coupon_by_id($data)
	{
		$query = $this->db->where('promotional_code_id ', $data)
			->get('tblpromotional_code');
		return $query->result();
	}


	public function getPromotionalCodeClientGroup($promotional_code_id)
	{
		$this->db->select('tblpromotional_code_client_group.clientgroup_id');
		$this->db->from('tblpromotional_code_client_group');

		$this->db->where('promotional_code_id', $promotional_code_id);
		;
		$query_result = $this->db->get();
		//echo '<pre>'; print_r( $this->db->last_query() );exit;
		if ($query_result->num_rows() > 0) {
			$i = 0;
			foreach ($query_result->result_array() as $row) {
				foreach ($row as $key => $val) {
					$ArrTemp[$key] = $val;
				}
				$sdata[$i++] = $ArrTemp;
			}
			return $sdata;
		} else {
			return false;
		}

	}

	public function cust_order_count($user_id)
	{
		$this->db->select('COUNT(*) AS total');
		$this->db->from('tbl_orders');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		$result = $query->row();
		return $result->total;

	}


	public function getGroupCustomerIds($ArrClientGroup='')
	{
		if(!empty($ArrClientGroup)){
			$this->db->select("tblclientgroup_details.user_id");
			$this->db->from('tblclientgroup_details');
			$this->db->where_in('clientgroup_id', $ArrClientGroup);

			$query_result = $this->db->get();
			if ($query_result->num_rows() > 0) {
				$i = 0;
				foreach ($query_result->result_array() as $row) {
					foreach ($row as $key => $val) {
						$sdata[$i++] = $val;
					}
				}
				return $sdata;
			} else {
				return false;
			}
		}else{
			return false;
		}
	}


}