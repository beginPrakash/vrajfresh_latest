<div class="login-popup" id="signup-popup">
  <div class="social-login-item">
    <img src="<?php echo ASSET_URL . 'images/logo.png'; ?>">
    <h3>Create New Account!</h3>
    <!--<ul><li class="fb"><a href="
      <?php echo BASE_URL . 'Facebook'; ?>" class=""><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a></li><li class="google"><a href="
      <?php echo BASE_URL . 'Google'; ?>"><i class="fa fa-google-plus" aria-hidden="true"></i> Sign In with Google+</a></li></ul><span>OR</span>-->
    <strong>Fill the forms bellow to register</strong>
    <div id="signup_message"></div>
    <form id="registration" method="post">
      <input type="email" name="email" placeholder="Email">
      <input type="text" name="user" placeholder="Full Name">
      <input type="password" name="password" placeholder="Password">
      <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
      <input type="hidden" name="user_role_id" value="4">
      <button onclick="signup()">Sign up</button>
      <div class="bottom_links"> All fields are required. <a href="#" class="bs_popuplink" id="vraj-login-1">
          <i class="fa fa-lock"></i> Login </a>
      </div>
    </form>
    <div class="close-vraj-login">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
  </div>
</div>