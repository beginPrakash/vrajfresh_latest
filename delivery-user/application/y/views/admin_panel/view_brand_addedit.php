<div class="row">
   <div class="col-md-12">
      <div class="box box-primary">
         <div class="box1" >
            <div class="box-info">
               <!-- DEFAULT LOADER --> 
               <div class="box-text box-body">
                  <?php
                     if( isset($edit_id) && $edit_id > 0) {
                        $attributes = array('class' => 'form-horizontal', 'id' => 'brand__form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                        echo form_open('brand-update/'.$edit_id, $attributes);
                     }else{
                        $attributes = array('class' => 'form-horizontal', 'id' => 'brand__form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                        echo form_open('brand-add', $attributes);
                     }
                     ?>
                  <div class="box-body">
                     
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Brand Name <span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Name" class="form-control" name="brand_name" value="<?php 
                           echo (isset($edit_id) && $edit_id > 0)?$brand['brand_name']:''; ?>" required>
                           <?php echo form_error('brand_name'); ?>
                        </div>
                     </div>
                    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Long Description: </label>
                        </div>
                        <div class="col-md-9">
                           <!-- selectpicker -->
                           <textarea placeholder="Long Description" rows="3" id="editor1" class="form-control" name="brand_description"><?php echo (isset($edit_id) && $edit_id > 0)? $brand['brand_description']:''; ?></textarea>
                           <?php echo form_error('brand_description'); ?>
                        </div>
                     </div>


              </div>
			  
			  
			  
                     
                     
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Brand Image : </label>
                        </div>
                        <div class="col-md-6">
                        <?php if( isset($brand) && $brand['brand_image'] != ''){ ?>
						
                        <div class="col-md-3">
                           <img height="25px" width="25px" src='<?php echo base_url();?>uploads/brand/<?php echo $brand['brand_image'] ?>' border=0 >
                        </div>
                        <div class="col-md-6">
						<input type="file" class="form-control" name="brand_image">
                        </div>
                           <input type="hidden" value="<?php echo $brand['brand_image'] ?>" name="cat_image">
                        <?php }else{ ?>
                           <input type="file" class="form-control" name="brand_image">      
                        <?php } ?>
                        <?php echo form_error('brand_image'); ?>
                        </div>
                     </div>
    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Meta Title : </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Meta Title" class="form-control" name="meta_title" value="<?php echo (isset($edit_id) && $edit_id > 0)? $brand['meta_title']:''; ?>">
                           <?php echo form_error('meta_title'); ?>
                        </div>
                     </div>
    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Meta Keyword : </label> 
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Meta Keyword" class="form-control" name="meta_keyword" value="<?php echo (isset($edit_id) && $edit_id > 0)? $brand['meta_keyword']:''; ?>">
                           <?php echo form_error('meta_keyword'); ?>
                        </div>
                     </div>
    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Meta Description : </label>
                        </div>
                        <div class="col-md-9">
                           <textarea placeholder="Meta Description" rows="3" class="form-control" name="meta_description"><?php echo (isset($edit_id) && $edit_id > 0)? $brand['meta_description']:''; ?></textarea>
                           <?php echo form_error('meta_description'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Is Home Display: </label>
                        </div>
              <div class="radio col-md-9 radio_btn_design_nomal">
                            <?php if(isset($brand['is_home_display']) && $edit_id > 0 ){ ?>
                     <label><input type="radio" name="is_home_display" id="is_home_display1" value="1" <?php echo (isset($brand['is_home_display']) && $brand['is_home_display'] == "1")?'checked':''; ?>>Yes</label>
                     <label><input type="radio" name="is_home_display" id="is_home_display2" value="0" <?php echo (isset($brand['is_home_display']) && $brand['is_home_display'] == "0")?'checked':''; ?>>No</label>
                 <?php }else{ ?>
                     <label><input type="radio" name="is_home_display" id="is_home_display1" value="1" checked>Yes</label>
                     <label><input type="radio" name="is_home_display" id="is_home_display2" value="0" >No</label>
                  <?php }?>
                        </div>
                        
                     </div>
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Is Active: </label>
                        </div>
              <div class="radio col-md-9 radio_btn_design_nomal">
                            <?php if(isset($brand['is_active']) && $edit_id > 0 ){ ?>
                     <label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($brand['is_active']) && $brand['is_active'] == "1")?'checked':''; ?>>Yes</label>
                     <label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($brand['is_active']) && $brand['is_active'] == "0")?'checked':''; ?>>No</label>
                 <?php }else{ ?>
                     <label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>
                     <label><input type="radio" name="is_active" id="is_active2" value="0" >No</label>
                  <?php }?>
                        </div>
                        
                     </div>
					 
					 
                     <div class="form-group">
                        <div class="col-md-2 col-md-offset-3">
                              <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0)?$edit_id:''; ?>" name="brand_id">
                              <button type="submit" class="btn btn-default" name="submit" id="submit" value="<?php echo (isset($edit_id) && $edit_id > 0)?'Update':'Add'; ?>" <?php echo 'name="save_user"';?> ><?php echo (isset($edit_id) && $edit_id > 0)?'Update':'Add'; ?></button>
                              <button type="reset" value="Reset" class="btn btn-info">Cancel</button>
                           
                        </div>
                     </div>
                  </div>
               </div>
               </form>
			   							  <!-- box-body --> 
				  <script>
					$(function () {
					  // Replace the <textarea id="editor1"> with a CKEditor
					  CKEDITOR.replace('editor1');
					  $(".textarea").wysihtml5();
					});
				  </script>

            </div>
         </div>
      </div>
   </div>
</div>

</div>