

<script>

$(document).ready(function() {

    showProgress('div#spinner');
    get_slider_banners(api_url_prefix);

    get_side_banners(api_url_prefix);

    get_icon_categories(api_url_prefix);

    get_special_product(api_url_prefix);

    get_special_product1(api_url_prefix);

    get_discover_products(api_url_prefix);

    get_category_products(api_url_prefix);

    get_brands(api_url_prefix);

    $('#viewMoreBtn').on('click', function() {
        $('.featured_cat_container').addClass('expanded');
        $(this).addClass('hidden');
    });


    get_home_banners(api_url_prefix);
    get_featured_category(api_url_prefix);
    get_stockup_your_frozen(api_url_prefix);
    get_advertise_top(api_url_prefix);
    get_home_product_slider(api_url_prefix);
    get_advertise_bottom(api_url_prefix);
    get_refill_pantry(api_url_prefix);

	

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
            } else {
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
                
                var cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
                cart_arr = JSON.parse(cart_arr);
                var findDay =product_id; //find price for day 1
                //find product row id
                var cart_row_id = $.map(cart_arr, function(value, key) {
                    if (value.id == findDay)
                    {
                        return value.rowid;
                    }
                });

                if(cart_row_id.length > 0){
                    var cart_row_id_val = cart_row_id[0];
                }else{
                    var cart_row_id_val = '';
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
    });

});

function get_slider_banners(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "is_active_only": "1",

        "banner_type": "slider",

        "search_keyword": "",

        "limit": "10",

        "page_no": "0",

        "sort_column": "banner_id",

        "sort_order": "desc"

    };

    var slider_banner = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-banners',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if(response.is_successful == 1){
                for (let a = 0; a < response.data.length; a++) {

                    slider_banner = slider_banner.concat("<div><a href='" + response.data[a]['banner_link'] +

                        "'><img src='" + response.data[a]['banner_image'] + "' onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");

                }

                $("#home_banner").html(slider_banner);

                $('.slick-carousel').slick({

                    arrows: false,

                    autoplay: true,

                    centerPadding: "0px",

                    dots: true,

                    slidesToShow: 1,

                    infinite: false,

                    responsive: [{

                        breakpoint: 991,

                        settings: {

                            slidesToShow: 1,

                        }

                    }, {

                        breakpoint: 767,

                        settings: {

                            slidesToShow: 1,

                        }

                    }]

                });
            }

        },

        "error": function(response) {

            console.log(response.errors);

        }

    });

}

function get_side_banners(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "is_active_only": "1",

        "banner_type": "side_banner",

        "search_keyword": "",

        "limit": "10",

        "page_no": "0",

        "sort_column": "banner_id",

        "sort_order": "desc"

    };

    var slider_banner = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-banners',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                for (let a = 0; a < response.data.length; a++) {

                    slider_banner = slider_banner.concat("<div><a href='" + response.data[a]['banner_link'] +

                        "'><img src=" + response.data[a]['banner_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");

                }

            }

            $("#banner-right").html(slider_banner);

            $('#banner-right').slick({

                arrows: false,

                centerPadding: "0px",

                dots: true,

                slidesToShow: 1,

                infinite: false,

                responsive: [{

                    breakpoint: 991,

                    settings: {

                        slidesToShow: 1,

                    }

                }, {

                    breakpoint: 767,

                    settings: {

                        slidesToShow: 1,

                    }

                }]

            });

        },

        "error": function(response) {

            console.log(response.errors);

        }

    });

}

function get_brands(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "is_active_only": "1",

        "is_home_display": "1",

        "search_keyword": "",

        "limit": "50",

        "page_no": "0",

        "sort_column": "brand_id",

        "sort_order": "desc"

    };

    var brand = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-brands',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                for (let a = 0; a < response.data.length; a++) {

                    brand = brand.concat("<div><a href='<?php echo BASE_URL; ?>brand/" + response.data[a]['brand_slug'] + "'><img src=" + response.data[a]['brand_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");

                }

            }

            jQuery("#brand").html(brand);

            jQuery('.brand').slick({

                dots: false,

                autoplay: true,

                arrows: true,

                infinite: true,

                speed: 300,

                slidesToShow: 7,

                slidesToScroll: 7,

                responsive: [{

                    breakpoint: 991,

                    settings: {

                        slidesToShow: 5,

                        slidesToScroll: 5,

                    }

                }, {

                    breakpoint: 767,

                    settings: {

                        slidesToShow: 3,

                        slidesToScroll: 3,

                    }

                }]

            });

        },

        "error": function(response) {

            console.log(response.errors);

        }

    });

}

function get_icon_categories(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "is_active_only": "1",

        "search_keyword": "",

        "limit": "7",

        "page_no": "0",

        "style": "top",

        "is_home_display": "1",

        "sort_column": "category_id",

        "sort_order": "asc",

        "zipcode": Cookies.get("zipcode")

    };

    var category = "";

    var product = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-home-categories-product',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                for (let a = 0; a < response.data.categories.length; a++) {

                    category = category.concat('<li class="tab-link" data-tab="' + response.data.categories[

                            a]['category_slug'] + '"> <img class="svg" src="' + response.data

                        .categories[a]['category_image'] + '"> <span>' + response.data.categories[a][

                            'category_name'

                        ] + '</span></li>');

                    product = product.concat('<div id="' + response.data.categories[a]['category_slug'] +

                        '" class="tab-content"><div class="product-grid">');

                }

                $("#tabs").html(category);

                $("#categories_product").html(

                    '<div id="tab-content" class="tab-content current"><div class="product-grid">Loading....</div></div>'

                );

                $("#tabs li:first-child").addClass("current");

                $("#tabs li:first-child").click();

                hideProgress('div#spinner');

            } else {

                $("#tabs").hide();

                hideProgress('div#spinner');

            }

        },

        "error": function(response) {

            category = response.errors;

            $("#tabs").html(category);

            hideProgress('div#spinner');

        }

    });

}

