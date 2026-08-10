<?php require_once('common/header.php'); ?>
<style>
	.filters {
		display: block;
	}

	a {
		text-decoration: none;
	}
	.d-none{
      display: none !important;
   }
</style>
<style>
	.filters {
		display: block;
	}

	a {
		text-decoration: none;
	}

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
</style>
<?php // echo "<pre>";print_r($product_search);exit;
?>
<section class="categories-banner" id="category_product_title">
</section>
<section class="category-product">
	<input type="hidden" id="product_ids">
	<div class="container">
		<div class="container-flex ">
			<div class="category-filter" id="category-filter">
				<div id="categories">
					<h3 class="accordion-toggle">CATEGORY <span class="fa fa-minus pull-right "></span></h3>
					<div class="accordion" id="category_filter">
					</div>
				</div>
				<div id="brands">
					<h3 class="accordion-toggle">BRAND <span class="fa fa-minus pull-right"></span></h3>
					<div class="accordion" id="brand_filter">
					</div>
				</div>
				<div>
					<h3 class="accordion-toggle last-accordion">PRICE <span class="fa fa-minus pull-right"></span></h3>
					<div class="accordion">
						<input placeholder="Min price" id="min_price" name="" type="text" onkeyup="apply_filter()">
						<span>To</span>
						<input placeholder="Max price" id="max_price" name="" type="text" onkeyup="apply_filter()">
					</div>
				</div>
				<div id="tags">
					<h3 class="accordion-toggle">TAGS <span class="fa fa-minus pull-right"></span></h3>
					<div class="accordion" id="tag_filter">
					</div>
				</div>
				<!-- <div><button onclick="apply_filter();" class="vraj-btn">Apply</button></div> -->
			</div>
			<div class="product-filter">

				<div class="product-grid" id="category_product">
					<input type="hidden" id="brand_url" value="<?php echo ($url != "" ? $url : ""); ?>">
					<input type="hidden" id="search_product" value="<?php echo ($product_search != "" ? $product_search : ""); ?>">
				</div>
			</div>
		</div>
	</div>
