<div class="row">

   <div class="col-md-12">

      <div class="box box-primary">

         <div class="box1">

            <div class="box-info">

               <!-- DEFAULT LOADER -->

               <div class="box-text box-body">

                  <?php

                  if (isset($edit_id) && $edit_id > 0) {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'shop_ayurvedic__form', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('shop_ayurvedic-update/' . $edit_id, $attributes);

                  } else {

                     $attributes = array('class' => 'form-horizontal', 'id' => 'shop_ayurvedic__form', 'role' => 'form', 'enctype' => 'multipart/form-data');

                     echo form_open('shop_ayurvedic-add', $attributes);

                  }

                  ?>

                  <div class="box-body">



                     <div class="form-group">

                        <div class="col-md-3">

                           <label>Select Product <span class="red">*</span>: </label>

                        </div>

                        <div class="col-md-9">

                           <select name="shop_product_id" id="shop_product_id" class="selectpicker form-control"

                              required data-show-subtext="true" data-live-search="true">

                              <option value="">Select Product</option>

                              <?php

                              $product_id = !empty($edit_id) ? $brand['shop_product_id'] : '';



                              if ($ArrProduct != '') {

                                 foreach ($ArrProduct as $row) {

                                    $selected = "";

                                    if ($product_id == $row["product_id"]) {

                                       $selected = "selected";

                                    }



                                    if (!empty($row['product_name']))

                                       echo '<option value=' . $row["product_id"] . ' ' . $selected . '>' . $row["product_name"] . '</option>';

                                 }

                                 //form validation

                              } ?>

                           </select>

                           <?php echo form_error('shop_product_id'); ?>

                           <p id="shop_product_iderror"></p>

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
                  </div>

                  <div class="form-group">

                     <div class="col-md-2 col-md-offset-3">

                        <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>"

                           name="shop_id">

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