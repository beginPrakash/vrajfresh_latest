$(document).ready(function(){ 
	$('#brands').multiselect({
	columns: 1,
	placeholder: '-- Select Brand --',
	selectAll: true,
	});
	
  	$('#exclude_category').multiselect({
		columns: 1,
		placeholder: '-- Select Category --',
		selectAll: true,
	});
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
	$("#promotional_code__form").validate({
		rules: {
			promotional_code: {
				required: true
			},
			start_from: {
				required: true
			},
			valid_upto: {
				required: true
			},
			promotional_type: {
				required: true
			},
			apply_to: {
				required: true
			},
			specific_customer_id: {
				required: true
			},
			apply_to_product: {
				required: true
			},
			discount_type: {
				required: true
			},
			discount_value: {
				required: true
			},
			product_discount_value: {
				required: true
			},
		},
		messages: {
			promotional_code: {
				required: "Please add promotional code."
			},
						
			start_from: {
				required: "Please add start from Date."
			},
			valid_upto: {
				required: "Please add valid upto date."
			},			
			promotional_type: {
				required: "Please select promotional type."
			},
			apply_to: {
				required: "Please select apply to."
			},
			specific_customer_id: {
				required: "Please add specific customer email."
			},
			apply_to_product: {
				required: "Please select apply to product."
			},
			discount_type: {
				required: "Please select discount type."
			},
			discount_value: {
				required: "Please add discount value."
			},
			product_discount_value: {
				required: "Please select product discount value."
			},
		},
		submitHandler: function(form) {
			form.submit(); 
		}
	});
	

});

 $("#search-box").keyup(function(){
	 
	var siteUrl = window.location.origin +"/qeworld";
	  
	$.ajax({
	type: "POST",
	url: siteUrl + "/get-user-email/",
	data:'keyword='+$(this).val(),
	beforeSend: function(){
		//$("#search-box").css("background","#FFF url(siteUrl + /themes/admin_panel/dist/img/LoaderIcon.gif) no-repeat 165px");
	},
	success: function(data){
		//alert(data);
		$("#suggesstion-box").show();
		$("#suggesstion-box").html(data);
		$("#search-box").css("background","#FFF");
	}
	});
}); 
	
 $("input[name=submit]").click(function(){

	var siteUrl = window.location.origin +"/qeworld";

	$.ajax({
	type: "POST",
	url: siteUrl +"/adminpanel/controller_promotional_code/check_user_email/",
	data:'keyword='+$("#search-box").val(),
	success: function(data){		
		if(data.trim()=="success"){
			$("#error-email-id").hide();
		}
		else{
			$("#error-email-id").show();
			return false;
		}
	}
	});
});  
	
 $('#promotional_code').keyup(function()
{
	var yourInput = $(this).val();
	re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
	var isSplChar = re.test(yourInput);
	if(isSplChar)
	{
		var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
		$(this).val(no_spl_char);
	}
}); 

 function selectCountry(val,user_id) {
	$("#search-box").val(val);
	$("#suggesstion-box").hide();
	$("#search-box-hides").val(user_id);
}



var checkProductvalidation = false;
function setDiscountType(discount_type)
{
	$("#discount_type_span").html("<b>"+discount_type+"</b>");
	if(discount_type=='$')
	{
		$('#discount_value').attr('maxlength', '4');
	}
	else
	{
		$('#discount_value').attr('maxlength', '2');
	}
}
function showHideClientGroupDiv(apply_to)
{
	if(apply_to=='SG')
	{
		$("#search-box").prop('required',false);
		$("#client_group_div").show();
		$("#customer_div").hide();
	}
	else
	{
		$("#search-box").prop('required',false);
		$("#client_group_div").hide();
		$("#customer_div").hide();
	}	
}
function validation()
{
	//$('#to option').prop('selected', true);
	var startDate = $("#start_from").val();
	var endDate = $("#valid_upto").val();
	var submitFlag = true;
	/* alert(startDate);
	alert(endDate); */
	if(startDate>endDate){
		alert('Please select end data greater than start date');
		return false;
	}	
	if(checkProductvalidation)
	{
		//validation start
		var submitFlag = true;
		for(i=1;i<=$("#product_counter").val();i++)
		{
			if( $(".selProduct"+i).val()=='' )
			{
				alert('Please select product from dropdown');
				$(".selProduct"+i).focus();
				return false;
			}
			if( $(".discountValue"+i).val()=='' )
			{
				alert('Please enter discount value');
				$(".discountValue"+i).focus();
				return false;
			}
			if( $(".selDiscountType"+i).val()=='' )
			{
				alert('Please select dicount type from dropdown');
				$(".selDiscountType"+i).focus();
				return false;
			}
		}
	}
	return true;
}
/******************** PRODUCT **************************/

function showHideProductsDiv(apply_to)
{
	
	if(apply_to=='A')
	{
		checkProductvalidation = false;
		$("#all_product_div").show();
		$("#specific_product_div").hide();
		document.getElementById('discount_type').required=true;
		document.getElementById('discount_value').required=true;
	}
	else
	{
		checkProductvalidation = true;
		//$("#all_product_div").hide();
		$("#all_product_div").show();
		$("#discount_type").val('');
		$("#discount_value").val('');
		$("#discount_type_span").val('');
		$("#specific_product_div").show();
		document.getElementById('discount_type').required=false;
		document.getElementById('discount_value').required=false; 
	}	
}
function checkPreselected(select_obj)
{
	var current_selection = select_obj.value;
	var tempCounter = 0;
	$( ".selProduct" ).each(function( index ) {
		if(this.value==current_selection)
		{
			tempCounter++;
		}
		
		if(tempCounter>1)
		{
			alert('You have already selected this product, please select different product.');
			$(select_obj).val("");
		}
	});
} 
//special charr not allow in coupon code textbox


/* function validate_title(){
	//alert("hii");
		var m_id = $("#m_id").val().trim();
		var membership_title = $("#membership_title").val().trim();
		if(membership_title !=""){
		var res = false;
		$.ajax({
			type        : "POST",
			url         : $("#check_title").val(),
			data        : "membership_title="+membership_title+"&m_id="+m_id,
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
	if(res){
		$('#membership_title').focus();
		$('.alert-danger').hide();
		$("#membership_title").siblings('.alert-danger').show();
		$("#membership_title").siblings('.alert-danger').text('Membership Title already exist! please enter the different title');
		return false;
	}
	
} */