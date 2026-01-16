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
	$("#deliveryuser").validate({
		// Specify validation rules
		rules: {
			
			first_name:  {
				required: true,
			},
            last_name:  {
				required: true,
			},
            phone:  {
				required: true,
			},
			city:  {
				required: true,
			},
			zipcode:  {
				required: true,
			},
			email: {
				required: true,
				regex: /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,7}$/,
				email: true
			},
            password:  { required: true },
		},
		// Specify validation error messages
		messages: {
            first_name: "Please enter a first name",
			last_name: "Please enter a last name",
			email: "Please enter a valid email",
			phone: "Please enter phone no",
			city: "Please enter city name",
			zipcode: "Please enter zipcode",
			password: "Please enter password",
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
	
});

function validate_adduser(){
	//alert('sdfsf');
	$('.email_exist').text('');
	$('.user_exist').text('');
	var email = $("#email").val().trim();
	var user_id = $("#user_id").val().trim();
	var user_name = $("#user_exist").val().trim();

	if(email !=""){
		var res = false;
		$.ajax({
			type        : "POST",
			url         : $("#check_email").val(),
			data        : "email="+email+"&user_id="+user_id,
			async		: false,
			success: function(results) {
				if(results.trim() == 'Yes'){
					res = true;
				}else{
					res = false;
				}
			},
			error: function() {
				toastr.error('Oops...! somthing went wrong please try again!!');
			}
		});
	}
	if(user_name !=""){
		var user_name_error = false;
		$.ajax({
			type        : "POST",
			url         : $("#check_user").val(),
			data        : "user_name="+user_name+"&user_id="+user_id,
			async		: false,
			success: function(results) {
				if(results.trim() == 'Yes'){
					user_name_error = true;
				}else{
					user_name_error = false;
				}
			},
			error: function() {
				toastr.error('Oops...! somthing went wrong please try again!!');
			}
		});
	}
	
	if(res){
		$('#email').focus();
        $('.email_exist').text('Email already exist! please enter the different email');
		return false;
	}
	if(user_name_error){
		$('#user_exist').focus();
        $('.user_exist').text('User name already exist! please enter the different user name');
		return false;
	}
	
}
function convertToSlug()
{
	var Text = $("#email").val().trim();
	var emailval = Text.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
	$("#email").val(emailval);
}


