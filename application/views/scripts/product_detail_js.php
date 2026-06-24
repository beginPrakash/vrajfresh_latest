 

<script>

$(document).ready(function () {

    showProgress('div#spinner');
    get_product_detail(api_url_prefix);
    hideProgress('div#spinner');

    $(document).on('click', '.add_cart', function () {
        
        var product_id = $(this).data('productid');
        console.log("product_id:" + product_id);
        // var product_name = $(this).data('productname');
        var product_name = $(this).data('productname').trim();
        var image = $(this).data('productimage');
        var quantity = $('#' + product_id).val();
        var product_slug = $(this).data('productslug');
        var is_perisible = $(this).data('isperisible');
        var product_tax = $(this).data('producttax');
        
        //jQuery("#" + tab_id).addClass('price-current');
        var tab_id = $(".price-current").attr('data-tab');
        console.log("tab_id:" + tab_id);
        var parent = $("li.price-current").attr('data-weight_string');
        if (parent && parent != "") {
            
            $("#price-filter li").removeClass("select");
            var selected = $(this).closest('li').addClass('select');
            var tab_id = $("li.price-current").attr('data-tab');
            
            var variant_price = $(".variant"+tab_id).attr('data-price');
            var weight_current = $(".variant"+tab_id).attr('data-weight_string');

            console.log("variant_price:" + variant_price);

            if (tab_id == undefined || tab_id == null || tab_id == '' && variant_price == undefined || variant_price == null || variant_price == '') {
                var selectedLi = $("li[value='" + weight_current + "']");
                var tab_id = selectedLi.data("tab");
                var variant_price = selectedLi.data("price");
            }

            var weight = weight_current;
            var price = variant_price;
            var variant_id = tab_id;
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
            var total_qty = parseInt($("#cartCount").html()) + parseInt(quantity);
            
            $("#cartCount").html(total_qty);

            $.ajax({
                "type": "POST",
                "url": front_url + 'cart/add',
                "data": JSON.stringify(json_request),
                "dataType": "JSON",
                "success": function (response) {
                    $('#qty_'+product_id).find('.qty_change_sub').attr('data-productrowid',response.data);
                    $("#btn_" + product_id).text('Added');
                    $("button#view_cart").show();
                    setTimeout(() => {
                        //$(".add").prev().val(1);
                        $("#btn_" + product_id).text('Add');
                    }, 3000);

                    //	alert('Product Added Into Cart');
                    //  $("#cart-details").html(data);
                    // setTimeout(function(){
                    // 	location.reload();
                    // 		},1000);
                },
                "error": function (response) {
                    console.log(response.errors);
                }
            });
            $('#qty_' + product_id + ' input[type="text"]').val('1');
            $('#btn_'+product_id).addClass('d-none');
            $('#qty_'+product_id).removeClass('d-none');

        } else {
            alert("Please Enter quantity");
        }

    });

    $(document).on('click', '.add_detail_cart', function() {
			
			var product_id = $(this).data('productid');
			var product_name = $(this).data('productname');
			var price = $(this).data('price');
			var quantity = $('#' + product_id).val();
			var image = $(this).data('productimage');
			var product_slug = $(this).data('productslug');
			var is_perisible = $(this).data('isperisible');
			var product_tax = $(this).data('producttax');
			// alert(product_tax);

			var parent = $("#product_varient_"+product_id).val();
			
			if (parent && parent!= "") {
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
				"is_perisible": is_perisible,
				"product_tax": product_tax,

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
						setTimeout(() => {
							//$(".add").prev().val(1);
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
				$('#btn_'+product_id).addClass('d-none');
                $('#btn_section4_'+product_id).addClass('d-none');
            	$('#qty_'+product_id).removeClass('d-none');

			} else {
				alert("Please Enter quantity");
			}

	});

    
    $(document).on('click', '.qty_change_add', function () {
        var product_id = $(this).data('productid');
        console.log("product_id:" + product_id);
        // var product_name = $(this).data('productname');
        var product_name = $("div.product-details-name > h2").text().trim();
        var image = $(this).data('productimage');
        var quantity = $('#' + product_id).val();
        var product_slug = $(this).data('productslug');
        var is_perisible = $(this).data('isperisible');
        var product_tax = $(this).data('producttax');
        
        //jQuery("#" + tab_id).addClass('price-current');
        var tab_id = $(".price-current").attr('data-tab');
        console.log("tab_id:" + tab_id);
        var parent = $("li.price-current").attr('data-weight_string');
        if (parent && parent != "") {
            
            $("#price-filter li").removeClass("select");
            var selected = $(this).closest('li').addClass('select');
            
            var tab_id = $("li.price-current").attr('data-tab');
            
            var variant_price = $(".variant"+tab_id).attr('data-price');
            var weight_current = $(".variant"+tab_id).attr('data-weight_string');

            console.log("variant_price:" + variant_price);

            if (tab_id == undefined || tab_id == null || tab_id == '' && variant_price == undefined || variant_price == null || variant_price == '') {
                var selectedLi = $("li[value='" + weight_current + "']");
                var tab_id = selectedLi.data("tab");
                var variant_price = selectedLi.data("price");
            }

            var weight = weight_current;
            var price = variant_price;
            var variant_id = tab_id;
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
                "success": function (response) {

                    //$("#btn_" + product_id).text('Added');
                    $("button#view_cart").show();
                    // setTimeout(() => {
                    //     //$(".add").prev().val(1);
                    //     $("#btn_" + product_id).text('Add');
                    // }, 3000);

                    //	alert('Product Added Into Cart');
                    //  $("#cart-details").html(data);
                    // setTimeout(function(){
                    // 	location.reload();
                    // 		},1000);
                },
                "error": function (response) {
                    console.log(response.errors);
                }
            });
            $('#qty_'+product_id).removeClass('d-none');

        } else {
            alert("Please Enter quantity");
        }

    });

    $(document).on('click', '.qty_change_sub', function () {
        var product_id = $(this).data('productid');
        console.log("product_id:" + product_id);
        // var product_name = $(this).data('productname');
        var product_name = $("div.product-details-name > h2").text().trim();
        var image = $(this).data('productimage');
        var quantity = $('#' + product_id).val();
        var product_slug = $(this).data('productslug');
        var is_perisible = $(this).data('isperisible');
        var product_tax = $(this).data('producttax');
        
        //jQuery("#" + tab_id).addClass('price-current');
        var tab_id = $(".price-current").attr('data-tab');
        console.log("tab_id:" + tab_id);
        var parent = $("li.price-current").attr('data-weight_string');
        if (parent && parent != "") {
            
            $("#price-filter li").removeClass("select");
            var selected = $(this).closest('li').addClass('select');
            
            var tab_id = $("li.price-current").attr('data-tab');

            
            var variant_price = $(".variant"+tab_id).attr('data-price');
            var weight_current = $(".variant"+tab_id).attr('data-weight_string');

            console.log("variant_price:" + variant_price);

            if (tab_id == undefined || tab_id == null || tab_id == '' && variant_price == undefined || variant_price == null || variant_price == '') {
                var selectedLi = $("li[value='" + weight_current + "']");
                var tab_id = selectedLi.data("tab");
                var variant_price = selectedLi.data("price");
            }

            var weight = weight_current;
            var price = variant_price;
            var variant_id = tab_id;
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
                        "success": function (response) {

                            $("#btn_" + product_id).text('Added');
                            $("button#view_cart").show();
                            setTimeout(() => {
                                //$(".add").prev().val(1);
                                $("#btn_" + product_id).text('Add');
                            }, 3000);

                            //	alert('Product Added Into Cart');
                            //  $("#cart-details").html(data);
                            // setTimeout(function(){
                            // 	location.reload();
                            // 		},1000);
                        },
                        "error": function (response) {
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
                        if (value.id == findDay && variant_id == value.options.variant_id)
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
                           // location.reload();
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

     $(document).on('click', '.qty_change_add_detail', function() {
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

    $(document).on('click', '.qty_change_sub_detail', function() {
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
                console.log('fff'+product_id);
                $('#qty_'+product_id).addClass('d-none');
                $('#btn_section4_'+product_id).removeClass('d-none');
                // 🔥 IMPORTANT: reset input to 1
                    $('#' + product_id).val(1);
            }
          
            
        } else {
            alert("Please Enter quantity");
        }
    });

});

function enableCartButton(variant_id) {
    
    $(".out_of_stock").removeClass("out_of_stock");
    $(".product-stock-message-detail").hide();
    $(".v_options").removeClass('price-current');
    
    $(".variant" + variant_id).addClass('price-current');
    console.log("variant_id:" + variant_id);
    
    var parent = $(".variant"+variant_id).data('weight_string');
    var price = $(".variant"+variant_id).data('price');
    var imageURL = $(".variant"+variant_id).data('url');

    var product_id = $(".variant"+variant_id).data('productid');
    
    var weight = "";
    if (parent && parent != "") {
        let parentStr = String(parent);

        if (parentStr.includes("-")) {
            var variant_detail = parentStr.split('-');
            var weight = variant_detail[0];
        }else{
            var weight = parentStr;
        }
    }   
    $(".qty_change_sub_id"+product_id).attr('data-price', price);
    $(".qty_change_add_id"+product_id).attr('data-price', price);
    $("#btn_"+product_id).attr('data-price', price);
    
    $(".qty_change_sub_id"+product_id).attr('data-productimage', imageURL);
    $(".qty_change_add_id"+product_id).attr('data-productimage', imageURL);
    $("#btn_"+product_id).attr('data-productimage', imageURL);

    $(".qty_change_sub_id"+product_id).attr('data-productweight', weight);
    $(".qty_change_add_id"+product_id).attr('data-productweight', weight);
    $("#btn_"+product_id).attr('data-productweight', weight);
    
    if (login_user_id == 0) {
        cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
        cart_arr = JSON.parse(cart_arr);
    }

        
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
    if(varient_id.length > 0){
        var varient_id_val = varient_id[0];
    }else{
        var varient_id_val = 0;
    }   
    console.log(varient_id_val + "> 0 && " + cart_row_id_val);
    if(varient_id_val > 0 && cart_row_id_val != ""){
        
        $("#btn_"+product_id).addClass("d-none");
        $("#qty_"+product_id).removeClass("d-none");
        $('#qty_'+product_id+' input[type="text"]').val(cart_qty_val);
    } else {
        $("#btn_"+product_id).removeClass("d-none");
        $("#qty_"+product_id).addClass("d-none");        
    }
}

function get_product_detail(api_url_prefix) {
    if (login_user_id == 0) {
        cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
        cart_arr = JSON.parse(cart_arr);
    }
    var cart_qty_val = 1;
    var url = $("#product_slug").val();
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "product_slug": url
    };
    var product = "";
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-product-by-slug',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function (response) {
            // showProgress('div#spinner');
            if (response.data != null) {
                //console.log(response.data);
                //console.log(cart_qty);
                if (response.data.variants.length > 1) {
                    var price = [];
                    var price_weight1 = '<div class="product-price"><div class="price-filter-content price-current"></div> <ul class="price-filter" id="price-filter">';
                    var first_variant_out_of_stock = false;
                    var is_first_variant = '';
                    var is_first_variant_flag = true;
                    var is_first_variant_added_in_cart = false;
                    var is_first_variant_qnt = 0;
                    
                    for (let j = 0; j < response.data.variants.length; j++) {

                        if(j == 0){
                            var variant_id = response.data.variants[j].id;
                            var product_id = response.data.product_id; //find price for day 1
                        
                            console.log(cart_arr, 'cart_arr'+variant_id);
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
                                
                                is_first_variant_added_in_cart = true;
                                is_first_variant_qnt = cart_qty_val;
                            }
                        }


                        

                        console.log("variant_price"+is_first_variant_added_in_cart);
                        price.push(response.data.variants[j].variant_price);

                        console.log(j + "is_first_variant:" + is_first_variant);
                        if (response.data.variants[j].is_out_of_stock > 0 && is_first_variant_flag) {
                            is_first_variant = 'price-current ';
                            is_first_variant_flag = false;
                        }
                        else {
                            is_first_variant = ' ';
                        }
                        console.log("is_first_variant:" + is_first_variant);
                        
                        var out_of_stock_variant = 'v_options variant' + response.data.variants[j].id;
                        if (response.data.variants[j].is_out_of_stock == 0) {
                            out_of_stock_variant = ' v_options out_of_stock_variant variant' + response.data.variants[j].id;
                            if (j == 0) {
                                first_variant_out_of_stock = true;
                            }
                        }
                        /* IF PRODUCR IS NOT sold out */
                        if (response.data.is_out_of_stock == 1)
                        {
                            price_weight1 = price_weight1.concat('<li onClick="enableCartButton(' + response.data.variants[j].id + ');" class="' + is_first_variant + out_of_stock_variant + '" data-productid="'+response.data.product_id+'" data-tab="' + response.data.variants[j].id + '" data-price="' + response.data.variants[j].variant_price + '"  data-weight_string="'+response.data.variants[j].product_variant_size+'" value = "' + response.data.variants[j].product_variant_size + '" data-url="' + response.data.variants[j].variant_image + '">' + response.data.variants[j].product_variant_size + 'LB' + '</li>');
                        }

                        var simple_price_weight = '';
                        var max_price = Math.max.apply(Math, price); // 3
                        var min_price = Math.min.apply(Math, price); // 1
                        var price_weight = '<span id="variant-price">$' + min_price + '- $' + max_price + '</span>';
                        //var price_weight = '<span id="variant-price">$' + min_price + '</span>';
                    }
                    price_weight1 += '</ul></div>';
                } else {
                    var simple_price_weight = '';
                    if(response.data.product_weight_gms != 0){
                        simple_price_weight = +response.data.product_weight_gms;
                    }
                   
                    var tagdiscounttext = '';
                    if (response.data.tag_discount != '') {
                        tagdiscounttext = '<p class="detailtag_img_container">'+response.data.tag_discount+'</p>';
                    }else{
                        tagdiscounttext = '';
                    }

                    if(response.data.product_price != response.data.sale_price){
                        var price_weight = '<del>$' + response.data.product_price + '</del>'+tagdiscounttext+'<span>$' + response.data.sale_price + '</span>';
                    } else {
                        var price_weight = '<span>$' + response.data.sale_price + '</span>';
                    }
                    

                    var price_weight1 = '';
                }
                var product_large_image = '';
                var product_large_image_thumb = '';
                if (response.data.images.length > 0) {
                    for (let a = 0; a < response.data.images.length; a++) {
                        //product_image = "<div><img src='" + response.data.images[a].image + "'></div>";  
                        var_product_image = response.data.images[0].image;
                        product_large_image = product_large_image + '<div class ="zoom ex1"><img src="' + response.data.images[a].image + '" onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" style="width:350px"></div>';
                        product_large_image_thumb = product_large_image_thumb + '<div><img src="' + response.data.images[a].image + '" onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" style="width:150px;height:150px;"></div>';
                    }


                } else {
                    //product_image = "<div><img src='" + response.data.product_images + "'></div>";
                    var_product_image = response.data.product_images;
                    product_large_image = product_large_image + '<div class ="zoom ex1"><img src="' + response.data.product_images + '" onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" style="width:150px;height:150px;"></div>';
                    product_large_image_thumb = product_large_image_thumb + '<div><img src="' + response.data.product_images + '" style="width:150px;height:150px;"></div>';
                }
                var data = '<div class="main"><div class="slider slider-for">' + product_large_image + '</div><div class="slider slider-nav">' + product_large_image_thumb + '</div></div>';
                var data = '<div class=""><div class="slider slider-for">' + product_large_image + '</div><div class="slider slider-nav">' + product_large_image_thumb + '</div></div>';
                $(".product-details-left").html(data);
                 
                var product_id = response.data.product_id;
                console.log("variant_length"+is_first_variant_added_in_cart);
                if (response.data.variants.length > 1){ 
                    if(is_first_variant_added_in_cart == true){
                        $("#btn_"+product_id).addClass("d-none");
                        $("#qty_"+product_id).removeClass("d-none");
                        $('#qty_'+product_id+' input[type="text"]').val(is_first_variant_qnt);
                        var qty_class="";
                        var add_class="d-none";
                    } else {
                        console.log("variant_length nae"+is_first_variant_added_in_cart);
                        $("#btn_"+product_id).removeClass("d-none");
                        $("#qty_"+product_id).addClass("d-none");
                        var qty_class="d-none";
                        var add_class="";   
                    }
                } else {
                    var cart_qty = $.map(cart_arr, function(value, key) {
                        if (value.id == product_id)
                        {
                            return value.qty;
                        }
                    });
                    var cart_row_id = $.map(cart_arr, function(value, key) {
                        if (value.id == product_id)
                        {
                            return (value.options && value.options.db_rowid) ? value.options.db_rowid : value.rowid;
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
                    if(cart_qty.length > 0 && cart_row_id_val != ""){

                        $("#btn_"+product_id).addClass("d-none");
                        $("#qty_"+product_id).removeClass("d-none");
                        $('#qty_'+product_id+' input[type="text"]').val(cart_qty_val);
                        var qty_class="";
                        var add_class="d-none";
                    } else {
                        $("#btn_"+product_id).removeClass("d-none");
                        $("#qty_"+product_id).addClass("d-none");
                        var qty_class="d-none";
                        var add_class="";   
                    }
                }
                
                

                /* PRODUCT IMAGE SLIDER START */
                $('.slider-for').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: '.slider-nav'
                });
                $('.slider-nav').slick({
                    slidesToShow: 5,
                    slidesToScroll: 1,
                    asNavFor: '.slider-for',
                    dots: true,
                    focusOnSelect: true
                });
                $('a[data-slide]').click(function (e) {
                    e.preventDefault();
                    var slideno = $(this).data('slide');
                    $('.slider-nav').slick('slickGoTo', slideno - 1);
                }); /* PRODUCT IMAGE SLIDER END */
                var categories = "";
                if (response.data.categories != null && response.data.categories.length > 0) {
                    categories = "<li>Categories :";
                    for (let a = 0; a < response.data.categories.length; a++) {
                        categories = categories.concat("<a href='<?php echo BASE_URL; ?>category/" + response.data.categories[a].category_slug + "'>" + response.data.categories[a].category_name + "</a>, ");

                    }
                    categories = categories.slice(0, -2);
                    categories = categories + "</li>";

                }

                var tags = "";
                if (response.data.tags != null && response.data.tags.length > 0) {
                    tags = "<li id='tags'>tags :";
                    for (let a = 0; a < response.data.tags.length; a++) {
                        // tags = tags.concat("<a href='<?php echo BASE_URL; ?>tag/" + response.data.tags[a].tag_id + "'>" + response.data.tags[a].tag + "</a>, ");
                        tags = tags.concat("<a href='#'>" + response.data.tags[a].tag + "</a>, ");

                    }
                    tags = tags.slice(0, -2);
                    tags = tags + "</li>";


                } else {
                    $("#tags").hide();
                }

                if (simple_price_weight != "" && simple_price_weight != null) {
                    simple_price_weight = "<li id='weight'>weight : " + simple_price_weight + " LB</li>";
                } else {
                    $("#weight").hide();
                }

                if (simple_price_weight == 0) {
                    $("#weight").hide();
                }


                if (response.data.product_slug == "oil-beans") {
                    $("li#weight").hide();
                    simple_price_weight = "<li id='weight1'>weight : " + response.data.sale_price + " Ounces</li>";
                } else {
                    $("#weight").hide();
                }

                if (response.data.product_sub_name != "" && response.data.product_sub_name != null) {
                    var product_sub_name = '<p>' + response.data.product_sub_name + '</p>';
                } else {
                    var product_sub_name = '';
                }
                //if (response.data.product_description != "" && response.data.product_description != null) {
                    //var product_description = '<p>' + response.data.product_description + '</p>';
               // } else {
                    //var product_description = '';
               // }
                var out_of_stock = "<div class='product-stock-message-detail'></div>";
                var out_of_stock_class = "";
                /* IF PRODUCR IS sold out ELSE FIRST VARIANT sold out ELSE */
                if (response.data.is_out_of_stock == 0)
                {
                    out_of_stock = "<div class='product-stock-message-detail'>Product is sold out</div>";
                    out_of_stock_class = "out_of_stock";
                }
                else if (first_variant_out_of_stock) {
                    out_of_stock = "<div class='product-stock-message-detail'>Product is sold out</div>";
                    out_of_stock_class = "out_of_stock";
                }
                else if (response.data.is_out_of_stock == 0 && response.data.variants.length==0) {
                    out_of_stock = "<div class='product-stock-message-detail'>Product is sold out</div>";
                    out_of_stock_class = "out_of_stock";
                }
                
                product_detail = "<div class='product-details-name'><h2>" + response.data.product_name + "</h2>" + "</div>" + product_sub_name + price_weight + price_weight1 + out_of_stock + "<ul class='btnul'><li><div id='qty_"+ response.data.product_id+"' class='quantity " + out_of_stock_class + " "+ qty_class + "'><button type='button' id='sub' class='sub qty_change_sub qty_change_sub_id"+response.data.product_id+"' data-isperisible='" + response.data.is_perisible_products + "' data-productslug='" + response.data.product_slug + "' data-productimage= '" + var_product_image + "' data-productname='" + response.data.product_name + "' data-price=" + response.data.sale_price + " data-productid = " + response.data.product_id + " data-productweight =" + response.data.product_weight_gms + " data-producttax=" + response.data.product_tax + " data-productrowid="+cart_row_id_val+">-</button><input type='text' id='" + response.data.product_id + "' value='"+cart_qty_val+"' min='1' max='3' disabled /><button type='button' id='add' class='add qty_change_add qty_change_add_id"+response.data.product_id+"' data-isperisible='" + response.data.is_perisible_products + "' data-productslug='" + response.data.product_slug + "' data-productimage= '" + var_product_image + "' data-productname='" + response.data.product_name + "' data-price=" + response.data.sale_price + " data-productid = " + response.data.product_id + " data-productweight =" + response.data.product_weight_gms + " data-producttax=" + response.data.product_tax + " data-productrowid="+cart_row_id_val+">+</button></div></li><li><button id= 'btn_" + response.data.product_id + "' class='add_cart " + out_of_stock_class + " "+add_class+"' data-isperisible='" + response.data.is_perisible_products + "' data-productslug='" + response.data.product_slug + "' data-productimage= '" + var_product_image + "' data-productname='" + response.data.product_name + "' data-price=" + response.data.sale_price + " data-productid = " + response.data.product_id + " data-productweight =" + response.data.product_weight_gms + " data-producttax=" + response.data.product_tax + " data-productrowid="+cart_row_id_val+">Add</button></li></ul><p id='delivery_time'></p><div class='categories-tag'><ul>" + simple_price_weight + categories + tags + "<li id='brand'>Brand : <a href='<?php echo BASE_URL; ?>brand/" + response.data.brand_slug + "'>" + response.data.brand_name + "</a></li></ul></div>";
                $(".product-details-right").html(product_detail);
                //<li></button><button id ='view_cart'><a href=" + front_url + "cart-detail>View Cart</a></button></li>

                get_related_product(api_url_prefix, response.data.category_slug, response.data.product_id);
                get_delivery_message();
            }
        },
        "error": function (response) {
            $(".product-details-right").html(response.errors);
        }
    });
}

