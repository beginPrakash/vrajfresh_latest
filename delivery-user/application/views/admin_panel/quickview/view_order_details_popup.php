<div class="row">
   <div class="col-sm-3">
      <label>Customer Name : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['display_name'] ?>
      </label>
   </div>
</div>


<div class="row">
   <div class="col-sm-3">
      <label>Email : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['email'] ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Mobile : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['mobile_no'] ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Address : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['address'] ?>
      </label>
   </div>
</div>
<div class="row">
   <div class="col-sm-3">
      <label>Address : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['address2'] ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>City : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo $ArrFieldData['city'] ?>
      </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-3">
      <label>Is Active : </label>
   </div>
   <div class="col-sm-9">
      <label style="font-weight:400; line-height: 20px;">
         <?php echo ($ArrFieldData['is_active'] == '1') ? 'Yes' : 'No'; ?>
      </label>
   </div>
</div>
<div class="row">
   <div class="col-sm-12">
      <label>
         <h4>Order Products</h4>
      </label>
   </div>
</div>
<div class="row">
   <div class="col-sm-12">
      <?php
      if (is_array($ArrOrderProduct) && count($ArrOrderProduct) > 0) {
         ?>
         <table border="1" width="100%">
            <tr>
               <th>#</th>
               <th>Product Name</th>
               <th>Quantity</th>
               <th>Price (in $)</th>
               <th>Total (in $)</th>
            </tr>
            <?php $i = 1;
            foreach ($ArrOrderProduct as $arr) { ?>
               <tr>
                  <th>
                     <?php echo $i++; ?>
                  </th>
                  <th>
                     <?php echo $arr['product_name']; ?>
                  </th>
                  <th>
                     <?php if ($arr['old_qty'] > 0) { ?><del>
                        <?php echo $arr['old_qty']; ?>
                     </del>&nbsp;
                  <?php } ?>
                     <?php echo $arr['qty']; ?>
                  </th>
                  <th>
                     <?php echo $arr['unit_price']; ?>
                  </th>
                  <th>
                     <?php echo $arr['total_amount']; ?>
                  </th>
               </tr>
            <?php } ?>
         </table>
      <?php } ?>
   </div>
</div>


<div class="row">

   <div class="col-sm-4">
      <label>Order Status : </label>
      <?php echo $ArrFieldData['order_status']; ?>
   </div>

   <div class="col-sm-4">
      <label>Shipping Charge : </label>
      $
      <?php echo $ArrFieldData['fedex_shipping_charge']; ?>
   </div>

   <div class="col-sm-4">
      <label>Tip Amount : </label>
      $
      <?php echo $ArrFieldData['order_tip']; ?>
   </div>

   <div class="col-sm-4">
      <label>Tax : </label>
      $
      <?php echo $ArrFieldData['order_total_tax']; ?>
   </div>

   <div class="col-sm-4">
      <label>Order Discount : </label>
      $
      <?php echo $ArrFieldData['discount_amount']; ?>
   </div>
</div>