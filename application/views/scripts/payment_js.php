<script type="text/javascript">
//set your publishable key
Stripe.setPublishableKey('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        //enable the submit button
        $('#payBtn').removeAttr("disabled");
        //display the errors on the form
        // $('#payment-errors').attr('hidden', 'false');
        $('#payment-errors').addClass('alert alert-danger');
        $("#payment-errors").html(response.error.message);
    } else {
        var form$ = $("#paymentFrm");
        //get token id
        var token = response['id'];
        //insert the token into the form
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        //submit form to the server
        form$.get(0).submit();
    }
}
$(document).ready(function() {
    //on form submit
    $("#paymentFrm").submit(function(event) {
        //disable the submit button to prevent repeated clicks
        $('#payBtn').attr("disabled", "disabled");

        //create single-use token to charge the user
        Stripe.createToken({
            number: $('#card_num').val(),
            cvc: $('#card-cvc').val(),
            exp_month: $('#card-expiry-month').val(),
            exp_year: $('#card-expiry-year').val()
        }, stripeResponseHandler);

        //submit from callback
        return false;
    });

    var input = document.getElementById("card_num");

    input.onkeydown = function(event) {
        if (input.value.length <= 27) {
            var key = event.keyCode || event.charCode;

            // Include key codes for both top row number keys (48 to 57) and number keypad (96 to 105)
            if ((key > 31 && key < 48) || (key > 57 && key < 96) || key > 105) {
                return false;
            } else if (key == 8 || key == 46) {
                // Allow backspace (key code 8) and delete (key code 46) keys
            } else {
                if (input.value.length > 0) {
                    if (
                        input.value.length == 4 ||
                        input.value.length == 9 ||
                        input.value.length == 14
                    ) {
                        input.value += " ";
                    }
                }
            }
        } else {
            return false;
        }
    };

});

$(document).ready(function() {

    if ($("#user_id").val() > 0) {
        get_content(api_url_prefix);
    }
});

function get_content(api_url_prefix) {
    var user_id = $("#user_id").val();
    console.log(user_id);
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "user_id": user_id
    };
    var user = "";

    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-user-detail',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            console.log("response:" + response);
            if (response.data != "" && response.data != null) {
                var card_number = response.data[0].card_num;
                var card_number_without_space = card_number.replace(/\s/g, '');

                const card_number_final = card_number_without_space.match(/.{1,4}/g);

                $("#card_num").val(card_number_final.join(" "));
                $("#card-expiry-month").val(response.data[0].card_exp_month);
                $("#card-expiry-year").val(response.data[0].card_exp_year);
            }

        },
        "error": function(response) {
            console.log(response.errors);
        }
    });
}
</script>