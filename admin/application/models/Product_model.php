<?php

class product_model extends CI_Model

{



	public function add($ArrProductData)

	{



		$this->db->insert('tbl_products', $ArrProductData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}

	}

	public function add_images($ArrProductData)

	{



		$this->db->insert('tbl_product_images', $ArrProductData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}

	}

	public function add_variants($ArrVariantData)

	{

		$this->db->insert('tblproduct_variant', $ArrVariantData);

		if ($this->db->affected_rows() > 0) {

			return $this->db->insert_id();

		} else {

			return false;

		}

	}

	public function update($product_id, $ArrProductData)

	{

		$this->db->where('product_id', $product_id);

		$update = $this->db->update('tbl_products', $ArrProductData);

		return $this->db->affected_rows();

	}





	public function delete($product_id)

	{

		$this->delete_image($product_id);

		$this->db->where('product_id', $product_id);



		// Soft Delete Disabled.

		// $this->db->update('tbl_products', array('is_deleted'=>1));



		// Hard Delete Enabled 

		$this->db->delete('tbl_products');



		return true;

	}



	public function getProductUsingID($product_id)

	{

		$ArrProductData = array();

		$this->db->select("*");

		$this->db->from('tbl_products');

		$this->db->where('product_id', $product_id);

		$this->db->where('is_deleted', 0);



		$query = $this->db->get();



		if ($query->num_rows() > 0) {

			$ArrProductData = $query->result_array()[0];

			$ArrProductData['product_price'] = number_format($ArrProductData['product_price'], 2, '.', '');

			$ArrProductData['sale_price'] = number_format($ArrProductData['sale_price'], 2, '.', '');



		}

		return $ArrProductData;

	}













	public function checkDuplicate($product_id, $product_name)

	{

		$query = $this->db->query("SELECT * FROM `tbl_products` WHERE `product_name` = '" . $product_name . "' and `product_id` != '" . $product_id . "'");

		if ($query->num_rows() > 0) {

			return true;

		} else {

			return false;

		}

	}



	public function getVariantProduct()

	{

		$query = $this->db->query("SELECT * FROM `tbl_products` WHERE `is_active` = '1' AND product_id IN (SELECT product_id FROM tblproduct_variant)");

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}

	public function getNonVariantProduct()

	{

		$query = $this->db->query("SELECT * FROM `tbl_products` WHERE `is_active` = '1' AND product_id NOT IN (SELECT product_id FROM tblproduct_variant)");

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}





	public function product_list_data($ids = array())

	{

		$this->db->select("*");

		$this->db->from('tbl_products');

		$this->db->where('is_deleted', 0);

		if (is_array($ids) && count($ids) > 0) {

			$this->db->where_in('product_id', $ids);

		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}



	public function product_list_data_with_variant()

	{

		$this->db->select("tbl_products.product_id,tbl_products.product_name,tbl_products.product_price,tbl_products.sale_price,tblproduct_variant.id,tblproduct_variant.variant_price,tblproduct_variant.product_variant_size,tbl_products.product_tax");

		$this->db->from('tbl_products');

		$this->db->join('tblproduct_variant', 'tblproduct_variant.product_id = tbl_products.product_id', 'left');

		$this->db->where('tbl_products.is_deleted', 0);

		//$this->db->where('tblproduct_variant.is_deleted',0);



		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return array();

		}

	}



	public function getProductQueryString($columns = "*", $searchString = '', $order_by_col = 'tbl_products.product_id', $order_by = 'desc', $page = 0)

	{

		$no_of_records_per_page = NUMBER_OF_PAGE;

		$this->db->select($columns);

		$this->db->from('tbl_products');

		$this->db->where('is_deleted', 0);

		if ($searchString != '') {

			$this->db->where($searchString, NULL, FALSE);

		}

		$this->db->order_by($order_by_col, $order_by);

		if ($page > 0) {

			$start = ($page - 1) * $no_of_records_per_page;

			$this->db->limit($no_of_records_per_page, $start);

		}

		$query = $this->db->get();

		//print_r($this->db->last_query());

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}



