<?php
class product_variant_master_model extends CI_Model
{

	public function add($data)
	{

		$this->db->insert('tblproduct_variant_master', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}

	public function update($product_variant_id, $arrData)
	{
		$this->db->where('product_variant_id', $product_variant_id);
		$update = $this->db->update('tblproduct_variant_master', $arrData);
		return $this->db->affected_rows();
	}

	public function delete($product_variant_id)
	{
		$this->db->where('product_variant_id', $product_variant_id);
		// $this->db->update('tblproduct_variant_master', array('is_deleted'=>1));
		$this->db->delete('tblproduct_variant_master');
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function getProductVariantDetailsUsingID($product_variant_id)
	{
		$this->db->select("*");
		$this->db->from('tblproduct_variant_master');
		$this->db->where('is_deleted', 0);
		$this->db->where('product_variant_id', $product_variant_id);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array()[0];
		}
	}

	public function getProductVariant($is_active = '1')
	{
		$ArrProductData = array();
		$this->db->select("*");
		$this->db->where('is_deleted', 0);
		$this->db->from('tblproduct_variant_master');

		if ($is_active != "") {
			$this->db->where("tblproduct_variant_master.is_active", $is_active);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}





	//- Export to csv of product_variant - START
	public function ExportProductVariantListData($Arr)
	{
		$column_search = array('tblproduct_variant_master.product_variant_id', 'tblproduct_variant_master.created_datetime', 'tblproduct_variant_master.product_variant_name', 'tblproduct_variant_master.option1', 'tblproduct_variant_master.is_active', 'tblproduct_variant_master.option2', 'tblproduct_variant_master.option3', 'tblproduct_variant_master.option4');

		$aColumns = array('tblproduct_variant_master.product_variant_id', 'tblproduct_variant_master.created_datetime', 'tblproduct_variant_master.product_variant_name', 'tblproduct_variant_master.option1', 'tblproduct_variant_master.is_active');

		$sTable = 'tblproduct_variant_master';

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
		$this->db->order_by('tblproduct_variant_master.product_variant_id', 'DESC');
		$rResult = $this->db->get($sTable);

		// echo $this->db->last_query();		
		return $rResult->result_array();

	}
	//- Export to csv of product_variant - END

	/* :)END */
}

?>