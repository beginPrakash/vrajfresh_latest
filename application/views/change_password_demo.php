<?php require_once('common/header.php'); ?>
<style>
    .error {
        color: red;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<script src="https://www.google.com/recaptcha/api.js?render=6Lcqhi4hAAAAALt9JTNpQj383nwxlpnWY-MCJSf-"></script>
<div class="container">

    <form id="change_password" method="post">
        <div class="form-group">
            <label>User Email<span class="text-danger">*</span></label>
            <input type="email" name="user_email" class="form-control">
        </div>

        <div class="form-group">
            <label>New Password<span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control">
        </div>

        <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
        <input type="hidden" name="user_role_id" value="4">
        <button onclick="change_password()" class="btn btn-primary">Change Password</button>
    </form>
    <!-- <div><a href="<?php //echo BASE_URL.'forgot-password';?>">Forgot Password?</a></div>
    <div><a href="<?php // echo BASE_URL.'change-password';?>">Change Password</a></div> -->
    <div id="message"></div>
    <!-- <div class="panel panel-default">
        <?php
        /*if(isset($facebook_url))
        {
        echo '<div align="center"><a href="'.$facebook_url.'"><img src="'.ASSET_URL.'images/facebook-login-button.png" style="width:250px;" /></a><a href="'.$google_url.'"><img src="'.ASSET_URL.'images/google-button.png" /></a></div>';
        }
        else
        {
        echo '<div class="panel-heading">Welcome User</div><div class="panel-body">';
        echo '<img src="'.$_SESSION["user_image"].'" class="img-responsive img-circle img-thumbnail" />';
        echo '<h3><b>Name :</b> '.$_SESSION['user_name'].'</h3>';
        echo '<h3><b>Email :</b> '.$_SESSION['user_email_address'].'</h3>';
        echo '<h3><a href="logout.php">Logout</h3></div>';
        }*/
        ?>
   </div> -->
</div>
<?php require_once('common/common_js.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>

    function change_password() {
        $("#change_password").validate({
            rules: {
                user_email: 'required',
                password: 'required',

            },
            messages: {
                user_email: 'This field is required',
                password: 'This field is required'
            },
            submitHandler: function (form) {
                form.submit();
                var form = $("#change_password");

                $.ajax({
                    "type": "POST",
                    "url": api_url_prefix + 'change-password',
                    "data": form.serialize(),
                    "dataType": "JSON",
                    "success": function (response) {
                        $("#message").val(response.success_message);
                        window.location.replace('<?php echo BASE_URL . 'signup'; ?>');

                    },
                    "error": function (e) {
                        $("#message").val(response.errors);

                    }
                });
            },

        });

    }
</script>
<?php require_once('common/footer.php'); ?>