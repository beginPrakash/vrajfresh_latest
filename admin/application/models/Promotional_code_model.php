<?php
class promotional_code_model extends CI_Model
{

	public function add($ArrPromotionalCodeData)
	{

		$this->db->insert('tblpromotional_code', $ArrPromotionalCodeData);
		//echo '<pre>'; echo $this->db->last_query();exit;		
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($promotional_code_id, $ArrPromotionalCodeData)
	{
		$this->db->where('promotional_code_id', $promotional_code_id);
		$update = $this->db->update('tblpromotional_code', $ArrPromotionalCodeData);
		//echo '<pre>'; echo $this->db->last_query();exit;
		return $this->db->affected_rows();
	}

	public function delete($promotional_code_id)
	{
		$this->db->where('promotional_code_id', $promotional_code_id);
		// $this->db->update('tblpromotional_code', array('is_deleted'=>1));
		$this->db->delete('tblpromotional_code');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getPromotionalCodeListData($Arr)
	{

		$column_order = array(null, 'tblpromotional_code.promotional_code_id', 'tblpromotional_code.created_datetime', 'tblpromotional_code.promotional_code', 'tblpromotional_code.description', 'tblpromotional_code.start_from', 'tblpromotional_code.valid_upto', 'tblpromotional_code.promotional_type', 'tblpromotional_code.apply_to', 'tblpromotional_code.apply_to_product', 'tblpromotional_code.is_active');

		$aColumns = array('tblpromotional_code.promotional_code_id', 'tblpromotional_code.created_datetime', 'tblpromotional_code.promotional_code', 'tblpromotional_code.description', 'tblpromotional_code.start_from', 'tblpromotional_code.valid_upto', 'tblpromotional_code.promotional_type', 'tblpromotional_code.apply_to', 'tblpromotional_code.apply_to_product', 'tblpromotional_code.is_active');

		$column_search = array('promotional_code');

		$sTable = 'tblpromotional_code';


		/* search by keyword */
		$i = 0;
		foreach ($column_search as $item) { /*loop column */
			if (@$_POST['search']['value'] || @$_POST['txtSearchKeyWord']) /*if datatable send POST for search*/{
				if ($i === 0) {
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					if ($_POST['search']['value'] && $_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['search']['value']);
						$this->db->or_like($item, $_POST['txtSearchKeyWord']);
					} else if ($_POST['search']['value'] && !$_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['search']['value']);
					} else if (!$_POST['search']['value'] && $_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['txtSearchKeyWord']);
					}
				} else {
					if ($_POST['search']['value']) {
						$this->db->or_like($item, $_POST['search']['value']);
					}
					if ($_POST['txtSearchKeyWord']) {
						$this->db->or_like($item, $_POST['txtSearchKeyWord']);
					}
				}
				if (count($column_search) - 1 == $i) {
					$this->db->group_end(); /*close bracket*/
				}
			}
			$i++;
		}
		if (@$_REQUEST['promotional_type'] != "") {
			$this->db->where('tblpromotional_code.promotional_type', $_REQUEST['promotional_type']);
		}
		if (@$_REQUEST['promotional_code_for'] != "") {
			$this->db->where('tblpromotional_code.apply_to', $_REQUEST['promotional_code_for']);
		}
		if (@$_REQUEST['select_products'] != "") {
			$this->db->where('tblpromotional_code.apply_to_product', $_REQUEST['select_products']);
		}

		/* end */
		if (@$_REQUEST['columns'] != "") {
			// set columns start
			if (@$_REQUEST['columns'] != "") {
				if (@$_REQUEST['length'] != -1) {
					$this->db->limit($_REQUEST['length'], $_REQUEST['start']);
				}
			}

			// Select Data
			$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);




			if (isset($_POST['order'])) { // here order processing
				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else {
				$this->db->order_by('tblpromotional_code.promotional_code_id', 'desc');
			}
			$this->db->where('tblpromotional_code.is_deleted', 0);
			$rResult = $this->db->get($sTable);
			//echo '<pre>'; print_r( $this->db->last_query() );exit;
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}

	public function ExportPromotionalCodeData($Arr)
	{

		$aColumns = array('tblpromotional_code.promotional_code_id', 'tblpromotional_code.created_datetime', 'tblpromotional_code.promotional_code', 'tblpromotional_code.description', 'tblpromotional_code.start_from', 'tblpromotional_code.valid_upto', 'tblpromotional_code.promotional_type', 'tblpromotional_code.apply_to', 'tblpromotional_code.apply_to_product', 'tblpromotional_code.is_active');

		$column_search = array('promotional_code');

		$sTable = 'tblpromotional_code';
		/* search by keyword */
		$i = 0;
		foreach ($column_search as $item) { /*loop column */
			if ($_POST['txtSearchKeyWord']) /*if datatable send POST for search*/{
				if ($i === 0) {
					$this->db->group_start();
					if ($_POST['txtSearchKeyWord']) {
						$this->db->like($item, $_POST['txtSearchKeyWord']);
					}
				} else {
					if ($_POST['txtSearchKeyWord']) {
						$this->db->or_like($item, $_POST['txtSearchKeyWord']);
					}
				}
				if (count($column_search) - 1 == $i) {
					$this->db->group_end(); /*close bracket*/
				}
			}
			$i++;
		}
		/* end */
		$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);