function home_products(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "category_slug": "1",

        "is_active_only": "1",

        "search_keyword": "",

        "limit": "10",

        "page_no": "0",

        "banner_type": "bottom",

        "sort_column": "banner_id",

        "sort_order": "asc"

    };

    var banners = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-banners',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                for (let a = 0; a < response.data.length; a++) {

                    banners = banners.concat('<li><a href="' + response.data[a]['banner_link'] +

                        '"><img class="svg" src=' + response.data[a]['banner_image'] + '> </a></li>');

                }

                $("#bottom_category").html(banners);

            }

        },

        "error": function(e) {

            console.log(e.responseText);

        }

    });

}

function get_image_categories(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "is_active_only": "1",

        "search_keyword": "",

        "limit": "10",

        "page_no": "0",

        "banner_type": "bottom",

        "sort_column": "banner_id",

        "sort_order": "asc"

    };

    var banners = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-banners',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                for (let a = 0; a < response.data.length; a++) {

                    banners = banners.concat('<li><a href="' + response.data[a]['banner_link'] +

                        '"><img class="svg" src=' + response.data[a]['banner_image'] + '> </a></li>');

                }

                $("#bottom_category").html(banners);

            }

        },

        "error": function(e) {

            console.log(e.responseText);

        }

    });

}

function get_products(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "is_active_only": "1",

        "search_keyword": "",

        "is_home_display": "1",

        "limit": "10",

        "page_no": "0",

        "sort_column": "product_id",

        "sort_order": "desc"

    };

    var product = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-products',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                for (let a = 0; a < response.data.length; a++) {

                    product = product.concat('<div class="product-box"><img src=' + response.data[a][

                            "image"

                        ] + 'onerror=this.src="<?php echo BASE_URL; ?>assets/images/logo-2.png"><h4>' + response.data[a]["product_name"] + '</h4><strong>$' +

                        response.data[a]["product_price"] + '</strong> <span>' + response.data[a][

                            "product_weight_gms"

                        ] +

                        'g</span><ul><li><img src=<?php echo ASSET_URL . "images/plus-minus.png"; ?>></li><li><button>Add</button></li></ul></div>'

                    );

                }

                $("#product-grid").html(product);

            }

        },

        "error": function(e) {

            console.log(e.responseText);

        }

    });

}

function get_special_product(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "special_product_slug": "popular-items",

        "zipcode": Cookies.get("zipcode")

    };

    var product = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-special-products',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

			console.log(response);

            if (response.data != null) {

                product_title = '<h2>' + response.data.special_product_name + '</h2>';

                if (response.data.product_detail != "") {

                        var first_variant_out_of_stock = false;

                    for (let a = 0; a < response.data.product_detail.length; a++) {

                       

							var price_weight = '<span>' + response.data.product_detail[a]

                                .product_weight_gms + 'lb</span> - <strong>$' + response.data.product_detail[a]

                                .product_price + '</strong>';

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

							if(objectLength>=2)

							{

								price_weight = ''

								var buttonSection = '<a href="product/' + response.data.product_detail[a].product_slug +'"><ul><li><button class="add_cart">More Options</button></li></ul></a>';

							}

							else

							{

							var buttonSection = ' <ul><li><div class="quantity ' + out_of_stock_class + '"><button type="button" id="sub" class="sub">-</button><input type="text" id="' + response.data.product_detail[a].product_id + '" value="1" min="1" max="3" disabled /><button type="button" id="add" class="add">+</button></div></li><li><button id= "btn_section4_' + response.data.product_detail[a].product_id + '"data-productslug="' + response.data.product_detail[a].product_slug + '" data-productimage= "' + response.data.product_detail[a].product_image + '" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" class="add_cart ' + out_of_stock_class + '" data-section="section4" data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" data-productname="' + response.data.product_detail[a].product_name + '" data-price=' + response.data.product_detail[a].product_price + ' data-productid = ' + response.data.product_detail[a].product_id + ' data-productweight =' + response.data.product_detail[a].product_weight_gms +  ' data-producttax=' + response.data.product_detail[a].product_tax + '>Add</button></li></ul>';

							}

						}

                        product = product.concat('<div class="product-box"><a href="product/' + response.data.product_detail[a].product_slug +

                            '"> <img src=' + response.data.product_detail[a]

                            .image + ' onerror=this.src="<?php echo BASE_URL; ?>assets/images/logo-2.png"><h4>' + response.data.product_detail[a].product_name +

                            '</h4></a>' + price_weight +buttonSection+'</div>'

							

							

							

                        );

                        jQuery("#popular-product").html(product_title +

                            '<div class="popular-items"  id="popular-items">' + product + '</div>');

                        setTimeout(function() {

                            jQuery('.popular-items, .popular-items-2').slick({

                                dots: false,

                                arrows: true,

                                autoplay: true,

                                infinite: false,

                                speed: 300,

                                slidesToShow: 5,

                                slidesToScroll: 5,

                                responsive: [{

                                    breakpoint: 991,

                                    settings: {

                                        slidesToShow: 3,

                                        slidesToScroll: 3,

                                    }

                                }, {

                                    breakpoint: 767,

                                    settings: {

                                        slidesToShow: 2,

                                        slidesToScroll: 2,

                                    }

                                }]

                            });

                        }, 500);

                    }

                } else {

                    jQuery("#popular-product").hide();

                }

            }

        },

        "error": function(response) {

            jQuery("#popular-product").html('<div class="popular-items"  id="popular-items">' + response.errors + '</div>');;

        }

    });

}

