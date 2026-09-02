<!DOCTYPE html>
<html lang="en">
<?php
// echo '<pre>';
// print_r($_SESSION['cart_contents']);
// echo current_url(true);
?>

<head>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WHKSZHHL');</script>
    <!-- End Google Tag Manager -->

     <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-1PKZMFV141"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-1PKZMFV141');

        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", "vtnve5a0i9");
        
    </script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google-site-verification" content="FNeLESBhXnmv7O-GJWgi96TbBopIy-wCVCGEWJgPkno" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?= (isset($meta_title) && !empty($meta_title)) ? $meta_title : 'VrajFresh'; ?></title>
    <meta name="description" content="<?= (isset($meta_description) && !empty($meta_description)) ? $meta_description : 'VrajFresh'; ?>">
    <?php if (!empty($og_title)) { ?>

    <meta property="og:title"
          content="<?php echo htmlspecialchars(trim(preg_replace('/\s+/', ' ', strip_tags($og_title))), ENT_QUOTES, 'UTF-8'); ?>">

    <meta property="og:description"
          content="<?php echo htmlspecialchars(trim(preg_replace('/\s+/', ' ', strip_tags($og_description))), ENT_QUOTES, 'UTF-8'); ?>">

    <meta property="og:image"
          content="<?php echo htmlspecialchars($og_image, ENT_QUOTES, 'UTF-8'); ?>">

    <meta property="og:url"
          content="<?php echo htmlspecialchars($og_url, ENT_QUOTES, 'UTF-8'); ?>">

    <meta property="og:type" content="website">

    <meta property="og:site_name" content="VrajFresh">

<?php } ?>
    <meta name="author" content="">
    <title>VrajFresh</title>
    <link rel="shortcut icon" type="image/png" href=<?php echo ASSET_URL . "images/favicon.png"; ?>>
    <link href='<?php echo ASSET_URL . "css/slick.css?v=1.1"; ?>' rel="stylesheet">
    <link href='<?php echo ASSET_URL . "css/vraj-fresh-custom.css?v=" . date("d.H.m.s"); ?>' rel="stylesheet">
    <link href='<?php echo ASSET_URL . "css/style.css?v=1.1"; ?>' rel="stylesheet">
    <link href='<?php echo ASSET_URL . "css/vraj-fresh-responsive.css?v=" . date("d.H.m.s"); ?>' rel="stylesheet">
    <link href='<?php echo ASSET_URL . "css/vrjcst.css?v=" . date("d.H.m.s"); ?>' rel="stylesheet">
    <link rel="stylesheet" href=<?php echo ASSET_URL . "css/font-awesome.min.css?v=1.1"; ?>>
    <?php
        $canonical = base_url(uri_string());
    ?>
    <link rel="canonical" href="<?= $canonical; ?>" />
    <script src='<?php echo ASSET_URL . "js/jquery.js"; ?>'>
    </script>

    <script>
        function getParameterByName(name) {
            var url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
    </script>
    <style>
        .menu_sale {
            background: red;
        }
    </style>
    
    <meta name="google-site-verification" content="xaJvsYfgjva1zg6alJmoQ0Er7XPWoDt0zunyKPNcHiE" />

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '996549885017760');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
            src="https://www.facebook.com/tr?id=996549885017760&ev=PageView
&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->

