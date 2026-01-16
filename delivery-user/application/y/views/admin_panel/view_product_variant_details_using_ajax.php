<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Created Date : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo date(DATE_FORMAT, strtotime($ArrFieldData['created_datetime'])) ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Product Variant Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['product_variant_name'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Values : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['product_variant_value'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Is Active : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo ($ArrFieldData['is_active'] == '1')?'Yes':'No'; ?></label>
   </div>
</div>