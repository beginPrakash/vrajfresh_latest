<?php
class common_model extends CI_Model
{

	public function updateIsActive($primary_id, $column_name, $table_name)
	{
		$this->db->select("is_active");
		$this->db->from($table_name);
		$this->db->where($column_name, $primary_id);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$dataArr = $query->result_array()[0];
			if ($dataArr['is_active'] == "0") {
				$this->db->where($column_name, $primary_id);
				$arrData = array('is_active' => '1');
				$this->db->update($table_name, $arrData);
				if ($this->db->affected_rows() > 0) {
					return array('status' => '1', 'responce' => true);
				} else {
					return false;
				}
			} else {
				$this->db->where($column_name, $primary_id);
				$arrData = array('is_active' => '0');
				$this->db->update($table_name, $arrData);
				if ($this->db->affected_rows() > 0) {
					return array('status' => '0', 'responce' => true);
				} else {
					return false;
				}
			}
		}

	}

	public function select($vars, $table_name, $where = "", $order_by = "", $group_by = "")
	{
		$this->db->select($vars);
		$this->db->from($table_name);
		$this->db->where($where, NULL, FALSE);
		$this->db->order_by($order_by, "asc");
		$this->db->group_by($group_by);
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function delete_multiple_records($primary_id, $column_name, $tablename)
	{
		$this->db->where($column_name, $primary_id);
		$this->db->delete($tablename);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/* upload mockup files*/
	function upload_attachment_file($field_name, $user_id, $folder_name = '')
	{
		#Create customer message  folder
		$message_folder_path = "uploads/message/" . $user_id . "/";

		if (!is_dir($message_folder_path)) {
			mkdir($message_folder_path, 0755, TRUE);
		}
		if ($folder_name != '') {
			$message_folder_path = "uploads/message/" . $user_id . "/" . $folder_name . "/";

			if (!is_dir($message_folder_path)) {
				mkdir($message_folder_path, 0755, TRUE);
			}
		}
		/* FILE UPLOAD */
		$config['upload_path'] = $message_folder_path;
		$config['allowed_types'] = 'mp3|gif|jpg|jpeg|png|txt|pdf|xls|xlx|doc|docx|xlsx|csv|ods|odt|zip|psd|ai|cdr|eps|odt|rar|dwg|dxf|dwt|dws|rvt|rte|pln|max|dae|3ds|fbx|obj|vsdx|skp|skb|xlsx';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($field_name)) {
			$error = array('error' => $this->upload->display_errors());

			$temp = array();
			return $temp;
		} else {
			$data = array('upload_data' => $this->upload->data());
			/* FILE UPLOAD END */
			return $data['upload_data'];
		}

	}

	public function updateAnyColumn($primary_id, $column_name, $table_name, $col_to_update)
	{
		$this->db->select($col_to_update);
		$this->db->from($table_name);
		$this->db->where($column_name, $primary_id);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$dataArr = $query->result_array()[0];
			if ($dataArr[$col_to_update] == "0") {
				$this->db->where($column_name, $primary_id);
				$arrData = array($col_to_update => '1');
				$this->db->update($table_name, $arrData);
				if ($this->db->affected_rows() > 0) {
					return array('status' => '1', 'responce' => true);
				} else {
					return false;
				}
			} else {
				$this->db->where($column_name, $primary_id);
				$arrData = array($col_to_update => '0');
				$this->db->update($table_name, $arrData);
				if ($this->db->affected_rows() > 0) {
					return array('status' => '0', 'responce' => true);
				} else {
					return false;
				}
			}
		}

	}

	/* upload user attachment files*/
	function uploadAnyFileAttachment($field_name, $user_id, $folder_path, $allowd_type)
	{
		#Create customer message  folder		
		if (!is_dir($folder_path)) {
			mkdir($folder_path, 0755, TRUE);
		}
		/* FILE UPLOAD */
		$config['upload_path'] = $folder_path;
		$config['allowed_types'] = $allowd_type;

		$this->load->library('upload', $config);
		if (!$this->upload->do_upload($field_name)) {
			$error = array('error' => $this->upload->display_errors());
			return $error;
		} else {
			$data = array('upload_data' => $this->upload->data());
			return $data['upload_data'];
		}

	}

}

?>