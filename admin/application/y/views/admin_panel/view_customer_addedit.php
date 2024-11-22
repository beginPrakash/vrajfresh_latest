<div class="row">
   <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
         
         
         <!-- form start -->
         <?php
          //form validation
          $attributes = array('id' => 'customer_form', 'role' => 'form', 'enctype' => 'multipart/form-data','onSubmit' => 'return valdiateform();');
          $customer_id= (!empty($edit_id) && $edit_id>0)?$edit_id:'';
          if(!empty( $customer_id )) {
             echo form_open('customer-update/'.$customer_id, $attributes);;
          }else{
             echo form_open('customer-add', $attributes);
          }
          ?>
            <div class="box-body">
               <!-- FORM FIELD -->
               <div class="add_users_page_form_main">
              
               
               
               
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>First Name : <span class="red">*</span></label>
                     <input type="text" value="<?php echo @$ArrFieldData['first_name'] ?>" placeholder="First Name" class="form-control" name="first_name" required>
                     <?php echo form_error('first_name'); ?>
                  </div>
               </div>
               
               
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Last Name : </label>
                     <input type="text" value="<?php echo @$ArrFieldData['last_name'] ?>" placeholder="Last Name" class="form-control" name="last_name">
                     <?php echo form_error('last_name'); ?>
                  </div>
               </div>
               
               
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Display Name : <span class="red">*</span></label>
                     <input type="text" value="<?php echo @$ArrFieldData['display_name'] ?>" placeholder="Display Name" class="form-control" name="display_name" required>
                     <?php echo form_error('display_name'); ?>
                  </div>
               </div>

               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Email : <span class="red">*</span></label>
                     <input type="email" placeholder="email" class="form-control" name="email" id="email" value="<?php echo @$ArrFieldData['email'] ?>" required>
                     <span class="error email_exist"></span>
                     <?php //echo form_error('name'); ?>
                  </div>
               </div>
               
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Mobile : <span class="red">*</span></label>
                     <input type="text" value="<?php echo @$ArrFieldData['mobile_no'] ?>" placeholder="Mobile" class="form-control numberonly" name="mobile_no" required maxlength="12">
                     <?php //echo form_error('name'); ?>
                  </div>
               </div>

               
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Phone : </label>
                     <input type="text" value="<?php echo @$ArrFieldData['phone'] ?>" placeholder="Phone" class="form-control numberonly" name="phone" maxlength="12">
                     <?php //echo form_error('name'); ?>
                  </div>
               </div>

               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Address 1 : </label>
                     <input type="text" value="<?php echo @$ArrFieldData['address'] ?>" placeholder="Address 1" class="form-control" name="address">
                     <?php //echo form_error('name'); ?>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Address 2 : </label>
                     <input type="text" value="<?php echo @$ArrFieldData['address2'] ?>" placeholder="Address 2" class="form-control" name="address2">
                     <?php //echo form_error('name'); ?>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>City : </label>
                     <input type="text" value="<?php echo @$ArrFieldData['city'] ?>" placeholder="City" class="form-control" name="city">
                     <?php //echo form_error('name'); ?>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Country : </label>
                     <?php $country_id = ( isset($ArrFieldData['country_id']) )?$ArrFieldData['country_id']:235;?>
                     <?php echo form_dropdown('country_id', $ArrCountryOption, $country_id, 'id="country_id" class="form-control" onChange="getState(this.value);"'); ?>
                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>State : </label>
                     
                     <?php $state = ( isset($ArrFieldData['state']) )?$ArrFieldData['state']:0;?>
                     <div id="stateDD">
                        <?php echo form_dropdown('state', $ArrStateOption, $state, 'id="state" class="form-control"'); ?>
                     </div>

                  </div>
               </div>
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Zip : </label>
                     <input type="text" value="<?php echo @$ArrFieldData['zip'] ?>" placeholder="ZIP Code" class="form-control numberonly" name="zip" maxlength="12">
                     <?php //echo form_error('name'); ?>
                  </div>
               </div>
              
               <div class="col-sm-4">
                  <div class="form-group">
                     <label>Is Active: </label>      
                     <div class="checkbox checkbox_and_radio_buttons">
                        <?php if(isset($ArrFieldData['user_id']) && $ArrFieldData['user_id'] > 0){ ?>
                        <label>
                           <input type="radio" name="is_active" value="1" <?php echo (isset($ArrFieldData['is_active']) && $ArrFieldData['is_active'] == "1")?'checked="checked"':'' ?>> Yes  
                           
                        </label>
						
						<label> 
                           <input type="radio" name="is_active" value="0" <?php echo (isset($ArrFieldData['is_active']) && $ArrFieldData['is_active'] == "0")?'checked="checked"':'' ?>> No 
                        </label>
                        <?php }else { ?>
                        <label>
                           <input type="radio" name="is_active" value="1" checked="checked"> Yes 
                        </label>
						
						<label>
                           <input type="radio" name="is_active" value="0"> No 
                        </label>
                        <?php } ?>
                     </div>
                     <?php //echo form_error('is_featured'); ?> 
                  </div>
               </div>
            </div>
            <!-- FORM FIELD -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      <input type="submit" class="btn btn-default" value="<?php echo (isset($ArrFieldData['user_id']) && $ArrFieldData['user_id'] > 0)?'Update':'Add'; ?>" <?php echo 'name="save_user"';?> onClick="return validate_adduser();">

      <button type="reset" value="Reset" class="btn btn-info">Cancel</button>
      <input type="hidden" id="check_email" value="<?php echo SITE_URL ?>adminpanel/controller_user/ajaxCheckEmail">
      <input type="hidden" name="user_id" id="user_id" value="<?php echo (isset($ArrFieldData['user_id']) && $ArrFieldData['user_id'] > 0)?$ArrFieldData['user_id']:0; ?>">
      <input type="hidden" id="user_exist" name="user_role_id" value="<?php echo (isset($ArrFieldData['user_role_id']) && $ArrFieldData['user_role_id'] > 0)?$ArrFieldData['user_role_id']:0; ?>">
      </div>
      <!-- /.box-footer -->
      </form>
   </div>
   <!-- /.box -->
</div>
<!-- col-md-12 -->
</div><!-- row -->
<script>
function getState(id)
{
   $(document.body).css({'cursor' : 'wait'});
	

      $.ajax({
		type        : "POST",
      url         : "<?php echo base_url(); ?>adminpanel/Controller_user/getState/"+id,
		success: function(results) {
			$(document.body).css({'cursor' : 'default'});
            $("#stateDD").html(results);
		},
		error: function() {
			$(document.body).css({'cursor' : 'default'});
			toastr.error('Oops...! active status updating failed, please try again.');
		}
	});


}
</script>