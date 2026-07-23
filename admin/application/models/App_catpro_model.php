<?php

class app_catpro_model extends CI_Model

{



	public function add($ArrPortfoliosData)

	{



		$this->db->insert('appbanner_products', $ArrPortfoliosData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}



	}



	public function update($id, $ArrPortfoliosData)

	{

		$this->db->where('id', $id);

		$update = $this->db->update('appbanner_products', $ArrPortfoliosData);

		return $this->db->affected_rows();

	}



	public function delete($id)

	{

		$this->delete_image($id);

		$this->db->where('id', $id);

		// $this->db->update('appbanner_products', array('is_deleted'=>1));

		$this->db->delete('appbanner_products');

		return true;

	}



	public function getBannerById($id, $parm = "*")

	{

		$this->db->select($parm);

		$this->db->from('appbanner_products');

		$this->db->where('id', $id);

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}







	public function getBannerQueryString($searchString = '', $whrArray = array('is_active' => '1'))

	{

		$this->db->select("*");

		$this->db->from('appbanner_products');


		if ($searchString != '') {

			$this->db->where($searchString, NULL, FALSE);

		}

		if (is_array($whrArray) && count($whrArray) > 0)

			$this->db->where($whrArray);



		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return false;

		}

	}



	public function getBannerListData($Arr)

	{



		$column_order = array(null, 'appbanner_products.id', 'appbanner_products.product_link','appbanner_products.product_srno', null, 'appbanner_products.is_active');



		$aColumns = array('appbanner_products.id', 'appbanner_products.category_id','appbanner_products.product_name', 'appbanner_products.product_link', 'appbanner_products.product_srno','appbanner_products.is_active', 'appbanner_products.product_image');



		$column_search = array('product_link');



		$sTable = 'appbanner_products';

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

				$this->db->order_by('appbanner_products.id', 'desc');

			}

			$rResult = $this->db->get($sTable);



			$this->db->select('FOUND_ROWS() AS found_rows');

			$iFilteredTotal = $this->db->get()->row()->found_rows;

			$iTotal = $this->db->count_all($sTable);



			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());



		}



	}


	// get banner name list

	public function getBannerAllDetailsById($id)

	{

		$query = $this->db->query("SELECT * FROM `appbanner_products` where `appbanner_products`.`id` = $id");

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}

	
	public function getBannercatnamebyId($id)

	{

		$query = $this->db->query("SELECT * FROM `tbl_appbannerdata` where `tbl_appbannerdata`.`banner_id` = $id");

		if ($query->num_rows() > 0) {

			return $query->row()->category_name;

		} else {

			return false;

		}

	}



	public function delete_image($catg_id) // delete 

	{

		$Arrdata = $this->getBannerById($catg_id);

		if (isset($Arrdata)) {

			$value = $Arrdata['product_image'];

			if (!empty($value) && trim($value) != "") {

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





	public function banner_list_data() // do not delete this function as it is used for getting banner list

	{

		$this->db->select("*");

		$this->db->from('appbanner_products');


		$this->db->order_by('appbanner_products.id', 'desc');

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



} // :)



?>