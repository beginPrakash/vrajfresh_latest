<div class="row">
   <div class="col-md-12">
      <div class="box box-primary">
         <div class="box1" >
            <div class="box-info">
               <!-- DEFAULT LOADER --> 
               <div class="box-text box-body">
                  <?php

					
	  
                     $attributes = array('class' => 'form-horizontal', 'id' => 'clientgroup__form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                     if( isset($edit_id) && $edit_id > 0) {
                        echo form_open('promotional-code-update/'.$edit_id, $attributes);
                     }else{
                        echo form_open('promotional-code-add', $attributes);
                     }
					 
                     ?>
                  <div class="box-body">
                      
					 
					 
                    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Customer Signed up date<span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">                         
						   <input type="text" name="user_signup_date" id="user_signup_date" placeholder="Signed up From" style="width:150px;" class="form-control mdycalender" />	
							-
							<input type="text" name="user_signup_date_to" id="user_signup_date_to" placeholder="Signed up To" style="width:150px;" class="form-control mdycalender" />  
		
                           <?php echo form_error('description'); ?>
                        </div>
                     </div> 
					 
					 <div class="form-group">
					  <div class="col-md-3"><label>Amount paid till date:</label></div>
					  <div class="col-md-9">
						<input type="text" name="amount_paid" id="amount_paid" placeholder="Amount e.g. 5000" style="width:150px;" class="custom_txtbox only_number" />	
						-
						<input type="text" name="amount_paid_to" id="amount_paid_to" placeholder="Amount e.g. 5000" style="width:150px;" class="custom_txtbox only_number" />		
					  </div>
                    </div>
					<div class="form-group">
					  <div class="col-md-3"><label>Amount outstanding till date: </label></div>
					  <div class="col-md-9">
						<input type="text" name="amount_outstanding" id="amount_outstanding" placeholder="Amount e.g. 5000" class="custom_txtbox only_number" style="width:150px;" />	
						-
						<input type="text" name="amount_outstanding_to" id="amount_outstanding_to" placeholder="Amount e.g. 5000" class="custom_txtbox only_number"  style="width:150px;"/>	
					  </div>
					</div>
					<div class="form-group">
					  <div class="col-md-3"><label>Number of orders:</label></div>
					  <div class="col-md-9">
						<input type="number" name="number_of_order_from" id="number_of_order_from" style="width:150px;" min="0" placeholder="From" class="custom_txtbox" /> - 
						<input type="number" name="number_of_order_to" id="number_of_order_to" style="width:150px;" min="0" placeholder="To" class="custom_txtbox" />
					  </div>
					</div>
					
					<div class="form-group">
					   <div class="col-md-3"><label>Last Order Date:</label></div>
					   <div class="col-md-9">
						<input type="text" name="last_order_date" style="width:150px;" id="last_order_date" value="" placeholder="Last Order Date" class="form-control mdycalender" />
					   </div>
					</div>
					<div class="form-group">
					  <div class="col-md-3"><label>No order in last:</label></div>
					  <div class="col-md-9">	
						<input type="number" name="no_order_in_last_days" id="no_order_in_last_days" style="width:150px;" min="1" placeholder="Days" class="form-control" />
					  </div>
					</div>
	
					<div class="form-group">
					  <div class="col-md-3"><label>Product:</label></div>
					  <div class="col-md-9"><select name="selProduct" id="selProduct" class="form-control">
							<option value="">--Select Product--</option>
							<?php foreach($ArrProducts as $product) { ?>
							<option value="<?php echo $product['product_id']; ?>" <?php if($product['product_id']==$clientgroup[0]['product_id']) { echo 'selected'; } ?>><?php echo $product['product_name']; ?></option>
							<?php } ?>
						</select>						
							<input type="hidden" name="oldselProduct" id="oldselProduct" value="<?php //echo $clientgroup[0]['product_id']; ?>" />
					  </div>		
					</div>
					
					
					
					
	

                    <div class="form-group">
						<div class="col-md-3">
							<label>Is Active : </label> 
						</div>
						<div class="col-md-9">
							<?php if(!empty ( $edit_id ) && $edit_id>0 ){ ?>
							<div class="add_promotion_radios">
							<div class="radio_left_status">
							<input type="radio" name="radio1" id="radio1" value="Y" <?php if( $ArrFieldData['is_active'] == 'Y' ) {?> checked="checked" <?php }?>>
							<label for="radio1"> Yes</label>
							</div>								
							<div class="radio_left_status">
							<input type="radio" name="radio1" id="radio2" value="N" <?php if( $ArrFieldData['is_active'] == 'N' ) {?> checked="checked" <?php }?>>
							<label for="radio2"> No </label>
							</div>	
							</div>
							<?php }else { ?>
						
							<div class="add_promotion_radios">
							<div class="radio_left_status">
							<input type="radio" name="radio1" id="radio1" value="Y" checked="checked" >
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
                              <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0)?$edit_id:''; ?>" name="promotional_code_id">
                              <button type="submit" class="btn btn-default" name="submit" id="submit" value="<?php echo (isset($edit_id) && $edit_id > 0)?'Update':'Add'; ?>" <?php echo 'name="save_user"';?> ><?php echo (isset($edit_id) && $edit_id > 0)?'Update':'Add'; ?></button>
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