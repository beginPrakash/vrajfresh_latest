<?php require_once('common/header.php'); ?>
<style>
    .error {
        color: red;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<script src="https://www.google.com/recaptcha/api.js?render=6Lcqhi4hAAAAALt9JTNpQj383nwxlpnWY-MCJSf-"></script>
<div class="container">
    <div id="message"></div>
    <form id="registration" method="post" action="">
        <div class="form-group">
            <label>User Name<span class="text-danger">*</span></label>
            <input type="text" name="user_name" class="form-control">
        </div>
        <div class="form-group">
            <label>User Email<span class="text-danger">*</span></label>
            <input type="email" name="user_email" class="form-control">
        </div>
        <div class="form-group">
            <label>Password<span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label>Zipcode<span class="text-danger">*</span></label>
            <input type="text" name="zipcode" class="form-control">
        </div>
        <div class="form-group">
            <label>Mobile No.</label>
            <input type="text" name="mobile_no" class="form-control">
        </div>
        <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
        <input type="hidden" name="user_role_id" value="4">

        <button onclick="signup()" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php require_once('common/common_js.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
    function onClick(e) {
        e.preventDefault();
        grecaptcha.ready(function () {
            grecaptcha.execute('6Lcqhi4hAAAAALt9JTNpQj383nwxlpnWY-MCJSf-', { action: 'submit' }).then(function (token) {
                // Add your logic to submit to your backend server here.
            });
        });
    }
</script>
<script>

    function signup() {
        $("#registration").validate({
            rules: {
                user_name: 'required',
                zipcode: 'required',
                user_email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 4
                },
            },
            messages: {
                user_name: 'This field is required',
                password: { required: 'This field is required', minlength: "Your password must be at least 5 characters long" },
                user_email: { required: 'This field is required', email: 'Enter a valid email' },
                zipcode: 'This field is required'
            },
            submitHandler: function (form) {
                form.submit();
                var form = $("#registration");

                $.ajax({
                    "type": "POST",
                    "url": api_url_prefix + 'add-user',
                    "data": form.serialize(),
                    "success": function (response) {
                        $("#message").html("<div class='text-success'>Registration Successfully</div>");
                    },
                    "error": function (e) {
                        $("#message").html("<div class='text-error'>Registration Not  Successfully</div>");
                    }
                });
            },

        });

    }
</script>
<?php require_once('common/footer.php'); ?>