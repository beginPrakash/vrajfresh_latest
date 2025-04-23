<?php 
require_once('common/header.php');

$first_name = ($_COOKIE['first_name'] != 'null') ? $_COOKIE['first_name'] : "";
$last_name  = ($_COOKIE['last_name'] != 'null') ? $_COOKIE['last_name'] : "";
$shipping_street_address  = ($_COOKIE['shipping_street_address'] != 'null') ? $_COOKIE['shipping_street_address'] : "";
$shipping_apartment  = ($_COOKIE['shipping_apartment'] != 'null') ? $_COOKIE['shipping_apartment'] : "";
$shipping_city  = ($_COOKIE['shipping_city'] != 'null') ? $_COOKIE['shipping_city'] : "";
$zipcode  = ($_COOKIE['zipcode'] != 'null') ? $_COOKIE['zipcode'] : "";
$shipping_phone = ($_COOKIE['shipping_phone'] != 'null') ? $_COOKIE['shipping_phone'] : "";
$shipping_state = "";
if ($_COOKIE['delivery_state_id'] != 'null') {
   $shipping_state = $_COOKIE['delivery_state_id'];
} else if ($_COOKIE['shipping_state'] != 'null') {
   $shipping_state = $_COOKIE['shipping_state'];
}
?>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<style>
   .StripeElement {
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin: 5px 0;
    }
    .red-link {
    width: 100%;
    background: #45629f !important;
    color: #fff !important;
    height: 40px !important;
    border: none;
    font-size: 16px;
    border-radius: 30px;
    margin: 0 auto;
    padding: 10px 80px;
    width: 180px;
    display: block;
}

   .cash_cr_text{
      color: green;
      font-weight: 600;
    }
</style>
<section class="categories-banner">
   <h2>CHECKOUT</h2>
</section>
<style>
   .error {
   color: red;
   }
   .social-login-item{
    overflow-y: scroll;
    height: 600px;
   }
</style>
<?php
   $action = BASE_URL . 'checkout-process';
   
   $attributes = array('class' => '', 'id' => 'checkoutForm', 'role' => 'form', 'enctype' => 'multipart/form-data');
   
   echo form_open($action, $attributes);
   
   $this->load->config('config');
   
   ?>
