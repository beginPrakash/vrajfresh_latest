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

   .tabs {
        margin: 50px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Tab Buttons */
    .tab-buttons {
        display: flex;
        border-bottom: 2px solid #eee;
    }

    .tab-buttons button {
        flex: 1;
        padding: 15px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 16px;
        font-weight:bold;
        transition: 0.3s;
    }

    .tab-buttons button:hover {
        background: #209FCF !important;
    }

    .tab-buttons button.active {
        background: #209FCF ;
        color: #fff;
    }

    /* Tab Content */
    .tab-content {
        display: none;
        padding: 20px;
        animation: fadeEffect 0.4s;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeEffect {
        from {opacity: 0;}
        to {opacity: 1;}
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
<?php if(!empty($product_description) || !empty($health_benefits) || !empty($ingredients) || !empty($usage_instructions) || !empty($storage_information) || !empty($faqs)){ ?>
    <section class="products-metadetails">
        <div class="tabs container">
            <div class="tab-buttons">
                <?php if(isset($product_description) && !empty($product_description)){ ?>
                    <button class="tab-link active" onclick="openTab(event, 'tab1')">Description</button>
                <?php } ?>
                <?php if(isset($health_benefits) && !empty($health_benefits)){ ?>
                    <button class="tab-link" onclick="openTab(event, 'tab2')"><?= $health_benefit_title; ?></button>
                <?php } ?>
                <?php if(isset($ingredients) && !empty($ingredients)){ ?>
                    <button class="tab-link" onclick="openTab(event, 'tab3')">Ingredients</button>
                <?php } ?>
                <?php if(isset($usage_instructions) && !empty($usage_instructions)){ ?>
                    <button class="tab-link" onclick="openTab(event, 'tab4')">Usage Instructions</button>
                <?php } ?>
                <?php if(isset($storage_information) && !empty($storage_information)){ ?>
                    <button class="tab-link" onclick="openTab(event, 'tab5')">Storage Information</button>
                <?php } ?>
                <?php if(isset($faqs) && !empty($faqs)){ ?>
                    <button class="tab-link" onclick="openTab(event, 'tab6')">Faqs</button>
                <?php } ?>
            </div>
            <?php if(isset($product_description) && !empty($product_description)){ ?>
                <div id="tab1" class="tab-content active">
                    <?= $product_description; ?>
                </div>
            <?php } ?>
            <?php if(isset($health_benefits) && !empty($health_benefits)){ ?>
                <div id="tab2" class="tab-content">
                    <?= $health_benefits; ?>
                </div>
            <?php } ?>
            <?php if(isset($ingredients) && !empty($ingredients)){ ?>
                <div id="tab3" class="tab-content">
                <?= $ingredients; ?>
                </div>
            <?php } ?>
            <?php if(isset($usage_instructions) && !empty($usage_instructions)){ ?>
                <div id="tab4" class="tab-content">
                    <?= $usage_instructions; ?>
                </div>
            <?php } ?>
            <?php if(isset($storage_information) && !empty($storage_information)){ ?>
                <div id="tab5" class="tab-content">
                    <?= $storage_information; ?>
                </div>
            <?php } ?>
            <?php if(isset($faqs) && !empty($faqs)){ ?>
                <div id="tab6" class="tab-content">
                    <?= $faqs; ?>
                </div>
            <?php } ?>
            <!-- Disclaimer Section -->
            <div class="product-disclaimer">
                <span>
                    Customers with food allergies or specific dietary requirements should carefully read the product labels, ingredients, and warnings before consuming any product.
                    The information provided on this website is for reference purposes only and should not replace professional advice.
                </span>
            </div>
        </div>
    </section>
<?php } ?>
<section class="related-products">

    <div class="container">

        <h2>Related Products</h2>

        <div class="product-grid" id="related-products">



        </div>

    </div>

</section>

<?php require_once('common/common_js.php'); ?>

<?php require_once('scripts/product_detail_js.php'); ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Product",
  "@id": "<?= base_url('product/'.$url); ?>",
  "name": "<?= addslashes($product_name); ?>",
  "image": [
    "<?= base_url('admin/uploads/products/'.$product_image); ?>"
  ],
  "description": "<?= addslashes(strip_tags($product_description)); ?>",
  "sku": "<?= $product_sku; ?>",
  "brand": {
    "@type": "Brand",
    "name": "<?= $brand_name; ?>"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": <?= $rating_value; ?>,
    "reviewCount": <?= $review_count; ?>
  },
  "offers": {
    "@type": "Offer",
    "url": "<?= base_url('product/'.$url); ?>",
    "priceCurrency": "USD",
    "price": <?= $product_price; ?>,
    "availability": "https://schema.org/<?= $is_stock; ?>",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>



<?php require_once('common/footer.php'); ?>