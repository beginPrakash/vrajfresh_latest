<?php
class zone_model extends CI_Model
{

	public function add($ArrProductData)
	{

		$this->db->insert('tbl_zones', $ArrProductData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	
	public function update($zone_id, $ArrProductData)
	{
		$this->db->where('zone_id', $zone_id);
		$update = $this->db->update('tbl_zones', $ArrProductData);
		return $this->db->affected_rows();
	}


	public function delete($zone_id)
	{
		$this->db->where('zone_id', $zone_id);
		// Hard Delete Enabled 
		$this->db->delete('tbl_zones');

		return true;
	}

	public function getProductUsingID($zone_id)
	{
		$this->db->select("*");
		$this->db->from('tbl_zones');
		$this->db->where('zone_id', $zone_id);
		$this->db->where('is_deleted', 0);

		$query = $this->db->get();
        if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return array();
		}

	}

	public function zone_list()
	{
		$this->db->select("*");
		$this->db->from('tbl_zones');
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	public function getZipcodelist()
	{
		$this->db->select("zipcode_id,zipcode");
		$this->db->from('tbl_zipcodes');
		$this->db->where('is_deleted', 0);
		$this->db->where('is_active', 1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	
	public function getProductById($zone_id) // function used for edit product  -- getting product by id
	{
		$this->db->select('*');
		$this->db->from('tbl_zones');
		$this->db->where('zone_id', $zone_id);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//print_r($this->db->last_query());   exit; 
		return $query->result_array();
	}
	public function getProductDetail($product_slug)
	{
		$this->db->select('*');
		$this->db->from('tbl_zones');
		$this->db->where('is_deleted', 0);
		$this->db->where('product_slug', $product_slug);
		$query = $this->db->get();
		return $query->result_array();
	}



	public function getProductListData($Arr)
	{
		$column_order = array(null, 'tbl_zones.title', 'tbl_zones.holiday_start_date', 'tbl_zones.holiday_end_date','tbl_zones.created_datetime');

		$aColumns = array('zone_id','tbl_zones.title', 'tbl_zones.holiday_start_date', 'tbl_zones.holiday_end_date', 'tbl_zones.created_datetime');

		$column_search = array('title');

		$sTable = 'tbl_zones';
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
			//$this->db->join('tbl_categories', 'tbl_zones.category_id = tbl_categories.category_id', 'left'); 


			if (isset($_POST['order'])) { // here order processing
				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else {
				$this->db->order_by('tbl_zones.zone_id', 'desc');
			}
			$this->db->where('tbl_zones.is_deleted', 0);
			$rResult = $this->db->get($sTable);
			//echo '<pre>'; print_r( $this->db->last_query() );exit;
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}


	public function get_all_products()
	{
		$this->db->select("*");
		$this->db->from('tbl_zones');
		$this->db->where('is_deleted', 0);

		$this->db->order_by('tbl_zones.zone_id', 'desc');
		$this->db->order_by("title", "asc");


		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

    public function soft_delete_product($id) {
        $this->db->where('zone_id', $id);
        return $this->db->update('tbl_zones', array('is_deleted' => 1));
    }

}
?>