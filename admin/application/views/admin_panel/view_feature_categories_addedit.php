<div class="row">

   <div class="col-md-12">

      <div class="box box-primary">

         <div class="box1">

            <div class="box-info">

               <!-- DEFAULT LOADER -->

               <div class="box-text box-body">

                  <?php

                  if (isset($edit_id) && $edit_id > 0) {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'fcategory_form', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('fcategories-update/' . $edit_id, $attributes);

                  } else {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'fcategory_form', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('fcategories-add', $attributes);

                  }

                  ?>

                  <div class="box-body">



                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Category Name <span class="red">*</span>: </label>

                        </div>

                        <div class="col-md-9">

                           <input type="text" placeholder="Name" class="form-control" name="cat_name" value="<?php

                           echo (isset($edit_id) && $edit_id > 0) ? $brand['cat_name'] : ''; ?>" required>

                           <?php echo form_error('cat_name'); ?>

                        </div>

                     </div>
                     



                     <div class="form-group">

                        <div class="col-md-3">

                            <label>Category Link <span class="red">*</span>: </label>

                            </div>

                            <div class="col-md-9">

                            <input type="text" placeholder="Link" class="form-control" name="cat_link" value="<?php

                            echo (isset($edit_id) && $edit_id > 0) ? $brand['cat_link'] : ''; ?>" required>

                            <?php echo form_error('cat_link'); ?>

                            </div>

                        </div>


                  </div>











                  <div class="form-group">

                     <div class="col-md-3">

                        <label>Category Image : </label>

                     </div>

                     <div class="col-md-6">

                        <?php if (isset($brand) && $brand['cat_image'] != '') { ?>



                           <div class="col-md-3">

                              <img height="25px" width="25px"

                                 src='<?php echo base_url(); ?>uploads/feature_categories/<?php echo $brand['cat_image'] ?>' border=0>

                           </div>

                           <div class="col-md-6">

                              <input type="file" class="form-control" name="cat_image">

                           </div>

                           <input type="hidden" value="<?php echo $brand['cat_image'] ?>" name="cat_image">

                        <?php } else { ?>

                           <input type="file" class="form-control" name="cat_image">

                        <?php } ?>

                        <?php echo form_error('cat_image'); ?>

                     </div>

                  </div>
                  
                  <div class="form-group">

                     <div class="col-md-3">

                        <label>Is Active: </label>

                     </div>

                     <div class="radio col-md-9 radio_btn_design_nomal">

                        <?php if (isset($brand['is_active']) && $edit_id > 0) { ?>

                           <label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($brand['is_active']) && $brand['is_active'] == "1") ? 'checked' : ''; ?>>Yes</label>

                           <label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($brand['is_active']) && $brand['is_active'] == "0") ? 'checked' : ''; ?>>No</label>

                        <?php } else { ?>

                           <label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>

                           <label><input type="radio" name="is_active" id="is_active2" value="0">No</label>

                        <?php } ?>

                     </div>



                  </div>





                  <div class="form-group">

                     <div class="col-md-2 col-md-offset-3">

                        <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>"

                           name="feturecat_id">

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