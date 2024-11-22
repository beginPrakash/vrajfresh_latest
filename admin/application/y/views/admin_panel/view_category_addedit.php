<div class="row">
   <div class="col-md-12">
      <div class="box box-primary">
         <div class="box1" >
            <div class="box-info">
               <!-- DEFAULT LOADER --> 
               <div class="box-text box-body">
                  <?php
                     if( isset($edit_id) && $edit_id > 0) {
                        $attributes = array('class' => 'form-horizontal', 'id' => 'category__form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                        echo form_open('category-update/'.$edit_id, $attributes);
                     }else{
                        $attributes = array('class' => 'form-horizontal', 'id' => 'category__form', 'role' => 'form', 'enctype' => 'multipart/form-data');
                        echo form_open('category-add', $attributes);
                     }
                     ?>
                  <div class="box-body">
                     <div class="form-group">
                        <div class="col-md-3"> 
                           <label>Parent Category <span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <select name="parent_category_id" id="parent_category_id" class="selectpicker form-control" required  data-show-subtext="true" data-live-search="true">
                              <option value="0">Select Parent Category</option>
                              <?php
                                 $category_id = !empty( $edit_id ) ? $category['parent_category_id'] : '';
                                 
                                 if($ArrParentCategory != '')
                                 {
                                 	foreach ($ArrParentCategory as $row)
                                 	{
                                 		$selected = "";
                                 		if( $category_id == $row["category_id"] )
                                 		{
                                 			$selected = "selected";
                                 		}
                                 	
                                 		if ( !empty ( $row['category_name'] ) )
                                 			echo '<option value='.$row["category_id"].' '.$selected.'>'.$row["category_name"].'</option>';
                                 	}
                                 //form validation
                                 } ?>
                           </select>
                           <?php echo form_error('parent_category_id'); ?>
                           <p id="parent_category_iderror"></p>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Category Name <span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Name" class="form-control" name="category_name" value="<?php 
                           echo (isset($edit_id) && $edit_id > 0)?$category['category_name']:''; ?>" required>
                           <?php echo form_error('category_name'); ?>
                        </div>
                     </div>
                    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Long Description: </label>
                        </div>
                        <div class="col-md-9">
                           <!-- selectpicker -->
                           <textarea placeholder="Long Description" rows="3" id="editor1" class="form-control" name="category_description"><?php echo (isset($edit_id) && $edit_id > 0)? $category['category_description']:''; ?></textarea>
                           <?php echo form_error('category_description'); ?>
                        </div>
                     </div>


              </div>
			  
			  
			  
                     
                     
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Category Image : </label>
                        </div>
                        <div class="col-md-6">
                        <?php if( isset($category) && $category['category_image'] != ''){ ?>
						
                        <div class="col-md-3">
                           <img height="25px" width="25px" src='<?php echo base_url();?>uploads/category/<?php echo $category['category_image'] ?>' border=0 >
                        </div>
                        <div class="col-md-6">
						<input type="file" class="form-control" name="category_image">
                        </div>
                           <input type="hidden" value="<?php echo $category['category_image'] ?>" name="cat_image">
                        <?php }else{ ?>
                           <input type="file" class="form-control" name="category_image">      
                        <?php } ?>
                           <?php echo "Recommended Size: ".CATEGORY_IMAGE_WIDTH." x ".CATEGORY_IMAGE_HEIGHT; ?>
                        <?php echo form_error('category_image'); ?>
                        </div>
                     </div>
    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Meta Title : </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Meta Title" class="form-control" name="meta_title" value="<?php echo (isset($edit_id) && $edit_id > 0)? $category['meta_title']:''; ?>">
                           <?php echo form_error('meta_title'); ?>
                        </div>
                     </div>
    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Meta Keyword : </label> 
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Meta Keyword" class="form-control" name="meta_keyword" value="<?php echo (isset($edit_id) && $edit_id > 0)? $category['meta_keyword']:''; ?>">
                           <?php echo form_error('meta_keyword'); ?>
                        </div>
                     </div>
    
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Meta Description : </label>
                        </div>
                        <div class="col-md-9">
                           <textarea placeholder="Meta Description" rows="3" class="form-control" name="meta_description"><?php echo (isset($edit_id) && $edit_id > 0)? $category['meta_description']:''; ?></textarea>
                           <?php echo form_error('meta_description'); ?>
                        </div>
                     </div>


                     
					<div class="form-group">
						<div class="col-md-3">
						<label>Is Display on Home?: </label>
						</div>
						<div class="radio col-md-9 radio_btn_design_nomal">
						<?php if(isset($category['is_home_display']) && $edit_id > 0 ){ ?>
						<label><input type="radio" name="is_home_display" id="is_home_display1" value="1" <?php echo (isset($category['is_home_display']) && $category['is_home_display'] == "1")?'checked':''; ?>>Yes</label>
						<label><input type="radio" name="is_home_display" id="is_home_display2" value="0" <?php echo (isset($category['is_home_display']) && $category['is_home_display'] == "0")?'checked':''; ?>>No</label>
						<?php }else{ ?>
						<label><input type="radio" name="is_home_display" id="is_home_display1" value="1" checked>Yes</label>
						<label><input type="radio" name="is_home_display" id="is_home_display2" value="0" >No</label>
						<?php }?>
						</div>
					</div>

               
               <div class="form-group">
                        <div class="col-md-3">
                           <label>Display Style : </label>
                        </div>
                        <div class="col-md-9">
                        <?php
                           $ArrStyleOptions = array('0'=>'Select Style','top'=>'top','top2'=>'top2');
                            $attributes = array('id' => 'style','class' => 'form-control');
                            echo form_dropdown('style', $ArrStyleOptions, $category['style'], $attributes);
                          ?>
                        </div>
                     </div>

               
					<div class="form-group">
						<div class="col-md-3">
						<label>Is Perishable Category?: </label>
						</div>
						<div class="radio col-md-9 radio_btn_design_nomal">
						<?php if(isset($category['is_perisible_products']) && $edit_id > 0 ){ ?>
						<label><input type="radio" name="is_perisible_products" id="is_perisible_products1" value="1" <?php echo (isset($category['is_perisible_products']) && $category['is_perisible_products'] == "1")?'checked':''; ?>>Yes</label>
						<label><input type="radio" name="is_perisible_products" id="is_perisible_products2" value="0" <?php echo (isset($category['is_perisible_products']) && $category['is_perisible_products'] == "0")?'checked':''; ?>>No</label>
						<?php }else{ ?>
						<label><input type="radio" name="is_perisible_products" id="is_perisible_products1" value="1" checked>Yes</label>
						<label><input type="radio" name="is_perisible_products" id="is_perisible_products2" value="0" >No</label>
						<?php }?>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-3">
						<label>Is Active: </label>
						</div>
						<div class="radio col-md-9 radio_btn_design_nomal">
						<?php if(isset($category['is_active']) && $edit_id > 0 ){ ?>
						<label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($category['is_active']) && $category['is_active'] == "1")?'checked':''; ?>>Yes</label>
						<label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($category['is_active']) && $category['is_active'] == "0")?'checked':''; ?>>No</label>
						<?php }else{ ?>
						<label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>
						<label><input type="radio" name="is_active" id="is_active2" value="0" >No</label>
						<?php }?>
						</div>
					</div>
					
					
                     <div class="form-group">
                        <div class="col-md-2 col-md-offset-3">
                              <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0)?$edit_id:''; ?>" name="category_id">
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