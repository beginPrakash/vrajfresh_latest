 <script>
    function loadGPayButton(amount) {
        const paymentsClient = new google.payments.api.PaymentsClient({ environment: 'TEST' });

        const paymentDataRequest = {
        apiVersion: 2,
        apiVersionMinor: 0,
        allowedPaymentMethods: [{
            type: 'CARD',
            parameters: {
            allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
            allowedCardNetworks: ['MASTERCARD', 'VISA']
            },
            tokenizationSpecification: {
            type: 'PAYMENT_GATEWAY',
            parameters: {
                gateway: 'stripe',
                'stripe:version': '2020-08-27',
                'stripe:publishableKey': '<?php echo STRIPE_PUBLISHABLE_KEY; ?>'
            }
            }
        }],
        
        transactionInfo: {
            totalPriceStatus: 'ESTIMATED',
            totalPrice: amount,
            currencyCode: 'USD'
        }
        };
    
        function onGooglePayLoaded() {
        paymentsClient.isReadyToPay(paymentDataRequest).then(function(response) {
            if (response.result) {
            const button = paymentsClient.createButton({ onClick: onGooglePaymentButtonClicked });
            document.getElementById('gpay_div_container').appendChild(button);
            }
        });
        }

        function onGooglePaymentButtonClicked() {
            paymentsClient.loadPaymentData(paymentDataRequest).then(function(paymentData) {
            var generated_pay_token = paymentData.paymentMethodData.tokenizationData.token;
            if(generated_pay_token != ""){
               $('#gpay_token_serialize').val(generated_pay_token).serializeArray();
            }else{
                $("#checkout-submit").prop('disabled', false);
                $("#checkout-submit").text('Proceed to Pay');
                alert("Please fill out all required fields or Select an option for replacement policy.");
                return false;
            }
            

            // // Send token to server
            // fetch('process.php', {
            //   method: 'POST',
            //   headers: { 'Content-Type': 'application/json' },
            //   body: JSON.stringify({ token: paymentData.paymentMethodData.tokenizationData.token })
            // })
            // .then(res => res.json())
            // .then(data => {
            //   alert(data.message || 'Payment processed');
            // });
        });
        }

        onGooglePayLoaded();
    }

    // 🔄 Load initial GPay button
    loadGPayButton($('#cart_total').val());

    // 🔁 Update GPay button when amount changes
    $('#gpay_pay_div').click(function() {
        const newAmount = $('#cart_total').val();
        loadGPayButton(newAmount);
    });
  </script>
