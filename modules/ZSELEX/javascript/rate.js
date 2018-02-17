
//$.noConflict();
jQuery.noConflict();
jQuery(function() {
    var shopId = document.getElementById('shop_id').value;
    //alert(shopId);
    jQuery(".star").live("mouseover", function() { //SELECTING A STAR
        jQuery(".rating").hide(); //HIDES THE CURRENT RATING WHEN MOUSE IS OVER A STAR
        var d = jQuery(this).attr("id"); //GETS THE NUMBER OF THE STAR
        //HIGHLIGHTS EVERY STAR BEHIND IT
        for (i = (d - 1); i >= 0; i--) {
            jQuery(".transparent .star:eq(" + i + ")").css({
                "opacity": "1.0"
            });
        }
    }).live("click", function() { //RATING PROCESS

        jQuery(".userRated").html('');
        var the_id = jQuery("#id").val(); //GETS THE ID OF THE CONTENT
        var rating = jQuery(this).attr("id"); //GETS THE NUMBER OF THE STAR
        var data = 'rating=' + rating + '&shop_id=' + shopId; //ID OF THE CONTENT
        var e;
        var lang = jQuery('#curr_lang').val();
        //alert(lang);
        jQuery.ajax({
            type: "POST",
            data: data,
            // url: "rate.php", //CALLBACK FILE
            url: "index.php?module=ZSELEX&type=ajax&func=rate&lang=" + lang, //CALLBACK FILE
            success: function(e) {
                //alert('hiiii');
                jQuery(".ajax").html(e); //DISPLAYS THE NEW RATING IN HTML
            }
        });
    }).live("mouseout", function() { //WHEN MOUSE IS NOT OVER THE RATING
        jQuery(".rating").show(); //SHOWS THE CURRENT RATING
        jQuery(".transparent .star").css({
            "opacity": "0.25"
        }); //TRANSPARENTS THE BASE
    });
});
//jQuery.noConflict();