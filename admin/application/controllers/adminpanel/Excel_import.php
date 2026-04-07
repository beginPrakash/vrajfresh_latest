<?php

ini_set('memory_limit', '1024M');

if (!defined('BASEPATH'))

	exit('No direct script access allowed');

error_reporting(1);

ini_set('display_errors', 1);

/* error_reporting(-1);*/



class Excel_import extends CI_Controller

{

	public function __construct()

	{

		parent::__construct();

		$this->load->model('product_model');

		$this->load->model('brand_model');

		$this->load->model('category_model');

		$this->load->model('product_category_mapping_model');

		$this->load->model('product_tag_model');

		$this->load->model('tag_model');

		$this->load->library('excel');

	}



	function index()

	{

		$ArrPageData['cms_title'] = 'Import Product';

		$ArrPageData['view_name'] = 'excel_import.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}

	function meta_index()

	{

		$ArrPageData['cms_title'] = 'Import Product Meta';

		$ArrPageData['view_name'] = 'metaexcel_import.php';

		$this->load->view('admin_panel/admin_panel', $ArrPageData);

	}





	function import()

	{

		if (isset($_FILES["file"]["name"])) {

			$path = $_FILES["file"]["tmp_name"];

			$object = PHPExcel_IOFactory::load($path);



			$product_insert_count = 0;

			$variant_product_insert_count = 0;

			$product_failed_count = 0;



			$product_already_there = "<br><b>The below products has not imported:</b>";





			foreach ($object->getWorksheetIterator() as $worksheet) {

				$product_id_for_variant = 0;

				$old_product_name = '';

				$old_product_id = 0;

				$highestRow = $worksheet->getHighestRow();

				$highestColumn = $worksheet->getHighestColumn();

				for ($row = 2; $row <= $highestRow; $row++) {

					$product_slug = cleanSlug($worksheet->getCellByColumnAndRow(1, $row)->getValue());

					$product_description = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

					$meta_title = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

					$meta_description = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

					$health_benefit_title = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

					$health_benefits = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

					$ingredients = $worksheet->getCellByColumnAndRow(7, $row)->getValue();

					$usage_instructions = $worksheet->getCellByColumnAndRow(8, $row)->getValue();

					$storage_information = $worksheet->getCellByColumnAndRow(9, $row)->getValue();

					$faqs = $worksheet->getCellByColumnAndRow(10, $row)->getValue();

					$rating_value = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

					$review_count = $worksheet->getCellByColumnAndRow(12, $row)->getValue();

					$product_data = array(

							'product_name' => trim(str_replace(',', ' ', $product_name)),

							'product_slug' => str_replace(',', ' ', $product_slug),

							'product_style' => trim($product_style),

							'product_price' => trim($product_price),

							'sale_price' => trim($sale_price),

							'product_description' => trim($product_description),

							'product_sku' => trim($product_sku),

							'brand_id' => trim($brand_id),

							'is_active' => $is_active,

							'product_weight_gms' => trim($weight),

							'created_datetime' => date('Y-m-d H:i:s'),

							'created_by' => 1,

							'is_active' => '1'

						);



						$product_id = $this->product_model->add($product_data);

				}

			}



			echo '<br>Product(s) import process has been completed successfully.<br>';

			if ($product_failed_count > 0) {

				echo '<br><b>Total Product Import Failed:</b>' . $product_failed_count;

			}


		}

	}

	function importmeta()

	{

		if (isset($_FILES["file"]["name"])) {

			$path = $_FILES["file"]["tmp_name"];

			$object = PHPExcel_IOFactory::load($path);



			$product_insert_count = 0;

			$variant_product_insert_count = 0;

			$product_failed_count = 0;



			$product_already_there = "<br><b>The below products has not imported:</b>";





			foreach ($object->getWorksheetIterator() as $worksheet) {

				$product_id_for_variant = 0;

				$old_product_name = '';

				$old_product_id = 0;

				$highestRow = $worksheet->getHighestRow();

				$highestColumn = $worksheet->getHighestColumn();

				for ($row = 2; $row <= $highestRow; $row++) {

					$product_slug = cleanSlug($worksheet->getCellByColumnAndRow(0, $row)->getValue());

					$product_name = cleanSlug($worksheet->getCellByColumnAndRow(1, $row)->getValue());

					$product_description = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

					$meta_title = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

					$meta_description = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

					$health_benefit_title = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

					$health_benefits = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

					$ingredients = $worksheet->getCellByColumnAndRow(7, $row)->getValue();

					$usage_instructions = $worksheet->getCellByColumnAndRow(8, $row)->getValue();

					$storage_information = $worksheet->getCellByColumnAndRow(9, $row)->getValue();

					$faqs = $worksheet->getCellByColumnAndRow(10, $row)->getValue();

					$rating_value = $worksheet->getCellByColumnAndRow(11, $row)->getValue();

					$review_count = $worksheet->getCellByColumnAndRow(12, $row)->getValue();

						$product_data = array(

							'product_description' => trim($product_description),

							'meta_title' => trim($meta_title),

							'meta_description' => trim($meta_description),

							'health_benefit_title' => trim($health_benefit_title),

							'health_benefits' => trim($health_benefits),

							'ingredients' => trim($ingredients),

							'usage_instructions' => trim($usage_instructions),

							'storage_information' => trim($storage_information),

							'faqs' => trim($faqs),

							'rating_value' => trim($rating_value),

							'review_count' => trim($review_count),

						);



						$product_id = $this->product_model->update_by_slug($product_slug,$product_data);
			

				}

			}



			echo '<br>Product(s) import process has been completed successfully.<br>';


		}

	}



}



?>