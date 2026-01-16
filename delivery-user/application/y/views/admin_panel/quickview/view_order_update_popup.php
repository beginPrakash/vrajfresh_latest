<style>
.select2-container--open .select2-dropdown--below{z-index:9999999999}
</style>

<?php 
$order_status = $ArrFieldData['order_status'];
$editAllow = "No";
if($order_status=='Pending Payment' || $order_status=='Processing') { 
$editAllow = "Yes";
}

  $attributes = array('class' => 'form-horizontal', 'id' => 'banner_frm', 'role' => 'form', 'enctype' => 'multipart/form-data');
  echo form_open('update-order-process/', $attributes);
  ?>
<input type="hidden" name="order_id" value="<?php echo $ArrFieldData['order_id'];  ?>" />
<input type="hidden" name="order_total_tax" value="<?php echo $ArrFieldData['order_total_tax'];  ?>" >
<input type="hidden" name="product_counter" id="product_counter" value="0" />
<div class="row">
   <div class="col-sm-4">
      <label>Order ID : </label>
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['order_id'];  ?></label>
   </div>
   <div class="col-sm-4">
      <label>Order Date : </label>
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['order_datetime'];  ?></label>
   </div>
   <div class="col-sm-4">
      <label>Order Amount : </label>
      <label  style="font-weight:400; line-height: 20px;">$<?php echo $ArrFieldData['order_total_amount'];  ?></label>
   </div>
   <?php if($ArrFieldData['order_notes']!='') { ?>
   <div class="col-sm-12">
      <label>Order Notes : </label>
      <label  style="font-weight:400; line-height: 20px;"><?php echo $ArrFieldData['order_notes'];  ?></label>
   </div>
   <?php } ?>
   <div class="col-sm-12">
      <label style="font-weight:400; line-height: 20px;">
	  <?php
	  if($ArrFieldData['is_replace_item']==1){ echo 'Yes, please substitute unavailable items with similar products'; }
	  else { echo 'No, please refund for unavailable items'; }
	  ?>
	  </label>
   </div>
</div>

<div class="row">
   <div class="col-sm-12">
      <label><h4>Order Products</h4></label>
   </div>