function get_special_product1(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "special_product_slug": "best-of-home-essentials",

        "zipcode": Cookies.get("zipcode")

    };

    var product1 = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-special-products',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                product_title1 = '<h2>' + response.data.special_product_name + '</h2>';

                if (response.data.product_detail != "") {

					

                        var first_variant_out_of_stock = false;

                    for (let a = 0; a < response.data.product_detail.length; a++) {

                        

						

						var price_weight = '<span>' + response.data.product_detail[a]

                                .product_weight_gms + 'lb</span> - <strong>$' + response.data.product_detail[a]

                                .product_price + '</strong>';

								

                            var out_of_stock = "<div class='product-stock-message'></div>";

							var out_of_stock_class = "";

							

							if (response.data.product_detail[a].is_out_of_stock == 0)

							{

								price_weight = ''

								out_of_stock_class = "out_of_stock";

								var buttonSection = "<div class='product-stock-message'>Product is sold out</div>";

							}

							else

							{

								var obj = response.data.product_detail[a].product_size;

								objectLength = Object.keys(obj).length;

								if(objectLength>=2)

								{

									price_weight = ''

									var buttonSection = '<a href="product/' + response.data.product_detail[a].product_slug +'"><ul><li><button class="add_cart">More Options</button></li></ul></a>';

								}

								else

								{

								var buttonSection = '<ul><li><div class="quantity ' + out_of_stock_class + '"><button type="button" id="sub" class="sub">-</button><input type="text" id= "' + response.data.product_detail[a].product_id + '" value="1" min="1" max="3" disabled /><button type="button" id="add" class="add">+</button></div></li><li><button id= "btn_section5_' + response.data.product_detail[a].product_id + '" class="add_cart ' + out_of_stock_class + '" data-section="section5"  data-isperisible="' + response.data.product_detail[a].is_perisible_products + '" data-productslug="' + response.data.product_detail[a].product_slug + '" data-productimage= "' + response.data.product_detail[a].product_image + '" data-productname="' + response.data.product_detail[a].product_name + '" data-price=' + response.data.product_detail[a].product_price + ' data-productid = ' + response.data.product_detail[a].product_id + ' data-productweight =' + response.data.product_detail[a].product_weight_gms +  ' data-producttax=' + response.data.product_detail[a].product_tax + '>Add</button></li></ul>';

								}

							}

                        product1 = product1.concat('<div class="product-box"><a href="product/' + response.data.product_detail[a].product_slug +

                            '"><img src=' + response.data.product_detail[a]

                            .image + ' onerror=this.src="<?php echo BASE_URL; ?>assets/images/logo-2.png"><h4>' + response.data.product_detail[a].product_name +

                            '</h4></a>' + price_weight + out_of_stock + buttonSection +'</div>'

                        );

                    }

					

                    jQuery("#popular-product1").html(product_title1 +'<div class="popular-items" id="popular-items">' + product1 + '</div>');

                    setTimeout(function() {

                        jQuery('.popular-items').slick({

                            dots: true,

                            arrows: false,

                            infinite: true,

                            autoplay: true,

                            speed: 300,

                            slidesToShow: 5,

                            slidesToScroll: 5,

                            responsive: [{

                                    breakpoint: 1025,

                                    settings: {

                                        slidesToShow: 3,

                                    }

                                },

                                {

                                    breakpoint: 767,

                                    settings: {

                                        slidesToShow: 2,

                                    }

                                }

                            ]

                        });

                    }, 500);

                } else {

                    jQuery("#popular-product1").hide();

                }

            }

        },

        "error": function(response) {

            jQuery("#popular-product1").html('<div class="popular-items" id="popular-items1">' + response.errors + '</div>');

        }

    });

}

function get_discover_products(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "special_product_slug": "discover",

        "zipcode": Cookies.get("zipcode")

    };

    var product = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-special-products',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                product_title = '<h2>' + response.data.special_product_name + '</h2>';

                if (response.data.product_detail != "") {

                    for (let a = 0; a < response.data.product_detail.length; a++) {

                        product = product.concat('<a href="product/' + response.data.product_detail[a].product_slug + '"><img src=' + response.data.product_detail[a].product_image + ' onerror=this.src="<?php echo BASE_URL; ?>assets/images/logo-2.png"><h4>' + response.data.product_detail[a].product_name + '</h4></a>');

                    }

                    $("#discover").html(product_title + "<div class='discover'>" + product + "</div>");

                    jQuery('.discover').slick({

                        dots: false,

                        arrows: true,

                        infinite: false,

						autoplay: true,

                        speed: 300,

                        slidesToShow: 3,

                        slidesToScroll: 3,

                        responsive: [{

                            breakpoint: 991,

                            settings: {

                                slidesToShow: 3,

                                slidesToScroll: 3,

                            }

                        }, {

                            breakpoint: 767,

                            settings: {

                                slidesToShow: 2,

                                slidesToScroll: 1,

                            }

                        }]

                    });

                } else {

                    $("#discover").hide();

                }

            }

        },

        "error": function(response) {

            $("#discover").html("<div class='discover'>" + response.errors + "</div>");

        }

    });

}

