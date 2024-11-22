<?php
class newsletter_subscriber_model extends CI_Model
{

	#SUBSCRIBE NEWS LETTER
	public function subscribe_newsletter($email, $name = '', $from_which_place = '')
	{
		if ($email != '') {
			$data['email'] = $email;
			$data['name'] = $name;
			$data['from_which_place'] = $from_which_place;

			$id = $this->email_already_exist_in_newsletter($email);
			if ($id > 0) {
				$this->db->where('newsletter_subscriber_id ', $id);
				$this->db->update('tblnewsletter_subscriber', $data);
			} else {
				$data['created_datetime'] = date('Y-m-d h:i:s');
				$insert = $this->db->insert('tblnewsletter_subscriber', $data);
				//echo $this->db->last_query();exit;
			}
			return $id;
		}
	}

	#CHECK email ALREADY EXIST IN DATABASE
	public function email_already_exist_in_newsletter($email)
	{
		$this->db->select('*')->from('tblnewsletter_subscriber');
		$this->db->where('email', $email);
		$query_result = $this->db->get();

		$data = $query_result->result_array();

		if (is_array($data) && count($data) > 0) {
			return $data[0]['newsletter_subscriber_id'];
		}
		return 0;
	}

	public function add($ArrNewsletterSubscriberData)
	{

		$this->db->insert('tblnewsletter_subscriber', $ArrNewsletterSubscriberData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}

	}

	public function update($newsletter_subscriber_id, $ArrNewsletterSubscriberData)
	{
		$this->db->where('newsletter_subscriber_id', $newsletter_subscriber_id);
		$update = $this->db->update('tblnewsletter_subscriber', $ArrNewsletterSubscriberData);
		return $this->db->affected_rows();
	}
	public function delete($newsletter_subscriber_id)
	{
		$this->db->where('newsletter_subscriber_id', $newsletter_subscriber_id);
		$this->db->delete('tblnewsletter_subscriber');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getNewsletterSubscriberUsingID($newsletter_subscriber_id)
	{
		$this->db->select("*");
		$this->db->from('tblnewsletter_subscriber');
		$this->db->where('newsletter_subscriber_id', $newsletter_subscriber_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}


	public function getNewsletterSubscriberQueryString($searchString)
	{
		$this->db->select("*");
		$this->db->from('tblnewsletter_subscriber');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}



	public function getNewsletterSubscriberListData($Arr)
	{

		$column_order = array(null, 'tblnewsletter_subscriber.newsletter_subscriber_id', 'tblnewsletter_subscriber.created_datetime', 'tblnewsletter_subscriber.name', 'tblnewsletter_subscriber.email', 'status', 'tblnewsletter_subscriber.from_which_place');

		$aColumns = array('tblnewsletter_subscriber.newsletter_subscriber_id', 'tblnewsletter_subscriber.created_datetime', 'tblnewsletter_subscriber.name', 'tblnewsletter_subscriber.email', 'status', 'tblnewsletter_subscriber.from_which_place');

		$column_search = array('tblnewsletter_subscriber.newsletter_subscriber_id', 'tblnewsletter_subscriber.created_datetime', 'tblnewsletter_subscriber.name', 'tblnewsletter_subscriber.email', 'status', 'tblnewsletter_subscriber.from_which_place');

		$sTable = 'tblnewsletter_subscriber';
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
			$this->db->where('DATE(tblnewsletter_subscriber.created_datetime) >=', date('Y-m-d', strtotime(@$_POST['txtSearchFrom'])));
			$this->db->where('DATE(tblnewsletter_subscriber.created_datetime) <=', date('Y-m-d', strtotime(@$_POST['txtSearchTo'])));
		}
		if (@$_POST['txtSearchFrom'] != "" && @$_POST['txtSearchTo'] == "") {
			$this->db->where('DATE(tblnewsletter_subscriber.created_datetime) >=', date('Y-m-d', strtotime(@$_POST['txtSearchFrom'])));
		}
		if (@$_POST['txtSearchTo'] != "" && @$_POST['txtSearchFrom'] == "") {
			$this->db->where('DATE(tblnewsletter_subscriber.created_datetime) <=', date('Y-m-d', strtotime(@$_POST['txtSearchTo'])));
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
				$this->db->order_by('tblnewsletter_subscriber.newsletter_subscriber_id', 'desc');
			}
			$rResult = $this->db->get($sTable);
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}



	public function newsletterUnsubscribe($data, $email, $chooseNewsletter = '')
	{
		if ($chooseNewsletter == 'NO') {
			$this->db->select('*');
			$this->db->from('tblnewsletter_subscriber');
			$this->db->where(array('newsletter_email' => '0', 'sales_email' => '0', 'special_offer_email' => '0', 'status' => 0, 'email' => $email));

			$query = $this->db->get();

			if ($query->num_rows() > 0) //unsubscribed
			{
				return 'unsubscribed';
			} else {
				$data['unsubscribe_date'] = date('Y-m-d');

				$this->db->where('email', $email);
				$update = $this->db->update('tblnewsletter_subscriber', $data);

			}
		} else {

			$data['modified_datetime'] = date('Y-m-d h:i:s');

			$this->db->where('email', $email);
			$update = $this->db->update('tblnewsletter_subscriber', $data);
		}
		return $this->db->affected_rows();
	}

	public function ExportNewsletterSubscriberData($Arr)
	{

		$aColumns = array('tblnewsletter_subscriber.newsletter_subscriber_id', 'tblnewsletter_subscriber.created_datetime', 'tblnewsletter_subscriber.name', 'tblnewsletter_subscriber.email', 'status', 'tblnewsletter_subscriber.from_which_place');

		$column_search = array('tblnewsletter_subscriber.newsletter_subscriber_id', 'tblnewsletter_subscriber.created_datetime', 'tblnewsletter_subscriber.name', 'tblnewsletter_subscriber.email', 'status', 'tblnewsletter_subscriber.from_which_place');

		$sTable = 'tblnewsletter_subscriber';
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
		$this->db->group_by('tblnewsletter_subscriber.newsletter_subscriber_id');

		$this->db->order_by('tblnewsletter_subscriber.newsletter_subscriber_id', 'desc');
		$rResult = $this->db->get($sTable);
		return $rResult->result_array();
	}
}

?>