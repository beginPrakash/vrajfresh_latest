<div class="row">
   <div class="col-sm-3">
      <label>Date : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['created_datetime']; ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Order ID : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['order_id']; ?>
      </label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Name : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['display_name']; ?>
      </label>
   </div>
</div>


<div class="row">
   <div class="col-sm-3">
      <label>Email : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['email']; ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Contact : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['phone']; ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Complain : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['complain']; ?>
      </label>
   </div>
</div>
<?php if ($ArrFieldData['image1'] != "") { ?>
   <div class="row">
      <div class="col-sm-3">
         <label>Attachment1 : </label>
      </div>
      <div class="col-sm-9">
         <label style="font-weight:400; line-height: 20px;"><a
               href="<?php echo SITE_URL . 'uploads/reports/' . $ArrFieldData['image1']; ?>" target="_blank">View
               File</a></label>
      </div>
   </div>
<?php } ?>
<?php if ($ArrFieldData['image2'] != "") { ?>
   <div class="row">
      <div class="col-sm-3">
         <label>Attachmnt2 : </label>
      </div>
      <div class="col-sm-9">
         <label style="font-weight:400; line-height: 20px;"><a
               href="<?php echo SITE_URL . 'uploads/reports/' . $ArrFieldData['image2']; ?>" target="_blank">View
               File</a></label>
      </div>
   </div>
<?php } ?>