function get_delivery_message() {
    var delivery_type = Cookies.get("delivery_type");
    var delivery_days = Cookies.get("delivery_days");

    var current = new Date();
   // var hr = current.getHours();
    //var min = current.getMinutes();

    const parts = new Intl.DateTimeFormat('en-US', {
        timeZone: 'America/New_York',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }).formatToParts(new Date());

    let hr, min;

    parts.forEach(part => {
        if (part.type === 'hour') hr = part.value;
        if (part.type === 'minute') min = part.value;
    });

   
    var message = '';
    if (delivery_type == 'Express Delivery') {
        message = 'Order within 23 hours and get delivery tomorrow';
        if (hr < 14) {
            message = 'Order within ' + (14 - hr) + ' hours' + ' : ' + (60 - min) + ' minutes and get same day delivery';
        }
        message = message + '<br>* Order before 2:00 PM and you can expect your order within 4 hours with our Express Delivery.'
    }
    if (delivery_type == 'Same Day Delivery') {

        if (hr >= 14 && min > 0) {
            message = 'Order within ' + (24 - hr + 13) + ' hours' + ' : ' + (60 - min) + ' minutes and delivery By Tomorrow <?php $newDate1 = date('l', strtotime('+1 days'));
            echo $newDate1 ?>, ' + ' <?php $newDate = date('d/M/Y', strtotime('+1 days'));
                echo $newDate; ?> ';
        }
        if (hr < 14) {
            message = 'Order within ' + (13 - hr) + ' hours' + ' : ' + (60 - min) + ' minutes and delivery By Today <?php echo date("l") ?>,' + ' <?php echo date("d/M/Y") ?> ';
        }
    }
    if (delivery_type == 'Twise in a week') {
        const dayArray = delivery_days.split(",");
        
        // Code start by HD
        
        // Get the current date and time
        const currentDate = new Date();

        // Check if the current day is in dayArray and the time is before 3 PM
        if (dayArray.includes(getDayName(currentDate)) && currentDate.getHours() < 15) {
            var delivery_date = currentDate;
        } else {
            // Loop through the next 7 days to find the first available day in dayArray
            for (let i = 1; i <= 7; i++) {
            const nextDate = new Date();
            nextDate.setDate(currentDate.getDate() + i);
            if (dayArray.includes(getDayName(nextDate))) {
                var delivery_date = nextDate;
                break;
            }
            }
        }
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        
        // Format the delivery_date as "Day dd mm, yyyy"
        const formattedDate = getDayName(delivery_date) + " (" + delivery_date.getDate() + " " + monthNames[delivery_date.getMonth()] + ", " + delivery_date.getFullYear() + ")";

        message = 'You can expect delivery by coming ' + formattedDate;
        
        // Log the formatted date
        console.log(formattedDate);
        
        // Code end by HD
        
        /*
        var d_day = 0;
    if (dayArray.indexOf("Monday") > -1) {
            d_day = 1;
        } else if (dayArray.indexOf("Tuesday") > -1) {
            d_day = 2;
        } else if (dayArray.indexOf("Wednesday") > -1) {
            d_day = 3;
        } else if (dayArray.indexOf("Thursday") > -1) {
            d_day = 4;
        } else if (dayArray.indexOf("Friday") > -1) {
            d_day = 5;
        } else if (dayArray.indexOf("Saturday") > -1) {
            d_day = 6;
        } else if (dayArray.indexOf("Sunday") > -1) {
            d_day = 7;
        }
        if (d_day == 1) {
            message = 'You can expect delivery by coming ' + '<?php $date = date_create();
            $date->modify('next Monday');
            echo date_format($date, "l d/M/Y") ?> ';
        }
        if (d_day == 2) {
            message = 'You can expect delivery by coming ' + '<?php $date = date_create();
            $date->modify('next Tuesday');
            echo date_format($date, "l d/M/Y") ?> ';
        }
        if (d_day == 3) {
            message = 'You can expect delivery by coming ' + '<?php $date = date_create();
            $date->modify('next Wednesday');
            echo date_format($date, "l d/M/Y") ?> ';
        }
        if (d_day == 4) {
            message = 'You can expect delivery by coming ' + '<?php $date = date_create();
            $date->modify('next Thursday');
            echo date_format($date, "l d/M/Y") ?> ';
        }
        if (d_day == 5) {
            message = 'You can expect delivery by coming ' + '<?php $date = date_create();
            $date->modify('next Friday');
            echo date_format($date, "l d/M/Y") ?> ';
        }
        if (d_day == 6) {
            message = 'You can expect delivery by coming ' + '<?php $date = date_create();
            $date->modify('next Saturday');
            echo date_format($date, "l d/M/Y") ?> ';
        }
        if (d_day == 7) {
            message = 'You can expect delivery by coming ' + '<?php $date = date_create();
            $date->modify('next Sunday');
            echo date_format($date, "l d/M/Y") ?> ';
        }
    */

    }

    $("#delivery_time").html(message);
}