<section class="billing-page checkout-page">
   <div class="container container-flex">
      <div class="col-md-4">
         <div class="billing-left" id="shippingData">
            <h3>SHIPPING ADDRESS</h3>
            <span id="shipping-address-error" class="error"></span>
            <div class="add-address">
               <a href="javascript:void(0);" id="AddShippingAddressbtn">Add New Address</a>
               <input type="hidden" name="shipping_address_count" id="shipping_address_count" value="<?php echo $shipping_address_count; ?>" >
            </div>
		      <div class="your-address">
			      <span>Your Addresses</span>
			      <?php if(!empty($shipping_address)){ 
                  for($i = 0; $i < count($shipping_address); $i++){ ?>
				         <div class="address-box">
				            <input type="radio" name="shipping_id" id="shipping_id<?php echo $i; ?>" data-zipcode="<?php echo $shipping_address[$i]['shipping_zipcode']; ?>" data-tax="<?php echo $shipping_address[$i]['tax']; ?>" data-state="<?php echo $shipping_address[$i]['shipping_state_id']; ?>" value="<?php echo $shipping_address[$i]['shipping_id']; ?>">
                        <div class="detail-box">
					            <p><b><?php echo $shipping_address[$i]['first_name'] . ' '. $shipping_address[$i]['last_name']; ?></b></p>
                           <?php $address = '';
                           $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['shipping_street_address'] : $address = $shipping_address[$i]['shipping_street_address'];
                           $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['shipping_apartment'] : $address = $shipping_address[$i]['shipping_apartment'];
                           $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['shipping_city'] : $address = $shipping_address[$i]['shipping_city'];
                           $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['state_name'] : $address = $shipping_address[$i]['state_name']; ?>
                           <p><?php echo $address; ?></p>
				            </div>
				            <div class="add-edit">
					            <p>
                              <a href="javascript:void(0);" data-id="<?php echo $shipping_address[$i]['shipping_id']; ?>" class="edit_shipping_data_btn" >
                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="25" height="25" viewBox="0 0 25 25">
                                    <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAiCAMAAADiW5DOAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABiVBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADwwLPBAAAAgnRSTlMAT9rkZQQhMBFpiBue8e4L00nAOu3eCHjJDwaylwEX7O9yIxADjvUth8OlfeGL1RWs/lOWz/3XHFBb3EL2vKMFVPokTiKvmPv5Crq+wnzKZJXFgauokY+2efRinHNgDCU5UWewNp9A28FrSx8J/FdIneV+PfhKMjGS4xPY4Cx20NQNoV6DzAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAG0SURBVDjLjdNpV9NAGIbhV4qgbFIWkdKqQFEsoKAUsNSlRaUBFAEXFq0LoIIWVGRzA+5fbrNMJwktx+fDnMzJdTKTeRKRUjlVRqBcT09XVFo54yJnMVPlzKprarFTd85N6oMNCjU2odJ8XpEWuNAqoTYIR0QuXuJye0enlaiXiEJdXLnq3243XLOXjcagR3rp85Oq/LLXb9jX0X7qZICbXhG5BYNxhobV5gcERrwkDG2h23ESFhpNcsdvonchdk/kformtMhYksADnzH3GLNebyxFIG0N4jWhh/DIOYFgkqZxEhXiNerM7IxmcDblMsYE9BWIlMeprxavMSZ1h05jziEVTGgKHmvSDk8cos00tGiiGvOap8xoMgsNBaLNHM/cjU0actzM8FzV8QJeuog2Zcy7G5NiZoFFu44lmFMk8uq1yxgQzL55++79smrMzApktemAceezXi0Q+QCd2gzHrfsLHz+t6Tp8Rhqn11c+fxFvfKZo/t/k2DjBbEL+Jxri6wmmi2/58Tu5zZJk64f192UT5MLbO5XHsxvcy7D/07TpX5TO7z/2A42/B5miIHXYc2SCfx+PrwcAjj9tAAAAAElFTkSuQmCC" width="25" height="25"/>
                                 </svg>
                              </a>
                              <a href="javascript:void(0);" data-id="<?php echo $shipping_address[$i]['shipping_id']; ?>" class="delete_shipping_data_btn" >
                                 <button type="button" class="remove_inventory remove_add_btn error">X
                                 </button>
                              </a>
                           </p>
					         </div>
				         </div>
			         <?php } 
               } ?>
		      </div>
         </div>

         <div class="billing-left" id="billingData">
            <h3>BILLING ADDRESS</h3>
            <span id="billing-address-error" class="error"></span>
            <div class="add-address">
               <a href="javascript:void(0);" id="AddBillingAddressBtn">Add New Address</a>
               <input type="hidden" name="billing_address_count" id="billing_address_count" value="<?php echo $billing_address_count; ?>" >
            </div>
            <div class="your-address">
               <!-- <div class="same-shipping-add">
                  <a href="javascript:void(0);" id="SameShippingAddressBtn">Same As Shipping Address</a>
               </div> -->
               <div class="address-box">
                  <input type="radio" name="billing_id" id="billing_id_" value="same_address">
                  <div class="detail-box">
                     <p><b>Same As Shipping Address</b></p>
                  </div>
               </div>
               <?php if(!empty($billing_address)){ 
                  for($i = 0; $i < count($billing_address); $i++){ ?>
                  <div class="address-box">
                     <input type="radio" name="billing_id" id="billing_id<?php echo $i; ?>" value="<?php echo $billing_address[$i]['billing_id']; ?>">
                     <div class="detail-box">
                        <p><b><?php echo $billing_address[$i]['first_name'] . ' '. $billing_address[$i]['last_name']; ?></b></p>
                        <?php $address = '';
                        $address = ($address != "") ? $address .= ", ".$billing_address[$i]['billing_street_address'] : $address = $billing_address[$i]['billing_street_address'];
                        $address = ($address != "") ? $address .= ", ".$billing_address[$i]['billing_apartment'] : $address = $billing_address[$i]['billing_apartment'];
                        $address = ($address != "") ? $address .= ", ".$billing_address[$i]['billing_city'] : $address = $billing_address[$i]['billing_city'];
                        $address = ($address != "") ? $address .= ", ".$billing_address[$i]['state_name'] : $address = $billing_address[$i]['state_name']; ?>
                        <p><?php echo  $address; ?></p>
                     </div>
                     <div class="add-edit">
                        <p>
                           <a href="javascript:void(0);" data-id="<?php echo $billing_address[$i]['billing_id']; ?>" class="edit_billing_data_btn" >
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="25" height="25" viewBox="0 0 25 25">
                                 <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAiCAMAAADiW5DOAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABiVBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADwwLPBAAAAgnRSTlMAT9rkZQQhMBFpiBue8e4L00nAOu3eCHjJDwaylwEX7O9yIxADjvUth8OlfeGL1RWs/lOWz/3XHFBb3EL2vKMFVPokTiKvmPv5Crq+wnzKZJXFgauokY+2efRinHNgDCU5UWewNp9A28FrSx8J/FdIneV+PfhKMjGS4xPY4Cx20NQNoV6DzAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAG0SURBVDjLjdNpV9NAGIbhV4qgbFIWkdKqQFEsoKAUsNSlRaUBFAEXFq0LoIIWVGRzA+5fbrNMJwktx+fDnMzJdTKTeRKRUjlVRqBcT09XVFo54yJnMVPlzKprarFTd85N6oMNCjU2odJ8XpEWuNAqoTYIR0QuXuJye0enlaiXiEJdXLnq3243XLOXjcagR3rp85Oq/LLXb9jX0X7qZICbXhG5BYNxhobV5gcERrwkDG2h23ESFhpNcsdvonchdk/kformtMhYksADnzH3GLNebyxFIG0N4jWhh/DIOYFgkqZxEhXiNerM7IxmcDblMsYE9BWIlMeprxavMSZ1h05jziEVTGgKHmvSDk8cos00tGiiGvOap8xoMgsNBaLNHM/cjU0actzM8FzV8QJeuog2Zcy7G5NiZoFFu44lmFMk8uq1yxgQzL55++79smrMzApktemAceezXi0Q+QCd2gzHrfsLHz+t6Tp8Rhqn11c+fxFvfKZo/t/k2DjBbEL+Jxri6wmmi2/58Tu5zZJk64f192UT5MLbO5XHsxvcy7D/07TpX5TO7z/2A42/B5miIHXYc2SCfx+PrwcAjj9tAAAAAElFTkSuQmCC" width="25" height="25"/>
                              </svg>
                           </a>
                           <a href="javascript:void(0);" data-id="<?php echo $billing_address[$i]['billing_id']; ?>" class="delete_billing_data_btn" >
                                 <button type="button" class="remove_inventory remove_add_btn error">X
                                 </button>
                           </a>
                        </p>
                     </div>
                  </div>
               <?php } } ?>
            </div>
         </div>
         <div class="billing-left" id="CardData">
            <h3>Payment</h3>
            <span>All transactions are secure and encrypted.</span>
            </br>
         <div>
			<div class="add-card">
				<input type="radio" name="card_id" id="card_id_" value="0" <?php if($card_count == 0){ echo 'CHECKED'; } ?>> <label>Add Another Credit Card</label>
				<input type="hidden" name="card_count" id="card_count" value="<?php echo $card_count; ?>" >
			</div>
            <select name="state" id="state" style="display:none;" data-tax="">
               <?php
                  $tax_percentage = 0;
                  
                  foreach ($ArrStateOption as $key => $state_data) {
                  
                  	$ArrStateData = explode('|', $key);
                  
                  	$billing_state_id = $ArrStateData[1];
                  
                  	if ($billing_state_id == $shipping_state || $state_data == $shipping_state) {
                  
                  	$tax_percentage = $ArrStateData[0];
                  
                  	}
                  
                  	?>
               <option value="<?php echo $key; ?>" data-id="<?php echo $billing_state_id; ?>" <?php if ($billing_state_id == $shipping_state || $state_data == $shipping_state) {
                  echo 'selected';
                  
                  } ?>><?php echo $state_data; ?></option>
               <?php } ?>
            </select>
         </div>
         <div id="card-element" class="StripeElement"  role="group" aria-labelledby="card-label" <?php if($card_count > 0){ ?> style="display:none;" <?php } ?>>
         <!-- A Stripe Element will be inserted here. -->
         </div>
         <!-- Used to display form errors. -->
         <div id="card-errors" role="alert" aria-live="assertive" class="error"></div>
         <div class="card-form" <?php if($card_count > 0){ ?> style="display:none;" <?php } ?>> 
         
            <input type="hidden" name="CardToken" id="CardToken" />
            <input type="hidden" name="StripeCardID" id="StripeCardID" />
            <input type="hidden" name="CardPaymentMethod" id="CardPaymentMethod" />
            
            <br>
            <input type="checkbox" name="save_card" id="save_card" value="1" /> <lable for="save_card">Save my information for a faster checkout </lable>
         </div>
         <hr><br>
         
         <?php if(!empty($cards)){ ?>
         <?php for($i = 0; $i < count($cards); $i++){ ?>
            <div class="type-box">
               <input type="radio" name="card_id" id="card_id<?php echo $i; ?>" value="<?php echo $cards[$i]['card_id']; ?>" <?php if($cards[$i]['card_id'] == $card_id){ echo 'CHECKED'; } ?>>
               <p><?php echo $cards[$i]['card_brand']. " - " . $cards[$i]['card_holder'] ." ". $cards[$i]['card_no']; ?></p>
            </div>
         <?php } ?>

         <?php } ?>
      </div>

      <div class="billing-left">
         <div id="shipingAddressBox" style="display:none;">
            <div>
               <label>Billing Street address <small title="required">*</small>
               </label>
               <input placeholder="House number and street name" value="<?php echo $shipping_street_address; ?>"
                  name="shiping_street_address" id="shiping_street_address" type="text" maxlength="500">
               <span id="shiping-street-address-error" class="error"></span>
               <input placeholder="Apartment, suite, unit, etc. (optional)" value="<?php echo $shipping_apartment; ?>"
                  name="shiping_apartment" id="shiping_apartment" type="text" maxlength="500">
                  <span id="shiping-street-address2-error" class="error"></span>
            </div>
            <div>
               <label>Billing Town / City <small title="required">*</small>
               </label>
               <input placeholder="City" value="<?php echo $shipping_city; ?>" name="shiping_city" id="shiping_city"
                  type="text" maxlength="100">
               <span id="shiping-city-error" class="error"></span>
            </div>
            <div><label>Billing State <small title="required">*</small>
               </label>
               <?php echo form_dropdown('shiping_state', $ArrBillingStateOption, $state, 'id="shiping_state" '); ?>
               <span id="shiping-state-error" class="error"></span>
            </div>
            <div><label>Billing Postcode / ZIP <small title="required">*</small>
               </label>
               <input placeholder="Zip Code" value="<?php echo $zipcode; ?>" name="shiping_zip_code"
                  id="shiping_zip_code" type="text" maxlength="100">
               <span id="zip-error" class="error"></span>
            </div>
            <div><label>Billing Mobile <small title="required">*</small>
               </label>
               <input placeholder="Mobile" value="<?php echo $shipping_phone; ?>" name="shiping_phone" id="shiping_phone"
                  type="text" class="numberonly" maxlength="10">
               <span id="shiping-phone-error" class="error"></span>
            </div>
         </div>
         <?php if ($_COOKIE['delivery_type'] == 'Express Delivery' || $_COOKIE['delivery_type'] == 'Same Day Delivery') { ?>
		   <h3>Delivery Type</h3>
         <div>
            <!-- <input name="delivery_type" id="delivery_type_hour" type="radio" value="two_hour" class="chk_delivery_type"> -->
            <!-- <label for="delivery_type_hour" id="delivery_after_hr"> 2 Hours Delivery</label> -->
			<div class="type-box">
				<input name="delivery_type" id="delivery_type_day" type="radio" value="one_day"
               class="chk_delivery_type" checked>
				<label for="delivery_type_day"> One Day Delivery</label>
			</div>
			<label for="delivery_type_day"> Choose Date</label>
			<input type="text" name="delivery_one_day_date" id="delivery_one_day_date" value="" readonly />
            <span id="delivery_one_day_date-error" class="error"></span>
         </div>
         <?php }
            if ($_COOKIE['delivery_type'] == 'Twise in a week') { ?>
         <label><b>Tentitive Delivery Date:</b></label>
            <p>
               <?php 
               $delivery_days = $_COOKIE['delivery_days'];
               $dayArray = explode(',', $delivery_days);
               $deliveryDate = date('d-m-Y');
               $expdeliveryDate = date('d-m-Y');
               
               $hours = date('H');
               $today = date('l');
               
               if(in_array($today, $dayArray) && $hours < 15){
               	$deliveryDate = date('d-m-Y');
                  $expdeliveryDate = date('d-m-Y');
               } else {
               	for ($i = 1; $i <= 7; $i++) {
               		// Get the date for the next iteration
               		$nextDate = new DateTime();
               		$nextDate->modify("+$i day");
               	
               		// Check if the day of the next date is in the dayArray
               		if (in_array($nextDate->format('l'), $dayArray)) {
               			$deliveryDate = $nextDate->format('l (d F, Y)');
                        $expdeliveryDate = $nextDate->format('d-m-Y');
               			break; // Exit the loop once a suitable delivery date is found
               		}
               	}
               }
               echo $deliveryDate;
               echo $expdeliveryDate;
               
               /* $t = date('d-m-Y');
               
               
               if (date("l", strtotime($t)) == "Tuesday" || date("l", strtotime($t)) == "Wednesday" || date("l", strtotime($t)) == "Thursday") {
               
               	$date = new DateTime();
               
               	echo $date->modify('next thursday')->format('m-d-Y l');
               
               }
               
               if (date("l", strtotime($t)) == "Friday" || date("l", strtotime($t)) == "Saturday" || date("l", strtotime($t)) == "Sunday" || date("l", strtotime($t)) == "Monday") {
               
               	$date = new DateTime();
               
               	echo $date->modify('next monday')->format('m-d-Y l');
               
               } */
               
               ?>
            </p>
         <input name="delivery_type" id="delivery_type_week" type="hidden"
            value="<?php echo $_COOKIE['delivery_type']; ?>" class="chk_delivery_type">
         <?php } ?><br />
         <input type="hidden" name="coupon_id"
            value="<?php echo ((isset($_SESSION['coupon_id']) && $_SESSION['coupon_id'] > 0) ? $_SESSION['coupon_id'] : 0); ?>">
      </div>
      <div class="billing-left" id="TipData">
         <h3>Tip</h3>
         <div class="order_tip_form">
            <input type="hidden" id="added_order_tip" value="0" />
            <button id="order_tip_5" type="button" class="order_tip" data-tip="5">$5</button>
            <button id="order_tip_10" type="button" class="order_tip" data-tip="10">$ 10</button>
            <button id="order_tip_15" type="button" class="order_tip" data-tip="15">$ 15</button>
            <button id="order_tip_20" type="button" class="order_tip" data-tip="20">$ 20</button>
            <button id="order_tip_25" type="button" class="order_tip" data-tip="25">$ 25</button>
            <button id="order_tip_custom" type="button" class="active" data-tip="custom"
               onClick="addCustomTip();">Custom Tip </button>
            <input placeholder="Enter Tip Amount" name="tip_amount" id="tip_amount" type="text"
               style="display:none;" onkeypress="return isNumber(event)">
            <span id="tip_error_message" style="display:none;color:red;" >Please enter valid amount</span>
            <a href="javascript:void();" onClick="addCustomTipAmount()" id="add_tip_button" style="display:none;color: #fff;border: none;padding: 8px 9px;cursor: pointer;background: #1e53a5;border-radius: 2px;">Add Tip</a>
            <span id="tip_remove_button" style="display:none; " ><a href="javascript:void(0);" onClick="removeTipAmount()" style="color: #fff;border: none;padding: 8px 9px;cursor: pointer;background: #1e53a5;border-radius: 2px;">Remove Tip</a></span>
         </div>
      </div>
      <div class="billing-left" id="NoteData">
         <h3>Notes</h3>
		 <div class="instructions-box">
			 <lable>Product Instructions</lable>
			 <textarea name="order_comments" id="order_comments" placeholder=""></textarea>
			 <lable>Delivery Instructions</lable>
			 <textarea name="delivery_comments" id="delivery_comments" placeholder=""></textarea>
		 </div>
      </div>
      
   </div>
   <div class="col-md-8">
      <?php //print_r($this->cart->contents()); 
         ?>
      <div class="billing-right" id="SubstitutiondeData">
         <h3 id="required_policy">SUBSTITUTION PREFERENCES<small title="required">*</small></h3>
         <div class="substitue-box">
            <input name="refund_for_unavailable" id="substitute_on_entire_order" type="radio" value="3" checked>
            <label for="refund_for_unavailable">Substitute on entire order</label>
         </div>
		   <div class="substitue-box">
            <input name="refund_for_unavailable" id="substitute_on_selected_products" type="radio" value="2">
            <label for="substitute_unavailable">Substitute on selected products only</label>
         </div>
		   <div class="substitue-box">
            <input name="refund_for_unavailable" id="no_please_refund" type="radio" value="4">
            <label for="refund_for_unavailable">No, please refund</label>
		   </div>
         <span id="replace-policy-error" class="error"></span>
      </div>
      <div class="billing-right">
         <h3>REVIEW YOUR ORDERS</h3>
         <table width="100%" cellpadding="0" cellspacing="5" align="center">
            <tr>
               <th scope="col" class="substitution_products_div" style="display:none;">SUBSTITUTE</th>
               <th scope="col">PRODUCT</th>
               <th scope="col">QTY</th>
               <th scope="col">TAX($) </th>
               <th scope="col">TOTAL</th>
            </tr>
            <?php if (count($this->cart->contents()) > 0) {
               $counter = 1;
               
               //echo "<pre>";print_r($this->cart->contents());exit;
               
               foreach ($this->cart->contents() as $items) {
               
               	?>
            <tr>
               <td class="substitution_products_div" style="display:none;">
                  <input type="checkbox" name="substitution_product_ids[]" class="substitution_products checkbox" value="<?php echo $items['id']; ?>" />
               </td>
               <td>
                  <img src="<?php echo $items["image"]; ?>" alt="<?php echo $items["name"]; ?>">
                  <span class="product_name">
                  <?php echo $items["name"]; ?>
                  </span>
               </td>
               <td>
                  <?php echo $items["qty"]; ?>
                  <?php //$product_tax = $items["product_tax"];
                     //$total_tax = (intval($items["product_tax"]) / 100); 
                     
                     ?>
               </td>
               <td>
                  <?php
                     if ($items['product_tax'] == 1) {
                     
                     	?>
                  <span id="product-tax-<?php echo $counter; ?>" data-tax="">$0.00</span>
                  <?php } else {
                     ?>
                  <span id="product-tax-<?php echo $counter; ?>" data-tax="">$0.00</span>
                  <?php 
                     } ?>
               </td>
               <td>
                  <span id="product-price-<?php echo $counter++; ?>"></span>
               </td>
            </tr>
            <tr>
               <!--<td><?php //echo $items["name"]; 
                  ?></td>-->
            </tr>
            <?php } ?>
            <?php } ?>
         </table>
         <p class="or_text">Order Summary</p>
         <div class="cart_totals">
            <ul>
            <li class="cart-subtotal">
                  <p class="cash_cr_text">Use Cashback Credits</p>
                  <input type="hidden" id="earned_credit_val" name="earned_credit_val" value="<?php echo number_format($earned_credit,2); ?>">
                  <input type="hidden" id="last_credit_per" name="last_credit_per" value="<?php echo $last_credit_per; ?>">
               </li>
               <li class="cart-subtotal cash_credits" id="cash_credits">
                  <p><b>Available Credits: </b><span id="cash_credits">$<?php echo number_format($earned_credit,2); ?></span></p>
                  <span><input type="checkbox" id="earned_credit_checkbox" name="earned_credit_checkbox" value="1"></span>
               </li>
            </ul>
         </div>
         <div class="cart_totals" style="background:#efefef !important;">
            <ul>
               <li class="cart-subtotal coupon_detail" id="coupon_detail">
                  <p>Applyed Coupon Code:</p>
                  <span id="apply_coupon_code"></span>
               </li>
               <li class="cart-subtotal" style="margin:0px !important;">
                  <p>Subtotal:</p>
                  <span id="subtotal">
                  <?php
                     $taxable_products_amount = 0;
                     
                     $tax_add = 0;
                     
                     if (count($this->cart->contents()) > 0) {
                     
                     	$total_price = 0;
                     
                     	foreach ($this->cart->contents() as $items) {
                     
                     		$total_price += ($items["price"] * $items["qty"]);
                     
                     		if($items["product_tax"]==1)
                     
                     		{
                     
                     			$tax_add = 1;
                     
                     			$taxable_products_amount += ($items["price"] * $items["qty"]);
                     
                     		}
                     
                     	}
                     
                     	echo "$" . number_format($total_price, 2); 
                     
                     } ?>
                  </span>
               </li>
               <li class="cart-subtotal">
                  <p>Discount:</p>
                  <span>
                  <span id="discount"></span>
                  </span>
               </li>
               <li class="cart-subtotal" id="sub_credit_div" style="display:none";>
                  <p>Credit Value:</p>
                  <span>
                  <span id="sub_credit_val"></span>
                  </span>
               </li>
               <li class="cart-subtotal">
                  <p>Delivery:</p>
                  <span>
                  <b>Free Delivery</b>
                  </span>
               </li>
               <li class="cart-tip">
                  <p>Tip:</p>
                  <span>
                  <b>$<span id="tip"></span></b>
                  </span>
               </li>
               <li class="extra_delivery_charge">
                  <p>Extra Delivery Charge:</p>
                  <span>
                  <span id="extra_delivery_charge"></span>
                  </span>
               </li>
               <li class="cart-tax">
                  <p>State Tax:</p>
                  <span>
                  <b>$<span id="cart-tax" data-tax="">
                  <?php ?>
                  </span></b>
                  </span>
               </li>
               <!-- <li class="cart-product-tax">
                  <p>Product Tax:</p>
                  
                  <span>
                  
                  	<b><span id="cart-product-tax" data-tax=""><?php ?></span>%</b>
                  
                  </span>
                  
                  </li> -->
               <li class="cart-Preparation">
                  <p>Preparation Cost:</p>
                  <span>
                  <b>$<span name="cart-preparation" id="cart-preparation">
                  <?php echo $preparation_cost = $preparation['preparation_cost'] = $this->config->item('preparation_cost'); ?>
                  </span></b>
                  </span>
               </li>
               <li class="cart-packaging">
                  <p>Packaging Cost:</p>
                  <span>
                  <b>$<span name="cart-packaging" id="cart-packaging">
                  <?php echo $packaging_cost = $packaging['packaging_cost'] = $this->config->item('packaging_cost'); ?>
                  </span></b>
                  </span>
               </li>
               <li class="cart-subtotal">
                  <p>Total:</p>
                  <span>
                  <b>$<span name="cart-total" id="cart-total">
                  <?php echo $total_price + $preparation_cost + $packaging_cost; ?>
                  </span></b>
                  </span>
               </li>
            </ul>
            <input type="hidden" name="cart_total1" id="hdn_order_amount" value="<?php echo $total_price + $preparation_cost + $packaging_cost; ?>">
            <input type="hidden" name="hdn_subtotal" id="hdn_subtotal" value="<?php echo number_format($total_price, 2); ?>">
            <input type="hidden" name="cart_total" id="cart_total"
               value="<?php echo $total_price + $preparation_cost + $packaging_cost; ?>">
            <input type="hidden" name="preparation_cost" id="cart_preparation"
               value="<?php echo $preparation_cost = $preparation['preparation_cost'] = $this->config->item('preparation_cost'); ?>">
            <input type="hidden" name="packaging_cost" id="cart_packaging"
               value="<?php echo $packaging_cost = $packaging['packaging_cost'] = $this->config->item('packaging_cost'); ?>">
            <input type="hidden" name="state_tax" id="state_tax" value="">
            <input type="hidden" name="hdn_tip_amount" id="hdn_tip_amount" value="">
            <input type="hidden" name="discount_amount" id="dis_count_amount">
            <input type="hidden" name="discount_id" id="discount_id">
            <input type="hidden" name="product_tax" id="product_tax">
            <?php if ($last_credit_per > 0) { ?>
            <p><span>You'll receive <b id="earn_cr_txtval"></b> credit when you place an order.</span></p>
            <?Php } ?>

            <p>
               <b>Note: </b>Orders placed before 3:00PM, will be delivered same day between 5:00 PM - 9:00 PM.
               Orders placed after 3:00PM, will be delivered next day 5:00 PM - 9:00 PM
            </p>

            <?php if ($_COOKIE['delivery_type'] == 'Twise in a week') { ?>
         <label><b>Expected Delivery Date:</b></label>
            <p>
            <?php 
               $delivery_days = $_COOKIE['delivery_days'];
               $dayArray = explode(',', $delivery_days);
               $deliveryDate = date('d-m-Y');
               $expdeliveryDate = date('d-m-Y');
               
               $hours = date('H');
               $today = date('l');
               
               if(in_array($today, $dayArray) && $hours < 15){
               	$deliveryDate = date('d-m-Y');
                  $expdeliveryDate = date('d-m-Y');
               } else {
               	for ($i = 1; $i <= 7; $i++) {
               		// Get the date for the next iteration
               		$nextDate = new DateTime();
               		$nextDate->modify("+$i day");
               	
               		// Check if the day of the next date is in the dayArray
               		if (in_array($nextDate->format('l'), $dayArray)) {
               			$deliveryDate = $nextDate->format('l (d F, Y)');
                        $expdeliveryDate = $nextDate->format('d-m-Y');
               			break; // Exit the loop once a suitable delivery date is found
               		}
               	}
               }
               echo $deliveryDate;
              
               
               ?>
            </p>
        
         <?php } ?>
         <?php if ($_COOKIE['delivery_type'] == 'Express Delivery' || $_COOKIE['delivery_type'] == 'Same Day Delivery') { ?>
            <?php $deliveryDate = date('d-m-Y'); ?>
            <p><label class="expected_d_date_label"><b>Expected Delivery Date : </b><?php echo $deliveryDate; ?></label></p>
            <?php } ?>
            <input type="hidden" name="expec_delivery_date" id="expec_delivery_date" value="<?php echo $expdeliveryDate; ?>">
           <br/>
          
            <?php
               if ($_COOKIE['valid_zipcode']) {
               
                     if ($this->cart->total() > 0) { ?>
               <button type="button" onclick="return validateForm()" class="vraj-btn" name="submit" id="checkout-submit"
                  value="Processed to Pay">Proceed to Pay</button>
               <button style="display:none;" type="submit" name="submit" id="submit" value="Processed to Pay">Checkout Form Submit</button>
               <?php
                  }
               
               }
               
               ?>
         </div>
      </div>
   </div>
   </div>
