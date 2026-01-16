<div class="row">
   <div class="col-sm-3">
      <label>Display Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['display_name']  ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>First Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['first_name']  ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Last Name : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['last_name']  ?></label>
   </div>
</div>


<div class="row">
   <div class="col-sm-3">
      <label>Email : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['email'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Mobile : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['mobile_no'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Address 1 : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['address'] ?></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Address 2 : </label>
   </div>
   <div class="col-sm-9">
   <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['address2'] ?></label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>City : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['city'] ?></label>  
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>State : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['state'] ?></label>  
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>ZIP Code : </label>
   </div>
   <div class="col-sm-9">
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['zip'] ?></label>  
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