</section>
<?php require_once('common/common_js.php'); ?>
<script>
	// const api_url_prefix = "https://dev.thcitsolutions.com/vrajfresh/api/";
	// const api_url_prefix = "http://localhost/git/vraj-fresh-api/";
	$(document).ready(function() {

		showProgress('div#spinner');
		get_brand_products(api_url_prefix);
		hideProgress('div#spinner');

		$(document).on('click', '.add_cart', function() {
			var product_id = $(this).data('productid');
			var product_name = $(this).data('productname');
			var quantity = $('#' + product_id).val();
			var image = $(this).data('productimage');
			var product_slug = $(this).data('productslug');
			var is_perisible = $(this).data('isperisible');

			var parent = $("#product_varient_"+product_id).val();
			if (parent && parent != "") {
				var variant_detail = parent.split('-');
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
				"is_perisible": is_perisible
			};
			if (quantity != "" || quantity > 0) {
				var total_qty = parseInt($("#cartCount").html()) + parseInt(1);
				$("#cartCount").html(total_qty);
				$.ajax({
					"type": "POST",
					"url": front_url + 'cart/add',
					"data": JSON.stringify(json_request),
					"dataType": "JSON",
					"success": function(response) {

						$('#qty_'+product_id).find('.qty_change_sub').attr('data-productrowid',response.data);


						$("#btn_" + product_id).text('Added');
						//	alert('Product Added Into Cart');
						//  $("#cart-details").html(data);
						/*setTimeout(function() {
							location.reload();
						}, 1000);*/
					},
					"error": function(response) {
						$("#category_product").html(response.errors);
					}
				});
				$('#btn_'+product_id).addClass('d-none');
            	$('#qty_'+product_id).removeClass('d-none');

			} else {
				alert("Please Enter quantity");
			}

		});
		
		$(document).on('click', '.qty_change_add', function() {
			
			var section_id = $(this).data('section');
			var product_id = $(this).data('productid');
			var product_name = $(this).data('productname');
			var image = $(this).data('productimage');
			var product_slug = $(this).data('productslug');
			var is_perisible = $(this).data('isperisible');
			var product_tax = $(this).data('producttax');
			//	alert('product_tax'+product_tax);
			var quantity = $('#' + product_id).val();
			var parent = $("#product_varient_"+product_id).val();
			if (parent && parent != "") {
				var variant_detail = parent.split('-');
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
				"product_tax": product_tax,
				"is_perisible": is_perisible
			};

			if (quantity != "" || quantity > 0) {
				var total_qty = parseInt($("#cartCount").html()) + 1;
				$("#cartCount").html(total_qty);
				$.ajax({
					"type": "POST",
					"url": front_url + 'cart/add',
					"data": JSON.stringify(json_request),
					"dataType": "JSON",
					"success": function(response) {
						// $("#btn_" + section_id + "_" + product_id).text('Added');
						// setTimeout(() => {
					
						//     $("#btn_" + section_id + "_" + product_id).text('Add');
						// }, 3000);
					},
					"error": function(response) {
						console.log(response.errors);
					}
				});
			
				$('#qty_'+product_id).removeClass('d-none');
			} else {
				alert("Please Enter quantity");
			}
    	});

		$(document).on('click', '.qty_change_sub', function() {
			
			var section_id = $(this).data('section');
			var product_id = $(this).data('productid');
			var product_name = $(this).data('productname');
			var image = $(this).data('productimage');
			var product_slug = $(this).data('productslug');
			var is_perisible = $(this).data('isperisible');
			var product_tax = $(this).data('producttax');
			//	alert('product_tax'+product_tax);
			var quantity = $('#' + product_id).val();
			var parent = $("#product_varient_"+product_id).val();
			if (parent && parent != "") {
				var variant_detail = parent.split('-');
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
				"product_tax": product_tax,
				"is_perisible": is_perisible
			};
			
			if (quantity != "" && quantity > 0) {
				var total_qty = parseInt($("#cartCount").html()) - 1;
				$("#cartCount").html(total_qty);
				
				$.ajax({
					"type": "POST",
					"url": front_url + 'cart/add',
					"data": JSON.stringify(json_request),
					"dataType": "JSON",
					"success": function(response) {
						console.log(response);
						// $("#btn_" + section_id + "_" + product_id).text('Added');
						// setTimeout(() => {
					
						//     $("#btn_" + section_id + "_" + product_id).text('Add');
						// }, 3000);
					},
					"error": function(response) {
						console.log(response.errors);
					}
				});
			} else {
				console.log('fail');
				if (login_user_id == 0) {
						cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
						cart_arr = JSON.parse(cart_arr);
				}
				var findDay =product_id; //find price for day 1
				//find product row id
				var cart_row_id = $.map(cart_arr, function(value, key) {
					if (value.id == findDay)
					{
						return (value.options && value.options.db_rowid) ? value.options.db_rowid : value.rowid;
					}
				});

				if(cart_row_id.length > 0){
                        var cart_row_id_val = cart_row_id[0];
                        $(this).attr('data-productrowid',cart_row_id_val);
                    }else{
                        var cart_row_id_val = '';
                    }
				var product_rowid = $(this).data('productrowid');
				$.ajax({
					"type": "POST",
					"url": front_url + 'item-remove',
					"data": {row_id:product_rowid},
					"dataType": "JSON",
					"success": function(response) {
						//location.reload();
					},
					"error": function(response) {
						console.log(response.errors);
					}
				});
				$('#qty_'+product_id).addClass('d-none');
				$('#btn_'+product_id).removeClass('d-none');
				// 🔥 IMPORTANT: reset input to 1
                    $('#' + product_id).val(1);
			}
		});

	});

	$(document).on('change', '.product_varient', function() {
        
        var parent = $(this).val();
        var product_id = $(this).data('productid');
        
        if (parent && parent != "") { 
            
            var variant_detail = parent.split('-');
            var weight = variant_detail[0];
            var price = variant_detail[1];
            var variant_id = variant_detail[2];
    
			get_cart_arr().then(function(cart_arr) {
				var cart_qty = $.map(cart_arr, function(value, key) {
					if (value.id == product_id && variant_id == value.options.variant_id)
					{
						return value.qty;
					}
				});
				//find product row id
				var cart_row_id = $.map(cart_arr, function(value, key) {
					if (value.id == product_id && variant_id == value.options.variant_id)
					{
						return (value.options && value.options.db_rowid) ? value.options.db_rowid : value.rowid;
					}
				});
				var varient_id = $.map(cart_arr, function(value, key) {
					if (value.id == product_id && variant_id == value.options.variant_id)
					{
						return value.options.variant_id;
					}
				});
		
				if(cart_row_id.length > 0){
					var cart_row_id_val = cart_row_id[0];
				}else{
					var cart_row_id_val = '';
				}
		
				if(cart_qty.length > 0){
					var cart_qty_val = cart_qty[0];
				}else{
					var cart_qty_val = 1;
				}
				if(cart_qty.length > 0){
					
					var qty_class="";
					var add_class="d-none";
				}else{
					var qty_class="d-none";
					var add_class="";
				}
				if(varient_id.length > 0){
					var varient_id_val = varient_id[0];
				}else{
					var varient_id_val = 0;
				}   
				if(varient_id_val > 0 && cart_row_id_val != ""){
					
					$("#btn_"+product_id).addClass("d-none");
					$("#qty_"+product_id).removeClass("d-none");
					$('#qty_'+product_id+' input[type="text"]').val(cart_qty_val);
				} else {
					$("#btn_"+product_id).removeClass("d-none");
					$("#qty_"+product_id).addClass("d-none");
					$('#qty_'+product_id+' input[type="text"]').val(1);
					
				}
			});
        }
    });

	function get_cart_arr(){
		return new Promise(function(resolve, reject) {
			$.ajax({
				"type": "GET",
				"url": front_url + 'cart-session-data',
				"dataType": "JSON",
				"success": function(response) {
					if(response.is_successful == 1){
						resolve(response.data);
					} else {
						resolve([]);
					}
				},
				"error": function(response) {
					resolve([]);
				}
			});
		});
	}

	function get_filters(api_url_prefix, product_ids) {
		var url = $("#category_url").val();
		//var product_ids=$("#product_ids").val();
		var json_request = {

			"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"product_ids": product_ids

		};
		var category = "";
		var brand = "";
		var tag = "";
		$.ajax({
			"type": "POST",
			"url": api_url_prefix + 'get-filters',
			"data": JSON.stringify(json_request),
			"dataType": "JSON",
			"success": function(response) {
				if (response.data != null) {
					//product_title = '<h2>' + response.data.category.category_name + '</h2>';
					category_detail = response.data.filters.category;
					brand_detail = response.data.filters.brand;
					tag_detail = response.data.filters.tag;

					if (category_detail != null || category_detail != "") {
						category_key = Object.keys(category_detail);
						if (category_key.length > 0) {
							for (let i = 0; i < category_key.length; i++) {
								category = category.concat('<ul class="filters"><li><input name="" type="checkbox" id="category_' + category_key[i] + '" value="' + category_key[i] + '" class="checkbox" onchange="apply_filter()"><label for="category_' + category_key[i] + '">' + category_detail[category_key[i]] + '</label></li></ul>');
							}
						}
						$("#category_filter").html(category);
					} else {
						$("#categories").hide();
					}
					if (brand_detail != null || brand_detail != "") {
						brand_key = Object.keys(brand_detail);
						if (brand_key.length > 0) {
							for (let j = 0; j < brand_key.length; j++) {
								brand = brand.concat('<ul class="filters"><li><input name="" type="checkbox" id="brand_' + brand_key[j] + '" value="' + brand_key[j] + '" class="checkbox" onchange="apply_filter()"><label for="brand_' + brand_key[j] + '">' + brand_detail[brand_key[j]] + '</label></li></ul>');
							}
						}
						$("#brand_filter").html(brand);

					} else {
						$("#brands").hide();
					}
					if (tag_detail != null || tag_detail != "") {
						tag_key = Object.keys(tag_detail);
						if (tag_key.length > 0) {
							for (let k = 0; k < tag_key.length; k++) {
								tag = tag.concat('<ul class="filters"><li><input name="" type="checkbox" id="tag_' + tag_key[k] + '" value="' + tag_key[k] + '" class="checkbox" onchange="apply_filter()"><label for="tag_' + tag_key[k] + '">' + tag_detail[tag_key[k]] + '</label></li></ul>');
							}
						}
						$("#tag_filter").html(tag);
					} else {
						$("#tags").hide();
					}


				}
			},
			"error": function(response) {
				$("#category_product").html(response.errors);
			}

		});

	}

	function get_brand_products(api_url_prefix) {
		console.log('test');
		var url = $("#brand_url").val();
		var search = $("#search_product").val();
		if (search != "" && search != undefined) {
			var json_request = {
				"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
				"search_term": search
			};
			var product = "";
			$.ajax({
				"type": "POST",
				"url": api_url_prefix + 'get-brand-product-search',
				"data": JSON.stringify(json_request),
				"dataType": "JSON",
				"success": function(response) {
					if (response.data.product_id != null) {

						//product_title = '<h2>' + response.data.category.category_name + '</h2>';
						// for (let a = 0; a < response.data.products.length; a++) {
						// 	var price_weight = "";
						// 	if (response.data.products[a].product_size.length > 1) {

						// 		var price_weight = '<form name="form" id="form"><select name="jumpMenu" class="variants" id="jumpMenu">';
						// 		for (let j = 0; j < response.data.products[a].product_size.length; j++) {
						// 			price_weight = price_weight.concat('<option value="' + response.data.products[a].product_size[j].size + '-' + response.data.products[a].product_size[j].price + '-' + response.data.products[a].product_size[j].variant_id + '">' + response.data.products[a].product_size[j].size + 'lb - $' + response.data.products[a].product_size[j].price + '</option>');
						// 		}
						// 		price_weight += '</select></form>';
						// 	} else {
						// 		var price_weight = '<span>' + response.data.products[a]
						// 			.product_weight_gms + 'lb</span> - <strong>$' + response.data.products[a]
						// 			.product_price + '</strong>';
						// 	}

						// 	//product = product.concat('<div class="product-box"><img src='+response.data[a]["image"]+'><h4>'+response.data[a]["product_name"]+'</h4><strong>'+response.data[a]["product_price"]+'</strong> <span>'+response.data[a]["product_weight_gms"]+'</span><ul><li><img src=<?php echo ASSET_URL . "images/plus-minus.png"; ?>></li><li><button>Add</button></li></ul></div>');
						// 	product = product.concat('<a href="<?php echo BASE_URL; ?>product/' + response.data.products[a].product_slug + '"><div class="product-box"><img src=' + response.data.products[a].product_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png"><div><h4>' + response.data.products[a].product_name + '</h4></a>' + price_weight + '<ul><li><div class="quantity"><button type="button" id="sub" class="sub">-</button><input type="text" id="' + response.data.products[a].product_id + '" value="1" min="1" max="3" disabled /><button type="button" id="add" class="add">+</button></div></li><li><button id= "btn_' + response.data.products[a].product_id + '" class="add_cart" data-isperisible="' + response.data.products[a].is_perisible_products + '" data-productslug="' + response.data.products[a].product_slug + '" data-productimage= "' + response.data.products[a].product_image + '" data-productname="' + response.data.products[a].product_name + '" data-price=' + response.data.products[a].product_price + ' data-productid = ' + response.data.products[a].product_id + '>Add</button></li></ul></div></div>');
						// }
						for (let a = 0; a < response.data.products.length; a++) {

							var findDay = response.data.products[a].product_id; //find price for day 1
							var cart_qty = $.map(cart_arr, function(value, key) {
								if (value.id == findDay)
								{
									return value.qty;
								}
							});
							console.log(cart_qty);
							//find product row id
							var cart_row_id = $.map(cart_arr, function(value, key) {
								if (value.id == findDay)
								{
									return (value.options && value.options.db_rowid) ? value.options.db_rowid : value.rowid;
								}
							});
							var varient_id = $.map(cart_arr, function(value, key) {
								if (value.id == findDay)
								{
									return value.options.variant_id;
								}
							});
	
	
							if(cart_row_id.length > 0){
								var cart_row_id_val = cart_row_id[0];
							}else{
								var cart_row_id_val = '';
							}
	
							if(cart_qty.length > 0){
								if(cart_qty.length == 2){
									var cart_qty_val = cart_qty[1];
								}else{
									var cart_qty_val = cart_qty[0];
								}
							}else{
								cart_qty_val = 1;
							}
							if(cart_qty.length > 0){
								var qty_class="";
								var add_class="d-none";
							}else{
								var qty_class="d-none";
								var add_class="";
							}
							if(cart_qty.length > 0){
								var cart_qty_val = cart_qty[0];
							}else{
								var cart_qty_val = 1;
							}
							var out_of_stock = "<div class='product-stock-message'></div>";
							var out_of_stock_class = "";
							var variant_in_stock_count = 0;
							/*if (response.data.products[a].is_out_of_stock == 0) {
								out_of_stock = "<div class='product-stock-message'>Sold out</div>";
								out_of_stock_class = "out_of_stock";
							}*/
							if (response.data.products[a].product_size.length > 1 && response.data.products[a].product_size[0] != "") {
								var price_weight = '<form name="form" id="form"><select id="product_varient_'+ response.data.product_id+'" data-productid="'+ response.data.product_id+'" name="variants" class="variants product_varient ' + out_of_stock_class + '">';
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
										
										var selected = "";
										if(varient_id_val == response.data.products[a].product_size[j].variant_id){
											selected = "selected";
										}
										price_weight = price_weight.concat('<option '+selected+' class="' + out_of_stock_variant + '" value="' + response.data.products[a].product_size[j].size + '-' + response.data.products[a].product_size[j].price + '-' + response.data.products[a].product_size[j].variant_id + '">' + response.data.products[a].product_size[j].size + 'lb - $' + response.data.products[a].product_size[j].price + '</option>');
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

							console.log("product sold out:" + response.data.products[a].is_out_of_stock + " and variant sold out:" + variant_in_stock_count);
							if (response.data.products[a].is_out_of_stock == 0) {
								out_of_stock = "<div class='product-stock-message'>Product Sold out</div>";
								out_of_stock_class = "out_of_stock";
								var price_weight = "";
							}

							//product = product.concat('<div class="product-box"><img src='+response.data[a]["image"]+'><h4>'+response.data[a]["product_name"]+'</h4><strong>'+response.data[a]["product_price"]+'</strong> <span>'+response.data[a]["product_weight_gms"]+'</span><ul><li><img src=<?php echo ASSET_URL . "images/plus-minus.png"; ?>></li><li><button>Add</button></li></ul></div>');
							product = product.concat('<a href="<?php echo BASE_URL; ?>product/' + response.data.products[a].product_slug + '"><div class="product-box"><img src=' + response.data.products[a].product_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" ><div><h4>' + response.data.products[a].product_name + '</h4></a>' + price_weight + out_of_stock + '<ul><li><div class="quantity ' + out_of_stock_class + '"><button type="button" id="sub" class="sub">-</button><input type="text" class = "prod_add1" id="' + response.data.products[a].product_id + '" value="1" min="1" max="3" disabled /><button type="button" id="add" class="add">+</button></div></li><li><button id= "btn_' + response.data.products[a].product_id + '" class="add_cart ' + out_of_stock_class + '" data-isperisible="' + response.data.products[a].is_perisible_products + '" data-productslug="' + response.data.products[a].product_slug + '" data-productimage= "' + response.data.products[a].product_image + '" data-productname="' + response.data.products[a].product_name + '" data-price=' + response.data.products[a].product_price + ' data-productid = ' + response.data.products[a].product_id + ' data-productweight =' + response.data.products[a].product_weight_gms + ' data-producttax="' + response.data.products[a].product_tax + '" >Add</button></li></ul></div></div>');

						}
						get_filters(api_url_prefix, response.data.product_id);
						//$("#category_product_title").html(product_title);
						$("#category_product").html(product);


					} else {
						product = '<div class="product-box">No Products Found</div>';
						$(".category-filter").hide();
						$("#category_product").html(product);
					}
				},
				"error": function(response) {
					$("#category_product").html(response.errors);
				}

			});
		} else {
			console.log('sds');
			var json_request = {
				"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
				"brand_slug": url
			};
			var product = "";
			if (login_user_id == 0) {
				cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
				cart_arr = JSON.parse(cart_arr);
			}

			
			var cart_qty_val = 1;
			$.ajax({
				"type": "POST",
				"url": api_url_prefix + 'get-brand-product-detail',
				"data": JSON.stringify(json_request),
				"dataType": "JSON",
				"success": function(response) {
					if (response.data != null) {

						product_title = '<h1 class="category_t_title">' + response.data.brand.brand_name + '</h1>';
						if (response.data.product_id != null) {
							// for (let a = 0; a < response.data.product_id.length; a++) {
							// 	var price_weight = "";
							// 	if (response.data.products[a].product_size.length > 1) {

							// 		var price_weight = '<form name="form" id="form"><select name="jumpMenu" class="variants" id="jumpMenu">';
							// 		for (let j = 0; j < response.data.products[a].product_size.length; j++) {
							// 			price_weight = price_weight.concat('<option value="' + response.data.products[a].product_size[j].size + '-' + response.data.products[a].product_size[j].price + '-' + response.data.products[a].product_size[j].variant_id + '">' + response.data.products[a].product_size[j].size + 'lb - $' + response.data.products[a].product_size[j].price + '</option>');
							// 		}
							// 		price_weight += '</select></form>';
							// 	} else {
							// 		var price_weight = '<span>' + response.data.products[a]
							// 			.product_weight_gms + 'lb</span> - <strong>$' + response.data.products[a]
							// 			.product_price + '</strong>';
							// 	}
							// 	//product = product.concat('<div class="product-box"><img src='+response.data[a]["image"]+'><h4>'+response.data[a]["product_name"]+'</h4><strong>'+response.data[a]["product_price"]+'</strong> <span>'+response.data[a]["product_weight_gms"]+'</span><ul><li><img src=<?php echo ASSET_URL . "images/plus-minus.png"; ?>></li><li><button>Add</button></li></ul></div>');
							// 	product = product.concat('<a href="<?php echo BASE_URL; ?>product/' + response.data.products[a].product_slug + '"><div class="product-box"><img src=' + response.data.products[a].product_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" ><div><h4>' + response.data.products[a].product_name + '</h4></a>' + price_weight + '<ul><li><div class="quantity"><button type="button" id="sub" class="sub">-</button><input type="text" id="' + response.data.products[a].product_id + '" value="1" min="1" max="3" disabled /><button type="button" id="add" class="add">+</button></div></li><li><button id= "btn_' + response.data.products[a].product_id + '" class="add_cart" data-isperisible="' + response.data.products[a].is_perisible_products + '" data-productslug="' + response.data.products[a].product_slug + '" data-productimage= "' + response.data.products[a].product_image + '" data-productname="' + response.data.products[a].product_name + '" data-price=' + response.data.products[a].product_price + ' data-productid = ' + response.data.products[a].product_id + ' >Add</button></li></ul></div></div>');

							// }

							for (let a = 0; a < response.data.products.length; a++) {
								var findDay = response.data.products[a].product_id; //find price for day 1
                                var cart_qty = $.map(cart_arr, function(value, key) {
                                    if (value.id == findDay)
                                    {
                                        return value.qty;
                                    }
                                });
								console.log(cart_qty);
                                //find product row id
                                var cart_row_id = $.map(cart_arr, function(value, key) {
                                    if (value.id == findDay)
                                    {
                                        return (value.options && value.options.db_rowid) ? value.options.db_rowid : value.rowid;
                                    }
                                });
								var varient_id = $.map(cart_arr, function(value, key) {
									if (value.id == findDay)
									{
										return value.options.variant_id;
									}
								});
                                if(cart_row_id.length > 0){
                                    var cart_row_id_val = cart_row_id[0];
                                }else{
                                    var cart_row_id_val = '';
                                }
        
                                if(cart_qty.length > 0){
                                    if(cart_qty.length == 2){
										var cart_qty_val = cart_qty[1];
									}else{
										var cart_qty_val = cart_qty[0];
									}
                                }else{
                                    cart_qty_val = 1;
                                }
                                if(cart_qty.length > 0){
                                    var qty_class="";
                                    var add_class="d-none";
                                }else{
                                    var qty_class="d-none";
                                    var add_class="";
                                }
								if(cart_qty.length > 0){
									var cart_qty_val = cart_qty[0];
								}else{
									var cart_qty_val = 1;
								}
								if(varient_id.length > 0){
									var varient_id_val = varient_id[0];
								}else{
									var varient_id_val = 0;
								}
								$(this).attr('data-productrowid',cart_row_id_val);
								var first_variant_out_of_stock = false;
								var variant_in_stock_count = 0;
								var is_variant_product = 0;

								var price_weight = "";
								var out_of_stock = "<div class='product-stock-message'></div>";
								var out_of_stock_class = "";
							/*if (response.data.products[a].is_out_of_stock == 0) {
								out_of_stock = "<div class='product-stock-message'>Sold out</div>";
								out_of_stock_class = "out_of_stock";
							}*/
							if (response.data.products[a].product_size.length > 1 && response.data.products[a].product_size[0] != "") {
								var price_weight = '<form name="form" id="form"><select id="product_varient_'+ response.data.products[a].product_id+'" data-productid="'+ response.data.products[a].product_id+'" name="variants" class="variants product_varient ' + out_of_stock_class + '">';
								for (let j = 0; j < response.data.products[a].product_size.length; j++) {
									is_variant_product = 1;
									var first_variant_out_of_stock = false;
									var out_of_stock_variant = '';
									if (response.data.products[a].product_size[j].is_out_of_stock == 0) {
										var out_of_stock_variant = ' out_of_stock_variant';
										if (response.data.products[a].product_size[j].is_out_of_stock == 0) {
											first_variant_out_of_stock = true;
										}
									}
									
									var selected = "";
									if(varient_id_val == response.data.products[a].product_size[j].variant_id){
										selected = "selected";
									}

									if (!first_variant_out_of_stock) {
										price_weight = price_weight.concat('<option '+selected+' class="' + out_of_stock_variant + '" value="' + response.data.products[a].product_size[j].size + '-' + response.data.products[a].product_size[j].price + '-' + response.data.products[a].product_size[j].variant_id + '">' + response.data.products[a].product_size[j].size + 'lb - $' + response.data.products[a].product_size[j].price + '</option>');
										variant_in_stock_count = variant_in_stock_count + 1;
									}
								}
								price_weight += '</select></form>';
							} else {
								var price_weight = '<span>' + response.data.products[a]
									.product_weight_gms + 'lb</span> - <strong>$' + response.data.products[a]
									.sale_price + '</strong>';
							}

							var out_of_stock = "<div class='product-stock-message'></div>";
							var out_of_stock_class = "";
							console.log(response.data.products[a].product_name.substring(0, 32) + "====product sold out:" + response.data.products[a].is_out_of_stock + " and variant sold out:" + variant_in_stock_count + "is_variant_product" + is_variant_product);
							/* PRODUCT IS IN STOCK AND ALL VARIANT SOLD OUT  */
							if (response.data.products[a].is_out_of_stock == 0 && variant_in_stock_count == 0) {
								var price_weight = "";
								out_of_stock = "<div class='product-stock-message'>Product Sold out</div>";
								out_of_stock_class = "out_of_stock";
							}
							/* PRODUCT IS SOLD OUT */
							if (response.data.products[a].is_out_of_stock == 0) {
								out_of_stock = "<div class='product-stock-message'>Product Sold out</div>";
								out_of_stock_class = "out_of_stock";
								var price_weight = "";
							}
							/* PRODUCT IS VARIANT AND ALL VARIANT SOLD OUT */
							if (is_variant_product == 1 && variant_in_stock_count == 0) {
								var price_weight = "";
								out_of_stock = "<div class='product-stock-message'>Product Sold out</div>";
								out_of_stock_class = "out_of_stock";
							}
							//product = product.concat('<div class="product-box"><img src='+response.data[a]["image"]+'><h4>'+response.data[a]["product_name"]+'</h4><strong>'+response.data[a]["product_price"]+'</strong> <span>'+response.data[a]["product_weight_gms"]+'</span><ul><li><img src=<?php echo ASSET_URL . "images/plus-minus.png"; ?>></li><li><button>Add</button></li></ul></div>');

							product = product.concat('<a href="<?php echo BASE_URL; ?>product/' + response.data.products[a].product_slug + '"><div class="product-box"><img src=' + response.data.products[a].product_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" ><div><h4>' + response.data.products[a].product_name.substring(0, 32) + '...</h4></a>' + price_weight + out_of_stock + '<ul><li><div id="qty_'+ response.data.products[a].product_id+'" class="quantity ' + out_of_stock_class + ' '+ qty_class + '"><button type="button" id="sub" class="sub qty_change_sub" data-isperisible="' + response.data.products[a].is_perisible_products + '" data-productslug="' + response.data.products[a].product_slug + '" data-productimage= "' + response.data.products[a].product_image + '" data-productname="' + response.data.products[a].product_name + '" data-price=' + response.data.products[a].sale_price + ' data-productid = ' + response.data.products[a].product_id + ' data-productweight =' + response.data.products[a].product_weight_gms + ' data-producttax=' + response.data.products[a].product_tax + ' data-productrowid='+cart_row_id_val+'>-</button><input type="text" class= "prod_add1" id="' + response.data.products[a].product_id + '" value="'+cart_qty_val+'" min="1" max="3" disabled /><button type="button" id="add" class="add qty_change_add" data-isperisible="' + response.data.products[a].is_perisible_products + '" data-productslug="' + response.data.products[a].product_slug + '" data-productimage= "' + response.data.products[a].product_image + '" data-productname="' + response.data.products[a].product_name + '" data-price=' + response.data.products[a].sale_price + ' data-productid = ' + response.data.products[a].product_id + ' data-productweight =' + response.data.products[a].product_weight_gms + ' data-producttax=' + response.data.products[a].product_tax + 'data-productrowid='+cart_row_id_val+'>+</button></div></li><li><button id= "btn_' + response.data.products[a].product_id + '" class="add_cart ' + out_of_stock_class + ' '+ add_class+'" data-isperisible="' + response.data.products[a].is_perisible_products + '" data-productslug="' + response.data.products[a].product_slug + '" data-productimage= "' + response.data.products[a].product_image + '" data-productname="' + response.data.products[a].product_name + '" data-price=' + response.data.products[a].sale_price + ' data-productid = ' + response.data.products[a].product_id + ' data-productweight =' + response.data.products[a].product_weight_gms + ' data-producttax=' + response.data.products[a].product_tax + 'data-productrowid='+cart_row_id_val+'>Add</button></li></ul></div></div>');

							var out_of_stock_class_varaiant = '';

						}
							get_filters(api_url_prefix, response.data.product_id);
							$("#category_product_title").html(product_title);
							$("#category_product").html(product);
						} else {
							$("#category_product").html('<div class="product-box">No Products Found</div>');
							$("#category-filter").hide();
						}

					}
				},
				"error": function(response) {
					$("#category_product").html(response.errors);
				}

			});
		}


	}

	function apply_filter() {

		var category_ids = $("#categories input:checkbox:checked").map(function() {
			return $(this).val();
		}).get();
		var brand_ids = $("#brands input:checkbox:checked").map(function() {
			return $(this).val();
		}).get();
		var tag_ids = $("#tags input:checkbox:checked").map(function() {
			return $(this).val();
		}).get();
		var min_val = $("#min_price").val();
		var max_val = $("#max_price").val();
		var search_keyword = getParameterByName('search');
		var json_request = {

			"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"category_id": category_ids,
			"brand_id": brand_ids,
			"tag_id": tag_ids,
			"min_price": min_val,
			"max_price": max_val,
			"search_keyword": search_keyword

		};
		$.ajax({
			"type": "POST",
			"url": api_url_prefix + 'get-category-product-detail-id',
			"data": JSON.stringify(json_request),
			"dataType": "JSON",
			"success": function(response) {
				console.log(response);
				if (response.data != null) {
					var product = "";
					if (login_user_id == 0) {
						cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
						cart_arr = JSON.parse(cart_arr);
					}
					
					var cart_qty_val = 1;
					if (response.data.product_details.length > 0) {

						for (let a = 0; a < response.data.product_details.length; a++) {
							var findDay = response.data.product_details[a].product_id; //find price for day 1
							var cart_qty = $.map(cart_arr, function(value, key) {
								if (value.id == findDay)
								{
									return value.qty;
								}
							});
							//find product row id
							var cart_row_id = $.map(cart_arr, function(value, key) {
								if (value.id == findDay)
								{
									return (value.options && value.options.db_rowid) ? value.options.db_rowid : value.rowid;
								}
							});
							var varient_id = $.map(cart_arr, function(value, key) {
								if (value.id == findDay)
								{
									return value.options.variant_id;
								}
							});
	
							if(cart_row_id.length > 0){
								var cart_row_id_val = cart_row_id[0];
							}else{
								var cart_row_id_val = '';
							}
	
							if(cart_qty.length > 0){
								if(cart_qty.length == 2){
									var cart_qty_val = cart_qty[1];
								}else{
									var cart_qty_val = cart_qty[0];
								}
								
							}else{
								var cart_qty_val = 1;
							}
							if(cart_qty.length > 0){
								var qty_class="";
								var add_class="d-none";
							}else{
								var qty_class="d-none";
								var add_class="";
							}
							if(varient_id.length > 0){
								var varient_id_val = varient_id[0];
							}else{
								var varient_id_val = 0;
							}
							var out_of_stock_class = "";
							var first_variant_out_of_stock = false;
							var variant_in_stock_count = 0;
							var is_variant_product = 0;
							var out_of_stock_variant = '';
							if (response.data.product_details[a].product_size.length > 1 && response.data.product_details[a].product_size[0] != "") {
								var price_weight = '<form name="form" id="form"><select id="product_varient_'+ response.data.product_details[a].product_id+'" data-productid="'+ response.data.product_details[a].product_id+'" name="variants" class="variants product_varient ' + out_of_stock_class + '">';
								for (let j = 0; j < response.data.product_details[a].product_size.length; j++) {

									var selected = "";
									if(varient_id_val == response.data.product_details[a].product_size[j].product_variant_id){
										selected = "selected";
									}

									is_variant_product = 1;
									var first_variant_out_of_stock = false;
									var out_of_stock_variant = '';
									if (response.data.product_details[a].product_size[j].is_out_of_stock == 0) {
										var out_of_stock_variant = ' out_of_stock_variant';
										if (response.data.product_details[a].product_size[j].is_out_of_stock == 0) {
											first_variant_out_of_stock = true;
										}
									}
									if (!first_variant_out_of_stock) {
										price_weight = price_weight.concat('<option '+selected+' class="' + out_of_stock_variant + '" value="' + response.data.product_details[a].product_size[j].size + '-' + response.data.product_details[a].product_size[j].price + '-' + response.data.product_details[a].product_size[j].variant_id + '">' + response.data.product_details[a].product_size[j].size + 'lb - $' + response.data.product_details[a].product_size[j].price + '</option>');
										variant_in_stock_count = variant_in_stock_count + 1;
									}
								}
								price_weight += '</select></form>';
							} else {
								var price_weight = '<span>' + response.data.product_details[a]
									.product_weight_gms + 'lb</span> - <strong>$' + response.data.product_details[a]
									.product_price + '</strong>';
							}
							product = product.concat('<a href="<?php echo BASE_URL; ?>product/' + response.data.product_details[a].product_slug + '"><div class="product-box"><img src=' + response.data.product_details[a].product_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png"><div><h4>' + response.data.product_details[a].product_name + '</h4></a>' + price_weight + '<ul><li><div id="qty_'+ response.data.product_details[a].product_id+'" class="quantity '+ qty_class + '"><button type="button" id="sub" class="sub qty_change_sub" data-isperisible="' + response.data.product_details[a].is_perisible_products + '" data-productslug="' + response.data.product_details[a].product_slug + '" data-productimage= "' + response.data.product_details[a].product_image + '" data-productname="' + response.data.product_details[a].product_name + '" data-price=' + response.data.product_details[a].product_price + ' data-productid = ' + response.data.product_details[a].product_id + ' data-productrowid='+cart_row_id_val+'>-</button><input type="text" id="' + response.data.product_details[a].product_id + '" value="'+cart_qty_val+'" min="1" max="3" disabled /><button type="button" id="add" class="add qty_change_add" data-isperisible="' + response.data.product_details[a].is_perisible_products + '" data-productslug="' + response.data.product_details[a].product_slug + '" data-productimage= "' + response.data.product_details[a].product_image + '" data-productname="' + response.data.product_details[a].product_name + '" data-price=' + response.data.product_details[a].product_price + ' data-productid = ' + response.data.product_details[a].product_id + ' data-productrowid='+cart_row_id_val+'>+</button></div></li><li><button id= "btn_' + response.data.product_details[a].product_id + '" class="add_cart '+ add_class+'" data-isperisible="' + response.data.product_details[a].is_perisible_products + '" data-productslug="' + response.data.product_details[a].product_slug + '" data-productimage= "' + response.data.product_details[a].product_image + '" data-productname="' + response.data.product_details[a].product_name + '" data-price=' + response.data.product_details[a].product_price + ' data-productid = ' + response.data.product_details[a].product_id + ' data-productrowid='+cart_row_id_val+'>Add</button></li></ul></div></div>');

						}
					} else {
						product = product.concat('<div class="product-box">No Product Found</div>');
					}
					showProgress('div#spinner');
					$("#category_product").html(product);
					hideProgress('div#spinner');

				} else {
					product = '<div class="product-box">No Product Found</div>';
					showProgress('div#spinner');
					$("#category_product").html(product);
					// $("#category-filter").hide();
					hideProgress('div#spinner');
				}
			},
			"error": function(response) {
				$("#category_product").html(response.errors);
				// $("#category-filter").hide();
			}

		});

	}
</script>
<?php require_once('common/footer.php'); ?>