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
	$("#frmPromoCode").validate({
    ignore: [],
		rules: {
			customer: {
				required: true
			},	
			notes: {
				required: true
			},			
		},
		messages: {
			customer: {
				required: "This field is required."
			},
			notes: {
				required: "This field is required."
			}
		},
		submitHandler: function(form) {  form.submit(); } });
});

function getCustomerPayment(customer_id){
	if(customer_id!=""){
		$.ajax({
			type: "post",
			url: site_url+"adminpanel/controller_payment_log/get_customer_part_payment_details/"+customer_id,
			method: "POST",
			cache: false,
			success: function(data) 
			{ 
				$('#customer_payment_details').html(data);
				if(data.trim()!="Order Not Found."){
					$('#actionbtn').show();
				}	
				if(data.trim()=="Order Not Found."){
					$('#actionbtn').hide();
				}					
			}
		});
	}
}

function setTotalAmount(chk,amount)
{
	var total_amount = parseFloat($("#amount").val());
	if(chk.checked)
	{
		total_amount = total_amount + parseFloat(amount);
	}
	else
	{
		total_amount = total_amount - parseFloat(amount);
	}
	var temp_total_amount = total_amount.toFixed(2);
	
	$("#amount").val(temp_total_amount);
}

function frmValidate()
{
	if($("#amount").val()<=0){
		alert('Please select one order');
		return false;
	}
}

$(document).on('click', '.re_part_order_payment', function(){
	var action_url = $(this).attr('rel');
	var title = $(this).attr('title');
	$.ajax({
		type        : "POST",
		url         : action_url,
		data		: {title:title },
		success: function(results) {
			$(document.body).css({'cursor' : 'default'});
			$('#recordDetailsPopUp').modal('show');
			$('#detailsPopUpData').html(results);
			$('#detailsPopUpTitle').html('');
		},
		error: function() {
			$(document.body).css({'cursor' : 'default'});
			toastr.error('Oops..!! something went wrong please try again.');
		}
	});
});
$(document).on('click', '.close', function(){
	var customer_id = $('#re_part_customer_id').val();
	if(customer_id!=""){
		getCustomerPayment(customer_id);
	}
	
});
