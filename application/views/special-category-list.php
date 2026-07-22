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

		
        /* $(document).on('click', '.add_cart', function() {
            var section_id = $(this).data('section');
            var product_id = $(this).data('productid');
            var product_name = $(this).data('productname');
            var image = $(this).data('productimage');
            var product_slug = $(this).data('productslug');
            var is_perisible = $(this).data('isperisible');
            var product_tax = $(this).data('producttax');
            var isvariable = $(this).data('isvariable');
            

            //	alert('product_tax'+product_tax);
            var quantity = $('#'+ product_id).val(); 
            console.log(quantity);
            if (isvariable==1) {
                var variant_id = $(this).data('variant_id');
                var price = $(this).data('price');
                var weight = $(this).data('productweight');
            } else {
                var price = $(this).data('price');
                var weight = $(this).data('productweight');
            }
            //$('#qty_'+product_id).find('.add').prev().val(1);
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
                var total_qty = parseInt($("#cartCount").html()) + parseInt(1);
                $("#cartCount").html(total_qty);
                $.ajax({
                    "type": "POST",
                    "url": front_url + 'cart/add',
                    "data": JSON.stringify(json_request),
                    "dataType": "JSON",
                    "success": function(response) {
                        $("#btn_" + section_id + "_" + product_id).text('Added');
                        setTimeout(() => {
                            $("#btn_" + section_id + "_" + product_id).text('Add');
                        }, 3000);
                    },
                    "error": function(response) {
                        console.log(response.errors);
                    }
                });
                //$(this).hide();
                if (isvariable==1) {
                    $('#btn_section4_'+product_id).addClass('d-none');
                    $('#qty_'+product_id).removeClass('d-none');
                }
                else{
                $('#btn_section4_'+product_id).addClass('d-none');
                $('#qty_'+product_id).removeClass('d-none');
                }

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
            var quantity = $('#'+ product_id).val();
            console.log(quantity);
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
                "product_tax": product_tax,
                "is_perisible": is_perisible
            };

            


            if (quantity != "" || quantity > 0) {
                var total_qty = parseInt($("#cartCount").html()) - 1;
                    $("#cartCount").html(total_qty);
                if(quantity > 0){ 
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
                }else{
                    
                    var cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
                    cart_arr = JSON.parse(cart_arr);
                    var findDay =product_id; //find price for day 1
                    //find product row id
                    var cart_row_id = $.map(cart_arr, function(value, key) {
                        if (value.id == findDay)
                        {
                            return (value.options && value.options.db_rowid) ? value.options.db_rowid : value.rowid;
                        }
                    });

                    if(cart_row_id.length > 0){
                        cart_row_id_val = cart_row_id[0];
                    }else{
                        cart_row_id_val = '';
                    }

                    
                    $(this).attr('data-productrowid',cart_row_id_val);
                    var product_rowid = $(this).data('productrowid');
                    $.ajax({
                        "type": "POST",
                        "url": front_url + 'item-remove',
                        "data": {row_id:product_rowid},
                        "dataType": "JSON",
                        "success": function(response) {
                            location.reload();
                        },
                        "error": function(response) {
                            console.log(response.errors);
                        }
                    });
                    //location.reload();
                    console.log('fff'+product_id);
                    $('#qty_'+product_id).addClass('d-none');
                    $('#btn_section4_'+product_id).removeClass('d-none');
                    
                }
            
                
            } else {
                alert("Please Enter quantity");
            }
        }); */

        $(document).on('click', '.add_cart', function() {
        
            var section_id = $(this).data('section');
            var product_id = $(this).data('productid');
            var product_name = $(this).data('productname');
            var image = $(this).data('productimage');
            var product_slug = $(this).data('productslug');
            var is_perisible = $(this).data('isperisible');
            var product_tax = $(this).data('producttax');
            var isvariable = $(this).data('isvariable');
            

            //	alert('product_tax'+product_tax);
            var quantity = $('#' + product_id).val();        
            /* if (isvariable==1) {
                var variant_id = $(this).data('variant_id');
                var price = $(this).data('price');
                var weight = $(this).data('productweight');
            } else {
                var price = $(this).data('price');
                var weight = $(this).data('productweight');
            } */
            
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
            //$('#qty_'+product_id).find('.add').prev().val(1);
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
                var total_qty = parseInt($("#cartCount").html()) + parseInt(1);
                $("#cartCount").html(total_qty);
                $.ajax({
                    "type": "POST",
                    "url": front_url + 'cart/add',
                    "data": JSON.stringify(json_request),
                    "dataType": "JSON",
                    "success": function(response) {
                        $('#qty_'+product_id).find('.qty_change_sub').attr('data-productrowid',response.data);
                        //$("#btn_" + section_id + "_" + product_id).text('Added');
                        setTimeout(() => {
                            //$("#btn_" + section_id + "_" + product_id).text('Add');
                        }, 3000);
                    },
                    "error": function(response) {
                        console.log(response.errors);
                    }
                });
                //$(this).hide();
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
            //var parent = $(this).parent().parent().parent().find("select.variants");
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
            console.log(json_request, "ADD");

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
            //var parent = $(this).parent().parent().parent().find("select.variants");
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
            console.log(json_request, 'minus');
            


            if (quantity != "" || quantity > 0) {
                var total_qty = parseInt($("#cartCount").html()) - 1;
                    $("#cartCount").html(total_qty);
                if(quantity > 0){
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
                }else{
                    
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
                    //location.reload();
                
                    $('#qty_'+product_id).addClass('d-none');
                    $('#btn_'+product_id).removeClass('d-none');
                    // 🔥 IMPORTANT: reset input to 1
                    $('#' + product_id).val(1);
                    
                }
            
                
            } else {
                alert("Please Enter quantity");
            }
        });
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
    
    $(document).on('change', '.product_varient', function() {
        
        var parent = $(this).val();
        var product_id = $(this).data('productid');
        
        if (parent && parent != "") { 
            
            var variant_detail = parent.split('-');
            var weight = variant_detail[0];
            var price = variant_detail[1];
            var variant_id = variant_detail[2];
    
            get_cart_arr().then(function(cart_arr) {
                console.log(cart_arr, 'cart_arr');
            
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
                    $('#qty_'+product_id+' input[type="text"]').val('1');
                    
                }
            });
        }
    });
