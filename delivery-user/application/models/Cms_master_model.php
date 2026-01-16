<?php
class cms_master_model extends CI_Model
{

	public function add($data)
	{

		$this->db->insert('tbl_cms', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}

	public function delete($cms_id)
	{
		$this->db->where('cms_id', $cms_id);
		// $this->db->update('tbl_cms', array('is_deleted'=>1));
		$this->db->delete('tbl_cms');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getCMSDetailsUsingID($cms_id)
	{
		$this->db->select("*");
		$this->db->from('tbl_cms');
		$this->db->where('cms_id', $cms_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function getCMSDetailsUsingPageUrl($cms_url, $cms_id)
	{
		$query = $this->db->query("SELECT cms_id FROM `tbl_cms` WHERE cms_id != '" . $cms_id . "' and `cms_url`='" . $cms_url . "'");
		return $query->num_rows();
	}

	public function update($cms_id, $arrData)
	{
		$this->db->where('cms_id', $cms_id);
		$update = $this->db->update('tbl_cms', $arrData);
		return $this->db->affected_rows();
	}

	public function getCmsDetailsById($cms_id)
	{
		$this->db->select('*');
		$this->db->from('tbl_cms');

		$this->db->where('cms_id', $cms_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getCmsDetailsBySlug($page_slug = '')
	{
		if ($page_slug != "") {
			$this->db->select('*');
			$this->db->from('tbl_cms');
			$this->db->where('cms_url', $page_slug);

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row_array();
			} else {
				return array();
			}

		} else {
			return array();
		}
	}

	public function getCmsResultBySlug($page_slug = '')
	{
		if ($page_slug != "") {
			$this->db->select('*');
			$this->db->from('tbl_cms');
			$this->db->where('cms_url', $page_slug);

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return array();
			}

		} else {
			return array();
		}
	}

	public function isUrlExist($cms_url, $cms_id)
	{
		$this->db->select('cms_id');
		$this->db->from('tbl_cms');
		$this->db->where('cms_url', $cms_url);
		$this->db->where('cms_id !=', $cms_id);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	//- Export to csv of cms - START
	public function ExportCmsListData($Arr)
	{
		$column_search = array('tbl_cms.cms_id', 'tbl_cms.created_datetime', 'tbl_cms.cms_title', 'tbl_cms.cms_url', 'tbl_cms.is_active', 'tbl_cms.cms_description', 'tbl_cms.meta_title', 'tbl_cms.meta_descriptions');

		$aColumns = array('tbl_cms.cms_id', 'tbl_cms.created_datetime', 'tbl_cms.cms_title', 'tbl_cms.cms_url', 'tbl_cms.is_active');

		$sTable = 'tbl_cms';

		$i = 0;
		foreach ($column_search as $item) { /*loop column */
			if ($_POST['txtSearchKeyWord']) /*if datatable send POST for search*/{
				if ($i === 0) {
					$this->db->group_start(); /*start bracket*/
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

		if (@$_REQUEST['ddIsActive'] != "") {
			$this->db->where('is_active', $_REQUEST['ddIsActive']);
		}


		$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);
		$this->db->order_by('tbl_cms.cms_id', 'DESC');
		$rResult = $this->db->get($sTable);

		// echo $this->db->last_query();		
		return $rResult->result_array();

	}
	//- Export to csv of cms - END

	/* :)END */
}

?>