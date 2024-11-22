<?php
class clientgroup_model extends CI_Model
{

	public function add($ArrClientGroupData)
	{

		$this->db->insert('tblclientgroup', $ArrClientGroupData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($clientgroup_id, $ArrClientGroupData)
	{
		$this->db->where('clientgroup_id', $clientgroup_id);
		$update = $this->db->update('tblclientgroup', $ArrClientGroupData);
		return $this->db->affected_rows();
	}
	public function delete($clientgroup_id)
	{
		$this->db->where('clientgroup_id', $clientgroup_id);
		$this->db->delete('tblclientgroup');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getClientGroupListData($Arr)
	{

		$column_order = array(null, 'tblclientgroup.clientgroup_id', 'tblclientgroup.created_date', 'tblclientgroup.clientgroup_title', 'tblclientgroup.is_active');

		$aColumns = array('tblclientgroup.clientgroup_id', 'tblclientgroup.created_date', 'tblclientgroup.clientgroup_title', 'tblclientgroup.is_active');

		$column_search = array('clientgroup_title');

		$sTable = 'tblclientgroup';


		/* search by keyword */
		$i = 0;
		foreach ($column_search as $item) { /*loop column */
			if (@$_POST['search']['value'] || @$_POST['txtSearchKeyWord']) /*if datatable send POST for search*/{
				/* if($i===0)
							{ 
								$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
								if($_POST['search']['value'] && $_POST['txtSearchKeyWord']) {
									$this->db->like($item, $_POST['search']['value']);                        
									$this->db->or_like($item, $_POST['txtSearchKeyWord']);
								}  else if ($_POST['search']['value'] && !$_POST['txtSearchKeyWord']) {                            
									$this->db->like($item, $_POST['search']['value']);
								} else if (!$_POST['search']['value'] && $_POST['txtSearchKeyWord']) {                            
									$this->db->like($item, $_POST['txtSearchKeyWord']);
								}
							}
							else
							{
								if($_POST['search']['value']) { $this->db->or_like($item, $_POST['search']['value']);}
								if($_POST['txtSearchKeyWord']){$this->db->or_like($item, $_POST['txtSearchKeyWord']);}
							}  */
				if (count($column_search) - 1 == $i) {
					$this->db->group_end(); /*close bracket*/
				}
			}
			$i++;
		}
		if (@$_REQUEST['ser_from_date'] != "" && @$_REQUEST['ser_to_date'] != "") {
			//$this->db->where('tblclientgroup.created_date >=',date('Y-m-d', strtotime($_REQUEST['ser_from_date'])));
			$this->db->where('tblclientgroup.created_date BETWEEN "' . date('Y-m-d', strtotime($_REQUEST['ser_from_date'])) . '" and "' . date('Y-m-d', strtotime($_REQUEST['ser_to_date'])) . '"');

		}
		//- export to csv - START //-
		if (@$_POST['ser_from_date'] != "" && @$_REQUEST['ser_to_date'] == "") {
			$this->db->where('tblclientgroup.created_date >=', @$_POST['ser_from_date']);
		}
		if (@$_POST['ser_to_date'] != "" && @$_REQUEST['ser_from_date'] = "") {
			$this->db->where('tblclientgroup.created_date <=', @$_POST['ser_to_date']);
		}
		//- export to csv - END //-




		/* if(@$_REQUEST['ser_to_date'] != "")
			  {
				  $this->db->where('tblclientgroup.created_date <=',date('Y-m-d', strtotime($_REQUEST['ser_to_date'])));
				  //$this->db->where('tblclientgroup.created_date BETWEEN "'. date('Y-m-d', strtotime($_REQUEST['ser_from_date'])). '" and "'. date('Y-m-d', strtotime($_REQUEST['ser_to_date'])).'"');

			  } */


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
				$this->db->order_by('tblclientgroup.clientgroup_id', 'desc');
			}

			$rResult = $this->db->get($sTable);
			// echo '<pre>'; print_r( $this->db->last_query() );exit;
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}

	public function ExportClientGroupData($Arr)
	{



		$aColumns = array('tblclientgroup.clientgroup_id', 'tblclientgroup.created_date', 'tblclientgroup.clientgroup_title', 'tblclientgroup.is_active');

		$column_search = array('clientgroup_title');

		$sTable = 'tblclientgroup';
		/* search by keyword */
		$i = 0;
		/*foreach ($column_search as $item) { /*loop column */
		//if($_POST['txtSearchKeyWord'])  /*if datatable send POST for search*/
		//{                           
		/* if($i===0)
					{ 
						$this->db->group_start();
						if ($_POST['txtSearchKeyWord']) {                            
							$this->db->like($item, $_POST['txtSearchKeyWord']);
						}
					}
					else
					{
						if($_POST['txtSearchKeyWord']){$this->db->or_like($item, $_POST['txtSearchKeyWord']);}
					}  */
		//if(count($column_search) - 1 == $i) { $this->db->group_end(); /*close bracket*/ }
		//}
		//$i++;
		//}
		/* end */
		$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);


		if (@$_REQUEST['ser_from_date'] != "" && @$_REQUEST['ser_to_date'] != "") {
			//$this->db->where('tblclientgroup.created_date >=',date('Y-m-d', strtotime($_REQUEST['ser_from_date'])));
			$this->db->where('tblclientgroup.created_date BETWEEN "' . date('Y-m-d', strtotime($_REQUEST['ser_from_date'])) . '" and "' . date('Y-m-d', strtotime($_REQUEST['ser_to_date'])) . '"');
		}
		//- export to csv - START //-
		if (@$_POST['ser_from_date'] != "" && @$_REQUEST['ser_to_date'] == "") {
			$this->db->where('tblclientgroup.created_date >=', @$_POST['ser_from_date']);
		}
		if (@$_POST['ser_to_date'] != "" && @$_REQUEST['ser_from_date'] = "") {
			$this->db->where('tblclientgroup.created_date <=', @$_POST['ser_to_date']);
		}
		//- export to csv - END //-


		/*if(@$_REQUEST['ser_from_date'] != '' && @$_REQUEST['ser_to_date'] != ''){
				  $this->db->where('tblclientgroup.created_date >=', date('Y-m-d',strtotime(@$_REQUEST['ser_from_date'])));
				  $this->db->where('tblclientgroup.created_date <=', date('Y-m-d',strtotime(@$_REQUEST['ser_to_date'])));
			  } */
		$this->db->order_by('tblclientgroup.clientgroup_id', 'desc');
		$rResult = $this->db->get($sTable);
		// echo 'test<pre>'; print_r( $this->db->last_query());exit;
		return $rResult->result_array();

	}

	public function getClientGroupById($clientgroup_id)
	{
		$this->db->select('*');
		$this->db->from('tblclientgroup');
		$this->db->where('clientgroup_id', $clientgroup_id);
		$query = $this->db->get();
		//echo '<pre>'; print_r( $this->db->last_query());exit;
		return $query->result_array();
	}

	public function getClientGroupUsingID($clientgroup_id)
	{
		$this->db->select("*");
		$this->db->from('tblclientgroup');
		$this->db->where('clientgroup_id', $clientgroup_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function checkDuplicate($clientgroup_id, $clientgroup_title)
	{
		$query = $this->db->query("SELECT * FROM `tblclientgroup` WHERE `clientgroup_title` = '" . $clientgroup_title . "' and `clientgroup_id` != '" . $clientgroup_id . "'");
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function getClientGroupQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tblclientgroup');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function getClientGroup($condition)
	{
		$this->db->select('*');
		$this->db->from('tblclientgroup');
		if ($condition != '')
			$this->db->where($condition, NULL, FALSE);
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

	public function updateClientGroup($clientgroup_id, $data)
	{
		$this->db->where('clientgroup_id', $clientgroup_id);
		$update = $this->db->update('tblclientgroup', $data);
		/*$que = $this->db->last_query().'<br/>';
			  $myfile = fopen("testfile.txt", "a+");
			  fwrite($myfile, $que);*/
		$report = array();

		if ($report !== 0) {
			return true;
		} else {
			return false;
		}
	}

	public function addClientGroup($data)
	{
		$this->db->insert('tblclientgroup', $data);

		$insert = $this->db->insert_id();
		return $insert;
	}

	public function getAllProducts()
	{
		$this->db->select("*");
		$this->db->from('tbl_products');
		$this->db->where('is_active', '1');
		$this->db->order_by("product_name", "asc");
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function getFilterUserId($sql, $field = '')
	{
		$ArrUsers = array();
		$i = 0;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$ArrUser = $query->result_array();
			foreach ($ArrUser as $user_id) {
				if ($field != '') {
					$ArrUsers[$i++] = $user_id[$field];
				} else {
					$ArrUsers[$i++] = $user_id;
				}
			}
		}
		return $ArrUsers;
	}

	public function getUserOrderAmount($sql)
	{
		//echo $sql;exit;
		$ArrUsers = array();
		$i = 0;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {

			$ArrOrder = $query->result_array();
			foreach ($ArrOrder as $TempArr) {
				//print_r($TempArr);
				$ArrUsers[$TempArr['user_id']] = $TempArr['amount'] - $TempArr['discount'];
			}
		}
		return $ArrUsers;
	}

	public function getUserOrderPaidAmount($sql)
	{
		$ArrUsers = array();
		$i = 0;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {

			$ArrOrder = $query->result_array();
			foreach ($ArrOrder as $TempArr) {
				$ArrUsers[$TempArr['user_id']] = $TempArr['amount'];
			}
		}
		return $ArrUsers;
	}
}

?>