</div>
<div class="row">
   <div class="col-sm-12">
      <?php
      if(is_array($ArrOrderProduct) && count($ArrOrderProduct)>0)
      {
      ?>
         <table border="1" width="100%" id="product">
            <thead>
            <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price (in $)</th>
            <th>Product Tax (in %)</th>
            <th>Total (in $)</th>
            <th>
               <?php if($editAllow=='Yes') { ?>
				<a href="javascript:void(0);" onClick="addProductRow()" title="Click here to add new product">
                  <img src="<?php echo admin_media(); ?>dist/img/plus.png">
               </a>
			   <?php } ?>
            </th>
             </tr>
      </thead>
      <tbody>
         <?php
         $total_qty = $net_total = 0;
        //  print_r($ArrOrderProduct);
         foreach($ArrOrderProduct as $arr) { 
            $total_qty += $arr['qty'];
            $net_total += $arr['total_amount'];
         ?>
               <tr>
               <td>
                  <input type="hidden" name="ArrOrderProductIds[]" value="<?php echo $arr['order_product_id']; ?>">
                  <?php echo $arr['product_name']; ?>
               </td>
               <td>
                  <input type="text" placeholder="QTY" class="form-control qty" id="qty<?php echo $arr['order_product_id']; ?>" name="qty[<?php echo $arr['order_product_id']; ?>]" value="<?php echo $arr['qty']; ?>" required onChange="updateProductTotalAmount(this.value,<?php echo $arr['order_product_id']; ?>);">
               </td>
               
               <td>
                  <input type="text" placeholder="Unit Price" class="form-control" id="unit_price<?php echo $arr['order_product_id']; ?>" name="unit_price[<?php echo $arr['order_product_id']; ?>]" value="<?php echo $arr['unit_price']; ?>" required readonly>
               </td>

               <td>
                  <input type="text" placeholder="product tax" class="form-control" id="product_tax<?php echo $arr['order_product_id']; ?>" name="product_tax[<?php echo $arr['order_product_id']; ?>]" value="<?php echo $arr['product_tax']; ?>" required readonly>
               </td>
               
               <td>
                  <input type="text" placeholder="Total Amount" class="form-control total_amount" id="total_amount<?php echo $arr['order_product_id']; ?>" name="total_amount[<?php echo $arr['order_product_id']; ?>]" value="<?php echo $arr['total_amount']; ?>" required readonly>
               </td>
               <td>
				<?php if($editAllow=='Yes') { ?>
					<a href="javascript:void(0);" class="remove-button" title="Click here to remove product" data="<?php echo $arr['order_product_id']; ?>">
					<img src="<?php echo admin_media(); ?>dist/img/close-2.png">
					</a>
				<?php } ?>
            </td>
            </tr>
         <?php } ?>
         </tbody>
         <tfoot>
            <tr>
               <td>Total:</td>
               <td id="divQTYTotal" style="padding: 6px 13px;"><?php echo   $total_qty; ?></td>
               <td style="padding: 6px 13px;">--</td>
               <td style="padding: 6px 13px;">--</td>
               <td id="divNetTotal" style="padding: 6px 13px;"><?php echo $net_total; ?></td>
         </tr>
         </tfoot>
         </table>
						
	  <?php } ?>
   </div>
   </div>
   <div class="row">

      <div class="col-sm-4">
         <!--<select class="form-control select_product_ajax" style="width: 100%;" name="ddIsActive" id="ddIsActive">
						<option value="1">one</option>
						<option value="2">two</option>
						<option value="3">three</option>
                  </select>-->
         <label>Shipping Charge : </label>
		 
         <input type="text" placeholder="Shipping Charge" class="form-control" name="shipping_charge" id="shipping_charge" value="<?php echo $ArrFieldData['fedex_shipping_charge'];  ?>" onChange="updateFooterTotal();" required <?php if($editAllow=='No') { echo 'readOnly'; }?>>
		 
      </div>

   <div class="col-sm-4">
      <label>Tip Amount : </label>
      <input type="text" placeholder="Order Tip" class="form-control" name="order_tip" id="order_tip" value="<?php echo $ArrFieldData['order_tip'];  ?>" onChange="updateFooterTotal();" required <?php if($editAllow=='No') { echo 'readOnly'; }?> >
   </div>
   
   
   <div class="col-sm-4">
      <label>Order Discount : </label>
      <input type="text" placeholder="Order Discount" class="form-control" name="discount_amount" id="discount_amount" value="<?php echo $ArrFieldData['discount_amount'];  ?>" onChange="updateFooterTotal();" required <?php if($editAllow=='No') { echo 'readOnly'; }?> >
   </div>
   
   <div class="col-sm-4">
      <label>Preparation Cost: </label>
      <input type="text" placeholder="Preparation Cost" class="form-control" value="<?php echo $ArrFieldData['preparation_cost'];  ?>" onChange="updateFooterTotal();" required readonly >
   </div>

   <div class="col-sm-4">
      <label>Packaging Cost: </label>
      <input type="text" placeholder="Order Discount" class="form-control" name="discount_amount" id="discount_amount" value="<?php echo $ArrFieldData['packaging_cost'];  ?>" onChange="updateFooterTotal();" required readonly>
   </div>

   <div class="col-sm-4">
      <label>State Tax:(in %)</label>
      <input type="text" placeholder="State Tax" class="form-control" name="discount_amount" id="discount_amount" value="<?php echo $ArrFieldData['state_tax'];  ?>" onChange="updateFooterTotal();" required readonly>
   </div>

   <div class="col-sm-4">
      <label>Order Status : </label>
	  <?php if($editAllow=='No') { 
	  echo $order_status;
	  }
	  else
	  {
		  echo form_dropdown('order_status', $ArrOrderStatus, $order_status, 'id="order_status" class="form-control"'); 
	  }
	  ?>
   </div>

</div>


<div class="row">
   <div class="col-sm-12">
      <br>
      <button type="submit" class="btn btn-default" name="submit" id="submit" value="Submit">Update Order</button>
   </div>
</div>
                           
