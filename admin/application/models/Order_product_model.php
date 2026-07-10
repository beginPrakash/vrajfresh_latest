<?php
class order_product_model extends CI_Model
{

	public function add($ArrOrderProductData)
	{

		$this->db->insert('tbl_order_products', $ArrOrderProductData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function addOrderProLog($ArrOrderProductData)
	{

		$this->db->insert('tbl_order_products_log', $ArrOrderProductData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function update($order_product_id, $ArrOrderProductData)
	{
		$this->db->where('order_product_id', $order_product_id);
		$update = $this->db->update('tbl_order_products', $ArrOrderProductData);
		return $this->db->affected_rows();
	}

	public function updateLog($order_product_id, $ArrOrderProductData)
	{
		$this->db->where('order_product_id', $order_product_id);
		$update = $this->db->update('tbl_order_products_log', $ArrOrderProductData);
		return $this->db->affected_rows();
	}

	public function delete($order_product_id)
	{
		$this->db->where('order_product_id', $order_product_id);
		// $this->db->update('tbl_order_products', array('is_deleted'=>1));
		$this->db->delete('tbl_order_products');
		return true;
	}

	public function deleteProductrefunded($order_product_id)
	{
		$this->db->where('order_product_id', $order_product_id);
		// $this->db->update('tbl_order_products', array('is_deleted'=>1));
		$this->db->delete('tbl_order_products');
		return true;
	}

	public function updateOldQty($order_id)
	{
		$sql = "UPDATE tbl_order_products SET old_qty=qty WHERE old_qty IS NULL AND order_id=" . $order_id;
		$query = $this->db->query($sql);
		return $this->db->affected_rows();
	}

	public function updateOldQtyLog($order_id)
	{
		$sql = "UPDATE tbl_order_products_log SET old_qty=qty,qty_order=qty WHERE old_qty IS NULL AND order_id=" . $order_id;
		$query = $this->db->query($sql);
		return $this->db->affected_rows();
	}


	public function getOrderProductByOrderId($order_id, $parm = "*", $searchString = '')
	{
                /*
                $this->db->select('tbl_order_products.,tbl_products.,tblproduct_variant.variant_sku,tblproduct_variant.product_variant_size,tbl_categories.category_name');
                $this->db->from('tbl_order_products');
                $this->db->join('tbl_products', 'tbl_products.product_id = tbl_order_products.product_id', 'left');
                $this->db->join('tblproduct_variant', 'tblproduct_variant.id = tbl_order_products.product_variant_id', 'left');
                $this->db->join('tbl_categories_products_mapping', 'tbl_categories_products_mapping.product_id = tbl_order_products.product_id', 'left');
                $this->db->join('tbl_categories', 'tbl_categories.category_id = tbl_categories_products_mapping.category_id', 'left');
                $this->db->where('tbl_order_products.order_id', $order_id);
                $this->db->where('tbl_order_products.is_deleted', 0);
                $this->db->group_by('tbl_order_products.product_variant_id');
                $this->db->order_by('tbl_categories.category_name', 'asc');

                if ($searchString != '') {
                        $this->db->where($searchString, NULL, FALSE);
                }
                $query = $this->db->get();
*/
                $sql = "SELECT op.*,`tbl_products`.*,`tblproduct_variant`.`variant_sku`,`tblproduct_variant`.`product_variant_size`,(SELECT `tbl_categories`.`category_name` FROM `tbl_categories_products_mapping` JOIN `tbl_categories` ON `tbl_categories`.`category_id` = `tbl_categories_products_mapping`.`category_id` AND `tbl_categories_products_mapping`.`product_id` = op.`product_id` LIMIT 1 ) AS category_name FROM `tbl_order_products` AS op JOIN `tbl_products` ON `tbl_products`.`product_id` = op.`product_id` LEFT JOIN `tblproduct_variant` ON `tblproduct_variant`.`id` = op.`product_variant_id` WHERE op.`order_id` = '".$order_id."' AND op.`is_deleted` = 0 ORDER BY category_name ASC";

                $query = $this->db->query($sql);
                // echo $this->db->last_query();exit;
                if ($query->num_rows() > 0) {
                        return $query->result_array();
                } else {
                        return false;
                }
        }

		public function getOrderProductLogByOrderId($order_id, $parm = "*", $searchString = '')
	{
			$sql = "SELECT op.*,`tbl_products`.*,`tblproduct_variant`.`variant_sku`,`tblproduct_variant`.`product_variant_size`,(SELECT `tbl_categories`.`category_name` FROM `tbl_categories_products_mapping` JOIN `tbl_categories` ON `tbl_categories`.`category_id` = `tbl_categories_products_mapping`.`category_id` AND `tbl_categories_products_mapping`.`product_id` = op.`product_id` LIMIT 1 ) AS category_name FROM `tbl_order_products_log` AS op JOIN `tbl_products` ON `tbl_products`.`product_id` = op.`product_id` LEFT JOIN `tblproduct_variant` ON `tblproduct_variant`.`id` = op.`product_variant_id` WHERE op.`order_id` = '".$order_id."' AND op.`is_deleted` = 0 ORDER BY category_name ASC";

			$query = $this->db->query($sql);
			// echo $this->db->last_query();exit;
			if ($query->num_rows() > 0) {
					return $query->result_array();
			} else {
					return false;
			}
	}

		public function getCategoryWiseOrderProducts($order_ids, $parm = "*", $searchString = '')
		{		
			$sql = "SELECT op.*,`tbl_products`.*,`tblproduct_variant`.`variant_sku`,`tblproduct_variant`.`product_variant_size`,(SELECT `tbl_categories`.`category_name` FROM `tbl_categories_products_mapping` JOIN `tbl_categories` ON `tbl_categories`.`category_id` = `tbl_categories_products_mapping`.`category_id` AND `tbl_categories_products_mapping`.`product_id` = op.`product_id` LIMIT 1 ) AS category_name FROM `tbl_order_products` AS op JOIN `tbl_products` ON `tbl_products`.`product_id` = op.`product_id` LEFT JOIN `tblproduct_variant` ON `tblproduct_variant`.`id` = op.`product_variant_id` WHERE op.`order_id` in (".implode(',',$order_ids).") AND op.`is_deleted` = 0 ORDER BY category_name,product_sku,variant_sku ASC";

			$query = $this->db->query($sql);
			// echo $this->db->last_query();exit;
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return false;
			}
		}

	public function getOrderProductQueryString($searchString = '', $whrArray = array('is_active' => '1'))
	{
		$this->db->select("*");
		$this->db->from('tbl_order_products');
		if ($searchString != '') {
			$this->db->where($searchString, NULL, FALSE);
		}
		if (is_array($whrArray) && count($whrArray) > 0)
			$this->db->where($whrArray);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}


}
?>