// Function to get the day name from a date
function getDayName(date) {
    const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    return daysOfWeek[date.getDay()];
}

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
                
                $("#btn_section4_"+product_id).addClass("d-none");
                $("#qty_"+product_id).removeClass("d-none");
                $('#qty_'+product_id+' input[type="text"]').val(cart_qty_val);
            } else {
                $("#btn_section4_"+product_id).removeClass("d-none");
                $("#qty_"+product_id).addClass("d-none");
                $('#qty_'+product_id+' input[type="text"]').val('1');
                
            }
        });
    }
});

function get_related_product(api_url_prefix, category_slug, product_id) {
    var url = $("#product_slug").val();
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "category_slug": category_slug,
        "product_id": product_id
    };
    var product = "";
    if (login_user_id == 0) {
        cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
        cart_arr = JSON.parse(cart_arr);
    }
    console.log(cart_arr);
    var cart_qty_val = 1;
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-related-product-by-category-slug',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function (response) {
            if (response.data != "" && response.data != null) {
                product += '<div class="product-grid">';
                var count = '';
                if (response.data.length < 10) {
                    count = response.data.length;
                } else {
                    count = 10;
                }
                for (let a = 0; a < response.data.length; a++) {
								var findDay = response.data[a].product_id; //find price for day 1
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
                                    cart_row_id_val = cart_row_id[0];
                                }else{
                                    cart_row_id_val = '';
                                }
        
                                if(cart_qty.length > 0){
                                    cart_qty_val = cart_qty[0];
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
								if(varient_id.length > 0){
                                    var varient_id_val = varient_id[0];
                                }else{
                                    var varient_id_val = 0;
                                }
								var out_of_stock = "<div class='product-stock-message'></div>";
								var out_of_stock_class = "";
								var variant_in_stock_count = 0;
								/*if (response.data[a].is_out_of_stock == 0) {
									out_of_stock = "<div class='product-stock-message'>Out of stock</div>";
									out_of_stock_class = "out_of_stock";
								}*/
								if (response.data[a].product_size.length > 1 && response.data[a].product_size[0] != "") {
									var price_weight = '<form name="form" id="form"><select id="product_varient_'+ response.data[a].product_id+'" data-productid="'+ response.data[a].product_id +'" name="variants" class="variants product_varient ' + out_of_stock_class + '">';
									for (let j = 0; j < response.data[a].product_size.length; j++) {
										var first_variant_out_of_stock = false;
										var out_of_stock_variant = '';

										var selected = "";
                                        if(varient_id_val == response.data[a].product_size[j].variant_id){
                                            selected = "selected";
                                        }

										if (response.data[a].product_size[j].is_out_of_stock == 0) {
											out_of_stock_variant = ' out_of_stock_variant';
											if (response.data[a].product_size[j].is_out_of_stock == 0) {
												first_variant_out_of_stock = true;
											}
										}
										if (!first_variant_out_of_stock) {
											price_weight = price_weight.concat('<option '+selected+' class="' + out_of_stock_variant + '" value="' + response.data[a].product_size[j].size + '-' + response.data[a].product_size[j].price + '-' + response.data[a].product_size[j].variant_id + '">' + response.data[a].product_size[j].size + 'lb - $' + response.data[a].product_size[j].price + '</option>');
											variant_in_stock_count = variant_in_stock_count + 1;
										}
									}
									price_weight += '</select></form>';
								} else {
									var price_weight = '<span>' + response.data[a]
										.product_weight_gms + 'lb</span> - <strong>$' + response.data[a]
										.product_price + '</strong>';
								}

								var out_of_stock = "<div class='product-stock-message'></div>";
								var out_of_stock_class = "";

								//console.log("product out of stock:" + response.data[a].is_out_of_stock + " and variant out of stock:" + variant_in_stock_count);
								if (response.data[a].is_out_of_stock == 0) {
									out_of_stock = "<div class='product-stock-message'>Product Out of stock</div>";
									out_of_stock_class = "out_of_stock";
									var price_weight = "";
								}

								//product = product.concat('<div class="product-box"><img src='+response.data[a]["image"]+'><h4>'+response.data[a]["product_name"]+'</h4><strong>'+response.data[a]["product_price"]+'</strong> <span>'+response.data[a]["product_weight_gms"]+'</span><ul><li><img src=<?php echo ASSET_URL . "images/plus-minus.png"; ?>></li><li><button>Add</button></li></ul></div>');
								product = product.concat('<div class="product-box"><a href="<?php echo BASE_URL; ?>product/' + response.data[a].product_slug + '"><img src=' + response.data[a].product_image + ' onerror=this.src="<?php echo ADMIN_URL; ?>uploads/logo-2.png" ><h4>' + response.data[a].product_name + '</h4></a>' + price_weight + out_of_stock + '<ul><li><div id="qty_'+ response.data[a].product_id+'" class="quantity ' + out_of_stock_class + ' '+ qty_class + '"><button type="button" id="sub" class="sub qty_change_sub_detail" data-isperisible="' + response.data[a].is_perisible_products + '" data-productslug="' + response.data[a].product_slug + '" data-productimage= "' + response.data[a].product_image + '" data-productname="' + response.data[a].product_name + '" data-price=' + response.data[a].product_price + ' data-productid = ' + response.data[a].product_id + ' data-productweight =' + response.data[a].product_weight_gms + ' data-producttax="' + response.data[a].product_tax + '" data-productrowid="' + cart_row_id_val + '">-</button><input type="text" class = "prod_add1" id="' + response.data[a].product_id + '" value="'+cart_qty_val+'" min="1" max="3" disabled /><button type="button" id="add" class="add qty_change_add_detail" data-isperisible="' + response.data[a].is_perisible_products + '" data-productslug="' + response.data[a].product_slug + '" data-productimage= "' + response.data[a].product_image + '" data-productname="' + response.data[a].product_name + '" data-price=' + response.data[a].product_price + ' data-productid = ' + response.data[a].product_id + ' data-productweight =' + response.data[a].product_weight_gms + ' data-producttax="' + response.data[a].product_tax + '" data-productrowid="' + cart_row_id_val + '">+</button></div></li><li><button id= "btn_section4_' + response.data[a].product_id + '" class="add_detail_cart ' + out_of_stock_class + ' '+ add_class+'" data-isperisible="' + response.data[a].is_perisible_products + '" data-productslug="' + response.data[a].product_slug + '" data-productimage= "' + response.data[a].product_image + '" data-productname="' + response.data[a].product_name + '" data-price=' + response.data[a].product_price + ' data-productid = ' + response.data[a].product_id + ' data-productweight =' + response.data[a].product_weight_gms + ' data-producttax="' + response.data[a].product_tax + '" data-productrowid="' + cart_row_id_val + '">Add</button></li></ul></div>');

							}
                product += '</div>';
                $("#related-products").html(product);
            } else {
                product = "No Products";
            }
        },
        "error": function (response) {
            $("#related-products").html(response.errors);
        }
    });
}

function openTab(evt, tabName) {

    let tabcontent = document.querySelectorAll(".tab-content");
    let tablinks = document.querySelectorAll(".tab-link");

    tabcontent.forEach(content => content.classList.remove("active"));
    tablinks.forEach(link => link.classList.remove("active"));

    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

</script>

