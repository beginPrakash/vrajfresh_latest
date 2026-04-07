jQuery(document).ready(function ($) {
    /************Banner & reviews*********************/
    jQuery().ready(function () {
        // jQuery('.slick-carousel').slick({
        //     arrows: false,
        //     centerPadding: "0px",
        //     dots: true,
        //     slidesToShow: 1,
        //     infinite: false,
        //     responsive: [{
        //             breakpoint: 991,
        //             settings: {
        //                 slidesToShow: 3,
        //             }
        //         },
        //         {
        //             breakpoint: 767,
        //             settings: {
        //                 slidesToShow: 1,
        //             }
        //         }
        //     ]
        // });
        jQuery('.category').slick({
            arrows: false,
            centerPadding: "0px",
            dots: true,
            slidesToShow: 7,
            infinite: false,
            responsive: [{
                breakpoint: 991,
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
        //  jQuery('.product-grid, .product-grid-2').slick({
        //		dots: false,
        //		arrows: false,
        //		infinite: true,
        //		speed: 300,
        //		slidesToShow: 5,
        //		slidesToScroll: 5,
        //		  responsive: [
        //      {
        //        breakpoint: 991,
        //        settings: {
        //          slidesToShow: 3,
        //        }
        //      },
        //      {
        //        breakpoint: 767,
        //        settings: {
        //          slidesToShow: 2,
        //        }
        //      }
        //    ]
        //  });
        // jQuery('.popular-items, .popular-items-2').slick({
        //     dots: true,
        //     arrows: false,
        //     infinite: true,
        //     speed: 300,
        //     slidesToShow: 5,
        //     slidesToScroll: 5,
        //     responsive: [{
        //             breakpoint: 991,
        //             settings: {
        //                 slidesToShow: 3,
        //             }
        //         },
        //         {
        //             breakpoint: 767,
        //             settings: {
        //                 slidesToShow: 2,
        //             }
        //         }
        //     ]
        // });
        // jQuery('.discover').slick({
        //     dots: true,
        //     arrows: false,
        //     infinite: true,
        //     speed: 300,
        //     slidesToShow: 3,
        //     slidesToScroll: 3,
        //     responsive: [{
        //             breakpoint: 991,
        //             settings: {
        //                 slidesToShow: 3,
        //             }
        //         },
        //         {
        //             breakpoint: 767,
        //             settings: {
        //                 slidesToShow: 2,
        //             }
        //         }
        //     ]
        // });
        // jQuery('.brand').slick({
        //     dots: true,
        //     arrows: false,
        //     infinite: true,
        //     speed: 300,
        //     slidesToShow: 7,
        //     slidesToScroll: 7,
        //     responsive: [{
        //             breakpoint: 991,
        //             settings: {
        //                 slidesToShow: 3,
        //             }
        //         },
        //         {
        //             breakpoint: 767,
        //             settings: {
        //                 slidesToShow: 2,
        //             }
        //         }
        //     ]
        // });
    });
    /************Body-add-class*********************/
    jQuery(".hamburg-categories").click(function () {
        jQuery("body").addClass("open-categories");
    });
    jQuery(".close-categories").click(function () {
        jQuery("body").removeClass("open-categories");
    });
    /************mobile-menu*********************/
    jQuery(".mobile-menu").click(function () {
        jQuery("body").addClass("open-mobile-menu");
    });
    jQuery(".close-menu").click(function () {
        jQuery("body").removeClass("open-mobile-menu");
    });
});
jQuery(window).scroll(function () {
    if (jQuery(this).scrollTop() > 1) {
        jQuery('header').addClass("sticky");
    } else {
        jQuery('header').removeClass("sticky");
    }
});
jQuery(document).ready(function () {
    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > 100) {
            jQuery('#scroll').fadeIn();
        } else {
            jQuery('#scroll').fadeOut();
        }
    });
    jQuery('#scroll').click(function () {
        jQuery("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() > 100) {
            jQuery('#scroll').fadeIn();
        } else {
            jQuery('#scroll').fadeOut();
        }
    });
    jQuery('#scroll').click(function () {
        jQuery("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
    /************SVG-Color*********************/
    /************SVG-Color*********************/
    $(document).on('click', '.add', function () {
        if ($(this).prev().val() < 50) {
            $(this).prev().val(+$(this).prev().val() + 1);
        }
    });
    $(document).on('click', '.sub', function () {
        if ($(this).next().val() > 0) {
            if ($(this).next().val() > 0) $(this).next().val(+$(this).next().val() - 1);
        }
    });
    // jQuery(".vraj-login").on('click', function() {
    //     jQuery("body").addClass("open-login");
    //     jQuery("div#signup-popup").hide();
    //     jQuery("div#forgot-popup").hide();
    // });
    var urlParams = new URLSearchParams(window.location.search);
    console.log('login_url');
    if (urlParams.has('login')) {
        jQuery("body").addClass("open-login");
        jQuery("div#signup-popup").hide();
        jQuery("div#forgot-popup").hide();
    }

    jQuery("#vraj-btn-login").on('click', function () {
        jQuery("body").addClass("open-login");
        jQuery("div#signup-popup").hide();
        jQuery("div#forgot-popup").hide();
    });

    jQuery(".vraj-signup").on('click', function () {
        jQuery("body").removeClass("open-login");
        jQuery("div#login-popup").hide();
        jQuery("body").addClass("open-login");

        jQuery("div#signup-popup").show();
    });
    jQuery("#vraj-login-1").on('click', function () {
        jQuery("div#signup-popup").hide();
        jQuery("div#login-popup").show();
        jQuery("div#forgot-popup").hide();
    });
    jQuery(".forgot").on('click', function () {
        jQuery("div#signup-popup").hide();
        jQuery("div#login-popup").hide();
        jQuery("div#forgot-popup").show();
    });
    jQuery(".forgot-new").on('click', function () {
        jQuery("body").addClass("open-login");
        jQuery("div#signup-popup").hide();
        jQuery("div#login-popup").hide();
        jQuery("div#forgot-popup").show();
    });
    jQuery(".close-vraj-login").on('click', function () {
        jQuery("body").removeClass("open-login");
    });
    /************login-End*********************/

    jQuery("#login-click").on("click", function (event) {
        jQuery(this).next().slideToggle("active");
    });
    /************login-End*********************/

    jQuery("#login-click").on("click", function (event) {
        jQuery(this).next().slideToggle("active");
    });


});
setTimeout(() => {
    jQuery('#price-filter li').on("click", function () {
        var tab_id = jQuery(this).attr('data-tab');
        var variant_price = jQuery(this).attr('data-price');

        jQuery('.price-filter li').removeClass('price-current');
        jQuery('.price-filter-content').removeClass('price-current');
        jQuery(this).addClass('price-current');
        jQuery("#" + tab_id).addClass('price-current');
        jQuery()
        jQuery("#variant-price").text('$' + variant_price);
        var img = jQuery(this).attr('data-url');
        jQuery("div.slick-track img").attr("src", img);
    });
    jQuery(document).ready(function () {
        jQuery("button#view_cart").hide();
        // jQuery("#zoom").ezPlus();
        jQuery('.ex1').zoom();
    });
    hideProgress('div#spinner');
}, 2000);

jQuery(document).ready(function () {
    if (jQuery(window).width() < 768) {
        setTimeout(() => {
            jQuery('.but').on("click", function () {
                jQuery('.submenu').not(jQuery(this).next()).slideUp(300);
                jQuery(this).next('.submenu').stop().slideToggle(300);
            });
        }, 5000);
    }
});
