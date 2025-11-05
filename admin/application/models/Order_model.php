<?php
class order_model extends CI_Model
{

	public function add($ArrOrderData)
	{

		$this->db->insert('tbl_orders', $ArrOrderData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($order_id, $ArrOrderData)
	{
		$this->db->where('order_id', $order_id);
		$update = $this->db->update('tbl_orders', $ArrOrderData);
		return $this->db->affected_rows();
	}

	public function delete($order_id)
	{
		$this->db->where('order_id', $order_id);
		// $this->db->update('tbl_orders', array('is_deleted'=>1));
		$this->db->delete('tbl_orders');
		return true;
	}

	public function getOrderById($order_id, $parm = "*")
	{
		$this->db->select('tbl_orders.' . $parm . ',tblpromotional_code.promotional_code,tblpromotional_code.discount_type as promo_dis_type,tblpromotional_code.maximum_order_discount as maximum_order_discount,tblpromotional_code.discount_value as promo_dis_val, stb.state as billing_state_name, sts.state as shipping_state_name, CASE 
        WHEN tbl_orders.payment_methodtype = "google_pay" THEN "Google Pay"
        WHEN tbl_orders.payment_methodtype = "apple_pay" THEN "Apple Pay"
        ELSE "Stripe Card"
    END as payment_methodtype', false);
		$this->db->from('tbl_orders');
		$this->db->where('order_id', $order_id);
		$this->db->where('tbl_orders.is_deleted', 0);
		$this->db->join('tbl_users', 'tbl_users.user_id = tbl_orders.user_id', 'left');
		$this->db->join('tblpromotional_code', 'tblpromotional_code.promotional_code_id  = tbl_orders.coupon_id ', 'left');
		$this->db->join('state stb', 'stb.state_id = tbl_orders.billing_state_id', 'left');
		$this->db->join('state sts', 'sts.state_id = tbl_orders.shipping_state_id', 'left');
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	public function getProductsByIds($ids)
	{

		$this->db->select('*');
		$this->db->from('tbl_products');
		$this->db->where_in('product_id', $ids);
		$query = $this->db->get();
		// echo $this->db->last_query();exit;

		$result = $query->result();
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
	}

	public function getOrderByUserId($user_id, $parm = "*")
	{
		$this->db->select($parm);
		$this->db->from('tbl_orders');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}



	public function getOrderQueryString($searchString = '', $whrArray = array('is_active' => '1'))
	{
		$this->db->select("*");
		$this->db->from('tbl_orders');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		if (is_array($whrArray) && count($whrArray) > 0)
			$this->db->where($whrArray);

		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}



	public function getOrderListData($Arr)
	{

		$column_order = array(null, 'tbl_orders.order_id', 'tbl_orders.order_datetime', 'tbl_users.display_name', 'tbl_users.mobile_no', 'tbl_orders.order_total_amount', 'tbl_orders.order_status', 'tbl_orders.is_active', 'tbl_orders.created_datetime');

		$aColumns = array('tbl_orders.order_id', 'tbl_orders.order_datetime', 'tbl_users.display_name', 'tbl_users.mobile_no', 'tbl_orders.order_total_amount', 'tbl_orders.order_status', 'tbl_orders.is_active', 'tbl_orders.created_datetime');

		$column_search = array('tbl_orders.order_id', 'tbl_users.display_name', 'tbl_users.first_name', 'tbl_users.last_name', 'tbl_users.email', 'tbl_orders.order_status', 'tbl_users.mobile_no');

		$sTable = 'tbl_orders';
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
		/* end */

		if (@$_REQUEST['txtSearchFrom'] != "" && @$_REQUEST['txtSearchTo'] != "") {
			$txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
			$txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
			$this->db->where('tbl_orders.order_datetime >=', $txtSearchFrom . " 00:00:00");
			$this->db->where('tbl_orders.order_datetime <=', $txtSearchTo . " 23:59:59");
		}
		if (@$_REQUEST['txtSearchFrom'] != "") {
			$txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
			$this->db->where('tbl_orders.order_datetime >=', $txtSearchFrom . " 00:00:00");
		}
		if (@$_REQUEST['txtSearchTo'] != "") {
			$txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
			$this->db->where('tbl_orders.order_datetime <=', $txtSearchTo . " 23:59:59");
		}
		if (@$_REQUEST['ddIsActive'] != "") {
			$this->db->where('tbl_orders.order_status', $_REQUEST['ddIsActive']);
		}


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
				$this->db->order_by('tbl_orders.user_id', 'desc');
			}
			$this->db->where('tbl_orders.is_deleted', 0);
			$this->db->join('tbl_users', 'tbl_users.user_id = tbl_orders.user_id', 'left');
			$rResult = $this->db->get($sTable);
			//echo '<pre>'; print_r( $this->db->last_query() );exit;
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}
	public function getOrderCount($arraywhr)
	{

		$sql = "SELECT count(*) as order_count FROM `tbl_orders` WHERE " . $arraywhr;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->result_array()[0]['order_count'];
		}
	}

	public function getMonthlyOrderNumbers()
	{

		$sql = "SELECT MONTHNAME(created_datetime) as month,count(*) as order_count,SUM(order_total_amount) as order_total FROM `tbl_orders` WHERE 1=1 AND created_datetime >= DATE_FORMAT(NOW() - INTERVAL 11 MONTH, '%Y-%m-01') AND created_datetime <= LAST_DAY(NOW())
  GROUP BY MONTH(created_datetime)";
  $query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function getCategoryWiseProductSale()
	{

		$sql = "SELECT MONTHNAME(created_datetime) as month,count(*) as order_count,SUM(order_total_amount) as order_total FROM `tbl_orders` WHERE 1=1 GROUP BY MONTH(created_datetime)";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function getTotalOrderProductsSum($order_id)
	{

		$sql = "SELECT SUM(total_amount) as total_amount FROM `tbl_order_products` WHERE order_id=$order_id GROUP BY order_id";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}
}
?>