<?php

class pantry_model extends CI_Model

{



	public function add($ArrPortfoliosData)

	{



		$this->db->insert('tbl_pantry', $ArrPortfoliosData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}



	}



	public function update($pantry_id, $ArrPortfoliosData)

	{

		$this->db->where('pantry_id', $pantry_id);

		$update = $this->db->update('tbl_pantry', $ArrPortfoliosData);

		return $this->db->affected_rows();

	}



	public function delete($pantry_id)

	{

		$this->delete_image($pantry_id);

		$this->db->where('pantry_id', $pantry_id);

		// $this->db->update('tbl_pantry', array('is_deleted'=>1));

		$this->db->delete('tbl_pantry');

		return true;

	}



	public function getPantryById($pantry_id, $parm = "*")

	{

		$this->db->select($parm);

		$this->db->from('tbl_pantry');

		$this->db->where('pantry_id', $pantry_id);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}




	public function getBrandQueryString($searchString = '', $whrArray = array('is_active' => '1'))

	{

		$this->db->select("*");

		$this->db->from('tbl_pantry');

		if ($searchString != '') {

			$this->db->where($searchString, NULL, FALSE);

		}

		if (is_array($whrArray) && count($whrArray) > 0)

			$this->db->where($whrArray);



		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return false;

		}

	}



	public function getPantryListData($Arr)

	{



		$column_order = array(null, 'tbl_pantry.pantry_id', 'tbl_pantry.pantry_name', 'tbl_pantry.pantry_link', null, 'tbl_pantry.is_active');



		$aColumns = array('tbl_pantry.pantry_id', 'tbl_pantry.pantry_name', 'tbl_pantry.pantry_link', 'tbl_pantry.is_active', 'tbl_pantry.pantry_image');



		$column_search = array('pantry_name', 'pantry_link');



		$sTable = 'tbl_pantry';

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

				$this->db->order_by('tbl_pantry.pantry_id', 'desc');

			}

			$this->db->where('is_deleted', 0);

			$rResult = $this->db->get($sTable);

			//echo '<pre>'; print_r( $this->db->last_query() );exit;

			$this->db->select('FOUND_ROWS() AS found_rows');

			$iFilteredTotal = $this->db->get()->row()->found_rows;

			$iTotal = $this->db->count_all($sTable);



			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());



		}



	}


	// get pantry name list

	public function getPantryAllDetailsById($pantry_id)

	{

		$query = $this->db->query("SELECT tbl_pantry.pantry_id, tbl_pantry.pantry_name, tbl_pantry.pantry_link, tbl_pantry.is_active, tbl_pantry.pantry_image FROM `tbl_pantry` where `tbl_pantry`.`pantry_id` = $pantry_id");

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}



	public function delete_image($catg_id) // delete 

	{

		$Arrdata = $this->getPantryById($catg_id);

		if (isset($Arrdata)) {

			$value = $Arrdata['pantry_image'];

			if (trim($value) != "") {

				$filename = 'uploads/' . $value;

				if (file_exists($filename)) {

					unlink($filename);

				}

			}

			return true;

		} else {

			return FALSE;

		}

	}





	public function pantry_list_data() // do not delete this function as it is used for getting pantry list

	{

		$this->db->select("*");

		$this->db->from('tbl_pantry');

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



} // :)



?>