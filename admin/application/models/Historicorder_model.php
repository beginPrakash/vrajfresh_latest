<?php
class historicorder_model extends CI_Model
{

	public function getOrderById($order_id, $parm = "*")
	{
		$this->db->select($parm);
		$this->db->from('tbl_orders');
		$this->db->where('order_id', $order_id);
		$this->db->where('tbl_orders.is_deleted', 0);
		$this->db->join('tbl_users', 'tbl_users.user_id = tbl_orders.user_id', 'left');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
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


	public function getOrderListData()
	{

		// $this->db->select('tbl_historicorders.*');
		// $this->db->from('tbl_historicorders');
		// $this->db->order_by('tbl_historicorders.order_number', 'DESC');
		// $this->db->limit(10);
		// $query = $this->db->get();
		// if ($query->num_rows() > 0) {
		// 	return $query->result_array();
		// } else {
		// 	return false;
		// }

		$column_order = array(null, 'tbl_historicorders.order_number');

		$aColumns = array('tbl_historicorders.order_number', 'tbl_historicorders.order_status', 'tbl_historicorders.order_date', 'tbl_historicorders.customer_note', 'tbl_historicorders.first_name', 'tbl_historicorders.last_name', 'tbl_historicorders.company', 'tbl_historicorders.address', 'tbl_historicorders.city', 'tbl_historicorders.state_code', 'tbl_historicorders.post_code', 'tbl_historicorders.country_code', 'tbl_historicorders.email', 'tbl_historicorders.phone', 'tbl_historicorders.payment_method_title', 'tbl_historicorders.cart_discount_amount', 'tbl_historicorders.order_subtotal', 'tbl_historicorders.delivery_method_title', 'tbl_historicorders.order_delivery_amount', 'tbl_historicorders.order_refund_amount', 'tbl_historicorders.order_total_amount', 'tbl_historicorders.order_total_tax_amont', 'tbl_historicorders.sku', 'tbl_historicorders.item', 'tbl_historicorders.item_name', 'tbl_historicorders.quantity', 'tbl_historicorders.item_cost');

		$column_search = array('tbl_historicorders.order_number', 'tbl_historicorders.order_status', 'tbl_historicorders.order_date', 'tbl_historicorders.customer_note', 'tbl_historicorders.first_name', 'tbl_historicorders.last_name', 'tbl_historicorders.company', 'tbl_historicorders.address', 'tbl_historicorders.city', 'tbl_historicorders.state_code', 'tbl_historicorders.post_code', 'tbl_historicorders.country_code', 'tbl_historicorders.email', 'tbl_historicorders.phone', 'tbl_historicorders.payment_method_title', 'tbl_historicorders.cart_discount_amount', 'tbl_historicorders.order_subtotal', 'tbl_historicorders.delivery_method_title', 'tbl_historicorders.order_delivery_amount', 'tbl_historicorders.order_refund_amount', 'tbl_historicorders.order_total_amount', 'tbl_historicorders.order_total_tax_amont', 'tbl_historicorders.sku', 'tbl_historicorders.item', 'tbl_historicorders.item_name', 'tbl_historicorders.quantity', 'tbl_historicorders.item_cost');

		$sTable = 'tbl_historicorders';
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
				$this->db->order_by('tbl_historicorders.order_number', 'desc');
			}
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
}