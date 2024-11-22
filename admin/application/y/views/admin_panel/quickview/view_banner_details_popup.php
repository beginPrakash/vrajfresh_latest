

<div class="row">
   <div class="col-sm-3">
      <label>Banner Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['banner_text'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Banner URL: </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['banner_link'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Description : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['description'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Banner Image : </label>
   </div>
   <div class="col-sm-9">
      <?php
      if($ArrFieldData['banner_image'] == ''){
         $imag = base_url().'uploads/noimg.gif'; 
      }else{
         $imag = base_url().'uploads/banner/'.$ArrFieldData['banner_image'];
      }  ?>
      <img width="auto" src= "<?php echo base_url().'timthumb.php?src='.$imag; ?>&w=350px&zc=-1" alt="No Image Found" >
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Banner Type : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo ($ArrFieldData['banner_type'] == 'slider')?'Slider':'Bottom'; ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Is Active : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo ($ArrFieldData['is_active'] == '1')?'Yes':'No'; ?></label>
   </div>
</div>