function get_category_products(api_url_prefix) {

    var json_request = {

        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",

        "zipcode": Cookies.get("zipcode")

    };

    var product = "";

    $.ajax({

        "type": "POST",

        "url": api_url_prefix + 'get-home-category-product',

        "data": JSON.stringify(json_request),

        "dataType": "JSON",

        "success": function(response) {

            if (response.data != null) {

                product_title = '<h2>' + response.data[0].category_name + '</h2>';

                for (let a = 0; a < response.data.length; a++) {

                    var price_weight = "";

                    if (response.data[a].product_size.length > 1) {

                        //var price_weight = '<form name="form" id="form"><select name="jumpMenu" class="variants" id="section3_jumpMenu' + response.data[a].product_id + '">';
                        var price_weight = '<form name="form" id="form"><select id="product_varient_'+ response.data[a].product_id+'" data-productid="'+ response.data[a].product_id+'" name="variants" class="variants product_varient">';

                        for (let j = 0; j < response.data[a].product_size.length; j++) {

                            price_weight = price_weight.concat('<option value="' + response.data[a].product_size[j].size + '-' + response.data[a].product_size[j].price + '-' + response.data[a].product_size[j].variant_id + '">' + response.data[a].product_size[j].size + 'lb - $' + response.data[a].product_size[j].price + '</option>');

                        }

                        price_weight += '</select></form>';

                    } else {

                        var price_weight = '<span>' + response.data[a]

                            .product_weight_gms + 'lb</span> - <strong>$' + response.data[a]

                            .product_price + '</strong>';

                    }

                    product = product.concat('<div class="product-box"><a href="product/' + response.data[a][

                        "product_slug"

                    ] + '"><img src=' + response.data[a]["image"] + ' onerror=this.src="<?php echo BASE_URL; ?>assets/images/logo-2.png"><div><h4>' + response.data[a][

                        "product_name"

                    ] + '</h4></a>' + price_weight + '<ul><li><div class="quantity"><button type="button" id="sub" class="sub">-</button><input type="text" id="' + response.data[a]["product_id"] + '" value="1" min="1" max="3" disabled/><button type="button" id="add" class="add">+</button></div></li><li><button type="button" id= "btn_section3_' + response.data[a]["product_id"] + '" class="add_cart" data-section="section3" data-isperisible="' + response.data[a]["is_perisible_products"] + '" data-productslug="' + response.data[a]["product_slug"] + '" data-productimage= "' + response.data[a]["image"] + '" data-productname="' + response.data[a]["product_name"] + '" data-price=' + response.data[a]["product_price"] + ' data-productid = ' + response.data[a]["product_id"] + 'data-productweight =' + response.data[a]["product_weight_gms"] + 'data-productslug=' + response.data[a]["product_slug"] + ' data-producttax=' + response.data[a].product_tax + '>Add</button></li></ul></div></div>');

                }

                $("#category_product").html(product_title + '<div class="container-flex"><div class="product-grid">' + product +

                    '</div></div>');

                $("#vraj-btn").html("<a href='category/" + response.data[0].category_slug +

                    "' class='vraj-btn'>View all " + response.data[0].category_name + " products</a>")

            } else {

                //$("#category_product").html("<div>"+response.errors+"</div>");

                $(".vraj-bakery").hide();

                $("a.vraj-btn").hide();

            }

        },

        "error": function(response) {

            $("#category_product").html("<div>" + response.errors + "</div>");

            $("a.vraj-btn").hide();

        }

    });

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
                    return value.rowid;
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

function get_featured_category(api_url_prefix) {
    $("#featured_category").html('<div id="tab-content" class="tab-content current"><div class="product-grid">Loading....</div></div>');
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "is_active_only": "1",
        "search_keyword": "",
        "limit": "18",
        "page_no": "0",
    };
    var category_html = "";
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-featured_category',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {

                category_html=`<h2 class="b_main_heading" id="${response.data.length}">${response.data2.section_title}</h2><div  class="featured_cat_container">`;
                
                for (let a = 0; a < response.data.length; a++) {
                    category_html = category_html.concat(`
                        <div class="col-md-2">
                        <div class="featured_category_image"><a href="${response.data[a].cat_link}"><img class="svg" src="${response.data[a].cat_image}"></a></div>
                            <div class="featured_category_title"><a href="${response.data[a].cat_link}">${response.data[a].cat_name}</a></div>
                        </div>`);
                }
                category_html = category_html.concat(`</div>`);
                var desk_class = 'deskhide';
                var mob_class = 'mobhide';
                if(response.data.length>16) 
                {
                    desk_class = 'deskshow';
                }
                if(response.data.length>14)
                {
                    mob_class = 'mobshow';
                }
                //category_html = category_html.concat(`<a class="${desk_class} ${mob_class}" id="viewMoreBtn">View All</a>`);
                $("#viewMoreBtn").addClass(desk_class+' '+mob_class); 

                
                $("#featured_category").html(category_html);
                hideProgress('div#spinner');
            } else {
                $("#tabs").hide();
                hideProgress('div#spinner');
            }
        },
        "error": function(response) {
            category = response.errors;
            $("#tabs").html(category);
            hideProgress('div#spinner');
        }
    });
}