</form>
<?php if($order_status=='Processing') { ?>
<?php
  $attributes = array('class' => 'form-horizontal', 'id' => 'paymentFrm', 'role' => 'form', 'enctype' => 'multipart/form-data');
  echo form_open('order-payment-capture');
  ?>
      <br>
<div class="row">
	<div class="col-sm-4">
		<input type="hidden" name="payment_intent_id" value="<?php echo $ArrFieldData['payment_intent_id']; ?>" />
		<input type="hidden" name="order_id" value="<?php echo $ArrFieldData['order_id']; ?>" />
		<input type="hidden" name="stripeToken" value="<?php echo $ArrFieldData['payment_intent_id']."-".$ArrFieldData['order_id']; ?>" />
		<input type="text" class="form-control" name="amount_to_capture" id="amount_to_capture" value="<?php echo $ArrFieldData['order_total_amount']; ?>" />
	</div>
	<div class="col-sm-4">
		<button type="submit" id="payBtn" class="btn btn-success">Capture Payment</button>
	</div>
</div>
</form>  
<?php } ?>
<script>
$( document ).ready(function() {
   $("#product").on('click', '.remove-button', function(e) {
      if (confirm("Do you want to remove order product?") == true) {
         RemoveProductFromOrder($(this).attr("data"));
         var whichtr = $(this).closest("tr");
         whichtr.remove();  
         var product_counter = parseInt($("#product_counter").val());
         product_counter = product_counter -1;
         $("#product_counter").val(product_counter);   
      }
   });
});
function addProductRow()
{
   var product_counter = parseInt($("#product_counter").val());
   product_counter = product_counter + 1;
   $("#product_counter").val(product_counter);
   var data = '<tr><td><select name="ArrNewProducts[]" id="" onChange="SetPrice(this.value,'+product_counter+');" class="form-control select_product_ajax" required></select></td><td><input type="text" placeholder="QTY" class="form-control" id="qty'+product_counter+'" name="ArrNewQty[]" value="" required onChange="updateProductTotalAmount(this.value,'+product_counter+');"></td><td><input type="text" placeholder="Unit Price" class="form-control" id="unit_price'+product_counter+'" name="ArrNewUnit_price[]" value="" required readonly></td><td><input type="text" placeholder="Total Amount" class="form-control total_amount" id="total_amount'+product_counter+'" name="ArrNewTotal_amount[]" value="" required readonly></td><td><a href="javascript:void(0);" class="remove-button" title="Click here to remove product"><img src="<?php echo admin_media(); ?>dist/img/close-2.png"></a></td></tr>';
   //$('#product').append(data);
   $.ajax({
   type: "POST",
   url: "<?php echo site_url(); ?>getCartProductRow/"+product_counter,
   success: function (data) {
   $('#product').append(data);
	$('.select_product_ajax').select2();
   //ajax_page_drop_down();
   }
   });
}
function RemoveProductFromOrder(order_product_id)
{
   $.ajax({
   type: "POST",
   url: "<?php echo site_url(); ?>remove-order-process/"+order_product_id,
   success: function (data) {
      updateFooterTotal();
   }
   });  
}
function SetPrice(val,product_counter)
{
   const ArrProductDetails = val.split("|");
   //Format: product_id-variant_id-variant_price-product_name
   $("#qty"+product_counter).val(1);
   $("#unit_price"+product_counter).val(ArrProductDetails[2]);
   $("#total_amount"+product_counter).val(parseFloat(1 * ArrProductDetails[2]));
   updateFooterTotal();
}

function updateProductTotalAmount(val,product_counter)
{
   $("#total_amount"+product_counter).val(parseFloat(val * $("#unit_price"+product_counter).val()));
   updateFooterTotal();
}
function updateFooterTotal()
{
   var QTYTotal = 0;
   var NetTotal = 0;
   $('.qty').each(function(){
      QTYTotal = QTYTotal + parseInt($(this).val());
   });
   $('.total_amount').each(function(){
      NetTotal = NetTotal + parseFloat($(this).val());
	  //alert($(this).val());
   });
   $("#divQTYTotal").html(QTYTotal);
   //$("#divNetTotal").html(NetTotal);
   //NetTotal = Math.round(NetTotal).toFixed(2);
   $("#divNetTotal").html(NetTotal.toFixed(2));
   //update capture amount
	var total = (parseFloat(NetTotal.toFixed(2)) + parseFloat($("#shipping_charge").val()) + parseFloat($("#order_tip").val()));
	var capture_total = total-parseFloat($("#discount_amount").val());
   $("#amount_to_capture").val(capture_total);
}
</script>