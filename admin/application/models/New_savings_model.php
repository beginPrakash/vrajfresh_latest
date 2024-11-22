<?php

class new_savings_model extends CI_Model

{



	public function add($ArrPortfoliosData)

	{



		$this->db->insert('tbl_new_savings', $ArrPortfoliosData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}



	}



	public function update($savings_id, $ArrPortfoliosData)

	{

		$this->db->where('savings_id', $savings_id);

		$update = $this->db->update('tbl_new_savings', $ArrPortfoliosData);

		return $this->db->affected_rows();

	}



	public function delete($savings_id)

	{

		$this->delete_image($savings_id);

		$this->db->where('savings_id', $savings_id);

		// $this->db->update('tbl_new_savings', array('is_deleted'=>1));

		$this->db->delete('tbl_new_savings');

		return true;

	}



	public function getBrandById($savings_id, $parm = "*")

	{

		$this->db->select($parm);

		$this->db->from('tbl_new_savings');

		$this->db->where('savings_id', $savings_id);

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

		$this->db->from('tbl_new_savings');
		

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



	public function getBrandListData($Arr)

	{


		$column_order = array(null, 'tbl_new_savings.savings_id', 'tbl_products.product_name', null, 'tbl_new_savings.is_active');



		$aColumns = array('tbl_new_savings.savings_id', 'tbl_products.product_name', 'tbl_new_savings.is_active');



		$column_search = array('tbl_products.product_name');



		$sTable = 'tbl_new_savings';

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
			
			$this->db->join('tbl_products', 'tbl_new_savings.savings_product_id = tbl_products.product_id', 'JOIN');





			if (isset($_POST['order'])) { // here order processing

				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

			} else {

				$this->db->order_by('tbl_new_savings.savings_id', 'desc');

			}

			$this->db->where('tbl_new_savings.is_deleted', 0);

			$rResult = $this->db->get($sTable);

			//echo '<pre>'; print_r( $this->db->last_query() );exit;

			$this->db->select('FOUND_ROWS() AS found_rows');

			$iFilteredTotal = $this->db->get()->row()->found_rows;

			$iTotal = $this->db->count_all($sTable);



			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());



		}



	}


	// get brand name list

	public function getBrandAllDetailsById($savings_id)

	{

		$query = $this->db->query("SELECT tbl_new_savings.savings_id, tbl_products.product_name, tbl_new_savings.is_active FROM `tbl_new_savings` INNER JOIN tbl_products
		ON tbl_new_savings.savings_product_id = tbl_products.product_id where `tbl_new_savings`.`savings_id` = $savings_id");

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}



	public function delete_image($catg_id) // delete 

	{

		$Arrdata = $this->getBrandById($catg_id);

		if (isset($Arrdata)) {

			return true;

		} else {

			return FALSE;

		}

	}





	public function product_list_data() // do not delete this function as it is used for getting brand list

	{

		$this->db->select("*");

		$this->db->from('tbl_products');

		$this->db->where('is_deleted', 0);
        
        $this->db->where('is_active', 1);

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



} // :)



?>