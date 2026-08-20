<?php

class report_about_order_model extends CI_Model

{



	public function add($ArrNotificationData)

	{



		$this->db->insert('tbl_order_complains', $ArrNotificationData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}



	}



	public function update($order_complain_id, $ArrNotificationData)

	{

		$this->db->where('order_complain_id', $order_complain_id);

		$update = $this->db->update('tbl_order_complains', $ArrNotificationData);

		return $this->db->affected_rows();

	}



	public function delete($order_complain_id)

	{

		$this->db->where('order_complain_id', $order_complain_id);

		// $this->db->update('tbl_order_complains', array('is_deleted'=>1));

		$this->db->delete('tbl_order_complains');

		if ($this->db->affected_rows() > 0) {

			return true;

		} else {

			return false;

		}

	}



	public function getReportAboutOrderById($order_complain_id)

	{

		$this->db->select("*");

		$this->db->from('tbl_order_complains');

		$this->db->join('tbl_users', 'tbl_users.user_id = tbl_order_complains.user_id', 'left');

		$this->db->where('order_complain_id', $order_complain_id);

		$this->db->where('tbl_order_complains.is_deleted', 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array()[0];

		}

	}





	public function getReportAboutOrderUsingQueryString($searchString)

	{

		$this->db->select("*");

		$this->db->from('tbl_order_complains');

		$this->db->where('is_deleted', 0);

		if ($searchString != '') {

			$this->db->where($searchString, NULL, FALSE);

		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



	public function getReportAboutOrderListData()

	{



		$column_order = array(null, 'tbl_order_complains.order_complain_id', 'tbl_order_complains.order_id', 'tbl_order_complains.created_datetime', 'tbl_users.namdisplay_namee', 'tbl_users.email', 'tbl_users.phone');



		$aColumns = array('tbl_order_complains.order_complain_id', 'tbl_order_complains.order_id', 'tbl_order_complains.created_datetime', 'tbl_users.display_name', 'tbl_users.email', 'tbl_users.phone');



		$column_search = array('tbl_order_complains.order_complain_id', 'tbl_order_complains.created_datetime', 'tbl_users.name', 'tbl_users.email', 'tbl_users.phone', 'tbl_order_complains.complain');



		$sTable = 'tbl_order_complains';

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

		if (@$_POST['txtSearchFrom'] != "" && @$_POST['txtSearchTo'] != "") {

			$this->db->where('DATE(tbl_order_complains.created_datetime) >=', date('Y-m-d', strtotime(@$_POST['txtSearchFrom'])));

			$this->db->where('DATE(tbl_order_complains.created_datetime) <=', date('Y-m-d', strtotime(@$_POST['txtSearchTo'])));

		}

		if (@$_POST['txtSearchFrom'] != "" && @$_POST['txtSearchTo'] == "") {

			$this->db->where('DATE(tbl_order_complains.created_datetime) >=', date('Y-m-d', strtotime(@$_POST['txtSearchFrom'])));

		}

		if (@$_POST['txtSearchTo'] != "" && @$_POST['txtSearchFrom'] == "") {

			$this->db->where('DATE(tbl_order_complains.created_datetime) <=', date('Y-m-d', strtotime(@$_POST['txtSearchTo'])));

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

				$this->db->order_by('tbl_order_complains.order_complain_id', 'desc');

			}

			$this->db->where('tbl_order_complains.is_deleted', 0);

			$this->db->join('tbl_users', 'tbl_users.user_id = tbl_order_complains.user_id', 'left');

			$rResult = $this->db->get($sTable);

			//echo '<pre>'; print_r( $this->db->last_query() );exit;

			$this->db->select('FOUND_ROWS() AS found_rows');

			$iFilteredTotal = $this->db->get()->row()->found_rows;

			$iTotal = $this->db->count_all($sTable);



			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());



		}



	}



	public function ExportReportAboutOrderData($Arr)

	{



		$aColumns = array('tbl_order_complains.order_complain_id', 'tbl_order_complains.created_datetime', 'tbl_order_complains.contact_first_name', 'tbl_order_complains.contact_company_name', 'tbl_order_complains.contact_email', 'tbl_order_complains.contact_phone_number', 'contact_domain_name', 'contact_country_name', 'tbl_order_complains.ip_address', 'tbl_order_complains.contact_last_name');



		$column_search = array('tbl_order_complains.order_complain_id', 'tbl_order_complains.created_datetime', 'tbl_order_complains.contact_first_name', 'tbl_order_complains.contact_company_name', 'tbl_order_complains.contact_email', 'tbl_order_complains.contact_phone_number', 'contact_domain_name', 'contact_country_name', 'tbl_order_complains.ip_address', 'tbl_order_complains.contact_last_name');



		$sTable = 'tbl_order_complains';

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

		$this->db->group_by('tbl_order_complains.order_complain_id');



		$this->db->where('is_deleted', 0);

		$this->db->order_by('tbl_order_complains.order_complain_id', 'desc');

		$rResult = $this->db->get($sTable);

		//echo $this->db->last_query();exit;

		return $rResult->result_array();

	}

	public function UpdateReadReportAboutOrderStatus(){
		$this->db->where('is_read', 1);

		$this->db->update('tbl_order_complains', array('is_read'=>0));
		if ($this->db->affected_rows() > 0) {

			return true;

		} else {

			return false;

		}
	}

}



?>