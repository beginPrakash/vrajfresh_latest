<script>

/***********************************************************************************************************

										TIP FUNCTIONS START

/***********************************************************************************************************/
const front_urls = "<?php echo BASE_URL ?>";
$(document).on('change', 'input[type=radio][name=shipping_id]', function() {
    var zipcode = $(this).attr('data-zipcode');
    var order_val = parseInt($('#hdn_subtotal').val());
    
    var minim_val;
    if(Cookies.get("zipcode") != zipcode){
        var json_request = {
            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
            "zipcode": zipcode
        };

        $.ajax({
            'async': false,
            "type": "POST",
            "url": api_url_prefix + 'get-zipcode-detail',
            "data": JSON.stringify(json_request),
            "dataType": "JSON",
            "success": function(response) {
                
                if (response.data != null) {
                    minim_val = response.data[0].minimum_order_value;
                } 

            },
            "error": function(response) {
            }
        });
        if (confirm("Selected address has different zipcode. Please review the order before you proceed.")) {
            $("#zipcode").val(zipcode);
            //setZipCodeCookie('header');
            if(order_val < minim_val){
                //console.log(front_urls + "cart-detail");
                window.location.href = front_urls + "cart-detail?zipcode="+zipcode;
                return false;
            }
        } else {
            $('input[type=radio][name=shipping_id]').prop('checked', false);
        }    
    } else {
        var state = $(this).attr('data-state');
        var tax = $(this).attr('data-tax');
        update_state(state, tax);
    }
});

function CloseCheckoutModels ()
{
    jQuery("body").removeClass("open-login");
      
    jQuery("div#signup-popup").hide();
    jQuery("div#login-popup").hide();
    jQuery("div#forgot-popup").hide();
    jQuery("div#BillingAddressModal").hide();
    jQuery("div#ShippingAddressModal").hide();
    jQuery("div#EditShippingAddressModal").hide();
    jQuery("div#EditBillingAddressModal").hide();
}

$(document).on('click', '#SameShippingAddressBtn', function() {
    
    CloseCheckoutModels();
    if($("#shipping_address_count").val() > 0){
        
        var user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
        var id = $('input[type=radio][name=shipping_id]:checked').val();
        if(id > 0){

            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "user_id": user_id,
                "id": id,
                "type": 'shipping',
            };
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'get-checkout-address-details',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function(response) {
                    
                    if (response.data != null) {
                        
                        $("#FrmEdit_billing_Address")[0].reset();

                        $("#billing_first_name").val(response.data.first_name);
                        $("#billing_last_name").val(response.data.last_name);
                        $("#billing_street_address").val(response.data.shipping_street_address);
                        $("#billing_apartment").val(response.data.shipping_apartment);
                        $("#billing_city").val(response.data.shipping_city);
                        $("#billing_zip_code").val(response.data.shipping_zipcode);
                        $("#billing_phone").val(response.data.shipping_phone);
                        if(response.data.shipping_state_id != ""){
                            $('#billing_state_id option[data-id="' + response.data.shipping_state_id + '"]').prop('selected', true);
                        }
                        
                        
                        jQuery("body").addClass("open-login");
                        jQuery("div#BillingAddressModal").show();
                    } else {
                        
                        $("#Frm_billing_Address")[0].reset();
                        jQuery("div#BillingAddressModal").show();
                    }
                },
                "error": function(response) {
                    
                    $("#Frm_billing_Address")[0].reset();
                    jQuery("div#BillingAddressModal").show();
                }
            }); 
        } else {
            
            $("#Frm_billing_Address")[0].reset();
            jQuery("div#BillingAddressModal").show();
        }
    } else {
        
        $("#Frm_billing_Address")[0].reset();
        jQuery("div#BillingAddressModal").show();
    }

    
});
$(document).on('click', '.edit_shipping_data_btn', function() {
    
    var user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
    var id = $(this).attr('data-id');
    var type = 'shipping';

    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "user_id": user_id,
        "id": id,
        "type": type,
    };
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-checkout-address-details',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {
                
                $("#FrmEdit_shipping_Address")[0].reset();
                
                $("#edit_shipping_first_name").val(response.data.first_name);
                $("#edit_shipping_last_name").val(response.data.last_name);
                $("#edit_shipping_street_address").val(response.data.shipping_street_address);
                $("#edit_shipping_apartment").val(response.data.shipping_apartment);
                $("#edit_shipping_city").val(response.data.shipping_city);
                $("#edit_shipping_zipcode").val(response.data.shipping_zipcode);
                $("#edit_shipping_phone").val(response.data.shipping_phone);
                $('#edit_shipping_state_id option[data-id="' + response.data.shipping_state_id + '"]').prop('selected', true);
                $("#edit_shipping_id").val(response.data.shipping_id);
                CloseCheckoutModels();
                
                jQuery("body").addClass("open-login");
                jQuery("div#EditShippingAddressModal").show();
            } else {
                CloseCheckoutModels();
            }
        },
        "error": function(response) {
            CloseCheckoutModels();
        }
    });

    
});
$(document).on('click', '.edit_billing_data_btn', function() {
    
    var user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
    var id = $(this).attr('data-id');
    var type = 'billing';

    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "user_id": user_id,
        "id": id,
        "type": type,
    };
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-checkout-address-details',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            
            if (response.data != null) {
                
                $("#FrmEdit_billing_Address")[0].reset();

                $("#edit_billing_first_name").val(response.data.first_name);
                $("#edit_billing_last_name").val(response.data.last_name);
                $("#edit_billing_street_address").val(response.data.billing_street_address);
                $("#edit_billing_apartment").val(response.data.billing_apartment);
                $("#edit_billing_city").val(response.data.billing_city);
                $("#edit_billing_zipcode").val(response.data.billing_zipcode);
                $("#edit_billing_phone").val(response.data.billing_phone);
                $('#edit_billing_state_id option[data-id="' + response.data.billing_state_id + '"]').prop('selected', true);
                $("#edit_billing_id").val(response.data.billing_id);
                CloseCheckoutModels();
                
                jQuery("body").addClass("open-login");
                jQuery("div#EditBillingAddressModal").show();
            } else {
                CloseCheckoutModels();
            }
        },
        "error": function(response) {
            CloseCheckoutModels();
        }
    }); 
});

