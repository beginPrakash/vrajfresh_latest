<?php require_once('common/header.php'); ?>
    <section class="vraj-title">
        <div class="container">
            <div class="popular-product" id="">
                <div class="welcome">
                    <button class="btn">
                        <a href="<?php echo BASE_URL.'signup'; ?>">Create an Account</a>
                    </button>
                </div>
                <div class="login">
                    <form id="Frmlogin" name="Frmlogin" method="post" action="<?php echo BASE_URL . 'login'; ?>">
                        <div class="sign-up">
                            <p> Don't You have an account?</p>
                            <button id="btn-signup-popup">
                                <a href="<?php echo BASE_URL.'signup'; ?>">SIGN UP</a>
                            </button>
                        </div>
                        <h3> Welcome Back</h3>
                        <h5> Login to your account </h5>

                        <div id="message"></div>

                        <div class="form-data">
                            <div class="mail">
                            <label>Username</label>
                            <input type="email" name="email" placeholder="Your Email">
                        </div>
                        <div class="password">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="Your Password">
                        </div>
                            <input type="hidden" name="oauth_key"
                                value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
                            <input type="hidden" name="user_role_id" value="4">
                            <div class="bottom_links">
                                <a href="javascript:void(0);" class="bs_popuplink forgot-new">Forgot Password?</a>
                            </div>
                        </div>
                        <button id="btn-signup-popup" onclick="signin()" class="login-btm">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php require_once('common/common_js.php'); ?>

<link href="<?php echo ASSET_URL; ?>css/toastr.css" rel="stylesheet" />
<script src="<?php echo ASSET_URL; ?>js/toastr.js"></script>

<?php require_once('scripts/home_js.php'); ?>
<?php require_once('common/footer.php'); ?>
<script>
    $(".popup").hide();
    document.getElementById("Frmlogin").addEventListener("keydown", function(event) {
        if (event.keyCode == 13) {
            $("#Frmlogin").submit();
        }
    });
</script>
