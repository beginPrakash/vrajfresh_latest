<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="">
    <meta name="author" content="">
    <title>vrajfresh</title>
    <link rel="shortcut icon" type="image/png" href=<?php echo ASSET_URL . "images/favicon.png"; ?>>
    <link href='<?php echo ASSET_URL . "css/slick.css"; ?>' rel="stylesheet">
    <link href='<?php echo ASSET_URL . "css/vraj-fresh-custom.css"; ?>' rel="stylesheet">
    <link href='<?php echo ASSET_URL . "css/style.css"; ?>' rel="stylesheet">
    <link href='<?php echo ASSET_URL . "css/vraj-fresh-responsive.css"; ?>' rel="stylesheet">
    <link rel="stylesheet" href=<?php echo ASSET_URL . "css/font-awesome.min.css"; ?>>
    <script src='<?php echo ASSET_URL . "js/jquery.js"; ?>'></script>
</head>
<?php
#get current page link to redirect zipcode in same page
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<body>
    <div id="spinner"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        const api_url_prefix = "<?php echo API_URL ?>";
        const front_url = "<?php echo BASE_URL ?>";

        $(document).ready(function () {
            showProgress('div#spinner');
            get_top_menus(api_url_prefix);
            get_side_menus(api_url_prefix);
            hideProgress('div#spinner');

            $("#zipcode").val(Cookies.get("zipcode"));
        });

        function get_top_menus(api_url_prefix) {
            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "menu_id": "1"
            };
            var menus = "";
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'get-menus',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function (response) {
                    for (let a = 0; a < response.data.length; a++) {

                        if (response.data[a].child_menus != null) {

                            var child_menus = "";
                            for (var b = 0; b < response.data[a].child_menus.length; b++) {

                                var child_menu_link = response.data[a].child_menus[b].child_menu_link;
                                if (child_menu_link.indexOf('http') != -1 || child_menu_link.indexOf('https') != -1) {
                                    child_menus = child_menus.concat("<li><a href='" + child_menu_link + "' target='_blank'>" + response.data[a].child_menus[b].child_menu_title + "</a></li>");
                                } else {
                                    child_menus = child_menus.concat("<li><a href='<?php echo BASE_URL; ?>" + child_menu_link + "'>" + response.data[a].child_menus[b].child_menu_title + "</a></li>");
                                }

                            }
                            menus = menus.concat("<li><a href='<?php echo BASE_URL; ?>" + response.data[a].parent_menu_link + "'>" + response.data[a].parent_menu_title + "</a><button class='but'></button><div class='submenu'><ul>" + child_menus + "</ul></div></li>");
                        } else {
                            var menu_link = response.data[a].menu_link;
                            var menu_sale = '';
                            if (response.data[a].is_sale == 1 || response.data[a].menu_title == 'Sale') {
                                menu_sale = 'menu_sale';
                            }
                            if (menu_link.indexOf('http') != -1 || menu_link.indexOf('https') != -1) {
                                menus = menus.concat("<li class='" + menu_sale + "'><a href='" + response.data[a].menu_link +
                                    "' target='_blank'>" + response.data[a].menu_title + "</a></li>");
                            } else {
                                menus = menus.concat("<li class='" + menu_sale + "'><a href='<?php echo BASE_URL; ?>" + response.data[a].menu_link +
                                    "'>" + response.data[a].menu_title + "</a>");
                            }
                        }
                    }
                    $("#main-menu").html(menus);
                },
                "error": function (response) {
                    $("#main-menu").html(response.errors);
                }
            });
        }

        function get_side_menus(api_url_prefix) {
            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "menu_id": "2"
            };
            var menus = "";
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'get-menus',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function (response) {
                    for (let a = 0; a < response.data.length; a++) {
                        menus = menus.concat("<li><a href='<?php echo BASE_URL; ?>" + response.data[a].menu_link +
                            "'>" + response.data[a].menu_title + "</a></li>");
                    }

                    $("#menu-categories").html(menus);
                },
                "error": function (e) {
                    $("#menu-categories").html(response.errors);
                }
            });
        }

        /*function setCookie() {
            var zipcode = $("#zipcode").val();
            if (zipcode != '') {
                Cookies.set('zipcode', zipcode);
                var json_request = {
                    "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                    "zipcode": zipcode
                };
                $.ajax({
                    "type": "POST",
                    "url": api_url_prefix + 'get-zipcode-detail',
                    "data": JSON.stringify(json_request),
                    "dataType": "JSON",
                    "success": function (response) {
                        //alert('test');
                        if (response.data != null) {
                            Cookies.set('minimum_order_value', '');
                            Cookies.set('is_deliver_perishable_products', '');
                            Cookies.set('delivery_type', '');
                            Cookies.set('delivery_days', '');
                            Cookies.set('shipping_city', '');
                            Cookies.set('shipping_state', '');
                            Cookies.set('zipcode_success_message', '');
                            Cookies.set('zipcode_error_message', '');
                            Cookies.set('valid_zipcode', '');
                            Cookies.set('shipping_zip_code', '');
                            Cookies.set('minimum_order_value', response.data[0].minimum_order_value);
                            Cookies.set('is_deliver_perishable_products', response.data[0].can_deliver_perishable_products);
                            Cookies.set('delivery_type', response.data[0].delivery_types);
                            Cookies.set('delivery_days', response.data[0].delivery_days);
                            Cookies.set('shipping_city', response.data[0].area_name);
                            Cookies.set('shipping_state', response.data[0].state);
                            Cookies.set('shipping_zip_code', response.data[0].zipcode);
                            Cookies.set('zipcode_success_message', 'Yes, We deliver in your area!');
                            Cookies.set('zipcode_error_message', '');
                            Cookies.set('valid_zipcode', 'TRUE');
                            window.location = '<?php echo $actual_link; ?>';
                        } else {
                            Cookies.set('minimum_order_value', '');
                            Cookies.set('is_deliver_perishable_products', '');
                            Cookies.set('delivery_type', '');
                            Cookies.set('delivery_days', '');
                            Cookies.set('shipping_state', '');
                            Cookies.set('shipping_city', '');
                            Cookies.set('zipcode_success_message', '');
                            Cookies.set('zipcode_error_message', '');
                            Cookies.set('valid_zipcode', '');
                            Cookies.set('shipping_zip_code', '');

                            $("#zipcode_message").html('Sorry We do not deliver in your area.');
                            Cookies.set('zipcode_error_message', 'Sorry We do not deliver in your area.');
                            Cookies.set('valid_zipcode', 'FALSE');
                            window.location = '<?php echo $actual_link; ?>';
                        }

                    },
                    "error": function (response) {
                        $("#zipcode_message").html('Sorry We do not deliver in your area.');
                        Cookies.set('valid_zipcode', 'FALSE');
                        window.location = '<?php echo $actual_link; ?>';
                    }
                });
            } else {
                $("#zipcode_message1").html('Please enter zipcode');
                $("#success_center").hide();
            }
        }*/
        $('.numberonly').keypress(function (e) {
            var charCode = (e.which) ? e.which : event.keyCode;
            if (String.fromCharCode(charCode).match(/[^0-9]/g)) return false;
        }); 
    </script>