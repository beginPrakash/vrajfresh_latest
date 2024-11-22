<?php require_once('common/header.php'); ?>
<section class="categories-banner">
	<h2>CHECKOUT</h2>
</section>
<style>
	.error {
		color: red;
	}
</style>
<section class="click-login">
	<div class="container container-flex">
	</div>
</section>
<?php
$action = BASE_URL . 'checkout-process';
$attributes = array('class' => '', 'id' => 'checkoutForm', 'role' => 'form', 'enctype' => 'multipart/form-data');
echo form_open($action, $attributes);
$this->load->config('config');
?>
<section class="billing-page">
	<div class="container container-flex">
		<div class="billing-left">
			<h3>SHIPPING ADDRESS INFO</h3>
			<div>
				<label>First Name <small title="required">*</small>
				</label>
				<?php
				if ($_COOKIE['first_name'] != "") {
					$first_name = $_COOKIE['first_name'];
				} else {
					$first_name = "";
				}
				?>
				<input placeholder="First Name" name="first_name" id="first_name" type="text" maxlength="100"
					value="<?php echo $first_name; ?>">
				<span id="first-name-error" class="error"></span>
			</div>
			<?php
			if ($_COOKIE['last_name'] != "") {
				$last_name = $_COOKIE['last_name'];
			} else {
				$last_name = "";
			}
			?>
			<div>
				<label>Last name <small title="required">*</small>
				</label>
				<input placeholder="Last name" name="last_name" id="last_name" type="text" maxlength="100"
					value="<?php echo $last_name; ?>">
				<span id="last-name-error" class="error"></span>
			</div>
			<div>
				<label>Country / Region <small title="required">*</small>
				</label>
				<br>
				<strong>United States (US)</strong>
				<br>
				<br>
			</div>
			<?php
			if ($_COOKIE['shipping_street_address'] != "") {
				$shipping_street_address = $_COOKIE['shipping_street_address'];
			} else {
				$shipping_street_address = "";
			}
			if ($_COOKIE['shipping_apartment'] != "") {
				$shipping_apartment = $_COOKIE['shipping_apartment'];
			} else {
				$shipping_apartment = "";
			}
			?>
			<div>
				<label>Shipping Street address <small title="required">*</small>
				</label>
				<input placeholder="House number and street name" name="street_address" id="street_address" type="text"
					maxlength="500" value="<?php echo $shipping_street_address; ?>">
				<input placeholder="Apartment, suite, unit, etc. (optional)" name="apartment" id="apartment" type="text"
					maxlength="500" value="<?php echo $shipping_apartment; ?>">
				<span id="street-address-error" class="error"></span>
			</div>
			<?php
			/*if ($_COOKIE['delivery_area_name'] != "") {
				$shipping_city = $_COOKIE['delivery_area_name'];
			} 
			else */
			if ($_COOKIE['shipping_city'] != "") {
				$shipping_city = $_COOKIE['shipping_city'];
			} else {
				$shipping_city = "";
			}
			?>
			<div>
				<label>Shipping Town / City <small title="required">*</small>
				</label>
				<input placeholder="City" name="city" id="city" type="text" maxlength="100"
					value="<?php echo $shipping_city; ?>">
				<span id="city-error" class="error"></span>
			</div>
			<?php //check if state is set in cookie if yes then select that state
			
			if ($_COOKIE['delivery_state_id'] != "") {
				$shipping_state = $_COOKIE['delivery_state_id'];
			}else if ($_COOKIE['shipping_state'] != "") {
				$shipping_state = $_COOKIE['shipping_state'];
			} else {
				$shipping_state = "";
			}
			?>
			<div>
				<label>Shipping State <small title="required">*</small>
				</label>
				<?php //echo form_dropdown('state', $ArrStateOption, $shipping_state, 'id="state" data-tax=""'); ?>
				<select name="state" id="state" data-tax="">
					<?php
					$tax_percentage = 0;
					foreach ($ArrStateOption as $key => $state_data) {
						$ArrStateData = explode('|', $key);
						$billing_state_id = $ArrStateData[1];
						if ($billing_state_id == $shipping_state || $state_data == $shipping_state) {
						$tax_percentage = $ArrStateData[0];
						}
						?>
						<option value="<?php echo $key; ?>" <?php if ($billing_state_id == $shipping_state || $state_data == $shipping_state) {
							   echo 'selected';
						   } ?>><?php echo $state_data; ?></option>
					<?php } ?>
				</select>
				<span id="state-error" class="error"></span>
			</div>
			<?php
			$zipcode = '';
			if (isset($_COOKIE['zipcode']) && $_COOKIE['zipcode'] != '') {
				$zipcode = $_COOKIE['zipcode'];
			} else if (isset($_COOKIE['shipping_zip_code']) && $_COOKIE['shipping_zip_code'] != '') {
				$zipcode = $_COOKIE['shipping_zip_code'];
			}
			?>
			<div>
				<label>Shipping Postcode / ZIP <small title="required">*</small>
				</label>
				<input placeholder="Zip Code" name="zip_code" id="zip_code" type="text" value="<?php echo $zipcode; ?>"
					maxlength="100" readonly>
				<span id="zip-code-error" class="error"></span>
			</div>
			<div>
				<label>Shipping Mobile <small title="required">*</small>
				</label>
				<input placeholder="Mobile" name="phone" id="phone" type="text" value="<?php echo $_COOKIE['shipping_phone']; ?>"
					maxlength="15">
				<span id="phone-error" class="error"></span>
			</div>
			<div>
				<label>Email address <small title="required">*</small>
				</label>
				<?php if ($this->session->userdata['logged_in']['email'] != "") {
					$readonly = 'readonly';
					$class = 'readonly';
				} else {
					$readonly = '';
					$class = '';
				}
				?>
				<input placeholder="Email address" name="email" id="email" type="email"
					value="<?php echo $this->session->userdata['logged_in']['email']; ?>" <?php echo $readonly; ?>
					class="<?php echo $class; ?>" maxlength="100">
				<span id="email-address-error" class="error"></span>
			</div>

			<br>
			<button id="autofill-btn">Same as Shipping Address</button>
			<!-- <label>Create an account?</label> -->
			<br><br>
			<h3>BILLING INFO</h3>
			<input name="ship_to_different_address" id="ship_to_different_address" type="hidden" value="No">
			<!--<label>Ship to a different address?</label>-->

			<?php
			$address = $address2 = $city = $zip = $phone = '';
		//	echo "<pre>";print_r($_COOKIE);exit;
			if ($_COOKIE['address'] != "") {
				$address = $_COOKIE['address'];
			}
			if ($_COOKIE['address2'] != "") {
				$address2 = $_COOKIE['address2'];
			}
			if ($_COOKIE['city'] != "") {
				$city = $_COOKIE['city'];
			}
			if ($_COOKIE['zip'] != "") {
				$zip = $_COOKIE['zip'];
			}
			if ($_COOKIE['phone'] != "") {
				$phone = $_COOKIE['phone'];
			}
			if ($_COOKIE['state'] != "") {
				$state = $_COOKIE['state'];
			}
			?>
			<div id="shipingAddressBox">
				<div>
					<label>Billing Street address <small title="required">*</small>
					</label>
					<input placeholder="House number and street name" value="<?php echo $address; ?>"
						name="shiping_street_address" id="shiping_street_address" type="text" maxlength="500">
					<span id="shiping-street-address-error" class="error"></span>
					<input placeholder="Apartment, suite, unit, etc. (optional)" value="<?php echo $address2; ?>"
						name="shiping_apartment" id="shiping_apartment" type="text" maxlength="500">
					<span id="shiping-street-address2-error" class="error"></span>
				</div>
				<div>
					<label>Billing Town / City <small title="required">*</small>
					</label>
					<input placeholder="City" value="<?php echo $city; ?>" name="shiping_city" id="shiping_city"
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
					<input placeholder="Zip Code" value="<?php echo $zip; ?>" name="shiping_zip_code"
						id="shiping_zip_code" type="text" maxlength="100">
					<span id="zip-error" class="error"></span>
				</div>
				<div><label>Billing Mobile <small title="required">*</small>
					</label>
					<input placeholder="Mobile" value="<?php echo $phone; ?>" name="shiping_phone" id="shiping_phone"
						type="text" maxlength="15">
					<span id="shiping-phone-error" class="error"></span>
				</div>


			</div><br />
			<?php if ($_COOKIE['delivery_type'] == 'Express Delivery' || $_COOKIE['delivery_type'] == 'Same Day Delivery') { ?>
				<div><label><b>Delivery Type</b></label><br />
					<!-- <input name="delivery_type" id="delivery_type_hour" type="radio" value="two_hour" class="chk_delivery_type"> -->
					<!-- <label for="delivery_type_hour" id="delivery_after_hr"> 2 Hours Delivery</label> -->
					<input name="delivery_type" id="delivery_type_day" type="radio" value="one_day"
						class="chk_delivery_type" checked>
					<label for="delivery_type_day"> One Day Delivery</label>

				</div>
			<?php }
			if ($_COOKIE['delivery_type'] == 'Twise in a week') { ?>
				<label><b>Tentitive Delivery Date:</b></label>
				<p>
					<?php $t = date('d-m-Y');
					if (date("l", strtotime($t)) == "Tuesday" || date("l", strtotime($t)) == "Wednesday" || date("l", strtotime($t)) == "Thursday") {
						$date = new DateTime();
						echo $date->modify('next thursday')->format('m-d-Y l');
					}
					if (date("l", strtotime($t)) == "Friday" || date("l", strtotime($t)) == "Saturday" || date("l", strtotime($t)) == "Sunday" || date("l", strtotime($t)) == "Monday") {
						$date = new DateTime();
						echo $date->modify('next monday')->format('m-d-Y l');
					}
					?>
				</p>
				<input name="delivery_type" id="delivery_type_week" type="hidden"
					value="<?php echo $_COOKIE['delivery_type']; ?>" class="chk_delivery_type">
			<?php } ?><br />
			<input type="hidden" name="coupon_id"
				value="<?php echo ((isset($_SESSION['coupon_id']) && $_SESSION['coupon_id'] > 0) ? $_SESSION['coupon_id'] : 0); ?>">
			<h4>Tip</h4>
			<!--<input name="tip" type="checkbox" >Tip</h4>-->
			<div class="order_tip_form">
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
					<span id="tip_remove_button" style="display:none;" ><a href="javascript:void(0);" onClick="removeTipAmount()">Remove Tip</a></span>
			</div>

			<label>Order Notes (optional)
			</label>
			<textarea name="order_comments" id="order_comments"
				placeholder="Notes about your order, e.g. special notes for delivery."></textarea>

			<h3 id="required_policy">SUBSTITUTION PREFERENCES<small title="required">*</small></h3>
			<input name="refund_for_unavailable" id="substitute_unavailable" type="radio" value="1">
			<label for="substitute_unavailable">Yes, please substitute unavailable items with similar products </label>
			<br>
			<input name="refund_for_unavailable" id="refund_for_unavailable" type="radio" value="0">
			<label for="refund_for_unavailable">No, please refund for unavailable items</label>
			<span id="replace-policy-error" class="error"></span>
			</form>
		</div>
		<?php //print_r($this->cart->contents()); 
		?>
		<div class="billing-right">
			<h3>REVIEW YOUR ORDERS</h3>
			<table width="100%" cellpadding="0" cellspacing="5" align="center">
				<tr>
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
			<div class="cart_totals">
				<ul>
					<li class="cart-subtotal coupon_detail" id="coupon_detail">
						<p>Applyed Coupon Code:</p>
						<span id="apply_coupon_code"></span>
					</li>
					<li class="cart-subtotal">
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
				<p>
					<b>Note: </b>Orders placed before 3:00PM, will be delivered same day between 5:00 PM - 9:00 PM.
					Orders placed after 3:00PM, will be delivered next day 5:00 PM - 9:00 PM
				</p>
				<?php
				if ($_COOKIE['valid_zipcode']) {
					if ($this->cart->total() > 0) { ?>
						<button type="submit" onclick="return validateForm()" class="vraj-btn" name="submit" id="submit"
							value="Processed to Pay">Proceed to Pay</button>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</section>

</form>
<?php require_once('common/common_js.php'); ?>
<?php require_once('common/footer.php'); ?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<?php require_once('scripts/checkout_js.php'); ?>