$(document).on('click', '.delete_shipping_data_btn', function() {
    
    var user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
    var id = $(this).attr('data-id');
    var type = 'shipping';

    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "user_id": user_id,
        "id": id,
        "type": type,
    };
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'delete-checkout-address-details',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            
            if (response.data != null) {
                location.reload();
            } 
        },
        "error": function(response) {
         
        }
    }); 
});

$(document).on('click', '.delete_billing_data_btn', function() {
    
    var user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
    var id = $(this).attr('data-id');
    var type = 'billing';

    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "user_id": user_id,
        "id": id,
        "type": type,
    };
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'delete-checkout-address-details',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            
            if (response.data != null) {
                location.reload();
            } 
        },
        "error": function(response) {
         
        }
    }); 
});

function update_state(state, tax){
    
    $('#state option').prop('selected', false);
    $('#state option[data-id="' + state + '"]').prop('selected', true);
    $("#state").attr("data-tax", tax);
    taxCalc();
}

function UpdateAddress(AddressType) {
    
    var user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
    var prefix = 'edit_' + AddressType + '_';

    var FrmUpdateAddressRules = {};
    FrmUpdateAddressRules[prefix + 'first_name'] = 'required';
    FrmUpdateAddressRules[prefix + 'last_name'] = 'required';
    FrmUpdateAddressRules[prefix + 'street_address'] = 'required';
    //FrmUpdateAddressRules[prefix + 'apartment'] = 'required';
    FrmUpdateAddressRules[prefix + 'city'] = 'required';
    FrmUpdateAddressRules[prefix + 'state_id'] = 'required';
    FrmUpdateAddressRules[prefix + 'zipcode'] = { required: true, number: true };
    FrmUpdateAddressRules[prefix + 'phone'] = { required: true, number: true };
    

    $("#FrmEdit_" + AddressType + "_Address").validate({
        rules: FrmUpdateAddressRules,
        messages: {
            [prefix + 'first_name'] : 'First name is required',
            [prefix + 'last_name'] : 'Last name is required',
            [prefix + 'street_address'] : 'Street address is required',
            //[prefix + 'apartment'] : 'Apartment is required',
            [prefix + 'city'] : 'City is required',
            [prefix + 'state_id'] : 'State is required',
            [prefix + 'zipcode'] : {
                required: 'Zip code is required',
                email: 'Please enter only digits'
            },
            [prefix + 'phone'] : {
                required: 'Phone number is required',
                email: 'Please enter only digits'
            }
        },
        submitHandler: function(form) {
        // form.submit();
            var form = $("#FrmEdit_" + AddressType + "_Address");
            $.ajax({
                "type": "POST",
                "url": "<?php echo BASE_URL; ?>" + "update-checkout-address",
                "data": form.serialize()+ "&AddressType=" + AddressType + "&user_id=" + user_id,
                "success": function(response) {
                    if(response.status == 1)
                    {
                        if(AddressType == "shipping"){
                            
                            var Selected_shipping_id = $('input[type=radio][name=shipping_id]:checked').val();
                            var updated_shipping_id = $('#edit_shipping_id').val();
                            if(Selected_shipping_id == updated_shipping_id){
                                
                                var edit_state_option = $("#edit_shipping_state_id").val().split('|');
                                var tax = edit_state_option[0];
                                var state = edit_state_option[1];
                                update_state(state, tax);
                            }
                        }
                        

                        $("#"+AddressType+"Data").empty();
                        $("#"+AddressType+"Data").append(response.html);
                        CloseCheckoutModels();
                        $("#FrmEdit_" + AddressType + "_Address")[0].reset();
                        $("#" + prefix + "zipcode").val(Cookies.get("zipcode"));
                        location.reload();

                    } else {
                        CloseCheckoutModels();
                        $("#FrmEdit_" + AddressType + "_Address")[0].reset();
                        $("#" + prefix + "zipcode").val(Cookies.get("zipcode"));
                    }
                },
                "error": function(response) {
                    CloseCheckoutModels();
                    $("#Frm_" + AddressType + "Address")[0].reset();
                    $("#" + prefix + "zipcode").val(Cookies.get("zipcode"));
                }
            });
            return false;
        },
    });
}

