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

						echo form_open('save-cashcredits', $attributes);

						?>

						<div class="box-body">



							<div class="col-md-12">

								<div class="form-group">

									<div class="col-md-12">

										<h4>Cash Credit :</h4>

									</div>

								</div>

							</div>

							<div class="col-md-12">

									<div class="form-group">

										<div class="col-md-4">

										Cash Credit % : <span class="red">*</span>

										</div>

										<div class="col-sm-8">

											<input type="number" placeholder="Credit Value (%)"

											class="form-control" name="credit_val"

											min="1" value="<?php echo $creditData['credit_per']; ?>" required />

										</div>

									</div>		

							</div>

							<div class="box-body">

								<div class="col-md-12">

									<div class="btn_alignmemt"><button class="btn btn-success " name="submit"

											value="">Update</button></div>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>