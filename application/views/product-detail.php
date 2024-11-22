<?php require_once('common/header.php'); ?>

<style>

    a {

        text-decoration: none;

    }



    .price-filter li.out_of_stock_variant {

        background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 100 100'><path d='M1 0 L0 1 L99 100 L100 99' fill='black' /><path d='M0 99 L99 0 L100 1 L1 100' fill='black' /></svg>");

        background-repeat: no-repeat;

        background-position: center center;

        background-size: 100% 100%, auto;

        pointer-events: none;

        cursor: default;

    }



    .product-stock-message {

        color: red;

    }



    .out_of_stock {

        display: none !important;

    }

    .d-none{
      display: none !important;
   }
   @media only screen and (max-width: 767px) {
    .quantity button {
        padding: 0 6px !important;
    }
   }

</style>

<section class="breadcrumb">

    <div class="container" id="breadcrumb">

        <input type="hidden" name="product_slug" id="product_slug" value="<?php echo $url; ?>">

    </div>

</section>

<section class="product-details-banner">

    <div class="container container-flex">

        <div class="product-details-left">

            <!--<img class="svg" src="images/Cilantro.jpg">-->

        </div>

        <style>

            .main {

                font-family: Arial;

                width: 500px;

                display: block;

                margin: 0 auto;

            }

        </style>

        <div class="product-details-right">

        </div>

        <div class="product-usp">

            <ul>

                <li> <img class="svg" src="<?php echo ASSET_URL; ?>images/same-day-delivery.png"> <span>Same Day

                        Delivery</span></li>

                <li><img class="svg" src="<?php echo ASSET_URL; ?>images/same-day-delivery.png"> <span>Free

                        Delivery</span></li>

                <li> <img class="svg" src="<?php echo ASSET_URL; ?>images/return-policy.png"> <span>Easy Return

                        Policy</span></li>

                <li> <img class="svg" src="<?php echo ASSET_URL; ?>images/fresh-products.png"> <span>Pure And Fresh

                        Products</span></li>

                <li> <img class="svg" src="<?php echo ASSET_URL; ?>images/the-market.png"> <span>Best Price In The

                        Market</span></li>

            </ul>

        </div>

    </div>

</section>

<section class="related-products">

    <div class="container">

        <h2>Related Products</h2>

        <div class="product-grid" id="related-products">



        </div>

    </div>

</section>

<?php require_once('common/common_js.php'); ?>

<?php require_once('scripts/product_detail_js.php'); ?>



<?php require_once('common/footer.php'); ?>