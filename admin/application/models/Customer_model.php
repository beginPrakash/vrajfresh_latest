<?php
class customer_model extends CI_Model
{

	public function add($ArrCustomerData)
	{

		$this->db->insert('tbl_users', $ArrCustomerData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($user_id, $ArrCustomerData)
	{
		$this->db->where('user_id', $user_id);
		$update = $this->db->update('tbl_users', $ArrCustomerData);
		return $this->db->affected_rows();
	}

	public function delete($user_id)
	{
		$this->db->where('user_id', $user_id);
		// $this->db->update('tbl_users', array('is_deleted'=>1));
		$this->db->delete('tbl_users');
		return true;
	}

	public function getCustomerUsingID($user_id)
	{
		$ArrCustomerData = array();
		$this->db->select("tbl_users.*, stb.state as billing_state_name, sts.state as shipping_state_name");
		$this->db->from('tbl_users');
		$this->db->join('state stb', 'stb.state_id = tbl_users.state', 'left');
		$this->db->join('state sts', 'sts.state_id = tbl_users.shipping_state', 'left');
		$this->db->where('user_role_id', 4);
		$this->db->where('is_deleted', 0);
		$this->db->where('user_id', $user_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$ArrCustomerData = $query->result_array()[0];

		}
		return $ArrCustomerData;
	}



	public function getCustomerQueryString($columns = "*", $searchString = '')
	{
		$this->db->select($columns);
		$this->db->from('tbl_users');
		$this->db->where('user_role_id', 4);
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function getCustomerById($user_id) // function used for edit customer  -- getting customer by id
	{
		$this->db->select('*');
		$this->db->from('tbl_users');
		$this->db->where('user_id', $user_id);
		$this->db->where('user_role_id', 4);
		$query = $this->db->get();
		return $query->result_array();
	}


	public function getCustomerListData($Arr)
	{

		$column_order = array(null, 'tbl_users.user_id', 'tbl_users.display_name', 'tbl_users.mobile_no', 'tbl_users.address', 'tbl_users.is_active');

		$aColumns = array('tbl_users.user_id', 'tbl_users.display_name', 'tbl_users.mobile_no', 'tbl_users.address', 'tbl_users.is_active');

		$column_search = array('tbl_users.user_id', 'tbl_users.display_name', 'tbl_users.first_name', 'tbl_users.last_name', 'tbl_users.email', 'tbl_users.address', 'tbl_users.mobile_no');

		$sTable = 'tbl_users';
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
			$this->db->where('tbl_users.created_datetime >=', $txtSearchFrom . " 00:00:00");
			$this->db->where('tbl_users.created_datetime <=', $txtSearchTo . " 23:59:59");
		}
		if (@$_REQUEST['txtSearchFrom'] != "") {
			$txtSearchFrom = date('Y-m-d', strtotime(@$_REQUEST['txtSearchFrom']));
			$this->db->where('tbl_users.created_datetime >=', $txtSearchFrom . " 00:00:00");
		}
		if (@$_REQUEST['txtSearchTo'] != "") {
			$txtSearchTo = date('Y-m-d', strtotime(@$_REQUEST['txtSearchTo']));
			$this->db->where('tbl_users.created_datetime <=', $txtSearchTo . " 23:59:59");
		}


		if (@$_REQUEST['ddIsActive'] != "") {
			$this->db->where('tbl_users.is_active', $_REQUEST['ddIsActive']);
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



			$this->db->where('user_role_id', 4);
			$this->db->where('is_deleted', 0);
			if (isset($_POST['order'])) { // here order processing
				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else {
				$this->db->order_by('tbl_users.user_id', 'desc');
			}
			$rResult = $this->db->get($sTable);
			//echo '<pre>'; print_r( $this->db->last_query() );exit;
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}






} //:)
?>