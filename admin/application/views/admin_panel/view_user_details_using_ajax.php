<div class="row">
   <div class="col-sm-6">
      <div class="row">
         <div class="col-sm-3">
            <label>User Type:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['user_role_name']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>User Name:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['user_name']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Email:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['email']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Joining Date:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo date(DATE_FORMAT, strtotime($ArrFieldData['created_datetime'])); ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Name:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['name']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Address1:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['address']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Address2:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['address2']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>City:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['city']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>State:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['state']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Country:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['country_name']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Zip:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['zip']; ?>
            </label>
         </div>
      </div>

   </div>
   <div class="col-sm-6">
      <div class="row">
         <div class="col-sm-3">
            <label>Phone:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['phone']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Mobile:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['mobile_no']; ?>
            </label>
         </div>
      </div>

      <div class="row">
         <div class="col-sm-3">
            <label>Birth Date:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo date(DATE_FORMAT, strtotime($ArrFieldData['birth_date'])); ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Anniversary:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo date(DATE_FORMAT, strtotime($ArrFieldData['anniversary_date'])); ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Qualification:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['qualification']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Experience:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['experience']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Skills:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['skills']; ?>
            </label>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-3">
            <label>Active:</label>
         </div>
         <div class="col-sm-9">
            <label style="font-weight:400; line-height: 20px;">
               <?php echo ($ArrFieldData['is_active'] == '1') ? 'Yes' : 'No'; ?>
            </label>
         </div>
      </div>
   </div>
</div>