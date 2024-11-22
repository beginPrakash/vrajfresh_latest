<?php
class tag_model extends CI_Model
{

	public function add($ArrPortfoliosData)
	{

		$this->db->insert('tbl_tag_master', $ArrPortfoliosData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($tag_id, $ArrPortfoliosData)
	{
		$this->db->where('tag_id', $tag_id);
		$update = $this->db->update('tbl_tag_master', $ArrPortfoliosData);
		return $this->db->affected_rows();
	}

	public function delete($tag_id)
	{
		$this->db->where('tag_id', $tag_id);
		// $this->db->update('tbl_tag_master', array('is_deleted'=>1));
		$this->db->delete('tbl_tag_master');
		return true;
	}

	public function getTagById($tag_id, $parm = "*")
	{
		$this->db->select($parm);
		$this->db->from('tbl_tag_master');
		$this->db->where('tag_id', $tag_id);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}
	public function getTagIDByName($tag)
	{
		$this->db->select('tag_id');
		$this->db->from('tbl_tag_master');
		$this->db->where('tag', $tag);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row_array()['tag_id'];
		} else {
			return 0;
		}
	}

	public function getTagQueryString($searchString = '', $whrArray = array('is_active' => '1'))
	{
		$this->db->select("*");
		$this->db->from('tbl_tag_master');
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

	public function getTagListData($Arr)
	{

		$column_order = array(null, 'tbl_tag_master.tag_id', 'tbl_tag_master.tag', 'tbl_tag_master.is_active');

		$aColumns = array('tbl_tag_master.tag_id', 'tbl_tag_master.tag', 'tbl_tag_master.is_active');

		$column_search = array('tag', 'tag');

		$sTable = 'tbl_tag_master';
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
				$this->db->order_by('tbl_tag_master.tag_id', 'desc');
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

	public function ExportTagData($Arr)
	{

		$aColumns = array('tbl_tag_master.tag_id', 'tbl_tag_master.tag', 'tbl_tag_master.tag_description', 'tbl_tag_master.is_active', 'tbl_tag_master.tag_image');
		$column_search = array('tag', 'tag_description');

		$sTable = 'tbl_tag_master';
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

		$this->db->order_by('tbl_tag_master.tag_id', 'desc');
		$rResult = $this->db->get($sTable);
		//echo '<pre>'; print_r( $this->db->last_query() );exit;
		return $rResult->result_array();

	}
	// get tag name list
	public function getTagAllDetailsById($tag_id)
	{
		$query = $this->db->query("SELECT tbl_tag_master.tag_id, tbl_tag_master.tag, tbl_tag_master.is_active FROM `tbl_tag_master` where `tbl_tag_master`.`tag_id` = $tag_id");
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}



	public function tag_list_data() // do not delete this function as it is used for getting tag list
	{
		$this->db->select("*");
		$this->db->from('tbl_tag_master');
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

} // :)

?>