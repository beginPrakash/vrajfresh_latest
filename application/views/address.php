<?php require_once('common/header.php'); ?>
<?php 
unset($_SESSION['redirect_after_login']);
if (!IsUserLogin()) {
    // Save requested page
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    header("Location: ".BASE_URL . "login");
} ?>
<section class="categories-banner">

    <h2>Address</h2>

</section>

<section class="my-account-page">

    <div class="container container-flex">

        <?php require_once('common/sidebar.php'); ?>

        <div class="account-info">

            <p>The following addresses will be used on the checkout page by default.</p>

            <div id="address_message"></div>

            <input type="hidden" name="user_id" id="user_id"

                value="<?php echo $this->session->userdata['logged_in']['user_id']; ?>">

            <input type="hidden" name="user_role_id" id="user_role_id"

                value="<?php echo $this->session->userdata['logged_in']['user_role_id']; ?>">

            <table width="100%" align="left" cellspacing="5" id="tbl_view">

                <tr>

                    <th colspan="2"><b>Billing Address</b></th>

                </tr>

                <tr>

                    <th>Name</th>

                    <td id="display_name"></td>

                </tr>

                <tr>

                    <th>Address1</th>

                    <td id="address"></td>

                </tr>

                <tr>

                    <th>Address2</th>

                    <td id="address2"></td>

                </tr>

                <tr>

                    <th>City</th>

                    <td id="city"></td>

                </tr>

                <tr>

                    <th>State</th>

                    <td id="state"></td>

                </tr>

                <tr>

                    <th>Zipcode</th>

                    <td id="zip"></td>

                </tr>

                <tr>

                    <th>Phone</th>

                    <td id="phone"></td>

                </tr>

                <tr>

                    <th colspan="2"><b>Shipping Address</b></th>

                </tr>

                <tr>

                    <th>Address 1</th>

                    <td id="shipping_street_address"></td>

                </tr>

                <tr>

                    <th>Address 2</th>

                    <td id="shipping_apartment"></td>

                </tr>

                <tr>

                    <th>City</th>

                    <td id="shipping_city"></td>

                </tr>

                <tr>

                    <th>State</th>

                    <td id="shipping_state"></td>

                </tr>

                <tr>

                    <th>Zipcode</th>

                    <td id="shipping_zip_code"></td>

                </tr>

                <tr>

                    <th>Phone</th>

                    <td id="shipping_phone"></td>

                </tr>

                <tr>

                    <th><a href="#" id="edit">Edit</a></th>

                    <td></td>

                </tr>

            </table>

            <form method="post" id="user_address">

                <table width="100%" align="left" cellspacing="5" id="tbl_edit">

                    <input type="hidden" name="user_id" id="user_id"

                        value="<?php echo $this->session->userdata['logged_in']['user_id']; ?>">

                    <input type="hidden" name="user_role_id" id="user_role_id"

                        value="<?php echo $this->session->userdata['logged_in']['user_role_id']; ?>">

                    <input type="hidden" name="oauth_key" id="oauth_key"

                        value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">

                    <tr>

                        <th colspan="2"><b>Billing Address</b></th>

                    </tr>

                    <tr>

                        <th>Name</th>

                        <td><input type="text" name="display_name" id="edit_user_name" class="address_textbox"></td>

                    </tr>

                    <tr>

                        <th>Address1</th>

                        <td><textarea name="address" id="edit_address" class="address_textbox"></textarea></td>

                    </tr>

                    <tr>

                        <th>Address2</th>

                        <td><textarea name="address2" id="edit_address2" class="address_textbox"></textarea></td>

                    </tr>

                    <tr>

                        <th>City</th>

                        <td><input type="text" name="city" id="edit_city" class="address_textbox">

                        </td>

                    </tr>

                    <tr>

                        <th>State</th>

                        <td>

                            <?php echo form_dropdown('state', $ArrStateOption, 0, 'id="edit_state" class="address_textbox" '); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>Zipcode</th>

                        <td><input type="text" name="zip" id="edit_zipcode" class="address_textbox">

                        </td>

                    </tr>

                    <tr>

                        <th>Phone</th>

                        <td><input type="text" name="phone" id="edit_phone" class="address_textbox"></td>

                    </tr>

                    <tr>

                        <th colspan="2"><b>Shipping Address</b></th>

                    </tr>

                    <tr>

                        <th>Address 1</th>

                        <td><textarea name="shipping_street_address" id="edit_shipping_street_address"

                                class="address_textbox"></textarea></td>

                    </tr>

                    <tr>

                        <th>Address 2</th>

                        <td><textarea name="shipping_apartment" id="edit_shipping_apartment"

                                class="address_textbox"></textarea></td>

                    </tr>

                    <tr>

                        <th>City</th>

                        <td><input type="text" name="shipping_city" id="edit_shipping_city" class="address_textbox">

                        </td>

                    </tr>

                    <tr>

                        <th>State</th>

                        <td><!--<input type="text" name="shipping_state" id="edit_shipping_state" class="address_textbox">-->

                            <?php echo form_dropdown('shipping_state', $ArrStateOption, 0, 'id="edit_shipping_state" class="address_textbox" '); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>Zipcode</th>

                        <td><input type="text" name="shipping_zip_code" id="edit_shipping_zip_code"

                                class="address_textbox">

                        </td>

                    </tr>

                    <tr>

                        <th>Phone</th>

                        <td><input type="text" name="shipping_phone" id="edit_shipping_phone" class="address_textbox">

                        </td>

                    </tr>

                    <tr>

                        <th><button type="button" onclick="edit_detail();">Update</button></th>

                        <td></td>

                    </tr>

                </table>

            </form>

        </div>

    </div>

