jQuery(document).ready(function($) {
    /************Banner & reviews*********************/
    jQuery().ready(function() {
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
        jQuery('.popular-items, .popular-items-2').slick({
            dots: true,
            arrows: false,
            infinite: true,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
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
        jQuery('.discover').slick({
            dots: true,
            arrows: false,
            infinite: true,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 3,
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
        jQuery('.brand').slick({
            dots: true,
            arrows: false,
            infinite: true,
            speed: 300,
            slidesToShow: 7,
            slidesToScroll: 7,
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
    });
    /************Body-add-class*********************/
    jQuery(".hamburg-categories").click(function() {
        jQuery("body").addClass("open-categories");
    });
    jQuery(".close-categories").click(function() {
        jQuery("body").removeClass("open-categories");
    });
    /************Menu*********************/

});
jQuery(window).scroll(function() {
    if (jQuery(this).scrollTop() > 1) {
        jQuery('header').addClass("sticky");
    } else {
        jQuery('header').removeClass("sticky");
    }
});
jQuery(document).ready(function() {
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > 100) {
            jQuery('#scroll').fadeIn();
        } else {
            jQuery('#scroll').fadeOut();
        }
    });
    jQuery('#scroll').click(function() {
        jQuery("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > 100) {
            jQuery('#scroll').fadeIn();
        } else {
            jQuery('#scroll').fadeOut();
        }
    });
    jQuery('#scroll').click(function() {
        jQuery("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
    /************SVG-Color*********************/
    /************SVG-Color*********************/
});