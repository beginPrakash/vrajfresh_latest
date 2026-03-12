<div class="row">

   <div class="col-md-12">

      <div class="box box-primary">

         <div class="box1">

            <div class="box-info">

               <!-- DEFAULT LOADER -->

               <div class="box-text box-body">

                  <?php

                  if (isset($edit_id) && $edit_id > 0) {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'advertistop_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('advertises-update/' . $edit_id, $attributes);

                  } else {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'advertistop_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('advertises-add', $attributes);

                  }

                  ?>

                  <div class="box-body">


                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Advertise URL: </label>

                        </div>

                        <div class="col-md-9">

                           <input type="text" placeholder="Advertise URL" class="form-control" name="adv_link"

                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['adv_link'] : ''; ?>">

                           <?php echo form_error('adv_link'); ?>

                        </div>

                     </div>

                     <div class="form-group">
                        <div class="col-md-3">
                           <label>Categoty : </label>
                        </div>
                        <div class="col-md-9">
                           <select name="category_id" id="category_id" class="form-control">
                              <option value=""> -- Select Category -- </option>
                              <?php if(!empty($category)){
                                 for($i = 0; $i < count($category); $i++){ 
                                    $select = "";
                                    if (isset($banner) && $banner['category_id'] > 0) {
                                       if($banner['category_id'] == $category[$i]->category_id){
                                          $select = "selected";
                                       }
                                    }
                                    ?>
                                    <option <?php echo $select; ?> value="<?php echo $category[$i]->category_id; ?>"><?php echo $category[$i]->category_name; ?></option>
                              <?php $select = ""; } } ?>
                           </select>
                           <?php echo form_error('category_id'); ?>
                        </div>
                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Display Order<span class="red">*</span>: </label>

                        </div>

                        <div class="col-md-9">

                           <input type="text" placeholder="Display Order" class="form-control only_number"

                              name="adv_srno"

                              value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['adv_srno'] : ''; ?>"

                              required>

                           <?php echo form_error('adv_srno'); ?>

                        </div>



                     </div>













                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Banner Image : </label>

                        </div>

                        <div class="col-md-6">

                           <?php if (isset($banner) && $banner['adv_image'] != '') { ?>



                              <div class="col-md-3">

                                 <img height="25px" width="25px"

                                    src='<?php echo base_url(); ?>uploads/advertise/<?php echo $banner['adv_image'] ?>'

                                    border=0>

                              </div>

                              <div class="col-md-6">

                                 <input type="file" class="form-control" name="adv_image">

                              </div>

                              <input type="hidden" value="<?php echo $banner['adv_image'] ?>" name="cat_image">

                           <?php } else { ?>

                              <input type="file" class="form-control" name="adv_image">

                           <?php } ?>

                           <?php echo form_error('adv_image'); ?>

                        </div>

                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                        <label>Mobile Image : </label>

                        </div>

                        <div class="col-md-6">

                        <?php if (isset($banner) && $banner['adv_mob_image'] != '') { ?>



                            <div class="col-md-3">

                                <img height="25px" width="25px"

                                    src='<?php echo base_url(); ?>uploads/advertise/<?php echo $banner['adv_mob_image'] ?>'

                                    border=0>

                            </div>

                            <div class="col-md-6">

                                <input type="file" class="form-control" name="adv_mob_image">

                            </div>

                            <input type="hidden" value="<?php echo $banner['adv_mob_image'] ?>" name="mob_cat_image">

                        <?php } else { ?>

                            <input type="file" class="form-control" name="adv_mob_image">

                        <?php } ?>

                        <?php echo form_error('adv_mob_image'); ?>

                        </div>  

                     </div>

                     <div class="form-group">

                        <div class="col-md-3">

                        <label>Mobile App Image : </label>

                        </div>

                        <div class="col-md-6">

                        <?php if (isset($banner) && $banner['adv_mobapp_image'] != '') { ?>



                            <div class="col-md-3">

                                <img height="25px" width="25px"

                                    src='<?php echo base_url(); ?>uploads/advertise/<?php echo $banner['adv_mobapp_image'] ?>'

                                    border=0>

                            </div>

                            <div class="col-md-6">

                                <input type="file" class="form-control" name="adv_mobapp_image">

                            </div>

                            <input type="hidden" value="<?php echo $banner['adv_mobapp_image'] ?>" name="mobapp_cat_image">

                        <?php } else { ?>

                            <input type="file" class="form-control" name="adv_mobapp_image">

                        <?php } ?>

                        <?php echo form_error('adv_mobapp_image'); ?>

                        </div>  

                     </div>



                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Advertise Type: </label>

                        </div>

                        <div class="radio col-md-9 radio_btn_design_nomal">

                           <?php if (isset($banner['adv_type']) && $edit_id > 0) { ?>

                              <label><input type="radio" name="adv_type" id="adv_type1" value="top" <?php echo (isset($banner['adv_type']) && $banner['adv_type'] == "top") ? 'checked' : ''; ?>>Top</label>

                              <label><input type="radio" name="adv_type" id="adv_type2" value="bottom" <?php echo (isset($banner['adv_type']) && $banner['adv_type'] == "bottom") ? 'checked' : ''; ?>>Bottom</label>

                               <?php } else { ?>

                              <label><input type="radio" name="adv_type" id="adv_type1" value="top"

                                    checked>Top</label>

                              <label><input type="radio" name="adv_type" id="adv_type2" value="bottom">Bottom</label>


                           <?php } ?>

                        </div>



                     </div>
                     
                     <?php 
                        $divstyle = "display:none;";
                        if((isset($banner['adv_type']) && $banner['adv_type'] == "bottom") && $edit_id > 0){
                           $divstyle = "display:block;";
                        }
                        ?>
                     <div id="bottomOption" style="<?php echo $divstyle; ?>">
                        <div class="form-group">

                           <div class="col-md-3">

                              <label>Alternate Advertise URL: </label>

                           </div>

                           <div class="col-md-9">

                              <input type="text" placeholder="Alternate Advertise URL" class="form-control" id="alt_adv_link" name="alt_adv_link"

                                 value="<?php echo (isset($edit_id) && $edit_id > 0) ? $banner['alt_adv_link'] : ''; ?>">

                              <?php echo form_error('alt_adv_link'); ?>

                           </div>

                        </div>
                        
                        <div class="form-group">
                           <div class="col-md-3">
                              <label>Alternate Banner Image : </label>
                           </div>
                           <div class="col-md-6">
                              <?php if (isset($banner) && $banner['alt_adv_image'] != '') { ?>
                                 <div class="col-md-3">
                                    <img height="25px" width="25px" src='<?php echo base_url(); ?>uploads/advertise/<?php echo $banner['alt_adv_image'] ?>' border=0>
                                 </div>
                                 <div class="col-md-6">
                                    <input type="file" class="form-control" name="alt_adv_image">
                                 </div>
                                 <input type="hidden" value="<?php echo $banner['alt_adv_image'] ?>" name="cat_alt_image">
                              <?php } else { ?>
                                 <input type="file" class="form-control" name="alt_adv_image">
                              <?php } ?>
                              <?php echo form_error('alt_adv_image'); ?>
                           </div>
                        </div>
                        <div class="form-group">
                           <div class="col-md-3">
                              <label>Alternate Mobile Image : </label>
                           </div>
                           <div class="col-md-6">
                              <?php if (isset($banner) && $banner['alt_adv_mob_image'] != '') { ?>
                              <div class="col-md-3">
                                 <img height="25px" width="25px" src='<?php echo base_url(); ?>uploads/advertise/<?php echo $banner['alt_adv_mob_image'] ?>' border=0>
                              </div>
                              <div class="col-md-6">
                                 <input type="file" class="form-control" name="alt_adv_mob_image">
                              </div>
                              <input type="hidden" value="<?php echo $banner['alt_adv_mob_image'] ?>" name="cat_alt_m_image">
                              <?php } else { ?>
                                 <input type="file" class="form-control" name="alt_adv_mob_image">
                              <?php } ?>
                              <?php echo form_error('alt_adv_mob_image'); ?>
                           </div>  
                        </div>

                        <div class="form-group">
                           <div class="col-md-3">
                              <label>Alternate Mobile App Image : </label>
                           </div>
                           <div class="col-md-6">
                              <?php if (isset($banner) && $banner['alt_adv_mobapp_image'] != '') { ?>
                              <div class="col-md-3">
                                 <img height="25px" width="25px" src='<?php echo base_url(); ?>uploads/advertise/<?php echo $banner['alt_adv_mobapp_image'] ?>' border=0>
                              </div>
                              <div class="col-md-6">
                                 <input type="file" class="form-control" name="alt_adv_mobapp_image">
                              </div>
                              <input type="hidden" value="<?php echo $banner['alt_adv_mobapp_image'] ?>" name="cat_alt_mapp_image">
                              <?php } else { ?>
                                 <input type="file" class="form-control" name="alt_adv_mobapp_image">
                              <?php } ?>
                              <?php echo form_error('alt_adv_mobapp_image'); ?>
                           </div>  
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

                              name="adv_id">

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

<script>
   $(document).ready(function(){  
      $('input[name=adv_type]').change(function(){
         if($(this).val() == "bottom"){
            $("#bottomOption").show();
         } else {
            $('input[name=alt_adv_image]').val('');
            $('input[name=alt_adv_mob_image]').val('');
            $('#alt_adv_link').val('');
            $("#bottomOption").hide();
         }
      });
   });
</script>