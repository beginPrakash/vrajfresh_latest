<style>
   .select2-container--open .select2-dropdown--below {
      z-index: 9999999999
   }
</style>

<?php
$order_status = $ArrFieldData['order_status'];
$editAllow = "No";
if ($order_status == 'Pending Payment' || $order_status == 'Processing') {
   $editAllow = "Yes";
}

unset($ArrOrderStatus['Payment Processed']);
$is_payment_received = false;
if(isset($ArrFieldData['amount_received_status']) && $ArrFieldData['amount_received_status']=='succeeded')
{

	// $is_payment_received = true;
	
   // $editAllow = "No";
	
   // Pending Payment and Processing status delete
	
   /* unset($ArrOrderStatus['Pending Payment']);
	
   unset($ArrOrderStatus['Processing']); */
}
if($order_status == 'Pending Payment')
{
$is_payment_received = true;
}
?>
<a href="<?php echo SITE_URL ?>home" class="btn btn-primary" style="float:right">Back</a>
<form class="form-horizontal" action="<?php echo site_url(); ?>update-order-process/" id="banner_frms" role="form" enctype="multipart/form-data" method="post"
   accept-charset="utf-8">
   <input type="hidden" name="order_id" value="<?php echo $ArrFieldData['order_id']; ?>" />
   


   
         <?php if ($ArrFieldData['delivery_comments'] != '') { ?>
            <div class="col-sm-12 user_highlight_notes">
               <label>Delivery Comments By Admin :</label>
                  <p><?php echo $ArrFieldData['delivery_comments']; ?></p>
            </div>
         <?php } ?>
         <br>

      <?php if ($ArrFieldData['delivery_user_comment'] != '') { ?>
         <div class="col-sm-12 user_highlight_notes">
            <label>Delivery Comments By User :</label>
            <p><?php echo $ArrFieldData['delivery_user_comment']; ?></p>
         </div>
      <?php } ?>

      

      <div class="col-sm-12">
         <label>Shipping Address : </label>
         <?php $shipping_emails = empty($ArrFieldData['shipping_email']) ?  $ArrUser['email'] : $ArrFieldData['shipping_email']; ?>
         <div>
            <?php echo 'Name:' . $ArrFieldData['shipping_first_name'] . ' ' . $ArrFieldData['shipping_last_name'] . '<br>' . $ArrFieldData['shipping_street_name'] . ' ' . $ArrFieldData['shipping_apartment_name'] . ' , ' . $ArrFieldData['shipping_city'] . ' , ' . $ArrFieldData['shipping_state_name'] . ' , ' . $ArrFieldData['shipping_zipcode'] . '<br> Email: ' . $shipping_emails . '<br>Contact No. ' . $ArrFieldData['shipping_phone']; ?>
         </div>
      </div>
        
      <div class="col-sm-12"> <label> </label></div>
      <div class="col-sm-12">
         <label>Order ID : </label>
         <label style="font-weight:400; line-height: 20px;">
            <?php echo $ArrFieldData['order_id']; ?>
         </label>
      </div>
      <div class="col-sm-12">
         <label>Order Date : </label>
         <label style="font-weight:400; line-height: 20px;">
            <?php echo $ArrFieldData['created_datetime']; ?>
         </label>
      </div>

      <?php if ($ArrFieldData['order_notes'] != '') { ?>
         <div class="col-sm-4">
            <label>Order Notes : </label>
            <label style="font-weight:400; line-height: 20px;">
               <?php echo $ArrFieldData['order_notes']; ?>
            </label>
         </div>
      <?php } ?>

      <div class="col-sm-12">
         <div>
            <label>Order Photo : </label>
            <?php if ($ArrFieldData['delivery_attachment'] != '') { ?>
               <a href="<?php echo API_URL; ?>admin/uploads/products/<?php echo $ArrFieldData['delivery_attachment'] ?>"><img height="500px" width="500px" src='<?php echo API_URL; ?>admin/uploads/products/<?php echo $ArrFieldData['delivery_attachment'] ?>' border=0 target="_blank"></a>
               <input type="file" class="" name="delivery_attachment" accept="image/*" data-type="image">
            <?php } else { ?>
               <input type="file" class="form-control" name="delivery_attachment" accept="image/*" data-type="image">
            <?php } ?>
            <?php echo form_error('delivery_attachment'); ?>
         </div>
      </div>



   <?php if($ArrFieldData['order_status'] == 'Out For Delivery'){ ?>
      <div class="row">
         <div class="col-sm-12">
            <br>
            <button type="button" class="btn btn-default" name="submit" id="submit" value="Submit">Update Order</button>
         </div>
      </div>

   <?php } ?>
   

</form>


<script>

   $("#submit").click(function (e) {
    e.preventDefault();

    let isValid = true;
    $(".error").remove();

    // REQUIRED image validation
    let image = $("input[name='delivery_attachment']")[0].files.length;
    if (image === 0) {
        isValid = false;
        $("input[name='delivery_attachment']")
            .after('<span class="error text-danger">Image is required</span>');
    }

    if (!isValid) return false;

    // CONFIRMATION ALERT
    if (!confirm("Are you sure you want to complete this order?")) {
        return false; // user clicked Cancel
    }

    // Submit via AJAX
    var formData = new FormData(document.getElementById('banner_frms'));

    $.ajax({
        url: '<?php echo site_url(); ?>update-order-process/',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (data) {
            toastr.success('Order has been updated successfully!');
            location.reload();
        }
    });
});


</script>
