<?php require_once('common/header.php'); ?>
<style>
    .error {
        color: red;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<script src="https://www.google.com/recaptcha/api.js?render=6Lcqhi4hAAAAALt9JTNpQj383nwxlpnWY-MCJSf-"></script>
<div class="container">
    <div id="forgot_password_message"></div>
    <form id="forgot_password" method="post">
        <div class="form-group">
            <label>User Email<span class="text-danger">*</span></label>
            <input type="text" name="user_email" class="form-control">
        </div>

        <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
        <input type="hidden" name="user_role_id" value="4">
        <button onclick="forgot_password()" class="btn btn-primary">Submit</button>
    </form>


</div>
<?php require_once('common/common_js.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>

   /* function forgot_password()
    {
        $("#forgot_password").validate({
            rules: {
                user_name: 'required'
                    
            },
         messages: {
                user_name: 'This field is required'
            },
            submitHandler: function(form) {
               
                form.submit();
                var form=$("#forgot_password");
       
                $.ajax({
                    "type": "POST",
                    "url": api_url_prefix + 'forgot-password',
                    "data": form.serialize(),
                    "dataType": "JSON",
                    "success": function(response) {
                            $("#message").val(response.success_message);
                            window.location.replace('<?php echo BASE_URL . 'signup'; ?>');
                         
                    },
    "error": function(e) {
        console.log(e.responseText);

    }
                });
            },
           
        });
       
    }* /
</script>
<?php require_once('common/footer.php'); ?>