</section>
</form>

<div class="login-popup" id="ShippingAddressModal" style="padding: 20px 0 !important;">
  <div class="social-login-item">
    <h3>Add New Address</h3>
    <div id="shipping_message"></div>
    <form id="Frm_shipping_Address" method="post">
      <label>First Name <small title="required">*</small></label>
      <input placeholder="First Name" name="shipping_first_name" id="shipping_first_name" type="text" maxlength="100" value="">         
      <label>Last name <small title="required">*</small>
      </label>
      <input placeholder="Last name" name="shipping_last_name" id="shipping_last_name" type="text" maxlength="100" value="">
      <label>Country / Region <small title="required">*</small></label>
      <br>
      <strong>United States (US)</strong>
      <br>
      <br>
      <label>Shipping Street address <small title="required">*</small></label>
      <input placeholder="House number and street name" name="shipping_street_address" id="shipping_street_address" type="text" maxlength="500" value="">
      <input placeholder="Apartment, suite, unit, etc. (optional)" name="shipping_apartment" id="shipping_apartment" type="text" maxlength="500" value="">
      <label>Shipping Town / City <small title="required">*</small></label>
      <input placeholder="City" name="shipping_city" id="shipping_city" type="text" maxlength="100" value="">
      <label>Shipping State <small title="required">*</small></label>
      
      <select name="check_shipping_state_id" id="check_shipping_state_id" data-tax="">
         <?php
            foreach ($ArrStateOption as $key => $state_data) {
            $ArrStateData = explode('|', $key);
            $billing_state_id_ = $ArrStateData[1];
         ?>
         <option value="<?php echo $key; ?>" data-id="<?php echo $billing_state_id_; ?>"><?php echo $state_data; ?></option>
         <?php } ?>
      </select>
      <label>Shipping Postcode / ZIP <small title="required">*</small></label>
      <input placeholder="Zip Code" name="shipping_zipcode" id="shipping_zipcode" type="text" value="" maxlength="100">
      <label>Shipping Mobile <small title="required">*</small></label>
      <input placeholder="Mobile" name="shipping_phone" id="shipping_phone" class="numberonly" type="text" value="" maxlength="10">
      <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
      <input type="hidden" name="user_role_id" value="4">
      <button onclick="SaveAddress('shipping');">SAVE ADDRESS</button>
    </form>
    <div class="close-vraj-checkout">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
  </div>
