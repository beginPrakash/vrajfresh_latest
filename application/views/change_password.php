<?php require_once('common/header.php'); ?>
<style>
    .Short {
        width: 100%;
        background-color: #dc3545;
        margin-top: 5px;
        height: 3px;
        color: #dc3545;
        font-weight: 500;
        font-size: 12px;
    }

    .Weak {
        width: 100%;
        background-color: #ffc107;
        margin-top: 5px;
        height: 3px;
        color: #ffc107;
        font-weight: 500;
        font-size: 12px;
    }

    .Good {
        width: 100%;
        background-color: #28a745;
        margin-top: 5px;
        height: 3px;
        color: #28a745;
        font-weight: 500;
        font-size: 12px;
    }

    .Strong {
        width: 100%;
        background-color: #d39e00;
        margin-top: 5px;
        height: 3px;
        color: #d39e00;
        font-weight: 500;
        font-size: 12px;
    }
</style>
<section class="categories-banner">
    <h2>Change Password</h2>
</section>
<section class="my-account-page">
    <div class="container container-flex">
        <?php require_once('common/sidebar.php'); ?>
        <div class="account-info">
            <div class="billing-left">
                <?php if (isset($_GET['is_success']) && $_GET['is_success'] == '1') { ?>
                    <div class="alert-success">
                        <?php echo $this->session->flashdata('success_message'); ?>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['is_error']) && $_GET['is_error'] == '1') { ?>
                    <div class="alert alert-danger">
                        <?php echo $this->session->flashdata('error_message'); ?>
                    </div>
                <?php } ?>
                <form id="change_password_form" method="post"
                    action="<?php echo BASE_URL . 'change_password_process'; ?>">

                    <b>Password change</b><br>
                    <br>
                    <div id="strengthMessage"></div><br>
                    <label>Current Password </label>
                    <input type="password" name="current_password" id="current_password">
                    <?php echo form_error("current_password", "<div class='form-error'>", "</div>"); ?>
                    <label>New Password</label>
                    <input type="password" name="new_password" id="new_password">
                    <?php echo form_error("new_password", "<div class='form-error'>", "</div>"); ?>
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password">
                    <?php echo form_error("confirm_password", "<div class='form-error'>", "</div>"); ?>
                    <input type="hidden" name="user_id" id="user_id"
                        value="<?php echo $this->session->userdata['logged_in']['user_id']; ?>">
                    <input type="hidden" name="user_role_id" id="user_role_id"
                        value="<?php echo $this->session->userdata['logged_in']['user_role_id']; ?>">
                    <input type="hidden" name="user_name" id="user_name"
                        value="<?php echo $this->session->userdata['logged_in']['user_name']; ?>">
                    <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
                    <button type="submit" onClick="return checkValidation();">Save changes</button>
                    <!-- set cookie to forgot password NO -->
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once('common/common_js.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
    var passwordWeek = true;

    function checkValidation() {
        if ($("#current_password").val() == '') {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Weak');
            $('#strengthMessage').html("Please enter current password.");
            return false;
        } else if ($("#new_password").val() == '') {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Weak');
            $('#strengthMessage').html("Please enter new password.");
            return false;
        } else if ($("#confirm_password").val() == '') {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Weak');
            $('#strengthMessage').html("Please enter confirm password.");
            return false;
        } else if ($("#new_password").val() != $("#confirm_password").val()) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Weak');
            $('#strengthMessage').html("New paasword and confirm password should be same.");
            return false;
        } else if (passwordWeek) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Weak');
            $('#strengthMessage').html("Entered new password is week, password should be strong.");
            return false;
        } else {
            Cookies.remove('forget_password');
            Cookies.set('forget_password_no', 'no');
            return true;
        }
    }

    function checkStrength(password) {
        var strength = 0;
        if (password.length < 6) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Short');
            return 'New password is too short';
        }
        if (password.length > 7) {
            strength += 1;
        }
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
            strength += 1;
        }
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
            strength += 1;
        }
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
            strength += 1;
        }
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) {
            strength += 1;
        }
        if (strength < 2) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Weak');
            passwordWeek = true;
            return 'New password is weak';
        } else if (strength == 2) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Good');
            passwordWeek = false;
            return 'New password is Good';
        } else {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Strong');
            passwordWeek = false;
            return 'New password is strong';
        }
    }
    $(document).ready(function () {
        $("#my-address").removeClass("active");
        $("#my-account").removeClass("active");
        $("#my-orders").removeClass("active");
        $("#change_password").addClass("active");

        $('#new_password').keyup(function () {
            $('#strengthMessage').html(checkStrength($('#new_password').val()))
        })
    });
</script>
<?php require_once('common/footer.php'); ?>