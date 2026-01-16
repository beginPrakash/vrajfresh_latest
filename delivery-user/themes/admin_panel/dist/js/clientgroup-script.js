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
	
	$("#clientgroup__form").validate({
		rules: {
			client_type: {
				required: true
			},
		},
		messages: {
			client_type: {
				required: "Please select client type."
			},
			
			
		},
		submitHandler: function(form) {
			form.submit(); 
		}
	});
	
	$("#clientgroup__form1").validate({
		rules: {
			clientgroup_title: {
				required: true
			},
			
		},
		messages: {	
			clientgroup_title: {
				required: "Please enter clientgroup title."
			},
			
		},
		submitHandler: function(form) {
			form.submit(); 
		}
	});

});


$(function() {
	$("#blocked_situation_section").hide();
	
	$('#contact_type').change(function(){
		if ($(this).val() === 'Yes') 
		{
			$("#blocked_situation_section").show();
		}else{
			$("#blocked_situation_section").hide();
		}
	});
});


	$('#business_category').multiSelect({
	    placeholder: '-- Select Category --',
	    selectAll: true		
	});
	
	$('#plateform').multiSelect({
	    placeholder: '-- Select Plateform --',
	    selectAll: true		
	});
	
	function validation()
	{
		if($("#client_type").val()=='')
		{
			if($("#user_signup_date").val()=='' && $("#user_signup_date_to").val()=='' )
			{
				if($("#amount_paid").val()=='' && $("#amount_paid_to").val()=='' )
				{
					if($("#amount_outstanding").val()=='' && $("#amount_outstanding_to").val()=='' )
					{
						if($("#number_of_order_from").val()=='')
						{
							if($("#number_of_order_to").val()=='')
							{
								if($("#last_order_date").val()=='')
								{
									if($("#no_order_in_last_days").val()=='')
									{
										if($("#selProduct").val()=='')
										{
											if($("#life_cycle_stage").val()=='')
											{
												if($("#contact_type").val()=='')
												{
													if($("#blocked_situation").val()=='')
													{
														if($("#plateform").val()=='')
														{
															if($("#business_category").val()=='')
															{
																if($("#lead_source").val()=='')
																{
																	alert('Select atleat one criteria and try again');
																	return false;
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
		if($("#number_of_order_from").val()>$("#number_of_order_to").val())
		{
			alert('Please enter valid "Number of orders from and to" value');
			return false;
		}
		return true;
	}
	function showHideMembershipDateDiv(client_type)
	{
		if(client_type=="m")
		{
			$("#member_date_div").show();
		}
		else
		{
			$("#member_date_div").hide();
			$("#membership_purchase_date").val('');
		}
	}
	function titleHideShow(client_group)
	{
		if(client_group==0)
		{
			$("#client_group_title").show();
			$("#client_group_dd").hide();
		}
		else
		{
			$("#client_group_title").hide();
			$("#client_group_dd").show();
		}			
	}
	function validation_client()
	{
		if($("#client_group").val()>0)
		{
			return true;
		}
		else
		{
			if($("#clientgroup_title").val()=='')
			{
				alert('Please enter client group name.');
				return false;
			}
		}
		return true;
	}
	
	function view_client(id) 
	{
		$.ajax({
			
			type: "post",
			url: "http://192.168.10.2:8082/qeworld/adminpanel/controller_clientgroup/viewClientGroupDetails/"+id,
			method: "POST",
			cache: false,
			success: function(data) 
			{ 
				$('#general_popup_content').html(data);	
				$('#general_popup_title').html('View Group Customer');				
			}
		});
		return false; 
	}

/* QUICK VIEW POP UP */
$(document).on('click', '.view_action', function(){
	var action_url = $(this).attr('rel');
	var title = $(this).attr('title');
	var id = $(this).attr('id');
	$.ajax({
		type        : "POST",
		url         : action_url,
		data				: { id : id,title:title },
		success: function(results) {
			$(document.body).css({'cursor' : 'default'});
			$('#recordDetailsPopUp').modal('show');
			$('#detailsPopUpData').html(results);
			$('#detailsPopUpTitle').html(title);
		},
		error: function() {
			$(document.body).css({'cursor' : 'default'});
			toastr.error('Oops..!! something went wrong please try again.');
			}
	});
});




