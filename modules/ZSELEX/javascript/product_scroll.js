jQuery().ready(function () {
  var $scrollingDiv = jQuery("#editProduct");

    jQuery(window).scroll(function () {
        $scrollingDiv
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });


    var $scrollingDivCat = jQuery("#editCategory");
    jQuery(window).scroll(function () {
        $scrollingDivCat
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });



    var $scrollingDivManuf = jQuery("#editManufacturer");
    jQuery(window).scroll(function () {
        $scrollingDivManuf
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });



    var $scrollingDivViewManu = jQuery(".manage_content");
    jQuery(window).scroll(function () {
        $scrollingDivViewManu
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });

    var $scrollingDivCopy = jQuery("#showProducts");
    jQuery(window).scroll(function () {
        $scrollingDivCopy
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });

    });

