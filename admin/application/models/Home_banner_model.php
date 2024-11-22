<?php

class home_banner_model extends CI_Model

{



	public function add($ArrPortfoliosData)

	{



		$this->db->insert('tbl_home_banners', $ArrPortfoliosData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}



	}



	public function update($banner_id, $ArrPortfoliosData)

	{

		$this->db->where('banner_id', $banner_id);

		$update = $this->db->update('tbl_home_banners', $ArrPortfoliosData);

		return $this->db->affected_rows();

	}



	public function delete($banner_id)

	{

		$this->delete_image($banner_id);

		$this->db->where('banner_id', $banner_id);

		// $this->db->update('tbl_home_banners', array('is_deleted'=>1));

		$this->db->delete('tbl_home_banners');

		return true;

	}



	public function getBannerById($banner_id, $parm = "*")

	{

		$this->db->select($parm);

		$this->db->from('tbl_home_banners');

		$this->db->where('banner_id', $banner_id);

		$this->db->where('is_deleted', 0);

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

		$this->db->from('tbl_home_banners');

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



	public function getBannerListData($Arr)

	{



		$column_order = array(null, 'tbl_home_banners.banner_id', 'tbl_home_banners.banner_link','tbl_home_banners.banner_srno', null, 'tbl_home_banners.is_active');



		$aColumns = array('tbl_home_banners.banner_id', 'tbl_home_banners.banner_link', 'tbl_home_banners.banner_srno','tbl_home_banners.is_active', 'tbl_home_banners.banner_image','tbl_home_banners.banner_type');



		$column_search = array('banner_link');



		$sTable = 'tbl_home_banners';

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

				$this->db->order_by('tbl_home_banners.banner_id', 'desc');

			}

			$rResult = $this->db->get($sTable);



			$this->db->select('FOUND_ROWS() AS found_rows');

			$iFilteredTotal = $this->db->get()->row()->found_rows;

			$iTotal = $this->db->count_all($sTable);



			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());



		}



	}


	// get banner name list

	public function getBannerAllDetailsById($banner_id)

	{

		$query = $this->db->query("SELECT * FROM `tbl_home_banners` where `tbl_home_banners`.`banner_id` = $banner_id");

		if ($query->num_rows() > 0) {

			return $query->row_array();

		} else {

			return false;

		}

	}



	public function delete_image($catg_id) // delete 

	{

		$Arrdata = $this->getBannerById($catg_id);

		if (isset($Arrdata)) {

			$value = $Arrdata['banner_image'];

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





	public function banner_list_data() // do not delete this function as it is used for getting banner list

	{

		$this->db->select("*");

		$this->db->from('tbl_home_banners');

		$this->db->where('is_deleted', 0);

		$this->db->order_by('tbl_home_banners.banner_id', 'desc');

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



} // :)



?>