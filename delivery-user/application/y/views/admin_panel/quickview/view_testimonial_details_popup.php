

<div class="row">
   <div class="col-sm-3">
      <label>Customer Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['customer_name'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Sub Title: </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['customer_subname'] ?></label>
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
      <label>Customer Photo : </label>
   </div>
   <div class="col-sm-9">
      <?php
      if($ArrFieldData['testimonial_image'] == ''){
         $imag = base_url().'uploads/noimg.gif'; 
      }else{
         $imag = base_url().'uploads/testimonial/'.$ArrFieldData['testimonial_image'];
      }  ?>
      <img width="auto" src= "<?php echo base_url().'timthumb.php?src='.$imag; ?>&w=350px&zc=-1" alt="No Image Found" >
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