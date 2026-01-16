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
	$("#category__form").validate({
		rules: {
			category_name: {
				required: true
			},
			
			display_order: {
				required: true,
				digits: true,
			},
			
		},
		messages: {
			category_name: {
				required: "Please add category name."
			},
			
			display_order: {
				required: "Please add display Order.",
				digits:"Please enter numbers Only"
			},
		},
		submitHandler: function(form) { form.submit(); }
	});
	
	$("#submit").click(function(){
		/*var parent_category_id= $("#parent_category_id").val();
		$("#parent_category_iderror").text('');

		if(parent_category_id == "0"){
			$("#parent_category_iderror").text('This Field is required');
			return false;
		}*/
		
	});

});