function SaveAddress(AddressType) {

    var user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
    var prefix = AddressType+'_';

    var FrmAddAddressRules = {};
    FrmAddAddressRules[prefix + 'first_name'] = 'required';
    FrmAddAddressRules[prefix + 'last_name'] = 'required';
    FrmAddAddressRules[prefix + 'street_address'] = 'required';
    //FrmAddAddressRules[prefix + 'apartment'] = 'required';
    FrmAddAddressRules[prefix + 'city'] = 'required';
    FrmAddAddressRules['check_' + prefix + 'state_id'] = 'required';
    FrmAddAddressRules[prefix + 'zipcode'] = { required: true, number: true };
    FrmAddAddressRules[prefix + 'phone'] = { required: true, number: true };

    $("#Frm_" + prefix + "Address").validate({
        rules: FrmAddAddressRules,
        messages: {
            [prefix + 'first_name'] : 'First name is required',
            [prefix + 'last_name'] : 'Last name is required',
            [prefix + 'street_address'] : 'Street address is required',
            //[prefix + 'apartment'] : 'Apartment is required',
            [prefix + 'city'] : 'City is required',
            ['check_' + prefix + 'state_id'] : 'State is required',
            [prefix + 'zipcode'] : {
                required: 'Zip code is required',
                email: 'Please enter only digits'
            },
            [prefix + 'phone'] : {
                required: 'Phone number is required',
                email: 'Please enter only digits'
            }
        },
        submitHandler: function(form) {
        // form.submit();
            var form = $("#Frm_" + prefix + "Address");
            $.ajax({
                "type": "POST",
                "url": "<?php echo BASE_URL; ?>" + "add-checkout-address",
                "data": form.serialize()+ "&AddressType=" + AddressType + "&user_id=" + user_id,
                "success": function(response) {
                    if(response.status == 1)
                    {

                        if($("#shipping_address_count").val() == 0 && AddressType == "shipping"){
                            
                            var edit_state_option = $('#check_' + prefix + 'state_id').val().split('|');
                            var tax = edit_state_option[0];
                            var state = edit_state_option[1];
                            update_state(state, tax);
                        }

                        $("#"+AddressType+"Data").empty();
                        $("#"+AddressType+"Data").append(response.html);
                        CloseCheckoutModels();
                        $("#Frm_" + prefix + "Address")[0].reset();
                        $("#" + prefix + "zipcode").val(Cookies.get("zipcode"));
                        location.reload();

                    } else {
                        CloseCheckoutModels();
                        $("#Frm_" + prefix + "Address")[0].reset();
                        $("#" + prefix + "zipcode").val(Cookies.get("zipcode"));
                    }
                },
                "error": function(response) {
                    CloseCheckoutModels();
                    $("#Frm_" + prefix + "Address")[0].reset();
                    $("#" + prefix + "zipcode").val(Cookies.get("zipcode"));
                }
            });
            return false;
        },
    });
}

