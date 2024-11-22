<div class="row">
   <div class="col-md-12">
      <div class="box box-primary">
         <div class="box1">
            <div class="box-info">
               <!-- DEFAULT LOADER -->
               <div class="box-text box-body">
                  <?php
                  if (isset($edit_id) && $edit_id > 0) {
                     $attributes = array('class' => 'form-horizontal', 'id' => 'banner_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');
                     echo form_open('banner-update/' . $edit_id, $attributes);
                  } else {
                     $attributes = array('class' => 'form-horizontal', 'id' => 'banner_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');
                     echo form_open('banner-add', $attributes);
                  }
                  ?>
                  <div class="box-body">

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Banner Name <span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Name" class="form-control" name="banner_text" value="<?php
                           echo (isset($edit_id) && $edit_id > 0) ? $banner['banner_text'] : ''; ?>" required>
                           <?php echo form_error('banner_text'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Banner URL: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Banner URL" class="form-control" name="banner_link"
                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['banner_link'] : ''; ?>">
                           <?php echo form_error('banner_link'); ?>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Description: </label>
                        </div>
                        <div class="col-md-9">
                           <!-- selectpicker -->
                           <textarea placeholder="Description" rows="3" id="editor1" class="form-control"
                              name="description"><?php echo (isset($edit_id) && $edit_id > 0) ? $banner['description'] : ''; ?></textarea>
                           <?php echo form_error('description'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Display Order<span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Display Order" class="form-control only_number"
                              name="banner_srno"
                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['banner_srno'] : ''; ?>"
                              required>
                           <?php echo form_error('banner_srno'); ?>
                        </div>

                     </div>






                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Banner Image : </label>
                        </div>
                        <div class="col-md-6">
                           <?php if (isset($banner) && $banner['banner_image'] != '') { ?>

                              <div class="col-md-3">
                                 <img height="25px" width="25px"
                                    src='<?php echo base_url(); ?>uploads/banner/<?php echo $banner['banner_image'] ?>'
                                    border=0>
                              </div>
                              <div class="col-md-6">
                                 <input type="file" class="form-control" name="banner_image">
                              </div>
                              <input type="hidden" value="<?php echo $banner['banner_image'] ?>" name="cat_image">
                           <?php } else { ?>
                              <input type="file" class="form-control" name="banner_image">
                           <?php } ?>
                           <?php echo form_error('banner_image'); ?>
                        </div>
                     </div>


                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Banner Type: </label>
                        </div>
                        <div class="radio col-md-9 radio_btn_design_nomal">
                           <?php if (isset($banner['banner_type']) && $edit_id > 0) { ?>
                              <label><input type="radio" name="banner_type" id="banner_type1" value="slider" <?php echo (isset($banner['banner_type']) && $banner['banner_type'] == "slider") ? 'checked' : ''; ?>>Slider</label>
                              <label><input type="radio" name="banner_type" id="banner_type2" value="bottom" <?php echo (isset($banner['banner_type']) && $banner['banner_type'] == "bottom") ? 'checked' : ''; ?>>Bottom</label>
                              <label><input type="radio" name="banner_type" id="banner_type3" value="side_banner" <?php echo (isset($banner['banner_type']) && $banner['banner_type'] == "side_banner") ? 'checked' : ''; ?>>Side Banner</label>
                           <?php } else { ?>
                              <label><input type="radio" name="banner_type" id="banner_type1" value="slider"
                                    checked>Slider</label>
                              <label><input type="radio" name="banner_type" id="banner_type2" value="bottom">Bottom</label>
                              <label><input type="radio" name="banner_type" id="banner_type3" value="side_banner">Side
                                 Banner</label>
                           <?php } ?>
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