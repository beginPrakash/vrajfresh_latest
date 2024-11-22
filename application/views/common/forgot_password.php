<div class="login-popup" id="forgot-popup">
  <div class="social-login-item">
    <img src="<?php echo ASSET_URL . 'images/logo.png'; ?>">
    <h3>Forgot Password</h3>
    <div id="forgot_password_message"></div>
    <form id="forgot_password" method="post">
      <input type="email" name="email" placeholder="Email">
      <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
      <input type="hidden" name="user_role_id" value="4">
      <button id="btn-forgot-popup" onclick="forget_password()">Submit</button>
      <!-- <div class="bottom_links"><a href="#" class="bs_popuplink forgot">Forgotten Password?</a><a href="#" class="vraj-signup"><i class="fa fa-user"></i> Sign Up</a></div> -->
    </form>
    <div class="close-vraj-login">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
  </div>
</div>