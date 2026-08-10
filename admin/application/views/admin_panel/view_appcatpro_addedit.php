<div class="row">

   <div class="col-md-12">

      <div class="box box-primary">

         <div class="box1">

            <div class="box-info">

               <!-- DEFAULT LOADER -->

               <div class="box-text box-body">

                  <?php
$Arrcategory_id = '';
                  if (isset($edit_id) && $edit_id > 0) {

                  $Arrcategory_id = $banner['category_id'] ?? '';

                     $attributes = array('class' => 'form-horizontal', 'id' => 'homebanner_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('appbannercategory_product-update/' . $edit_id, $attributes);

                  } else {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'homebanner_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('appbannercategory_product-add', $attributes);

                  }

                  $options_category = array();
                     if (isset($bannercat) && count($bannercat) > 0)
                        foreach ($bannercat as $row) {
                           $options_category[$row['banner_id']] = $row['category_name'];
                        }

                  ?>

                  <div class="box-body">

                  <div class="form-group">

                        <div class="col-md-3">

                           <label>Product Name: </label>

                        </div>

                        <div class="col-md-9">

                           <input type="text" placeholder="Product Name" class="form-control" name="product_name"

                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['product_name'] : ''; ?>">

                           <?php echo form_error('product_name'); ?>

                        </div>

                     </div>


                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Product Slug: </label>

                        </div>

                        <div class="col-md-9">

                           <input type="text" placeholder="Product Slug" class="form-control" name="product_link"

                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['product_link'] : ''; ?>">

                           <?php echo form_error('product_link'); ?>

                        </div>

                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Product Category: </label>

                        </div>

                       <div class="col-md-9">
                              <div>
                                 <?php
                                 $attributes = array('id' => 'category_id', 'class' => 'form-control');
                                 echo form_dropdown('category_id', $options_category, $Arrcategory_id, $attributes);
                                 ?>
                                 <label for="category_iderror" id="category_iderror" class="error"></label>
                              </div>
                           </div>

                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Product Url Category: </label>

                        </div>

                        <div class="col-md-9">


                           <select name="type" class="form-control select_nocler">

										<option value="">Select Product Url Category</option>

										<option value="category" <?php echo (@$banner['type'] == "category") ? 'Selected' : '' ?>>Category</option>

										<option value="special-category" <?php echo (@$banner['type'] == "special-category") ? 'Selected' : '' ?>>Special Category</option>

										<option value="brand" <?php echo (@$banner['type'] == "brand") ? 'Selected' : '' ?>>Brand</option>

									</select>

                              <?php echo form_error('type'); ?>

                        </div>

                     </div>


                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Display Order<span class="red">*</span>: </label>

                        </div>

                        <div class="col-md-9">

                           <input type="text" placeholder="Display Order" class="form-control only_number"

                              name="product_srno"

                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['product_srno'] : ''; ?>"

                              required>

                           <?php echo form_error('product_srno'); ?>

                        </div>



                     </div>

                     
                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Product Image : </label>

                        </div>

                        <div class="col-md-6">

                           <?php if (isset($banner) && $banner['product_image'] != '') { ?>



                              <div class="col-md-3">
                     
                                    <img height="25px" width="25px"

                                       src='<?php echo base_url(); ?>uploads/bannerdata/<?php echo $banner['product_image'] ?>'

                                       border=0>
                                

                              </div>

                              <div class="col-md-6">

                                 <input type="file" class="form-control" name="product_image" id="product_image" accept=".jpg,.jpeg,.png">

                              </div>

                              <input type="hidden" value="<?php echo $banner['product_image'] ?>" name="cat_image">

                           <?php } else { ?>

                              <input type="file" class="form-control" name="product_image" id="product_image" accept=".jpg,.jpeg,.png" required>

                           <?php } ?>

                           <?php echo form_error('product_image'); ?>

                        </div>

                     </div>




                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Is Active: </label>

                        </div>

                        <div class="radio col-md-9 radio_btn_design_nomal">

                           <?php if (isset($banner['is_active']) && $edit_id > 0) { ?>

                              <label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($banner['is_active']) && $banner['is_active'] == "1") ? 'checked' : ''; ?>>Yes</label>

                              <label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($banner['is_active']) && $banner['is_active'] == "0") ? 'checked' : ''; ?>>No</label>

                           <?php } else { ?>

                              <label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>

                              <label><input type="radio" name="is_active" id="is_active2" value="0">No</label>

                           <?php } ?>

                        </div>



                     </div>



                     <div class="form-group">

                        <div class="col-md-2 col-md-offset-3">

                           <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>"

                              name="banner_id">

                           <button type="submit" class="btn btn-default" name="submit" id="submit"

                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?>" <?php echo 'name="save_user"'; ?>><?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?></button>

                           <button type="reset" value="Reset" class="btn btn-info">Cancel</button>



                        </div>

                     </div>

                  </div>

               </div>

               </form>

               <!-- box-body -->

            </div>

         </div>

      </div>

   </div>

</div>



</div>
<script type="text/javascript">
   $(document).on('change','.banner_type',function(){
      var type = $(this).val();
      if(type == 'video'){
         $('#product_image').attr('accept','.mp4');
         $('#banner_mob_image').attr('accept','.mp4');
      }else{
         $('#product_image').attr('accept','.jpg,.jpeg,.png');
         $('#banner_mob_image').attr('accept','.jpg,.jpeg,.png');
      }
   });
   var type = $('.banner_type:checked').val();
      if(type == 'video'){
         $('#product_image').attr('accept','.mp4');
         $('#banner_mob_image').attr('accept','.mp4');
      }else{
         $('#product_image').attr('accept','.jpg,.jpeg,.png');
         $('#banner_mob_image').attr('accept','.jpg,.jpeg,.png');
      }
</script>