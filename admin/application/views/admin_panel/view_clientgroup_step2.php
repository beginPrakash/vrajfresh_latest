<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box1">
				<div class="box-info">
					<div class="box-text box-body">
						<?php



						$attributes = array('class' => 'form-horizontal', 'id' => 'clientgroup__form1', 'role' => 'form', 'enctype' => 'multipart/form-data');
						if (isset($edit_id) && $edit_id > 0) {
							//echo form_open('promotional-code-update/'.$edit_id, $attributes);
						} else {
							echo form_open('clientgroup-step2', $attributes);
						}
						//print_r($ArrFieldData);
						?>
						<div class="box-body">
							<div class="form-group">
								<div class="col-md-3">
									<label>Customer Type<span class="red">*</span>: </label>
								</div>
								<div class="col-md-9">
									<div class="add_promotion_radios">
										<div class="radio_left_status">
											<input type="radio" name="group" id="radio11" value="1" checked="checked"
												onClick="titleHideShow(1);">
											<label for="radio11">Customer add in existing group</label>
										</div>
										<div class="radio_left_status">
											<input type="radio" name="group" id="radio22" value="0"
												onClick="titleHideShow(0);">
											<label for="radio22">Create New Group</label>

										</div>
									</div>

								</div>
							</div>


							<div class="form-group" id="client_group_dd">
								<div class="col-md-3"><label>Select Group:</label></div>
								<div class="col-md-9">
									<?php
									$ArrOptionClientGroup = array();
									$ArrOptionClientGroup[''] = "-- Select Group --";

									if (is_array($ArrClientGroup) && count($ArrClientGroup) > 0) {
										foreach ($ArrClientGroup as $row) {
											$ArrOptionClientGroup[$row['clientgroup_id']] = $row['clientgroup_title'];
										}
									}
									echo form_dropdown('client_group', $ArrOptionClientGroup, '', 'id="client_group" class="form-control select_with_clear" onChange="titleHideShow(this.value)"');
									?>
								</div>
							</div>

							<div class="form-group" id="client_group_title" style="display:none;">
								<div class="col-md-3"><label>Customer Group Title<span class="red">*</span>: </label>
								</div>
								<div class="col-md-9"><input type="text" placeholder="Group Title" class="form-control"
										id="clientgroup_title" name="clientgroup_title">
									<?php echo form_error('clientgroup_title'); ?>
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
												<input type="radio" name="radio1" id="radio1" value="Y" <?php if ($ArrFieldData['is_active'] == 'Y') { ?> checked="checked" <?php } ?>>
												<label for="radio1"> Yes</label>
											</div>
											<div class="radio_left_status">
												<input type="radio" name="radio1" id="radio2" value="N" <?php if ($ArrFieldData['is_active'] == 'N') { ?> checked="checked" <?php } ?>>
												<label for="radio2"> No </label>
											</div>
										</div>
									<?php } else { ?>

										<div class="add_promotion_radios">
											<div class="radio_left_status">
												<input type="radio" name="radio1" id="radio1" value="Y" checked="checked">
												<label for="radio1"> Yes</label>
											</div>
											<div class="radio_left_status">
												<input type="radio" name="radio1" id="radio2" value="N">
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
										name="clientgroup_id">
									<button type="submit" class="btn btn-default" name="submit" id="submit" onClick=""
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