function addTipAmount(tip_amount) {

	var sub_total = parseFloat($("#hdn_order_amount").val());

	console.log("tip_amount:" + tip_amount);

	console.log("sub_total:" + sub_total);

	if (tip_amount > 0) {

		console.log('in ifffffffffffffffffffffff');

		var total = parseFloat(sub_total + tip_amount).toFixed(2);

		console.log("total:" + total);

		$("#tip_remove_button").show();

		$(".cart-tip").show();

		$("#cart-total").html(total);

		$("#tip").html(tip_amount);

        $("#added_order_tip").val(tip_amount);


		$("#hdn_order_amount").val(parseFloat(total - tip_amount).toFixed(2));

		$("#cart_total").val(parseFloat(total));

		$("#hdn_tip_amount").val(tip_amount);

	} else {

		console.log('in elseeeeeeeeeeeeeeee');

		$(".cart-tip").hide();

		$(".tip_remove_button").hide();



		$("#cart-total").html(sub_total);

		$("#tip").html("$0");

        $("#added_order_tip").val('0');

		$("#hdn_order_amount").val(parseFloat(sub_total));

		$("#cart_total").val(parseFloat(sub_total));

		$("#hdn_tip_amount").val(0);

	}

}

function addFixedTipAmount(tip_amount) {

    var sub_total = parseFloat($("#hdn_order_amount").val());

    console.log("tip_amount:" + tip_amount);

    console.log("sub_total:" + sub_total);

    if (tip_amount > 0) {

        console.log('in ifffffffffffffffffffffff');

        var total = parseFloat(sub_total + tip_amount).toFixed(2);

        console.log("total:" + total);

        //$("#tip_remove_button").show();

        $(".cart-tip").show();

        $("#cart-total").html(total);

        $("#tip").html(tip_amount);

        $("#hdn_order_amount").val(parseFloat(total - tip_amount).toFixed(2));

        $("#cart_total").val(parseFloat(total));

        $("#hdn_tip_amount").val(tip_amount);

    } else {

        console.log('in elseeeeeeeeeeeeeeee');

        $(".cart-tip").hide();

        $(".tip_remove_button").hide();

        $("#added_order_tip").val('0');

        $("#cart-total").html(sub_total);

        $("#tip").html("$0");



        $("#hdn_order_amount").val(parseFloat(sub_total));

        $("#cart_total").val(parseFloat(sub_total));

        $("#hdn_tip_amount").val(0);

    }

    }



function addCustomTip() {

    removeTipAmount(0);
    
    $("#added_order_tip").val('0');

    $("#tip_amount").show();

    $(".order_tip").removeClass('active');

    $("#add_tip_button").show();

}



function addCustomTipAmount() {

    $("#tip_error_message").hide();

    var tip_amount = parseFloat($("#tip_amount").val());

    console.log("addCustomTipAmount tip_amount:" + tip_amount);

    if (tip_amount > 0) {

        $("#add_tip_button").hide();

        $("#added_order_tip").val('0');
        addTipAmount(tip_amount);

    } else {

        $("#added_order_tip").val('0');
        addTipAmount(0);

        $("#tip_error_message").show();

        return false;

    }

}



function removeTipAmount() {

    $(".order_tip").removeClass('active');
    var tip_amount = 0;

    $("#added_order_tip").val('0');

    $("#tip").html(tip_amount);

    $("#tip_amount").val(tip_amount);

    $("#hdn_tip_amount").val(tip_amount);

    $("#tip_amount").hide();

    $("#add_tip_button").hide();

    $("#tip_remove_button").hide();

    $("#linkRemoveTip").hide();



    var tip = $("#tip").text();

    var sub_total = $("#hdn_subtotal").val();



    var total = $("#hdn_order_amount").val();//(parseFloat(sub_total) + parseFloat( <?php echo $preparation_cost + $packaging_cost; ?> )).toFixed(2);

    var cart_tax = $("#state_tax").val();
    //var cart_tax = 0;



    var discount = sessionStorage.getItem("discount_amount");

    var discount_amount = 0;

    if (discount != null && discount != '' && discount != undefined) {

        var discount_amount = parseFloat(discount);

    }

    //var cart_net_total = parseFloat(total + cart_tax).toFixed(2);
    //var cart_net_total = parseFloat(total) + parseFloat(cart_tax);
    var cart_net_total = parseFloat(total);
    cart_net_total = cart_net_total.toFixed(2);



    $("#hdn_order_amount").val(cart_net_total);

    $("#cart-total").html(cart_net_total);

    $("#cart_total").val(cart_net_total);



}