	public function getProductBySlug($product_slug)

	{

		$this->db->select('product_id');

		$this->db->from('tbl_products');

		$this->db->where('product_slug', trim($product_slug));

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return 1;

		} else {

			return 0;

		}

	}



	public function getProductBySKU($product_sku)

	{

		$this->db->select('product_id');

		$this->db->from('tbl_products');

		$this->db->where('product_sku', trim($product_sku));

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return 1;

		} else {

			return 0;

		}

	}

	public function getProductBySKUgetID($product_sku)

	{

		$this->db->select('product_id');

		$this->db->from('tbl_products');

		$this->db->where('product_sku', trim($product_sku));

		$query = $this->db->get();

		return $query->result_array();

	}



	public function getProductVariantBySKU($product_sku)

	{

		$this->db->select('product_id');

		$this->db->from('tblproduct_variant');

		$this->db->where('variant_sku', trim($product_sku));

		// 		$this->db->where('product_id', $product_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return 1;

		} else {

			return 0;

		}

	}



	public function getProductById($product_id) // function used for edit product  -- getting product by id

	{

		$this->db->select('*');

		$this->db->from('tbl_products');

		$this->db->where('product_id', $product_id);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		//print_r($this->db->last_query());   exit; 

		return $query->result_array();

	}

	public function getProductDetail($product_slug)

	{

		$this->db->select('*');

		$this->db->from('tbl_products');

		$this->db->where('is_deleted', 0);

		$this->db->where('product_slug', $product_slug);

		$query = $this->db->get();

		return $query->result_array();

	}







	public function getProductListData($Arr)

	{



		$column_order = array(null, 'tbl_products.product_sku', 'tbl_products.product_name', 'tbl_products.product_price', 'tbl_products.sale_price', null, 'tbl_products.is_active');



		$aColumns = array('tbl_products.product_sku', 'tbl_products.product_name', 'tbl_products.product_price', 'tbl_products.sale_price','tbl_products.unit_cost', 'tbl_products.product_image', 'tbl_products.is_active', 'tbl_products.product_id');



		$column_search = array('product_name', 'product_sku', 'product_sub_name', 'product_slug', 'product_weight_gms', 'product_price', 'sale_price', 'product_style', 'product_description', 'meta_title', 'meta_description');



		$sTable = 'tbl_products';

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

		// CATEGORY SEARCH

		if (isset($_POST['src_category']) && is_array($_POST['src_category']) && count($_POST['src_category']) > 0):

			$src_category = $_POST['src_category'];

			$this->db->join(

				'tbl_categories_products_mapping',

				'tbl_categories_products_mapping.product_id = tbl_products.product_id',

				'left'

			);

			$this->db->where_in('tbl_categories_products_mapping.category_id', $src_category);

		endif;



		// CATEGORY SEARCH



		if (@$_REQUEST['columns'] != "") {

			// set columns start

			if (@$_REQUEST['columns'] != "") {

				if (@$_REQUEST['length'] != -1) {

					$this->db->limit($_REQUEST['length'], $_REQUEST['start']);

				}

			}

			// Select Data

			$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);

			//$this->db->join('tbl_categories', 'tbl_products.category_id = tbl_categories.category_id', 'left'); 





			if (isset($_POST['order'])) { // here order processing

				$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);

			} else {

				$this->db->order_by('tbl_products.product_id', 'desc');

			}

			$this->db->where('tbl_products.is_deleted', 0);

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



		$aColumns = array('tbl_products.product_id', 'tbl_products.product_name', 'tbl_products.product_price', 'tbl_products.sale_price', 'tbl_products.product_image', 'tbl_products.is_active');



		$column_search = array('product_name', 'product_price', 'sale_price');



		$sTable = 'tbl_products';

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

					$this->db->like('tbl_products.category_id', $cat_id);

				else

					$this->db->or_like('tbl_products.category_id', $cat_id);

				$c++;

			endforeach;

			$this->db->group_end();

		endif;

		// CATEGORY SEARCH //- END





		$this->db->select('SQL_CALC_FOUND_ROWS ' . str_replace(' , ', ' ', implode(', ', $aColumns)), false);

		//$this->db->join('tblproduct_preference_type', 'tbl_products.product_id = tblproduct_preference_type.product_id', 'left');

		//$this->db->select('group_concat(DISTINCT tbl_categories.category_name SEPARATOR ",") as Category');

		//$this->db->join('tbl_categories', 'FIND_IN_SET(tbl_categories.category_id,tbl_products.category_id) > 0', 'left',FALSE); 

		$this->db->group_by('tbl_products.product_id');



		$this->db->order_by('tbl_products.product_id', 'desc');

		$rResult = $this->db->get($sTable);

		//echo $this->db->last_query();exit;

		return $rResult->result_array();

	}



	public function delete_image($product_id, $image_type = '')

	{

		$Arrdata = $this->getProductUsingID($product_id);

		if (isset($Arrdata['product_image'])) {

			$product_image = $Arrdata['product_image'];

			$product_image = $Arrdata['product_image'];

			if ($image_type == "full") {

				if (trim($product_image) != "") {

					$filename = 'uploads/products/' . $product_image;

					if (file_exists($filename)) {

						unlink($filename);

					}

				}

			}

			if ($image_type == "thumb") {

				if (trim($product_image) != "") {

					$filename = 'uploads/products/' . $product_image;

					if (file_exists($filename)) {

						unlink($filename);

					}

				}

			}

			if ($image_type == '') {

				if (trim($product_image) != "") {

					$filename = 'uploads/products/' . $product_image;

					if (file_exists($filename)) {

						unlink($filename);

					}

				}

				if (trim($product_image) != "") {

					$filename = 'uploads/products/' . $product_image;

					if (file_exists($filename)) {

						unlink($filename);

					}

				}

			}



			return true;

		} else {

			return FALSE;

		}

	}



	public function get_all_products()

	{

		$this->db->select("*");

		$this->db->from('tbl_products');

		$this->db->where('is_deleted', 0);



		$this->db->order_by('tbl_products.product_id', 'desc');

		$this->db->order_by("product_name", "asc");





		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		}

	}







	public function getProductCategories($product_id = 0)

	{

		$result = array();

		$category_id = "";



		if ($product_id > 0) {

			$ArrData = $this->getProductById($product_id);

			if (is_array($ArrData) && count($ArrData) > 0) {

				$category_id = $ArrData[0]['category_id'];

				if ($category_id != "") {

					$Arrcat = explode(',', $category_id);

					$Arrcat = array_filter($Arrcat);

					if (count($Arrcat) > 0) {

						foreach ($Arrcat as $key => $value) {

							$tmpArrData = $this->category_model->getCategoryById($value, 'category_id,category_name');

							$result[] = array('category_id' => $tmpArrData['category_id'], 'category_name' => $tmpArrData['category_name']);

						}

						return $result;

					} else {

						return $result;

					}

				}

			}



		} else {

			return $result;

		}



	}





	public function getProductJsonDropDown($term = '')

	{



		$this->db->select('*');

		$this->db->from('tbl_products');

		if ($term != "") {

			$this->db->like('product_name', $term);

			$this->db->or_like('product_sub_name', $term);

		}

		$this->db->where('tbl_products.is_deleted', 0);

		$this->db->order_by('product_name', 'asc', false);

		$this->db->limit(10);

		$query = $this->db->get();

		//echo $this->db->last_query();exit;

		if ($query->num_rows() > 0) {

			//$array_data[] = array('id' => '', 'text'=> 'Select What');

			foreach ($query->result_array() as $aRow) {

				$array_data[] = array(

					'id' => $aRow['product_id'],

					'text' => $aRow['product_name']

				);



			}

			return $array_data;

		} else {

			return array();

		}

	}



	public function checkSku($product_sku_name)

	{

		$this->db->select('*');

		$this->db->from('tbl_products');

		$this->db->where('product_sku', $product_sku_name);

		$this->db->where('is_deleted', 0);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {

			return $query->result_array();

		} else {

			return false;

		}

	}



} //:)

?>