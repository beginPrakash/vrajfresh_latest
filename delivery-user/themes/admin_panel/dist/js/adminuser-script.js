$(document).ready(function(){
	$.validator.addMethod(
		"regex",
		function(value, element, regexp) {
			if (regexp.constructor != RegExp)
				regexp = new RegExp(regexp);
			else if (regexp.global)
				regexp.lastIndex = 0;
			return this.optional(element) || regexp.test(value);
		},
		"Please check your input."
	);
	$("form[name='updateProfile']").validate({
		// Specify validation rules
		rules: {
			user_name:  {
				required: true,
				regex: /^[a-zA-Z]+$/,
			},
			user_phone: {
				regex: /^[0-9]+$/,
			},
			user_email: {
				required: true,
				regex: /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,7}$/,
				email: true
			},
		},
		// Specify validation error messages
		messages: {
			user_name: "Please enter your name",
			user_phone: "Please enter your phone number",
			user_email: "Please enter a valid email address"
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	/* updatePassword */
	$("form[name='updatePassword']").validate({
		// Specify validation rules
		rules: {
			oldpassword:  {
				required: true,
			},
			newPassword: "required",
			confirm_password: {
				equalTo: "#newPassword"
			}			
		},
		// Specify validation error messages
		messages: {
			oldpassword: "This field is required",
			newPassword: "This field is required",
			confirm_password: "Enter confirm password same as new password"
		},
		submitHandler: function(form) {
			form.submit();
		}
	});

	/* adminLogin */
	$("form[name='adminLogin']").validate({
		// Specify validation rules
		rules: {
			login_view:  {
				required: true,
				regex: /^[a-zA-Z]+$/,
			},
			password: "required",			
		},
		// Specify validation error messages
		messages: {
			login_view: "Please enter valid username",
			password: "Please enter password",
		},
		submitHandler: function(form) {
			form.submit();
		}
	});

	/* forgotPassword */
	$("form[name='forgotPassword']").validate({
		// Specify validation rules
		rules: {
			email: {
				required: true,
				regex: /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,7}$/,
				email: true
			},
		},
		// Specify validation error messages
		messages: {
			email: "Please enter a valid email address",
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	
}); //ready_function end

$(document).ready(function() {
    $(".show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($(this).closest('.input-group').find('input').attr("type") == "text"){
            $(this).closest('.input-group').find('input').attr('type', 'password');
            $(this).closest('.input-group').find('i').addClass( "fa-eye-slash" );
            $(this).closest('.input-group').find('i').removeClass( "fa-eye" );
        }else if($(this).closest('.input-group').find('input').attr("type") == "password"){
            $(this).closest('.input-group').find('input').attr('type', 'text');
            $(this).closest('.input-group').find('i').removeClass( "fa-eye-slash" );
            $(this).closest('.input-group').find('i').addClass( "fa-eye" );
        }
    });
});