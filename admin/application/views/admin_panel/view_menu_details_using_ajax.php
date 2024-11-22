<div class="row">

   <div class="col-sm-3">

      <label for="exampleInputEmail1">Created Date : </label>

   </div>

   <div class="col-sm-9">

      <label style="font-weight:400; line-height: 20px;">

         <?php echo date(DATE_FORMAT, strtotime($ArrFieldData['created_datetime'])) ?>

      </label>

   </div>

</div>

<div class="row">

   <div class="col-sm-3">

      <label for="exampleInputEmail1">Menu Name : </label>

   </div>

   <div class="col-sm-9">

      <label style="font-weight:400; line-height: 20px;">

         <?php echo $ArrFieldData['menu_name'] ?>

      </label>

   </div>

</div>

<div class="row">

   <div class="col-sm-3">

      <label for="exampleInputEmail1">Menu Items : </label>

   </div>

   <div class="col-sm-9">

      <label style="font-weight:400; line-height: 20px;">

         <?php

         if (is_array($ArrMenuItemsData) && count($ArrMenuItemsData) > 0) {

            foreach ($ArrMenuItemsData as $value) {

               ?>

               <a href="<?php echo $value['menu_link']; ?>" target="_blank"><?php echo $value['category_name']; ?></a><br />

               <?php

            }

         } //End if 

         ?>

      </label>

   </div>

</div>

<div class="row">

   <div class="col-sm-3">

      <label for="exampleInputEmail1">Is Active : </label>

   </div>

   <div class="col-sm-9">

      <label style="font-weight:400; line-height: 20px;">

         <?php echo ($ArrFieldData['is_active'] == '1') ? 'Yes' : 'No'; ?>

      </label>

   </div>

</div>