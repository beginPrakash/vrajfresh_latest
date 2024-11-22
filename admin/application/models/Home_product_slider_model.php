<?php
class home_product_slider_model extends CI_Model
{

	public function add($ArrPortfoliosData)
	{

		$this->db->insert('tbl_home_product_slider', $ArrPortfoliosData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($home_product_slider_id, $ArrPortfoliosData)
	{
		$this->db->where('home_product_slider_id', $home_product_slider_id);
		$update = $this->db->update('tbl_home_product_slider', $ArrPortfoliosData);
		return $this->db->affected_rows();
	}

	public function delete($home_product_slider_id)
	{
		$this->db->where('home_product_slider_id', $home_product_slider_id);
		// $this->db->update('tbl_home_product_slider', array('is_deleted'=>1));
		$this->db->delete('tbl_home_product_slider');
		return true;
	}

	public function getBannerById($home_product_slider_id, $parm = "*")
	{
		$this->db->select($parm);
		$this->db->from('tbl_home_product_slider');
		$this->db->where('home_product_slider_id', $home_product_slider_id);
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
		$this->db->from('tbl_home_product_slider');
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

		$column_order = array(null, 'tbl_home_product_slider.home_product_slider_id', 'tbl_home_product_slider.title', null, 'tbl_home_product_slider.is_active');

		$aColumns = array('tbl_home_product_slider.home_product_slider_id', 'tbl_home_product_slider.title', 'tbl_home_product_slider.is_active');

		$column_search = array('title');

		$sTable = 'tbl_home_product_slider';
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
				$this->db->order_by('tbl_home_product_slider.home_product_slider_id', 'desc');
			}
			$rResult = $this->db->get($sTable);

			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}

	// get banner name list
	public function getBannerAllDetailsById($home_product_slider_id)
	{
		$query = $this->db->query("SELECT * FROM `tbl_home_product_slider` where `tbl_home_product_slider`.`home_product_slider_id` = $home_product_slider_id");
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}


	public function banner_list_data() // do not delete this function as it is used for getting banner list
	{
		$this->db->select("*");
		$this->db->from('tbl_home_product_slider');
		$this->db->where('is_deleted', 0);
		$this->db->order_by('tbl_home_product_slider.home_product_slider_id', 'desc');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

} // :)

?>