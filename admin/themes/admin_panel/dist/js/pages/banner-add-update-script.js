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
	$("#banner_frm").validate({
		rules: {
			banner_text: {
				required: true
			},
			
		},
		messages: {
			banner_text: {
				required: "Please add banner text."
			},
		},
		submitHandler: function(form) { form.submit(); }
	});
	
	$("#submit").click(function(){
		
	});

});