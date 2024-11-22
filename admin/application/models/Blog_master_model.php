<?php
class blog_master_model extends CI_Model
{

	public function add($data)
	{

		$this->db->insert('tblblog_master', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return 0;
		}

	}

	public function delete($blog_id)
	{
		$this->db->where('blog_id', $blog_id);
		// $this->db->update('tblblog_master', array('is_deleted'=>1));
		$this->db->delete('tblblog_master');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getBlogDetailsUsingID($blog_id)
	{
		$this->db->select("*");
		$this->db->from('tblblog_master');
		$this->db->where('blog_id', $blog_id);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function getBlogDetailsUsingBlogUrl($blog_url, $blog_id)
	{
		$query = $this->db->query("SELECT blog_id FROM `tblblog_master` WHERE blog_id != '" . $blog_id . "' and `blog_url`='" . $blog_url . "'");
		return $query->num_rows();
	}

	public function update($blog_id, $arrData)
	{
		$this->db->where('blog_id', $blog_id);
		$update = $this->db->update('tblblog_master', $arrData);
		return $this->db->affected_rows();
	}



	public function getBlogDetailsBySlug($blog_slug = '')
	{
		if ($blog_slug != "") {
			$this->db->select('*');
			$this->db->from('tblblog_master');
			$this->db->where('is_deleted', 0);
			$this->db->where('blog_url', $blog_slug);

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

	public function getBlogQueryString($searchString = '', $whrArray = array('is_active' => '1'))
	{
		$this->db->select("*");
		$this->db->from('tblblog_master');
		$this->db->where('is_deleted', 0);
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		if (is_array($whrArray) && count($whrArray) > 0)
			$this->db->where($whrArray);

		$this->db->order_by('tblblog_master.blog_id', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	public function getBlogResultBySlug($blog_slug = '')
	{
		if ($blog_slug != "") {
			$this->db->select('*');
			$this->db->from('tblblog_master');
			$this->db->where('is_deleted', 0);
			$this->db->where('blog_url', $blog_slug);

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

	public function isUrlExist($blog_url, $blog_id)
	{
		$this->db->select('blog_id');
		$this->db->from('tblblog_master');
		$this->db->where('blog_url', $blog_url);
		$this->db->where('blog_id !=', $blog_id);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}

	//- Export to csv of blog - START
	public function ExportBlogListData($Arr)
	{
		$column_search = array('tblblog_master.blog_id', 'tblblog_master.created_datetime', 'tblblog_master.blog_title', 'tblblog_master.blog_url', 'tblblog_master.is_active', 'tblblog_master.blog_description', 'tblblog_master.meta_title', 'tblblog_master.meta_descriptions');

		$aColumns = array('tblblog_master.blog_id', 'tblblog_master.created_datetime', 'tblblog_master.blog_title', 'tblblog_master.blog_url', 'tblblog_master.is_active');

		$sTable = 'tblblog_master';

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
		$this->db->order_by('tblblog_master.blog_id', 'DESC');
		$rResult = $this->db->get($sTable);

		// echo $this->db->last_query();		
		return $rResult->result_array();

	}
	//- Export to csv of blog - END

	public function delete_image($blog_id)
	{
		$Arrdata = $this->getBlogDetailsUsingID($blog_id);
		if (isset($Arrdata)) {
			$blog_image = $Arrdata['blog_image'];

			if (trim($blog_image) != "") {
				$filename = 'uploads/blog/' . $blog_image;
				if (file_exists($filename)) {
					unlink($filename);
				}
			}

			return true;
		} else {
			return FALSE;
		}
	}
	/* :)END */
}

?>