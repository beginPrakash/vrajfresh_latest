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
      <label>Name : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['name']; ?>
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
      <label>status : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo ($ArrFieldData['status'] == '1') ? 'Subscribed' : 'Unsubscribed'; ?>
      </label>
   </div>
</div>


<div class="row">
   <div class="col-sm-3">
      <label>Subscribed From : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['from_which_place']; ?>
      </label>
   </div>
</div>