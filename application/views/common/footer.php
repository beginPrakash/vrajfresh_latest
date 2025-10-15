<div class="popup" style="display: none;">
	<div class="popup-container">
		<div class="popup-left">
			<ul>
				<li><img src="<?php echo ASSET_URL . "images/pin.svg"; ?>" />
					<p>449, Market Street Saddle Brook,<br />
						NJ 07663</p>
				</li>
				<li><img src="<?php echo ASSET_URL . "images/phone.svg"; ?>" />
					<p>201-688-7887</p>
				</li>
				<li><img src="<?php echo ASSET_URL . "images/wall-clock.svg"; ?>" />
					<p>Monday - Sunday <br />
						10:00 - 21:00</p>
				</li>
			</ul>
		</div>
		<div class="popup-right">
			<h2>we are currently delivering to 100+ zip codes in nj and growing rapidly</h2>
			<p>Please check if we're in your area by simply entering zip code below.</p>
			<form action="">
				<input type="text" placeholder="Enter Zip Code..." name="zipcode_popup" id="zipcode_popup">
				<input type="hidden"  name="zipcode_popup_value" id="zipcode_popup_value">
				<div id="zipcode_popup_data"></div>
				<button type="submit"  onclick="return setZipCodeCookie('popup');" ><img src="<?php echo ASSET_URL . "images/right-arrows.svg"; ?>" class=zipcode_popup_btn /></button>
			</form>
			<div class="mb-3">
				<div id="zipcode_popup_message" class="error"></div>
				<div id="zipcode_popup_message1" class="error"></div>
			</div>
			<ul class="popup-ul">
				<li> Same Day* Delivery</li>
				<li>Free Delivery</li>
			</ul>
		</div>
		<div class="close-popup"><a href="javascript:void(0)">X</a></div>
	</div>
</div>
<script>
        $(document).ready(function() {
			$('.footer-grid h3').on('click', function() {
                $(this).toggleClass('expanded'); // Toggle class on h3
                $(this).next('ul').toggleClass('expanded'); // Toggle class on ul
            });
        });
    </script>

<footer>
	<div class="container">
		<div class="container-flex ">
			<div class="footer-grid">
				<img src=<?php echo ASSET_URL . "images/logo.png"; ?> alt="">
				<h3 class="dnd">About Us <i class="arrow"></i></h3>
				<ul>
					<li><img src="<?php echo ASSET_URL . "images/map-pin.png"; ?>">449, Market Street Saddle Brook, NJ
						07663</li>
					<li><a href="tel:201-688-7887"><img src="<?php echo ASSET_URL . "images/phone-icon.png"; ?>">201-688-7887</a></li>
					<li><a href="mailto:orders@vrajfresh.com"><img src="<?php echo ASSET_URL . "images/mail-icon.png"; ?>">orders@vrajfresh.com</a></li>
					<li><img src="<?php echo ASSET_URL . "images/watch-icon.png"; ?>">Work hours: 10:00 - 21:00, Monday
						- Sunday</li>
				</ul>
			</div>
			<div class="footer-grid">
				<h3>Account <i class="arrow"></i></h3>
				<?php if (!IsUserLogin()) {
					$order_link = "#";
					$address_link = "#";
					$account_link = "#";
					$change_password_link = "#";
					$report_order = "#";
					$special_request = "#";
				} else {
					$order_link = BASE_URL . "my-orders";
					$address_link = BASE_URL . "my-address";
					$account_link = BASE_URL . "my-account";
					$change_password_link = BASE_URL . "change-password";
					$report_order = BASE_URL . "report-order";
					$special_request = BASE_URL . "special-request";
				}

				?>

				<ul>
					<li><a href="<?php echo $order_link; ?>">Orders</a></li>
					<li><a href="<?php echo $address_link; ?>">Addresses</a></li>
					<li><a href="<?php echo $account_link; ?>">Account Details</a></li>
					<li><a href="<?php echo $change_password_link; ?>">Change Password</a></li>
					<?php if ($report_order != "#") { ?>
						<li id="report-order">
							<a href="<?php echo $report_order; ?>">Report Order </a>
						</li>
						<li id="special-request">
							<a href="<?php echo $special_request; ?>">Special Request </a>
						</li>
					<?php } ?>
					<!-- <li><a href="#">Wishlist</a></li><li><a href="#">Cart</a></li><li><a href="#">Track Order</a></li><li><a href="#">Shipping Details</a></li> -->
				</ul>
			</div>

			<div class="footer-grid">
			<h3>Useful links <i class="arrow"></i></h3>
				<ul>
					<li><a href="<?php echo SITE_URL . 'get-cms-by-slug/about-us'; ?>">About Us</a></li>
					<li><a href="<?php echo SITE_URL . 'contact'; ?>">Contact</a></li>
					<li><a href="<?php echo SITE_URL . 'get-cms-by-slug/promotions'; ?>">Promotions</a></li>
					<li><a href="<?php echo SITE_URL . 'get-cms-by-slug/new-products'; ?>">New products</a></li>
				</ul>
			</div>
			<div class="footer-grid">
			<h3>Help Center <i class="arrow"></i></h3>
				<ul>
					<li><a href="<?php echo SITE_URL . 'get-cms-by-slug/refund-and-return-policy'; ?>">Refund Policy</a>
					</li>
					<li><a href="<?php echo SITE_URL . 'get-cms-by-slug/shipping'; ?>">Shipping</a></li>
					<li><a href="<?php echo SITE_URL . 'get-cms-by-slug/terms-conditions'; ?>">Terms & Conditions</a>
					</li>
					<li><a href="<?php echo SITE_URL . 'get-cms-by-slug/privacy-statement'; ?>">Privacy Policy</a></li>
				</ul>
			</div>
		</div>
		<div class="container-flex copyright ">
			<div>
				<p>©
					<?php echo date("Y"); ?>, All rights reserved
				</p>
			</div>
			<div>
				<img src=<?php echo ASSET_URL . "images/Payment.png"; ?> alt="">
			</div>
			<div>
				<p>©
					<?php echo date("Y"); ?>, All rights reserved
				</p>
			</div>
		</div>
		<div class="container-flex ">
			<div>
				<p>Vraj Fresh is your go-to place in New Jersey to shop for fresh vegetables, fruits, dairy items or
					groceries. Visit us for the fresh, hygienic and original branded food products or order online
					through our website. Our home delivery service option gives you 100% safe and contactless shopping
					experience for your essentials.</p>
			</div>
		</div>
	</div>
