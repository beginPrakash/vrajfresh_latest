<div class="row">

	<div class="col-md-12">

		<div class="box box-primary">

			<div class="box1">

				<div class="box-info">

					<!-- DEFAULT LOADER -->

					<div class="box-text box-body">

						<?php

						$brand_ids = (!empty($edit_id)) ? $ArrFieldData['brand_ids'] : '';

						$brand_ids = explode(",", $brand_ids);

						$exclude_category = (!empty($edit_id)) ? $ArrFieldData['exclude_category'] : '';

						$Arrexcludecategory_id = explode(",", $exclude_category);



						$options_brand = array();

						if (isset($ArrBrands) && count($ArrBrands) > 0)

							foreach ($ArrBrands as $row) {

								$options_brand[$row['brand_id']] = $row['brand_name'];

							}

						$options_category = array();

						if (isset($category) && count($category) > 0)

							foreach ($category as $row) {

								$options_category[$row['category_id']] = $row['category_name'];

							}

						$attributes = array('class' => 'form-horizontal', 'id' => 'promotional_code__form', 'role' => 'form', 'enctype' => 'multipart/form-data');

						if (isset($edit_id) && $edit_id > 0) {

							echo form_open('promotional-code-update/' . $edit_id, $attributes);

						} else {

							echo form_open('promotional-code-add', $attributes);

						}

						//print_r($ArrFieldData);

						?>

						<div class="box-body">

							<div class="form-group">

								<div class="col-md-3">

									<label> Promotional Code <span class="red">*</span>: </label>

								</div>

								<div class="col-md-9">

									<!--input type="hidden" name="check_title" id="check_title" value="<?php //echo base_url() ?>adminpanel/controller_membership/ajaxCheckTitle"-->

									<input type="text" placeholder="Promotional Code"

										class="form-control allow_only_char_number" name="promotional_code"

										id="promotional_code"

										value="<?php

										echo (isset($ArrFieldData['promotional_code'])) ? trim($ArrFieldData['promotional_code']) : ''; ?>" required>

									<?php echo form_error('promotional_code'); ?>



								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Description: </label>

								</div>

								<div class="col-md-9">

									<input type="text" placeholder="Description" class="form-control" name="description"

										value="<?php echo (isset($ArrFieldData['description'])) ? trim($ArrFieldData['description']) : ''; ?>">

									<?php //echo form_error('description'); ?>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Start From<span class="red">*</span>: </label>

								</div>

								<div class="col-md-9">

									<input type="text" placeholder="Start From" name="start_from" id="start_from"

										value="<?php echo (isset($ArrFieldData['start_from'])) ? date('d-m-Y', strtotime($ArrFieldData['start_from'])) : ''; ?>"

										class='date_picker_bottom_left form-control' autocomplete="true">

									<?php echo form_error('start_from'); ?>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Valid Upto<span class="red">*</span>: </label>

								</div>

								<div class="col-md-9">

									<input type="text" placeholder="Valid Upto" name="valid_upto" id="valid_upto"

										value="<?php echo (isset($ArrFieldData['valid_upto'])) ? date('d-m-Y', strtotime($ArrFieldData['valid_upto'])) : ''; ?>"

										class='date_picker_bottom_left form-control' autocomplete="true">

									<?php echo form_error('valid_upto'); ?>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Brands:</label>

								</div>

								<div class="col-md-9">

									<?php

									$attributes = array('id' => 'brands', 'class' => 'form-control', "multiple" => "true");

									echo form_dropdown('brands[]', $options_brand, $brand_ids, $attributes);

									?>

									<?php echo form_error('brands'); ?>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Exclude Category:</label>

								</div>

								<div class="col-md-9">

									<?php

									$attributes = array('id' => 'exclude_category', 'class' => 'form-control', "multiple" => "true");

									echo form_dropdown('exclude_category[]', $options_category, $Arrexcludecategory_id, $attributes);

									?>

									<?php echo form_error('exclude_category'); ?>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Minimum Order Value: </label>

								</div>

								<div class="col-md-9">

									<input type="text" placeholder="Minimum Order Value" name="minimum_order_value"

										id="minimum_order_value"

										value="<?php echo (isset($ArrFieldData['minimum_order_value'])) ? trim($ArrFieldData['minimum_order_value']) : ''; ?>"

										class='form-control'>

									<?php echo form_error('minimum_order_value'); ?>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Maximum Discount ($): </label>

								</div>

								<div class="col-md-9">

									<input type="text" placeholder="Maximum Discount ($)" name="maximum_order_discount"

										id="maximum_order_discount"

										value="<?php echo (isset($ArrFieldData['maximum_order_discount'])) ? trim($ArrFieldData['maximum_order_discount']) : ''; ?>"

										class='form-control'>

									<?php echo form_error('maximum_order_discount'); ?>

								</div>

							</div>





							<input type="hidden" name="apply_to" id="apply_to" value="A" />



							<div class="form-group">

								<div class="col-md-3">

									<label>Promotional type<span class="red">*</span>: </label>

								</div>

								<div class="col-md-9">

									<select name="promotional_type" class="form-control select_nocler" required>

										<option value="">Select</option>

										<option value="S" <?php if (isset($ArrFieldData['promotional_type']) && $ArrFieldData['promotional_type'] == 'S') {

											echo 'selected';

										} ?>>Single

										</option>

										<option value="M" <?php if (isset($ArrFieldData['promotional_type']) && $ArrFieldData['promotional_type'] == 'M') {

											echo 'selected';

										} ?>>Multiple

										</option>

										<option value="OT" <?php if (isset($ArrFieldData['promotional_type']) && $ArrFieldData['promotional_type'] == 'OT') {

											echo 'selected';

										} ?>>Only One

											Time</option>

									</select>

									<?php echo form_error('promotional_type'); ?>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Promotional Code For<span class="red">*</span>: </label>

								</div>

								<div class="col-md-9">

									<select name="apply_to" class="form-control select_nocler" required=""

										onchange="showHideClientGroupDiv(this.value);">

										<option value="">Select</option>

										<option value="SG" <?php echo (@$ArrFieldData['apply_to'] == "SG") ? 'Selected' : '' ?>>Specific Group</option>

										<option value="AC" <?php echo (@$ArrFieldData['apply_to'] == "AC") ? 'Selected' : '' ?>>All Customer</option>

										<option value="NC" <?php echo (@$ArrFieldData['apply_to'] == "NC") ? 'Selected' : '' ?>>New Customer</option>

										<option value="RC" <?php echo (@$ArrFieldData['apply_to'] == "RC") ? 'Selected' : '' ?>>Returning Customer</option>

									</select>

									<?php echo form_error('apply_to'); ?>

								</div>

							</div>

							<!--

					 

					 <div class="form-group" id="customer_div" <?php if (@$ArrFieldData['apply_to'] != 'SC') {

						 echo 'style="display:none;"';

					 } ?>>

						<div class="col-md-3">

						   <label>Select Customer<span class="red">*</span>: </label>

						</div>

						<div class="col-md-9">

						   <?php @$result_data = $this->user_model->getUserByID($ArrFieldData['specific_customer_id']); ?>

						   <input type="text" placeholder="Select Customer" id="search-box" class="form-control" name="specific_customer_email" value="<?php echo (isset($result_data['email'])) ? trim($result_data['email']) : ''; ?>" autocomplete="off" <?php if (isset($ArrFieldData) && $ArrFieldData['apply_to'] != 'SC') {

								  echo 'required';

							  } ?>>

						   <?php echo form_error('specific_customer_id'); ?>

							<input type="hidden" id="search-box-hides" name="specific_customer_id" value="<?php echo (isset($ArrFieldData['specific_customer_id'])) ? trim($ArrFieldData['specific_customer_id']) : ''; ?>" />

														

							<div id="suggesstion-box"></div>

							<div id="error-email-id" style="display:none;color:red"> No Match Found</div>

						</div>

					 </div>

					 -->







							<div id="client_group_div" <?php if (@$ArrFieldData['apply_to'] != 'SG') {

								echo 'style="display:none;"';

							} ?>>



								<style type="text/css">

									select {

										width: 170px;

									}



									.controls {

										float: left;

										margin-left: 80px;

									}



									.controls a {

										background-color: #008d4c;

										border-radius: 4px;

										border: 1px solid #000;

										color: #ffffff;

										padding: 5px;

										font-size: 14px;

										text-decoration: none;

										display: inline-block;

										text-align: center;

										margin: 5px;

										width: 30px;

									}

								</style>

								<div class="form-group">

									<div class="col-md-3"></div>

									<div class="col-md-9">

										<div class="controls">

											<a href="javascript:moveAll('from', 'to')">&gt;&gt;</a>

											<a href="javascript:moveSelected('from', 'to')">&gt;</a>

											<a href="javascript:moveSelected('to', 'from')">&lt;</a>

											<a href="javascript:moveAll('to', 'from')" href="#">&lt;&lt;</a>

										</div>

									</div>

								</div>

								<div class="form-group">

									<div class="col-md-3">

										<label>Client Group:</label>

									</div>



									<script>

										$('#from option:selected').remove().appendTo('#to');

										$('#from option').remove().appendTo('#to');

									</script>

									<script>

										function moveAll(from, to) {

											$('#' + from + ' option').remove().appendTo('#' + to);

										}



										function moveSelected(from, to) {

											$('#' + from + ' option:selected').remove().appendTo('#' + to);

										}

										function selectAll() {

											$("select option").attr("selected", "selected");

										}

									</script>

									<div class="col-md-9">

										<select multiple size="10" id="from" name="clientgroup">

											<?php $ArrSelectedClientGroupId = array();

											if (isset($ArrClientGroup) && $ArrClientGroup > 0) {

												foreach ($ArrClientGroup as $group) {

													if (in_array($group['clientgroup_id'], $ArrSelectedClientGroupId))

														continue;

													?>

													<option value="<?php echo $group['clientgroup_id']; ?>"><?php echo $group['clientgroup_title']; ?></option>

												<?php }

											} ?>

										</select>

										<select multiple id="to" size="10" name="selected_clientgroup[]">

											<?php $ArrSelectedClientGroupId = array();

											foreach ($ArrClientGroup as $group) {

												if (!in_array($group['clientgroup_id'], $ArrSelectedClientGroupId))

													continue;

												?>

												<option value="<?php echo $group['clientgroup_id']; ?>" selected><?php echo $group['clientgroup_title']; ?></option>

											<?php } ?>

										</select>

									</div>

								</div>

							</div>



							<div class="form-group">

								<div class="col-md-3">

									<label>Select Products<span class="red">*</span>: </label>

								</div>

								<div class="col-md-9">

									<select name="apply_to_product" class="form-control select_nocler" required=""

										onchange="showHideProductsDiv(this.value);">

										<option value="">Select</option>

										<option value="A" <?php echo (@$ArrFieldData['apply_to_product'] == "A") ? 'Selected' : '' ?>>All Products

										</option>

										<option value="SP" <?php echo (@$ArrFieldData['apply_to_product'] == "SP") ? 'Selected' : '' ?>>Specific

											Products</option>

									</select>

									<?php echo form_error('apply_to_product'); ?>



								</div>

							</div>







							<div id="all_product_div">

								<div class="form-group">

									<div class="col-md-3">

										<label>Discount Type:<span class="red">*</span></label>

									</div>

									<div class="col-md-9">

										<select name="discount_type" id="discount_type"

											class="form-control select_nocler" onChange="setDiscountType(this.value);">

											<option value="">Select</option>

											<option value="%" <?php echo (@$ArrFieldData['discount_type'] == "%") ? 'Selected' : ''; ?>>Percentage

											</option>

											<option value="$" <?php echo (@$ArrFieldData['discount_type'] == "$") ? 'Selected' : ''; ?>>Amount($)

											</option>

										</select>

									</div>

								</div>



								<div class="form-group">

									<div class="col-md-3">

										<label>

											Discount value:<span class="red">*</span></label>

									</div>

									<div class="col-md-9">

										<input type="text" name="discount_value" id="discount_value" maxlength="4"

											value="<?php echo (isset($ArrFieldData['discount_value'])) ? trim($ArrFieldData['discount_value']) : ''; ?>"

											placeholder="Discount value" class="form-control only_number" /><span

											id="discount_type_span"

											style="float:right;font-size:22px;margin:-35px 6px;"><b>

												<?php echo (isset($ArrFieldData['discount_value'])) ? trim($ArrFieldData['discount_value']) : ''; ?>

											</b></span>

									</div>

								</div>

							</div>





							<div id="specific_product_div" <?php if (@$ArrFieldData['apply_to_product'] == 'SP') {

								echo 'style="display:block;"';

							} else {

								echo 'style="display:none;"';

							} ?>>

								<div class="form-group">

									<div class="col-md-3"><label>Select Products:</label></div>

									<div class="col-md-9">







										<script>

											$(document).ready(function () {

												$("#promotional_code_products").on('click', '.remove-button', function (e) {

													var rowCount = $('#promotional_code_products tr').length;

													if (rowCount <= 1) {

														alert('You can not remove all the products');

													}

													else {

														var whichtr = $(this).closest("tr");

														whichtr.remove();

														var product_counter = parseInt($("#product_counter").val());

														product_counter = product_counter - 1;

														$("#product_counter").val(product_counter);

													}

												});



											});

											function addRow() {

												var product_counter = parseInt($("#product_counter").val());

												product_counter = product_counter + 1;

												$("#product_counter").val(product_counter);



												$('#promotional_code_products').append('<tr><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove Product"><img src="<?php echo admin_media(); ?>dist/img/close-2.png"></a></td><td><select name="product_selected_product[]" class="form-control selProduct' + product_counter + ' product_option" onChange="checkPreselected(this);"><option value="">--Select Product--</option><?php foreach ($ArrProducts as $product) { ?><option value="<?php echo $product['product_id']; ?>"><?php echo preg_replace('/[^A-Za-z0-9\-]/', '', $product['product_name']); ?></option><?php } ?></select></td><td><a href="javascript:void(0);" onClick="addRow()" title="Click here to add new Product"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td></tr>');

											}

										</script>

										<style type="text/css">

											#promotional_code_products tr td {

												padding: 2px;

											}



											#promotional_code_products .form-control {

												width: 100%;

											}

										</style>



										<table id="promotional_code_products">

											<?php

											$product_counter = 1;

											if (is_array($ArrSelectedProducts) && count($ArrSelectedProducts) > 0) {

												foreach ($ArrSelectedProducts as $product_details) {

													?>

													<tr>

														<td>

															<a href="javascript:void(0);" class="remove-button"

																title="Click here to remove Product">

																<img src="<?php echo admin_media(); ?>dist/img/close-2.png">

															</a>

														</td>

														<td>

															<select name="product_selected_product[]" required

																class="form-control selProduct<?php echo $product_counter; ?>"

																onChange="checkPreselected(this);">

																<option value="">--Select Product--</option>

																<?php foreach ($ArrProducts as $product) { ?>

																	<option value="<?php echo $product['product_id']; ?>" <?php if ($product['product_id'] == $product_details['product_id']) {

																		   echo 'selected';

																	   } ?>><?php echo $product['product_name']; ?></option>

																<?php } ?>

															</select>

														</td>

														<td><a href="javascript:void(0);" onClick="addRow()"

																title="Click here to add new Product"><img

																	src="<?php echo admin_media(); ?>dist/img/plus.png"></a>

														</td>

													</tr>



													<?php

												}

											} else { #End if 

												?>

											<tr>

												<td>

													<a href="javascript:void(0);" class="remove-button"

														title="Click here to remove Product">

														<img src="<?php echo admin_media(); ?>dist/img/close-2.png">

													</a>

												</td>

												<td>

													<select name="product_selected_product[]"

														class="form-control selProduct1"

														onChange="checkPreselected(this);">

														<option value="">--Select Product--</option>

														<?php foreach ($ArrProducts as $product) { ?>

															<option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>

														<?php } ?>

													</select>

												</td>

												<td><a href="javascript:void(0);" onClick="addRow()"

														title="Click here to add new Product"><img

															src="<?php echo admin_media(); ?>dist/img/plus.png"></a>

												</td>

											</tr>

											<?php } ?>



										</table>

										<input type="hidden" name="product_counter" id="product_counter"

											value="<?php echo $product_counter; ?>" />

									</div>

								</div>

							</div>


							<div class="form-group">

								<div class="col-md-3">

									<label>Coupon For:<span class="red">*</span></label>

								</div>

								<div class="col-md-9">

									<select name="coupon_for" id="coupon_for"

										class="form-control">

										<option value="">Select</option>

										<option value="all" <?php echo (@$ArrFieldData['coupon_for'] == "all") ? 'Selected' : ''; ?>>All

										</option>

										<option value="website" <?php echo (@$ArrFieldData['coupon_for'] == "website") ? 'Selected' : ''; ?>>Website

										</option>

										<option value="mobile_aplication" <?php echo (@$ArrFieldData['coupon_for'] == "mobile_aplication") ? 'Selected' : ''; ?>>Mobile Application

										</option>

									</select>

								</div>

							</div>

							<div class="form-group">

								<div class="col-md-3">

									<label>Is Active : </label>

								</div>

								<div class="col-md-9">

									<?php if (!empty($edit_id) && $edit_id > 0) { ?>

										<div class="add_promotion_radios">

											<div class="radio_left_status">

												<input type="radio" name="is_active" id="radio1" value="1" <?php if ($ArrFieldData['is_active'] == '1') { ?> checked="checked" <?php } ?>>

												<label for="radio1"> Yes</label>

											</div>

											<div class="radio_left_status">

												<input type="radio" name="is_active" id="radio2" value="0" <?php if ($ArrFieldData['is_active'] == '0') { ?> checked="checked" <?php } ?>>

												<label for="radio2"> No </label>

											</div>

										</div>

									<?php } else { ?>



										<div class="add_promotion_radios">

											<div class="radio_left_status">

												<input type="radio" name="is_active" id="radio1" value="1"

													checked="checked">

												<label for="radio1"> Yes</label>

											</div>

											<div class="radio_left_status">

												<input type="radio" name="is_active" id="radio2" value="0">

												<label for="radio2"> No </label>

											</div>

										</div>

									<?php } ?>

								</div>

							</div>







							<div class="form-group">

								<div class="col-md-2 col-md-offset-3">

									<input type="hidden"

										value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>"

										name="promotional_code_id">

									<button type="submit" class="btn btn-default" name="submit" id="submit"

										onClick="return validation();"

										value="<?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?>" <?php echo 'name="save_user"'; ?>><?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?></button>

									<button type="reset" value="Reset" class="btn btn-info">Cancel</button>



								</div>

							</div>

						</div>

					</div>

					</form>

				</div>

			</div>

		</div>

	</div>

</div>



</div>