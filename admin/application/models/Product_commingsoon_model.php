<?php
class product_commingsoon_model extends CI_Model
{

	public function add($ArrProductData)
	{

		$this->db->insert('tbl_products_comingsoon', $ArrProductData);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	
	public function update($comingsoon_id, $ArrProductData)
	{
		$this->db->where('comingsoon_id', $comingsoon_id);
		$update = $this->db->update('tbl_products_comingsoon', $ArrProductData);
		return $this->db->affected_rows();
	}


	public function delete($comingsoon_id)
	{
		$this->db->where('comingsoon_id', $comingsoon_id);
		// Hard Delete Enabled 
		$this->db->delete('tbl_products_comingsoon');

		return true;
	}

	public function getProductUsingID($comingsoon_id)
	{
		$this->db->select("*");
		$this->db->from('tbl_products_comingsoon');
		$this->db->where('comingsoon_id', $comingsoon_id);
		$this->db->where('is_deleted', 0);

		$query = $this->db->get();
        if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return array();
		}

	}

	public function product_list_data($ids = array())
	{
		$this->db->select("*");
		$this->db->from('tbl_products_comingsoon');
		$this->db->where('is_deleted', 0);
		if (is_array($ids) && count($ids) > 0) {
			$this->db->where_in('comingsoon_id', $ids);
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return array();
		}
	}

	

	public function getProductById($comingsoon_id) // function used for edit product  -- getting product by id
	{
		$this->db->select('*');
		$this->db->from('tbl_products_comingsoon');
		$this->db->where('comingsoon_id', $comingsoon_id);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get();
		//print_r($this->db->last_query());   exit; 
		return $query->result_array();
	}
	public function getProductDetail($product_slug)
	{
		$this->db->select('*');
		$this->db->from('tbl_products_comingsoon');
		$this->db->where('is_deleted', 0);
		$this->db->where('product_slug', $product_slug);
		$query = $this->db->get();
		return $query->result_array();
	}



	public function getProductListData($Arr)
	{
		$column_order = array(null, 'tbl_products_comingsoon.product_name', 'tbl_products_comingsoon.product_details','tbl_products_comingsoon.created_datetime');

		$aColumns = array('comingsoon_id','tbl_products_comingsoon.product_name', 'tbl_products_comingsoon.product_details', 'tbl_products_comingsoon.created_datetime');

		$column_search = array('product_name', 'product_details');

		$sTable = 'tbl_products_comingsoon';
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
			//$this->db->join('tbl_categories', 'tbl_products_comingsoon.category_id = tbl_categories.category_id', 'left'); 


			if (isset($_POST['order'])) { // here order processing
				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} else {
				$this->db->order_by('tbl_products_comingsoon.comingsoon_id', 'desc');
			}
			$this->db->where('tbl_products_comingsoon.is_deleted', 0);
			$rResult = $this->db->get($sTable);
			//echo '<pre>'; print_r( $this->db->last_query() );exit;
			$this->db->select('FOUND_ROWS() AS found_rows');
			$iFilteredTotal = $this->db->get()->row()->found_rows;
			$iTotal = $this->db->count_all($sTable);

			return array('iTotalRecords' => $iTotal, 'iTotalDisplayRecords' => $iFilteredTotal, 'result' => $rResult->result_array());

		}

	}

	public function ExportProductData($Arr)
	{

		$aColumns = array('tbl_products_comingsoon.comingsoon_id', 'tbl_products_comingsoon.product_name', 'tbl_products_comingsoon.product_price', 'tbl_products_comingsoon.sale_price', 'tbl_products_comingsoon.product_image', 'tbl_products_comingsoon.is_active');

		$column_search = array('product_name', 'product_price', 'sale_price');

		$sTable = 'tbl_products_comingsoon';
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
		// CATEGORY SEARCH //- START
		if (isset($_POST['src_category']) && is_array($_POST['src_category']) && count($_POST['src_category']) > 0):

			$c = 1;
			$this->db->group_start();
			foreach ($_POST['src_category'] as $key => $cat_id):
				if ($c == 1)
					$this->db->like('tbl_products_comingsoon.category_id', $cat_id);
				else
					$this->db->or_like('tbl_products_comingsoon.category_id', $cat_id);
				$c++;
			endforeach;
			$this->db->group_end();
		endif;
		// CATEGORY SEARCH //- END


		$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);
		$this->db->group_by('tbl_products_comingsoon.comingsoon_id');

		$this->db->order_by('tbl_products_comingsoon.comingsoon_id', 'desc');
		$rResult = $this->db->get($sTable);
		//echo $this->db->last_query();exit;
		return $rResult->result_array();
	}

	public function get_all_products()
	{
		$this->db->select("*");
		$this->db->from('tbl_products_comingsoon');
		$this->db->where('is_deleted', 0);

		$this->db->order_by('tbl_products_comingsoon.comingsoon_id', 'desc');
		$this->db->order_by("product_name", "asc");


		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

    public function soft_delete_product($id) {
        $this->db->where('comingsoon_id', $id);
        return $this->db->update('tbl_products_comingsoon', array('is_deleted' => 1));
    }

}
?>