$(document).on('click', '.order_tip', function() {

    $("#add_tip_button").hide();

    // Remove previously selected tip

    $("#tip_amount").hide();

    $("#hdn_tip_amount").val(0);

    $(".order_tip").removeClass("active");
    $(this).addClass("active");

    // Add new tip

    var tip_amount = $(this).data('tip');

    if($("#added_order_tip").val() == tip_amount){

        addFixedTipAmount(0);
        $("#added_order_tip").val('0');
        $(".order_tip").removeClass("active");
        return false;
    } else {
        $("#added_order_tip").val(tip_amount);
        addFixedTipAmount(tip_amount);
    }
});



/***********************************************************************************************************

										TIP FUNCTIONS END

/***********************************************************************************************************/





function ManageShipingAddressBox(obj) {

    if (obj.checked) {

        $("#ship_to_different_address").val('Yes');

        $("#shipingAddressBox").show();

    } else {

        $("#ship_to_different_address").val('No');

        $("#shipingAddressBox").hide();

    }

}

$('input[type=radio][name=refund_for_unavailable]').change(function() {

    if ($(this).is(':checked')) {
        var selectedCardId = $(this).val();
        if(selectedCardId == "2"){
            $(".substitution_products_div").show();
            $('input[type=checkbox][name="substitution_product_ids[]"]').prop('checked', false);
            $('input[type=checkbox][name="substitution_product_ids[]"]').prop('disabled', false);
            $(".substitution_products_div").show();
        } else if(selectedCardId == "3"){
            $('input[type=checkbox][name="substitution_product_ids[]"]').prop('checked', true);
            $('input[type=checkbox][name="substitution_product_ids[]"]').prop('disabled', true);
            $(".substitution_products_div").show();
        } else if(selectedCardId == "4"){
            $('input[type=checkbox][name="substitution_product_ids[]"]').prop('checked', false);
            $('input[type=checkbox][name="substitution_product_ids[]"]').prop('disabled', true);
            $(".substitution_products_div").hide();
        }
        
    }
});

$('input[type=radio][name=card_id]').change(function() {
    if ($(this).is(':checked')) {
        var selectedCardId = $(this).val();
        if(selectedCardId == 0){
            $("#card-element").show();
            $(".card-form").show();
        } else {
            $("#card-element").hide();
            $(".card-form").hide();
        }
    }
});
function checkstripeCard ()
{
    if($('input[type=radio][name=card_id]:checked').val() > 0){
        
        $("#CardPaymentMethod").val('');
        $("#CardToken").val('');
        $("#StripeCardID").val('');
        $("#submit").click();
        $("#submit").click();
        
    } else {
        
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                
                $("#checkout-submit").prop('disabled', false);
                $("#checkout-submit").text('Proceed to Pay');
                return false;

            } else {
                
                stripe.createPaymentMethod({
                    type: 'card',
                    card: card,
                    billing_details: {
                        name: 'Jenny Rosen',
                    },
                })
                .then(function(PaymentMethodResult) {
                    if (PaymentMethodResult.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = PaymentMethodResult.error.message;

                        console.log(PaymentMethodResult.error.message, "error message2");
                        
                        $("#checkout-submit").prop('disabled', false);
                        $("#checkout-submit").text('Proceed to Pay');
                        return false;
                    } else {
                        console.log(PaymentMethodResult, 'stripe');
                        
                        $("#CardPaymentMethod").val(PaymentMethodResult.paymentMethod.id);
                        $("#CardToken").val(result.token.id);
                        $("#StripeCardID").val(result.token.card.id);
                        $("#submit").click();
                    }
                    // Handle result.error or result.paymentMethod
                });

                

            }
        });

       
    }
}

	
function validateForm() {

    

    $("#checkout-submit").prop('disabled', true);
    $("#checkout-submit").text('Loading...');

    var isValid = false;
    var AllErrorFixed = 0;
    
    var SubstituteError = 1;
    var ShippingAddressError = 1;
    var BillingAddressError = 1;
    var CardAllError = 1;
    //var CardNumberError = 1;
    //var CardExpiryError = 1;
    //var CardSecurityCodeError = 1;
    //var CardHolderError = 1;
    var DeliveryTypeError = 1;
    var DeliveryTypeDateError = 0;

    if($('input[type=radio][name=delivery_type]:checked').val() == "one_day"){
        DeliveryTypeDateError = 1;
        if($("#delivery_one_day_date").val() != ""){
            DeliveryTypeError = 0;
            DeliveryTypeDateError = 0;
        }
        
    } else {
        if($('input[type=radio][name=delivery_type]:checked').val() != ""){
            DeliveryTypeError = 0;
        }
    }
    if(DeliveryTypeDateError == 0){
        $("#delivery_one_day_date-error").text("");
    } else {
        $("#delivery_one_day_date-error").text("Please select delivery date.");
    }

    if($("#shipping_address_count").val() > 0){
        if($('input[type=radio][name=shipping_id]:checked').val() > 0){
            ShippingAddressError = 0;
        }
    }

    /* if($("#billing_address_count").val() > 0){
        if($('input[type=radio][name=billing_id]:checked').val() > 0){
            BillingAddressError = 0;
        }
    } */
    if($('input[type=radio][name=billing_id]:checked').val() != ""){
        if(typeof($('input[type=radio][name=billing_id]:checked').val()) == 'undefined'){
            $('#billing-address-error').text("Please select billing address.");
        } else {
            BillingAddressError = 0;
            $('#billing-address-error').text("");
        }    
    } else {
        $('#billing-address-error').text("Please select billing address.");
    }

    var substitute = document.getElementsByName("refund_for_unavailable");
    for (var i = 0; i < substitute.length; i++) {
        if (substitute[i].checked) {
            SubstituteError = 0;
            //isValid = true;
            break;
        }
    }

    if(ShippingAddressError == 1){
        if($("#shipping_address_count").val() > 0){
            $('#shipping-address-error').text("Please select shipping address.");
        } else {
            $('#shipping-address-error').text("Please add new shipping address.");
        }
    } else {
        $('#shipping-address-error').text("");
    }

    /* if(BillingAddressError == 1){
        if($("#billing_address_count").val() > 0){
            $('#billing-address-error').text("Please select billing address.");
        } else {
            $('#billing-address-error').text("Please add new billing address.");
        }
    } else {
        $('#billing-address-error').text("");
    } */

    if(SubstituteError == 1){
        $("#replace-policy-error").text("Please select an option.");
    } else {
        $("#replace-policy-error").text("");
    }
    
    if(SubstituteError == 0 && ShippingAddressError == 0 && BillingAddressError == 0 && DeliveryTypeError == 0 && DeliveryTypeDateError == 0){
        AllErrorFixed = 1;
    }

    if(AllErrorFixed == 1){
        checkstripeCard();
    } else {
        $("#checkout-submit").prop('disabled', false);
        $("#checkout-submit").text('Proceed to Pay');
        alert("Please fill out all required fields or Select an option for replacement policy.");
        return false;
    }
}