</div>

<div class="login-popup" id="EditShippingAddressModal" style="padding: 20px 0 !important;">
  <div class="social-login-item">
    <h3>Update Shipping Address</h3>
    <div id="edit_shipping_message"></div>
    <form id="FrmEdit_shipping_Address" method="post">
      <label>First Name <small title="required">*</small></label>
      <input placeholder="First Name" name="edit_shipping_first_name" id="edit_shipping_first_name" type="text" maxlength="100" value="">
      <label>Last name <small title="required">*</small>
      </label>
      <input placeholder="Last name" name="edit_shipping_last_name" id="edit_shipping_last_name" type="text" maxlength="100" value="">
      <label>Country / Region <small title="required">*</small></label>
      <br>
      <strong>United States (US)</strong>
      <br>
      <br>
      <label>Shipping Street address <small title="required">*</small></label>
      <input placeholder="House number and street name" name="edit_shipping_street_address" id="edit_shipping_street_address" type="text" maxlength="500" value="">
      <input placeholder="Apartment, suite, unit, etc. (optional)" name="edit_shipping_apartment" id="edit_shipping_apartment" type="text" maxlength="500" value="">
      <label>Shipping Town / City <small title="required">*</small></label>
      <input placeholder="City" name="edit_shipping_city" id="edit_shipping_city" type="text" maxlength="100" value="">
      <label>Shipping State <small title="required">*</small></label>
      <select name="edit_shipping_state_id" id="edit_shipping_state_id" data-tax="">
         <?php 
            foreach ($ArrStateOption as $key => $state_data) {
            $ArrStateData = explode('|', $key);
            $billing_state_id_ = $ArrStateData[1];
         ?>
         <option value="<?php echo $key; ?>" data-id="<?php echo $billing_state_id_; ?>"><?php echo $state_data; ?></option>
         <?php } ?>
      </select>

      <label>Shipping Postcode / ZIP <small title="required">*</small></label>
      <input placeholder="Zip Code" name="edit_shipping_zipcode" id="edit_shipping_zipcode" type="text" value="" maxlength="100">
      <label>Shipping Mobile <small title="required">*</small></label>
      <input placeholder="Mobile" name="edit_shipping_phone" id="edit_shipping_phone" class="numberonly" type="text" value="" maxlength="10">
      <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
      <input type="hidden" name="user_role_id" value="4">
      <input name="edit_shipping_id" id="edit_shipping_id" type="hidden">
      <button onclick="UpdateAddress('shipping');">UPDATE ADDRESS</button>
    </form>
    <div class="close-vraj-checkout">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
  </div>
