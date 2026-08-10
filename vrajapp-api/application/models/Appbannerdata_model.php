<?php

class Appbannerdata_model extends CI_Model

{

	public function getBannerdata()

	{

		$ArrProductData = array();

		$this->db->select('banner_id,category_name,category_logo,banner_image,product_slider_title');

		$this->db->from('tbl_appbannerdata');

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}

	public function getBannerdatabyid($category_id)

	{

		$ArrProductData = array();

		$this->db->select('product_name,product_image,product_link as slug,type');
        $this->db->where('category_id',$category_id);
		$this->db->where('is_active',1);
		$this->db->order_by('product_srno','ASC');
		$this->db->from('appbanner_products');

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}

		public function getConfigurationColor()

	{

		$this->db->select('configuration_value');
$this->db->from('tbl_appconfigurations');
$this->db->where('configuration_key', 'header_color');

$query = $this->db->get();

if ($query->num_rows() > 0) {
    return $query->row()->configuration_value;
} else {
    return '';
}

	}

	public function getConfigurationColorPro()

	{

		$this->db->select('configuration_value');
$this->db->from('tbl_appconfigurations');
$this->db->where('configuration_key', 'product_color');

$query = $this->db->get();

if ($query->num_rows() > 0) {
    return $query->row()->configuration_value;
} else {
    return '';
}

	}



}



?>