<?php
    $cartContents = isset($_SESSION["cart_contents"]) ? json_encode($_SESSION["cart_contents"]) : '';
    $escapedCartContents = addslashes($cartContents);
?>

var cart_contents = '<?php echo $escapedCartContents; ?>';

function taxCalc() {

    console.log('taxCalc calling');

    <?php

    $preparation_cost = $preparation['preparation_cost'] = $this->config-> item('preparation_cost');

    $packaging_cost = $packaging['packaging_cost'] = $this->config-> item('packaging_cost'); 

	?>

    $(".cart-tax").show();

    var selectedOption = $("#state").val().split('|');

    var selectedValue = selectedOption[0];

    console.log("selectedValue" + selectedValue);

    var selectedstate_id = selectedOption[1];

    var taxable_products_amount = <?php echo $taxable_products_amount ?>;

    console.log("taxable_products_amount" + taxable_products_amount);

    var tax_add = <?php echo $tax_add; ?>;

    var tax_product = (selectedValue / 100) * <?php echo $taxable_products_amount ?>;
   

    $("#state").removeAttr("data-tax");

    $("#state").attr("data-tax", selectedValue);

    var sub_total = <?php echo number_format($total_price + $preparation_cost + $packaging_cost, 2, '.', '') ?>;

    // $("#hdn_order_amount").val();

    var price = (selectedValue / 100) * taxable_products_amount;

    console.log("price" + price);

    console.log("sub_total" + sub_total);

    var total_tax_sub = (parseFloat(sub_total) + parseFloat(price)).toFixed(2);

    $("#cart-tax").text(parseFloat(price).toFixed(2));

    $("#cart-total").html(parseFloat(total_tax_sub));

    $("#hdn_order_amount").val(parseFloat(total_tax_sub));

    $("#cart_total").val(parseFloat(total_tax_sub));

    $("#state_tax").val(parseFloat(selectedValue));

    $("#product_name").val(parseFloat(price));

    $("#shipping_state_id").val(selectedstate_id);

    var tip = $("#tip").text();



    //if discount is applied then calculate discount on total amount

    var discount = sessionStorage.getItem("discount_amount");

    var order_amount_session = sessionStorage.getItem("order_amount");

    if (order_amount_session != null) {

        var order_amount = order_amount_session.replace(/,/g, '');

    } else {

        var order_amount = order_amount_session;

    }

    var coupon_code = sessionStorage.getItem("coupon_code");



    if (discount != null && discount != '' && discount != undefined) {

        var discount_amount = parseFloat(discount);

        var total_amount = total_tax_sub;

        var total_amount_after_discount = total_amount - discount_amount;

        var total_amount_after_discount = total_amount_after_discount.toFixed(2);

        $("#cart-total").html(parseFloat(total_amount_after_discount));

        $("#hdn_order_amount").val(parseFloat(total_amount_after_discount));

        $("#cart_total").val(parseFloat(total_amount_after_discount));

        $("#discount_amount").val(parseFloat(discount_amount));

        $("#coupon_code").val(coupon_code);

    }



    if (tip != null && tip != '' && tip != undefined && discount != null && discount != '' && discount != undefined) {

        $("#cart_total").val(parseFloat(total_amount_after_discount) + parseFloat(tip));

        $("#cart-total").html(parseFloat(total_amount_after_discount) + parseFloat(tip));

    }



    if (tip != null && tip != '' && tip != undefined && discount == null && discount == '' && discount == undefined) {

        $("#cart_total").val(parseFloat(total_tax_sub) + parseFloat(tip));

        $("#cart-total").html(parseFloat(total_tax_sub) + parseFloat(tip));

    }



    // product_name

    if (tax_add == 1) {

        var product_tax = $("#product_tax").val(parseFloat(price).toFixed(2));

    } else {

        var product_tax = $("#product_tax").val(0);

    }



    if (product_tax == 0 || product_tax == null || product_tax == "") {

        $(".cart-product-tax").hide();

    } else {

        $(".cart-product-tax").show();

    }



    //  console.log(selectedState);

    // Add your onclick function code here, using the selectedState variable

    if (selectedValue == "") {

        $(".cart-tax").hide();

        $(".cart-product-tax").hide();

    }



    UpdateCartTax(selectedValue);

}



