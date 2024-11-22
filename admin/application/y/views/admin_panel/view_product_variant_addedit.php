<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      
      <form role="form" method="post" action="<?php echo SITE_URL ?>adminpanel/controller_product_variant/save">
            <div class="box-body">
               <div class="form-group">
                  <label for="exampleInputEmail1">Product Variant</label>
                  <input type="text" class="form-control" id="product_variant_name"  name="product_variant_name" placeholder="Enter product_variant_name" onKeyUp="convertToSlug();" value="<?php echo (isset($ArrProductVariantData['product_variant_name']))?trim($ArrProductVariantData['product_variant_name']):''; ?>">
                  <div class="alert alert-danger" role="alert" style="display:none"></div>
                  <input type="hidden" name="product_variant_id" id="product_variant_id" value="<?php echo (isset($ArrProductVariantData['product_variant_id']))?trim($ArrProductVariantData['product_variant_id']):''; ?>">
               </div>
			   
            
			
					<div id="specific_product_variant_div">
					<div class="form-group">
					   <div class="col-md-3"><label>Variant Values:</label></div>
					   <div class="col-md-9">
						
						
					
					<script>
						$( document ).ready(function() {
							$("#promotional_code_product_variants").on('click', '.remove-button', function(e) {
								var rowCount = $('#promotional_code_product_variants tr').length;
								if(rowCount<=1)
								{
										alert('You can not remove all the product variant');
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
						function addRow()
						{
							var product_variant_counter = parseInt($("#product_variant_counter").val());
							product_variant_counter = product_variant_counter + 1;
							$("#product_variant_counter").val(product_variant_counter);
							
							$('#promotional_code_product_variants').append('<tr><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove value"><img src="<?php echo admin_media(); ?>dist/img/close-2.png"></a></td><td><input type="text" name="product_variant_value[]" placeholder="Value" class="form-control only_number discountValue'+product_variant_counter+'"></td><td><a href="javascript:void(0);" onClick="addRow()" title="Click here to add new value"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td></tr>');
						}
						</script>
						<style type="text/css">
						
						#promotional_code_product_variants tr td
						{
							padding:2px;
						}
						#promotional_code_product_variants .form-control
						{
							width:100%;
						}
						</style>
						
						<table id="promotional_code_product_variants">
							<?php 
								$product_variant_counter=1;
							$ArrSelectedProducts = array();
							if(isset($ArrProductVariantData['product_variant_value']) && $ArrProductVariantData['product_variant_value']!='')
							{
							$ArrSelectedProducts = explode(",",$ArrProductVariantData['product_variant_value']);
							}
							
							if(is_array($ArrSelectedProducts) && count($ArrSelectedProducts)>0)
							{
							foreach($ArrSelectedProducts as $value) { 
							?>
							<tr>
								<td>
									<a href="javascript:void(0);" class="remove-button" title="Click here to remove value">
									<img src="<?php echo admin_media(); ?>dist/img/close-2.png">
									</a>
								</td>
								<td><input type="text" name="product_variant_value[]" required value="<?php echo $value; ?>" placeholder="Value" class="form-control only_number discountValue<?php echo $product_variant_counter; ?>">
								 <?php echo form_error('product_variant_value'); ?>
								 </td>
								<td><a href="javascript:void(0);" onClick="addRow()" title="Click here to add new value"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td>
							</tr>	
							
							<?php
							} 
							}else{#End if 
							?>
							<tr>
								<td>
									<a href="javascript:void(0);" class="remove-button" title="Click here to remove value">
									<img src="<?php echo admin_media(); ?>dist/img/close-2.png">
									</a>
								</td>
								<td><input type="text" name="product_variant_value[]" placeholder="Value" class="form-control only_number discountValue1"> <?php echo form_error('product_variant_value'); ?></td>
								<td><a href="javascript:void(0);" onClick="addRow()" title="Click here to add new value"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a></td>
							</tr>	
							<?php } ?>
										
						</table>
						<input type="hidden" name="product_variant_counter" id="product_variant_counter" value="<?php echo $product_variant_counter; ?>" />
						</div>
						</div>
					</div>
					
              <div class="form-group radio_btn_design_nomal">
                <label for="exampleInputEmail1">Is Active</label>
              <div class="radio">
                 <?php if(isset($ArrProductVariantData['is_active']) && $ArrProductVariantData['product_variant_id'] > 0 ){ ?>
                     <label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($ArrProductVariantData['is_active']) && $ArrProductVariantData['is_active'] == "1")?'checked':''; ?>>Yes</label>
                     <label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($ArrProductVariantData['is_active']) && $ArrProductVariantData['is_active'] == "0")?'checked':''; ?>>No</label>
                 <?php }else{ ?>
                     <label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>
                     <label><input type="radio" name="is_active" id="is_active2" value="0" >No</label>
                  <?php }?>

              </div>
              </div>
            </div>
              <div class="box-footer">
                  <input type="submit" name="save_product_variant"  class="btn btn-default" value="<?php echo (isset($ArrProductVariantData['product_variant_id']) && $ArrProductVariantData['product_variant_id'] > 0)?'Update':'Add'; ?>">
                  <a href="<?php echo SITE_URL ?>product_variant" class="btn btn-info">Cancel</a>
              </div>
      </form>
    </div>
  </div>
</div>
