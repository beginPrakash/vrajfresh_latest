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
					$product_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$product_slug = cleanSlug($worksheet->getCellByColumnAndRow(1, $row)->getValue());
					$product_description = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$product_sku = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$brand = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$category = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$tags = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$product_price = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$sale_price = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					$product_type = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$image = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$other_image = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					$weight = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
					$status = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
					$guid = GUID();

					$brand_id = 0;
					$product_tags = 0;
					$category_id = 0;
					if ($brand != '') {
						$brand_id = $this->brand_model->getBrandIDByName($brand);
						if ($brand_id == 0) {
							$brand_data = array(
								'brand_name' => $brand,
								'brand_slug' => cleanSlug($brand),
								'created_by' => 1,
								'created_datetime' => date('Y-m-d'),
								'is_active' => '1',
							);
							$brand_id = $this->brand_model->add($brand_data);
						}
					}
					$is_active = 'N';
					if ($status == 'Active') {
						$is_active = 'Y';
					}
					if ($sale_price == '') {
						$sale_price = $product_price;
					}
					$product_id = 0;
					$product_count = 1;
					$product_count_sku = 1;
					if (trim($weight) != '') {
						if (trim($product_price) != '') {
							if (trim($product_name) != '') {
								$product_count = $this->product_model->getProductBySlug(str_replace(',', ' ', $product_slug));
								$product_count_sku = $this->product_model->getProductBySKU($product_sku);
								/*echo $product_count.":".$product_count_sku.":".$product_type;exit;*/
								if (($product_count > 0 || $product_count_sku > 0) && strtolower($product_type) == 'simple') {
									if ($product_count > 0) {
										$temp_product_msg = ' <b>Product slug is already exist.</b>';
									}
									if ($product_count_sku > 0) {
										$temp_product_msg = ' <b>Product SKU is already exist.</b>';
									}
									$product_already_there = $product_already_there . "<br>" . ($product_failed_count + 1) . "-" . $product_name . " <span style='color:red;'> (Product already exists in the database)" . $temp_product_msg . "</span>";
									$product_failed_count++;
								}
							} else {
								$product_already_there = $product_already_there . "<br>" . ($product_failed_count + 1) . "-" . $product_name . " <span style='color:red;'> (Product name is blank in the sheet)</span>";
								$product_failed_count++;

							}
						} else {
							$product_already_there = $product_already_there . "<br>" . ($product_failed_count + 1) . "-" . $product_name . " <span style='color:red;'> (Product price is blank in the sheet)</span>";
							$product_failed_count++;

						}
					} else {
						$product_already_there = $product_already_there . "<br>" . ($product_failed_count + 1) . "-" . $product_name . " <span style='color:red;'> (Wight not found in the sheet)</span>";
						$product_failed_count++;
					}

					if ($product_count == 0 && $product_count_sku == 0) {

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
						$product_id_for_variant = $product_id;
						$product_insert_count++;
					}

					if ($product_id > 0) {
						if ($other_image != "") {

							$ArrImage = explode(",", $other_image);
							if (is_array($ArrImage) && count($ArrImage) > 0) {
								foreach ($ArrImage as $images) {
									$guid = GUID();
									$ext = pathinfo($images, PATHINFO_EXTENSION);
									$ext = explode("?", $ext);
									$ext = $ext[0];
									file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/vrajfresh/admin/uploads/products/" . $guid . "." . $ext, file_get_contents(trim($images)));
									compressImage(SITE_URL . "uploads/products/" . $guid . "." . $ext, SITE_URL . "uploads/products/" . $guid . "." . $ext, 0);

									$product_image_data = array(
										'product_id' => trim($product_id),
										'image' => trim($guid . '.' . $ext),
										'is_active' => $is_active,
										'created_datetime' => date('Y-m-d H:i:s'),
										'created_by' => 1,
										'is_active' => '1'
									);
									$image_product_id = $this->product_model->add_images($product_image_data);
								}
							} else {
								$ext = pathinfo($other_image, PATHINFO_EXTENSION);
								$ext = explode("?", $ext);
								$ext = $ext[0];
								file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/vrajfresh/admin/uploads/products/" . $guid . "." . $ext, file_get_contents(trim($other_image)));
								compressImage(SITE_URL . "uploads/products/" . $guid . "." . $ext, SITE_URL . "uploads/products/" . $guid . "." . $ext, 0);
								$product_image_data = array(
									'product_id' => trim($product_id),
									'image' => trim($guid . '.' . $ext),
									'is_active' => $is_active,
									'created_datetime' => date('Y-m-d H:i:s'),
									'created_by' => 1,
									'is_active' => '1'
								);
								$image_product_id = $this->product_model->add_images($product_image_data);
							}
						}

						if ($image != "") {


							$guid = GUID();
							$ext = pathinfo($image, PATHINFO_EXTENSION);
							$ext = explode("?", $ext);
							$ext = $ext[0];
							file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/vrajfresh/admin/uploads/products/" . $guid . "." . $ext, file_get_contents(trim($image)));
							compressImage(SITE_URL . "uploads/products/" . $guid . "." . $ext, SITE_URL . "uploads/products/" . $guid . "." . $ext, 0);

							$product_data = array(
								'product_image' => trim($guid . '.' . $ext)
							);
							$image_product_id = $this->product_model->update($product_id, $product_data);

						} else {
							$ArrImage = explode(",", $other_image);

							$guid = GUID();
							$ext = pathinfo($ArrImage[0], PATHINFO_EXTENSION);
							$ext = explode("?", $ext);
							$ext = $ext[0];
							file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/vrajfresh/admin/uploads/products/" . $guid . "." . $ext, file_get_contents(trim($ArrImage[0])));
							compressImage(SITE_URL . "uploads/products/" . $guid . "." . $ext, SITE_URL . "uploads/products/" . $guid . "." . $ext, 0);

							$product_data = array(
								'product_image' => trim($guid . '.' . $ext)
							);
							$image_product_id = $this->product_model->update($product_id, $product_data);


						}


						if ($category != '') {
							$ArrCategory = explode(",", $category);
							if (is_array($ArrCategory) && count($ArrCategory) > 0) {
								$ArrCategoryId = array();
								foreach ($ArrCategory as $category_name) {
									$category_id = $this->category_model->getCategoryIDByName(trim($category_name));
									if ($category_id == 0) {
										$category_data = array(
											'category_name' => trim($category_name),
											'category_slug' => cleanSlug($category_name),
											'parent_category_id' => 0,
											'created_by' => 1,
											'created_datetime' => date('Y-m-d'),
											'is_active' => '1'
										);

										$category_id = $this->category_model->add($category_data);
									}
									$ArrCategoryId[] = $category_id;
								}
								#add category in child table
								foreach ($ArrCategoryId as $category_id) {
									$ArrProductCategory = array(
										'product_id' => $product_id,
										'category_id' => $category_id,
										'created_datetime' => date('Y-m-d H:i:s'),
										'created_by' => 1
									);
									$this->product_category_mapping_model->add($ArrProductCategory);
								}
								#end category   
							}
						}
						if ($tags != '') {
							$ArrTags = explode(",", $tags);
							if (is_array($ArrTags) && count($ArrTags) > 0) {
								$ArrTagsId = array();
								foreach ($ArrTags as $tag) {
									$tag_id = $this->tag_model->getTagIDByName(trim($tag));
									if ($tag_id == 0) {
										$tag_data = array(
											'tag' => trim($tag),
											'is_active' => 'Y',
											'created_by' => 1,
											'created_datetime' => date('Y-m-d'),
											'is_active' => '1'
										);
										$tag_id = $this->tag_model->add($tag_data);
									}
									$ArrTagsId[] = $tag_id;
								}
								#add tags in child table
								foreach ($ArrTagsId as $tag) {
									$ArrProductTag = array(
										'product_id' => $product_id,
										'tag_id' => $tag,
										'created_datetime' => date('Y-m-d H:i:s'),
										'created_by' => 1
									);
									$this->product_tag_model->add($ArrProductTag);
								}
								#end tag
							}
						}
					}



					if (strtolower($product_type) == 'variant') { /*echo $old_product_name.":".$product_name;echo "in variant<br>product_sku:".$product_sku;;exit;*/
						$product_variant_count = $this->product_model->getProductVariantBySKU(trim($product_sku));
						if (trim($old_product_name) != trim($product_name)) {
							if ($product_variant_count == 0) {
								$product_variant_data = array(
									'product_id' => trim($product_id_for_variant),
									'product_variant_size' => trim($weight),
									'variant_price' => trim($product_price),
									'variant_sku' => trim($product_sku)
								); /*echo "<pre>";print_r($product_variant_data);exit;*/
								$variant_id = $this->product_model->add_variants($product_variant_data);
								$variant_product_insert_count++;
							} else {
								echo "in else if";
								$product_already_there = $product_already_there . "<br/>Product Variant SKU: " . $product_sku . " already present";
							}

							$old_product_name = $product_name;
							$old_product_id = $product_id_for_variant;

						} else {
							if ($product_variant_count == 0) {
								$product_variant_data = array(
									'product_id' => trim($old_product_id),
									'product_variant_size' => trim($weight),
									'variant_price' => trim($product_price),
									'variant_sku' => trim($product_sku)
								);
								$variant_id = $this->product_model->add_variants($product_variant_data);
								$variant_product_insert_count++;
							} else {
								$product_already_there = $product_already_there . "<br/>Product Variant SKU: " . $product_sku . " already present";
							}
						}

					}



				}

			}

			echo '<br>Product(s) import process has been completed successfully.<br>';
			echo '<br><b>Total Successfully Product Imported:</b>' . $product_insert_count;
			echo '<br><b>Total Successfully Variant Product Imported:</b>' . $variant_product_insert_count;
			if ($product_failed_count > 0) {
				echo '<br><b>Total Product Import Failed:</b>' . $product_failed_count;
			}
			echo $product_already_there;
		}
	}

}

?>