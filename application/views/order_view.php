<?php require_once('common/popup_header.php'); ?>

<?php /*?><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"><?php */?>

<link href='<?php echo ASSET_URL . "css/vraj-fresh-custom.css"; ?>' rel="stylesheet">

<link href='<?php echo ASSET_URL . "css/vraj-fresh-responsive.css"; ?>' rel="stylesheet">

<link href='<?php echo ASSET_URL . "css/style.css"; ?>' rel="stylesheet">

<input type="hidden" id="order_id" value="<?php echo $order_id; ?>">

<div class="container">



    <!-- <span class="close">&times;</span> -->

    <div class="order-details">

        <div>

            <h3>Order Details</h3>

            <ul>

                <li>

                    <label>Order No:</label>

                    <span id="order_id">

                        <?php echo $order_id; ?>

                    </span>

                </li>

                <li>

                    <label>Order Date:</label>

                    <span id="order_date"></span>

                </li>

                <li>

                    <label>Order Amount:</label>

                    <span id="order_amount"></span>

                </li>

                <li>

                    <label>Order Status:</label>

                    <span id="order_status"></span>

                </li>

                <li>

                    <label>Payment Method:</label>

                    <span id="payment_method"></span>

                </li>

            </ul>

        </div>

        <div>

            <h3>Shipping Address</h3>

            <ul>

                <li><span id="shipping_first_name"></span> <span id="shipping_last_name"></span></li>

                <li> <span id="shipping_street_name"></span> <span id="shipping_apartment_name"></span></li>

                <li><span id="shipping_city"></span> <span id="shipping_state"></span> <span

                        id="shipping_country"></span> <span id="shipping_zipcode"></span></li>

                <li>Email:<span id="shipping_email"></span></li>

                <li>Phone:<span id="shipping_phone"></span></li>

            </ul>

        </div>

    </div>

    <div class="order-details">

        <div>

            <h3>Product Details</h3>

            <table class="table table-striped" id="product_detail">

            </table>

        </div>

        <?php /*?><div></div><?php */?>

    </div>

    <div class="order-details">

        <div>

            <h3>Transaction Details</h3>

            <ul>

                <li>

                    <label>Block Amount:</label>

                    <span id="block_amount"></span>

                </li>

                <li>

                    <label>Transaction ID:</label>

                    <span id="block_intent_id"></span>

                </li>

            </ul>

        </div>

        <div>

            <h3></h3>

            <ul>

                <li>

                    <label>Order Amount:</label>

                    <span id="fetch_amount"></span>

                </li>

                <li>

                    <label>Transaction ID:</label>

                    <span id="fetch_intent_id"></span>

                </li>

            </ul>

        </div>

        <?php /*?><div class="col-md-2 col-lg-2"></div><?php */?>

    </div>

    <div class="order-details">

        <ul>

            <li class="coupon">

                <label>Coupon Code</label>

                <span id="coupon_code" class="order_amount"></span>

            </li>

            <li>

                <label>Delivery Date</label>

                <span id="delivery_date" class="order_amount"></span>

            </li>

            <li>

                <label>Order Amount</label>

                <span id="order_sub_amount" class="order_amount"></span>

            </li>

            <li>

                <label>Discount</label>

                <span id="discount" class="order_amount"></span>

            </li>

            <li>

                <label>Shipping Charge</label>

                <span id="delivery_charge" class="order_amount"></span>

            </li>

            <li>

                <label>Tip</label>

                <span id="tip" class="order_amount"></span>

            </li>

            <li>

                <label>Tax</label>

                <span id="tax" class="order_amount"></span>

            </li>

            <li>

                <label>Preparation Cost</label>

                <span id="preparation" class="order_amount"></span>

            </li>

            <li>

                <label>Packaging Cost</label>

                <span id="packaging" class="order_amount"></span>

            </li>

            <li>

                <label style="color:green;">Refund Amount</label>

                <span id="refund_amount" class="order_amount" style="color:green;"></span>

            </li>

            <li>

                <label>Grand Total</label>

                <span id="total_amount" class="order_amount"></span>

            </li>

        </ul>

    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script>

    $(document).ready(function () {

        // var api_url_prefix = "http://localhost/git/vraj-fresh-api/";

        //  var api_url_prefix = "https://dev.thcitsolutions.com/vrajfresh/api/";

        get_content(api_url_prefix);

    });



    function get_content(api_url_prefix) {

        var json_request = {

            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

            "order_id": $("#order_id").val()

        };



        $.ajax({

            "type": "POST",

            "url": api_url_prefix + 'get-order-by-id',

            "data": JSON.stringify(json_request),

            "dataType": "JSON",

            "success": function (response) {

				console.log(response);

                if (response.data != null) {



                    $("#order_date").html(moment(response.data.order_datetime).format('MM-DD-YYYY HH:mm:ss'));

                    $("#order_amount").html('$' + response.data.order_total_amount);

                    $("#order_status").html(response.data.order_status);

                    $("#payment_method").html(response.data.payment_methodtype);

                    $("#shipping_first_name").html(response.data.shipping_first_name);

                    $("#shipping_last_name").html(response.data.shipping_last_name);

                    $("#shipping_street_name").html(response.data.shipping_street_name);

                    $("#shipping_apartment_name").html(response.data.shipping_apartment_name);

                    $("#shipping_zipcode").html(response.data.shipping_zipcode);

                    $("#shipping_state").html(response.data.state + ',');

                    $("#shipping_city").html(response.data.shipping_city + ',');

                    $("#shipping_country").html(response.data.shipping_country);

                    $("#shipping_phone").html(response.data.shipping_phone);

                    $("#shipping_email").html(response.data.shipping_email);

                    $("#shipping_apartment_name").html(response.data.shipping_apartment_name);

                    $("#preparation").html('$' + response.data.preparation_cost);

                    $("#packaging").html('$' + response.data.packaging_cost);

                    if (response.data.delivery_type == "two_hour") {

                        $("#delivery_date").html(moment(response.data.delivery_datetime).format('MM-DD-YYYY HH:mm:ss'));

                    } else {

                        $("#delivery_date").html(moment(response.data.delivery_datetime).format('MM-DD-YYYY'));

                    }

                    $("#order_sub_amount").html('$' + response.data.order_amount);

                    $("#tip").html('$' +response.data.order_tip);

                    if (response.data.coupon.length > 0) {

                        $("#coupon_code").html(response.data.coupon[0].promotional_code);

                    } else {

                        $(".coupon").hide();

                    }



                    $("#delivery_charge").html('$' + response.data.fedex_shipping_charge);

                    if (response.data.discount_amount == "") {

                        $("#discount").html('0');

                    } else {

                        $("#discount").html('$' + response.data.discount_amount);

                    }

                    if (response.data.transactions.length > 0) {

                        $("#block_amount").html(response.data.transactions[0].transaction_amount);

                        $("#block_intent_id").html(response.data.transactions[0].payment_intent_id);

                        if (response.data.transactions.length > 1) {



                            $("#fetch_amount").html(response.data.transactions[1].transaction_amount);

                            $("#fetch_intent_id").html(response.data.transactions[1].payment_intent_id);

                        } else {

                            $("#fetch_amount").html('Yet Remaining');

                            $("#fetch_intent_id").html('Yet Remaining');

                        }



                    }

                    $("#total_amount").html('<b>$' + response.data.order_total_amount + '</b>');



                    var order_diff_amount = response.data.transactions[0].transaction_amount - response.data.order_total_amount;

                    $("#refund_amount").html('<b>$' + parseFloat(order_diff_amount).toFixed(2) + '</b>');



                    var product = "<tr><th>Product Name</th><th>Quantity</th><th>Unit Price</th><th>Total Price</th></tr>";

					var tax = 0;

                    for (let a = 0; a < response.data.products.length; a++) {

                        if (response.data.products[a].product_variant_size != "" && response.data.products[a].product_variant_size != null) {

                            var size = " (" + response.data.products[a].product_variant_size + " g)";

                        } else {

                            var size = '';

                        }

                        product = product.concat("<tr><td>" + response.data.products[a].product_name + size + "</td><td>");

                        if (response.data.products[a].old_qty > 0 && response.data.products[a].qty!=response.data.products[a].old_qty) {

                            product = product.concat("<del>" + response.data.products[a].old_qty + "</del>&nbsp;");

                        }

                        product = product.concat(response.data.products[a].qty + "</td><td>$" + response.data.products[a].unit_price + "</td><td>$" + response.data.products[a].total_amount + "</td></tr>");

						console.log(tax);

						console.log(response.data.products[a].product_tax_amount);

						

						tax = tax + parseFloat(response.data.products[a].product_tax_amount);

						console.log("Tax"+tax);

                    }

                    $("#product_detail").html(product);

					

                    $("#tax").html('$' +tax);

                }





            },

            "error": function (response) {

                $("#order_table").html("<tr>" + response.errors + "</tr>");

            }

        });

    }

</script>