function UpdateCartTax(taxPercentage) {

    console.log('test1');
    if (taxPercentage == "") {

        taxPercentage = 0;

    }



    <?php
    
    $cartContentsArr = isset($_SESSION["cart_contents"]) ? $_SESSION["cart_contents"] : '';
    $cartContentsNew = '';
    if(is_array($cartContentsArr)){
        
        unset($cartContentsArr['cart_total'], $cartContentsArr['total_items']);
        $cartContentsArr = json_encode($cartContentsArr);
        $cartContentsNew = addslashes($cartContentsArr);
    }    
    ?>

    var cart_contents = '<?php echo $cartContentsNew; ?>';
    var cartObject = $.parseJSON(cart_contents);
    
    var counter = 1;
    $.each(cartObject, function(i) {

        var subtotal = cartObject[i].subtotal;
        if (cartObject[i].product_tax == 1) {
            $("#product-tax-" + counter).text("$" + (taxPercentage * subtotal / 100).toFixed(2));
            subtotal = (parseFloat(subtotal) + (parseFloat(taxPercentage) * parseFloat(subtotal) / 100)).toFixed(2);
        } else {
            $("#product-tax-" + counter).text("$0.00");
        }

        $("#product-price-" + counter++).text("$" + parseFloat(subtotal).toFixed(2));

    });

}



function get_delivery_extra_charge(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "configuration_key": "'two_hour_delivery_charge'"

    };



    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-configurations-by-key',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {



            $("#extra_delivery_charge").html('$' + response.data[0].configuration_value);

            var sub_total = $("#cart-total").text();

            var delivery_charge = $("#extra_delivery_charge").text();

            var total = parseFloat(sub_total.replace('$', '')) + parseFloat(delivery_charge.replace('$', '')) + parseFloat(amount) + parseFloat($preparation_cost) + parseFloat($packaging_cost);

            $("#cart-total").html(parseFloat(total));

            $("#hdn_order_amount").val(parseFloat(total));



        },

        "error": function(response) {

            console.log(response.errors);

        }

    });

}



function isNumber(evt) {

    evt = (evt) ? evt : window.event;

    var charCode = (evt.which) ? evt.which : evt.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57)) {

        return false;

    }

    return true;

} 