</div>

<div class="login-popup" id="BillingAddressModal" style="padding: 20px 0 !important;">
  <div class="social-login-item">
    <h3>Add Billing Address</h3>
    <div id="billing_message"></div>
    <form id="Frm_billing_Address" method="post">
      <label>First Name <small title="required">*</small></label>
      <input placeholder="First Name" name="billing_first_name" id="billing_first_name" type="text" maxlength="100" value="">
      <label>Last name <small title="required">*</small></label>
      <input placeholder="Last name" name="billing_last_name" id="billing_last_name" type="text" maxlength="100" value="">
      <label>Country / Region <small title="required">*</small></label>
      <br>
      <strong>United States (US)</strong>
      <br>
      <br>
      <label>Billing Street address <small title="required">*</small></label>
      <input placeholder="House number and street name" name="billing_street_address" id="billing_street_address" type="text" maxlength="500" value="">
      <input placeholder="Apartment, suite, unit, etc. (optional)" name="billing_apartment" id="billing_apartment" type="text" maxlength="500" value="">
      <label>Billing Town / City <small title="required">*</small></label>
      <input placeholder="City" name="billing_city" id="billing_city" type="text" maxlength="100" value="">
      <label>Billing State <small title="required">*</small></label>
      <select name="billing_state_id" id="billing_state_id" data-tax="">
         <?php foreach ($ArrStateOption as $key => $state_data) { ?>
            <option value="<?php echo $key; ?>"><?php echo $state_data; ?></option>
         <?php } ?>
      </select>
      <label>Billing Postcode / ZIP <small title="required">*</small></label>
      <input placeholder="Zip Code" name="billing_zipcode" id="billing_zipcode" type="text" value="" maxlength="100">
      <label>Billing Mobile <small title="required">*</small></label>
      <input placeholder="Mobile" name="billing_phone" id="billing_phone" class="numberonly" type="text" value="" maxlength="10">
      <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
      <input type="hidden" name="user_role_id" value="4">
      <button onclick="SaveAddress('billing');">SAVE ADDRESS</button>
    </form>
    <div class="close-vraj-checkout">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
  </div>