</script>
<script>
	function get_brands(api_url_prefix) {
		
		var special_category_slug='<?php echo $special_category_slug; ?>';
		var special_category_title='';
		
		
		var json_request = {
			"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
			"is_active_only": "1",
			"special_category_slug": special_category_slug,
			"sort_order": "desc"
		};
		var brands = "";
		var product = "";
		if (login_user_id == 0) {
            cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
            cart_arr = JSON.parse(cart_arr);
        }
		var cart_qty_val = 1;
		$.ajax({
			"type": "POST",
			"url": api_url_prefix + 'get_special_category_product',
			"data": JSON.stringify(json_request),
			"dataType": "JSON",
			"success": function (response) {
				if (response.data != null) {
					// console.log(response.data.product_detail);
					if(response.data.slider_detail != ""){
						special_category_title=response.data.slider_detail[0].title;
					}
					brand_title = '<h1 class="category_t_title">'+special_category_title+'</h1>';
					if (response.data.product_detail != "") {
						var first_variant_out_of_stock = false;
						for (let a = 0; a < response.data.product_detail.length; a++) {
							var findDay = response.data.product_detail[a].product_id; //find price for day 1
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
							let product_price=response.data.product_detail[a].product_price;
							let sale_price=response.data.product_detail[a].sale_price;

							let price_html=`<strong>$${product_price}</strong>`;
							if(product_price != sale_price){
								price_html=`<del>$${product_price}</del><strong>$${sale_price}</strong>`;
							}
							var price_weight = '<div class="begin_details"> '+price_html+'</div>';
							var out_of_stock = "<div class='product-stock-message'></div>";
							var out_of_stock_class = "";
							
							if(response.data.product_detail[a].is_out_of_stock == 0)
							{
								out_of_stock_class = "out_of_stock";
								price_weight = ''
								var buttonSection = "<div class='product-stock-message'>Product is sold out</div>";
							}
							else
							{
                                var obj = response.data.product_detail[a].product_size;
                                    objectLength = Object.keys(obj).length;
								if (objectLength > 1) {

                                    var price_weight = '<form name="form" id="form"><select class="product_varient" id="product_varient_'+ response.data.product_detail[a].product_id+'" data-productid="'+ response.data.product_detail[a].product_id+'" name="variants" id="variants'+ response.data.product_detail[a].product_id+'" class="variants">';
                                    for (let j = 0; j < objectLength; j++) {
                                        console.log('eee');
                                        console.log(response.data.product_detail[a]);
                                        var selected = "";
                                        if(varient_id_val == response.data.product_detail[a].product_size[j].product_variant_id){
                                            selected = "selected";
                                        }
                                        price_weight = price_weight.concat('<option '+selected+' value="' + response.data.product_detail[a].product_size[j].size + '-' + response.data.product_detail[a].product_size[j].price + '-' + response.data.product_detail[a].product_size[j].product_variant_id + '">' + response.data.product_detail[a].product_size[j].size + 'lb - $' + response.data.product_detail[a].product_size[j].price + '</option>');
                                    }
                                    price_weight += '</select></form>';
                                    } else {
                                    // var price_weight = '<span>' + response.data.product_detail[a].product_weight_gms + 'lb</span> - <strong>$' + response.data.product_detail[a].product_price + '</strong>';
                                    }
								var buttonSection = ' <ul><li><div id="qty_'+ response.data.product_detail[a].product_id+'" class="quantity ' + out_of_stock_class +' '+ qty_class + '"><button type="button" id="sub" class="sub qty_change_sub" data-productslug="' + response.data.product_detail[a].product_slug + '" data-productimage= "' + response.data.product_detail[a].product_image + '" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" data-section="section4" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" data-productname="' + response.data.product_detail[a].product_name + '" data-price=' + response.data.product_detail[a].sale_price + ' data-productid = ' + response.data.product_detail[a].product_id + ' data-productweight =' + response.data.product_detail[a].product_weight_gms +  ' data-producttax=' + response.data.product_detail[a].product_tax + ' data-productrowid='+cart_row_id_val+'>-</button><input type="text" id="' + response.data.product_detail[a].product_id + '" value="'+cart_qty_val+'" min="1" max="3" disabled /><button type="button" id="add" class="add qty_change_add" data-productslug="' + response.data.product_detail[a].product_slug + '" data-productimage= "' + response.data.product_detail[a].product_image + '" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" data-section="section4" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" data-productname="' + response.data.product_detail[a].product_name + '" data-price=' + response.data.product_detail[a].sale_price + ' data-productid = ' + response.data.product_detail[a].product_id + ' data-productweight =' + response.data.product_detail[a].product_weight_gms +  ' data-producttax=' + response.data.product_detail[a].product_tax + ' data-productrowid=' + cart_row_id_val + '>+</button></div></li><li><button id= "btn_' + response.data.product_detail[a].product_id + '"data-productslug="' + response.data.product_detail[a].product_slug + '" data-productimage= "' + response.data.product_detail[a].product_image + '" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" class="add_cart ' + out_of_stock_class + ' '+ add_class+'" data-section="section4" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" data-productname="' + response.data.product_detail[a].product_name + '" data-price=' + response.data.product_detail[a].sale_price + ' data-productid = ' + response.data.product_detail[a].product_id + ' data-productweight =' + response.data.product_detail[a].product_weight_gms +  ' data-producttax=' + response.data.product_detail[a].product_tax + ' data-productrowid='+cart_row_id_val+'>Add</button></li></ul>';
								
							}

                            var tagdiscounttext = '';
                            if (response.data.product_detail[a].tag_discount != '') {
                                tagdiscounttext = '<span>'+response.data.product_detail[a].tag_discount+'</span>';
                            }else{
                                tagdiscounttext = '';
                            }
                            if (objectLength > 1) {
                                tagdiscounttext = '';
                            }
							product = product.concat('<div class="product-box"><a href="<?php echo BASE_URL; ?>product/' + response.data.product_detail[a].product_slug +
								'"> <div class="begin_img_container">'+tagdiscounttext+'<img src=' + response.data.product_detail[a].image + ' onerror=this.src="<?php echo BASE_URL; ?>assets/images/logo-2.png"></div><h4>' + response.data.product_detail[a].product_name +
								'</h4></a>' + price_weight +buttonSection+'</div>'
							);

							
						}
						$("#category_product_title").html(brand_title);
						$("#category_product").html(product);
					}

				} else {
					brands = '<div class="product-box">No Product Found</div>';
					
				}
			},
			"error": function (response) {
				$("#category_product").html(response.errors);
			}

		});
	}



    
</script>
<?php require_once('common/footer.php'); ?>