		if (@$_REQUEST['promotional_type'] != "") {
			$this->db->where('tblpromotional_code.promotional_type', $_REQUEST['promotional_type']);
		}
		if (@$_REQUEST['promotional_code_for'] != "") {
			$this->db->where('tblpromotional_code.apply_to', $_REQUEST['promotional_code_for']);
		}
		if (@$_REQUEST['select_products'] != "") {
			$this->db->where('tblpromotional_code.apply_to_product', $_REQUEST['select_products']);
		}
		$this->db->order_by('tblpromotional_code.promotional_code_id', 'desc');
		$rResult = $this->db->get($sTable);
		//echo '<pre>'; print_r( $this->db->last_query() );exit;
		return $rResult->result_array();

	}

	public function get_promotional_code_as_array($condition = '')
	{
		$this->db->select('*');
		$this->db->from('tblpromotional_code');
		if ($condition != '')
			$this->db->where($condition, NULL, FALSE);

		$query_result = $this->db->get();
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

	public function getPromotionalCodeUsingID($promotional_code_id)
	{
		$this->db->select("*");
		$this->db->from('tblpromotional_code');
		$this->db->where('promotional_code_id', $promotional_code_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function checkDuplicate($promotional_code_id, $promotional_code)
	{
		$query = $this->db->query("SELECT * FROM `tblpromotional_code` WHERE `promotional_code` = '" . $promotional_code . "' and `promotional_code_id` != '" . $promotional_code_id . "'");
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getPromotionalCodeQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tblpromotional_code');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function getPromotionalCodeAllDetailsById($promotional_code_id)
	{
		$query = $this->db->query("SELECT tblpromotional_code.promotional_code_id,tblpromotional_code.created_datetime,tblpromotional_code.promotional_code,tblpromotional_code.description,tblpromotional_code.start_from,tblpromotional_code.valid_upto,tblpromotional_code.promotional_type,tblpromotional_code.apply_to,tblpromotional_code.apply_to_product,tblpromotional_code.is_active FROM `tblpromotional_code` where `tblpromotional_code`.`promotional_code_id` = $promotional_code_id");
		//echo '<pre>'; print_r( $this->db->last_query());//exit; 
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	public function get_all_products()
	{
		$this->db->select("*");
		$this->db->from('tbl_products');
		$this->db->where('is_active', '1');

		$this->db->order_by("product_name", "asc");
		$query = $this->db->get();
		//echo '<pre>'; print_r( $this->db->last_query() );//exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function getSelectedProduct($promotional_code_id)
	{
		$this->db->select('tblpromotional_code_product.product_id,tbl_products.product_name,tblpromotional_code_product.discount_value,tblpromotional_code_product.discount_type');
		$this->db->from('tblpromotional_code_product');
		$this->db->join('tbl_products', 'tbl_products.product_id = tblpromotional_code_product.product_id', 'JOIN');
		$this->db->order_by("product_name", "asc");
		$this->db->where('tblpromotional_code_product.promotional_code_id', $promotional_code_id);

		$query_result = $this->db->get();
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
			return array();
		}

	}

	/**************************************** PROMOTIONAL CODE PRODUCTS *******************************************************/
	public function addPromotionalCodeProduct($promotional_code_client_group_data)
	{
		$insert = $this->db->insert('tblpromotional_code_product', $promotional_code_client_group_data);
		return $this->db->insert_id();
	}
	public function get_promotional_code_product($promotional_code_id)
	{
		$this->db->select('tblpromotional_code_product.product_id,tblclientgroup.clientgroup_title');
		$this->db->from('tblpromotional_code_product');
		$this->db->join('tblclientgroup', 'tblclientgroup.product_id = tblpromotional_code_product.product_id', 'JOIN');
		$this->db->order_by("clientgroup_title", "asc");

		$query_result = $this->db->get();

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

	function delete_promotional_code_product($promotional_code_id)
	{
		if ($promotional_code_id > 0) {
			$this->db->where('promotional_code_id', $promotional_code_id);
			$this->db->delete('tblpromotional_code_product');
		}
	}
	function check_product_discount($product_id, $promotional_code_id, $product_amount, $qty = 1)
	{
		//echo $product_id.":".$promotional_code_id.":".$product_amount;
		$this->db->select('*');
		$this->db->from('tblpromotional_code_product');
		$this->db->where('tblpromotional_code_product.product_id', $product_id);
		$this->db->where('tblpromotional_code_product.promotional_code_id', $promotional_code_id);
		$query_result = $this->db->get();

		if ($query_result->num_rows() > 0) {
			$i = 0;
			foreach ($query_result->result_array() as $row) {
				foreach ($row as $key => $val) {
					$ArrTemp[$key] = $val;
				}
				$sdata[$i++] = $ArrTemp;
			}
			$ArrProductCouponDetails = $sdata[0];
			$product_wise_discount = 0;
			if ($ArrProductCouponDetails['discount_type'] == '$') {
				$product_wise_discount = $ArrProductCouponDetails['discount_value'];
			} elseif ($ArrProductCouponDetails['discount_type'] == '%') {
				$product_wise_discount = (($product_amount * $qty * $ArrProductCouponDetails['discount_value']) / 100);
			}
		} else {
			$product_wise_discount = 0;
		}
		//echo $product_wise_discount;exit;
		return round($product_wise_discount);
	}
	#END

	/**************************************** PROMOTIONAL CODE CLIENT GROUP *******************************************************/
	public function addPromotionalCodeClientGroup($promotional_code_client_group_data)
	{

		$insert = $this->db->insert('tblpromotional_code_client_group', $promotional_code_client_group_data);
		return $this->db->insert_id();
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

	function delete_promotional_code_client_group($promotional_code_id)
	{
		if ($promotional_code_id > 0) {
			$this->db->where('promotional_code_id', $promotional_code_id);
			$this->db->delete('tblpromotional_code_client_group');
		}
	}
	#END
	/* get coupon code details using code*/
	public function get_promotional_code_by_promotional_code($promotional_code)
	{
		$this->db->select('*');
		$this->db->from('tblpromotional_code');
		$this->db->where('promotional_code', $promotional_code);

		$query = $this->db->get();
		//echo "<br><br>count". $this->db->last_query();exit;
		return $query->result_array();
	}

	/* check in client group - user eligible or not */
	public function eligible($user_id, $promotional_code_id)
	{
		$this->db->select('*');
		$this->db->from('tblpromotional_code_client_group');
		$this->db->join('tblclientgroup_details', 'tblclientgroup_details.clientgroup_id = tblpromotional_code_client_group.clientgroup_id', 'JOIN');
		$this->db->where('tblpromotional_code_client_group.promotional_code_id', $promotional_code_id);
		$this->db->where('tblclientgroup_details.user_id', $user_id);

		$query = $this->db->get();
		$Arr = $query->result_array();
		if (is_array($Arr) && count($Arr) > 0) {
			return true;
		} else {
			return false;
		}
	}

	/* check promo code in user's order details - already used or not */
	public function alReadyUsed($user_id, $coupon_code)
	{
		$this->db->select('*');
		$this->db->from('tblorder');
		if ($user_id > 0) {
			$this->db->where('customer_id', $user_id);
		}

		if ($coupon_code != '') {
			$this->db->where('coupon_code', $coupon_code);
		}
		$query = $this->db->get();
		$Arr = $query->result_array();
		if (is_array($Arr) && count($Arr) > 0) {
			return false;
		} else {
			return true;
		}
	}

	public function getPromotionalCodeAsArray($condition = '')
	{
		$this->db->select('*');
		$this->db->from('tblpromotional_code');
		if ($condition != '')
			$this->db->where($condition, NULL, FALSE);

		$query_result = $this->db->get();
		if ($query_result->num_rows() > 0) {
			$i = 0;
			foreach ($query_result->result_array() as $row) {
				//print_r($row);
				foreach ($row as $key => $val) {
					$ArrTemp[$key] = $val;
				}
				$sdata[$i++] = $ArrTemp;
			}
			//return $query_result->result_array();
			return $sdata;
		} else {
			return false;
		}
	}

	#Get customr promotional_code
	public function getCustomersPromoCode($user_id)
	{
		$this->db->select('*');
		$this->db->select("DATE_FORMAT(valid_upto, '%d-%b') as format_valid_upto");
		$this->db->from('tblpromotional_code');
		$searchString = ' is_active="1" AND valid_upto>=now() AND ( apply_to="A" OR (apply_to="SG" AND promotional_code_id IN (SELECT promotional_code_id FROM tblpromotional_code_client_group WHERE clientgroup_id IN (SELECT clientgroup_id FROM tblclientgroup_details WHERE user_id=' . $user_id . ') ) ) OR (apply_to ="SC" AND specific_customer_id=' . $user_id . ') )';
		$this->db->where($searchString, NULL, FALSE);

		$query = $this->db->get();

		$Arr = $query->result_array();
		if (is_array($Arr) && count($Arr) > 0) {
			return $Arr;
		} else {
			return array();
		}
	}

}

?>