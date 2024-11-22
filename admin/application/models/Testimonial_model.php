<?php
class testimonial_model extends CI_Model
{

	public function add($ArrPortfoliosData)
	{

		$this->db->insert('tbltestimonial', $ArrPortfoliosData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($testimonial_id, $ArrPortfoliosData)
	{
		$this->db->where('testimonial_id', $testimonial_id);
		$update = $this->db->update('tbltestimonial', $ArrPortfoliosData);
		return $this->db->affected_rows();
	}

	public function delete($testimonial_id)
	{
		$this->delete_image($testimonial_id);
		$this->db->where('testimonial_id', $testimonial_id);
		// $this->db->update('tbltestimonial', array('is_deleted'=>1));
		$this->db->delete('tbltestimonial');
		return true;
	}

	public function getTestimonialById($testimonial_id, $parm = "*")
	{
		$this->db->select($parm);
		$this->db->from('tbltestimonial');
		$this->db->where('testimonial_id', $testimonial_id);
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}



	public function getTestimonialQueryString($searchString = '', $whrArray = array('is_active' => '1'))
	{
		$this->db->select("*");
		$this->db->from('tbltestimonial');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		if (is_array($whrArray) && count($whrArray) > 0)
			$this->db->where($whrArray);

		$this->db->order_by('tbltestimonial.testimonial_id', 'desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function getTestimonialListData($Arr)
	{

		$column_order = array(null, 'tbltestimonial.testimonial_id', 'tbltestimonial.customer_name', 'tbltestimonial.customer_subname', 'tbltestimonial.description', null, 'tbltestimonial.is_active');

		$aColumns = array('tbltestimonial.testimonial_id', 'tbltestimonial.customer_name', 'tbltestimonial.customer_subname', 'tbltestimonial.description', 'tbltestimonial.is_active', 'tbltestimonial.testimonial_image');

		$column_search = array('customer_name', 'customer_subname', 'description');

		$sTable = 'tbltestimonial';
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
				$this->db->order_by('tbltestimonial.testimonial_id', 'desc');
			}
			$rResult = $this->db->get($sTable);

			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}

	public function ExportTestimonialData($Arr)
	{

		$aColumns = array('tbltestimonial.testimonial_id', 'tbltestimonial.customer_name', 'tbltestimonial.customer_subname', 'tbltestimonial.description', 'tbltestimonial.is_active', 'tbltestimonial.testimonial_image');
		$column_search = array('customer_name', 'customer_subname', 'description');

		$sTable = 'tbltestimonial';
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
		$this->db->join('tblsite_master', 'tblsite_master.site_master_id = tbltestimonial.site_master_id', 'JOIN');
		if ($this->session->userdata('site_master_id') > 0) {
			$this->db->where("tbltestimonial.site_master_id", $this->session->userdata('site_master_id'));
		}
		$this->db->order_by('tbltestimonial.testimonial_id', 'desc');
		$rResult = $this->db->get($sTable);
		//echo '<pre>'; print_r( $this->db->last_query() );exit;
		return $rResult->result_array();

	}
	// get testimonial name list
	public function getTestimonialAllDetailsById($testimonial_id)
	{
		$query = $this->db->query("SELECT tbltestimonial.testimonial_id, tbltestimonial.customer_name, tbltestimonial.customer_subname, tbltestimonial.description, tbltestimonial.is_active, tbltestimonial.testimonial_image FROM `tbltestimonial` where `tbltestimonial`.`testimonial_id` = $testimonial_id");
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	public function delete_image($catg_id) // delete 
	{
		$Arrdata = $this->getTestimonialById($catg_id);
		if (isset($Arrdata)) {
			$value = $Arrdata['testimonial_image'];
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


	public function testimonial_list_data() // do not delete this function as it is used for getting testimonial list
	{
		$this->db->select("*");
		$this->db->from('tbltestimonial');

		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

} // :)

?>