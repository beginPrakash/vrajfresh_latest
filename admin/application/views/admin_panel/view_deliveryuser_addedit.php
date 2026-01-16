<div class="row">

   <div class="col-md-12">

      <!-- general form elements -->

      <div class="box box-primary">





         <!-- form start -->

         <form role="form" method="post" name="deliveryuser" id="deliveryuser"

            action="<?php echo SITE_URL ?>adminpanel/Controller_delivery_user/save">

            <div class="box-body">

               <!-- FORM FIELD -->

               <div class="add_users_page_form_main">

                  <div class="col-sm-4">

                     <div class="form-group">

                        <label>First Name : <span class="red">*</span></label>

                        <input type="text" value="<?php echo @$ArrFieldData['first_name'] ?>" placeholder="First Name"

                           class="form-control" name="first_name" required>

                        <?php echo form_error('first_name'); ?>

                     </div>

                  </div>

                  <div class="col-sm-4">

                     <div class="form-group">

                        <label>Last Name : <span class="red">*</span></label>

                        <input type="text" value="<?php echo @$ArrFieldData['last_name'] ?>" placeholder="Last Name"

                           class="form-control" name="last_name" required>

                        <?php echo form_error('last_name'); ?>

                     </div>

                  </div>

                   <div class="col-sm-4">

                     <div class="form-group">

                        <label>Email : <span class="red">*</span></label>

                        <input type="text" placeholder="email" class="form-control" name="email" id="email"

                           value="<?php echo @$ArrFieldData['email'] ?>" required>

                        <span class="error email_exist"></span>

                        <?php //echo form_error('name'); ?>

                     </div>

                  </div>


                  <?php if (isset($ArrFieldData['user_id']) && $ArrFieldData['user_id'] > 0) { ?>

                  <?php } else { ?>

                     <div class="col-sm-4">

                        <div class="form-group">

                           <label>Password : <span class="red">*</span></label>

                           <input type="password" placeholder="password" class="form-control"

                              value="<?php echo @$ArrFieldData['password'] ?>" name="password" required>

                           <?php echo form_error('password'); ?>

                        </div>

                     </div>

                  <?php } ?>

                 

                  <div class="col-sm-4">

                     <div class="form-group">

                        <label>Phone : </label>

                        <input type="text" value="<?php echo @$ArrFieldData['phone'] ?>" placeholder="Phone"

                           class="form-control numberonly" name="phone" maxlength="10">

                        <?php //echo form_error('name'); ?>

                     </div>

                  </div>

                  <div class="col-sm-4">

                     <div class="form-group">

                        <label>City : <span class="red">*</span></label>

                        <input type="text" value="<?php echo @$ArrFieldData['city'] ?>" placeholder="City"

                           class="form-control" name="city" required>

                        <?php echo form_error('city'); ?>

                     </div>

                  </div>

                  <div class="col-sm-4">

                     <div class="form-group">

                        <label>Zipcode : <span class="red">*</span></label>

                        <input type="text" value="<?php echo @$ArrFieldData['zipcode'] ?>" placeholder="Zipcode"

                           class="form-control numberonly" name="zipcode" required>

                        <?php echo form_error('zipcode'); ?>

                     </div>

                  </div>
                  <div class="col-sm-4">

                     <div class="form-group">

                        <label>Is Active: </label>

                        <div class="checkbox checkbox_and_radio_buttons">

                           <?php if (isset($ArrFieldData['user_id']) && $ArrFieldData['user_id'] > 0) { ?>

                              <label>

                                 <input type="radio" name="is_active" value="1" <?php echo (isset($ArrFieldData['is_active']) && $ArrFieldData['is_active'] == "1") ? 'checked="checked"' : '' ?>> Yes



                              </label>



                              <label>

                                 <input type="radio" name="is_active" value="0" <?php echo (isset($ArrFieldData['is_active']) && $ArrFieldData['is_active'] == "0") ? 'checked="checked"' : '' ?>> No

                              </label>

                           <?php } else { ?>

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

               <input type="submit" class="btn btn-default"

                  value="<?php echo (isset($ArrFieldData['user_id']) && $ArrFieldData['user_id'] > 0) ? 'Update' : 'Add'; ?>"

                  <?php echo 'name="save_user"'; ?> onClick="return validate_adduser();">



               <button type="reset" value="Reset" class="btn btn-info">Cancel</button>

               <input type="hidden" id="check_email"

                  value="<?php echo SITE_URL ?>adminpanel/Controller_delivery_user/ajaxCheckEmail">

               <input type="hidden" id="check_user"

                  value="<?php echo SITE_URL ?>adminpanel/Controller_delivery_user/ajaxCheckUsername">

               <input type="hidden" name="user_id" id="user_id"

                  value="<?php echo (isset($ArrFieldData['user_id']) && $ArrFieldData['user_id'] > 0) ? $ArrFieldData['user_id'] : 0; ?>">

            </div>

            <!-- /.box-footer -->

         </form>

      </div>

      <!-- /.box -->

   </div>

   <!-- col-md-12 -->

</div><!-- row -->
