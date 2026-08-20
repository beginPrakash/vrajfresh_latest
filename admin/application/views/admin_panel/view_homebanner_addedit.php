<div class="row">

   <div class="col-md-12">

      <div class="box box-primary">

         <div class="box1">

            <div class="box-info">

               <!-- DEFAULT LOADER -->

               <div class="box-text box-body">

                  <?php

                  if (isset($edit_id) && $edit_id > 0) {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'homebanner_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('homebanners-update/' . $edit_id, $attributes);

                  } else {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'homebanner_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('homebanners-add', $attributes);

                  }

                  ?>

                  <div class="box-body">


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

                           <label>Banner Category: </label>

                        </div>

                        <div class="col-md-9">


                           <select name="banner_category" class="form-control select_nocler">

										<option value="">Select Banner Category</option>

										<option value="category" <?php echo (@$banner['banner_category'] == "category") ? 'Selected' : '' ?>>Category</option>

										<option value="special-category" <?php echo (@$banner['banner_category'] == "special-category") ? 'Selected' : '' ?>>Special Category</option>

										<option value="brand" <?php echo (@$banner['banner_category'] == "brand") ? 'Selected' : '' ?>>Brand</option>

									</select>

                              <?php echo form_error('banner_category'); ?>

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

                           <label>Banner Type: </label>

                        </div>

                        <div class="radio col-md-9 radio_btn_design_nomal">

                           <?php if (isset($banner['banner_type']) && $edit_id > 0) { ?>

                              <label><input class="banner_type" type="radio" name="banner_type" id="banner_type1" value="image" <?php echo (isset($banner['banner_type']) && $banner['banner_type'] == "image") ? 'checked' : ''; ?>>Image</label>

                              <label><input class="banner_type" type="radio" name="banner_type" id="banner_type2" value="video" <?php echo (isset($banner['banner_type']) && $banner['banner_type'] == "video") ? 'checked' : ''; ?>>Video</label>

                           <?php } else { ?>

                              <label><input class="banner_type" type="radio" name="banner_type" id="banner_type1" value="image" checked>Image</label>

                              <label><input class="banner_type" type="radio" name="banner_type" id="banner_type2" value="video">Video</label>

                           <?php } ?>

                        </div>



                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Upload Desktop File : </label>

                        </div>

                        <div class="col-md-6">

                           <?php if (isset($banner) && $banner['banner_image'] != '') { ?>



                              <div class="col-md-3">
                                 <?php if($banner['banner_type'] == 'image') { ?>
                                    <img height="25px" width="25px"

                                       src='<?php echo base_url(); ?>uploads/home_banner/<?php echo $banner['banner_image'] ?>'

                                       border=0>
                                 <?php }else{ ?>
                                    <video width="100" height="100" controls autoplay>
                                       <source src="<?php echo base_url(); ?>uploads/home_banner/<?php echo $banner['banner_image'] ?>" type="video/mp4">
                                    </video>
                                 <?php } ?>

                              </div>

                              <div class="col-md-6">

                                 <input type="file" class="form-control" name="banner_image" id="banner_image" accept=".jpg,.jpeg,.png">

                              </div>

                              <input type="hidden" value="<?php echo $banner['banner_image'] ?>" name="cat_image">

                           <?php } else { ?>

                              <input type="file" class="form-control" name="banner_image" id="banner_image" accept=".jpg,.jpeg,.png" required>

                           <?php } ?>

                           <?php echo form_error('banner_image'); ?>

                        </div>

                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                        <label>Upload Mobile File : </label>

                        </div>

                        <div class="col-md-6">

                        <?php if (isset($banner) && $banner['banner_mob_image'] != '') { ?>



                            <div class="col-md-3">

                                 <?php if($banner['banner_type'] == 'image') { ?>
                                    <img height="25px" width="25px"

                                    src='<?php echo base_url(); ?>uploads/home_banner/<?php echo $banner['banner_mob_image'] ?>'

                                    border=0>
                                 <?php }else{ ?>
                                    <video width="100" height="100" controls autoplay>
                                       <source src="<?php echo base_url(); ?>uploads/home_banner/<?php echo $banner['banner_mob_image'] ?>" type="video/mp4">
                                    </video>
                                 <?php } ?>
                                

                            </div>

                            <div class="col-md-6">

                                <input type="file" class="form-control" name="banner_mob_image" id="banner_mob_image" accept=".jpg,.jpeg,.png">

                            </div>

                            <input type="hidden" value="<?php echo $banner['banner_mob_image'] ?>" name="mob_cat_image">

                        <?php } else { ?>

                            <input type="file" class="form-control" name="banner_mob_image" id="banner_mob_image" accept=".jpg,.jpeg,.png" required>

                        <?php } ?>

                        <?php echo form_error('banner_mob_image'); ?>

                        </div>

                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                        <label>Upload Mobile APP File : </label>

                        </div>

                        <div class="col-md-6">

                        <?php if (isset($banner) && $banner['banner_mobapp_image'] != '') { ?>



                            <div class="col-md-3">

                                 <?php if($banner['banner_type'] == 'image') { ?>
                                    <img height="25px" width="25px"

                                    src='<?php echo base_url(); ?>uploads/home_banner/<?php echo $banner['banner_mobapp_image'] ?>'

                                    border=0>
                                 <?php }else{ ?>
                                    <video width="100" height="100" controls autoplay>
                                       <source src="<?php echo base_url(); ?>uploads/home_banner/<?php echo $banner['banner_mobapp_image'] ?>" type="video/mp4">
                                    </video>
                                 <?php } ?>
                                

                            </div>

                            <div class="col-md-6">

                                <input type="file" class="form-control" name="banner_mobapp_image" id="banner_mobapp_image" accept=".jpg,.jpeg,.png">

                            </div>

                            <input type="hidden" value="<?php echo $banner['banner_mobapp_image'] ?>" name="mobapp_cat_image">

                        <?php } else { ?>

                            <input type="file" class="form-control" name="banner_mobapp_image" id="banner_mobapp_image" accept=".jpg,.jpeg,.png" required>

                        <?php } ?>

                        <?php echo form_error('banner_mobapp_image'); ?>

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
         $('#banner_image').attr('accept','.mp4');
         $('#banner_mob_image').attr('accept','.mp4');
      }else{
         $('#banner_image').attr('accept','.jpg,.jpeg,.png');
         $('#banner_mob_image').attr('accept','.jpg,.jpeg,.png');
      }
   });
   var type = $('.banner_type:checked').val();
      if(type == 'video'){
         $('#banner_image').attr('accept','.mp4');
         $('#banner_mob_image').attr('accept','.mp4');
      }else{
         $('#banner_image').attr('accept','.jpg,.jpeg,.png');
         $('#banner_mob_image').attr('accept','.jpg,.jpeg,.png');
      }
</script>