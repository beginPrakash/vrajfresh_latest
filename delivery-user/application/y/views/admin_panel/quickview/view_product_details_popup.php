

<div class="row">
   <div class="col-sm-3">
      <label>Product SKU : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['product_sku'] ?></label>
   </div>
</div>


<div class="row">
   <div class="col-sm-3">
      <label>Product Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['product_name'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Product Sub Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['product_sub_name'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Product Weight : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['product_weight_gms'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>MRP : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['product_price'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Sale Price : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['sale_price'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Product Image : </label>
   </div>
   <div class="col-sm-9">
      <?php
      if($ArrFieldData['product_image'] == ''){
         $imag = base_url().'uploads/noimg.gif'; 
      }else{
         $imag = base_url().'uploads/products/'.$ArrFieldData['product_image'];
      }  ?>
      <img width="auto" src= "<?php echo base_url().'timthumb.php?src='.$imag; ?>&w=350px&zc=-1" alt="No Image Found" >
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Is Sold Out? : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo ($ArrFieldData['is_out_of_stock'] == '1')?'Yes':'No'; ?></label>
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
<div class="row">
   <div class="col-sm-3">
      <label>Is Home Display : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo ($ArrFieldData['is_home_display'] == '1')?'Yes':'No'; ?></label>
   </div>
</div>