</div>
<div class="login-popup" id="EditBillingAddressModal" style="padding: 20px 0 !important;">
  <div class="social-login-item">
    <h3>Update Billing Address</h3>
    <div id="billing_message"></div>
    <form id="FrmEdit_billing_Address" method="post">
      <label>First Name <small title="required">*</small></label>
      <input placeholder="First Name" name="edit_billing_first_name" id="edit_billing_first_name" type="text" maxlength="100" value="">         
      <label>Last name <small title="required">*</small></label>
      <input placeholder="Last name" name="edit_billing_last_name" id="edit_billing_last_name" type="text" maxlength="100" value="">
      <label>Country / Region <small title="required">*</small></label>
      <br>
      <strong>United States (US)</strong>
      <br>
      <br>
      <label>Billing Street address <small title="required">*</small></label>
      <input placeholder="House number and street name" name="edit_billing_street_address" id="edit_billing_street_address" type="text" maxlength="500" value="">
      <input placeholder="Apartment, suite, unit, etc. (optional)" name="edit_billing_apartment" id="edit_billing_apartment" type="text" maxlength="500" value="">
      <label>Billing Town / City <small title="required">*</small></label>
      <input placeholder="City" name="edit_billing_city" id="edit_billing_city" type="text" maxlength="100" value="">
      <label>Billing State <small title="required">*</small></label>
      <select name="edit_billing_state_id" id="edit_billing_state_id" data-tax="">
         <?php 
            foreach ($ArrStateOption as $key => $state_data) {
            $ArrStateData = explode('|', $key);
            $billing_state_id_ = $ArrStateData[1];
         ?>
         <option value="<?php echo $key; ?>" data-id="<?php echo $billing_state_id_; ?>"><?php echo $state_data; ?></option>
         <?php } ?>
      </select>
      <label>Billing Postcode / ZIP <small title="required">*</small></label>
      <input placeholder="Zip Code" name="edit_billing_zipcode" id="edit_billing_zipcode" type="text" value="" maxlength="100">
      <label>Billing Mobile <small title="required">*</small></label>
      <input placeholder="Mobile" name="edit_billing_phone" id="edit_billing_phone" class="numberonly" type="text" value="" maxlength="10">
      <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
      <input type="hidden" name="user_role_id" value="4">
      <input name="edit_billing_id" id="edit_billing_id" type="hidden">
      <button onclick="UpdateAddress('billing');">UPDATE ADDRESS</button>
    </form>
    <div class="close-vraj-checkout">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
  </div>
