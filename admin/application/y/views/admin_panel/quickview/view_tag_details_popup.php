

<div class="row">
   <div class="col-sm-3">
      <label>Tag Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['tag'] ?></label>
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