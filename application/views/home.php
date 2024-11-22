<?php require_once('common/header.php'); ?>
<style>
	
	.product-stock-message {
		color: red;
	}

	.out_of_stock {
		display: none !important;
	}
   .d-none{
      display: none !important;
   }
   .view_all:hover{
      color:#0000EE;
   }
   .view_all:focus{
      color:#0000EE;
   }
   .view_all:visited{
      color:#0000EE;
   }
   video {
      max-width: 100%;
      height: auto;
   }
   #stock_container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: flex-start;
    margin-top: 50px;
    margin-bottom: 40px;
}

@media only screen and (max-width: 358px) {
    .mobile-menu {
        top: 85px;
    }
}
</style>
<!-- <section class="banner container">
   <div class="banner-left">
      <div class="slick-carousel" id="home_banner"> </div>
   </div>
   <div class="banner-right" id="banner-right"> </div>
</section> -->

<script>
   function showMore() {
    const items = document.querySelectorAll('.col-md-2');
    items.forEach(item => {
        item.classList.remove('hidden');
    });

    const viewMoreButton = document.getElementById('view-more');
    viewMoreButton.classList.add('hidden');
}

// On page load, hide items beyond the first 2 rows (based on the screen size)

document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.col-md-2');
    const itemsPerRow = window.innerWidth >= 600 ? 5 : 1; // Adjust based on your layout
    const visibleItemsCount = itemsPerRow * 2;

    items.forEach((item, index) => {
        if (index >= visibleItemsCount) {
            item.classList.add('hidden');
        }
    });

    // Show the "View More" button only if there are more items to show
   const viewMoreButton = document.getElementById('view-more');
   if (viewMoreButton) {
      if (items.length <= visibleItemsCount) {
        viewMoreButton.classList.add('hidden');
      }    
   }
    
});

</script>

<section class="banner">
   <div class="home_banner" id="home_banner"> </div>
   <div class="home_banner_mobile" id="home_banner_mobile"> </div>
</section>

<section class="categories">
   <div class="container">
      <div id="featured_category"></div>
      <a id="viewMoreBtn">View All</a>
   </div>
</section>

<section class="categories suyf">
   <div class="container">
      <div class="slockup-title" id="stock_container"></div>
      <div id="stockup_your_frozen"></div>
   </div>
</section>

<section class="categories">
   <div class="container">
   <div class="advertise_top" id="advertise_top"></div>
      <div class="advertise_top_mobile" id="advertise_top_mobile"></div>
   </div>
</section>

<div class="home_product_slider_main"></div>

<!-- <section class="">
   <div class="container">
      <div id="title_container" class="new_savings-title"></div>
      <div id="new_savings"></div>
   </div>
</section>

<section class="categories">
   <div class="container">
      <div id="title_container" class="fresh_veg-title"></div>
      <div id="fresh_veg"></div>
   </div>
</section>

<section class="categories">
   <div class="container">
      <div id="title_container" class="vraj_backery-title"></div>
      <div id="vraj_backery"></div>
   </div>
</section>

<section class="categories">
   <div class="container">
      <div id="title_container" class="shop_ayurvedic-title"></div>
      <div id="shop_ayurvedic"></div>
   </div>
</section> -->

<section class="categories">
   <div class="container">
      <div class="advertise_bottom" id="advertise_bottom"></div>
      <div class="advertise_bottom_mobile" id="advertise_bottom_mobile"></div>
   </div>
</section>

<section class="categories refill_pantry">
   <div class="container">
      <div id="title_container" class="refill_pantry-title"></div>
      <div id="refill_pantry"></div>
   </div>
</section>

<!-- 
<section class="categories">
   <div class="container">
      <div class="tab-container">
         <ul class="tabs" id="tabs">
         </ul>
      </div>
      <div id="categories_product" class="tab-content current"></div>
   </div>
</section>
<section class="vraj-bakery vraj-title">
   <div class="container" id="category_product"> </div>
   <div id="vraj-btn"></div>
</section>
<section class="vraj-title">
   <div class="container">
      <div class="popular-product" id="popular-product"> </div>
   </div>
</section>
<section class="vraj-title">
   <div class="container">
      <div class="popular-product" id="popular-product1"> </div>
   </div>
</section>
<section class="discover-greatest vraj-title container" id="discover">
   <div class="container"> </div>
</section>
<section class="discover-greatest vraj-title">
   <div class="container">
      <h2>Shop by Brand</h2>
      <div class="brand" id="brand"> </div>
   </div>
</section>  -->
<?php require_once('common/common_js.php'); ?>
<link href="<?php echo ASSET_URL; ?>css/toastr.css" rel="stylesheet" />
<script src="<?php echo ASSET_URL; ?>js/toastr.js"></script>


<?php require_once('scripts/home_js.php'); ?>
<?php require_once('common/footer.php'); ?>