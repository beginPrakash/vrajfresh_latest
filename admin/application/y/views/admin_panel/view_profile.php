<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box1" >
				<div class="box-info">
					<div class="box-text box-body profile">
						<?php if(isset($error) && $error!='') { ?>
						<span style="color:red;text-align:center;"><?php echo $error; ?></span>
						<?php } ?>
						<?php if(isset($msg) && $msg!='') { ?>
						<span style="color:green;text-align:center;"><?php echo $msg; ?></span>
						<?php } ?>

						<?php
						$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'enctype' => 'multipart/form-data');
						echo form_open('my-profile', $attributes); 
						?>
						<div class="box-body">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-4">Login Name : <span class="red">*</span></div>
								<div class="col-sm-8">
								<input type="text" placeholder="Login Name" class="form-control" name="user_name" value="<?php echo $user['user_name']; ?>" required />
								<?php echo form_error('user_name'); ?>
								</div>
							</div>
							<div class="form-group">
							<div class="col-md-4">Email : <span class="red">*</span></div>
								<div class="col-sm-8">
							<input type="text" placeholder="Email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required >
							<?php //echo form_error('name'); ?>
							</div>
							</div>


							<div class="form-group">
							<div class="col-md-4">First Name : </div>
								<div class="col-sm-8">
							<input type="text" placeholder="First Name" class="form-control" name="first_name" value="<?php echo $user['first_name']; ?>">
							<?php echo form_error('first_name'); ?>
							</div>
							</div>
							<div class="form-group">
							<div class="col-md-4">Last Name : </div>
								<div class="col-sm-8">
							<input type="text" placeholder="Last Name" class="form-control" name="last_name" value="<?php echo $user['last_name']; ?>">
							<?php echo form_error('last_name'); ?>
							</div>
							</div>

							<div class="form-group">
							<div class="col-md-4">Address1 : </div>
								<div class="col-sm-8">
							<input type="text" placeholder="Address Line 1" class="form-control" name="address1" value="<?php echo $user['address']; ?>">
							<?php //echo form_error('name'); ?>
							</div>
							</div>

							</div>
						
						<div class="col-md-6">
							<div class="form-group">
							<div class="col-md-4">Address2 : </div>
								<div class="col-sm-8">
							<input type="text" placeholder="Address Line 2" class="form-control" name="address2" value="<?php echo $user['address2']; ?>">
							<?php //echo form_error('name'); ?>
							</div>
							</div>


							<div class="form-group">
							<div class="col-md-4">City :</div>
								<div class="col-sm-8">
							<input type="text" placeholder="City" class="form-control" name="city" value="<?php echo $user['city']; ?>">
							<?php //echo form_error('name'); ?>
							</div> 
							</div> 


							<div class="form-group">
							<div class="col-md-4">State : </div>
								<div class="col-sm-8">
							<input type="text" placeholder="State" class="form-control" name="state" value="<?php echo $user['state']; ?>">
							<?php //echo form_error('name'); ?>
							</div>  
							</div>  


							<div class="form-group">
							<div class="col-md-4">Zip :</div>
								<div class="col-sm-8">
							<input type="text" placeholder="ZIP Code" class="form-control only_number" name="zip" value="<?php echo $user['zip']; ?>">
							<?php //echo form_error('name'); ?>
							</div> 
							</div> 


							<div class="form-group">
							<div class="col-md-4">Phone : </div>
								<div class="col-sm-8">
							<input type="text" placeholder="Phone Number" class="form-control" name="phone" value="<?php echo $user['phone']; ?>">
							<?php //echo form_error('name'); ?>
							</div> 
							</div> 

						
						

						
						
						</div>
						
						<div class="box-body">
						
							
						
							
							<div class="col-md-12">
							<div class="btn_alignmemt"><button class="btn btn-success " name="submit" value="change_password">Update Profile</button></div>
							</div>
							
						</div>
				</div>
			</div>
		</div>
	</div>
</div>