</section>

<?php require_once('common/common_js.php'); ?>

<script>

    $(document).ready(function () {

        $("#my-address").addClass("active");

        $("#my-account").removeClass("active");

        $("#my-orders").removeClass("active");

        $("#change_password").removeClass("active");

        //  const api_url_prefix = "http://localhost/git/vraj-fresh-api/";

        showProgress('div#spinner');

        get_content(api_url_prefix);

        hideProgress('div#spinner');

        $("#tbl_edit").hide();

        $("#edit").on('click', function () {

            $("#tbl_view").hide();

            $("#tbl_edit").show();

        });

    });



    function get_content(api_url_prefix) {

        var json_request = {



            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

            "user_id": $("#user_id").val(),

            "user_role_id": $("#user_role_id").val()



        };



        $.ajax({

            "type": "POST",

            "url": api_url_prefix + 'get-user-address',

            "data": JSON.stringify(json_request),

            "dataType": "JSON",

            "success": function (response) {

                console.log(response);

				

                if (response.data != "" && response.data != null) {



                    $("#display_name").html(response.data[0].display_name);

                    $("#address").html(response.data[0].address);

                    $("#address2").html(response.data[0].address2);

                    $("#city").html(response.data[0].city);

                    $("#state").html(response.data[0].billing_state_name);

                    $("#zip").html(response.data[0].zip);

                    $("#phone").html(response.data[0].phone);



                    $("#edit_user_name").val(response.data[0].display_name);

                    $("#edit_address").val(response.data[0].address);

                    $("#edit_address2").val(response.data[0].address2);

                    $("#edit_city").val(response.data[0].city);

                    $("#edit_state").val(response.data[0].state);

                    $("#edit_zipcode").val(response.data[0].zip);

                    $("#edit_phone").val(response.data[0].phone);



                    setCookie('address', response.data[0].address);

                    setCookie('shipping_apartment', response.data[0].shipping_apartment);

                    setCookie('address2', response.data[0].address2);

                    setCookie('city', response.data[0].city);

                    setCookie('zip', response.data[0].zip);

                    setCookie('phone', response.data[0].phone);

                    setCookie('state', response.data[0].state);



                    $("#shipping_street_address").html(response.data[0].shipping_street_address);

                    $("#shipping_apartment").html(response.data[0].shipping_apartment);

                    $("#shipping_city").html(response.data[0].shipping_city);

                    $("#shipping_state").html(response.data[0].shipping_state_name);

                    $("#shipping_zip_code").html(response.data[0].shipping_zip_code);

                    $("#shipping_phone").html(response.data[0].shipping_phone);



                    $("#edit_shipping_street_address").val(response.data[0].shipping_street_address);

                    $("#edit_shipping_apartment").val(response.data[0].shipping_apartment);

                    $("#edit_shipping_city").val(response.data[0].shipping_city);

                    $("#edit_shipping_state").val(response.data[0].shipping_state);

                    $("#edit_shipping_zip_code").val(response.data[0].shipping_zip_code);

                    $("#edit_shipping_phone").val(response.data[0].shipping_phone);



                    setCookie('shipping_street_address', response.data[0].shipping_street_address);

                    setCookie('shipping_apartment', response.data[0].shipping_apartment);

                    setCookie('shipping_city', response.data[0].shipping_city);

                    setCookie('shipping_state', response.data[0].shipping_state);

                    setCookie('shipping_zip_code', response.data[0].shipping_zip_code);

                    setCookie('shipping_phone', response.data[0].shipping_phone);

                }



                //  $("#section-content").html(response.data[0].cms_description);



            },

            "error": function (response) {

                console.log(response.errors);

            }

        });

    }

   

    function edit_detail() {



        $.ajax({

            "type": "POST",

            "url": api_url_prefix + 'edit-user-address',

            "data": $("#user_address").serialize(),

            "success": function (response) {

                if (response.success_message != "") {



                    $("#address_message").html('<div class="alert-success">' + response.success_message + '</div>');

                    $("#tbl_view").show();

                    $("#tbl_edit").hide();

                    get_content(api_url_prefix);

                    //window.location = front_url + '/my-address';

                } else {

                    $("#address_message").html('<div class="alert-danger">' + response.errors + '</div>');

                }



            },

            "error": function (response) {

                $("#address_message").html('<div class="alert-danger">' + response.errors + '</div>');

            }

        });

    }

</script>

<?php require_once('common/footer.php'); ?>