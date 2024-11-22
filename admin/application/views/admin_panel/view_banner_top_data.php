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

						echo form_open('banner-top', $attributes);

						?>

						<div class="box-body">

							<div class="col-md-6">

								<div class="form-group">

									<div class="col-md-4">Text : <span class="red">*</span></div>

									<div class="col-sm-8">

										<input type="text" placeholder="Text" class="form-control"

											name="title" value="<?php echo $user['title']; ?>" required />

										<?php echo form_error('title'); ?>

									</div>

								</div>


								<div class="form-group">

									<div class="col-md-4">Url : </div>

									<div class="col-sm-8">

										<input type="text" placeholder="Url" class="form-control"

											name="url" value="<?php echo $user['url']; ?>">

										<?php echo form_error('url'); ?>

									</div>

								</div>

								<div class="form-group">

									<div class="col-md-4">Featured Categories Title : </div>

									<div class="col-sm-8">

										<input type="text" placeholder="Featured Categories Title" class="form-control"

											name="f_categories_title" value="<?php echo $user['f_categories_title']; ?>" required>

										<?php echo form_error('f_categories_title'); ?>

									</div>

								</div>

								<div class="form-group">

									<div class="col-md-4">StockUp TItle : </div>

									<div class="col-sm-8">

										<input type="text" placeholder="StockUp TItle" class="form-control"

											name="stockup_title" value="<?php echo $user['stockup_title']; ?>" required>

										<?php echo form_error('stockup_title'); ?>

									</div>

								</div>

								<div class="form-group">

									<div class="col-md-4">Refill Title : </div>

									<div class="col-sm-8">

										<input type="text" placeholder="Refill Title" class="form-control"

											name="refill_title" value="<?php echo $user['refill_title']; ?>" required>

										<?php echo form_error('refill_title'); ?>

									</div>

								</div>

								<div class="form-group">
								
									<div class="col-md-4">

									<label>Is Active: </label>

									</div>

									<div class="radio col-md-8 radio_btn_design_nomal">

										<label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($user['is_active']) && $user['is_active'] == "1") ? 'checked' : ''; ?>>Yes</label>

										<label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($user['is_active']) && $user['is_active'] == "0") ? 'checked' : ''; ?>>No</label>

								</div>



                     </div>


							</div>



							<div class="box-body">









								<div class="col-md-12">

									<div class="btn_alignmemt"><button class="btn btn-success " name="submit"

											value="change_password">Update</button></div>

								</div>



							</div>

						</div>

					</div>

				</div>

			</div>

		</div>