$(document).ready(function() {



    <?php

    $preparation_cost = $preparation['preparation_cost'] = $this->config-> item('preparation_cost');

    $packaging_cost = $packaging['packaging_cost'] = $this->config-> item('packaging_cost'); 

	?>

    $(".cart-tip").hide();

    $('.extra_delivery_charge').hide();

    var discount = sessionStorage.getItem("discount_amount");

    var order_amount_session = sessionStorage.getItem("order_amount");

    var discount_id = sessionStorage.getItem("coupon_id");

    if (order_amount_session != null) {

        var order_amount = order_amount_session.replace(/,/g, '');

    } else {

        var order_amount = order_amount_session;

    }

    var coupon_code = sessionStorage.getItem("coupon_code");

    if (discount != "" && discount != null) {

        $('#dis_count_amount').val(discount);

        $('#discount_id').val(discount_id);

        $("#discount").html('-$' + discount);

    } else {

        $("#discount").html('0.00');

    }

    if (order_amount != "" && order_amount != null) {

        $("#cart-total").html((parseFloat(order_amount) + parseFloat( <?php echo $preparation_cost + $packaging_cost; ?>)).toFixed(2));

        $("#hdn_order_amount").val((parseFloat(order_amount) + parseFloat( <?php echo $preparation_cost + $packaging_cost; ?>)).toFixed(2));

    } else {

        $("#cart-total").html( <?php echo number_format($total_price + $preparation_cost + $packaging_cost, 2, '.', ''); ?>);

        $("#hdn_order_amount").val( <?php echo number_format($total_price + $preparation_cost + $packaging_cost, 2, '.', ''); ?>);

    }

    if (coupon_code != "" && coupon_code != null) {

        $("#apply_coupon_code").html(coupon_code);

    } else {

        $("#coupon_detail").hide();

    }

    $(document).on('change', '.chk_delivery_type', function() {

        if (this.value == 'two_hour') {

            $(".extra_delivery_charge").show();

            get_delivery_extra_charge(api_url_prefix);



        } else {

            $('.extra_delivery_charge').hide();

        }

    });



    var current = new Date();

    var hr = current.getHours();

    var min = current.getMinutes();

    if (hr > 14) {

        $("#delivery_type_hour").hide();

        $("#delivery_after_hr").hide();

    }





    $('#autofill-btn').click(function() {

        var billingStreetAddress = $('#street_address').val();

        var billingApartment = $('#apartment').val();

        var billingCity = $('#city').val();

        var billingState = $('#state').val();

        var billingZipCode = $('#zip_code').val();

        var billingPhone = $('#phone').val();



        $('#shiping_street_address').val(billingStreetAddress);

        $('#shiping_apartment').val(billingApartment);

        $('#shiping_city').val(billingCity);

        //$('#shiping_state').val(billingState);

        $('#shiping_zip_code').val(billingZipCode);

        $('#shiping_phone').val(billingPhone);



        var billingState = $('#state').val().split('|');

        console.log("selectedstate_id" + $('#state').val());

        var selectedValue = billingState[0];

        var selectedstate_id = billingState[1];



        //if(selectedstate_id === undefined){selectedstate_id = $('#state').val();}

        console.log("selectedstate_id" + selectedstate_id);

        $('#shiping_state').val(selectedstate_id);



        return false;

    });

	

	  var get_state_from_cookie = Cookies.get('state');

    if (get_state_from_cookie != null && get_state_from_cookie != '' && get_state_from_cookie != undefined) {

        // $('#state').val(get_state_from_cookie);

        //commented by bbn $('#state').find('option:selected').val(get_state_from_cookie);

    }



    if (sessionStorage.getItem('coupon_id') != null && sessionStorage.getItem('coupon_id') != '' && sessionStorage.getItem('coupon_id') != undefined) {

        $('a#cart-details').hide();

    } else {

        $('a#cart-details').show();

    }

    $(".cart-tax").hide();

    $(".cart-product-tax").hide();





    $('#state').on('change', function() {

        taxCalc();

    });



    $("#shiping_state").on('change', function() {

        var selectedOption = $(this).val().split('|');

        var selectedValue = selectedOption[0];

        var selectedstate_id = selectedOption[1];

        //if(selectedstate_id === undefined){selectedstate_id = $(this).val();}

        console.log(selectedstate_id);

        $("#shiping_state").removeAttr("data-tax");

        $("#shiping_state").attr("data-tax", selectedValue);

        $("#billing_state_id").val(selectedstate_id);

    });

    taxCalc();

	

	

});



</script>