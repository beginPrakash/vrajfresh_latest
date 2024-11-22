<?php

class menu_master_model extends CI_Model

{



	public function add($data)

	{



		$this->db->insert('tbl_menu_master', $data);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return 0;

		}

	}





	public function update($menu_id, $arrData)

	{

		$this->db->where('menu_id', $menu_id);

		$update = $this->db->update('tbl_menu_master', $arrData);

		return $this->db->affected_rows();

	}



	public function delete($menu_id)

	{

		$this->db->where('menu_id', $menu_id);

		// $this->db->update('tbl_menu_master', array('is_deleted'=>1));

		$this->db->delete('tbl_menu_master');

		if ($this->db->affected_rows() > 0) {

			return true;

		} else {

			return false;

		}

	}



	public function getMenuDetailsUsingID($menu_id)

	{

		$this->db->select("*");

		$this->db->from('tbl_menu_master');

		$this->db->where('menu_id', $menu_id);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array()[0];

		}

	}



	public function getMenu($is_active = '')

	{

		$ArrProductData = array();

		$this->db->select("*");

		$this->db->where('is_deleted', 0);

		$this->db->from('tbl_menu_master');



		if ($is_active != "") {

			$this->db->where("tbl_menu_master.is_active", $is_active);

		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}



	public function addMenuItems($data)

	{



		$this->db->insert('tbl_menu_item', $data);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return 0;

		}

	}

	public function deleteMenuItems($menu_id)

	{

		$this->db->where('menu_id', $menu_id);

		$this->db->delete('tbl_menu_item');

	}

	public function getMenuItemsUsingMenuId($menu_id)

	{

		$this->db->select("m.*,c.category_name");

		$this->db->from('tbl_menu_item m')->join('tbl_categories c', 'm.category_id=c.category_id');

		$this->db->where('m.menu_id', $menu_id);

		$this->db->where('m.parent_menu_item_id', 0);

		$this->db->where('m.is_deleted', 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}

	public function getSubMenuItemsUsingMenuId($menu_id)

	{

		$this->db->select("*");

		$this->db->from('tbl_menu_item');

		$this->db->where('parent_menu_item_id', $menu_id);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}

}



?>