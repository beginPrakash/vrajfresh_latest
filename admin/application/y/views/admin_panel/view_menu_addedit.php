<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      
      <form role="form" method="post" action="<?php echo SITE_URL ?>adminpanel/controller_menu/save">
            <div class="box-body">
               <div class="form-group">
                  <label for="exampleInputEmail1">Menu Name</label>
                  <input type="text" class="form-control" id="menu_name"  name="menu_name" placeholder="Enter menu_name" onKeyUp="convertToSlug();" value="<?php echo (isset($ArrMenuData['menu_name']))?trim($ArrMenuData['menu_name']):''; ?>">
                  <div class="alert alert-danger" role="alert" style="display:none"></div>
                  <input type="hidden" name="menu_id" id="menu_id" value="<?php echo (isset($ArrMenuData['menu_id']))?trim($ArrMenuData['menu_id']):''; ?>">
               </div>
			   
            
			
					<div id="specific_menu_div">
					<div class="form-group">
					<label>Menu Items:</label>
					   <div class="col-md-12">
						
						
					
					<script>
						$( document ).ready(function() {
							$("#menu_items").on('click', '.remove-button', function(e) {
								var rowCount = $('#menu_items tr').length;
								if(rowCount<=1)
								{
										alert('You can not remove all the menu');
								}
								else
								{
								var whichtr = $(this).closest("tr");
								whichtr.remove();  
								var menu_counter = parseInt($("#menu_counter").val());
								menu_counter = menu_counter -1;
								$("#menu_counter").val(menu_counter);						
								}     
							});
							
							
							$("#menu_items").on('click', '.submenu-remove-button', function(e) {
								var whichtr = $(this).closest("tr");
								whichtr.remove();     
							});
							
						});
						function addRow()
						{
							var menu_counter = parseInt($("#menu_counter").val());
							menu_counter = menu_counter + 1;
							$("#menu_counter").val(menu_counter);
							
							$('#menu_items').append('<tr><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove parent menu"><img src="<?php echo admin_media(); ?>dist/img/close-2.png"></a></td><td><input type="text" name="menu_title['+menu_counter+'][]" placeholder="Menu Title" class="form-control menu_item'+menu_counter+'"></td><td><input type="text" name="menu_link['+menu_counter+'][]" required value="" placeholder="Menu Link" class="form-control menu_item'+menu_counter+'"></td><td><a href="javascript:void(0);" onClick="addSubMenu('+menu_counter+');" class="submenu-add-button" title="Click here to add new sub menu"><img src="<?php echo admin_media(); ?>dist/img/plus_icon.png"></a></td></tr><tr><td id="menu_'+menu_counter+'" colspan="3"></td></tr>');
						}
						function addSubMenu(counter)
						{
							$('#menu_'+counter).append('<tr><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove parent menu"><img src="<?php echo admin_media(); ?>dist/img/close-2.png" style="visibility:hidden;"></a></td><td><input type="text" name="sub_menu_title['+counter+'][]" placeholder="Sub Menu Title" class="form-control menu_item"></td><td><input type="text" name="sub_menu_link['+counter+'][]" required value="" placeholder="Sub Menu Link" class="form-control menu_item"></td><td><a href="javascript:void(0);" class="submenu-remove-button" title="Click here to remove sub menu"><img src="<?php echo admin_media(); ?>dist/img/delete_icon.png" width="20"></a></td></tr>'); 
						}
						
						</script>
						<style type="text/css">
						
						#menu_items tr td
						{
							padding:2px;
						}
						#menu_items .form-control
						{
							width:100%;
						}
						</style>
						
						<table id="menu_items">
							<?php 
							$menu_counter=1;
							
							if(is_array($ArrMenuItemsData) && count($ArrMenuItemsData)>0)
							{
							foreach($ArrMenuItemsData as $value) { 
							$ArrSubmenu = $this->menu_master_model->getSubMenuItemsUsingMenuId($value['menu_item_id']);
							?>
							<tr>
								<td>
									<a href="javascript:void(0);" class="remove-button" title="Click here to remove value">
									<img src="<?php echo admin_media(); ?>dist/img/close-2.png">
									</a>
								</td>
								<td><input type="text" name="menu_title[<?php echo $menu_counter; ?>][]" required value="<?php echo $value['menu_title']; ?>" placeholder="Menu Title" class="form-control menu_item<?php echo $menu_counter; ?>">
								 <?php echo form_error('menu_title'); ?>
								 </td>
								<td><input type="text" name="menu_link[<?php echo $menu_counter; ?>][]" required value="<?php echo $value['menu_link']; ?>" placeholder="Menu Link" class="form-control menu_item<?php echo $menu_counter; ?>">
								 <?php echo form_error('menu_link'); ?>
								 </td>
								<td>
								<a href="javascript:void(0);" onClick="addSubMenu(<?php echo $menu_counter; ?>);" class="submenu-add-button" title="Click here to add new sub menu"><img src="<?php echo admin_media(); ?>dist/img/plus_icon.png"></a>
								</td>
							</tr>
							<tr>
								<td id="menu_<?php echo $menu_counter; ?>" colspan="3">
								<?php
								if(is_array($ArrSubmenu) && count($ArrSubmenu)>0)
								{
									foreach($ArrSubmenu as $value)
									{
									?>
										<tr><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove parent menu"><img src="<?php echo admin_media(); ?>dist/img/close-2.png" style="visibility:hidden;"></a></td><td><input type="text" name="sub_menu_title[<?php echo $menu_counter; ?>][]" required value="<?php echo $value['menu_title']; ?>" placeholder="Sub Menu Title" class="form-control menu_item"></td><td><input type="text" name="sub_menu_link[<?php echo $menu_counter; ?>][]" required value="<?php echo $value['menu_link']; ?>" placeholder="Sub Menu Link" class="form-control menu_item"></td><td><a href="javascript:void(0);" class="submenu-remove-button" title="Click here to remove sub menu"><img src="<?php echo admin_media(); ?>dist/img/delete_icon.png" width="20"></a></td></tr>
									<?php
									}
								}
								?>
								</td>
							</tr>
							
							<?php
							$menu_counter++;
							} 
							}else{#End if 
							?>
							<tr>
								<td>
									<a href="javascript:void(0);" class="remove-button" title="Click here to remove value">
									<img src="<?php echo admin_media(); ?>dist/img/close-2.png">
									</a>
								</td>
								<td><input type="text" name="menu_title[1][]" placeholder="Menu Title" class="form-control menu_item1"> <?php echo form_error('menu_title'); ?></td>
								<td><input type="text" name="menu_link[1][]" placeholder="Menu Link" class="form-control menu_item1"> <?php echo form_error('menu_link'); ?></td>
								<td>
								<a href="javascript:void(0);" onClick="addSubMenu(1);" class="submenu-add-button" title="Click here to add new sub menu"><img src="<?php echo admin_media(); ?>dist/img/plus.png"></a>
								</td>
							</tr>
							<tr>
								<td id="menu_1" colspan="3"></td>
							</tr>	
							<?php } ?>
						</table>
								<a href="javascript:void(0);" onClick="addRow()" title="Click here to add new value"><img src="<?php echo admin_media(); ?>dist/img/plus.png"> Add New Menu</a>
						<input type="hidden" name="menu_counter" id="menu_counter" value="<?php echo $menu_counter; ?>" />
						</div>
						</div>
					</div>
					
              <div class="form-group radio_btn_design_nomal">
                <label for="exampleInputEmail1">Is Active</label>
              <div class="radio">
                 <?php if(isset($ArrMenuData['is_active']) && $ArrMenuData['menu_id'] > 0 ){ ?>
                     <label><input type="radio" name="is_active" id="is_active1" value="1" <?php echo (isset($ArrMenuData['is_active']) && $ArrMenuData['is_active'] == "1")?'checked':''; ?>>Yes</label>
                     <label><input type="radio" name="is_active" id="is_active2" value="0" <?php echo (isset($ArrMenuData['is_active']) && $ArrMenuData['is_active'] == "0")?'checked':''; ?>>No</label>
                 <?php }else{ ?>
                     <label><input type="radio" name="is_active" id="is_active1" value="1" checked>Yes</label>
                     <label><input type="radio" name="is_active" id="is_active2" value="0" >No</label>
                  <?php }?>

              </div>
              </div>
            </div>
              <div class="box-footer">
                  <input type="submit" name="save_menu"  class="btn btn-default" value="<?php echo (isset($ArrMenuData['menu_id']) && $ArrMenuData['menu_id'] > 0)?'Update':'Add'; ?>">
                  <a href="<?php echo SITE_URL ?>menu" class="btn btn-info">Cancel</a>
              </div>
      </form>
    </div>
  </div>
</div>
