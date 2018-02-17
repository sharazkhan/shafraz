jQuery().ready(function () {

    var $scrollingDiv = jQuery("#editEvent");

    jQuery(window).scroll(function () {
        //alert(jQuery("#editEvent").height());
        //jQuery("#sample").val(jQuery("#editEvent").height());
        //jQuery("#editEvent").css('height', jQuery("#editEvent").height()+"px");
        $scrollingDiv
                .stop()
                .animate({
                    "marginTop": (jQuery(window).scrollTop() + 50) + "px"
                }, "fast");
    });
});