function get_stockup_your_frozen(api_url_prefix) {
    $("#featured_category").html('<div id="tab-content" class="tab-content current"><div class="product-grid">Loading....</div></div>');
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "is_active_only": "1",
        "search_keyword": "",
        "limit": "20",
        "page_no": "0",
    };
    var stockup_html = "";
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-stockup_your_frozen',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {
                $('.slockup-title').html(`<h2 class="b_main_heading"  id="${response.data.length}">${response.data2.section_title}</h2>`);
                for (let a = 0; a < response.data.length; a++) {
                    stockup_html = stockup_html.concat(`
                        <div class="item">
                        <div class="featured_stockup_image"><a href="${response.data[a].stockup_link}"><img class="svg" src="${response.data[a].stockup_image}"></a></div>
                            <div class="featured_stockup_title"><a href="${response.data[a].stockup_link}">${response.data[a].stockup_name}</a></div>
                        </div>`);
                }
                
                $("#stockup_your_frozen").html(stockup_html);
                hideProgress('div#spinner');

                setTimeout(function() {
                        jQuery('#stockup_your_frozen').slick({
                            dots: false,
                            arrows: true,
                            infinite: false,
                            autoplay: false,
                            // speed: 300,
                            slidesToShow: 6,
                            slidesToScroll: 1,
                            responsive: [
                                {
                                    breakpoint: 1370,
                                    settings: {
                                        slidesToShow: 5,
                                    }
                                },
                                {
                                    breakpoint: 1190,
                                    settings: {
                                        slidesToShow: 4,
                                    }
                                }
                                ,
                                {
                                    breakpoint: 915,
                                    settings: {
                                        slidesToShow: 3,
                                        arrows: false   
                                    }
                                },
                                {
                                    breakpoint: 525,
                                    settings: {
                                        slidesToShow: 2.5,
                                        arrows: false   
                                    }
                                }
                            ]
                        });
                    }, 500);
            } else {
                hideProgress('div#spinner');
            }
        },
        "error": function(response) {
            stockup = response.errors;
            $("#tabs").html(stockup);
            hideProgress('div#spinner');
        }
    });
}

function get_refill_pantry(api_url_prefix) {
    $("#featured_category").html('<div id="tab-content" class="tab-content current"><div class="product-grid">Loading....</div></div>');
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "is_active_only": "1",
        "search_keyword": "",
        "limit": "20",
        "page_no": "0",
    };
    var pantry_html = "";
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-refill_pantry',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {
                $('.refill_pantry-title').html(`<h2 class="b_main_heading" >${response.data2.section_title}</h2>`);
                for (let a = 0; a < response.data.length; a++) {
                    pantry_html = pantry_html.concat(`
                        <div class="item">
                        <div class="featured_pantry_image"><a href="${response.data[a].pantry_link}"><img class="svg" src="${response.data[a].pantry_image}"></a></div>
                            <div class="featured_pantry_title"><a href="${response.data[a].pantry_link}">${response.data[a].pantry_name}</a></div>
                        </div>`);
                }
                $("#refill_pantry").html(pantry_html);
                hideProgress('div#spinner');

                setTimeout(function() {
                        jQuery('#refill_pantry').slick({
                            dots: false,
                            arrows: true,
                            infinite: false,
                            autoplay: false,
                            // speed: 300,
                            slidesToShow: 6,
                            slidesToScroll: 2,
                            responsive: [
                                {
                                    breakpoint: 1370,
                                    settings: {
                                        slidesToShow: 5,
                                    }
                                },
                                {
                                    breakpoint: 1190,
                                    settings: {
                                        slidesToShow: 4,
                                    }
                                }
                                ,
                                {
                                    breakpoint: 915,
                                    settings: {
                                        slidesToShow: 3,
                                        arrows: false   
                                    }
                                },
                                {
                                    breakpoint: 525,
                                    settings: {
                                        slidesToShow: 2.5,
                                        arrows: false   
                                    }
                                }
                            ]
                        });
                    }, 500);
            } else {
                hideProgress('div#spinner');
            }
        },
        "error": function(response) {
            pantry = response.errors;
            $("#tabs").html(pantry);
            hideProgress('div#spinner');
        }
    });
}

