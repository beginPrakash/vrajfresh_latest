<div class="row">
   <div class="col-md-12">
      <div class="box box-primary">
         <div class="box1">
            <div class="box-info">
               <!-- DEFAULT LOADER -->
               <div class="box-text box-body">
                  <?php
                  if (isset($edit_id) && $edit_id > 0) {
                     $attributes = array('class' => 'form-horizontal', 'id' => 'homep_slider_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');
                     echo form_open('homep_slider-update/' . $edit_id, $attributes);
                  } else {
                     $attributes = array('class' => 'form-horizontal', 'id' => 'homep_slider_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');
                     echo form_open('homep_slider-add', $attributes);
                  }
                  ?>
                  <div class="box-body">

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Title <span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Title" class="form-control" name="title" value="<?php
                           echo (isset($edit_id) && $edit_id > 0) ? $banner['title'] : ''; ?>" required>
                           <?php echo form_error('title'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Slug<span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Slug" class="form-control" name="slug"
                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['slug'] : ''; ?>" required>
                           <?php echo form_error('slug'); ?>
                        </div>
                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Display Order<span class="red">*</span>: </label>
                        </div>
                        <div class="col-md-9">
                           <input type="text" placeholder="Display Order" class="form-control only_number"
                              name="display_order"
                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['display_order'] : ''; ?>"
                              required>
                           <?php echo form_error('display_order'); ?>
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