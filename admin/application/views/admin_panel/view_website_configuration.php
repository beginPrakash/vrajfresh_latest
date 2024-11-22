<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box1">
				<div class="box-info">
					<div class="box-text box-body profile">
						<?php if (isset($error) && $error != '') { ?>
							<span style="color:red;text-align:center;">
								<?php echo $error; ?>
							</span>
						<?php } ?>
						<?php if (isset($msg) && $msg != '') { ?>
							<span style="color:green;text-align:center;">
								<?php echo $msg; ?>
							</span>
						<?php } ?>

						<?php
						$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'enctype' => 'multipart/form-data');
						echo form_open('save-configuration', $attributes);
						?>
						<div class="box-body">

							<div class="col-md-12">
								<div class="form-group">
									<div class="col-md-12">
										<h4>Website:</h4>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<?php foreach ($ArrConfiguration as $Arr) { ?>
									<div class="form-group">
										<div class="col-md-4">
											<?php echo $Arr['configuration_type']; ?>: <span class="red">*</span>
										</div>
										<div class="col-sm-8">
											<?php if ($Arr['field_type'] == 'text') { ?>
												<input type="text" placeholder="<?php echo $Arr['configuration_key']; ?>"
													class="form-control" name="ArrData[<?php echo $Arr['configuration_id']; ?>]"
													value="<?php echo $Arr['configuration_value']; ?>" required />
											<?php } elseif ($Arr['field_type'] == 'textarea') { ?>
												<textarea placeholder="<?php echo $Arr['configuration_key']; ?>" rows="3"
													class="form-control" id="editor1"
													name="ArrData[<?php echo $Arr['configuration_id']; ?>]"><?php echo $Arr['configuration_value']; ?></textarea>
											<?php } ?>


										</div>
									</div>
								<?php } ?>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<div class="col-md-12">
										<h4>Home Product Sections:</h4>
									</div>
								</div>
							</div>

							<?php foreach ($ArrSpecialProducts as $data) { ?>
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-md-4">
											<?php echo $data['special_product_name']; ?>:
										</div>
										<div class="col-sm-8">


											<?php

											$name = 'ArrProducts[' . $data['special_product_id'] . '][]';
											$ArrOptions = array('' => "Select");
											$ArrSelectedProducts = $array = explode(',', $data['product_ids']);
											$ArrProducts = $this->product_model->product_list_data();
											foreach ($ArrProducts as $key => $value) {
												$ArrOptions[$value['product_id']] = $value['product_name'];
											}
											//$ArrOptions = array('' => "Select");
											$html_element = 'class="multiple_product_select select_product_ajax" required  multiple=""';
											echo form_dropdown($name, $ArrOptions, $ArrSelectedProducts, $html_element);
											?>


										</div>
									</div>
								</div>
							<?php } ?>


							<div class="box-body">




								<div class="col-md-12">
									<div class="btn_alignmemt"><button class="btn btn-success " name="submit"
											value="change_password">Update Settings</button></div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>