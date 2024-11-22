<div class="row">
<div class="col-md-12">
   <div class="box box-primary">
      <div class="box1" >
         <div class="box-info">
            <div class="box-text box-body">
               <div class="box-body">
                  <?php
                  $selected_category_id = (!empty($edit_id)) ? $product['category_id'] : '';
                  $brand_id = (!empty($edit_id)) ? $product['brand_id'] : '0';
                  $product_associate_to_id = (!empty($edit_id)) ? $product['product_associate_to_id'] : '';

                  $options_brand = array("0" => 'Select');
                  if (isset($ArrBrands) && count($ArrBrands) > 0)
                     foreach ($ArrBrands as $row) {
                        $options_brand[$row['brand_id']] = $row['brand_name'];
                     }

                  $options_category = array();
                  if (isset($category) && count($category) > 0)
                     foreach ($category as $row) {
                        $options_category[$row['category_id']] = $row['category_name'];
                     }

                  $options_product = array();
                  foreach ($ArrProducts as $row) {
                     $options_product[$row['product_id']] = $row['product_name'];
                  }

                  //form validation
                  $attributes = array('class' => 'form-horizontal', 'id' => 'product_form', 'role' => 'form', 'enctype' => 'multipart/form-data', 'onSubmit' => 'return valdiateform();');
                  $product_id = (!empty($edit_id) && $edit_id > 0) ? $edit_id : '';
                  if (!empty($product_id)) {
                     echo form_open('product-update/' . $product_id, $attributes);
                     ;
                  } else {
                     echo form_open('product-add', $attributes);
                  }
                  ?>
                  <input type="hidden"  id="product_associate_to_ids" value="<?php echo $product_associate_to_id; ?>" />
                  <input type="hidden" id="selected_category_id" value="<?php echo $selected_category_id; ?>" />
                  <input type="hidden" name="prod_id" id="prod_id" value="<?php echo $product_id; ?>" />
                  <input type="hidden" name="prod_icon_img" value="<?php echo (!empty($edit_id)) ? $product['product_image'] : ''; ?>" />
                  <!-- text input -->
                  <div class="box_bg_color">
                    <div class="form-group">
                     <div class="col-sm-3">
                        <div>
                           <label>Product Name : <span class="red">*</span></label>
                           <input type="text" placeholder="Name" class="form-control" id="product_name" name="product_name" value="<?php echo (!empty($edit_id)) ? $product['product_name'] : ''; ?>" required onKeyUp="convertToSlug();" >
                           <?php echo form_error('product_name'); ?>
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div>
                           <label>Product Sub Name : <span class="red">*</span></label>
                           <input type="text" placeholder="Name" class="form-control" name="product_sub_name" value="<?php echo (!empty($edit_id)) ? $product['product_sub_name'] : ''; ?>" required>
                           <?php echo form_error('product_sub_name'); ?>
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div>
                           <label>Product URL : <span class="red">*</span></label>
                           
                    <input type="hidden" name="check_url" id="check_url" value="<?php echo base_url() ?>adminpanel/controller_product/ajaxCheckUrl">
                    <input type="text" class="form-control" id="product_slug" name="product_slug" placeholder="Product URL" value="<?php echo (isset($product['product_slug'])) ? trim($product['product_slug']) : ''; ?>">
                    <div class="alert alert-danger" role="alert" style="display:none"></div>
                    
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div>
                          <label>Brand<span class="red">*</span></label>
                          <?php
                          $attributes = array('id' => 'brand_id', 'class' => 'form-control');
                          echo form_dropdown('brand_id', $options_brand, $brand_id, $attributes);
                          ?>
                          <label for="brand_iderror" id="brand_iderror" class="error"></label>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div>
                          <label>Category<span class="red">*</span></label>
                          <?php
                          $attributes = array('id' => 'category_id', 'class' => 'form-control', "multiple" => "true");
                          echo form_dropdown('category_id[]', $options_category, '', $attributes);
                          ?>
                          <label for="category_id_iderror" id="category_id_iderror" class="error"></label>
                        </div>
                     </div>
                <div class="col-sm-6">
                        <div>
                          <label>Product Associate:</label>
                          <?php
                          $attributes = array('id' => 'product_associate_to_id', 'class' => 'form-control', "multiple" => "true");
                          echo form_dropdown('product_associate_to_id[]', $options_product, '', $attributes);
                          ?>
                        </div>
                     </div>
                     <!--<div class="col-sm-3">
                        <div>
                           <label>Short Description : <span class="red">*</span></label>
                           <input type="text" placeholder="Short Description" class="form-control" name="short_desc" value="<?php echo (!empty($edit_id)) ? $product['short_desc'] : ''; ?>" required>
                           <?php echo form_error('short_desc'); ?>
                        </div>
                     </div>-->
                     </div>
                     <div class="form-group">
                     <div class="col-sm-3">
                        <div>
                           <label>MRP : <span class="red">*</span></label>
                           <input type="text" placeholder="MRP" class="form-control only_number" name="product_price" value="<?php echo (!empty($edit_id)) ? $product['product_price'] : ''; ?>" id="product_price" required>
                           <?php echo form_error('product_price'); ?>
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div>
                           <label>Sale Price : <span class="red">*</span></label>
                           <input type="text" placeholder="Sale Price" class="form-control only_number" name="sale_price" id="sale_price"  value="<?php echo (!empty($edit_id)) ? $product['sale_price'] : ''; ?>" required>
                           <?php echo form_error('sale_price'); ?>
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div>
                           <label>Product Icon : </label>
                           <?php if (!empty($edit_id) && $product['product_image'] != '') { ?>
                              <img height="70px" width="70px" src='<?php echo base_url(); ?>uploads/products/<?php echo $product['product_image'] ?>' border=0 >  
                              <input type="file" class="" name="product_image" accept="image/*" data-type="image">
                           <?php } else { ?>
                              <input type="file" class="form-control" name="product_image" accept="image/*" data-type="image">   
                           <?php } ?>
                           <?php echo form_error('product_image'); ?>
                        </div>
                     </div>
                     <div class="col-sm-3">
                         <label>Product Style : </label>
                           <input type="text" placeholder="Product Style" class="form-control" name="product_style"  value="<?php echo (!empty($edit_id)) ? $product['product_style'] : ''; ?>" required>
                           <?php echo form_error('product_style'); ?>
                     </div>
                     </div>
                     <div class="form-group">
                
                     
                     
                     
                
                
                     </div>
                     <div class="clearfix"></div>
                
                
                
                     <div class="form-group">
                <div class="col-sm-12">
                        <div>
                          <label>Product Tags:</label>
                          <input type="text" name="product_tags" id="product_tags" value="Amsterdam,Washington,Sydney,Beijing,Cairo" data-role="tagsinput" 
                           </div>
                           </div>
                           </div>