</footer>
<style>
.ui-autocomplete .ui-menu-item{
  display:block;
  text-align: left;
  padding: 5px 0;
}
.ui-autocomplete{
	max-height: 200px;
	overflow-y: scroll;
}
</style>
<?php require_once('login.php'); ?>
<?php require_once('signup.php'); ?>
<?php require_once('forgot_password.php'); ?>
<a href="#" id="scroll"><span></span></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<!-- <script src="<?php echo ASSET_URL . 'js/login.js'; ?>"></script> -->
<script src="<?php echo ASSET_URL . 'js/signup.js'; ?>"></script>
<script src="<?php echo ASSET_URL . 'js/forgot_password.js'; ?>"></script>
<script src="<?php echo ASSET_URL . 'js/jquery.zoom.js'; ?>"></script>
<script>
	$(document).ready(function() {
		// ==================== start zipcode popup ====================
		// console.log(sessionStorage.getItem("zipcode_popup"))
		if (!Cookies.get("zipcode")=='1' && !sessionStorage.getItem("zipcode_popup")) {
			jQuery(".popup").show();
			sessionStorage.setItem("zipcode_popup", "1");
			// Cookies.set("zipcode_popup", '1');
		}
		jQuery(".close-popup").click(function(){
			jQuery(".popup").hide();
		});
		// $( "#zipcode_popup" ).on('change',function(e){
		// 	$("#zipcode_popup_value").val('');
		// });
		$( "#zipcode_popup" ).autocomplete({
			appendTo: $("#zipcode_popup_data"),
			source: function( request, response ) {
				var json_request = {
                	"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
					"term": request.term,
            	};
				$.ajax({
					"type": "POST",
					"url": api_url_prefix + 'get-zipcodes-autocomplete',
					"data": JSON.stringify(json_request),
					"dataType": "JSON",
					success: function(data) {
						if (!data.data) {
							// console.log('no data found')
							var result = [{
								label: 'Zipcode not found',
								value: response.term
							}];
							response(result);
						} else {
							// console.log('zipcode data')
							// console.log(data.data)
							response($.map(data.data, function(item) {
								return {
								label: item.zipcode+'('+item.area_name+')',
								value: item.zipcode
								}
							}));
						}

					},
					error: function(data) {
						$('input.suggest-user').removeClass('ui-autocomplete-loading');  
					}
				});
			},
			minLength: 3,
			// autoFocus: true,
			change: function(event, ui) {
                if(ui.item){
        			$("#zipcode_popup_value").val(ui.item.value);
				}else{
					$("#zipcode_popup_value").val('');
				}
			}
		});
		
		// ==================== end zipcode popup ====================

		$('.accordion-toggle').on('click', function() {
			$(this).next('.accordion').slideToggle('slow');
			$(this).children().toggleClass("fa-plus fa-minus");
		});

		$(function() {
			var availableProducts = ["Alabama", "Alaska", "Arizona"] <?php //echo json_encode($products);
																		?>;
			$("#search").autocomplete({
				source: availableProducts
			});
		});
		/* EXAMPLE - https://jqueryui.com/autocomplete/#remote-jsonp */

		/*$( "#search" ).autocomplete({
		source: function( request, response ) {
		$.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-products',
		"data": JSON.stringify({
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "is_active_only": "1",
        "search_keyword": request.term,
        "page_no": "0",
        "sort_column": "product_id",
        "sort_order": "desc"
		}),
		dataType: "JSON",
		success: function( data ) {
			response( data );
		}
		});
		},
		minLength: 3,
		select: function( event, ui ) {
		},
		open: function() {
		$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
		$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
		});*/



	});
</script>
<!-- Global site tag (gtag.js) - Google Analytics + Google Adwords -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-179492286-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-363117506');
  gtag('config', 'UA-179492286-1');
</script>
</body>

</html>