</head>
<?php
#get current page link to redirect zipcode in same page
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<body class="banner-empty">

 <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WHKSZHHL"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <div class="top-banner top-banner-text" style="display: none;"></div>
    <div class="zipcode-bar">
        <input name="zipcode" type="hidden" id="zipcode" class="zipcode_only myinput">
        <div class="zipcode-container">
            
            <div class="zipcode-item">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>My Location - <strong><strong id="zipcode_display"><?php echo (isset($_COOKIE['zipcode'])) ? $_COOKIE['zipcode'] : 'Enter Zipcode'; ?></strong></strong></span>
                <a href="javascript:void(0)" class="zipcode-edit-link" onclick="showZipCodepopup();" title="Edit Zipcode">
                    <i class="fa fa-pencil ms-1" style="font-size: 0.75rem;" aria-hidden="true"></i>
                </a>
            </div>
            <?php 
                         
                function get_single_delivery_date(){
                        $time_del_arr = getHolidayDatearr($_COOKIE['zipcode']);

                    $cutofftime    = trim($time_del_arr['cutoff_time']);   // Example: 03:00
                    $holidaye_date = $time_del_arr['holiday_arr'];  
                    // CURRENT DATE & TIME
                    // -----------------------------------

                    $todayDate = date('Y-m-d');

                    $currentHour   = (int) date('H');
                    $currentMinute = (int) date('i');

                    // -----------------------------------
                    // CUTOFF TIME
                    // -----------------------------------

                    // Example: 14:00
                    $cutoffParts = explode(':', $cutofftime);

                    $cutoffHour   = (int) $cutoffParts[0];
                    $cutoffMinute = (int) $cutoffParts[1];

                    // -----------------------------------
                    // COMPARE CURRENT TIME WITH CUTOFF
                    // -----------------------------------

                    if (
                        $currentHour > $cutoffHour ||
                        (
                            $currentHour == $cutoffHour &&
                            $currentMinute >= $cutoffMinute
                        )
                    ) {

                        // After cutoff → next day
                        $todayDate = date(
                            'Y-m-d',
                            strtotime($todayDate . ' +1 day')
                        );
                    }

                    // -----------------------------------
                    // CHECK HOLIDAYS
                    // -----------------------------------

                    while (in_array($todayDate, $holidaye_date)) {

                        // Holiday → next day
                        $todayDate = date(
                            'Y-m-d',
                            strtotime($todayDate . ' +1 day')
                        );
                    }

                    // -----------------------------------
                    // FINAL DELIVERY DATE
                    // -----------------------------------

                    $deliveryDate = date(
                        'l, d F Y',
                        strtotime($todayDate)
                    );
                    return $deliveryDate;
                }
                function get_twise_delivery_date(){
                    $time_del_arr = getHolidayDatearr($_COOKIE['zipcode']);

                    $cutofftime    = trim($time_del_arr['cutoff_time']);   // Example: 03:00
                    $holidaye_date = $time_del_arr['holiday_arr'];  
                    $delivery_days = $_COOKIE['delivery_days'];
                    $dayArray = array_map('trim', explode(',', $delivery_days));
                    // Make sure holiday array is valid
                    if (!is_array($holidaye_date)) {
                        $holidaye_date = array();
                    }


                    // ----------------------------------------
                    // CURRENT TIME
                    // ----------------------------------------
                    $todayDate = date('Y-m-d');

                    $currentHour   = (int)date('H');
                    $currentMinute = (int)date('i');


                    // ----------------------------------------
                    // CUTOFF TIME
                    // ----------------------------------------
                    $cutoffParts = explode(':', $cutofftime);

                    $cutoffHour   = (int)$cutoffParts[0];
                    $cutoffMinute = (int)$cutoffParts[1];


                    // ----------------------------------------
                    // AFTER CUTOFF?
                    // ----------------------------------------
                    $isAfterCutoff = false;

                    if (
                        $currentHour > $cutoffHour ||
                        (
                            $currentHour == $cutoffHour &&
                            $currentMinute >= $cutoffMinute
                        )
                    ) {
                        $isAfterCutoff = true;
                    }


                    // ----------------------------------------
                    // START DATE
                    // After cutoff → tomorrow
                    // Before cutoff → today
                    // ----------------------------------------
                    $checkDate = $todayDate;

                    if ($isAfterCutoff) {
                        $checkDate = date(
                            'Y-m-d',
                            strtotime($todayDate . ' +1 day')
                        );
                    }


                    // ----------------------------------------
                    // FIND NEXT AVAILABLE DELIVERY DATE
                    // ----------------------------------------
                    $deliveryDate = '';

                    for ($i = 0; $i < 60; $i++) {

                        $checkDay = date('l', strtotime($checkDate));

                        // 1. Must be a delivery day
                        $isDeliveryDay = in_array($checkDay, $dayArray, true);

                        // 2. Must NOT be a holiday date
                        $isHoliday = in_array($checkDate, $holidaye_date, true);

                        if ($isDeliveryDay && !$isHoliday) {

                            $deliveryDate = date(
                                'l, d F Y',
                                strtotime($checkDate)
                            );

                            break;
                        }

                        // Move to next date
                        $checkDate = date(
                            'Y-m-d',
                            strtotime($checkDate . ' +1 day')
                        );
                    }
                    return $deliveryDate;
                }
            ?>
            <?php if (isset($_COOKIE['zipcode_success_message']) && !empty($_COOKIE['zipcode_success_message'])) { ?>
            <span class="zipcode-divider">|</span>

            <div class="zipcode-item">
                <i class="fa fa-truck" aria-hidden="true"></i>
                <?php
                $deliveryDate = '';
                
                //print_r($holidaye_date);
                if ($_COOKIE['delivery_type'] == 'Express Delivery' || $_COOKIE['delivery_type'] == 'Same Day Delivery') {
                   $deliveryDate = get_single_delivery_date();
                }
                if ($_COOKIE['delivery_type'] == 'Twise in a week') {
                    // $delivery_days = $_COOKIE['delivery_days'];
                     $deliveryDate = get_twise_delivery_date();
                    // $dayArray = explode(',', $delivery_days);
                    // $deliveryDate = date('m-d-Y');
                    
                    // $hours = date('H');
                    // $today = date('l');
                    
                    // if(in_array($today, $dayArray) && $hours < 15){
                    //     $deliveryDate = date('l, d F Y');
                    // } else {
                    //     for ($i = 1; $i <= 7; $i++) {
                    //         // Get the date for the next iteration
                    //         $nextDate = new DateTime();
                    //         $nextDate->modify("+$i day");
                        
                    //         // Check if the day of the next date is in the dayArray
                    //         if (in_array($nextDate->format('l'), $dayArray)) {
                    //             $deliveryDate = $nextDate->format('l, d F Y)');
                    //             break; // Exit the loop once a suitable delivery date is found
                    //         }
                    //     }
                    // }
                   



                }
                ?>
                <span><?php echo $deliveryDate; ?></span>
            </div>
            <?php } ?>
            <?php if (isset($_COOKIE['zipcode_error_message']) && !empty($_COOKIE['zipcode_error_message'])) {?>
            <span class="zipcode-divider">|</span>

            <div class="zipcode-item">
                <i class="fa fa-truck" aria-hidden="true"></i>
                <span><?php echo $_COOKIE['zipcode_error_message']; ?></span>
            </div>
            <?php } ?>

        </div>
    </div>
    <header>
        <div class="container container-flex header">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>">
                    <img src=<?php echo ASSET_URL . "images/logo.png"; ?> alt="Vraj Fresh online grocery store logo">
                </a>
            </div>
            <?php /*
            <div class="zip-code mobile-hide">
                <div></div>
                <img src=<?php echo ASSET_URL . "images/map-pin.png"; ?> class="left-arrow">
                <input name="zipcode" placeholder="Enter your zip code" type="text" id="zipcode" required maxlength="6" class="zipcode_only myinput">
                <button onclick="setZipCodeCookie('header');" class="header_button">
                    <img src=<?php echo ASSET_URL . "images/go.png"; ?> alt="Submit">
                </button>
                <div id="zipcode_message1" class="error">
                    <?php if (isset($_COOKIE['zipcode_error_message'])) {
                        echo $_COOKIE['zipcode_error_message'];
                    } ?>
                </div>
                <div class="success" id="success_center">
                    <?php if (isset($_COOKIE['zipcode_success_message'])) {
                        echo $_COOKIE['zipcode_success_message'];
                    } ?>
                </div>
            </div>
            <?php */ ?>
            <?php
            $ch_ur = (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : '';
            if ($ch_ur == '/checkout') { ?>
                <div class="home-search" id="search-autocomplete">
                </div>
            <?php } else { ?>
                <div class="home-search" id="search-autocomplete">
                    <form action="<?php echo BASE_URL . 'products/search'; ?>" method="get">
                        <button type="submit" class="header_button">
                            <img src=<?php echo ASSET_URL . "images/search-icon.png"; ?> class="searchimg" alt="Search products">
                        </button>
                        <input name="search" class="myinput" placeholder="Search for items..." type="text" id="search" required maxlength="4000">
                        <div class="search-autocomplete-result">
                            <!-- load product -->
                            <div class="myspinner"></div>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <input type="hidden" value="<?Php echo $ch_ur; ?>" class="curs_name">
            <div class="my-account">
                <ul>
                    <li class="mobile-show">
                        <a href="javascript:void(0)" class="toggel-zipcode"><img src=<?php echo ASSET_URL . "images/mappin.svg"; ?> class="left-arrow" alt="Store map location"></a>
                    </li>
                    <?php if (!IsUserLogin()) { ?>
                        <?php if (isset($_COOKIE['user_id'])) { ?>
                            <li class="vraj-login1">
                                <a href="<?php echo BASE_URL . 'my-orders'; ?>"><img src=<?php echo ASSET_URL . "images/user-icon.png"; ?> alt="User account login"></a>
                            </li>
                        <?php } else { ?>

                            <li class="vraj-login">
                                <!-- <a href="<?php echo current_url() . '?login=true'; ?>"> <img src=<?php echo ASSET_URL . "images/user-icon.png"; ?> alt="User account login"></a> -->
                                <a href="<?php echo BASE_URL . 'login'; ?>"> <img src=<?php echo ASSET_URL . "images/user-icon.png"; ?> alt="User account login"></a>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <li class="vraj-login1">
                            <a href="<?php echo BASE_URL . 'my-orders'; ?>"><img src=<?php echo ASSET_URL . "images/user-icon.png"; ?> alt="User account login"></a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo BASE_URL . 'cart-detail'; ?>">
                            <img src=<?php echo ASSET_URL . "images/cart-icon.png"; ?> alt="Shopping cart">
                            <span id="cartCount">
                                <?php echo $this->cart->total_items(); ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mobile-menu">
            <img src=<?php echo ASSET_URL . "images/hamburger-menu.svg"; ?> alt="Open navigation menu">
        </div>
        <script>
            var cart_arr = {};
            document.addEventListener('DOMContentLoaded', function() {
                const leftArrow = document.querySelector('.menu-left-arrow');
                const rightArrow = document.querySelector('.menu-right-arrow');
                const menu = document.querySelector('.main-menu');

                function InitialupdateArrowState() {
                    if (menu.scrollLeft === 0) {
                        leftArrow.classList.add('disabled');
                    } else {
                        leftArrow.classList.remove('disabled');
                    }

                }

                function updateArrowState() {
                    if (menu.scrollLeft === 0) {
                        leftArrow.classList.add('disabled');
                    } else {
                        leftArrow.classList.remove('disabled');
                    }

                    if (menu.scrollLeft + menu.clientWidth >= menu.scrollWidth) {
                        rightArrow.classList.add('disabled');
                    } else {
                        rightArrow.classList.remove('disabled');
                    }
                }

                leftArrow.addEventListener('click', function() {
                    if (menu.scrollLeft > 0) {
                        menu.scrollBy({
                            left: -200, // Adjust the value as needed
                            behavior: 'smooth'
                        });
                    }
                });

                rightArrow.addEventListener('click', function() {
                    if (menu.scrollLeft + menu.clientWidth < menu.scrollWidth) {
                        menu.scrollBy({
                            left: 200, // Adjust the value as needed
                            behavior: 'smooth'
                        });
                    }
                });

                menu.addEventListener('scroll', updateArrowState);

                // Initial check
                InitialupdateArrowState();
            });
        </script>
        <nav class="menu">
            <div class="container container-flex">
                <div class="all-categories">
                    <span class="hamburg-categories">
                        <img src=<?php echo ASSET_URL . "images/hamburger-menu.svg"; ?> alt=""> All Categories </span>
                    <div class="menu-categories">
                        <ul id="menu-categories"></ul>
                        <div class="close-categories">
                            <img src=<?php echo ASSET_URL . "images/close.png"; ?> alt="Close popup">
                        </div>
                    </div>
                </div>
                <div class="menu-container">
                    <button class="scroll-arrow menu-left-arrow">&#171;</button>
                    <ul class="main-menu" id="main-menu"></ul>
                    <button class="scroll-arrow menu-right-arrow">&#187;</button>
                </div>
                <div class="close-menu">
                    <img src=<?php echo ASSET_URL . "images/close.png"; ?> alt="Close popup">
                </div>
            </div>
        </nav>
        <div class="wrapper begin-wrapper">
            <nav class="navigation">
                <div class="container">
                    <ul class="nav__list" id="nav__list"></ul>
                </div>
            </nav>
        </div>
    </header>
    <div id="spinner"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css?v=1.1">
    <script>
        const api_url_prefix = "<?php echo API_URL ?>";
        const front_url = "<?php echo BASE_URL ?>";

        $(document).on('mouseenter', '.nav__item', function(event) {
            $(this).children('.mainsubmenu').slideDown('fast');
        }).on('mouseleave', '.nav__item', function() {
            $(this).children('.mainsubmenu').stop(true, true).hide(); // instant close
        });


        var login_user_id = 0;
        <?php if (IsUserLogin()) { ?>
            var login_user_id = '<?php echo $this->session->userdata['logged_in']['user_id']; ?>';
        <?php } ?>

        $(document).ready(function() {

            if (login_user_id > 0) {
                var currentUrl = window.location.href;
                // Create a URL object
                var urlObj = new URL(currentUrl);
                // Extract the pathname
                var webpathname = urlObj.pathname;
                login_cart_data(webpathname);
            }else{
                var cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
                cart_arr = JSON.parse(cart_arr);
            }


            showProgress('div#spinner');
            get_top_menus(api_url_prefix);
            get_top_mainmenus(api_url_prefix);
            get_side_menus(api_url_prefix);
            get_top_banner(api_url_prefix);
            hideProgress('div#spinner');
            $("#zipcode").val(Cookies.get("zipcode"));
            $("#zipcode_popup").val(Cookies.get("zipcode"));
            jQuery('.toggel-zipcode').on('click', function() {
                $('.zip-code.mobile-hide').toggle();
            });

            // header search 

            jQuery(document).click(function(event) {
                var container = jQuery(".search-autocomplete-result");
                if (!container.is(event.target) && !container.has(event.target).length) {
                    container.removeClass("open");
                }
            });

            $("#search-autocomplete #search").on("keyup", function() {
                if (this.value.length > 2) {
                    $(".search-autocomplete-result").addClass('open');

                    var json_request = {
                        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                        "search_keyword": $(this).val(),
                        "zipcode": Cookies.get("zipcode")
                    };
                    $.ajax({
                        "type": "POST",
                        "url": api_url_prefix + 'get-header-search-product',
                        "data": JSON.stringify(json_request),
                        "dataType": "JSON",
                        "success": function(response) {
                            var product = '';
                            if (response.data != null) {
                                if (response.data.product_id != null) {
                                    //product_title = '<h2>' + response.data.category.category_name + '</h2>';
                                    for (let a = 0; a < response.data.products.length; a++) {
                                        var out_of_stock = "<div class='product-stock-message'></div>";
                                        var out_of_stock_class = "";
                                        var variant_in_stock_count = 0;
                                        var variant_size = response.data.products[a].product_variant_size;

                                        /*if (response.data.products[a].is_out_of_stock == 0) {
                                            out_of_stock = "<div class='product-stock-message'>Sold out</div>";
                                            out_of_stock_class = "out_of_stock";
                                        }*/
                                        if (response.data.products[a].product_size.length > 1 && response.data.products[a].product_size[0] != "") {
                                            var price_weight = '<form name="form" id="form"><select name="jumpMenu" id="jumpMenu" class="variants ' + out_of_stock_class + '">';
                                            for (let j = 0; j < response.data.products[a].product_size.length; j++) {
                                                var first_variant_out_of_stock = false;
                                                var out_of_stock_variant = '';
                                                if (response.data.products[a].product_size[j].is_out_of_stock == 0) {
                                                    out_of_stock_variant = ' out_of_stock_variant';
                                                    if (response.data.products[a].product_size[j].is_out_of_stock == 0) {
                                                        first_variant_out_of_stock = true;
                                                    }
                                                }
                                                if (!first_variant_out_of_stock) {
                                                    price_weight = price_weight.concat('<option class="' + out_of_stock_variant + '" value="' + response.data.products[a].product_size[j].size + '-' + response.data.products[a].product_size[j].price + '-' + response.data.products[a].product_size[j].variant_id + '">' + response.data.products[a].product_size[j].size + 'lb - $' + response.data.products[a].product_size[j].price + '</option>');
                                                    variant_in_stock_count = variant_in_stock_count + 1;
                                                }
                                            }
                                            price_weight += '</select></form>';
                                        } else {
                                            var price_weight = '<span>' + response.data.products[a]
                                                .product_weight_gms + 'lb</span> - <strong>$' + response.data.products[a]
                                                .product_price + '</strong>';
                                        }

                                        var out_of_stock = "<div class='product-stock-message'></div>";
                                        var out_of_stock_class = "";

                                        // console.log("product sold out:" + response.data.products[a].is_out_of_stock + " and variant sold out:" + variant_in_stock_count);
                                        if (response.data.products[a].is_out_of_stock == 0) {
                                            out_of_stock = "<div class='product-stock-message'>Product Sold out</div>";
                                            out_of_stock_class = "out_of_stock";
                                            var price_weight = "";
                                        }

                                        let product_price=response.data.products[a].product_price;
									    let sale_price=response.data.products[a].sale_price;
                                        let final_p_price = product_price;
                                        if(product_price != sale_price){
											final_p_price=sale_price;
									    }

                                        // product = product.concat('<li class="product-box"><a href="<?php echo BASE_URL; ?>product/' + response.data.products[a].product_slug + '"><img src=' + response.data.products[a].product_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" ><div><h4>' + response.data.products[a].product_name + '</h4></a>' + price_weight + out_of_stock + '<ul><li><div class="quantity ' + out_of_stock_class + '"><button type="button" id="sub" class="sub">-</button><input type="text" class = "prod_add1" id="' + response.data.products[a].product_id + '" value="1" min="1" max="3" disabled /><button type="button" id="add" class="add">+</button></div></li><li><button id= "btn_' + response.data.products[a].product_id + '" class="add_cart ' + out_of_stock_class + '" data-isperisible="' + response.data.products[a].is_perisible_products + '" data-productslug="' + response.data.products[a].product_slug + '" data-productimage= "' + response.data.products[a].product_image + '" data-productname="' + response.data.products[a].product_name + '" data-price=' + response.data.products[a].product_price + ' data-productid = ' + response.data.products[a].product_id + ' data-productweight =' + response.data.products[a].product_weight_gms + ' data-producttax="' + response.data.products[a].product_tax + '" >Add</button></li></ul></div></li>');
                                        product += `
                                        <div class="product-box-dropdown">
                                            <div class="image-box">
                                            <a href="<?php echo BASE_URL; ?>product/${response.data.products[a].product_slug}">
                                                <img src=${response.data.products[a].product_image} onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" height="50" >
                                            </a>
                                            </div>
                                            <div class="title-box">
                                                <div><a href="<?php echo BASE_URL; ?>product/${response.data.products[a].product_slug}">${response.data.products[a].product_name}</a></div>
                                                <div>${final_p_price}</div>
                                            </div>`;
                                        product += `<div class="cart-box">
                                            ${out_of_stock}
                                            <ul>`;
                                        if (variant_size == null || out_of_stock_class != '') {

                                            product += `<li>
                                                <div class="quantity ${out_of_stock_class}">
                                                <button type="button" id="sub" class="sub">-</button>
                                                <input type="text" class = "prod_add1 search_${response.data.products[a].product_id}" id="search_${response.data.products[a].product_id} " value="1" min="1" max="3" disabled />
                                                <button type="button" id="add" class="add">+</button>
                                                </div>
                                                </li>
                                                <li>
                                                <button type="button" id= "btn_${response.data.products[a].product_id}" class="add_cart_search ${out_of_stock_class}" data-isperisible="${response.data.products[a].is_perisible_products}" data-productslug="${response.data.products[a].product_slug}" data-productimage= "${response.data.products[a].product_image}" data-productname="${response.data.products[a].product_name}" data-price="${final_p_price}" data-productid = "${response.data.products[a].product_id}" data-productweight ="${response.data.products[a].product_weight_gms}" data-producttax="${response.data.products[a].product_tax}" >Add</button>
                                                </li>`;

                                        } else {
                                            product += `<li>
                                                <a href="<?php echo BASE_URL; ?>product/${response.data.products[a].product_slug}" class="search-view ${out_of_stock_class}" >View</a>
                                                </li>`;
                                        }
                                        product += `</ul>
                                            </div>`;

                                        product += `</div>`;

                                        // <div class="col-md-2">
                                        //     <ul>
                                        //         <li>
                                        //             <div class="quantity ${out_of_stock_class}">
                                        //                 <button type="button" id="sub" class="sub">-</button><input type="text" class = "prod_add1" id="${response.data.products[a].product_id} " value="1" min="1" max="3" disabled />
                                        //                 <button type="button" id="add" class="add">+</button>
                                        //             </div>
                                        //         </li>
                                        //         <li>
                                        //             <button id= "btn_${response.data.products[a].product_id}" class="add_cart ${out_of_stock_class}" data-isperisible="${response.data.products[a].is_perisible_products}" data-productslug="${response.data.products[a].product_slug}" data-productimage= "'${response.data.products[a].product_image}'" data-productname="${response.data.products[a].product_name}" data-price='${response.data.products[a].product_price}' data-productid = '${response.data.products[a].product_id} data-productweight =${response.data.products[a].product_weight_gms} data-producttax="${response.data.products[a].product_tax}" >Add</button>
                                        //         </li>
                                        //     </ul>
                                        //     </div>

                                    }
                                    product += `<button type="submit" class="vraj-btn" style="width: 100%;margin-top: 20px;padding: 10px 30px;">View All</button>`
                                    $(".search-autocomplete-result").html(product);
                                    var first_variant_out_of_stock = false;
                                    // console.log(product);

                                } else {
                                    $(".search-autocomplete-result").html('<li>No Products Found</li>');
                                }
                            } else {
                                $(".search-autocomplete-result").html('<li>No Products Found</li>');
                            }
                        },
                        "error": function(response) {
                            $(".search-autocomplete-result").html('<li>Someting went worng!</li>');
                        }
                    });

                } else {
                    $(".search-autocomplete-result").removeClass('open');
                }
            });

            $(document).on('click', '.add_cart_search', function() {
                var product_id = $(this).data('productid');
                // console.log(product_id);
                var product_name = $(this).data('productname');
                var price = $(this).data('price');
                var quantity = $('.search_' + product_id).val();
                // console.log(quantity);
                var image = $(this).data('productimage');
                var product_slug = $(this).data('productslug');
                var is_perisible = $(this).data('isperisible');
                var product_tax = $(this).data('producttax');

                var parent = $(this).parent().parent().parent().find("select.variants");
                if (parent.length > 0) {
                    var selected = parent.find("option:selected").val();
                    var variant_detail = selected.split('-');
                    var weight = variant_detail[0];
                    var price = variant_detail[1];
                    var variant_id = variant_detail[2];

                } else {
                    var price = $(this).data('price');
                    var weight = $(this).data('productweight');
                }

                var json_request = {
                    "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                    "product_id": product_id,
                    "product_name": product_name,
                    "price": price,
                    "quantity": quantity,
                    "product_image": image,
                    "weight": weight,
                    "variant_id": variant_id,
                    "product_slug": product_slug,
                    "is_perisible": is_perisible,
                    "product_tax": product_tax,

                };
                if (quantity != "" || quantity > 0) {
                    var total_qty = parseInt($("#cartCount").html()) + parseInt(quantity);
                    $("#cartCount").html(total_qty);
                    var curs_name = $('.curs_name').val();
                    $.ajax({
                        "type": "POST",
                        "url": front_url + 'cart/add',
                        "data": JSON.stringify(json_request),
                        "dataType": "JSON",
                        "success": function(response) {

                            $("#btn_" + product_id).text('Added');
                            if (curs_name == '/cart-detail') {
                                location.reload();
                            }
                            setTimeout(() => {
                                $(".add").prev().val(1);
                                $("#btn_" + product_id).text('Add');
                            }, 3000);


                            //	alert('Product Added Into Cart');
                            //  $("#cart-details").html(data);
                            // setTimeout(function() {
                            // 	location.reload();
                            // }, 1000);
                        },
                        "error": function(response) {
                            console.log(response.errors);
                        }
                    });

                } else {
                    alert("Please Enter quantity");
                }

            });

            <?php
            $cartContents = isset($_SESSION["cart_contents"]) ? json_encode($_SESSION["cart_contents"]) : '';
            $escapedCartContents = addslashes($cartContents);

            ?>

            $("#Frmlogin").validate({
                rules: {
                    email: "required",
                    password: "required",
                },
                messages: {
                    email: "This field is required",
                    password: "This field is required",
                },
                submitHandler: function(form) {
                    var form = $("#Frmlogin");

                    // form.submit();

                    //  e.preventDefault();
                    const loginrForm = document.getElementById('Frmlogin');
                    const formData = new FormData(loginrForm)
                    var cartData = '<?php echo $escapedCartContents; ?>';
                    formData.append('cart_data', cartData);;
                    // console.log(formData);
                    $.ajax({
                        type: "POST",
                        url: api_url_prefix + "login",
                        // url: front_url + "validate-login",
                        // data: form.serialize(),
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        success: function(response) {
                            if (response.data != null) {
                                $("#message").html('<div class="alert-success">' + response.success_message + "</div>");
                                Cookies.set("user_id", response.data[0].user_id);
                                Cookies.set("email", response.data[0].email);
                                Cookies.set("user_role_id", response.data[0].user_role_id);
                                Cookies.set("Is_login", "true");

                                if (response.data[0].user_name == null) {
                                    Cookies.set("user_name", "");
                                } else {
                                    Cookies.set("user_name", response.data[0].user_name);
                                }
                                if (response.data[0].first_name == null) {
                                    Cookies.set("first_name", "");
                                } else {
                                    Cookies.set("first_name", response.data[0].first_name);
                                }
                                if (response.data[0].last_name == null) {
                                    Cookies.set("last_name", "");
                                } else {
                                    Cookies.set("last_name", response.data[0].last_name);
                                }
                                if (response.data[0].display_name == null) {
                                    Cookies.set("display_name", "");
                                } else {
                                    Cookies.set("display_name", response.data[0].display_name);
                                }

                                if (response.data[0].address == null) {
                                    Cookies.set("address", "");
                                } else {
                                    Cookies.set("address", response.data[0].address);
                                }

                                if (response.data[0].address2 == null) {
                                    Cookies.set("address2", "");
                                } else {
                                    Cookies.set("address2", response.data[0].address2);
                                }
                                if (response.data[0].city == null) {
                                    Cookies.set("city", "");
                                } else {
                                    Cookies.set("city", response.data[0].city);
                                }

                                $.ajax({
                                    type: "POST",
                                    url: front_url + "cart/save_user_cart_data",
                                    // data: form.serialize(),
                                    data: {
                                        'oauth_key': 'F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT',
                                        'user_id': response.data[0].user_id
                                    },
                                    // contentType: false,
                                    // processData: false,
                                    dataType: "JSON",
                                    success: function(response_cart) {
                                        console.log('save_user_cart_data');
                                        console.log(response_cart);
                                    }
                                });

                                $.ajax({
                                    type: "POST",
                                    url: front_url + "cart/logincartdata",
                                    // data: form.serialize(),
                                    data: {
                                        'oauth_key': 'F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT',
                                        'user_id': response.data[0].user_id
                                    },
                                    // contentType: false,
                                    // processData: false,
                                    dataType: "JSON",
                                    success: function(response_cart) {
                                        console.log('logincard');
                                        console.log(response_cart);
                                    }
                                });
                                var get_forgot_cookie = Cookies.get("forget_password");
                                if (get_forgot_cookie == "yes") {
                                    window.location = front_url + "change-password";
                                } else {
                                    //window.location = front_url + "my-orders";
                                    if (response.data['prev_url'] != '') {
                                        window.location = front_url + response.data['prev_url'];
                                    } else {
                                        window.location = front_url + "my-orders";
                                    }
                                }
                            } else {
                                $("#message").html(
                                    '<div class="alert-danger">' + response.errors + "</div>"
                                );
                                if (
                                    response.errors ==
                                    "Your account is not registered with Vraj Fresh. Please create an account."
                                ) {
                                    alert(
                                        "Your account is not registered with Vraj Fresh. Please create an account."
                                    );
                                    jQuery("div#forgot-popup").hide();
                                    jQuery("div#login-popup").hide();
                                    jQuery("div#signup-popup").show();
                                }
                            }

                            // setTimeout(() => {
                            //     window.location.replace(front_url);
                            // }, 2000);
                        },
                        error: function(response) {
                            $("#message").html(
                                '<div class="alert-danger">' + response.errors + "</div>"
                            );
                        },
                    });
                    return false;
                },
            });

            $("#Frmregistration").validate({
                rules: {
                    first_name: 'required',
                    last_name: 'required',
                    phone_number: 'required',
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 4
                    },
                    phone_number: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    first_name: 'First name is required',
                    last_name: 'Last name is required',
                    password: {
                        required: 'Password is required',
                        minlength: "Your password must be at least 5 characters long"
                    },
                    email: {
                        required: 'Email is required',
                        email: 'Enter a valid email'
                    },
                    phone_number: {
                        required: 'Phone number is required',
                        email: 'Please enter only digits'
                    }
                    //zipcode: 'This field is required'
                },
                submitHandler: function(form) {
                    // form.submit();
                    if (grecaptcha.getResponse().length === 0) {
                        $('#recaptcha-error').show();
                        $('#recaptcha-error').html('Please complete the CAPTCHA');
                        return false;
                    }else{  
                        $('#recaptcha-error').html('');

                    }
                    var form = $("#Frmregistration");
                    $.ajax({
                        "type": "POST",
                        "url": api_url_prefix + 'add-user',
                        "data": form.serialize(),
                        "success": function(response) {
                            if (response.data != null) {
                                $("#signup_message").html('<div class="alert-success">' + response.success_message + '</div>');
                                console.log(response, 'reg data');
                                Cookies.set("user_id", response.data[0].user_id);
                                Cookies.set("email", response.data[0].email);
                                Cookies.set("user_role_id", response.data[0].user_role_id);
                                Cookies.set("Is_login", "true");

                                if (response.data[0].user_name == null) {
                                    Cookies.set("user_name", "");
                                } else {
                                    Cookies.set("user_name", response.data[0].user_name);
                                }
                                if (response.data[0].first_name == null) {
                                    Cookies.set("first_name", "");
                                } else {
                                    Cookies.set("first_name", response.data[0].first_name);
                                }
                                if (response.data[0].last_name == null) {
                                    Cookies.set("last_name", "");
                                } else {
                                    Cookies.set("last_name", response.data[0].last_name);
                                }
                                if (response.data[0].display_name == null) {
                                    Cookies.set("display_name", "");
                                } else {
                                    Cookies.set("display_name", response.data[0].display_name);
                                }

                                if (response.data[0].address == null) {
                                    Cookies.set("address", "");
                                } else {
                                    Cookies.set("address", response.data[0].address);
                                }

                                if (response.data[0].address2 == null) {
                                    Cookies.set("address2", "");
                                } else {
                                    Cookies.set("address2", response.data[0].address2);
                                }
                                if (response.data[0].city == null) {
                                    Cookies.set("city", "");
                                } else {
                                    Cookies.set("city", response.data[0].city);
                                }
                                window.location = front_url + "my-orders";
                            } else {
                                $("#signup_message").html('<div class="alert-danger">' + response.errors + '</div>');
                            }
                        },
                        "error": function(response) {
                            $("#signup_message").html('<div class="alert-danger">' + response.errors + '</div>');
                        }
                    });
                    return false;
                },
            });

        });

        function get_top_menus(api_url_prefix) {
            var zipcode = '';
            if (typeof Cookies.get("zipcode") !== "undefined") {
                zipcode = Cookies.get("zipcode");
            }

            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "menu_id": "1",
                "zipcode": zipcode
            };
            var menus = "";
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'get-menus',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function(response) {
                    console.log(response);
                    for (let a = 0; a < response.data.length; a++) {

                        if (response.data[a].child_menus != null) {

                            var child_menus = "";
                            for (var b = 0; b < response.data[a].child_menus.length; b++) {

                                var child_menu_link = response.data[a].child_menus[b].child_menu_link;
                                var child_category_slug = response.data[a].child_menus[b].child_category_slug;
                                if (child_menu_link.indexOf('http') != -1 || child_menu_link.indexOf('https') != -1) {
                                    child_menus = child_menus.concat("<li><a href='" + child_menu_link + "' target='_blank'>" + response.data[a].child_menus[b].child_category_name + "</a></li>");
                                } else {
                                    child_menus = child_menus.concat("<li><a href='<?php echo BASE_URL; ?>category/" + child_category_slug + "'>" + response.data[a].child_menus[b].child_category_name + "</a></li>");
                                }

                            }
                            menus = menus.concat("<li><a href='javascript:void();'>" + response.data[a].category_name + "</a><button class='but'></button><div class='submenu'><ul>" + child_menus + "</ul></div></li>");
                        } else {
                            var menu_link = response.data[a].menu_link;
                            var category_slug = response.data[a].category_slug;
                            var menu_sale = '';
                            if (response.data[a].category_name == 'Sale') {
                                menu_sale = 'menu_sale';
                            }
                            if (menu_link.indexOf('http') != -1 || menu_link.indexOf('https') != -1) {
                                menus = menus.concat("<li class='" + menu_sale + "'><a href='" + response.data[a].menu_link +
                                    "' target='_blank'>" + response.data[a].category_name + "</a></li>");
                            } else {
                                menus = menus.concat("<li class='" + menu_sale + "'><a href='<?php echo BASE_URL; ?>category/" + response.data[a].category_slug +
                                    "'>" + response.data[a].category_name + "</a>");
                            }
                        }
                    }
                    $("#main-menu").html(menus);
                },
                "error": function(response) {
                    $("#main-menu").html(response.errors);
                }
            });
        }

        function get_top_mainmenus(api_url_prefix) {
            var zipcode = '';
            if (typeof Cookies.get("zipcode") !== "undefined") {
                zipcode = Cookies.get("zipcode");
            }
            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "menu_id": "1",
                "zipcode": zipcode
            };
            var menus = "";
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'get-menus',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function(response) {
                    console.log(response);
                    for (let a = 0; a < response.data.length; a++) {

                        if (response.data[a].child_menus != null) {

                            var child_menus = "";
                            for (var b = 0; b < response.data[a].child_menus.length; b++) {

                                var child_menu_link = response.data[a].child_menus[b].child_menu_link;
                                var child_category_slug = response.data[a].child_menus[b].child_category_slug;
                                if (child_menu_link.indexOf('http') != -1 || child_menu_link.indexOf('https') != -1) {
                                    child_menus = child_menus.concat("<li><a href='" + child_menu_link + "' target='_blank'>" + response.data[a].child_menus[b].child_category_name + "</a></li>");
                                } else {
                                    child_menus = child_menus.concat("<li><a href='<?php echo BASE_URL; ?>category/" + child_category_slug + "'>" + response.data[a].child_menus[b].child_category_name + "</a></li>");
                                }

                            }
                            menus = menus.concat("<li class='nav__item'><a href='javascript:void();'>" + response.data[a].category_name + "</a><button class='but'></button><div class='mainsubmenu'><ul>" + child_menus + "</ul></div></li>");
                        } else {
                            var category_slug = response.data[a].category_slug;
                            var menu_link = response.data[a].menu_link;
                            var menu_sale = '';
                            if (response.data[a].category_name == 'Sale') {
                                menu_sale = 'menu_sale';
                            }
                            if (menu_link.indexOf('http') != -1 || menu_link.indexOf('https') != -1) {
                                menus = menus.concat("<li class='" + menu_sale + "nav__item'><a href='" + response.data[a].menu_link +
                                    "' target='_blank'>" + response.data[a].category_name + "</a></li>");
                            } else {
                                menus = menus.concat("<li class='" + menu_sale + "nav__item'><a href='<?php echo BASE_URL; ?>" + response.data[a].menu_link +
                                    "'>" + response.data[a].category_name + "</a>");
                            }
                        }
                    }
                    $("#nav__list").html(menus);
                    //responsive menu js
                    const container = document.querySelector('.navigation')
                    const primary = container.querySelector('.nav__list')
                    const primaryItems = container.querySelectorAll('.nav__list > li:not(.nav__item__more)')
                    container.classList.add('--jsfied')
                    console.log(primaryItems);
                    // insert "more" button and duplicate the list
                    primary.insertAdjacentHTML('beforeend', `
                    <li class="nav__item__more">
                        <button type="button" aria-haspopup="true" aria-expanded="false" class="mor_btn">
                        <li class="nav_items">More</li>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#fff" version="1.1" width="16" height="16" viewBox="0 0 256 256" xml:space="preserve">
                        <defs>
                        </defs>
                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: #fff; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        <polygon points="39.64,90 35.36,85.72 76.08,45 35.36,4.28 39.64,0 84.64,45 " style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
                        <polygon points="9.64,90 5.36,85.72 46.08,45 5.36,4.28 9.64,0 54.64,45 " style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
                        </g>
                        </svg>
                        </button>
                        <ul class="nav__list__more">
                        ${primary.innerHTML}
                        </ul>
                    </li>
                    `)

                    const secondary = container.querySelector('.nav__list__more')
                    const secondaryItems = secondary.querySelectorAll('li .nav__item')
                    const allItems = container.querySelectorAll('li .nav__item')
                    const moreLi = primary.querySelector('.nav__item__more')
                    const moreBtn = moreLi.querySelector('button')

                    moreBtn.addEventListener('click', (e) => {
                        e.preventDefault()
                        container.classList.toggle('nav__active')
                        moreBtn.setAttribute('aria-expanded', container.classList.contains('nav__active'))
                    })

                    // adapt tabs
                    const doAdapt = () => {
                        // reveal all ite  ms for the calculation
                        allItems.forEach((item) => {
                            //console.log(item);
                            item.classList.remove('nav__hidden')
                        })
                        console.log(allItems);
                        // hide items that won't fit in the Primary
                        let stopWidth = moreBtn.offsetWidth
                        let hiddenItems = []
                        const primaryWidth = primary.offsetWidth
                        primaryItems.forEach((item, i) => {
                            if (primaryWidth >= stopWidth + item.offsetWidth) {
                                stopWidth += item.offsetWidth
                            } else {
                                item.classList.add('nav__hidden')
                                hiddenItems.push(i)
                            }
                        })

                        // toggle the visibility of More button and items in Secondary
                        if (!hiddenItems.length) {
                            moreLi.classList.add('nav__hidden')
                            container.classList.remove('nav__active')
                            moreBtn.setAttribute('aria-expanded', false)
                        } else {
                            secondaryItems.forEach((item, i) => {
                                if (!hiddenItems.includes(i)) {
                                    item.classList.add('nav__hidden')
                                }
                            })
                        }
                    }

                    doAdapt() // adapt immediately on load
                    window.addEventListener('resize', doAdapt) // adapt on window resize

                    // hide Secondary on the outside click

                    document.addEventListener('click', (e) => {
                        let el = e.target
                        while (el) {
                            if (el === secondary || el === moreBtn) {
                                return;
                            }
                            el = el.parentNode
                        }
                        container.classList.remove('nav__active')
                        moreBtn.setAttribute('aria-expanded', false)
                    });
                },
                "error": function(response) {
                    $("#nav__list").html(response.errors);
                }
            });
        }

        function get_side_menus(api_url_prefix) {

            var zipcode = '';
            if (typeof Cookies.get("zipcode") !== "undefined") {
                zipcode = Cookies.get("zipcode");
            }
            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "menu_id": "2",
                "zipcode": zipcode
            };
            var menus = "";
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'get-menus',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function(response) {
                    for (let a = 0; a < response.data.length; a++) {
                        menus = menus.concat("<li><a href='<?php echo BASE_URL; ?>category/" + response.data[a].category_slug +
                            "'>" + response.data[a].category_name + "</a></li>");
                    }

                    $("#menu-categories").html(menus);
                },
                "error": function(e) {
                    $("#menu-categories").html(response.errors);
                }
            });
        }

        function setCookie(cname, cvalue, exdays = 365, domain = "") {
            var d = new Date();
            d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
            var expires = "expires=" + d.toUTCString();
            if (domain != "") {
                // domain=document.domain.match(/[^\.]*\.[^.]*$/)[0];
                domain = "domain=" + domain + ";";
            }

            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;samesite=Strict;" + domain;
        }

        function setZipCodeCookie(call_from) {

            if (call_from == 'header') {
                var zipcode = $("#zipcode").val();
            } else if (call_from == 'cart') {
                var zipcode = $("#zipcode_cart").val();
            } else if (call_from == 'popup') {
                $('#zipcode_popup').trigger('change');
                var zipcode = $("#zipcode_popup_value").val();
                if (zipcode == '') {
                    var firstItem = $("#zipcode_popup_data ul").find("li:first").text();
                    zipcode = firstItem.replace(/ *\([^)]*\) */g, "");
                }
                console.log(zipcode);
                // return false;
            }else if (call_from == 'popup_new') {
                $('#zipcode_popup_new').trigger('change');
                var zipcode = $("#zipcode_popup_new_value").val();
                if (zipcode == '') {
                    var firstItem = $("#zipcode_popup_data ul").find("li:first").text();
                    zipcode = firstItem.replace(/ *\([^)]*\) */g, "");
                }
                console.log(zipcode);
                // return false;
            }
            if (zipcode != '') {

                var json_request = {
                    "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                    "zipcode": zipcode
                };

                $.ajax({
                    "type": "POST",
                    "url": api_url_prefix + 'get-zipcode-detail',
                    "data": JSON.stringify(json_request),
                    "dataType": "JSON",
                    "success": function(response) {
                        if (response.data != null) {

                            // Cookies.set('zipcode', zipcode);
                            setCookie('zipcode', zipcode, 7);

                            // Cookies.set('minimum_order_value', '');
                            //Cookies.set('is_deliver_perishable_products', '');
                            //Cookies.set('delivery_type', '');
                            //Cookies.set('delivery_days', '');
                            // Cookies.set('zipcode_success_message', '');
                            //Cookies.set('zipcode_error_message', '');
                            // Cookies.set('valid_zipcode', '');
                            Cookies.set('current_page', '');

                            Cookies.set('minimum_order_value', response.data[0].minimum_order_value);
                            Cookies.set('is_deliver_perishable_products', response.data[0].can_deliver_perishable_products);
                            Cookies.set('delivery_type', response.data[0].delivery_types);
                            Cookies.set('delivery_days', response.data[0].delivery_days);
                            Cookies.set('delivery_area_name', response.data[0].area_name);
                            Cookies.set('delivery_state_id', response.data[0].state_id);
                            Cookies.set('zipcode_success_message', 'Yes, We deliver in your area!');
                            Cookies.set('zipcode_error_message', '');
                            Cookies.set('valid_zipcode', 'TRUE');
                            window.location = '<?php echo $actual_link; ?>';

                        } else {
                            // Cookies.set('zipcode', zipcode);
                            setCookie('zipcode', zipcode, 7);

                            Cookies.set('minimum_order_value', '');
                            Cookies.set('is_deliver_perishable_products', '');
                            Cookies.set('delivery_type', '');
                            Cookies.set('delivery_days', '');
                            Cookies.set('delivery_area_name', '');
                            Cookies.set('delivery_state_id', '');
                            Cookies.set('zipcode_success_message', '');
                            // Cookies.set('zipcode_error_message', '');
                            // Cookies.set('valid_zipcode', '');

                            $("#zipcode_message").html('Sorry We do not deliver in your area.');
                            $("#zipcode_popup_message").html('Sorry We do not deliver in your area.');
                            Cookies.set('zipcode_error_message', 'Sorry We do not deliver in your area.');
                            Cookies.set('valid_zipcode', 'FALSE');
                            window.location = '<?php echo $actual_link; ?>';
                        }

                    },
                    "error": function(response) {
                        console.log('error');
                        $("#zipcode_message").html('Sorry We do not deliver in your area.');
                        $("#zipcode_popup_message").html('Sorry We do not deliver in your area.');
                        Cookies.set('valid_zipcode', 'FALSE');
                        window.location = '<?php echo $actual_link; ?>';
                    }
                });
            } else {
                if (zipcode != '') {
                    $("#zipcode_message1").html('Please enter zipcode');
                    $("#zipcode_popup_message1").html('Please enter zipcode');
                    $("#success_center").hide();
                }
            }

            return false;
        }

        function setZipCodeCookie1(call_from) {

            if (call_from == 'header') {
                var zipcode = $("#zipcode").val();
            } else if (call_from == 'cart') {
                var zipcode = $("#zipcode_cart").val();
            } else if (call_from == 'popup') {
                $('#zipcode_popup').trigger('change');
                var zipcode = $("#zipcode_popup_value").val();
                if (zipcode == '') {
                    var firstItem = $("#zipcode_popup_data ul").find("li:first").text();
                    zipcode = firstItem.replace(/ *\([^)]*\) */g, "");
                }
                console.log(zipcode);
                // return false;
            }
            if (zipcode != '') {

                var json_request = {
                    "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                    "zipcode": zipcode
                };

                $.ajax({
                    "type": "POST",
                    "url": api_url_prefix + 'get-zipcode-detail',
                    "data": JSON.stringify(json_request),
                    "dataType": "JSON",
                    "success": function(response) {
                        if (response.data != null) {

                            // Cookies.set('zipcode', zipcode);
                            setCookie('zipcode', zipcode, 7);

                            // Cookies.set('minimum_order_value', '');
                            //Cookies.set('is_deliver_perishable_products', '');
                            //Cookies.set('delivery_type', '');
                            //Cookies.set('delivery_days', '');
                            // Cookies.set('zipcode_success_message', '');
                            //Cookies.set('zipcode_error_message', '');
                            // Cookies.set('valid_zipcode', '');
                            Cookies.set('current_page', '');

                            Cookies.set('minimum_order_value', response.data[0].minimum_order_value);
                            Cookies.set('is_deliver_perishable_products', response.data[0].can_deliver_perishable_products);
                            Cookies.set('delivery_type', response.data[0].delivery_types);
                            Cookies.set('delivery_days', response.data[0].delivery_days);
                            Cookies.set('delivery_area_name', response.data[0].area_name);
                            Cookies.set('delivery_state_id', response.data[0].state_id);
                            Cookies.set('zipcode_success_message', 'Yes, We deliver in your area!');
                            Cookies.set('zipcode_error_message', '');
                            Cookies.set('valid_zipcode', 'TRUE');
                            window.location.replace(front_urls + "cart-detail");

                        } else {
                            // Cookies.set('zipcode', zipcode);
                            setCookie('zipcode', zipcode, 7);

                            Cookies.set('minimum_order_value', '');
                            Cookies.set('is_deliver_perishable_products', '');
                            Cookies.set('delivery_type', '');
                            Cookies.set('delivery_days', '');
                            Cookies.set('delivery_area_name', '');
                            Cookies.set('delivery_state_id', '');
                            Cookies.set('zipcode_success_message', '');
                            // Cookies.set('zipcode_error_message', '');
                            // Cookies.set('valid_zipcode', '');

                            $("#zipcode_message").html('Sorry We do not deliver in your area.');
                            $("#zipcode_popup_message").html('Sorry We do not deliver in your area.');
                            Cookies.set('zipcode_error_message', 'Sorry We do not deliver in your area.');
                            Cookies.set('valid_zipcode', 'FALSE');
                            window.location.replace(front_urls + "cart-detail");
                        }

                    },
                    "error": function(response) {
                        console.log('error');
                        $("#zipcode_message").html('Sorry We do not deliver in your area.');
                        $("#zipcode_popup_message").html('Sorry We do not deliver in your area.');
                        Cookies.set('valid_zipcode', 'FALSE');
                        window.location.replace(front_urls + "cart-detail");
                    }
                });
            } else {
                if (zipcode != '') {
                    $("#zipcode_message1").html('Please enter zipcode');
                    $("#zipcode_popup_message1").html('Please enter zipcode');
                    $("#success_center").hide();
                }
            }

            return false;
        }

        function get_top_banner(api_url_prefix) {
            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "menu_id": "1"
            };
            var menus = "";
            $.ajax({
                "type": "POST",
                "url": api_url_prefix + 'get-top-banner',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function(response) {
                    console.log(response);
                    if (response.is_successful == 1) {
                        for (let a = 0; a < response.data.length; a++) {
                            //var top_banner = `${response.data[a].title} <a href="${response.data[a].url}">Click Here</a>`;
                            var top_banner = `${response.data[a].title}`;

                        }
                        $(".top-banner-text").html(top_banner);
                    $(".top-banner").show();
                    }
                    
                }
            });
        }

        $('.numberonly').keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode;
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });

        $('.zipcode_only').keypress(function(e) {
            var charCode = (e.which) ? e.which : event.keyCode;
            if (charCode == 13) {
                setZipCodeCookie('header');
            }
            if ((charCode >= 48 && charCode <= 57) || charCode == 13) {
                return true;
            } else {
                e.preventDefault();
                return false;
            }
        });

        function login_cart_data(webpathname) {
            var json_request = {
                "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
                "user_id": Cookies.get('user_id'),
            };
            $.ajax({
                "type": "POST",
                "url": front_url + 'cart/logincartdata',
                "dataType": "JSON",
                "data": json_request,
                "success": function(response) {

                    if (response.is_successful == "1") {
                        console.log("cartData", response.data);
                        $("#cartCount").text('0');
                        $("#cartCount").text(response.data.total_items);
                        cart_arr = response.data;
                        if (webpathname == "/cart-detail") {
                            $.ajax({
                                "type": "POST",
                                "url": front_url + 'load-cart-data',
                                "dataType": "JSON",
                                "data": json_request,
                                "success": function(data) {
                                    console.log("cartHtml", data.html);
                                    $('#cart-data').empty();
                                    $('#cart-data').html(data.html);
                                    $("#zipcode_cart").val(Cookies.get("zipcode"));
                                },
                                "error": function(response) {
                                    $('#cart-data').empty();
                                    $("#zipcode_cart").val(Cookies.get("zipcode"));
                                }
                            });
                        }
                    } else {
                        $("#cartCount").text('0');
                        if (webpathname == "/cart-detail") {
                            $('#cart-data').empty();
                            $("#zipcode_cart").val(Cookies.get("zipcode"));
                        }
                    }

                },
                "error": function(response) {
                    $("#cartCount").text('0');
                    if (webpathname == "/cart-detail") {
                        $('#cart-data').empty();
                    }
                }
            });
        }
    </script>