<div class="clearfix"></div>
                     <div class="form-group">
                <div class="col-sm-12">
                        <div>
                          <label>Product Variants:</label>
                          
                    
                    
               
               <script>
                  $( document ).ready(function() {
                     $("#product_variant").on('click', '.remove-button', function(e) {
                        var rowCount = $('#product_variant tr').length;
                        if(parseInt($("#product_variant_counter").val())==1)
                        {
                           var whichtr = $(this).closest("tr");
                           whichtr.remove();  
                           var product_variant_counter = parseInt($("#product_variant_counter").val());
                           product_variant_counter = product_variant_counter -1;
                           $("#product_variant_counter").val(product_variant_counter);	
                           $('#product_variant').append('<tr class="v_notice"><td>Variant not available. <a href="javascript:void(0);" onClick="addVariantRow()" title="Click here to add new variant">Add first variant</a>.</td></tr>');									
                        }
                        else
                        {
                        var whichtr = $(this).closest("tr");
                        whichtr.remove();  
                        var product_variant_counter = parseInt($("#product_variant_counter").val());
                        product_variant_counter = product_variant_counter -1;
                        $("#product_variant_counter").val(product_variant_counter);						
                        }     
                     });
                     
                  });
                  function addVariantRow()
                  {
                     var product_variant_counter = parseInt($("#product_variant_counter").val());
                     product_variant_counter = product_variant_counter + 1;
                     $("#product_variant_counter").val(product_variant_counter);
                     if($('#product_variant tr').length>0)
                     {
                        $(".v_notice").hide();
                     }
                     $.ajax({
                     type: "POST",
                     url: "<?php echo site_url(); ?>adminpanel/controller_product/getVariant/",
                     success: function (data) {
                        $('#product_variant').append(data);
                     }
                     });
                  }
                  </script>
                  <style type="text/css">
                  
                  #product_variant tr td
                  {
                     padding:2px;
                  }
                  #product_variant .form-control
                  {
                     width:100%;
                  }
                  </style>
                  
                  <table id="product_variant">
                     <?php
                     $product_variant_counter = 1;

                     if (isset($tempArrProductSelectedVariants) && is_array($tempArrProductSelectedVariants) && count($tempArrProductSelectedVariants) > 0) {
                        foreach ($tempArrProductSelectedVariants as $value) {
                           ?>
                           <tr>
                              <td>
                                 <input type="hidden" name="ArrVariantId[]" value="<?php echo $value['id']; ?>">
                                 <input type="hidden" name="ArrVariantImagePath[]" value="<?php echo $value['variant_image']; ?>">
                                 <a href="javascript:void(0);" class="remove-button" title="Click here to remove variant">
                                 <img src="<?php echo admin_media(); ?>dist/img/close-2.png">
                                 </a>
                              </td>
                              <td>
                                 <?php
                                 echo form_dropdown('ArrVariantColor[]', $ArrColor, $value['product_variant_color'], array('class' => 'form-control'));
                                 ?>
                              </td>
                              <td>
                                 <?php
                                 echo form_dropdown('ArrVariantSize[]', $ArrSize, $value['product_variant_size'], array('class' => 'form-control'));
                                 ?>
                              </td>
                              <td>
                                 <input type="text" placeholder="Price" class="form-control" name="ArrVariantPrice[]"  value="<?php echo $value['variant_price']; ?>" required>
                              </td>
                              <td>
                                 <input type="text" placeholder="Stock QTY" class="form-control" name="ArrVariantQTY[]"  value="<?php echo $value['variant_qty']; ?>" required>
                              </td>
                              <td>
                              <img height="70px" width="70px" src='<?php echo base_url(); ?>uploads/products/<?php echo $value['variant_image']; ?>' border=0 ><input type="file" name="ArrProducVariants[]" placeholder="Upload Image" class="form-control discountValue1"> <?php echo form_error('ArrProducVariants'); ?></td>
                              <td><a href="javascript:void(0);" onClick="addVariantRow()" title="Click here to add new variant"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td>
                           </tr>	
                           <?php
                        } #End Foreach
                     } else { #End if 
                        ?>
                     <!--<tr>
                        <td>
                           <input type="hidden" name="ArrVariantId[]" value="">
                           <input type="hidden" name="ArrVariantImagePath[]" value="">
                           <a href="javascript:void(0);" class="remove-button" title="Click here to remove variant">
                           <img src="<?php echo admin_media(); ?>dist/img/close-2.png">
                           </a>
                        </td>
                        <td>
                           <?php
                           echo form_dropdown('ArrVariantColor[]', $ArrColor, '', array('class' => 'form-control'));
                           ?>
                        </td>
                        <td>
                           <?php
                           echo form_dropdown('ArrVariantSize[]', $ArrSize, '', array('class' => 'form-control'));
                           ?>
                        </td>
                        <td>
                           <input type="text" placeholder="Price" class="form-control" name="ArrVariantPrice[]"  value="" required>
                        </td>
                        <td>
                           <input type="text" placeholder="Stock QTY" class="form-control" name="ArrVariantQTY[]"  value="" required>
                        </td>
                        <td>
                        <input type="file" name="ArrProducVariants[]" placeholder="Upload Image" class="form-control discountValue1"> <?php echo form_error('ArrProducVariants'); ?>
                        </td>
                        
                        <td><a href="javascript:void(0);" onClick="addVariantRow()" title="Click here to add new variant"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td>
                     </tr>-->
<td>Variant not available. <a href="javascript:void(0);" onClick="addVariantRow()" title="Click here to add new variant">Add first variant</a>.</td>							
                     <?php } ?>
                              
                  </table>
                  <input type="hidden" name="product_variant_counter" id="product_variant_counter" value="<?php echo $product_variant_counter; ?>" />
                  
                  
                        </div>
                     </div>
                     </div>
                
                     <!--<div class="form-group">
                <div class="col-sm-12">
                        <div>
                          <label>Product Variants:</label>
                          <?php
                          foreach ($ArrProductMasterVariants as $ArrPV) {
                             $ArrPVValue = explode(',', $ArrPV['product_variant_value']);
                             ?>
                     <br><label><?php echo $ArrPV['product_variant_name']; ?>:</label>
                     <?php
                     $i = 1;
                     foreach ($ArrPVValue as $variant_value) {
                        $vname = str_replace(' ', '', $ArrPV['product_variant_name']);
                        $temp_checked = false;
                        if (isset($ArrProductSelectedVariants) && count($ArrProductSelectedVariants) > 0) {
                           if (array_key_exists($vname, $ArrProductSelectedVariants)) {
                              $temp_checked = false;
                              foreach ($ArrProductSelectedVariants as $v => $v_value) {
                                 $ArrVValue = explode('|', $v_value);
                                 if (in_array($variant_value, $ArrVValue))
                                    $temp_checked = true;
                              }
                           }
                        }
                        ?>
                        &nbsp;&nbsp;<input type="checkbox" name="<?php echo $vname; ?>[]" value="<?php echo $variant_value; ?>" <?php if ($temp_checked) {
                                 echo 'checked';
                              } ?>>
                        &nbsp;<?php echo $variant_value; ?>
                        <?php
                     }
                          }
                          ?>
                        </div>
                     </div>
                     </div>-->
                     <div class="clearfix"></div>
                
                
                     <div class="form-group">
                <div class="col-sm-12">
                        <div>
                          <label>Product Images:</label>
                          
                    
                    
               
               <script>
                  $( document ).ready(function() {
                     $("#product_images").on('click', '.remove-button', function(e) {
                        var rowCount = $('#product_images tr').length;
                        if(rowCount<=1)
                        {
                              alert('You can not remove all the product image');
                        }
                        else
                        {
                        var whichtr = $(this).closest("tr");
                        whichtr.remove();  
                        var product_image_counter = parseInt($("#product_image_counter").val());
                        product_image_counter = product_image_counter -1;
                        $("#product_image_counter").val(product_image_counter);						
                        }     
                     });
                     
                  });
                  function addRow()
                  {
                     var product_image_counter = parseInt($("#product_image_counter").val());
                     product_image_counter = product_image_counter + 1;
                     $("#product_image_counter").val(product_image_counter);
                     
                     $('#product_images').append('<tr><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove image"><img src="<?php echo admin_media(); ?>dist/img/close-2.png"></a></td><td><input type="file" name="ArrProductImages[]" placeholder="Upload Image" class="form-control only_number discountValue'+product_image_counter+'"></td><td><a href="javascript:void(0);" onClick="addRow()" title="Click here to add new image"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td></tr>');
                  }
                  </script>
                  <style type="text/css">
                  
                  #product_images tr td
                  {
                     padding:2px;
                  }
                  #product_images .form-control
                  {
                     width:100%;
                  }
                  </style>
                  <div>
                     <?php
                     if (isset($ArrProductImages) && count($ArrProductImages) > 0) {
                        foreach ($ArrProductImages as $value) {
                           ?>
                           <span>
                           <img height="70px" width="70px" src='<?php echo base_url(); ?>uploads/products/<?php echo $value['product_image']; ?>' border=0 >
                           <a href="<?php echo base_url(); ?>product-image-delete/<?php echo $product_id; ?>/<?php echo $value['product_image_id']; ?>" class="remove-button" title="Click here to remove image">
                           <img src="<?php echo admin_media(); ?>dist/img/close-2.png">
                           </a>
                           </span>
                           <?php
                        }
                     }
                     ?>
                  </div>
                  <table id="product_images">
                     <?php
                     $product_image_counter = 1;

                     if (isset($ArrProductImages) && is_array($ArrProductImages) && count($ArrProductImages) > 0) {
                        ?>
                        <tr>
                           <td>
                              <a href="javascript:void(0);" class="remove-button" title="Click here to remove value">
                              <img src="<?php echo admin_media(); ?>dist/img/close-2.png">
                              </a>
                           </td>
                           <td><input type="file" name="ArrProductImages[]" placeholder="Upload Image" class="form-control discountValue1"> <?php echo form_error('ArrProductImages'); ?></td>
                           <td><a href="javascript:void(0);" onClick="addRow()" title="Click here to add new value"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td>
                        </tr>	
                        <?php
                     } else { #End if 
                        ?>
                        <tr>
                           <td>
                              <a href="javascript:void(0);" class="remove-button" title="Click here to remove value">
                              <img src="<?php echo admin_media(); ?>dist/img/close-2.png">
                              </a>
                           </td>
                           <td><input type="file" name="ArrProductImages[]" placeholder="Upload Image" class="form-control discountValue1"> <?php echo form_error('ArrProductImages'); ?></td>
                           <td><a href="javascript:void(0);" onClick="addRow()" title="Click here to add new value"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td>
                        </tr>	
                     <?php } ?>
                              
                  </table>
                  <input type="hidden" name="product_image_counter" id="product_image_counter" value="<?php echo $product_image_counter; ?>" />
                  
                  
                        </div>
                     </div>
                     </div>
                     <div class="clearfix"></div>
                
                
                
                     
                     <div class="form-group">
                     <div class="col-sm-3">
                        <div class="radio_btn_design_add_products">
                        
                           <label style="width: 100%;">Is Featured Product : </label>      
                           <div class="checkbox">
                           <?php if (!empty($edit_id)) { ?>
                              <label style="padding-left: 0px;"> <input type="radio" name="is_featured" value="1" <?php if (!empty($edit_id) && $product['is_home_display'] == "1") {
                                 echo "checked";
                              } ?>> Yes  
                              <input type="radio" name="is_featured" value="0" <?php if (!empty($edit_id) && $product['is_home_display'] == "0") {
                                 echo "checked";
                              } ?>> No </label>
                              <?php } else { ?>
                                 <label style="padding-left: 0px;"> <input type="radio" name="is_featured" value="1" checked> Yes  
                                  </label>
                          <label><input type="radio" name="is_featured" value="0"> No</label>
                               </label>
                              <?php } ?>
                           </div>
                           <?php echo form_error('is_featured'); ?> 
                        </div>
                     </div>
                     <div class="col-sm-3">
                        <div class="radio_btn_design_add_products">
                           <label style="padding-left: 0px; width: 100%;">Is Sale Product : </label>      
                           <div class="checkbox">
                           <?php if (!empty($edit_id)) { ?>
                              <label style="padding-left: 0px; width: 100%;"> 
                                <input type="radio" name="is_sale" value="1" <?php if ($product['product_type'] == "1") {
                                   echo "checked";
                                } ?>> Yes  
                                <input type="radio" name="is_sale" value="0" <?php if ($product['product_type'] == "0") {
                                   echo "checked";
                                } ?>> No 
                              </label>
                              <?php } else { ?>
                                 <label style="padding-left: 0px;"> 
                                 <input type="radio" name="is_sale" value="1" checked > Yes </label>
                         <label><input type="radio" name="is_sale" value="0"> No </label>
                              <?php } ?>
                           </div>
                           <?php echo form_error('is_featured'); ?> 
                        </div>
                     </div>
                
                
                     <div class="col-sm-3">
                        <div class="radio_btn_design_add_products">
                           <label style="padding-left: 0px;">Is product available in Make Box?: </label>      
                           <div class="checkbox">
                              <?php if (!empty($edit_id)) { ?>
                                 <label style="width: 100%; padding-left: 0px;"> 
                                 <input type="radio" name="product_style" value="1" <?php if ($product['product_style'] == "1") {
                                    echo "checked";
                                 } ?>> Yes  
                                 <input type="radio" name="product_style" value="0" <?php if ($product['product_style'] == "0") {
                                    echo "checked";
                                 } ?>> No </label>
                                <?php } else { ?>
                                   <label style="padding-left: 0px;"> 
                                   <input type="radio" name="product_style" value="1" checked> Yes  
                                   </label>
                           <label><input type="radio" name="product_style" value="0"> No </label>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                
                     <div class="col-sm-3">
                        <div class="radio_btn_design_add_products">
                           <label style="padding-left: 0px;">Is Active: </label>      
                           <div class="checkbox">
                              <?php if (!empty($edit_id)) { ?>
                                 <label style="width: 100%; padding-left: 0px;"> 
                                 <input type="radio" name="is_active" value="1" <?php if ($product['is_active'] == "1") {
                                    echo "checked";
                                 } ?>> Yes  
                                 <input type="radio" name="is_active" value="0" <?php if ($product['is_active'] == "0") {
                                    echo "checked";
                                 } ?>> No </label>
                                <?php } else { ?>
                                   <label style="padding-left: 0px;"> 
                                   <input type="radio" name="is_active" value="1" checked> Yes  
                                   </label>
                           <label><input type="radio" name="is_active" value="0"> No </label>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                     
                     </div>
                     <div class="form-group">
                     
                
                    
                     <div class="col-sm-12">
                        <div>
                           <label>Long Description : </label>
                           <textarea placeholder="Long Description" rows="3" class="form-control" id="editor1" name="product_description"><?php echo (!empty($edit_id)) ? $product['product_description'] : ''; ?></textarea>
                           <?php echo form_error('product_description'); ?>
                        </div>
                     </div>
                
                     </div>
                     <div class="form-group">
                     <div class="col-sm-12">
                        <div>
                           <label>Meta Title : </label>
                           <input type="text" placeholder="Meta Title" class="form-control" value="<?php echo (!empty($edit_id)) ? $product['meta_title'] : ''; ?>" name="meta_title">
                           <?php echo form_error('meta_title'); ?>
                        </div>
                     </div>
                     </div>
                     <div class="form-group">
                     <div class="col-sm-12">
                        <div>
                           <label>Meta Description : </label>
                           <textarea placeholder="Meta Description" rows="3" class="form-control" name="meta_description"><?php echo (!empty($edit_id)) ? $product['meta_description'] : ''; ?></textarea>
                           <?php echo form_error('meta_description'); ?>
                        </div>
                     </div>
                     
                     </div>
                     <div class="clearfix"></div>
                     <div class="col-md-2 col-md-offset-5">
                        <div>
                           <p></p>
                           <input type="hidden" value="<?php echo (isset($edit_id) && $edit_id > 0) ? $edit_id : ''; ?>" name="mockup_and_template_id">
                           <button type="submit" class="btn btn-default" name="submit" id="submit" value="<?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?>" <?php echo 'name="save_user"'; ?> ><?php echo (isset($edit_id) && $edit_id > 0) ? 'Update' : 'Add'; ?></button>
                           <button type="reset" value="Reset" class="btn btn-info">Cancel</button>
                          
                        </div>
                     </div>
                     
                     </form>
                  </div>
              <!-- box-body --> 
              <script>
               $(function () {
                 // Replace the <textarea id="editor1"> with a CKEditor
                 CKEDITOR.replace('editor1');
                 $(".textarea").wysihtml5();
               });
               
              </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/tags/bootstrap-tagsinput.js"></script>
<script>
                           var citynames = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  prefetch: {
    url: '<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/tags/citynames.json',
    filter: function(list) {
      return $.map(list, function(cityname) {
        return { name: cityname }; });
    }
  }
});
citynames.initialize();
$('#product_tags').tagsinput({
  typeaheadjs: {
    name: 'citynames',
    displayKey: 'name',
    valueKey: 'name',
    source: citynames.ttAdapter()
  }
});
</script>

               </div>
            </div>
         </div>
      </div>
   </div>
</div>