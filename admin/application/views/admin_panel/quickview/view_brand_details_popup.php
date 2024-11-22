<div class="row">
   <div class="col-sm-3">
      <label>Brand Name : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['brand_name'] ?>
      </label>
   </div>
</div>


<div class="row">
   <div class="col-sm-3">
      <label>Long Desc : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['brand_description'] ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Brand Img : </label>
   </div>
   <div class="col-sm-9">
      <?php
      if ($ArrFieldData['brand_image'] == '') {
         $imag = base_url() . 'uploads/noimg.gif';
      } else {
         $imag = base_url() . 'uploads/brand/' . $ArrFieldData['brand_image'];
      } ?>
      <img width="auto" src="<?php echo base_url() . 'timthumb.php?src=' . $imag; ?>&w=350px&zc=-1" alt="No Image Found">
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Is Active : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo ($ArrFieldData['is_active'] == '1') ? 'Yes' : 'No'; ?>
      </label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Is Home Display : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo ($ArrFieldData['is_home_display'] == '1') ? 'Yes' : 'No'; ?>
      </label>
   </div>
</div>