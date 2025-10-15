<?php

class contact_inquiry_model extends CI_Model

{



	public function add($ArrNotificationData)

	{



		$this->db->insert('tbl_contactus', $ArrNotificationData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}



	}



	public function update($contact_id, $ArrNotificationData)

	{

		$this->db->where('id', $contact_id);

		$update = $this->db->update('tbl_contactus', $ArrNotificationData);

		return $this->db->affected_rows();

	}



	public function delete($contact_id)

	{

		$this->db->where('id', $contact_id);

		// $this->db->update('tbl_contactus', array('is_deleted'=>1));

		$this->db->delete('tbl_contactus');

		if ($this->db->affected_rows() > 0) {

			return true;

		} else {

			return false;

		}

	}



	public function getContactInquiryById($contact_id)

	{

		$this->db->select("*");

		$this->db->from('tbl_contactus');

		$this->db->where('id', $contact_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array()[0];

		}

	}





	public function getContactInquiryUsingQueryString($searchString)

	{

		$this->db->select("*");

		$this->db->from('tbl_contactus');

		if ($searchString != '') {

			$this->db->where($searchString, NULL, FALSE);

		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



	public function getContactInquiryListData()

	{



		$column_order = array(null, 'tbl_contactus.id', 'tbl_contactus.created_datetime', 'tbl_contactus.first_name', 'tbl_contactus.last_name', 'tbl_contactus.email', 'tbl_contactus.phone_no');



		$aColumns = array('tbl_contactus.id', 'tbl_contactus.created_datetime', 'tbl_contactus.first_name', 'tbl_contactus.last_name', 'tbl_contactus.email', 'tbl_contactus.phone_no');



		$column_search = array('tbl_contactus.id', 'tbl_contactus.created_datetime', 'tbl_contactus.first_name', 'tbl_contactus.last_name', 'tbl_contactus.email', 'tbl_contactus.phone_no', 'tbl_contactus.message');



		$sTable = 'tbl_contactus';

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

		if (@$_POST['txtSearchFrom'] != "" && @$_POST['txtSearchTo'] != "") {

			$this->db->where('DATE(tbl_contactus.created_datetime) >=', date('Y-m-d', strtotime(@$_POST['txtSearchFrom'])));

			$this->db->where('DATE(tbl_contactus.created_datetime) <=', date('Y-m-d', strtotime(@$_POST['txtSearchTo'])));

		}

		if (@$_POST['txtSearchFrom'] != "" && @$_POST['txtSearchTo'] == "") {

			$this->db->where('DATE(tbl_contactus.created_datetime) >=', date('Y-m-d', strtotime(@$_POST['txtSearchFrom'])));

		}

		if (@$_POST['txtSearchTo'] != "" && @$_POST['txtSearchFrom'] == "") {

			$this->db->where('DATE(tbl_contactus.created_datetime) <=', date('Y-m-d', strtotime(@$_POST['txtSearchTo'])));

		}





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

				$this->db->order_by('tbl_contactus.id', 'desc');

			}


			$rResult = $this->db->get($sTable);

			//echo '<pre>'; print_r( $this->db->last_query() );exit;

			$this->db->select('FOUND_ROWS() AS found_rows');

			$iFilteredTotal = $this->db->get()->row()->found_rows;

			$iTotal = $this->db->count_all($sTable);



			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());



		}



	}



	public function ExportContactInquiryData($Arr)

	{



		$aColumns = array('tbl_contactus.id', 'tbl_contactus.created_datetime', 'tbl_contactus.contact_first_name', 'tbl_contactus.contact_company_name', 'tbl_contactus.contact_email', 'tbl_contactus.contact_phone_number', 'contact_domain_name', 'contact_country_name', 'tbl_contactus.ip_address', 'tbl_contactus.contact_last_name');



		$column_search = array('tbl_contactus.id', 'tbl_contactus.created_datetime', 'tbl_contactus.contact_first_name', 'tbl_contactus.contact_company_name', 'tbl_contactus.contact_email', 'tbl_contactus.contact_phone_number', 'contact_domain_name', 'contact_country_name', 'tbl_contactus.ip_address', 'tbl_contactus.contact_last_name');



		$sTable = 'tbl_contactus';

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

		$this->db->group_by('tbl_contactus.contact_id');



		$this->db->where('is_deleted', 0);

		$this->db->order_by('tbl_contactus.contact_id', 'desc');

		$rResult = $this->db->get($sTable);

		//echo $this->db->last_query();exit;

		return $rResult->result_array();

	}

}



?>