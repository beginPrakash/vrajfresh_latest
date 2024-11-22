<div class="row">
   <div class="col-md-12">
      <div class="box box-primary">
         <div class="box1">
            <div class="box-info">
               <!-- DEFAULT LOADER -->
               <div class="box-text box-body">
                  <?php
                  if (isset($edit_id) && $edit_id > 0) {
                     $attributes = array('class' => 'form-horizontal', 'id' => 'testimonial_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');
                     echo form_open('testimonial-update/' . $edit_id, $attributes);
                  } else {
                     $attributes = array('class' => 'form-horizontal', 'id' => 'testimonial_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');
                     echo form_open('testimonial-add', $attributes);
                  }
                  ?>
                  <div class="box-body">

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Customer Name <span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Name" class="form-control" name="customer_name" value="<?php
                           echo (isset($edit_id) && $edit_id > 0) ? $testimonial['customer_name'] : ''; ?>" required>
                           <?php echo form_error('customer_name'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Sub Title: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Sub Title" class="form-control" name="customer_subname"
                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $testimonial['customer_subname'] : ''; ?>">
                           <?php echo form_error('customer_subname'); ?>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Description:<span class="red">*</span> </label>
                        </div>
                        <div class="col-md-9">
                           <!-- selectpicker -->
                           <textarea placeholder="Description" rows="3" id="editor1" class="form-control"
                              name="description"><?php echo (isset($edit_id) && $edit_id > 0) ? $testimonial['description'] : ''; ?></textarea>
                           <?php echo form_error('description'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Display Order<span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Display Order" class="form-control only_number"
                              name="display_order"
                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $testimonial['display_order'] : ''; ?>"
                              required>
                           <?php echo form_error('display_order'); ?>
                        </div>

                     </div>






                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Customer Photo : </label>
                        </div>
                        <div class="col-md-6">
                           <?php if (isset($testimonial) && $testimonial['testimonial_image'] != '') { ?>

                              <div class="col-md-3">
                                 <img height="25px" width="25px"
                                    src='<?php echo base_url(); ?>uploads/testimonial/<?php echo $testimonial['testimonial_image'] ?>'
                                    border=0>
                              </div>
                              <div class="col-md-6">
                                 <input type="file" class="form-control" name="testimonial_image">
                              </div>
                              <input type="hidden" value="<?php echo $testimonial['testimonial_image'] ?>"
                                 name="cat_image">
                           <?php } else { ?>
                              <input type="file" class="form-control" name="testimonial_image">
                           <?php } ?>
                           <?php echo form_error('testimonial_image'); ?>
                        </div>
                     </div>


                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Is Active: </label>
                        </div>
                        <div class="radio col-md-9 radio_btn_design_nomal">
                           <?php if (isset($testimonial['is_active']) && $edit_id > 0) { ?>
                              <label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($testimonial['is_active']) && $testimonial['is_active'] == "1") ? 'checked' : ''; ?>>Yes</label>
                              <label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($testimonial['is_active']) && $testimonial['is_active'] == "0") ? 'checked' : ''; ?>>No</label>
                           <?php } else { ?>
                              <label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>
                              <label><input type="radio" name="is_active" id="is_active2" value="0">No</label>
                           <?php } ?>
                        </div>

                     </div>
                     <div class="form-group">
                        <div class="col-md-2 col-md-offset-3">
                           <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>"
                              name="testimonial_id">
                           <button type="submit" class="btn btn-default" name="submit" id="submit"
                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?>" <?php echo 'name="save_user"'; ?>><?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?></button>
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