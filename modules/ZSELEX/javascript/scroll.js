jQuery().ready(function () {
    var $scrollingDiv = jQuery("#editImage");
    jQuery(window).scroll(function () {
        $scrollingDiv
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });

    var $cropDiv = jQuery("#cropImage");
    jQuery(window).scroll(function () {
        $cropDiv
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });
});
