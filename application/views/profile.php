<?php require_once('common/header.php'); ?>
<style>
    .error {
        color: red;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
<script src="https://www.google.com/recaptcha/api.js?render=6Lcqhi4hAAAAALt9JTNpQj383nwxlpnWY-MCJSf-"></script>
<div class="container">
    <div id="message"></div>
    <form id="registration" method="post" action="">
        <div class="form-group">
            <label>User Name<span class="text-danger">*</span></label>
            <input type="text" name="user_name" id="user_name" class="form-control">
        </div>
        <div class="form-group">
            <label>User Email<span class="text-danger">*</span></label>
            <input type="email" name="user_email" id="user_email" class="form-control">
        </div>
        <div class="form-group">
            <label>Password<span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="form-group">
            <label>Zipcode<span class="text-danger">*</span></label>
            <input type="text" name="zipcode" id="zipcode" class="form-control">
        </div>
        <div class="form-group">
            <label>Mobile No.</label>
            <input type="text" name="mobile_no" id="mobile_no" class="form-control">
        </div>
        <input type="hidden" name="oauth_key" value="F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT">
        <input type="hidden" name="user_role_id" value="4">
        <input type="hidden" name="user_id" id="user_id">

        <button onclick="signup()" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php require_once('common/common_js.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    $(document).ready(function () {
        showProgress('div#spinner');
        get_profile(api_url_prefix);
        hideProgress('div#spinner');
    });
    function get_profile(api_url_prefix) {
        var user_id = $("#user_id").val();
        var json_request = {
            "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
            "user_id": user_id
        };
        var user = "";
        $.ajax({
            "type": "POST",
            "url": api_url_prefix + 'profile',
            "data": JSON.stringify(json_request),
            "dataType": "JSON",
            "success": function (response) {
                if (response.data != "") {
                    product += '<div class="product-grid">';
                    var count = '';
                    if (response.data.length < 10) {
                        count = response.data.length;
                    }
                    else {
                        count = 10;
                    }
                    for (let a = 0; a < count; a++) {
                        product = product.concat('<div class="product-box"><a href="<?php echo BASE_URL; ?>product/' + response.data[a].product_slug + '"><img src=' +
                            response.data[a].product_image + '><h4>' + response.data[a]
                                .product_name + '</h4><strong>$' + response.data[a]
                                .product_price + '</strong> <span>' + response.data[a]
                                .product_weight_gms +
                            'g</span></a><ul><li><div class="quantity"><button type="button" id="sub" class="sub">-</button><input type="text" id="' + response.data[a].product_id + '" value="1" min="1" max="3" disabled /><button type="button" id="add" class="add">+</button></div></li><li><button class="add_cart" data-productname=' + response.data[a].product_name + ' data-price=' + response.data[a].product_price + ' data-productid = ' + response.data[a].product_id + '>Add</button></li></ul></div>'
                        );

                    }
                    product += '</div>';
                    $("#related-products").html(product);
                } else {
                    product = "No Products";
                }
            },
            "error": function (e) {
                console.log(e.responseText);
            }
        });
    }
    function signup() {
        $("#registration").validate({
            rules: {
                user_name: 'required',
                zipcode: 'required',
                user_email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 4
                },
            },
            messages: {
                user_name: 'This field is required',
                password: { required: 'This field is required', minlength: "Your password must be at least 5 characters long" },
                user_email: { required: 'This field is required', email: 'Enter a valid email' },
                zipcode: 'This field is required'
            },
            submitHandler: function (form) {
                form.submit();
                var form = $("#registration");

                $.ajax({
                    "type": "POST",
                    "url": api_url_prefix + 'add-user',
                    "data": form.serialize(),
                    "success": function (response) {
                        $("#message").html("<div class='text-success'>Registration Successfully</div>");
                    },
                    "error": function (e) {
                        $("#message").html("<div class='text-error'>Registration Not  Successfully</div>");
                    }
                });
            },

        });

    }
</script>
<?php require_once('common/footer.php'); ?>