function get_home_product_slider(api_url_prefix) {
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "special_product_slug": "popular-items",
        "zipcode": Cookies.get("zipcode")
    };
    var slider_html='';
    var cart_arr = '<?php echo json_encode($this->cart->contents()); ?>';
    cart_arr = JSON.parse(cart_arr);
    
    var cart_qty_val = 1;

    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get_home_product_slider',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {
                
                if (response.data.product_slider_data != "") {
                    var first_variant_out_of_stock = false;
                    console.log(response.data.product_slider_data, 'Slider');
                    for (let a = 0; a < response.data.product_slider_data.length; a++) {
                        slider_html +=`<section id="mobile_full" >
                            <div class="container">
                                <div id="title_container" class="new_savings-title">
                                    <h2 class="b_main_heading" >${response.data.product_slider_data[a].title}</h2>
                                    <a href="special-category/${response.data.product_slider_data[a].slug}" class="view_all">View All</a>
                                </div>
                            <div class="${response.data.product_slider_data[a].slug}">`;
                            // console.log(response.data.product_slider_data[a]);

                        var product_slider_items=response.data.product_slider_data[a].product_slider_items;
                        if (product_slider_items) {
                                var product = "";
                                var first_variant_out_of_stock = false;
                            for (let a = 0; a < product_slider_items.length; a++) {
                                var findDay = product_slider_items[a].product_id; //find price for day 1
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
                                        return value.rowid;
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
                                
                                let product_price=product_slider_items[a].product_price;
                                let sale_price=product_slider_items[a].sale_price;
                                let price_html=`<strong>$${product_price}</strong>`;
                                if(product_price != sale_price){
                                        price_html=`<del>$${product_price}</del><strong>$${sale_price}</strong>`;
                                }
                                var price_weight = '<div class="begin_details"> '+price_html+'</div>';
        
                                
                                var out_of_stock = "<div class='product-stock-message'></div>";
                                var out_of_stock_class = "";
                                
                                if(product_slider_items[a].is_out_of_stock == 0)
                                {
                                    out_of_stock_class = "out_of_stock";
                                    price_weight = ''
                                    var buttonSection = "<div class='product-stock-message'>Product is sold out</div>";
                                }
                                else
                                {
                                    // var obj = product_slider_items[a].product_size;
                                    // objectLength = Object.keys(obj).length;
                                    // if(objectLength>=2)
                                    // {
                                    //     price_weight = ''
                                    //     var buttonSection = '<a href="product/' + product_slider_items[a].product_slug +'"><ul><li><button class="add_cart">More Options</button></li></ul></a>';
                                    // }
                                    // else
                                    // {
                                    // var buttonSection = ' <ul><li><div id="qty_'+ product_slider_items[a].product_id+'" class="quantity ' + out_of_stock_class +' '+ qty_class + '"><button type="button" id="sub" class="sub qty_change_sub" "data-productslug="' + product_slider_items[a].product_slug + '" data-productimage= "' + product_slider_items[a].product_image + '" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-section="section4" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-productname="' + product_slider_items[a].product_name + '" data-price=' + product_slider_items[a].product_price + ' data-productid = ' + product_slider_items[a].product_id + ' data-productweight =' + product_slider_items[a].product_weight_gms +  ' data-producttax=' + product_slider_items[a].product_tax + ' data-productrowid='+cart_row_id_val+'>-</button><input type="text" id="' + product_slider_items[a].product_id + '" value="'+cart_qty_val+'" min="1" max="3" disabled /><button type="button" id="add" class="add qty_change_add" "data-productslug="' + product_slider_items[a].product_slug + '" data-productimage= "' + product_slider_items[a].product_image + '" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-section="section4" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-productname="' + product_slider_items[a].product_name + '" data-price=' + product_slider_items[a].product_price + ' data-productid = ' + product_slider_items[a].product_id + ' data-productweight =' + product_slider_items[a].product_weight_gms +  ' data-producttax=' + product_slider_items[a].product_tax + '>+</button></div></li><li><button id= "btn_section4_' + product_slider_items[a].product_id + '"data-productslug="' + product_slider_items[a].product_slug + '" data-productimage= "' + product_slider_items[a].product_image + '" data-isperisible="' + product_slider_items[a].is_perisible_products + '" class="add_cart ' + out_of_stock_class + ' '+ add_class+'" data-section="section4" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-productname="' + product_slider_items[a].product_name + '" data-price=' + product_slider_items[a].product_price + ' data-productid = ' + product_slider_items[a].product_id + ' data-productweight =' + product_slider_items[a].product_weight_gms +  ' data-producttax=' + product_slider_items[a].product_tax + '>Add</button></li></ul>';
                                    // }
                                    var obj = product_slider_items[a].product_size;
                                    objectLength = Object.keys(obj).length;
                                        if (objectLength > 1) {

                                            var price_weight = '<form name="form" id="form"><select id="product_varient_'+ product_slider_items[a].product_id+'" data-productid="'+ product_slider_items[a].product_id+'" name="variants" class="variants product_varient">';
                                            for (let j = 0; j < objectLength; j++) {
                                                var selected = "";
                                                if(varient_id_val == product_slider_items[a].product_size[j].product_variant_id){
                                                    selected = "selected";
                                                }
                                                price_weight = price_weight.concat('<option '+selected+' value="' + product_slider_items[a].product_size[j].size + '-' + product_slider_items[a].product_size[j].price + '-' + product_slider_items[a].product_size[j].product_variant_id + '">' + product_slider_items[a].product_size[j].size + 'lb - $' + product_slider_items[a].product_size[j].price + '</option>');
                                            }
                                            price_weight += '</select></form>';
                                            } else {
                                            // var price_weight = '<span>' + product_slider_items[a].product_weight_gms + 'lb</span> - <strong>$' + product_slider_items[a].product_price + '</strong>';
                                            }
                                    var buttonSection = ' <ul><li><div id="qty_'+ product_slider_items[a].product_id+'" class="quantity ' + out_of_stock_class +' '+ qty_class + '"><button type="button" id="sub" class="sub qty_change_sub" "data-productslug="' + product_slider_items[a].product_slug + '" data-productimage= "' + product_slider_items[a].product_image + '" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-section="section4" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-productname="' + product_slider_items[a].product_name + '" data-price=' + product_slider_items[a].sale_price + ' data-productid = ' + product_slider_items[a].product_id + ' data-productweight =' + product_slider_items[a].product_weight_gms +  ' data-producttax=' + product_slider_items[a].product_tax + ' data-productrowid='+cart_row_id_val+'>-</button><input type="text" id="' + product_slider_items[a].product_id + '" value="'+cart_qty_val+'" min="1" max="3" disabled /><button type="button" id="add" class="add qty_change_add" "data-productslug="' + product_slider_items[a].product_slug + '" data-productimage= "' + product_slider_items[a].product_image + '" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-section="section4" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-productname="' + product_slider_items[a].product_name + '" data-price=' + product_slider_items[a].sale_price + ' data-productid = ' + product_slider_items[a].product_id + ' data-productweight =' + product_slider_items[a].product_weight_gms +  ' data-producttax=' + product_slider_items[a].product_tax + '>+</button></div></li><li><button id= "btn_section4_' + product_slider_items[a].product_id + '"data-productslug="' + product_slider_items[a].product_slug + '" data-productimage= "' + product_slider_items[a].product_image + '" data-isperisible="' + product_slider_items[a].is_perisible_products + '" class="add_cart ' + out_of_stock_class + ' '+ add_class+'" data-section="section4" data-isperisible="' + product_slider_items[a].is_perisible_products + '" data-productname="' + product_slider_items[a].product_name + '" data-price=' + product_slider_items[a].sale_price + ' data-productid = ' + product_slider_items[a].product_id + ' data-productweight =' + product_slider_items[a].product_weight_gms +  ' data-producttax=' + product_slider_items[a].product_tax + '>Add</button></li></ul>';
                                    
                                }

                                var tagdiscounttext = '';
                                if (product_slider_items[a].tag_discount != '') {
                                    tagdiscounttext = '<span>'+product_slider_items[a].tag_discount+'</span>';
                                }else{
                                    tagdiscounttext = '';
                                }
                                if (objectLength > 1) {
                                    tagdiscounttext = '';
                                }

                                product = product.concat('<div class="product-box"><a href="product/' + product_slider_items[a].product_slug +
                                    '"> <div class="begin_img_container">'+tagdiscounttext+'<img src=' + product_slider_items[a].image + ' onerror=this.src="<?php echo BASE_URL; ?>assets/images/logo-2.png"></div><h4>' + product_slider_items[a].product_name +
                                    '</h4></a>' + price_weight +buttonSection+'</div>'
                                    
                                    
                                    
                                );
                                
                            }
                            slider_html += product;
                        }

                        slider_html +=`</div>
                        </div>
                        </section>
                        `;
                        jQuery('.home_product_slider_main').html(slider_html);

                        setTimeout(function() {
                            jQuery('.'+response.data.product_slider_data[a].slug).slick({
                                dots: false,
                                arrows: true,
                                infinite: false,
                                autoplay: false,
                                speed: 300,
                                slidesToShow: 6,
                                slidesToScroll: 1,
                                responsive: [
                                    {
                                        breakpoint: 991,
                                        settings: {
                                            slidesToShow: 4,
                                            slidesToScroll: 4,
                                        }
                                    },
                                    {
                                        breakpoint: 1240,
                                        settings: {
                                            slidesToShow: 5,
                                            slidesToScroll: 1,
                                        }
                                    }, 
                                    {
                                    breakpoint: 767,
                                        settings: {
                                            slidesToShow: 2.5,
                                            arrows: false,
                                            infinite: false,
                                        }
                                    }
                            ]
                            });
                        }, 500);
                        
                    }
                }
            }
        },
        "error": function(response) {
            jQuery("#new_savings").html('<div class="popular-items"  id="popular-items">' + response.errors + '</div>');;
        }
    });
}

