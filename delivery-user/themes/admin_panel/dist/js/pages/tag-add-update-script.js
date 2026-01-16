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
	$("#tag__form").validate({
		rules: {
			tag_name: {
				required: true
			},
			
		},
		messages: {
			tag_name: {
				required: "Please add tag name."
			},
		},
		submitHandler: function(form) { form.submit(); }
	});
	
	$("#submit").click(function(){
		
	});

});