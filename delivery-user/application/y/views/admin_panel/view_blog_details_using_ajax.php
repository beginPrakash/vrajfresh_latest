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
      <label for="exampleInputEmail1">Blog Title : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['blog_title'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Blog URL : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['blog_url'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Blog Author : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['author'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Blog Description : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['blog_description'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Meta Title : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['meta_title'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label for="exampleInputEmail1">Meta Descriptions : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['meta_descriptions'] ?></label>
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