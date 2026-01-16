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
	$("#testimonial_frm").validate({
		ignore: [],
		rules: {
			customer_name: {
				required: true
			},
			description:{
				required: function() {
					CKEDITOR.instances.editor1.updateElement();
				}, 
			minlength:10
			}		
			
		},
		messages: {
			customer_name: {
				required: "Please add customer name."
			},
			description: {
				required: "Please add Description.",
				minlength:"Please enter 10 characters"
			},
		},
		submitHandler: function(form) { 			
			form.submit(); 
		}
	});
});