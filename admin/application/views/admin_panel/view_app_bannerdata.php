<div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box1">
        <div class="box-info">
          <div class="box-text box-body profile"> <?php if (isset($error) && $error != '') { ?> <span style="color:red;text-align:center;"> <?php echo $error; ?> </span> <?php } ?> <?php if (isset($msg) && $msg != '') { ?> <span style="color:green;text-align:center;"> <?php echo $msg; ?> </span> <?php } ?> <?php

						$attributes = array('class' => 'form-horizontal', 'id' => '', 'role' => 'form', 'enctype' => 'multipart/form-data');

						echo form_open('saveapp-bannerdata', $attributes);

						?> <div class="box-body">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-12">
                    <h4>Application Banner:</h4>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <div class="col-md-4">
                    <label>Banner Image : </label>
                  </div>
                  <div class="col-md-8"> <?php if (isset($getbannerDataSingle) && $getbannerDataSingle['banner_image'] != '') { ?> <div class="col-md-3">
                      <img height="25px" width="25px" src='
												<?php echo base_url(); ?>uploads/bannerdata/
												<?php echo $getbannerDataSingle['banner_image'] ?>' border=0>
                    </div>
                    <div class="col-md-6">
                      <input type="file" class="form-control" name="banner_image">
                    </div>
                    <input type="hidden" value="<?php echo $getbannerDataSingle['banner_image'] ?>" name="cat_image"> <?php } else { ?> <input type="file" class="form-control" name="banner_image"> <?php } ?> <?php echo form_error('banner_image'); ?>
                  </div>
                </div> <?php $i=0;
								foreach ($ArrBannerData as $Arr) { ?> <div class="form-group">
                  <div class="col-md-12">
                    <div class="form-group">
                      <div class="col-md-12">
                        <h4>Category Section <?php echo $Arr['banner_id']; ?>: </h4>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-4"> Category Name: <span class="red">*</span>
                  </div>
                  <div class="col-sm-8">
                    <input type="text" placeholder="Category 1" class="form-control" name="bannerArr[category_name][<?php echo $i ; ?>]" 
					value="<?php echo $Arr['category_name']; ?>" required />
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-4">
                    <label>Category Image: </label>
                  </div>
                  <div class="col-md-8"> <?php if (isset($ArrBannerData) && $Arr['category_logo'] != '') { ?> <div class="col-md-3">
                      <img height="25px" width="25px" src='
																	<?php echo base_url(); ?>uploads/bannerdata/
																	<?php echo $Arr['category_logo'] ?>' border=0>
                    </div>
                    <div class="col-md-6">
                      <input type="file" class="form-control" name="category_logo[]">
                    </div>
                    <input type="hidden" value="<?php echo $Arr['category_logo'] ?>" name="old_category_logo[]"> <?php } else { ?> <input type="file" class="form-control" name="category_logo[]"> <?php } ?> <?php echo form_error('category_logo'); ?>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-4"> Slider Title Main: <span class="red">*</span>
                  </div>
                  <div class="col-sm-8">
                    <input type="text" placeholder="Slider Title" class="form-control" name="bannerArr[product_slider_title][<?php echo $i ; ?>]" 
					value="<?php echo $Arr['product_slider_title']; ?>" required />
                  </div>
                </div>
                 <?php  $i++; } ?>
              </div>
              <div class="box-body">
                <div class="col-md-12">
                  <div class="btn_alignmemt">
                    <button class="btn btn-success " name="submit" value="change_password">Update</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>