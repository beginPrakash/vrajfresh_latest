<?php

class Controller_product extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->model('common_model');
		$this->load->model('category_model');
		$this->load->model('brand_model');
		$this->load->model('product_tag_model');
		$this->load->model('product_variant_master_model');
		$this->load->model('product_image_model');
		$this->load->model('product_variant_model');
		$this->load->model('product_category_mapping_model');
		$this->load->library('image_lib');

		if (!IsUserLogin()) {
			$authorized_error = "You are not authorized to view this page....!";
			$this->session->set_flashdata('authorized_error', $authorized_error);
			redirect('login');
		}

	}
	public function imgTest()
	{


		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		//$config['source_image'] = "https://dev.thcitsolutions.com/vrajfresh/admin/uploads/products/DCD0FD78-85D0-4B6F-A5A2-811310B5495A.png";//$source_image; //The path of the image to be watermarked
		$config['source_image'] = "uploads/products/DCD0FD78-85D0-4B6F-A5A2-811310B5495A.png"; //$source_image; //The path of the image to be watermarked
		$config['wm_text'] = 'Copyright Vraj Fresh Copyright Vraj Fresh Copyright Vraj Fresh Copyright Vraj Fresh Copyright Vraj Fresh Copyright Vraj Fresh Copyright Vraj Fresh';
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = 'themes/admin_panel/watermark/texb.ttf';
		$config['wm_font_size'] = '16';
		$config['wm_font_color'] = 'bababa';
		$config['wm_vrt_alignment'] = 'top';
		$config['wm_hor_alignment'] = 'left';
		$config['wm_padding'] = '20';
		$this->image_lib->initialize($config);
		if (!$this->image_lib->watermark()) {
			echo $this->image_lib->display_errors();
		}
		$this->image_lib->clear();
		echo "Hello";

	}

	/* PRODUCT URL DUBLICATE */
	public function ajaxCheckUrl()
	{
		$product_slug = $_POST['product_slug'];
		$product_id = $_POST['product_id'];
		$result = $this->product_model->isUrlExist($product_slug, $product_id);
		if ($result == 1) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}
	public function index()
	{
		$ArrData = $this->product_model->getProductListData($_POST);

		if (@$_REQUEST['columns'] != "") {
			$output = array('sEcho' => $_REQUEST['draw'], 'iTotalRecords' => $ArrData['iTotalRecords'], 'iTotalDisplayRecords' => $ArrData['iTotalDisplayRecords'], 'aaData' => array());

			$i = $_REQUEST['start'] + 1;
			foreach ($ArrData['result'] as $aRow) {
				$row = $category_name = array();
				$base_url = base_url();
				$product_id = $aRow['product_id'];
				$actions = '';
				$actions .= '<input type="checkbox" name="delete[]" class="chkDelete" value="' . $product_id . '" /><div class="btn-group">';
				$actions .= '<a rel="' . $base_url . 'adminpanel/controller_product/view_product_ajax/" id="' . $product_id . '" title="Product Detail" class="view_action btn btn-default btn-sm" ><i class="fa fa-eye"></i></a>';
				$actions .= '<a href="' . $base_url . 'product-update/' . $product_id . '" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></a>';
				$actions .= '<a rel="' . $base_url . 'product-delete/' . $product_id . '" class="deleteRecord btn btn-default btn-sm"><i class="fa fa-trash-o"></i></a></div>';

				/*$r = $this->product_model->getProductCategories($product_id);
							   if(is_array($r) && count($r) > 0){
								   foreach ($r as $key => $value) {
									   $category_name[] = $value['category_name'];
								   }
							   }*/
				$row[] = $actions;
				$row[] = $aRow['product_sku'];
				$row[] = $aRow['product_name'];
				//$row[] = implode(',',$category_name);
				$row[] = $aRow['product_price'];
				$row[] = $aRow['sale_price'];
				$row[] = $aRow['unit_cost'];

				if ($aRow['product_image'] == '') {
					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/noimg.gif" border=0 alt="No-Image">';
				} else {
					$image = $aRow['product_image'];
					$row[] = $imag = '<img height="70px" width="70px" src="' . $base_url . 'uploads/products/' . $image . '" alt="No Image found" border=0 >';
				}
				$row[] = getIssoldoutButtonForList($aRow['is_out_of_stock'], $product_id, 'tbl_products', 'product_id');
				$row[] = getIsactiveButtonForList($aRow['is_active'], $product_id, 'tbl_products', 'product_id');
				$i++;
				$output['aaData'][] = $row;

			}
			echo json_encode($output);
			exit;
		}
		/* DATA TABLE END */
		$ArrPageData = array();
		$ArrPageData['cms_title'] = 'Product List';
		$ArrPageData['button_url'] = base_url() . 'product-add';
		$ArrPageData['button_label'] = 'Add Product';
		$ArrPageData['view_name'] = 'view_product_list.php';
		$ArrPageData['category'] = $this->category_model->category_list_data();
		$this->load->view('admin_panel/admin_panel', $ArrPageData);
	}

	public function product_list_export()
	{
		extract($_POST);
		$this->load->helper('csv_helper');
		$ArrDataList = $this->product_model->ExportProductData($_POST);
		$data = array();
		$no = 0;
		foreach ($ArrDataList as $ArrData) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $ArrData['product_name'];
			$row[] = $ArrData['product_price'];
			$row[] = $ArrData['sale_price'];
			//$row[] = $ArrData['category_name'];
			$row[] = $ArrData['Preference'];
			$row[] = ($ArrData['is_active'] == '1') ? 'Yes' : 'No';
			$data[] = $row;
		}
		$report_title = "qe_product_list_" . time();
		$ArrHeading = array('Sr No', 'Product Name', 'Regular Price', 'Member Price', 'Category Name', 'Preference', 'site Name', 'Active');
		array_to_csv($ArrHeading, $data, $report_title);

	}

	public function view_product_ajax()
	{
		$id = $this->input->post('id');
		$data['ArrFieldData'] = $this->product_model->getProductUsingID($id);
		$this->load->view('admin_panel/quickview/view_product_details_popup', $data);
	}

	public function delete_ajax($id)
	{
		$result = $this->product_model->delete($id);
		if ($result) {
			echo 'Yes';
		} else {
			echo 'No';
		}

	}
	public function image_remove($product_id, $product_image_id)
	{
		$result = $this->product_image_model->delete($product_image_id);
		//redirect('product-update/'.$product_id);
		echo "success";
	}

	public function delete_multiple_product_record()
	{
		$id = $_POST['primary_id'];
		$primary_idArr = explode(',', $id);
		$result = 0;
		foreach ($primary_idArr as $primary_id) {
			$result += $this->product_model->delete($primary_id);
		}
		if ($result > 0) {
			echo 1;
		} else {
			echo 0;
		}

	}

	#ADD/EDIT
	public function add($product_id = 0)
	{	
		
		$ArrData = array();
		//$ArrData['ArrParentCategory'] = $this->category_model->parent_category_list_data();	
		$ArrData['ArrBrands'] = $this->brand_model->brand_list_data();
		$ArrData['category'] = $this->category_model->category_list_data();
		$ArrData['ArrProducts'] = $this->product_model->product_list_data();
		$ArrProductMasterVariants = $this->product_variant_master_model->getProductVariant('1');
		//echo "<pre>";print_r($ArrProductMasterVariants);exit;
		$ArrData['ArrProductMasterVariants'] = $ArrProductMasterVariants;

		$ArrColor = array('Select Color');
		$ArrSize = array('Select Weight');
		if (isset($ArrProductMasterVariants) && is_array($ArrProductMasterVariants) && count($ArrProductMasterVariants) > 0) {
			foreach ($ArrProductMasterVariants as $data) {
				if ($data['product_variant_name'] == 'Color') {
					$ArrColor = explode(",", "Select Color," . $data['product_variant_value']);
				}
				if ($data['product_variant_name'] == 'Weight') {
					$ArrSize = explode(",", "Select Weight," . $data['product_variant_value']);
				}
			}
			$ArrColor = array_combine($ArrColor, $ArrColor);
			$ArrSize = array_combine($ArrSize, $ArrSize);
		}
		$ArrData['ArrColor'] = $ArrColor;
		$ArrData['ArrSize'] = $ArrSize;
		$ArrData['Arrcategory_id'] = array();
		if ($product_id > 0) {

			//GET PRODUCT DETAILS
			$ArrData['product'] = $this->product_model->getProductUsingID($product_id);


			//GET PRODUCT TAGS
			$ArrData['ArrProductTags'] = $this->product_tag_model->getProductTags($product_id);


			//GET PRODUCT CATEGORY
			$ArrData['Arrcategory_id'] = $this->product_category_mapping_model->getProductCategory($product_id);
			//GET PRODUCT IMAGES
			$ArrData['ArrProductImages'] = $this->product_image_model->getProductImages($product_id);

			//GET PRODUCT VARIANTS
			$ArrProductSelectedVariants = array();
			$tempArrProductSelectedVariants = $this->product_variant_model->getProductVariants($product_id);
			//echo "<pre>";print_r($tempArrProductSelectedVariants);exit;

			$ArrData['tempArrProductSelectedVariants'] = $tempArrProductSelectedVariants;


			$ArrData['cms_title'] = "Update Product";
			$ArrData['edit_id'] = $product_id;
		} else {
			$ArrData['cms_title'] = "Add Product";
			$ArrData['edit_id'] = '';
		}

		$ArrData['button_url'] = base_url() . 'product';
		$ArrData['button_label'] = 'View Product';
		$ArrData['view_name'] = 'view_product_addedit.php';

		$css_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/css/jquery.multiselect.css'),
		);
		$js_assets = array(
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/pages/product-add-update-script.js'),
			array(ADMIN_PANEL_THEME_PATH . 'dist/js/jquery.multiselect.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/ckeditor/ckeditor.js'),
			array(ADMIN_PANEL_THEME_PATH . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'),
		);
		$this->carabiner->css($css_assets);
		$this->carabiner->js($js_assets);
		if (isset($_POST['submit']) && ($_POST['submit'] == "Add" || $_POST['submit'] == "Update")) {
			
			/* validation Process Start */
			$this->load->library('form_validation');
			$this->form_validation->set_rules('product_name', 'Product Name', 'required');
			if (isset($_POST['submit']) && ($_POST['submit'] == "Add")) {
				$this->form_validation->set_rules('product_sku', 'Product SKU', 'required|is_unique[tbl_products.product_sku]');
				$this->form_validation->set_rules('product_slug', 'Product URL', 'required|is_unique[tbl_products.product_slug]');
			} else {
				$this->form_validation->set_rules('product_sku', 'Product SKU', 'required');
				$this->form_validation->set_rules('product_slug', 'Product URL', 'required');
			}
			//$this->form_validation->set_rules('product_sku', 'Product SKU', 'required|is_unique[tbl_products.product_sku]');
			//$this->form_validation->set_rules('product_slug', 'Product URL', 'required');
			//$this->form_validation->set_rules('short_desc', 'Short Desc', 'required');
			$this->form_validation->set_rules('product_price', 'Regular Price', 'required');
			$this->form_validation->set_rules('sale_price', 'Member Price', 'required');
			$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');
			/* validation Process End */
			if ($this->form_validation->run()) {
				if ($product_id > 0) { //update process
					$config['upload_path'] = 'uploads/products/'; // set the filter image types
					$config['allowed_types'] = 'gif|jpg|jpeg|png'; //load the upload library
					$config['file_name'] = GUID();
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');
					$data['image_name'] = array();
					$data['icon_image'] = array();


					if (isset($_FILES['product_image']) && is_uploaded_file($_FILES['product_image']['tmp_name'])) {

						$this->product_model->delete_image($product_id, 'thumb');
						if (!$this->upload->do_upload('product_image')) {
							$data = array('error_message' => $this->upload->display_errors());
						} else {
							$data['icon_image'] = $this->upload->data();
							//$this->textWatermark($data['icon_image']['file_name']);
						}

					} else {

						$data['icon_image']['file_name'] = $this->input->post('prod_icon_img');
					}


					$product_associate_to_id = '';
					/*if(is_array($this->input->post('product_associate_to_id')))
								   {
								   $product_associate_to_id = ",".implode(",",$this->input->post('product_associate_to_id')).",";
								   }*/
					$check_Sku = $this->product_model->checkSku($_POST['product_sku']);
					if ($_POST['product_sku'] == $check_Sku[0]['product_sku'] && $product_id != $check_Sku[0]['product_id']) {
						$this->session->set_flashdata('error_message', 'Product SKU already exists.');
						redirect('product-update/' . $product_id);
					} else {
						$product_update_data = array(
							'product_name' => $this->input->post('product_name'),
							'product_sku' => $this->input->post('product_sku'),
							'product_sub_name' => $this->input->post('product_sub_name'),
							'product_slug' => $this->input->post('product_slug'),
							'product_weight_gms' => $this->input->post('product_weight_gms'),
							'product_price' => $this->input->post('product_price'),
							'sale_price' => $this->input->post('sale_price'),
							'unit_cost' => $this->input->post('unit_cost'),
							'product_style' => $this->input->post('product_style'),
							//'short_desc' => $this->input->post('short_desc'), 
							'product_description' => $this->input->post('product_description'),
							'product_image' => $data['icon_image']['file_name'],
							'is_home_display' => $this->input->post('is_home_display'),
							'is_perisible_products' => $this->input->post('is_perisible_products'),
							'is_liker_products' => $this->input->post('is_liker_products'),
							'is_cook_food_products' => $this->input->post('is_cook_food_products'),
							'brand_id' => $this->input->post('brand_id'),
							'product_type' => $this->input->post('product_type'),
							'is_out_of_stock' => $this->input->post('is_out_of_stock'),
							'product_tax' => $this->input->post('product_tax'),
							'is_active' => $this->input->post('is_active'),
							'product_associate_to_id' => $product_associate_to_id,
							'meta_title' => $this->input->post('meta_title'),
							'meta_description' => $this->input->post('meta_description'),
							'health_benefit_title' => $this->input->post('health_benefit_title'),
							'health_benefits' => $this->input->post('health_benefits'),
							'ingredients' => $this->input->post('ingredients'),
							'usage_instructions' => $this->input->post('usage_instructions'),
							'storage_information' => $this->input->post('storage_information'),
							'faqs' => $this->input->post('faqs'),
							'search_tags' => $this->input->post('search_tags'),
							'modified_datetime' => date('Y-m-d H:i:s'),
							'modified_by' => get_current_admin_id(),
						);
						//echo "<pre>";print_r($product_update_data);exit;
						$update = $this->product_model->update($product_id, $product_update_data);


						#add tags in child table
						$this->product_tag_model->delete($product_id);
						$ArrTags = explode(",", $this->input->post('product_tags'));
						foreach ($ArrTags as $tag) {
							$ArrProductTag = array(
								'product_id' => $product_id,
								'tag_id' => $tag,
								'created_datetime' => date('Y-m-d H:i:s'),
								'created_by' => get_current_admin_id()
							);
							$this->product_tag_model->add($ArrProductTag);
						}
						#end tag

						#add category in child table
						$this->product_category_mapping_model->delete($product_id);
						$ArrCategory = $this->input->post('category_id');
						foreach ($ArrCategory as $category_id) {
							$ArrProductCategory = array(
								'product_id' => $product_id,
								'category_id' => $category_id,
								'created_datetime' => date('Y-m-d H:i:s'),
								'created_by' => get_current_admin_id()
							);
							$this->product_category_mapping_model->add($ArrProductCategory);
						}
						#end category
						$this->session->set_flashdata('success_message', 'Product details has been updated successfully.');
					}
					$this->product_variant_model->deleteProductVariant($product_id);
					if (isset($_POST['ArrVariantId']) && is_array($_POST['ArrVariantId'])) {

						$config['upload_path'] = 'uploads/products/'; // set the filter image types
						$config['allowed_types'] = 'gif|jpg|jpeg|png'; //load the upload library
						$config['file_name'] = GUID();
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->set_allowed_types('*');

						$ArrVariantId = $_POST['ArrVariantId'];
						//$ArrVariantColor = $_POST['ArrVariantColor'];
						$ArrVariantSize = $_POST['ArrVariantSize'];
						$ArrVariantPrice = $_POST['ArrVariantPrice'];
						//$ArrVariantQTY = $_POST['ArrVariantQTY'];
						$ArrVariantSKU = $_POST['ArrVariantSKU'];
						$ArrUnitCost = $_POST['ArrUnitCost'];
						$ArrOutOfStock = $_POST['ArrOutOfStock'];
						$ArrVariantImagePath = $_POST['ArrVariantImagePath'];
						$ArrProducVariants = $_FILES['ArrProducVariants'];


						for ($i = 0; $i < count($ArrVariantSize); $i++) {
							if (isset($ArrVariantId[$i]) && $ArrVariantId[$i] > 0) {
								if (isset($ArrProducVariants['name'][$i])) {
									$_FILES['image']['name'] = $ArrProducVariants['name'][$i];
									$_FILES['image']['type'] = $ArrProducVariants['type'][$i];
									$_FILES['image']['tmp_name'] = $ArrProducVariants['tmp_name'][$i];
									$_FILES['image']['error'] = $ArrProducVariants['error'][$i];
									$_FILES['image']['size'] = $ArrProducVariants['size'][$i];
								}
								/*$product_variant_color = '';
														if($ArrVariantColor[$i]!='' && $ArrVariantColor[$i]!='Select Color')
														{
															$product_variant_color = $ArrVariantColor[$i];
														}*/
								$product_variant_size = '';
								if ($ArrVariantSize[$i] != '' && $ArrVariantSize[$i] != 'Select Weight') {
									$product_variant_size = $ArrVariantSize[$i];
								}
								$ArrProductVariant = array(
									'product_id' => $product_id,
									//'product_variant_color' => $product_variant_color,
									'product_variant_size' => $product_variant_size,
									'variant_price' => $ArrVariantPrice[$i],
									//'variant_qty' =>  $ArrVariantQTY[$i],
									'variant_sku' => $ArrVariantSKU[$i],
									'unit_cost' => $ArrUnitCost[$i],
									'is_out_of_stock' => $ArrOutOfStock[$i],
								);
								if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
									if ($this->upload->do_upload('image')) {
										$ArrProductVariant['variant_image'] = $this->upload->data()['file_name'];

										//for text watermark
										//$this->textWatermark($ArrProductVariant['variant_image']);
									}
								} else {
									$ArrProductVariant['variant_image'] = $ArrVariantImagePath[$i];
								}
								$this->product_variant_model->add($ArrProductVariant);
								//echo $this->db->last_query();exit;

							} else {
								if (isset($ArrProducVariants['name'][$i])) {
									$_FILES['image']['name'] = $ArrProducVariants['name'][$i];
									$_FILES['image']['type'] = $ArrProducVariants['type'][$i];
									$_FILES['image']['tmp_name'] = $ArrProducVariants['tmp_name'][$i];
									$_FILES['image']['error'] = $ArrProducVariants['error'][$i];
									$_FILES['image']['size'] = $ArrProducVariants['size'][$i];
								}
								/*$product_variant_color = '';
														if($ArrVariantColor[$i]!='' && $ArrVariantColor[$i]!='Select Color')
														{
															$product_variant_color = $ArrVariantColor[$i];
														}*/
								$product_variant_size = '';
								if ($ArrVariantSize[$i] != '' && $ArrVariantSize[$i] != 'Select Weight') {
									$product_variant_size = $ArrVariantSize[$i];
								}
								$ArrProductVariant = array(
									'product_id' => $product_id,
									//'product_variant_color' => $product_variant_color,
									'product_variant_size' => $product_variant_size,
									'variant_price' => $ArrVariantPrice[$i],
									//'variant_qty' =>  $ArrVariantQTY[$i],
									'variant_sku' => $ArrVariantSKU[$i],
									'unit_cost' => $ArrUnitCost[$i],
									'is_out_of_stock' => $ArrOutOfStock[$i],
								);
								if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
									if ($this->upload->do_upload('image')) {

										if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
											if ($this->upload->do_upload('image')) {
												$ArrProductVariant['variant_image'] = $this->upload->data()['file_name'];

												//for text watermark
												//$this->textWatermark($ArrProductVariant['variant_image']);
											}
										}
									}
								}
								$this->product_variant_model->add($ArrProductVariant);
								//echo $this->db->last_query();exit;
							}
						}
					} //end if
					//-----------------------------END-------------------------------------

					//UPLOAD IMAGES
					$ArrProductImages = $_FILES['ArrProductImages'];
					for ($i = 0; $i < count($_FILES['ArrProductImages']['name']); $i++) {
						$_FILES['image']['name'] = $ArrProductImages['name'][$i];
						$_FILES['image']['type'] = $ArrProductImages['type'][$i];
						$_FILES['image']['tmp_name'] = $ArrProductImages['tmp_name'][$i];
						$_FILES['image']['error'] = $ArrProductImages['error'][$i];
						$_FILES['image']['size'] = $ArrProductImages['size'][$i];

						if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
							if ($this->upload->do_upload('image')) {
								$ArrProductImage = array(
									'product_id' => $product_id,
									'image' => $this->upload->data()['file_name'],
								);
								$this->product_image_model->add($ArrProductImage);

								//for text watermark
								//$this->textWatermark($ArrProductImage['image']);
							}
						}

					}
					//END UPLOAD IMAGES

					redirect('product-update/' . $product_id);

				} else { // insert

					$config['upload_path'] = 'uploads/products/';
					$config['allowed_types'] = 'gif|jpg|jpeg|png';
					$config['file_name'] = GUID();
					//load the upload library
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					$this->upload->set_allowed_types('*');
					$data['upload_data'] = '';
					$data['icon_image'] = '';
					$file_name = '';
					if (isset($_FILES['product_image']) && is_uploaded_file($_FILES['product_image']['tmp_name'])) {

						$this->product_model->delete_image($product_id, 'thumb');
						if (!$this->upload->do_upload('product_image')) {
							$data = array('error_message' => $this->upload->display_errors());
						} else {
							$data['icon_image'] = $this->upload->data();
							$file_name = $data['icon_image']['file_name'];
							//$this->textWatermark($data['icon_image']['file_name']);
						}

					} else {
						$file_name = $this->input->post('prod_icon_img');
					}
					$product_associate_to_id = '';
					/*if(is_array($this->input->post('product_associate_to_id')))
								   {
								   $product_associate_to_id = ",".implode(",",$this->input->post('product_associate_to_id')).",";
								   }*/
					$product_data = array(
						'product_name' => $this->input->post('product_name'),
						'product_sku' => $this->input->post('product_sku'),
						'product_sub_name' => $this->input->post('product_sub_name'),
						'product_slug' => $this->input->post('product_slug'),
						'product_weight_gms' => $this->input->post('product_weight_gms'),
						//'short_desc' => $this->input->post('short_desc'), 		
						'product_description' => $this->input->post('product_description'),
						'product_image' => $file_name,
						'is_home_display' => $this->input->post('is_home_display'),
						'is_perisible_products' => $this->input->post('is_perisible_products'),
						'is_liker_products' => $this->input->post('is_liker_products'),
						'is_cook_food_products' => $this->input->post('is_cook_food_products'),
						'product_style' => $this->input->post('product_style'),
						'product_type' => $this->input->post('product_type'),
						'is_out_of_stock' => $this->input->post('is_out_of_stock'),
						'product_tax' => $this->input->post('product_tax'),
						'is_active' => $this->input->post('is_active'),
						'product_associate_to_id' => $product_associate_to_id,
						'brand_id' => $this->input->post('brand_id'),
						'meta_title' => $this->input->post('meta_title'),
						'meta_description' => $this->input->post('meta_description'),
						'health_benefit_title' => $this->input->post('health_benefit_title'),
						'health_benefits' => $this->input->post('health_benefits'),
						'ingredients' => $this->input->post('ingredients'),
						'usage_instructions' => $this->input->post('usage_instructions'),
						'storage_information' => $this->input->post('storage_information'),
						'faqs' => $this->input->post('faqs'),
						'created_datetime' => date('Y-m-d H:i:s'),
						'created_by' => get_current_admin_id(),
					);

					if ($this->input->post('product_price') != '')
						$product_data['product_price'] = $this->input->post('product_price');
					if ($this->input->post('sale_price') != '')
						$product_data['sale_price'] = $this->input->post('sale_price');
					if ($this->input->post('unit_cost') != '')
						$product_data['unit_cost'] = $this->input->post('unit_cost');

					$product_id = $this->product_model->add($product_data);
					if ($product_id > 0) {


						#add tags in child table
						$ArrTags = explode(",", $this->input->post('product_tags'));
						foreach ($ArrTags as $tag) {
							$ArrProductTag = array(
								'product_id' => $product_id,
								'tag_id' => $tag,
								'created_datetime' => date('Y-m-d H:i:s'),
								'created_by' => get_current_admin_id()
							);
							$this->product_tag_model->add($ArrProductTag);
						}
						#end tag

						#add category in child table
						$ArrCategory = $this->input->post('category_id');
						foreach ($ArrCategory as $category_id) {
							$ArrProductCategory = array(
								'product_id' => $product_id,
								'category_id' => $category_id,
								'created_datetime' => date('Y-m-d H:i:s'),
								'created_by' => get_current_admin_id()
							);
							$this->product_category_mapping_model->add($ArrProductCategory);
						}
						#end category

						if (isset($_POST['ArrVariantId']) && is_array($_POST['ArrVariantId'])) {
							$config['upload_path'] = 'uploads/products/'; // set the filter image types
							$config['allowed_types'] = 'gif|jpg|jpeg|png'; //load the upload library
							$config['file_name'] = GUID();
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							$this->upload->set_allowed_types('*');

							$ArrVariantId = $_POST['ArrVariantId'];
							//$ArrVariantColor = $_POST['ArrVariantColor'];
							$ArrVariantSize = $_POST['ArrVariantSize'];
							$ArrVariantPrice = $_POST['ArrVariantPrice'];
							//$ArrVariantQTY = $_POST['ArrVariantQTY'];
							$ArrVariantSKU = $_POST['ArrVariantSKU'];
							$ArrUnitCost = $_POST['ArrUnitCost'];
							$ArrOutOfStock = $_POST['ArrOutOfStock'];
							$ArrVariantImagePath = $_POST['ArrVariantImagePath'];
							$ArrProducVariants = $_FILES['ArrProducVariants'];


							for ($i = 0; $i < count($ArrVariantSize); $i++) {
								/*if(isset($ArrProducVariants['name'][$i]))
													 {
														 $_FILES['image']['name'] = $ArrProducVariants['name'][$i];
														 $_FILES['image']['type'] = $ArrProducVariants['type'][$i];
														 $_FILES['image']['tmp_name'] = $ArrProducVariants['tmp_name'][$i];
														 $_FILES['image']['error'] = $ArrProducVariants['error'][$i];
														 $_FILES['image']['size'] = $ArrProducVariants['size'][$i];
													 }*/

								/*$product_variant_color = '';
													 if($ArrVariantColor[$i]!='' && $ArrVariantColor[$i]!='Select Color')
													 {
														 $product_variant_color = $ArrVariantColor[$i];
													 }*/
								$product_variant_size = '';
								if ($ArrVariantSize[$i] != '' && $ArrVariantSize[$i] != 'Select Weight') {
									$product_variant_size = $ArrVariantSize[$i];
								}
								$ArrProductVariant = array(
									'product_id' => $product_id,
									//'product_variant_color' => $product_variant_color,
									'product_variant_size' => $product_variant_size,
									'variant_price' => $ArrVariantPrice[$i],
									//'variant_qty' =>  $ArrVariantQTY[$i],
									'variant_sku' => $ArrVariantSKU[$i],
									'unit_cost' => $ArrUnitCost[$i],
									'is_out_of_stock' => $ArrOutOfStock[$i],
								);
								/*if(isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name']))
													 {	
														 if ($this->upload->do_upload('image'))
														 {
															 $ArrProductVariant['variant_image'] = $this->upload->data()['file_name'];
														 }
													 }*/
								$this->product_variant_model->add($ArrProductVariant);
							}
						}
						//-----------------------------END-------------------------------------

						//UPLOAD IMAGES
						$ArrProductImages = $_FILES['ArrProductImages'];
						for ($i = 0; $i < count($_FILES['ArrProductImages']['name']); $i++) {
							$_FILES['image']['name'] = $ArrProductImages['name'][$i];
							$_FILES['image']['type'] = $ArrProductImages['type'][$i];
							$_FILES['image']['tmp_name'] = $ArrProductImages['tmp_name'][$i];
							$_FILES['image']['error'] = $ArrProductImages['error'][$i];
							$_FILES['image']['size'] = $ArrProductImages['size'][$i];

							if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
								if ($this->upload->do_upload('image')) {
									$ArrProductImage = array(
										'product_id' => $product_id,
										'image' => $this->upload->data()['file_name'],
									);
									$this->product_image_model->add($ArrProductImage);
									//$this->textWatermark($ArrProductImage['image']);
								}
							}
						}
						//END UPLOAD IMAGES

						$this->session->set_flashdata('success_message', 'Product details has been added successfully.');
					} else {
						$this->session->set_flashdata('error_message', 'Oops...! something went wrong, please try again');
					}
					redirect('product');
				}
			} else {
				$this->load->view('admin_panel/admin_panel', $ArrData);
			}
		} else {
			$this->load->view('admin_panel/admin_panel', $ArrData);
		}
	}
	public function getVariant()
	{
		$ArrProductMasterVariants = $this->product_variant_master_model->getProductVariant('1');
		$ArrColor = array('0' => 'Select Color');
		$ArrSize = array('0' => 'Select Weight');
		if (isset($ArrProductMasterVariants) && is_array($ArrProductMasterVariants) && count($ArrProductMasterVariants) > 0) {
			foreach ($ArrProductMasterVariants as $data) {
				if ($data['product_variant_name'] == 'Color') {
					$ArrColor = explode(",", "Select Color," . $data['product_variant_value']);
				}
				if ($data['product_variant_name'] == 'Weight') {
					$ArrSize = explode(",", "Select Weight," . $data['product_variant_value']);
				}
			}
			$ArrColor = array_combine($ArrColor, $ArrColor);
			$ArrSize = array_combine($ArrSize, $ArrSize);
		}
		?>
		<tr>
			<td width="50">
				<input type="hidden" name="ArrVariantId[]" value="">
				<input type="hidden" name="ArrVariantImagePath[]" value="">
				<a href="javascript:void(0);" class="remove-button" title="Click here to remove variant">
					<img src="<?php echo admin_media(); ?>dist/img/close-2.png">
				</a>
			</td>
			<!--<td>
					<?php
					//echo form_dropdown('ArrVariantColor[]', $ArrColor, '', array('class' => 'form-control'));
					?>
				</td>-->
			<td>
				<?php
				//echo form_dropdown('ArrVariantSize[]', $ArrSize, '', array('class' => 'form-control'));
				?>

				<input type="text" placeholder="Weight" class="form-control" name="ArrVariantSize[]" value="" required>
			</td>
			<td>
				<input type="text" placeholder="Price" class="form-control" name="ArrVariantPrice[]" value="" required>
			</td>
			<!--<td>
					<input type="text" placeholder="Stock QTY" class="form-control" name="ArrVariantQTY[]"  value="" required>
				</td>-->
			<td>
				<input type="text" placeholder="Stock SKU" class="form-control" name="ArrVariantSKU[]" value="" required>
			</td>
			<td>
				<input type="text" placeholder="Unit Cost" class="form-control" name="ArrUnitCost[]" value="" required>
			</td>
			<td>
				<select name="ArrOutOfStock[]" class="form-control" title="Is sold out?">
					<option value="1" selected>No</option>
					<option value="0">Yes</option>
				</select>
			</td>
			<td>
				<input type="file" name="ArrProducVariants[]" placeholder="Upload Image" class="form-control discountValue1">
				<?php echo form_error('ArrProducVariants'); ?>
			</td>
			<!--<td>
				<input type="file" name="ArrProducVariants[]" placeholder="Upload Image" class="form-control discountValue1"> <?php echo form_error('ArrProducVariants'); ?>
				</td>-->

			<td><a href="javascript:void(0);" onClick="addVariantRow()" title="Click here to add new variant"><img
						src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td>
		</tr>
		<?php
	}
	public function getTag()
	{
		$ArrTags = $this->product_tag_model->getAllTags();
		$ArrAllTags = array();
		foreach ($ArrTags as $data) {
			$tempArr = array("value" => $data['tag_id'], "text" => $data['tag']);
			$ArrAllTags[] = $tempArr;
		}
		echo json_encode($ArrAllTags);
	}
	public function getProductJson()
	{
		$term = (isset($_GET['term']) && $_GET['term'] != "") ? trim($_GET['term']) : '';
		if ($term != "") {
			$ArrProduct = $this->product_model->getProductJsonDropDown($term);
		} else {
			$ArrProduct = $this->product_model->getProductJsonDropDown();
		}
		$p = ['results' => $ArrProduct];
		echo json_encode($p);
	}

	public function textWatermark($source_image)
	{
		$watermarkLocations = $this->watermarkLocations();

		// 		foreach ($watermarkLocations as $row) {
// 			$config['image_library'] = 'gd2';
// 			//$config['source_image'] = "https://dev.thcitsolutions.com/vrajfresh/admin/uploads/products/DCD0FD78-85D0-4B6F-A5A2-811310B5495A.png";//$source_image; //The path of the image to be watermarked
// 			$config['source_image'] = "uploads/products/" . $source_image; //The path of the image to be watermarked
// 			$config['wm_text'] = 'Copyright Vraj Fresh Copyright Vraj Fresh';
// 			$config['wm_type'] = 'text';
// 			$config['wm_font_path'] = 'themes/admin_panel/watermark/texb.ttf';
// 			$config['wm_font_size'] = '23';
// 			$config['wm_font_color'] = 'bababa';
// 			$config['wm_hor_alignment'] = $row['horizontal']; // wm_hor_alignment =	left, center, right
// 			$config['wm_vrt_alignment'] = $row['vertical'];
// 			$config['wm_padding'] = '20';

		// 			$this->image_lib->initialize($config);
// 			if (!$this->image_lib->watermark()) {
// 				echo $this->image_lib->display_errors();
// 			}
// 			$this->image_lib->clear();
// 		}

		foreach ($watermarkLocations as $row) {
			$config['image_library'] = 'gd2';
			//$config['source_image'] = "https://dev.thcitsolutions.com/vrajfresh/admin/uploads/products/DCD0FD78-85D0-4B6F-A5A2-811310B5495A.png";//$source_image; //The path of the image to be watermarked
			$config['source_image'] = "uploads/products/" . $source_image; //The path of the image to be watermarked
			$config['wm_type'] = 'overlay';
			//$config['wm_overlay_path'] = 'themes/admin_panel/watermarkimage/watermark-logo.png';
			$config['wm_overlay_path'] = '';
			$config['wm_opacity'] = 50;
			// 			$config['wm_font_path'] = 'themes/admin_panel/watermarkimage/watermark-logo.png';
			$config['wm_hor_alignment'] = $row['horizontal']; // wm_hor_alignment =	left, center, right
			$config['wm_vrt_alignment'] = $row['vertical'];

			$this->image_lib->initialize($config);
			if (!$this->image_lib->watermark()) {
				echo $this->image_lib->display_errors();
			}
			$this->image_lib->clear();
		}
	}

	protected function watermarkLocations()
	{
		return [
			['horizontal' => 'left', 'vertical' => 'bottom'],
			['horizontal' => 'left', 'vertical' => 'top'],
			['horizontal' => 'right', 'vertical' => 'bottom'],
			['horizontal' => 'right', 'vertical' => 'top'],
			['horizontal' => 'center', 'vertical' => 'middle'],
		];
	}

}