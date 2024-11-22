<?php

class category_model extends CI_Model

{



	public function add($ArrPortfoliosData)

	{



		$this->db->insert('tbl_categories', $ArrPortfoliosData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}



	}



	public function update($category_id, $ArrPortfoliosData)

	{

		$this->db->where('category_id', $category_id);

		$update = $this->db->update('tbl_categories', $ArrPortfoliosData);

		return $this->db->affected_rows();

	}

	public function update_sub_category($category_id, $ArrPortfoliosData)

	{

		$this->db->where('parent_category_id', $category_id);

		$update = $this->db->update('tbl_categories', $ArrPortfoliosData);

		return $this->db->affected_rows();

	}



	public function updatePerisibleProducts($category_id, $is_perisible_products)

	{

		$this->db->select("product_id");

		$this->db->from('tbl_categories_products_mapping');

		$this->db->where('category_id', $category_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			$query = $this->db->query("UPDATE `tbl_products` SET is_perisible_products=$is_perisible_products WHERE `product_id` IN (SELECT product_id FROM tbl_categories_products_mapping WHERE category_id = $category_id)");

			if ($query->num_rows() > 0) {

				return $query->result_array();

			} else {

				return array();

			}

		}

	}

	public function delete($category_id)

	{

		$this->delete_image($category_id);

		

		

		$this->db->where('category_id', $category_id);

		$this->db->delete('tbl_categories_products_mapping');

		

		$this->db->where('category_id', $category_id);

		$this->db->delete('tbl_categories');

		return true;

	}



	public function getCategoryById($category_id, $parm = "*")

	{

		$this->db->select($parm);

		$this->db->from('tbl_categories');

		$this->db->where('category_id', $category_id);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}



	public function getParentCategoryIDBySlug($category_slug)

	{

		$this->db->select("parent_category_id");

		$this->db->from('tbl_categories');

		$this->db->where('category_slug', $category_slug);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}



	public function getCategoryIDByName($category_name)

	{

		$this->db->select("category_id");

		$this->db->from('tbl_categories');

		$this->db->where('category_name', $category_name);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		//echo "<br>QUEYR:".$this->db->last_query();

		if ($query->num_rows() > 0) {

			return $query->row_array()['category_id'];

		} else {

			return 0;

		}

	}



	public function getCategoryQueryString($searchString = '', $whrArray = array('is_active' => '1'))

	{

		$this->db->select("*");

		$this->db->from('tbl_categories');

		$this->db->where('is_deleted', 0);

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



	public function getCategoryListData($Arr)

	{



		$column_order = array(null, 'tbl_categories.category_id', 'tbl_categories.category_name', 'tbl_categories.category_description', null, 'tbl_categories.is_active');



		$aColumns = array('tbl_categories.category_id', 'tbl_categories.category_name', 'tbl_categories.category_description', 'tbl_categories.is_active', 'tbl_categories.category_image');



		$column_search = array('category_name', 'category_description');



		$sTable = 'tbl_categories';

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

				$this->db->order_by('tbl_categories.category_id', 'desc');

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



	public function ExportCategoryData($Arr)

	{



		$aColumns = array('tbl_categories.category_id', 'tbl_categories.category_name', 'tbl_categories.category_description', 'tbl_categories.is_active', 'tbl_categories.category_image');

		$column_search = array('category_name', 'category_description');



		$sTable = 'tbl_categories';

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

		$this->db->join('tblsite_master', 'tblsite_master.site_master_id = tbl_categories.site_master_id', 'JOIN');

		if ($this->session->userdata('site_master_id') > 0) {

			$this->db->where("tbl_categories.site_master_id", $this->session->userdata('site_master_id'));

		}

		$this->db->order_by('tbl_categories.category_id', 'desc');

		$rResult = $this->db->get($sTable);

		//echo '<pre>'; print_r( $this->db->last_query() );exit;

		return $rResult->result_array();



	}

	// get category name list

	public function getCategoryAllDetailsById($category_id)

	{

		$query = $this->db->query("SELECT tbl_categories.category_id, tbl_categories.category_name, tbl_categories.category_description, tbl_categories.is_active, tbl_categories.category_image, tbl_categories.is_perisible_products, tbl_categories.is_liker_category, tbl_categories.is_cook_food_category FROM `tbl_categories` where `tbl_categories`.`category_id` = $category_id");

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}



	public function delete_image($catg_id) // delete 

	{

		$Arrdata = $this->getCategoryById($catg_id);

		if (isset($Arrdata)) {

			$value = $Arrdata['category_image'];

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

	public function parent_category_list_data()

	{

		$this->db->select("*");

		$this->db->from('tbl_categories');

		$this->db->where('parent_category_id', 0);

		$this->db->where('is_deleted', 0);



		$query = $this->db->get();



		if ($query->num_rows() > 0) {

			return $query->result_array();



		}

	}



	public function category_list_data() // do not delete this function as it is used for getting category list

	{

		$this->db->select("*");

		$this->db->from('tbl_categories');

		//$this->db->where('parent_category_id > 0');

		$this->db->where('is_deleted', 0);



		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



} // :)



?>