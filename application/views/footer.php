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
			<div class="footer-grid"> <img src=<?php echo ASSET_URL . "images/logo.png"; ?> alt="">
				<ul>
					<li><img src=<?php echo ASSET_URL . "images/map-pin.png"; ?>>449, Market Street Saddle Brook, NJ
						07663</li>
					<li><img src=<?php echo ASSET_URL . "images/phone-icon.png"; ?>>201-688-7887</li>
					<li><img src=<?php echo ASSET_URL . "images/mail-icon.png"; ?>>info@vrajfresh.com</li>
					<li><img src=<?php echo ASSET_URL . "images/watch-icon.png"; ?>>Work hours:8:00 - 20:00, Sunday -
						Thursday</li>
				</ul>
			</div>
			<div class="footer-grid">
				<h3>Account</h3>
				<ul>
					<li><a href="#">Wishlist</a></li>
					<li><a href="#">Cart</a></li>
					<li><a href="#">Track Order</a></li>
					<li><a href="#">Shipping Details</a></li>
					<li><a href="#">Report Order</a></li>
					<li><a href="#">Special Request</a></li>
				</ul>
			</div>
			<div class="footer-grid">
				<h3>Useful links</h3>
				<ul>
					<li><a href="#">About Us</a></li>
					<li><a href="#">Conact</a></li>
					<li><a href="#">Promotions</a></li>
					<li><a href="#">New products</a></li>
				</ul>
			</div>
			<div class="footer-grid">
				<h3>Help Center</h3>
				<ul>
					<li><a href="<?php echo SITE_URL . 'refund-and-return-policy'; ?>">Refund Policy</a></li>
					<li><a href="#">Shipping</a></li>
					<li><a href="<?php echo SITE_URL . 'terms-conditions'; ?>">Terms & Conditions</a></li>
					<li><a href="<?php echo SITE_URL . 'privacy-statement'; ?>">Privacy Policy</a></li>
				</ul>
			</div>
		</div>
		<div class="container-flex copyright ">
			<div>
				<p>© 2022, All rights reserved</p>
			</div>
			<div> <img src=<?php echo ASSET_URL . "images/Payment.png"; ?> alt=""> </div>

			<div>

				<p>© 2022, All rights reserved</p>

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

<div class="login-popup" id="login-popup">

	<div class="social-login-item"> <img src="<?php echo ASSET_URL . 'images/logo.png'; ?>">

		<h3>Welcome Back!</h3>

		<ul>

			<li class="fb"><a href="<?php echo BASE_URL . 'Facebook'; ?>" class=""><i class="fa fa-facebook"

						aria-hidden="true"></i> Facebook</a></li>

			<li class="google"><a href="<?php echo BASE_URL . 'Google'; ?>"><i class="fa fa-google-plus"

						aria-hidden="true"></i> Sign In with Google+</a></li>

		</ul>

		<span>OR</span> <strong>Login to your account below</strong>

		<form id="login" method="post" action="<?php echo BASE_URL . 'login'; ?>">

			<input type="email" name="email" placeholder="Email">

			<input type="password" name="password" placeholder="Password">

			<input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">

			<input type="hidden" name="user_role_id" value="4">

			<button id="btn-signup-popup" onclick="signin()">Login</button>

			<div class="bottom_links"> <a href="#" class="bs_popuplink forgot">Forgotten Password?</a> <a href="#"

					class="vraj-signup"><i class="fa fa-user"></i> Sign Up</a> </div>

		</form>

		<div class="close-vraj-login"><i class="fa fa-times" aria-hidden="true"></i></div>

	</div>

</div>

<div class="login-popup" id="signup-popup">

	<div class="social-login-item"> <img src="<?php echo ASSET_URL . 'images/logo.png'; ?>">

		<h3>Create New Account!</h3>

		<ul>

			<li class="fb"><a href="<?php echo BASE_URL . 'Facebook'; ?>" class=""><i class="fa fa-facebook"

						aria-hidden="true"></i> Facebook</a></li>

			<li class="google"><a href="<?php echo BASE_URL . 'Google'; ?>"><i class="fa fa-google-plus"

						aria-hidden="true"></i> Sign In with Google+</a></li>

		</ul>

		<span>OR</span> <strong>Fill the forms bellow to register</strong>

		<form id="registration" method="post">

			<input type="email" name="email" placeholder="Email">

			<input type="text" name="user" placeholder="User">

			<input type="password" name="password" placeholder="Password">

			<input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">

			<input type="hidden" name="user_role_id" value="4">

			<button onclick="signup()">Sign up</button>

			<div class="bottom_links"> All fields are required. <a href="#" class="bs_popuplink" id="vraj-login-1"><i

						class="fa fa-lock"></i> Login</a> </div>

		</form>

		<div class="close-vraj-login"><i class="fa fa-times" aria-hidden="true"></i></div>

	</div>

</div>

<div class="login-popup" id="forgot-popup">

	<div class="social-login-item"> <img src="<?php echo ASSET_URL . 'images/logo.png'; ?>">

		<h3>Forgot Password</h3>

		<form id="forgot_password" method="post">

			<input type="email" name="email" placeholder="Email">

			<input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">

			<input type="hidden" name="user_role_id" value="4">

			<button id="btn-forgot-popup" onclick="forget_password()">Submit</button>



			<!-- <div class="bottom_links"> <a href="#" class="bs_popuplink forgot">Forgotten Password?</a> <a href="#" class="vraj-signup"><i class="fa fa-user"></i> Sign Up</a> </div> -->



		</form>

		<div class="close-vraj-login"><i class="fa fa-times" aria-hidden="true"></i></div>

	</div>

</div>

<a href="#" id="scroll"><span></span></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>



<!-- <script src="<?php //echo ASSET_URL.'js/login.js';?>"></script> -->



<script src="<?php echo ASSET_URL . 'js/signup.js'; ?>"></script>

<script src="<?php echo ASSET_URL . 'js/forgot_password.js'; ?>"></script>



<!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> -->



<script>



	/* ADDED AND COMENTED BY BBN */



	// $(document).ready(function() {



	// 	$(document).on('click', 'ul.tabs li', function() {



	// 		var tab_id = $(this).attr('data-tab');



	// 		$("#tab-content").html('tab_id' + tab_id);



	// 	});



	// });



	/*



	 jQuery(document).ready(function(){



		jQuery('ul.tabs li').click(function(){



			var tab_id = $(this).attr('data-tab');



			jQuery('ul.tabs li').removeClass('current');



			jQuery('.tab-content').removeClass('current');



	jQuery(this).addClass('current');



			jQuery("#"+tab_id).addClass('current');



		})



	}); */



	// Run once the document is ready.



	$(function () {



		// For each image with an SVG class, execute the following function.



		$("img.svg").each(function () {



			// Perf tip: Cache the image as jQuery object so that we don't use the selector muliple times.



			var $img = jQuery(this);



			// Get all the attributes.



			var attributes = $img.prop("attributes");



			// Get the image's URL.



			var imgURL = $img.attr("src");



			// Fire an AJAX GET request to the URL.



			$.get(imgURL, function (data) {



				// The data you get includes the document type definition, which we don't need.



				// We are only interested in the <svg> tag inside that.



				var $svg = $(data).find('svg');



				// Remove any invalid XML tags as per http://validator.w3.org



				$svg = $svg.removeAttr('xmlns:a');



				// Loop through original image's attributes and apply on SVG



				$.each(attributes, function () {



					$svg.attr(this.name, this.value);



				});



				// Replace image with new SVG



				$img.replaceWith($svg);



			});



		});



	});



</script>

</body>



</html>