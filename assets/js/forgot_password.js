
function forget_password() {
    $("#forgot_password").validate({
        rules: {
            email: 'required'
        },
        messages: {
            email: 'This field is required'
        },
        submitHandler: function(form) {
	    showProgress('div#spinner');
            //form.submit();
            var form = $("#forgot_password");
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'forgot-password',
                "data": form.serialize(),
                "dataType": "JSON",
                "success": function(response) {
                    if(response.data != null)
                    {
                       // $("#forgot_password_message").html('<div class="alert-success">'+response.success_message+'</div>');
                        Cookies.set('forget_password','yes')
                        jQuery("div#signup-popup").hide();
                        jQuery("div#forgot-popup").hide();
                        $("#forgot_password input[name='email']").val('');
                        hideProgress('div#spinner');
                        jQuery("div#login-popup").hide();
                        alert('You got your new password.Please check your email');
                        window.location = front_url + "/login";
                    } else {

                        $("#forgot_password_message").html('<div class="alert-danger">'+response.errors+'</div>');
                        window.location = front_url + "/login";
                    }
                },
                "error": function(response) {
                    $("#forgot_password_message").html('<div class="alert-danger">'+response.errors+'</div>');
                    window.location = front_url + "/login";
                }
            });
            return false;
        },

    });



}

