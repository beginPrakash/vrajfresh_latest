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
	$("#brand__form").validate({
		rules: {
			brand_name: {
				required: true
			},
			
		},
		messages: {
			brand_name: {
				required: "Please add brand title."
			},
		},
		submitHandler: function(form) { form.submit(); }
	});
	
	$("#submit").click(function(){
		
	});

});

function convertToSlug()
{
	var Text = $("#cmstitle").val().trim();
	var urlval = Text.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
	$("#cms_url").val(urlval);
}