<?php
class brand_model extends CI_Model
{

	public function add($ArrPortfoliosData)
	{

		$this->db->insert('tbl_brands', $ArrPortfoliosData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($brand_id, $ArrPortfoliosData)
	{
		$this->db->where('brand_id', $brand_id);
		$update = $this->db->update('tbl_brands', $ArrPortfoliosData);
		return $this->db->affected_rows();
	}

	public function delete($brand_id)
	{
		$this->delete_image($brand_id);
		$this->db->where('brand_id', $brand_id);
		// $this->db->update('tbl_brands', array('is_deleted'=>1));
		$this->db->delete('tbl_brands');
		return true;
	}

	public function getBrandById($brand_id, $parm = "*")
	{
		$this->db->select($parm);
		$this->db->from('tbl_brands');
		$this->db->where('brand_id', $brand_id);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	public function getBrandIDByName($brand_name)
	{
		$this->db->select('brand_id');
		$this->db->from('tbl_brands');
		$this->db->where('brand_name', $brand_name);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row_array()['brand_id'];
		} else {
			return 0;
		}
	}


	public function getBrandQueryString($searchString = '', $whrArray = array('is_active' => '1'))
	{
		$this->db->select("*");
		$this->db->from('tbl_brands');
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

		$column_order = array(null, 'tbl_brands.brand_id', 'tbl_brands.brand_name', 'tbl_brands.brand_description', null, 'tbl_brands.is_active');

		$aColumns = array('tbl_brands.brand_id', 'tbl_brands.brand_name', 'tbl_brands.brand_description', 'tbl_brands.is_active', 'tbl_brands.brand_image');

		$column_search = array('brand_name', 'brand_description');

		$sTable = 'tbl_brands';
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
				$this->db->order_by('tbl_brands.brand_id', 'desc');
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

	public function ExportBrandData($Arr)
	{

		$aColumns = array('tbl_brands.brand_id', 'tbl_brands.brand_name', 'tbl_brands.brand_description', 'tbl_brands.is_active', 'tbl_brands.brand_image');
		$column_search = array('brand_name', 'brand_description');

		$sTable = 'tbl_brands';
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
		$this->db->join('tblsite_master', 'tblsite_master.site_master_id = tbl_brands.site_master_id', 'JOIN');
		if ($this->session->userdata('site_master_id') > 0) {
			$this->db->where("tbl_brands.site_master_id", $this->session->userdata('site_master_id'));
		}
		$this->db->order_by('tbl_brands.brand_id', 'desc');
		$rResult = $this->db->get($sTable);
		//echo '<pre>'; print_r( $this->db->last_query() );exit;
		return $rResult->result_array();

	}
	// get brand name list
	public function getBrandAllDetailsById($brand_id)
	{
		$query = $this->db->query("SELECT tbl_brands.brand_id, tbl_brands.is_home_display, tbl_brands.brand_name, tbl_brands.brand_description, tbl_brands.is_active, tbl_brands.brand_image FROM `tbl_brands` where `tbl_brands`.`brand_id` = $brand_id");
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
			$value = $Arrdata['brand_image'];
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


	public function brand_list_data() // do not delete this function as it is used for getting brand list
	{
		$this->db->select("*");
		$this->db->from('tbl_brands');
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

} // :)

?>