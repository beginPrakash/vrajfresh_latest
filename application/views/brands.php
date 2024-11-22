<?php require_once('common/header.php'); ?>
<style>
	.filters {
		display: block;
	}

	a {
		text-decoration: none;
	}
</style>
<?php // echo "<pre>";print_r($product_search);exit;
?>
<section class="categories-banner" id="category_product_title">
	<!-- <h2>VEGETABLES</h2> -->
</section>
<section class="category-product">
	<input type="hidden" id="product_ids">
	<div class="container">
		<div class="container-flex ">

			<div class="product-filter">

				<div class="product-grid" id="category_product">
					<input type="hidden" id="brand_url" value="<?php echo ($url != "" ? $url : ""); ?>">
					<input type="hidden" id="search_product"
						value="<?php echo ($product_search != "" ? $product_search : ""); ?>">

				</div>
			</div>
		</div>
	</div>
</section>
<?php require_once('common/common_js.php'); ?>
<script>
	$(document).ready(function () {
		showProgress('div#spinner');
		get_brands(api_url_prefix);
		hideProgress('div#spinner');
	});
</script>
<script>
	function get_brands(api_url_prefix) {

		var json_request = {
			"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"is_active_only": "1",
			"search_keyword": "",
			"limit": "",
			"page_no": "",
			"sort_column": "brand_id",
			"sort_order": "desc"
		};
		var brands = "";
		$.ajax({
			"type": "POST",
			"url": api_url_prefix + 'get-brands',
			"data": JSON.stringify(json_request),
			"dataType": "JSON",
			"success": function (response) {
				if (response.data != null) {
					brand_title = '<h2>Brands</h2>';
					//product_title = '<h2>' + response.data.category.category_name + '</h2>';
					for (let a = 0; a < response.data.length; a++) {

						brands = brands.concat('<a href="<?php echo BASE_URL; ?>brand/' + response.data[a].brand_slug + '"><div class="product-box"><img src=' + response.data[a].brand_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png"><div><h4>' + response.data[a].brand_name + '</h4></a></div></div>');
					}
					$("#category_product_title").html(brand_title);
					$("#category_product").html(brands);


				} else {
					brands = '<div class="product-box">No Brand Found</div>';
					$("#category_product").html(brands);
				}
			},
			"error": function (response) {
				$("#category_product").html(response.errors);
			}

		});
	}
</script>
<?php require_once('common/footer.php'); ?>