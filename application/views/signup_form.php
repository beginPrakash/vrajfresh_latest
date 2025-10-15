<?php require_once('common/header.php'); ?>
   <section class="vraj-title">
        <div class="container">
            <div class="popular-product sign-up-form" id="">
                <div class="welcome sign-up-btn">
                    <button class="btn">
                        <a href="<?php echo BASE_URL.'login'; ?>">Login</a>
                    </button>
                </div>
                <div class="login">
                    <div id="signup_message"></div>
                   <form id="Frmregistration" name="Frmregistration" method="post">
                        <div class="sign-up">
                            <p> Already have an account?</p>
                            <button>
                                <a href="<?php echo BASE_URL.'login'; ?>">Login</a>
                            </button>
                        </div>
                        <div class="form-data">
                            <div class="mail">
                                <label>Username</label>
                                <input type="email" name="email" placeholder="Your Email">
                            </div>
                            <div class="password">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="Your Password">
                            </div>
                            <div class="bottom_links sign-up-bottom-links">
                                <a href="javascript:void(0);" class="bs_popuplink forgot-new">Forgot Password?</a>
                            </div>
                            <div class="name">
                                <div>
                                    <label>First name</label>
                                    <input type="text" name="first_name" placeholder="Your name">
                                </div>
                                <div>
                                    <label>Last name</label>
                                    <input type="text" name="last_name" placeholder="Your name">
                                </div>
                            </div>
                            <div class="number">
                                <label>Phone Number</label>
                                <input type="number" name="phone_number" placeholder="Your number">
                            </div>
                            <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
                            <input type="hidden" name="user_role_id" value="4">
                             <br>
                            <!-- Google reCAPTCHA Widget -->
                            <div class="g-recaptcha" data-sitekey="6Lcv6OQrAAAAAIuJAukHw8QlDHwbdi39bIhYfXqP"></div>
                            <label id="recaptcha-error" class="error" for="recaptcha"></label>
                          
                        </div>
                        <!-- <button id="" onclick="signup()" class="login-btm">Create an account</button> -->
                        <button id="" type="submit" class="login-btm">Create an account</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


<?php require_once('common/common_js.php'); ?>

<link href="<?php echo ASSET_URL; ?>css/toastr.css" rel="stylesheet" />
<script src="<?php echo ASSET_URL; ?>js/toastr.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php require_once('scripts/home_js.php'); ?>
<?php require_once('common/footer.php'); ?>
<script>
    $(".popup").hide();
    document.getElementById("Frmregistration").addEventListener("keydown", function(event) {
        if (event.keyCode == 13) {
            $("#Frmregistration").submit();
        }
    });
</script>