</div>

<?php require_once('common/common_js.php'); ?>
<?php require_once('common/footer.php'); ?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<?php require_once('scripts/checkout_js.php'); ?>
<script>
   $(function() {
      var today = new Date();
      var tomorrow = new Date(today);
      tomorrow.setDate(today.getDate() + 1);

      var maxDate = new Date(today);
      maxDate.setDate(today.getDate() + 7);

      $("#delivery_one_day_date").datepicker({
            dateFormat: 'dd/mm/yy',
            minDate: 0,
            maxDate: 7,
            defaultDate: today
      }).datepicker("setDate", today);

      $('#delivery_one_day_date').datepicker({dateFormat: 'dd-mm-yy'}).on('change', function (ev) {
         var firstDate = $(this).val();
         var text = firstDate.replace('/', '-');
         text = text.replace('/', '-');
         $('.expected_d_date_label').html('');
         $('#expec_delivery_date').val(text);
         $('.expected_d_date_label').html('<b>Expected Delivery Date : </b></label>'+text);
         
      });
   });
   
   let checkoutformsubmit = 0;
   
   var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
    var elements = stripe.elements();

    var style = {
      base: {
        fontSize: '16px',
        color: '#32325d',
      }
    };

    var card = elements.create('card', { style: style });
    card.mount('#card-element');

    card.on('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });

   $(document).on('click', '#AddShippingAddressbtn', function() {
      
      $("#Frm_shipping_Address")[0].reset();
      CloseCheckoutModels();
      jQuery("body").addClass("open-login");
      jQuery("div#ShippingAddressModal").show();
   });
   $(document).on('click', '#AddBillingAddressBtn', function() {
      $("#Frm_billing_Address")[0].reset();
      CloseCheckoutModels();
      jQuery("body").addClass("open-login");
      jQuery("div#BillingAddressModal").show();
    });
    $(document).on('click', '.close-vraj-checkout', function() {
      CloseCheckoutModels();
    });
    $(document).on('click', '.close-vraj-redchec', function() {
      CloseCheckoutModels();
      $('input[type=radio][name=shipping_id]').prop('checked', false);
    });

    $('.numberonly').keypress(function (e) {
		var charCode = (e.which) ? e.which : event.keyCode
		if (String.fromCharCode(charCode).match(/[^0-9]/g))
			return false;
	});
</script>

<style>
	.checkout-page.billing-page > .container-flex > div {
		border: none;
		padding: 0;
	}
	.checkout-page.billing-page .billing-left,
	.checkout-page.billing-page .billing-right {
		border: solid 1px #ddd;
		padding: 1em 2em;
	}
	.checkout-page.billing-page .billing-left + .billing-left {
		margin-top: 25px;
	}
	.checkout-page.billing-page .billing-right th {
		font-weight: 700;
	}
	.checkout-page.billing-page .billing-left .add-address {
		border-bottom: 1px solid #ddd;
		padding-bottom: 30px;
		margin-bottom: 20px;
	}
	.checkout-page.billing-page .billing-left .add-address > a {
		display: inline-block;
		color: #000;
		font-size: 18px;
		font-weight: 500;
		position: relative;
		padding-left: 40px;
		line-height: 25px;
	}
	.checkout-page.billing-page .billing-left .add-address > a::before {
		content: "+";
		position: absolute;
		left: 0;
		top: 0;
		font-size: 20px;
		border: 1px solid #000;
		width: 23px;
		height: 23px;
		border-radius: 50%;
		line-height: 21px;
		text-align: center;
		font-weight: 500;
	}
	.checkout-page.billing-page .billing-left .your-address > span {
		display: block;
		margin-bottom: 25px;
		font-size: 18px;
		font-weight: 500;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box {
		align-items: flex-start;
		display: flex;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box + .address-box {
		margin-top: 35px;
	}
	.checkout-page.billing-page .billing-left input[type="radio"] {
		border: 1px solid #000;
		border-radius: 50%;
		appearance: none;
	}
	.checkout-page.billing-page .billing-left input:checked[type="radio"] {
		background-color: #000;
		box-shadow: 0px 0px 0px 5px #fff inset;
		-webkit-box-shadow: 0px 0px 0px 5px #fff inset;
		-moz-box-shadow: 0px 0px 0px 5px #fff inset;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box input[type="radio"] {
		width: 23px;
		height: 23px;
		margin-right: 15px;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box input:checked[type="radio"] {
		box-shadow: 0px 0px 0px 6px #fff inset;
		-webkit-box-shadow: 0px 0px 0px 5px #fff inset;
		-moz-box-shadow: 0px 0px 0px 6px #fff inset;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box .detail-box {
		flex: 1%;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box .add-edit a {
		color: #000;
		font-weight: 700;		
	}
	.checkout-page.billing-page .billing-left .your-address .same-shipping-add,
	.checkout-page.billing-page .billing-left .your-address .address-box .detail-box > p {
		display: block;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box .detail-box > p {
		font-size: 17px;
		margin-bottom: 5px;
	}
	.checkout-page.billing-page .billing-left .your-address .address-box .detail-box > p > b {
		font-size: 18px;
	}
	.checkout-page.billing-page .billing-left .your-address .same-shipping-add {
		margin-bottom: 35px;
	}
	.checkout-page.billing-page .billing-left .your-address .same-shipping-add a {
		color: #000;
		font-weight: 700;
		font-size: 17px;
	}
	.checkout-page .billing-left .add-card {
		align-items: flex-start;
		display: flex;
		margin-top: 15px;
	}
	.checkout-page .billing-left .add-card input[type="radio"] {
		margin-right: 15px;
	}
	.checkout-page .billing-left .add-card label {
		flex: 1%;
		color: #000;
		font-weight: 700;
		font-size: 17px;
	}
	.checkout-page .billing-left .type-box {
		align-items: flex-start;
		display: flex;
	}
	.checkout-page .billing-left .type-box p,
	.checkout-page .billing-left .type-box label {
		font-weight: 600;
		flex: 1%;
		margin-left: 15px;
	}
	
	.checkout-page .billing-left .order_tip_form button {
		background-color: #cbcbcb;
		padding: 10px 14px;
		font-weight: 500;
		margin: 5px 2px;
		font-size: 14px;
	}
	.checkout-page .billing-left .order_tip_form button:hover,
	.checkout-page .billing-left .order_tip_form button.active {
		background-color: #1e53a5;
	}
	.checkout-page .billing-left .instructions-box lable {
		font-weight: 600;
		margin-bottom: 10px;
		display: block;
	}
	.checkout-page .billing-right .substitue-box {
		align-items: flex-start;
		display: flex;
	}
	.checkout-page .billing-right .substitue-box input[type="radio"] {
		border: 1px solid #000;
		width: 18px;
		height: 18px;
		margin-right: 15px;
	}
	.checkout-page .billing-right .substitue-box label {
		font-weight: 600;
	}
	
	.login-popup {
		text-align: left;
		z-index: 10000;
	}
	.login-popup .social-login-item {
		padding: 1em;
		width: 450px;
		max-height: 100%;
	}
	.login-popup .social-login-item h3 {
		background-color: #1E53A5;
		width: 100%;
		font-size: 18px;
		padding: 10px 20px;
		margin-bottom: 15px;
		color: #fff;
		margin-top: 0;
	}
	.login-popup .social-login-item form label {
		font-weight: 700;
		font-size: 16px;
		margin-bottom: 10px;
		display: block;
	}
	.login-popup .social-login-item form input {
		border-radius: 0;
		margin-top: 0;
	}
	.login-popup .social-login-item form label ~ input {
		margin-bottom: 20px;
	}
	.login-popup .social-login-item form select {
		padding: 12px 15px;
		margin: 10px 0;
		border: solid 1px #ddd;
		width: 100%;
	}
	@media screen and (-webkit-min-device-pixel-ratio:0) { 
		.login-popup .social-login-item form select {
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
			background: url(data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0Ljk1IDEwIj48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6I2ZmZjt9LmNscy0ye2ZpbGw6IzQ0NDt9PC9zdHlsZT48L2RlZnM+PHRpdGxlPmFycm93czwvdGl0bGU+PHJlY3QgY2xhc3M9ImNscy0xIiB3aWR0aD0iNC45NSIgaGVpZ2h0PSIxMCIvPjxwb2x5Z29uIGNsYXNzPSJjbHMtMiIgcG9pbnRzPSIxLjQxIDQuNjcgMi40OCAzLjE4IDMuNTQgNC42NyAxLjQxIDQuNjciLz48cG9seWdvbiBjbGFzcz0iY2xzLTIiIHBvaW50cz0iMy41NCA1LjMzIDIuNDggNi44MiAxLjQxIDUuMzMgMy41NCA1LjMzIi8+PC9zdmc+) no-repeat 95% 50%;
		}
	} 
	
	.login-popup .social-login-item form button {
		margin: 0 auto;
		padding: 12px 15px;
		width: 180px;
		display: block;
	}
	.login-popup .social-login-item .close-vraj-checkout {
		position: absolute;
		top: 26px;
		right: 30px;
		color: #fff;
		cursor: pointer;
	}
	@media (max-width: 575px) {
		.checkout-page.billing-page .billing-right {
			padding-left: 1em;
			padding-right: 1em;
		}
		.checkout-page.billing-page .billing-right table {
			display: block;
			overflow-x: scroll;			
		}
		.checkout-page.billing-page .billing-right table tr {
			margin-bottom: 5px;
		}
		.checkout-page.billing-page .billing-right table tr th,
		.checkout-page.billing-page .billing-right table tr td {
			margin-left: 2.5px;
			margin-right: 2.5px;
		}
	}
	
</style>