function get_home_banners(api_url_prefix) {
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "is_active_only": "1",
        "banner_type": "side_banner",
        "search_keyword": "",
        "limit": "10",
        "page_no": "0",
        "sort_column": "banner_srno",
        "sort_order": "ASC"
    };
    var slider_banner = "";
    var slider_banner_mobile = "";
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-home_banner',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {
                if (response.data.home_banner != null) {
                    for (let a = 0; a < response.data.home_banner.length; a++) {
                        if(response.data.home_banner[a]['banner_type'] == 'video'){
                            slider_banner = slider_banner.concat("<div><video muted autoplay loop><source src=" + response.data.home_banner[a]['banner_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png' type='video/mp4'></video></div>");
                        }else{
                            slider_banner = slider_banner.concat("<div><a href='" + response.data.home_banner[a]['banner_link'] +
                            "'><img src=" + response.data.home_banner[a]['banner_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                    }
                    $("#home_banner").html(slider_banner);
                    $('#home_banner').slick({
                        arrows: true,
                        prevArrow: '<button type="button" class="slick-prev">Previous</button>', // Custom previous button
                        nextArrow: '<button type="button" class="slick-next">Next</button>', // Custom next button
                        centerPadding: "0px",
                        dots: true,
                        slidesToShow: 1,
                        infinite: false,
                        autoplay: true,
                        autoplaySpeed: 4000, // Change banner every 5 seconds
                        speed: 500, // Slide transition speed
                        responsive: [{
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 1,
                                infinite: false,
                                autoplay: true,
                                autoplaySpeed: 4000, // Change banner every 5 seconds
                                speed: 500, // Slide transition speed
                            }
                        }, {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                infinite: false,
                                autoplay: true,
                                autoplaySpeed: 4000, // Change banner every 5 seconds
                                speed: 500, // Slide transition speed
                            }
                        }]
                    });
                }
                if (response.data.home_banner_mobile != null) {
                    for (let a = 0; a < response.data.home_banner_mobile.length; a++) {
                        if(response.data.home_banner[a]['banner_type'] == 'video'){
                            slider_banner_mobile = slider_banner_mobile.concat("<div><video muted autoplay loop><source src=" + response.data.home_banner_mobile[a]['banner_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png' type='video/mp4'></video></div>");
                        }else{
                            slider_banner_mobile = slider_banner_mobile.concat("<div><a href='" + response.data.home_banner_mobile[a]['banner_link'] +
                            "'><img src=" + response.data.home_banner_mobile[a]['banner_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                    }
                    $("#home_banner_mobile").html(slider_banner_mobile);
                    $('#home_banner_mobile').slick({
                        arrows: false,
                        centerPadding: "0px",
                        dots: true,
                        slidesToShow: 1,
                        infinite: false,
                        autoplay: true,
                        autoplaySpeed: 4000, // Change banner every 5 seconds
                        speed: 500, // Slide transition speed
                        responsive: [{
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 1,
                                infinite: false,
                                autoplay: true,
                                autoplaySpeed: 4000, // Change banner every 5 seconds
                                speed: 500, // Slide transition speed
                            }
                        }, {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                infinite: false,
                                autoplay: true,
                                autoplaySpeed: 4000, // Change banner every 5 seconds
                                speed: 500, // Slide transition speed
                            }
                        }]
                    });
                }
            }
        },
        "error": function(response) {
            console.log(response.errors);
        }
    });
}

function get_advertise_top(api_url_prefix) {
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "is_active_only": "1",
        "banner_type": "side_banner",
        "search_keyword": "",
        "limit": "10",
        "page_no": "0",
        "sort_column": "banner_srno",
        "sort_order": "ASC"
    };
    var slider_banner = "";
    var slider_banner_mobile = "";
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-advertise_top',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {
                if (response.data.ad_banner != null) {
                    var srnno=1;
                    for (let a = 0; a < response.data.ad_banner.length; a++) {
                        if(srnno==1)
                        {
                        slider_banner = slider_banner.concat("<div class='ad_image col-first srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                        if(srnno==2)
                        {
                            slider_banner = slider_banner.concat("<div class='col-second'><div class='ad_image desk srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                        if(srnno==3)
                        {
                            slider_banner = slider_banner.concat("<div class='col-sub'><div class='ad_image desk srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                        if(srnno==4)
                        {
                            slider_banner = slider_banner.concat("<div class='ad_image desk srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div></div></div>");
                        }
                        if(srnno==5)
                        {
                            slider_banner = slider_banner.concat("<div class='col-third'><div class='ad_image desk srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                        if(srnno==6)
                        {
                            slider_banner = slider_banner.concat("<div class='ad_image desk srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div></div>");
                        }

                            srnno++;
                    }
                    $("#advertise_top").html(slider_banner);
                }

                /*if (response.data.ad_banner_mobile != null) {
                    var srnno=1;
                    for (let a = 0; a < response.data.ad_banner_mobile.length; a++) {
                        slider_banner_mobile = slider_banner_mobile.concat("<div class='ad_image srno-"+srnno+"'><a href='" + response.data.ad_banner_mobile[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner_mobile[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                            srnno++;
                    }
                    $("#advertise_top_mobile").html(slider_banner_mobile);
                }*/
            }
        },
        "error": function(response) {
            console.log(response.errors);
        }
    });
}

function get_advertise_bottom(api_url_prefix) {
    var json_request = {
        "oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
        "is_active_only": "1",
        "banner_type": "side_banner",
        "search_keyword": "",
        "limit": "10",
        "page_no": "0",
        "sort_column": "banner_srno",
        "sort_order": "ASC",
        "zipcode": Cookies.get("zipcode"),
    };
    var slider_banner = "";
    var slider_banner_mobile = "";
    $.ajax({
        "type": "POST",
        "url": api_url_prefix + 'get-advertise_bottom',
        "data": JSON.stringify(json_request),
        "dataType": "JSON",
        "success": function(response) {
            if (response.data != null) {
                if (response.data.ad_banner != null) {
                    
                    var srnno=1;
                    for (let a = 0; a < response.data.ad_banner.length; a++) {
                        if(srnno==1)
                        {
                            slider_banner = slider_banner.concat("<div class='ad_image srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                        if(srnno==2)
                        {
                            slider_banner = slider_banner.concat("<div class='advertise_bottom_container'><div class='ad_image srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                        }
                        if(srnno==3)
                        {
                            slider_banner = slider_banner.concat("<div class='ad_image srno-"+srnno+"'><a href='" + response.data.ad_banner[a]['ad_link'] +
                            "'><img src=" + response.data.ad_banner[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div></div>");
                        }

                            srnno++;
                    }
                    $("#advertise_bottom").html(slider_banner);
                    
                }
                // if (response.data.ad_banner_mobile != null) {
                //     var srnno=1;
                //     for (let a = 0; a < response.data.ad_banner_mobile.length; a++) {
                //         slider_banner_mobile = slider_banner_mobile.concat("<div class='ad_image srno-"+srnno+"'><a href='" + response.data.ad_banner_mobile[a]['ad_link'] +
                //             "'><img src=" + response.data.ad_banner_mobile[a]['ad_image'] + " onerror=this.src='<?php echo BASE_URL; ?>assets/images/logo-2.png'></a></div>");
                //             srnno++;
                //     }
                //     $("#advertise_bottom_mobile").html(slider_banner_mobile);
                // }
            }
        },
        "error": function(response) {
            console.log(response.errors);
        }
    });
}

</script>