<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box1">
				<div class="box-info">
					<div class="box-text box-body">
						<?php




						$attributes = array('class' => 'form-horizontal', 'id' => 'clientgroup__form', 'role' => 'form', 'enctype' => 'multipart/form-data');
						if (isset($edit_id) && $edit_id > 0) {
							//echo form_open('promotional-code-update/'.$edit_id, $attributes);
						} else {
							echo form_open('clientgroup-step1', $attributes);
						}
						//print_r($ArrFieldData);
						?>
						<div class="box-body">




							<div class="form-group">
								<div class="col-md-3">
									<label>Customer Signed up date: </label>
								</div>
								<div class="col-md-9">
									<input type="text" name="user_signup_date" id="user_signup_date"
										placeholder="Signed up From" style="width:150px;"
										class="custom_txtbox date_picker_bottom_left" autocomplete="off" />
									-
									<input type="text" name="user_signup_date_to" id="user_signup_date_to"
										placeholder="Signed up To" style="width:150px;"
										class="custom_txtbox date_picker_bottom_left" autocomplete="off" />

									<?php echo form_error('description'); ?>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-3"><label>Amount paid till date:</label></div>
								<div class="col-md-9">
									<input type="text" name="amount_paid" id="amount_paid"
										placeholder="Amount e.g. 5000" style="width:150px;"
										class="custom_txtbox only_number" />
									-
									<input type="text" name="amount_paid_to" id="amount_paid_to"
										placeholder="Amount e.g. 5000" style="width:150px;"
										class="custom_txtbox only_number" />
								</div>
							</div>
							<!--div class="form-group">
					  <div class="col-md-3"><label>Amount outstanding till date: </label></div>
					  <div class="col-md-9">
						<input type="text" name="amount_outstanding" id="amount_outstanding" placeholder="Amount e.g. 5000" class="custom_txtbox only_number" style="width:150px;" />	
						-
						<input type="text" name="amount_outstanding_to" id="amount_outstanding_to" placeholder="Amount e.g. 5000" class="custom_txtbox only_number"  style="width:150px;"/>	
					  </div>
					</div-->
							<div class="form-group">
								<div class="col-md-3"><label>Number of orders:</label></div>
								<div class="col-md-9">
									<input type="number" name="number_of_order_from" id="number_of_order_from"
										style="width:150px;" min="0" placeholder="From" class="custom_txtbox" /> -
									<input type="number" name="number_of_order_to" id="number_of_order_to"
										style="width:150px;" min="0" placeholder="To" class="custom_txtbox" />
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-3"><label>Last Order Date:</label></div>
								<div class="col-md-9">
									<input type="text" name="last_order_date" style="width:150px;" id="last_order_date"
										value="" placeholder="Last Order Date"
										class="form-control date_picker_bottom_left" autocomplete="off" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3"><label>No order in last:</label></div>
								<div class="col-md-9">
									<input type="number" name="no_order_in_last_days" id="no_order_in_last_days"
										style="width:150px;" min="1" placeholder="Days" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-3"><label>Product:</label></div>
								<div class="col-md-9">
									<select name="selProduct" id="selProduct"
										class="form-control multiple_product_select">
										<option value="">--Select Product--</option>
										<?php foreach ($ArrProducts as $product) { ?>
											<option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
										<?php } ?>
									</select>
									<input type="hidden" name="oldselProduct" id="oldselProduct"
										value="<?php //echo $clientgroup[0]['product_id']; ?>" />
								</div>
							</div>


							<div class="form-group">
								<div class="col-md-3"><label>Customer:</label></div>
								<div class="col-md-9">
									<?php
									$ArrSelectedProducts = $ArrOptions = array();
									$name = 'ArrCustomer[]';
									$html_element = 'class="multiple_product_select select_customer_ajax"  multiple=""';
									echo form_dropdown($name, $ArrOptions, $ArrSelectedProducts, $html_element);
									?>
								</div>
							</div>





							<div class="form-group">
								<div class="col-md-2 col-md-offset-3">
									<input type="hidden"
										value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>"
										name="promotional_code_id">
									<button type="submit" class="btn btn-default" name="submit" id="submit"
										onClick="return validation();" <?php echo 'name="save_user"'; ?>>Next</button>

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
<link href="<?php echo admin_media(); ?>dist/multiple_select/jquery.multiselect.css" rel="stylesheet" type="text/css">
<script src="<?php echo admin_media(); ?>dist/multiple_select/jquery.multiselect.js"></script>